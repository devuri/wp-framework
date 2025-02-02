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

namespace WPframework\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use WPframework\Http\HttpFactory;

/**
 * Tests for HttpFactory.
 *
 * @group WPframework\Http
 *
 * @covers \WPframework\Http\HttpFactory
 *
 * @internal
 */
class HttpFactoryTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\HttpFactory'));
    }


    /**
     * @covers \WPframework\Http\HttpFactory::init
     */
    public function test_init(): void
    {
        self::assertTrue(method_exists('WPframework\Http\HttpFactory', 'init'));
    }
}
