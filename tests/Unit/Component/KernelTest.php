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

use PHPUnit\Framework\TestCase;
use WPframework\Kernel;

/**
 * @group WPframework
 *
 * @covers \WPframework\Kernel::getArgs
 *
 * @internal
 */
class KernelTest extends TestCase
{
    protected $http_app = null;

    protected function setUp(): void
    {
        $this->http_app = new Kernel(APP_TEST_PATH);
    }

    protected function tearDown(): void
    {
        $this->http_app = null;
    }

    public function test_get_app_path(): void
    {
        $this->assertEquals(APP_TEST_PATH, $this->http_app->get_app_path());
    }

    public function test_default_args(): void
    {
        $default = self::getDefaultConfig();

        // /dump($this->http_app->getArgs());

        $this->assertEquals($default, $this->http_app->getArgs());
    }

    public function test_constants_defined(): void
    {
        $this->http_app->setKernelConstants();

        $app_test_path = APP_TEST_PATH;

        $const_defaults = [
            "APP_PATH" => $app_test_path,
            "APP_HTTP_HOST" => "default_domain.com",
            "PUBLIC_WEB_DIR" => $app_test_path . "/public",

            // directory setup.
            "WP_DIR_PATH" => $app_test_path . "/public/wp",
            "APP_ASSETS_DIR" => $app_test_path . "/public/assets",
            "APP_CONTENT_DIR" => "wp-content",
            "WP_CONTENT_DIR" => $app_test_path . "/public/wp-content",
            "WP_CONTENT_URL" => "https://example.com/wp-content",
            "APP_THEME_DIR" => "templates",
            "WP_PLUGIN_DIR" => $app_test_path . "/public/wp-content/plugins",
            "WP_PLUGIN_URL" => "https://example.com/wp-content/plugins",
            "WPMU_PLUGIN_DIR" => $app_test_path . "/public/wp-content/mu-plugins",
            "WPMU_PLUGIN_URL" => "https://example.com/wp-content/mu-plugins",

            // admin
            "AUTOMATIC_UPDATER_DISABLED" => true,
            "WP_SUDO_ADMIN" => 1,
            "SUDO_ADMIN_GROUP" => null,
            "CAN_DEACTIVATE_PLUGINS" => true,

            // sqlite.
            "DB_DIR" => $app_test_path . "/sqlitedb",
            "DB_FILE" => ".sqlite-wpdatabase",

            "WP_DEFAULT_THEME" => "twentytwentythree",

            // salts.
            "COOKIEHASH" => "c984d06aafbecf6bc55569f964148ea3",
            "USER_COOKIE" => "wpc_user_c984d06aafbecf6bc55569f964148ea3",
            "PASS_COOKIE" => "wpc_pass_c984d06aafbecf6bc55569f964148ea3",
            "AUTH_COOKIE" => "wpc_c984d06aafbecf6bc55569f964148ea3",
            "SECURE_AUTH_COOKIE" => "wpc_sec_c984d06aafbecf6bc55569f964148ea3",
            "LOGGED_IN_COOKIE" => "wpc_logged_in_c984d06aafbecf6bc55569f964148ea3",
            "TEST_COOKIE" => "613df23f4d18ac79c829ba8c18b503e4",

            // sucuri.
            "ENABLE_SUCURI_WAF" => false,
            // "SUCURI_DATA_STORAGE" => $app_test_path . "../../storage/logs/sucuri",

            // redis
            "WP_REDIS_DISABLED" => null,
            "WP_REDIS_PREFIX" => "c984d06aafbecf6bc55569f964148ea3redis-cache",
            "WP_REDIS_DATABASE" => 0,
            "WP_REDIS_HOST" => "127.0.0.1",
            "WP_REDIS_PORT" => 6379,
            "WP_REDIS_PASSWORD" => null,
            "WP_REDIS_DISABLE_ADMINBAR" => null,
            "WP_REDIS_DISABLE_METRICS" => null,
            "WP_REDIS_DISABLE_BANNERS" => null,
            "WP_REDIS_TIMEOUT" => 1,
            "WP_REDIS_READ_TIMEOUT" => 1,
        ];

        $http_app_defined = $this->http_app->get_defined();

        $this->assertIsArray($http_app_defined);

        $count = \count($http_app_defined);

        $this->assertEquals(39, $count);

        $this->assertEquals($const_defaults, $this->http_app->get_defined());
    }

    public static function getDefaultConfig()
    {
        return [
            "error_handler" => null,
            "config_file" => "config",
            "terminate" => [
                "debugger" => false,
            ],
            "directory" => [
                "wp_dir_path" => "wp",
                "web_root_dir" => "public",
                "content_dir" => "wp-content",
                "plugin_dir" => "wp-content/plugins",
                "mu_plugin_dir" => "wp-content/mu-plugins",
                "sqlite_dir" => "sqlitedb",
                "sqlite_file" => ".sqlite-wpdatabase",
                "theme_dir" => "templates",
                "asset_dir" => "assets",
                "publickey_dir" => "pubkeys",
            ],
            "default_theme" => "twentytwentythree",
            "disable_updates" => true,
            "can_deactivate" => true,
            "security" => [
                "sucuri_waf" => false,
                "encryption_key" => null,
                "brute-force" => true,
                "two-factor" => true,
                "no-pwned-passwords" => true,
                "admin-ips" => [],
            ],
            "mailer" => [
                "brevo" => [
                    "apikey" => null,
                ],
                "postmark" => [
                    "token" => null,
                ],
                "sendgrid" => [
                    "apikey" => null,
                ],
                "mailerlite" => [
                    "apikey" => null,
                ],
                "mailgun" => [
                    "domain" => null,
                    "secret" => null,
                    "endpoint" => "api.mailgun.net",
                    "scheme" => "https",
                ],
                "ses" => [
                    "key" => null,
                    "secret" => null,
                    "region" => "us-east-1",
                ],
            ],
            "sudo_admin" => 1,
            "sudo_admin_group" => null,
            "s3uploads" => [
                "bucket" => "site-uploads",
                "key" => null,
                "secret" => null,
                "region" => "us-east-1",
                "bucket-url" => "https://example.com",
                "object-acl" => "public",
                "expires" => "2 days",
                "http-cache" => 300,
            ],
            "redis" => [
                "disabled" => null,
                "host" => "127.0.0.1",
                "port" => 6379,
                "password" => null,
                "adminbar" => null,
                "disable-metrics" => null,
                "disable-banners" => null,
                "prefix" => "c984d06aafbecf6bc55569f964148ea3redis-cache",
                "database" => 0,
                "timeout" => 1,
                "read-timeout" => 1,
            ],
            "publickey" => [
                "app-key" => "b75b666f-ac11-4342-b001-d2546f1d3a5b",
            ],
        ];
    }
}
