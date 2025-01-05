<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware\Handlers;

use InvalidArgumentException;
use Pimple\Psr11\Container as PsrContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

class MiddlewareDispatcher implements RequestHandlerInterface
{
    /**
     * @var PsrContainer
     */
    protected $container;
    /**
     * @var array
     */
    private array $middlewareQueue = [];

    /**
     * @var RequestHandlerInterface
     */
    private RequestHandlerInterface $finalHandler;

    /**
     * @var null|LoggerInterface
     */
    private ?LoggerInterface $logger;

    /**
     * @var MiddlewareRegistry
     */
    private MiddlewareRegistry $middlewareRegistry;

    /**
     * MiddlewareDispatcher constructor.
     *
     * @param RequestHandlerInterface $finalHandler The final handler to invoke if no middleware processes the request.
     * @param null|LoggerInterface    $logger       Optional logger to log any errors in middleware.
     */
    public function __construct(RequestHandlerInterface $finalHandler, PsrContainer $container, MiddlewareRegistry $middlewareRegistry, ?LoggerInterface $logger = null)
    {
        $this->finalHandler = $finalHandler;
        $this->container = $container;
        $this->middlewareRegistry = $middlewareRegistry;
        $this->logger = $logger;
        $this->middlewareQueue = $this->middlewareRegistry->getRegisteredMiddlewares();
    }

    /**
     * Adds middleware to the queue.
     *
     * @param callable|MiddlewareInterface $middleware Middleware to add to the queue.
     *
     * @throws InvalidArgumentException If the provided middleware is not callable or does not implement MiddlewareInterface.
     */
    public function addMiddleware($middleware): void
    {
        if (! \is_callable($middleware) && ! $middleware instanceof MiddlewareInterface) {
            throw new InvalidArgumentException('Middleware must be callable or implement MiddlewareInterface.');
        }
        $this->middlewareQueue[] = $middleware;
    }

    /**
     * Handles the request by processing the middleware queue.
     *
     * @param ServerRequestInterface $request The incoming server request.
     *
     * @throws Throwable If an exception occurs during middleware processing.
     *
     * @return ResponseInterface The response from the middleware or final handler.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->middlewareQueue)) {
            return $this->finalHandler->handle($request);
        }

        $middleware = array_shift($this->middlewareQueue);

        if (\is_string($middleware)) {
            $middleware = new $middleware($this->container);
        }

        try {
            if ($middleware instanceof MiddlewareInterface) {
                return $middleware->process($request, $this->getNextHandler());
            }

            if (\is_callable($middleware)) {
                return $middleware($request, $this->getNextHandler());
            }

            throw new RuntimeException('Invalid middleware type.');
        } catch (Throwable $e) {
            if ($this->logger) {
                $this->logger->error('Error in middleware: ' . $e->getMessage(), ['exception' => $e]);
            }

            throw $e;
        }
    }

    /**
     * Creates a new instance of MiddlewareDispatcher with the remaining middleware queue.
     *
     * @return static A new handler with the remaining middleware.
     */
    private function getNextHandler(): self
    {
        $nextHandler = clone $this;
        $nextHandler->middlewareQueue = $this->middlewareQueue;

        return $nextHandler;
    }
}
