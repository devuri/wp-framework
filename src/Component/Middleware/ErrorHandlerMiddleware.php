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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\ErrorHandler\Debug;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorHandlerMiddleware extends AbstractMiddleware
{
    /**
     * @var string
     */
    private $errorHandler;

    /**
     * @var string
     */
    private $environment;

    /**
     * Constructor to inject the logger.
     *
     * @param string $errorHandler
     */
    public function __construct(string $errorHandler = 'oops', ?string $environment = null)
    {
        $this->errorHandler = $errorHandler;
        $this->environment = $environment;
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
        $this->when();

        $this->setErrorHandler($request);

        return $handler->handle($request);
    }

    /**
     * @return static
     */
    public function setErrorHandler(ServerRequestInterface $request): void
    {
        if ( ! $this->enableErrorHandler()) {
            return;
        }

        if (\is_null($this->errorHandler)) {
            return;
        }

        if ( ! \in_array($this->environment, [ 'debug', 'development', 'dev', 'local' ], true)) {
            return;
        }

        if ('symfony' === $this->errorHandler) {
            Debug::enable();
        } elseif ('oops' === $this->errorHandler) {
            $whoops = new Run();
            $whoops->pushHandler(new PrettyPageHandler());
            $whoops->register();
        }
    }

    protected function enableErrorHandler(): bool
    {
        if ($this->errorHandler) {
            return true;
        }

        return false;
    }
}
