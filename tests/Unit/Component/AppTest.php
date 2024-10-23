<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Component;

use WPframework\Tests\BaseTest;

/**
 * @group WPframework
 *
 * @covers \WPframework\App
 *
 * @internal
 */
class AppTest extends BaseTest
{
    public function test_class_exists_is_true(): void
    {
        $this->assertTrue(class_exists('WPframework\App'));
    }
}
