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
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use WPframework\Http\Message\Response;

class WhoopsMiddleware implements MiddlewareInterface
{
    /**
     * @var Run
     */
    private $whoops;

    /**
     * @param Run $whoops
     */
    public function __construct(Run $whoops)
    {
        $this->whoops = $whoops;
    }

    /**
     * Process the request and handle any exceptions.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            return $this->handleException($request, $exception);
        }
    }

    /**
     * Handle the caught exception and return a response.
     *
     * @param ServerRequestInterface $request
     * @param Throwable              $exception
     *
     * @return ResponseInterface
     */
    private function handleException(ServerRequestInterface $request, Throwable $exception): ResponseInterface
    {
        $acceptHeader = $request->getHeaderLine('Accept');
        if (false !== strpos($acceptHeader, 'application/json')) {
            $jsonHandler = new JsonResponseHandler();
            $jsonHandler->setJsonApi(true);

            $this->whoops->pushHandler($jsonHandler);
        } else {
            $htmlHandler = new PrettyPageHandler();
            $this->whoops->pushHandler($htmlHandler);
        }

        ob_start();
        $this->whoops->handleException($exception);
        $output = ob_get_clean();

        $response = new Response(500);
        $response->getBody()->write($output);

        $contentType = false !== strpos($acceptHeader, 'application/json') ? 'application/json' : 'text/html';

        return $response->withHeader('Content-Type', $contentType);
    }
}
