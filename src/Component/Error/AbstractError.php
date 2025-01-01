<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Error;

use Whoops\Handler\Handler;

// @see https://github.com/filp/whoops/blob/master/src/Whoops/Handler/HandlerInterface.php
// @see https://github.com/filp/whoops/blob/master/src/Whoops/Handler/Handler.php
abstract class AbstractError extends Handler
{
    use ErrorTrait;
}
