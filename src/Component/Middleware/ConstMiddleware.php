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
use WPframework\Support\Configs;

class ConstMiddleware extends AbstractMiddleware
{
    private $constManager;
    private $siteManager;

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->constManager = $this->services->get('const_builder');
        $this->siteManager = $this->services->get('site_manager');

        $this->siteManager->setSwitcher($this->services->get('switcher'));

        $this->siteManager->appSetup($request)->constants();

        $this->constManager->setMap();

        $request = $request->withAttribute('isProd', $this->isProd());

        return $handler->handle($request);
    }

    private function isProd(): bool
    {
        return Configs::isProd($this->siteManager->getEnvironment());
    }
}
