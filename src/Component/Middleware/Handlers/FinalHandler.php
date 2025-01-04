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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WPframework\Http\Message\Response;

class FinalHandler implements RequestHandlerInterface
{
    protected $finalRequest;

    /**
     * Process the incoming request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @return Response
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->finalRequest = $request;

        return new Response();
    }

    public function getFinalRequest()
    {
        return $this->finalRequest;
    }
}
