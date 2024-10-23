<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit;

use Urisoft\DotAccess;
use WPframework\Tests\BaseTest;

/**
 * @group WPframework
 *
 * @covers ::config
 *
 * @internal
 */
class ConfigTest extends BaseTest
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
        $configs = config();

        $this->assertInstanceOf(DotAccess::class, $configs);

        // $this->assertIsArray( $configs );
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
