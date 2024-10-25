<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use WPframework\Http\Message\Response;
use WPframework\Middleware\Handlers\FinalHandler;
use WPframework\Middleware\Handlers\MiddlewareDispatcher;
use WPframework\Middleware\Handlers\MiddlewareRegistry;

class AppInit implements RequestHandlerInterface
{
    /**
     * @var MiddlewareRegistry
     */
    protected $middlewareRegistry;

    /**
     * @var null|callable
     */
    protected $defaultHandler;

    /**
     * @var null|callable
     */
    protected $errorHandler;

    /**
     * AppInit constructor.
     */
    public function __construct()
    {
        $this->middlewareRegistry = new MiddlewareRegistry();
        $this->defaultHandler = new FinalHandler();

        $this->errorHandler = function (Throwable $e, RequestInterface $request, ResponseInterface $response) {
            return $response->withStatus(500);
        };
    }

    /**
     * Add middleware to the application via MiddlewareRegistry.
     *
     * @param callable|MiddlewareInterface $middleware
     *
     * @return self
     */
    public function addMiddleware($middleware, string $key): self
    {
        $this->middlewareRegistry->register($middleware, $key);

        return $this;
    }

    /**
     * Set the error handler middleware.
     *
     * @param callable $errorHandler
     *
     * @return self
     */
    public function setErrorHandler(callable $errorHandler): self
    {
        $this->errorHandler = $errorHandler;

        return $this;
    }

    /**
     * Set the default handler that processes the final request if no other middleware modifies it.
     *
     * @param callable $defaultHandler
     *
     * @return self
     */
    public function setDefaultHandler(callable $defaultHandler): self
    {
        $this->defaultHandler = $defaultHandler;

        return $this;
    }

    /**
     * The PSR-15 compliant method to handle a request.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        try {
            $middlewareHandler = new MiddlewareDispatcher(
                $this->defaultHandler,
                $this->middlewareRegistry
            );

            return $middlewareHandler->handle($request);
        } catch (Throwable $e) {
            return $this->handleException($e, $request);
        }
    }

    /**
     * Start the application by handling the given request.
     *
     * @param RequestInterface $request
     *
     * @return void
     */
    public function run(RequestInterface $request): void
    {
        $response = $this->handle($request);
        $this->emitResponse($response);
    }

    /**
     * Method to handle exceptions using the defined error handler.
     *
     * @param Throwable        $exception
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    protected function handleException(Throwable $exception, RequestInterface $request): ResponseInterface
    {
        $response = new Response();

        return ($this->errorHandler)($exception, $request, $response);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return void
     */
    protected function emitResponse(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(\sprintf('%s: %s', $name, $value), false);
            }
        }

        http_response_code($response->getStatusCode());
    }
}
