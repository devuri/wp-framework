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
use Dotenv\Exception\InvalidPathException;
use Exception;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Filesystem\Filesystem;
use WPframework\EnvType;
use WPframework\Terminate;

class DotenvMiddleware extends AbstractMiddleware
{
    protected $envType;

    public function __construct()
    {
        if ( ! \defined('APP_DIR_PATH')) {
            throw new InvalidArgumentException('Error: APP_DIR_PATH is not setup', 1);
        }

        if ( ! \defined('APP_HTTP_HOST')) {
            throw new InvalidArgumentException('Error: APP_HTTP_HOST is not setup', 2);
        }

        if ( ! \defined('SITE_CONFIGS_DIR')) {
            throw new InvalidArgumentException('Error: SITE_CONFIGS_DIR is not setup', 3);
        }

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
        $envFiles = $this->envType->filterFiles(
            EnvType::supportedFiles(),
            APP_DIR_PATH
        );

        $_dotenv = Dotenv::createImmutable(APP_DIR_PATH, $envFiles);

        try {
            $_dotenv->load();
        } catch (InvalidPathException $e) {
            $this->envType->tryRegenerateFile(APP_DIR_PATH, APP_HTTP_HOST, $envFiles);

            throw new InvalidPathException($e->getMessage());
        }

        self::validateTenantdB($_dotenv);

        $request = $request->withAttribute('envFiles', $envFiles);

        return $handler->handle($request);
    }

    protected function validateTenantdB($_dotenv): void
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
