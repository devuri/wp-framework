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
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use WPframework\Exceptions\HttpException;
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
     * @var null|Throwable
     */
    protected $exception;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * AppInit constructor.
     */
    public function __construct(RequestInterface $request)
    {
        $this->defaultHandler = new FinalHandler();

        $this->errorHandler = function (Throwable $e, RequestInterface $request, ResponseInterface $response) {
            $this->exception =  $e;

            $this->response = (new Response())->withStatus(
                $this->exception->getCode(),
                $this->exception->getMessage()
            );

            return $this->response;
        };
    }

    /**
     * Add middleware to the application via MiddlewareRegistry.
     *
     * @param callable|MiddlewareInterface $middleware
     *
     * @return static
     */
    public function withMiddleware($middleware, string $key = ''): self
    {
        $this->middlewareRegistry->register($middleware, $key);

        return $this;
    }

    /**
     * Set the error handler middleware.
     *
     * @param callable $errorHandler
     *
     * @return static
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
     * @return static
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
                new MiddlewareRegistry()
            );

            return $middlewareHandler->handle($request);
        } catch (Throwable $e) {
            $e = $this->httpException($e);

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
     * @param Throwable        $except
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    protected function handleException(Throwable $except, RequestInterface $request): ResponseInterface
    {
        $this->exception = $except;

        $this->response = (new Response())->withStatus(
            $this->exception->getCode(),
            $this->exception->getMessage()
        );

        return ($this->errorHandler)($this->exception, $request, $this->response);
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

        if ($this->exception) {
            Terminate::exit(
                $this->exception,
                $response->getStatusCode(),
                $response->getReasonPhrase()
            );
        }

        http_response_code($response->getStatusCode());
    }

    /**
     * Handles exceptions and ensures they are converted to an HTTP exception if necessary.
     *
     * This method checks the status code of the given exception and returns it directly
     * if the code is within the valid HTTP status code range (100-599). Otherwise, it creates
     * and returns a new `HttpException` with the message from the original exception.
     *
     * @param Throwable $ex The exception to process.
     *
     * @return Throwable An instance of the original exception if valid, or a new `HttpException` otherwise.
     */
    private function httpException(Throwable $ex)
    {
        $statusCode = $ex->getCode();

        if ($statusCode >= 100 && $statusCode <= 599) {
            return $ex;
        }

        return new HttpException($ex->getMessage());
    }
}
