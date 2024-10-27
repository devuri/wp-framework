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

use PHPUnit\Framework\TestCase;
use WPframework\Support\HashValidator;

/**
 * @group WPframework
 *
 * @covers \WPframework\HashValidator::isMd5
 *
 * @internal
 */
class HashValidatorTest extends TestCase
{
    public function test_valid_md5(): void
    {
        $validMd5 = "d41d8cd98f00b204e9800998ecf8427e";
        $this->assertTrue(HashValidator::isMd5($validMd5), "Valid MD5 hash was not recognized.");
    }

    public function test_invalid_md5_length(): void
    {
        $invalidMd5Length = "d41d8cd98f00b204e9800998ecf842";
        $this->assertFalse(HashValidator::isMd5($invalidMd5Length), "Invalid MD5 hash (wrong length) was incorrectly recognized as valid.");
    }

    public function test_invalid_md5_characters(): void
    {
        $invalidMd5Characters = "z41d8cd98f00b204e9800998ecf8427g";
        $this->assertFalse(HashValidator::isMd5($invalidMd5Characters), "Invalid MD5 hash (wrong characters) was incorrectly recognized as valid.");
    }

    public function test_upper_case_md5(): void
    {
        $upperCaseMd5 = "D41D8CD98F00B204E9800998ECF8427E";
        $this->assertTrue(HashValidator::isMd5($upperCaseMd5), "Valid MD5 hash with uppercase letters was not recognized.");
    }

    public function test_empty_string(): void
    {
        $emptyString = "";
        $this->assertFalse(HashValidator::isMd5($emptyString), "Empty string was incorrectly recognized as a valid MD5 hash.");
    }

    public function test_non_hex_string(): void
    {
        $nonHexString = "g41d8cd98f00b204e9800998ecf8427h"; // 'g' and 'h' are not hex characters
        $this->assertFalse(HashValidator::isMd5($nonHexString), "String with non-hex characters was incorrectly recognized as a valid MD5 hash.");
    }
}
