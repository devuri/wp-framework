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

namespace WPframework\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPframework\App;
use WPframework\AppFactory;

/**
 * @group WPframework
 *
 * @covers \WPframework\AppFactory
 *
 * @internal
 */
class AppFactoryTest extends TestCase
{
    public function test_app_create(): void
    {
        $siteAppFactory = AppFactory::create(APP_TEST_PATH);
        $this->assertInstanceOf(App::class, $siteAppFactory);
    }
}
