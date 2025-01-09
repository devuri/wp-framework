<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware;

use Dotenv\Dotenv;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WPframework\EnvType;
use WPframework\Terminate;

class IgnitionMiddleware extends AbstractMiddleware
{
    protected ?EnvType $envType;
    private $tenant;
    private $configs;

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->tenant = $request->getAttribute('tenant', false);
        $this->isMultitenant = $request->getAttribute('isMultitenant', false);

        if (! $this->tenant) {
            return $handler->handle($request);
        }

        $this->envType = $this->services->get('env_type');
        $this->configs = $this->services->get('configs');
        $tenantConfigPath = $this->configs->getConfigsDir() . '/' . $this->tenant['uuid'];
        $envFiles = $this->envType->filterFiles(
            EnvType::supportedFiles(),
            $tenantConfigPath
        );

        // maybe create env file.
        $this->envType->tryRegenerateFile(
            $tenantConfigPath,
            APP_HTTP_HOST,
            $envFiles,
            ($this->tenant['framework'] ?? null),
        );

        // set env values.
        $_dotenv = Dotenv::createImmutable($tenantConfigPath, $envFiles, true);
        $_dotenv->load();

        if ($this->isMultitenant) {
            self::validateTenantdB($_dotenv);
        }

        return $handler->handle($request);
    }

    protected function validateTenantdB(Dotenv $_dotenv): void
    {
        try {
            $_dotenv->required('LANDLORD_DB_HOST')->notEmpty();
            $_dotenv->required('LANDLORD_DB_NAME')->notEmpty();
            $_dotenv->required('LANDLORD_DB_USER')->notEmpty();
            $_dotenv->required('LANDLORD_DB_PASSWORD')->notEmpty();
            $_dotenv->required('LANDLORD_DB_PREFIX')->notEmpty();
        } catch (Exception $e) {
            Terminate::exit(new Exception('Landlord info is required for multi-tenant'), 403);
        }
    }
}
