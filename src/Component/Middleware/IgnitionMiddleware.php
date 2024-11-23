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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Filesystem\Filesystem;
use WPframework\Config;
use WPframework\EnvType;

class IgnitionMiddleware extends AbstractMiddleware
{
    protected $envType;
    private $appPath;
    private $tenant;
    private $config;

    public function __construct()
    {
        $this->envType = new EnvType(new Filesystem());
    }

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

        if ( ! $this->tenant) {
            return $handler->handle($request);
        }

        $this->config  = new Config();
        $tenantConfigPath = $this->config->getConfigsDir() . '/' . $this->tenant['uuid'];
        $envFiles = $this->envType->filterFiles(
            EnvType::supportedFiles(),
            $tenantConfigPath
        );

        // maybe create env file.
        $this->envType->tryRegenerateFile(
            $tenantConfigPath,
            APP_HTTP_HOST,
            $envFiles
        );

        // set env values.
        $_dotenv = Dotenv::createImmutable($tenantConfigPath, $envFiles, true);
        $_dotenv->load();

        return $handler->handle($request);
    }
}
