<?php

declare(strict_types=1);

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Http\Message;

use Nyholm\Psr7\Request as NyholmRequest;
use Psr\Http\Message\RequestInterface;

// @phpstan-ignore-next-line
class Request extends NyholmRequest implements RequestInterface
{
    // Add any custom methods or overrides here (if needed)
}
