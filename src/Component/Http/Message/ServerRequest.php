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

use Nyholm\Psr7\ServerRequest as Psr7ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

// @phpstan-ignore-next-line
class ServerRequest extends Psr7ServerRequest implements ServerRequestInterface
{
    // Add any custom methods or overrides here (if needed)
}
