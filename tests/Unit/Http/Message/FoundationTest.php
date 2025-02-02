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

namespace WPframework\Tests\Unit\Http\Message;

use PHPUnit\Framework\TestCase;
use WPframework\Http\Message\Foundation;

/**
 * Tests for Foundation.
 *
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Foundation
 *
 * @internal
 */
class FoundationTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\Message\Foundation'));
    }


    /**
     * @covers \WPframework\Http\Message\Foundation::create
     */
    public function test_create(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Foundation', 'create'));
    }
}
