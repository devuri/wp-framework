<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

use WPframework\Interfaces\ExitInterface;

class ExitHandler implements ExitInterface
{
    /**
     * @param mixed $status
     *
     * @return never
     */
    public function terminate($status = 0): void
    {
        exit($status);
    }
}
