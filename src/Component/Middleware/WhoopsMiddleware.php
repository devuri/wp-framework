<?php

declare(strict_types=1);

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Whoops\Run;
use WPframework\Error\ErrorHandler;
use WPframework\Support\Configs;

class WhoopsMiddleware extends AbstractMiddleware
{
    /**
     * @var Run
     */
    private $whoops;

    /**
     * Process the request and handle any exceptions via Whoops.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->whoops = $this->services->get('whoops');
        $defaults     = self::defaults();

        // Merge default and user-defined error handler settings.
        $errorHandlerConfig = self::setErrorHandler($this->configs->app());

        // Extract config options.
        $errorHandlerClass = $errorHandlerConfig['class'] ?? ErrorHandler::class;
        $allowQuit         = $errorHandlerConfig['quit'] ?? true;
        $logsEnabled       = $errorHandlerConfig['logs'] ?? true;

        if (class_exists($errorHandlerClass)) {
            $whoopsHandler = new $errorHandlerClass();
        } else {
            $whoopsHandler = new ErrorHandler();
        }

        // Never use PrettyPageHandler in production.
        if ($this->configs::isInProdEnvironment() && $whoopsHandler instanceof \Whoops\Handler\PrettyPageHandler) {
            $whoopsHandler = new ErrorHandler();
        }

        // Must be an instance of AbstractError or Whoops\Handler\HandlerInterface.
        $this->whoops->pushHandler($whoopsHandler);

        // Configure Whoops behavior.
        $this->whoops->allowQuit($allowQuit);
        // If you want to wire up logs based on $logsEnabled, do it here.
        // e.g., if ($logsEnabled) { /* integrate with a logging handler */ }

        // Register Whoops.
        $this->whoops->register();

        return $handler->handle($request);
    }

    /**
     * Merge default error-handler settings with project configs.
     *
     * @param Configs $cfgs
     *
     * @return array
     */
    protected static function setErrorHandler(Configs $cfgs): array
    {
        $appErrorHandlerConfig = $cfgs->config['app']->get('error_handler', []);

        return array_merge(
            [
                'class'   => ErrorHandler::class,
                'quit'    => true,
                'logs'    => true,
            ],
            $appErrorHandlerConfig
        );
    }

    /**
     * Return the namespaced class of the requested error handler type,
     * or null if not found.
     *
     * @param null|string $key
     *
     * @return null|string
     */
    private static function defaults(?string $key = null): ?string
    {
        $errorHandlers = [
            'error'  => ErrorHandler::class,
            'text'   => \WPframework\Error\TextHandler::class,
            'json'   => \Whoops\Handler\JsonResponseHandler::class,
            'plain'  => \Whoops\Handler\PlainTextHandler::class,
            'pretty' => \Whoops\Handler\PrettyPageHandler::class,
        ];

        if (null !== $key && isset($errorHandlers[$key])) {
            return $errorHandlers[$key];
        }

        return null;
    }
}
