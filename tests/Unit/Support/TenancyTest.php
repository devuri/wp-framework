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
use WPframework\Support\Tenancy;

/**
 * Tests for Tenancy.
 *
 * @group WPframework\Support
 *
 * @covers \WPframework\Support\Tenancy
 *
 * @internal
 */
class TenancyTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Support\Tenancy'));
    }


    /**
     * @covers \WPframework\Support\Tenancy::init
     */
    public function test_init(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Tenancy', 'init'));
    }
    /**
     * @covers \WPframework\Support\Tenancy::setupMultiTenant
     */
    public function test_setup_multi_tenant(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Tenancy', 'setupMultiTenant'));
    }
    /**
     * @covers \WPframework\Support\Tenancy::defineTenantConstants
     */
    public function test_define_tenant_constants(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Tenancy', 'defineTenantConstants'));
    }
    /**
     * @covers \WPframework\Support\Tenancy::maybeRegenerateEnvFile
     */
    public function test_maybe_regenerate_env_file(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Tenancy', 'maybeRegenerateEnvFile'));
    }
    /**
     * @covers \WPframework\Support\Tenancy::getDBPrefix
     */
    public function test_get_db_prefix(): void
    {
        self::assertTrue(method_exists('WPframework\Support\Tenancy', 'getDBPrefix'));
    }
}
