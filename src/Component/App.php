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

namespace WPframework;

use Pimple\Container as PimpleContainer;
use Pimple\Psr11\Container as PsrContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use WPframework\Exceptions\HttpException;
use WPframework\Http\Message\Response;
use WPframework\Middleware\Handlers\FinalHandler;
use WPframework\Middleware\Handlers\MiddlewareDispatcher;
use WPframework\Middleware\Handlers\MiddlewareRegistry;
use WPframework\Support\Configs;
use WPframework\Support\Str;

class App implements RequestHandlerInterface
{
    /**
     * @var PimpleContainer
     */
    protected $container;

    /**
     * @var PsrContainer
     */
    protected $psrContainer;

    /**
     * @var Configs
     */
    protected $configs;

    /**
     * @var MiddlewareRegistry
     */
    protected $middlewareRegistry;

    /**
     * @var array
     */
    protected $middlewareFilter;

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
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var SapiEmitter
     */
    protected $emitter;

    /**
     * @var ServerRequestInterface
     */
    protected $finalRequest;

    /**
     * App constructor.
     */
    public function __construct(ServerRequestInterface $request, ?Bindings $containerBindings)
    {
        $this->container = $containerBindings->getContainer();
        $this->psrContainer = $containerBindings->getPsrContainer();
        $this->configs   = $this->psrContainer->get('configs');
        $this->request   = $request;

        $this->defaultHandler = new FinalHandler();

        $this->errorHandler = function (Throwable $e, ServerRequestInterface $request, ResponseInterface $response) {
            $this->exception =  $e;

            $this->response = (new Response())->withStatus(
                $this->exception->getCode(),
                $this->exception->getMessage()
            );

            return $this->response;
        };

        $this->emitter = new SapiEmitter();
    }

    public function getContainer(): PimpleContainer
    {
        return $this->container;
    }

    /**
     * Add a binding to the container.
     *
     * @param string   $key
     * @param callable $binding
     *
     * @return PimpleContainer
     */
    public function registerService(string $key, callable $binding): PimpleContainer
    {
        $this->container[$key] = $binding;

        return $this->container;
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
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request->withAttribute('isProd', Configs::isInProdEnvironment());

        // middlewares are loaded from configs/middleware.php
        $this->middlewareRegistry = new MiddlewareRegistry($this->psrContainer, $this->middlewareFilter);

        try {
            $middlewareHandler = new MiddlewareDispatcher(
                $this->defaultHandler,
                $this->psrContainer,
                $this->middlewareRegistry
            );

            return $middlewareHandler->handle($this->request);
        } catch (Throwable $e) {
            $e = $this->httpException($e);

            return $this->handleException($e, $this->request);
        }
    }

    /**
     * Filter middlewares.
     *
     * @return void
     */
    public function filter(array $middlewareFilter): void
    {
        $this->middlewareFilter = $middlewareFilter;
    }

    public function run(): void
    {
        $response = $this->handle($this->request);
        $this->finalRequest = $this->defaultHandler->getFinalRequest();
        $this->emitResponse($response);
    }

    /**
     * Method to handle exceptions using the defined error handler.
     *
     * @param Throwable              $except
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    protected function handleException(Throwable $except, ServerRequestInterface $request): ResponseInterface
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
        $contentType = $response->getHeaderLine('Content-Type');

        if (false === headers_sent()) {
            $this->emitter->emitHeaders($response);
            http_response_code($response->getStatusCode());
        }

        if ($this->exception) {
            $this->terminateWithException($this->exception, $response);
        }

        if (Str::contains($contentType, 'application/json')) {
            $this->emitter->emitBody($response);
            exit;
        }

        if (Str::contains($contentType, 'text/html')) {
            $this->emitter->emitBody($response);
            exit;
        }

        if ($this->finalRequest && $this->finalRequest->getAttribute('isRoute', false)) {
            $this->emitter->emitBody($response);
            exit;
        }
    }

    /**
     * Handles exception termination with context.
     *
     * @param Throwable         $exception
     * @param ResponseInterface $response
     */
    protected function terminateWithException(Throwable $exception, ResponseInterface $response): void
    {
        Terminate::exit(
            $exception,
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            [
                'host' => $this->request->getUri()->getHost(),
                'path' => $this->request->getUri()->getPath(),
                'query' => $this->request->getUri()->getQuery(),
            ]
        );
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

        return new HttpException($ex->getMessage(), 500, $statusCode, $ex);
    }
}
