<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use WPframework\Config;

/**
 * @group WPframework
 *
 * @covers \WPframework\Config
 *
 * @internal
 */
class ConfigDefaultTest extends TestCase
{
    public function setUp(): void
    {
        cleanSensitiveEnv(['SUDO_ADMIN', 'SENDGRID_API_KEY']);
    }

    public function test_app_config_returns_expected_structure_and_values(): void
    {
        $configs = Config::siteConfig(APP_TEST_PATH);

        // Assert overall structure
        $this->assertIsArray($configs);

        // Security checks
        $this->assertArrayHasKey('security', $configs);
        $this->assertCount(6, $configs['security']);
        $this->assertNull($configs['security']['encryption_key']);
        $this->assertTrue($configs['security']['brute-force']);
        $this->assertTrue($configs['security']['two-factor']);
        $this->assertTrue($configs['security']['no-pwned-passwords']);
        $this->assertEquals([], $configs['security']['admin-ips']);

        // Mailer checks
        $this->assertArrayHasKey('mailer', $configs);
        $this->assertCount(6, $configs['mailer']);

        // Mailer services checks
        foreach (['brevo', 'postmark', 'sendgrid', 'mailerlite'] as $service) {
            $this->assertArrayHasKey($service, $configs['mailer']);
            if (isset($configs['mailer'][$service]['apikey'])) {
                $this->assertEmpty($configs['mailer'][$service]['apikey']);
            }
        }

        $this->assertArrayHasKey('mailgun', $configs['mailer']);
        $this->assertEmpty($configs['mailer']['mailgun']['domain']);
        $this->assertEmpty($configs['mailer']['mailgun']['secret']);
        $this->assertEquals('api.mailgun.net', $configs['mailer']['mailgun']['endpoint']);
        $this->assertEquals('https', $configs['mailer']['mailgun']['scheme']);

        $this->assertArrayHasKey('ses', $configs['mailer']);
        $this->assertEmpty($configs['mailer']['ses']['key']);
        $this->assertEmpty($configs['mailer']['ses']['secret']);
        $this->assertEquals('us-east-1', $configs['mailer']['ses']['region']);

        // Sudo Admin checks
        $this->assertEquals(1, $configs['sudo_admin']);
        $this->assertNull($configs['sudo_admin_group']);

        // Web root and directories checks
        $this->assertEquals('public', $configs['directory']['web_root_dir']);
        $this->assertEquals('assets', $configs['directory']['asset_dir']);
        $this->assertEquals('wp-content', $configs['directory']['content_dir']);
        $this->assertEquals('wp-content/plugins', $configs['directory']['plugin_dir']);
        $this->assertEquals('wp-content/mu-plugins', $configs['directory']['mu_plugin_dir']);
        $this->assertEquals('sqlitedb', $configs['directory']['sqlite_dir']);
        $this->assertEquals('.sqlite-wpdatabase', $configs['directory']['sqlite_file']);
        $this->assertEquals('twentytwentythree', $configs['default_theme']);
        $this->assertEquals('templates', $configs['directory']['theme_dir']);

        // Boolean checks
        $this->assertTrue((bool) $configs['disable_updates']);
        $this->assertTrue((bool) $configs['can_deactivate']);

        // Error handler check
        $this->assertNull($configs['error_handler']);

        // S3 Uploads checks
        $this->assertArrayHasKey('s3uploads', $configs);
        $s3uploads = $configs['s3uploads'];
        $this->assertEquals('site-uploads', $s3uploads['bucket']);
        $this->assertEquals('', $s3uploads['key']);
        $this->assertEquals('', $s3uploads['secret']);
        $this->assertEquals('us-east-1', $s3uploads['region']);
        $this->assertEquals('https://example.com', $s3uploads['bucket-url']);
        $this->assertEquals('public', $s3uploads['object-acl']);
        $this->assertEquals('2 days', $s3uploads['expires']);
        $this->assertEquals(300, $s3uploads['http-cache']);

        // Redis checks
        $this->assertArrayHasKey('redis', $configs);
        $redis = $configs['redis'];
        $this->assertEmpty($redis['disabled']);
        $this->assertEquals('127.0.0.1', $redis['host']);
        $this->assertEquals(6379, $redis['port']);
        $this->assertEmpty($redis['password']);
        $this->assertEmpty($redis['adminbar']);
        $this->assertEmpty($redis['disable-metrics']);
        $this->assertEmpty($redis['disable-banners']);
        $this->assertEquals('c984d06aafbecf6bc55569f964148ea3redis-cache', $redis['prefix']);
        $this->assertEquals(0, $redis['database']);
        $this->assertEquals(1, $redis['timeout']);
        $this->assertEquals(1, $redis['read-timeout']);

        // Public key checks
        $this->assertArrayHasKey('publickey', $configs);
        $this->assertEquals('b75b666f-ac11-4342-b001-d2546f1d3a5b', $configs['publickey']['app-key']);
        $this->assertEquals('pubkeys', $configs['directory']['publickey_dir']);
    }
}
