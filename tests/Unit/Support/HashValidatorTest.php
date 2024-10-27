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
use WPframework\Support\HashValidator;

/**
 * Tests for HashValidator.
 *
 * @group WPframework\Support
 *
 * @covers \WPframework\Support\HashValidator
 *
 * @internal
 */
class HashValidatorTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Support\HashValidator'));
    }


    /**
     * @covers \WPframework\Support\HashValidator::isMd5
     */
    public function test_is_md5(): void
    {
        self::assertTrue(method_exists('WPframework\Support\HashValidator', 'isMd5'));
    }
}
