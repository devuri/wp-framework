<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Support;

use PHPUnit\Framework\TestCase;
use WPframework\Support\Switcher;

/**
 * Tests for Switcher.
 *
 * @group WPframework\Support
 *
 * @covers \WPframework\Support\Switcher
 *
 * @internal
 */
class SwitcherTest extends TestCase
{
    public function setUp(): void
    {
        // self::markTestIncomplete();
    }

    /**
     * @incomplete
     */
    public function test_class_exists(): void
    {
        self::assertTrue(class_exists('WPframework\Support\Switcher'));
    }


    /**
     * @covers \WPframework\Support\Switcher::createEnvironment
     */
    public function test_create_environment(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'createEnvironment'));
    }
    /**
     * @covers \WPframework\Support\Switcher::secure
     */
    public function test_secure(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'secure'));
    }
    /**
     * @covers \WPframework\Support\Switcher::production
     */
    public function test_production(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'production'));
    }
    /**
     * @covers \WPframework\Support\Switcher::staging
     */
    public function test_staging(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'staging'));
    }
    /**
     * @covers \WPframework\Support\Switcher::development
     */
    public function test_development(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'development'));
    }
    /**
     * @covers \WPframework\Support\Switcher::debug
     */
    public function test_debug(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'debug'));
    }
    /**
     * @covers \WPframework\Support\Switcher::setDebugLog
     */
    public function test_set_debug_log(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'setDebugLog'));
    }
    /**
     * @covers \WPframework\Support\Switcher::setErrorLogsDir
     */
    public function test_set_error_logs_dir(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'setErrorLogsDir'));
    }
    /**
     * @covers \WPframework\Support\Switcher::setCache
     */
    public function test_set_cache(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Switcher', 'setCache'));
    }
}
