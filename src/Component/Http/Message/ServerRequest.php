<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Http\Message;

use Nyholm\Psr7\ServerRequest as NyholmServerRequest;
use Psr\Http\Message\ServerRequestInterface;

// @phpstan-ignore-next-line
class ServerRequest extends NyholmServerRequest
{
    private static ?ServerRequestInterface $request = null;

    /**
     * Create and return a PSR-7 ServerRequest instance.
     *
     * @return ServerRequestInterface
     */
    public static function create(): ServerRequestInterface
    {
        if (null === self::$request) {
            self::$request = self::createRequest(new RequestFactory());
        }

        return self::$request;
    }

    /**
     * Internal method to create a PSR-7 ServerRequest using the provided factory.
     *
     * @param RequestFactory $psr17Factory
     *
     * @return ServerRequestInterface
     */
    private static function createRequest(RequestFactory $psr17Factory): ServerRequestInterface
    {
        $requestCreator = Foundation::create(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        return $requestCreator->fromGlobals();
    }
}
