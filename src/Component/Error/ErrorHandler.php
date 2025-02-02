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

namespace WPframework\Error;

use WPframework\Terminate;

class ErrorHandler extends AbstractError
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $exception = $this->getException();

        // @phpstan-ignore-next-line
        Terminate::exit($exception);
    }
}
