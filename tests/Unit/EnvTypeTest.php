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
use ReflectionClass;
use Symfony\Component\Filesystem\Filesystem;
use WPframework\EnvType;

/**
 * @internal
 *
 * @coversNothing
 */
class EnvTypeTest extends TestCase
{
    private Filesystem $filesystem;
    private EnvType $envType;
    private string $appPath;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->envType = new EnvType($this->filesystem);
        $this->appPath = sys_get_temp_dir() . '/env_test';

        if (! $this->filesystem->exists($this->appPath)) {
            $this->filesystem->mkdir($this->appPath);
        }
    }

    protected function tearDown(): void
    {
        $this->filesystem->remove($this->appPath);
    }

    public function test_is_valid_returns_true_for_valid_environment_type(): void
    {
        $this->assertTrue(EnvType::isValid('production'));
    }

    public function test_is_valid_returns_false_for_invalid_environment_type(): void
    {
        $this->assertFalse(EnvType::isValid('invalid_type'));
    }

    public function test_get_all_returns_all_environment_types(): void
    {
        $expectedTypes = [
            'secure', 'sec', 'production', 'prod', 'staging',
            'development', 'dev', 'debug', 'deb', 'local',
        ];
        $this->assertEquals($expectedTypes, EnvType::getAll());
    }

    public function test_supported_files_returns_default_supported_files(): void
    {
        $expectedFiles = [
            'env', '.env', '.env.secure', '.env.prod', '.env.staging',
            '.env.dev', '.env.debug', '.env.local', 'env.local',
        ];
        $this->assertEquals($expectedFiles, EnvType::supportedFiles());
    }

    public function test_filter_files_removes_non_existing_files(): void
    {
        $this->filesystem->touch($this->appPath . '/.env');

        $envFiles = EnvType::supportedFiles();
        $filteredFiles = (new EnvType($this->filesystem))->filterFiles($envFiles, $this->appPath);

        $this->assertContains('.env', $filteredFiles);
        $this->assertNotContains('.env.prod', $filteredFiles);
    }

    public function test_try_regenerate_file_creates_env_file_if_missing(): void
    {
        $envFilePath = $this->appPath . '/.env';

        // Ensure .env file does not exist
        $this->filesystem->remove($envFilePath);

        // Try to regenerate .env file
        $this->envType->tryRegenerateFile($this->appPath, 'example.com');

        $this->assertFileExists($envFilePath);
    }

    public function test_create_file_creates_custom_env_file(): void
    {
        $customEnvFilePath = $this->appPath . '/.env.custom';

        // Create a custom .env file
        $this->envType->createFile($customEnvFilePath, 'customdomain.com', 'customprefix');

        $this->assertFileExists($customEnvFilePath);

        // Verify contents
        $contents = file_get_contents($customEnvFilePath);
        $this->assertStringContainsString('customdomain.com', $contents);
        $this->assertStringContainsString('DB_PREFIX=wp_customprefix_', $contents);
    }

    public function test_rand_str_generates_password_of_given_length(): void
    {
        $length = 12;
        $password = EnvType::randStr($length);
        $this->assertEquals($length, \strlen($password));
    }

    public function test_generate_file_content_contains_expected_placeholders(): void
    {
        // Access the protected method by reflection
        $reflection = new ReflectionClass($this->envType);
        $method = $reflection->getMethod('generateFileContent');
        $method->setAccessible(true);

        $content = $method->invoke($this->envType, 'example.com', 'prefix');

        $this->assertStringContainsString("HOME_URL='https://example.com'", $content);
        $this->assertStringContainsString("DB_PREFIX=wp_prefix_", $content);
    }

    public function test_wp_salts_returns_expected_keys(): void
    {
        // Mock the wpSalts method to avoid actual API calls
        $reflection = new ReflectionClass($this->envType);
        $method = $reflection->getMethod('wpSalts');
        $method->setAccessible(true);

        try {
            $salts = $method->invoke($this->envType);
        } catch (Exception $e) {
            $this->fail("Failed to retrieve salts: " . $e->getMessage());

            return;
        }

        $expectedKeys = [
            'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY',
            'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT',
        ];
        $this->assertEquals($expectedKeys, array_keys($salts));
    }
}
