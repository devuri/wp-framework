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
use WPframework\Support\ConstantBuilder;
use WPframework\Support\SiteManager;
use WPframework\Support\Switcher;

class ConfigMiddleware extends AbstractMiddleware
{
    private $configManager;
    private $siteManager;

    /**
     * @param ConstantBuilder $configManager
     */
    public function __construct(ConstantBuilder $configManager)
    {
        $this->configManager = $configManager;
        $this->siteManager = new SiteManager($this->configManager);
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->siteManager->setSwitcher(
            new Switcher($this->configManager)
        );

        $this->siteManager->appSetup($request)->constants();

        $this->configManager->setMap();

        return $handler->handle($request);
    }
}
