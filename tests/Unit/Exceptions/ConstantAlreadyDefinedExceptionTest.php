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

namespace WPframework\Tests\Unit\Exceptions;

use PHPUnit\Framework\TestCase;
use WPframework\Exceptions\ConstantAlreadyDefinedException;

/**
 * Tests for ConstantAlreadyDefinedException.
 *
 * @group WPframework\Exceptions
 *
 * @covers \WPframework\Exceptions\ConstantAlreadyDefinedException
 *
 * @internal
 */
class ConstantAlreadyDefinedExceptionTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Exceptions\ConstantAlreadyDefinedException'));
    }


    /**
     * @covers \WPframework\Exceptions\ConstantAlreadyDefinedException::getMessage
     */
    public function test_get_message(): void
    {
        self::assertTrue(method_exists('WPframework\Exceptions\ConstantAlreadyDefinedException', 'getMessage'));
    }
    /**
     * @covers \WPframework\Exceptions\ConstantAlreadyDefinedException::getCode
     */
    public function test_get_code(): void
    {
        self::assertTrue(method_exists('WPframework\Exceptions\ConstantAlreadyDefinedException', 'getCode'));
    }
    /**
     * @covers \WPframework\Exceptions\ConstantAlreadyDefinedException::getFile
     */
    public function test_get_file(): void
    {
        self::assertTrue(method_exists('WPframework\Exceptions\ConstantAlreadyDefinedException', 'getFile'));
    }
    /**
     * @covers \WPframework\Exceptions\ConstantAlreadyDefinedException::getLine
     */
    public function test_get_line(): void
    {
        self::assertTrue(method_exists('WPframework\Exceptions\ConstantAlreadyDefinedException', 'getLine'));
    }
    /**
     * @covers \WPframework\Exceptions\ConstantAlreadyDefinedException::getTrace
     */
    public function test_get_trace(): void
    {
        self::assertTrue(method_exists('WPframework\Exceptions\ConstantAlreadyDefinedException', 'getTrace'));
    }
    /**
     * @covers \WPframework\Exceptions\ConstantAlreadyDefinedException::getPrevious
     */
    public function test_get_previous(): void
    {
        self::assertTrue(method_exists('WPframework\Exceptions\ConstantAlreadyDefinedException', 'getPrevious'));
    }
    /**
     * @covers \WPframework\Exceptions\ConstantAlreadyDefinedException::getTraceAsString
     */
    public function test_get_trace_as_string(): void
    {
        self::assertTrue(method_exists('WPframework\Exceptions\ConstantAlreadyDefinedException', 'getTraceAsString'));
    }
}
