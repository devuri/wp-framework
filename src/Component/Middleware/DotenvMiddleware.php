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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Filesystem\Filesystem;
use WPframework\EnvType;
use WPframework\Support\Tenancy;

class DotenvMiddleware extends AbstractMiddleware
{
    protected $envType;

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
        $envFiles = $this->envType->filterFiles(
            EnvType::supportedFiles(),
            APP_DIR_PATH
        );

        $_dotenv = Dotenv::createImmutable(APP_DIR_PATH, $envFiles, true);

        // Tenancy
        $this->tenantSetup()->init($_dotenv);

        try {
            $_dotenv->load();
        } catch (InvalidPathException $e) {
            $this->envType->tryRegenerateFile(APP_DIR_PATH, APP_HTTP_HOST, $envFiles);
            $this->log()->info("Missing env file: {$e->getMessage()}");
        } catch (Exception $e) {
            $this->log()->error("Exception: {$e->getMessage()}");
        }

        return $handler->handle($request);
    }

    /**
     * Bootstrap multitenancy.
     */
    protected function tenantSetup(): Tenancy
    {
        return new Tenancy(APP_DIR_PATH, SITE_CONFIGS_DIR);
    }
}
