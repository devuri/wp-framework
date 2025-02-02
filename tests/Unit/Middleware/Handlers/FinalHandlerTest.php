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

namespace WPframework\Tests\Unit\Middleware\Handlers;

use PHPUnit\Framework\TestCase;
use WPframework\Middleware\Handlers\FinalHandler;

/**
 * Tests for FinalHandler.
 *
 * @group WPframework\Middleware\Handlers
 *
 * @covers \WPframework\Middleware\Handlers\FinalHandler
 *
 * @internal
 */
class FinalHandlerTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\Handlers\FinalHandler'));
    }


    /**
     * @covers \WPframework\Middleware\Handlers\FinalHandler::handle
     */
    public function test_handle(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\Handlers\FinalHandler', 'handle'));
    }
}
