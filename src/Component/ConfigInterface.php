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

interface ConfigInterface
{
    public function set(string $key, $value): void;
    public function get(string $key, $default = null);
}
