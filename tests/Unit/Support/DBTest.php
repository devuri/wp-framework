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
use WPframework\Support\DB;

/**
 * Tests for DB.
 *
 * @group WPframework\Support
 *
 * @covers \WPframework\Support\DB
 *
 * @internal
 */
class DBTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Support\DB'));
    }


    /**
     * @covers \WPframework\Support\DB::all
     */
    public function test_all(): void
    {
        self::assertTrue(method_exists('WPframework\Support\DB', 'all'));
    }
    /**
     * @covers \WPframework\Support\DB::find
     */
    public function test_find(): void
    {
        self::assertTrue(method_exists('WPframework\Support\DB', 'find'));
    }
    /**
     * @covers \WPframework\Support\DB::where
     */
    public function test_where(): void
    {
        self::assertTrue(method_exists('WPframework\Support\DB', 'where'));
    }
}
