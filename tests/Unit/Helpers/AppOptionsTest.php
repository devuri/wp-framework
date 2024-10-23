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
use WPframework\Framework;

/**
 * @group WPframework
 *
 * @covers \WPframework\Framework::getAppOptions
 *
 * @internal
 */
class AppOptionsTest extends TestCase
{
    public function test_app_options_with_valid_configs(): void
    {
        $result = $this->_app_options();

        $this->assertIsArray($result);
    }
    private function _app_options()
    {
        return (new Framework())->getAppOptions();
    }
}
