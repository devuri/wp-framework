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
use WPframework\Support\KernelConfig;

class KernelMiddleware extends AbstractMiddleware
{
    /**
     * @var KernelConfig
     */
    private $kernelConfig;

    /**
     * Constructor to inject the response factory.
     *
     * @param KernelConfig $kernel
     */
    public function __construct(KernelConfig $kernelConfig)
    {
        $this->kernelConfig = $kernelConfig;
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
        $this->kernelConfig->setKernelConstants();

        return $handler->handle($request);
    }
}
