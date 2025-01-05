<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use WPframework\Http\HostManager;

/**
 * Tests for HostManager.
 *
 * @group WPframework\Http
 *
 * @covers \WPframework\Http\HostManager
 *
 * @internal
 */
class HostManagerTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\HostManager'));
    }
}
