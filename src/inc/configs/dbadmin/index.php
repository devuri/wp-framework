<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use WPframework\Support\Configs;

// @codingStandardsIgnoreFile.
function adminer_object()
{
    class AdminerAuth extends Adminer
    {
        public function login($login, $password)
        {
            return true;
        }

        public function credentials()
        {
            return [
                env('DB_HOST'),
                env('DB_USER'),
                env('DB_PASSWORD'),
            ];
        }

        public function database()
        {
            return env('DB_NAME');
        }
    }

    return new AdminerAuth();
}

// load adminer.
Configs::dbAdminer();
