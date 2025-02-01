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

namespace WPframework\Exceptions;

use RuntimeException;

/**
 * Exception thrown when attempting to define() a constant that has already been defined.
 *
 * This exception is thrown when a user tries to define a PHP constant using the define() function,
 * but the constant has already been defined elsewhere in the code.
 */
class ConstantAlreadyDefinedException extends RuntimeException
{
    // Class implementation goes here
}
