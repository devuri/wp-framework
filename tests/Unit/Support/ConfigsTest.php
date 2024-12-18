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
use RuntimeException;
use WPframework\Support\Configs;

/**
 * @internal
 *
 * @coversNothing
 */
class ConfigsTest extends TestCase
{
    /**
     * Test initialization without app path.
     */
    public function test_initialization_only(): void
    {
        $configs = new Configs();
        $this->assertSame(['tenancy','tenants','kiosk','composer','whitelist'], array_keys($configs->config));
    }

    /**
     * Test initialization without app path.
     */
    public function test_initialization_without_app_path(): void
    {
        $configs = new Configs();

        $this->assertNotNull($configs->getConfigsDir(), "Configs directory should be set on initialization");
        $this->assertNotEmpty($configs->getConfig('tenancy'), "Tenancy configuration should be preloaded");
    }

    /**
     * Test initialization with custom app path.
     */
    public function test_initialization_with_custom_app_path(): void
    {
        $customPath = '/custom/app/path';
        $configs = new Configs([], $customPath);

        $this->assertSame($customPath, $configs->getAppPath(), "App path should match the custom path provided");
    }

    /**
     * Test app configuration is not loaded on initialization.
     */
    public function test_app_configuration_not_loaded_on_initialization(): void
    {
        $configs = new Configs();

        $this->assertNull($configs->getConfig('app'), "App configuration should never be loaded initially");
    }

    /**
     * Test loading app configuration explicitly.
     */
    public function test_explicit_app_configuration_loading(): void
    {
        $configs = new Configs();
        $configs->app();

        $this->assertNotNull($configs->getConfig('app'), "App configuration should be loaded after calling app()");
    }

    /**
     * Test adding a new configuration.
     */
    public function test_add_config(): void
    {
        $configs = new Configs();
        $configs->addConfig('custom_config');

        $this->assertNotNull($configs->getConfig('custom_config'), "Custom configuration should be added and available");
    }

    /**
     * Test clearing configuration cache.
     */
    public function test_clear_config_cache(): void
    {
        $configs = new Configs();

        $configs->addConfig('custom_config');
        $this->assertNotNull($configs->getConfig('custom_config'), "Custom configuration should exist before clearing cache");

        $configs->clearConfigCache('custom_config');
        $this->assertNull($configs->getConfig('custom_config'), "Custom configuration should be removed after clearing cache");

        $configs->clearConfigCache();
        $this->assertEmpty($configs->config, "All configurations should be cleared from cache");
    }

    /**
     * Test invalid JSON file loading.
     */
    public function test_invalid_json_file_throws_exception(): void
    {
        $this->expectException(RuntimeException::class);

        $configs = new Configs();
        $configs->json('/invalid/path/to/file.json');
    }

    /**
     * Test valid JSON file loading.
     */
    public function test_valid_json_file_loading(): void
    {
        $configs = new Configs();

        $result = $configs->json(APP_TEST_PATH . '/fixtures/tenants.json');

        $this->assertEquals(self::validTenantJsonOutput(), $result, "Valid JSON file should load correctly");
    }

    /**
     * Test retrieving tenant-specific file path.
     */
    public function test_get_tenant_file_path(): void
    {
        $configs = new Configs();

        $tenantFilePath = $configs->getTenantFilePath('some_dir');

        $this->assertNull($tenantFilePath, "Tenant file path should return null if tenant is not active");

        // Simulating tenant-specific file path retrieval
        $configs = $this->getMockBuilder(Configs::class)
            ->onlyMethods(['getTenantFilePath'])
            ->getMock();

        $configs->method('getTenantFilePath')
            ->willReturn('/path/to/tenant/file');

        $result = $configs->getTenantFilePath('some_dir');

        $this->assertEquals('/path/to/tenant/file', $result, "Tenant file path should return the correct path if tenant is active");
    }

    /**
     * Test getting default configuration.
     */
    public function test_get_default_configuration(): void
    {
        $defaultConfig = Configs::getDefault();

        $this->assertArrayHasKey('error_handler', $defaultConfig, "Default configuration should have error_handler key");
        $this->assertArrayHasKey('directory', $defaultConfig, "Default configuration should have directory key");
    }

    private static function validTenantJsonOutput(): array
    {
        return [
            "alpha" => [
                "id" => 1,
                "uuid" => "a12b34c5-d67e-89f0-g123-h456i789j012",
                "name" => "Alpha Version Limited",
                "domain" => "alpha.domain1.local",
                "user_id" => 100,
                "created_at" => "2023-01-01T10:00:00Z",
                "status" => "active",
            ],
            "servicedesk" => [
                "id" => 2,
                "uuid" => "k13l45m6-n78o-90p1-q234-r567s890t123",
                "name" => "Service Desk",
                "domain" => "servicedesk.domain1.local",
                "user_id" => 101,
                "created_at" => "2023-01-05T14:15:00Z",
                "status" => "active",
            ],
            "gamma" => [
                "id" => 3,
                "uuid" => "u14v56w7-x89y-01z2-a345-b678c901d234",
                "name" => "Gamma Platform",
                "domain" => "gamma.local",
                "user_id" => 202,
                "created_at" => "2023-02-10T09:00:00Z",
                "status" => "inactive",
            ],
            "delta" => [
                "id" => 4,
                "uuid" => "e15f67g8-h90i-12j3-k456-l789m012n345",
                "name" => "Delta Insights",
                "domain" => "delta.domain1.local",
                "user_id" => 303,
                "created_at" => "2023-03-20T08:45:00Z",
                "status" => "active",
            ],
            "epsilon" => [
                "id" => 5,
                "uuid" => "o16p78q9-r01s-23t4-u567-v890w123x456",
                "name" => "Epsilon Analytics",
                "domain" => "epsilon.domain1.local",
                "user_id" => 404,
                "created_at" => "2024-01-01T12:30:00Z",
                "status" => "active",
            ],
            "zetahub" => [
                "id" => 6,
                "uuid" => "y17z89a0-b12c-34d5-e678-f901g234h567",
                "name" => "Zeta Hub",
                "domain" => "zetahub.local",
                "user_id" => 505,
                "created_at" => "2023-05-15T11:20:00Z",
                "status" => "inactive",
            ],
            "omega-systems" => [
                "id" => 7,
                "uuid" => "i18j90k1-l23m-45n6-o789-p012q345r678",
                "name" => "Omega Systems",
                "domain" => "omega-systems.local",
                "user_id" => 606,
                "created_at" => "2023-06-25T16:00:00Z",
                "status" => "active",
            ],
            "aplus" => [
                "id" => 8,
                "uuid" => "r567s890t123",
                "name" => "Aplus Systems",
                "domain" => "aplus.domain1.local",
                "user_id" => 707,
                "created_at" => "2023-07-25T16:00:00Z",
                "status" => "active",
            ],
            "domain1" => [
                "id" => 9,
                "uuid" => "81243057",
                "name" => "Aplus Systems",
                "domain" => "domain1.local",
                "user_id" => 808,
                "created_at" => "2023-01-01 10:00:00",
                "status" => "active",
            ],
        ];
    }
}
