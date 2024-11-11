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
use Whoops\Run;
use WPframework\Support\ErrHandler;

class WhoopsMiddleware extends AbstractMiddleware
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

        $outputHandler = new ErrHandler($this->log());
        $this->whoops->pushHandler($outputHandler);

        $this->whoops->allowQuit(false);
        $this->whoops->register();
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
        return $handler->handle($request);
    }
}
