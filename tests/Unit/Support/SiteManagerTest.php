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

use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use WPframework\Support\ConstantBuilder;
use WPframework\Support\SiteManager;
use WPframework\Support\Switcher;

/**
 * Tests for SiteManager.
 *
 * @group WPframework\Support
 *
 * @covers \WPframework\Support\SiteManager
 *
 * @internal
 */
class SiteManagerTest extends TestCase
{
    protected $configManager;
    protected $siteManager;

    protected function setUp(): void
    {
        $this->configManager = new ConstantBuilder();
        $this->siteManager = new SiteManager($this->configManager);
        $this->siteManager->setSwitcher(
            new Switcher($this->configManager)
        );
    }

    public function test_set_site_url(): void
    {
        // Setting environment variables for testing
        putenv('WP_HOME=http://example.com');
        putenv('WP_SITEURL=http://example.com/wp');

        $this->siteManager->setSiteUrl();

        $this->assertEquals('http://example.com', $this->configManager->getConstant('WP_HOME'));
        $this->assertEquals('http://example.com/wp', $this->configManager->getConstant('WP_SITEURL'));
    }

    public function test_set_asset_url(): void
    {
        putenv('ASSET_URL=https://cdn.example.com');

        $this->siteManager->setAssetUrl();

        $this->assertEquals('https://cdn.example.com', $this->configManager->getConstant('ASSET_URL'));
    }

    public function test_set_optimize(): void
    {
        putenv('CONCATENATE_SCRIPTS=1');

        $this->siteManager->setOptimize();

        $this->assertEquals(1, $this->configManager->getConstant('CONCATENATE_SCRIPTS'));
    }

    public function test_set_memory(): void
    {
        putenv('MEMORY_LIMIT=128M');
        putenv('MAX_MEMORY_LIMIT=512M');

        $this->siteManager->setMemory();

        $this->assertEquals('128M', $this->configManager->getConstant('WP_MEMORY_LIMIT'));
        $this->assertEquals('512M', $this->configManager->getConstant('WP_MAX_MEMORY_LIMIT'));
    }

    public function test_set_force_ssl(): void
    {
        putenv('FORCE_SSL_ADMIN=1');
        putenv('FORCE_SSL_LOGIN=0');

        $this->siteManager->setForceSsl();

        $this->assertEquals(1, $this->configManager->getConstant('FORCE_SSL_ADMIN'));
        $this->assertEquals(0, $this->configManager->getConstant('FORCE_SSL_LOGIN'));
    }

    public function test_set_autosave(): void
    {
        putenv('AUTOSAVE_INTERVAL=300');
        putenv('WP_POST_REVISIONS=5');

        $this->siteManager->setAutosave();

        $this->assertEquals(300, $this->configManager->getConstant('AUTOSAVE_INTERVAL'));
        $this->assertEquals(5, $this->configManager->getConstant('WP_POST_REVISIONS'));
    }

    public function test_set_database(): void
    {
        putenv('DB_NAME=wordpress');
        putenv('DB_USER=root');
        putenv('DB_PASSWORD=secret');
        putenv('DB_HOST=127.0.0.1');
        putenv('DB_CHARSET=utf8mb4');
        putenv('DB_COLLATE=');

        $this->siteManager->setDatabase();

        $this->assertEquals('wordpress', $this->configManager->getConstant('DB_NAME'));
        $this->assertEquals('root', $this->configManager->getConstant('DB_USER'));
        $this->assertEquals('secret', $this->configManager->getConstant('DB_PASSWORD'));
        $this->assertEquals('127.0.0.1', $this->configManager->getConstant('DB_HOST'));
        $this->assertEquals('utf8mb4', $this->configManager->getConstant('DB_CHARSET'));
        $this->assertEquals('', $this->configManager->getConstant('DB_COLLATE'));
    }

    public function test_set_salts(): void
    {
        putenv('AUTH_KEY=authkey');
        putenv('SECURE_AUTH_KEY=secureauthkey');
        putenv('LOGGED_IN_KEY=loggedinkey');
        putenv('NONCE_KEY=noncekey');
        putenv('AUTH_SALT=authsalt');
        putenv('SECURE_AUTH_SALT=secureauthsalt');
        putenv('LOGGED_IN_SALT=loggedinsalt');
        putenv('NONCE_SALT=noncesalt');

        $this->siteManager->setSalts();

        $this->assertEquals('authkey', $this->configManager->getConstant('AUTH_KEY'));
        $this->assertEquals('secureauthkey', $this->configManager->getConstant('SECURE_AUTH_KEY'));
        $this->assertEquals('loggedinkey', $this->configManager->getConstant('LOGGED_IN_KEY'));
        $this->assertEquals('noncekey', $this->configManager->getConstant('NONCE_KEY'));
        $this->assertEquals('authsalt', $this->configManager->getConstant('AUTH_SALT'));
        $this->assertEquals('secureauthsalt', $this->configManager->getConstant('SECURE_AUTH_SALT'));
        $this->assertEquals('loggedinsalt', $this->configManager->getConstant('LOGGED_IN_SALT'));
        $this->assertEquals('noncesalt', $this->configManager->getConstant('NONCE_SALT'));
    }

    public function test_set_environment(): void
    {
        putenv('WP_ENVIRONMENT_TYPE=staging');

        $this->siteManager->setEnvironment('staging');

        $this->assertEquals('staging', $this->configManager->getConstant('WP_ENVIRONMENT_TYPE'));
    }

    public function test_app_setup(): void
    {
        // Testing full app setup, assuming you have all env variables properly set
        putenv('WP_HOME=http://example.com');
        putenv('WP_SITEURL=http://example.com/wp');
        putenv('DB_NAME=wordpress');
        putenv('DB_USER=root');
        putenv('DB_PASSWORD=secret');
        putenv('DB_HOST=127.0.0.1');
        putenv('DB_CHARSET=utf8mb4');
        putenv('WP_ENVIRONMENT_TYPE=staging');
        putenv('ASSET_URL=https://cdn.example.com');
        putenv('MEMORY_LIMIT=128M');
        putenv('MAX_MEMORY_LIMIT=512M');
        putenv('CONCATENATE_SCRIPTS=1');
        putenv('FORCE_SSL_ADMIN=1');
        putenv('FORCE_SSL_LOGIN=0');
        putenv('AUTOSAVE_INTERVAL=300');
        putenv('WP_POST_REVISIONS=5');
        putenv('AUTH_KEY=authkey');
        putenv('SECURE_AUTH_KEY=secureauthkey');



        // Call the full app setup
        $this->siteManager->appSetup(new ServerRequest('GET', '/test'));

        // Check if some constants were set properly
        $this->assertEquals('http://example.com', $this->configManager->getConstant('WP_HOME'));
        $this->assertEquals('wordpress', $this->configManager->getConstant('DB_NAME'));
        $this->assertEquals('staging', $this->configManager->getConstant('WP_ENVIRONMENT_TYPE'));
        $this->assertEquals('128M', $this->configManager->getConstant('WP_MEMORY_LIMIT'));
    }
}
