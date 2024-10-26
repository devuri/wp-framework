<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

/**
 * @group WPframework
 *
 * @covers ::config
 *
 * @internal
 */
class ConfigTest extends TestCase
{
    public function test_config_function_with_valid_key(): void
    {
        $result = config('directory.plugin_dir');

        $this->assertEquals('wp-content/plugins', $result);
    }

    public function test_config_function_with_invalid_key(): void
    {
        $keyToTest = 'nonexistent.key';

        $result = config($keyToTest);

        $this->assertNull($result);
    }

    public function test_config_has_array(): void
    {
        $expected = [
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
        ];

        $result = config('directory');

        $this->assertIsArray($result);

        $this->assertEquals($expected, $result);
    }

    private static function array_data()
    {
        $_config_array_data = require \dirname(__FILE__, 3) . '/src/inc/configs/app.php';

        return $_config_array_data;
    }

    private static function not_array_data()
    {
        return \dirname(__FILE__, 3) . '/src/inc/app.php';
    }
}
