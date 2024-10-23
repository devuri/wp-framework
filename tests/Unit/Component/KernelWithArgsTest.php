<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Component;

use WPframework\Kernel;
use WPframework\Tests\BaseTest;

/**
 * @group WPframework
 *
 * @covers \WPframework\Kernel
 *
 * @internal
 */
class KernelWithArgsTest extends BaseTest
{
    /**
     * Test that getSettings returns the expected configuration array.
     */
    public function test_get_settings_returns_expected_array(): void
    {
        $app_with_args = new Kernel(getenv('FAKE_APP_DIR_PATH'), [
            'directory' => [
                'wp_dir_path'   => 'cms',
                'web_root_dir' => 'public',
                'content_dir'   => 'wp-content',
                'plugin_dir'    => 'wp-content/plugins',
                'mu_plugin_dir' => 'wp-content/mu-plugins',
                'sqlite_dir'    => 'sqlitedb',
                'sqlite_file'   => '.sqlite-wpdatabase',
                'theme_dir'     => 'templates',
                'asset_dir'     => 'asset',
                'publickey_dir' => 'pubkeys',
            ],
            'default_theme' => 'brisko',
        ]);

        $expected = [
            'directory'      => [
                'wp_dir_path'   => 'cms',
                'web_root_dir'  => 'public',
                'content_dir'   => 'wp-content',
                'plugin_dir'    => 'wp-content/plugins',
                'mu_plugin_dir' => 'wp-content/mu-plugins',
                'sqlite_dir'    => 'sqlitedb',
                'sqlite_file'   => '.sqlite-wpdatabase',
                'theme_dir'     => 'templates',
                'asset_dir'     => 'asset',
                'publickey_dir' => 'pubkeys',
            ],
            'default_theme'     => 'brisko',
            'disable_updates'   => true,
            'can_deactivate'    => true,
            'error_handler'     => null,
            'config_file'       => 'config',
            'sudo_admin'        => 1,
            'sudo_admin_group'  => null,
            'redis'             => [
                'disabled'         => null,
                'host'             => '127.0.0.1',
                'port'             => 6379,
                'password'         => null,
                'adminbar'         => null,
                'disable-metrics'  => null,
                'disable-banners'  => null,
                'prefix'           => 'c984d06aafbecf6bc55569f964148ea3redis-cache',
                'database'         => 0,
                'timeout'          => 1,
                'read-timeout'     => 1,
            ],
            'security'          => [
                'sucuri_waf'          => false,
                'encryption_key'      => null,
                'brute-force'         => true,
                'two-factor'          => true,
                'no-pwned-passwords'  => true,
                'admin-ips'           => [],
            ],
            'terminate'         => [
                'debugger' => false,
            ],
            'mailer'            => [
                'brevo'     => [
                    'apikey' => null,
                ],
                'postmark'  => [
                    'token' => null,
                ],
                'sendgrid'  => [
                    'apikey' => null,
                ],
                'mailerlite' => [
                    'apikey' => null,
                ],
                'mailgun'   => [
                    'domain'   => null,
                    'secret'   => null,
                    'endpoint' => 'api.mailgun.net',
                    'scheme'   => 'https',
                ],
                'ses'       => [
                    'key'     => null,
                    'secret'  => null,
                    'region'  => 'us-east-1',
                ],
            ],
            's3uploads'         => [
                'bucket'      => 'site-uploads',
                'key'         => null,
                'secret'      => null,
                'region'      => 'us-east-1',
                'bucket-url'  => 'https://example.com',
                'object-acl'  => 'public',
                'expires'     => '2 days',
                'http-cache'  => 300,
            ],
            'publickey'         => [
                'app-key' => 'b75b666f-ac11-4342-b001-d2546f1d3a5b',
            ],
        ];

        $actual = $app_with_args->getArgs();

        $this->assertEquals(
            $expected,
            $actual,
            'Failed asserting that default_args() returns the expected configuration array.'
        );
    }
}
