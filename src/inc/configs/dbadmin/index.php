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

use WPframework\Support\Configs;

// @codingStandardsIgnoreFile.
function adminer_object()
{
    class AdminerAuth extends Adminer
    {
        protected $settings;

        public function __construct(array $args = [])
        {
            $this->settings = array_merge(
                [
                    'autologin' => false,
                    'db' => env('DB_NAME'),
                    'host' => env('DB_HOST', 'localhost'),
                    'username' => env('DB_USER', null),
                    'password' => env('DB_PASSWORD', ''),
                ],
                $args
            );
        }

        public function login($login, $password)
        {
            if (! $this->settings['autologin']) {
                return false;
            }

            return true;
        }

        public function credentials()
        {
            if (! $this->settings['autologin']) {
                return [SERVER, $_GET["username"], get_password()];
            }

            return [
                $this->settings['host'] ?? 'localhost',
                $this->settings['username'] ?? null,
                $this->settings['password'] ?? "",
            ];
        }

        public function database()
        {
            return $this->settings['db'];
        }

        public function head(): void
        {
            parent::head();
            echo '<script src="/asset/js/autologin.js"></script>';
        }
    }

    $cfgs = Configs::init()->app();

    return new AdminerAuth($cfgs->config['app']->get('dbadmin'));
}

// load adminer.
Configs::dbAdminer();
