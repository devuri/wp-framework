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
     * Process the request and handle any exceptions.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->whoops = $this->services->get('whoops');

        $outputHandler = new ErrHandler($this->services->get('logger'));
        $this->whoops->pushHandler($outputHandler);

        $this->whoops->allowQuit(false);
        $this->whoops->register();

        return $handler->handle($request);
    }
}
