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

use Whoops\Handler\PlainTextHandler;

class TextHandler extends PlainTextHandler
{
    use ErrorTrait;

    /**
     * @return never
     */
    public function handle()
    {
        $exception = $this->getException();

        if (! self::isProd(env('ENVIRONMENT_TYPE'))) {
            exit($this->getExceptionOutput($exception));
        }
        exit($exception->getMessage());
    }
}
