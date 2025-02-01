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

namespace WPframework\Tests\Unit\Support;

use PHPUnit\Framework\TestCase;
use WPframework\Support\KernelConfig;

/**
 * Tests for KernelConfig.
 *
 * @group WPframework\Support
 *
 * @covers \WPframework\Support\KernelConfig
 *
 * @internal
 */
class KernelConfigTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Support\KernelConfig'));
    }


    /**
     * @covers \WPframework\Support\KernelConfig::setKernelConstants
     */
    public function test_set_kernel_constants(): void
    {
        self::assertTrue(method_exists('WPframework\Support\KernelConfig', 'setKernelConstants'));
    }
    /**
     * @covers \WPframework\Support\KernelConfig::envTenantId
     */
    public function test_env_tenant_id(): void
    {
        self::assertTrue(method_exists('WPframework\Support\KernelConfig', 'envTenantId'));
    }
    /**
     * @covers \WPframework\Support\KernelConfig::configurationOverrides
     */
    public function test_configuration_overrides(): void
    {
        self::assertTrue(method_exists('WPframework\Support\KernelConfig', 'configurationOverrides'));
    }
    /**
     * @covers \WPframework\Support\KernelConfig::getTenantConfigFile
     */
    public function test_get_tenant_config_file(): void
    {
        self::assertTrue(method_exists('WPframework\Support\KernelConfig', 'getTenantConfigFile'));
    }
    /**
     * @covers \WPframework\Support\KernelConfig::getDefaultConfigFile
     */
    public function test_get_default_config_file(): void
    {
        self::assertTrue(method_exists('WPframework\Support\KernelConfig', 'getDefaultConfigFile'));
    }
}
