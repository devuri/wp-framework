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

use Throwable;

trait ErrorTrait
{
    /**
     * @param string $environment
     *
     * @return bool
     */
    protected static function isProd(string $environment): bool
    {
        if (\in_array($environment, [ 'secure', 'sec', 'production', 'prod' ], true)) {
            return true;
        }

        return false;
    }

    /**
     * @param Throwable $exception
     */
    protected function getExceptionOutput(Throwable $exception)
    {
        return \sprintf(
            "%s: %s in file %s on line %d",
            \get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }
}
