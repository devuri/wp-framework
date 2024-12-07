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
    protected $request;

    protected function setUp(): void
    {
        $_dotenv = \Dotenv\Dotenv::createImmutable(APP_TEST_PATH, ['.env', '.env.local']);
        $_dotenv->load();

        $this->request = new ServerRequest('GET', '/test');
    }

    public function test_set_site_url(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setSiteUrl();

        $home = ($configManager->getConstant('WP_HOME') || \constant('WP_HOME'));

        $this->assertEquals('https://example.com', $home);
        $this->assertEquals('https://example.com/wp', $configManager->getConstant('WP_SITEURL'));
    }

    public function test_set_asset_url(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setAssetUrl();

        $this->assertEquals('https://cdn.example.com', $configManager->getConstant('ASSET_URL'));
    }

    public function test_set_optimize(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setOptimize();
        $this->assertEquals(1, $configManager->getConstant('CONCATENATE_SCRIPTS'));
    }

    public function test_set_memory(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setMemory();

        $this->assertEquals('256M', $configManager->getConstant('WP_MEMORY_LIMIT'));
        $this->assertEquals('512M', $configManager->getConstant('WP_MAX_MEMORY_LIMIT'));
    }

    public function test_set_force_ssl(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setForceSsl();

        $this->assertEquals(1, $configManager->getConstant('FORCE_SSL_ADMIN'));
        $this->assertEquals(0, $configManager->getConstant('FORCE_SSL_LOGIN'));
    }

    public function test_set_autosave(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setAutosave();

        $this->assertEquals(300, $configManager->getConstant('AUTOSAVE_INTERVAL'));
        $this->assertEquals(5, $configManager->getConstant('WP_POST_REVISIONS'));
    }

    public function test_set_database(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setDatabase();

        $this->assertEquals('wordpress_db', $configManager->getConstant('DB_NAME'));
        $this->assertEquals('root', $configManager->getConstant('DB_USER'));
        $this->assertEquals('password', $configManager->getConstant('DB_PASSWORD'));
        $this->assertEquals('127.0.0.1', $configManager->getConstant('DB_HOST'));
        $this->assertEquals('utf8mb4', $configManager->getConstant('DB_CHARSET'));
        $this->assertEquals('',$configManager->getConstant('DB_COLLATE'));
    }

    public function test_set_salts(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setSalts();

        $secretkey = [
            'authkey' => ']6)=4CVbrGB}]D>A0@qy7wudtjT}*cx=KD@tpj+Pn)nZsdb<;8Zf5k6t-U*B$rA#',
            'secureauthkey' => ':pH+lj&BhxwIs1xIq_2J64C-*e3K|C1!JP/Mju@D<<*.chibS7;7ncp]r@(dD|Gr',
            'loggedinkey' => 'IFTi<sI>g0R*(AHke!zQ%7=swR2iJ}i|M55/bnuA!(RBE)m&=tt#mKEn`&PHyrwg',
            'noncekey' => 'x(<yeYGhz4Uxop8B)IQn4?|SWmH>+>4xKycqI14-PA(x-re[.rYXe.|QrAadD+[z',
            'authsalt' => 'V2#iZwkzb7DQYLbR]Xgk6tjVg6Psp#$Gu$aSSxR4;,okWb>AHeU4qvWPN]WXAL]%',
            'secureauthsalt' => 'Wjvt)Z1(n6q$bAO|Y4IYEP)}{L5q?iR}pqWWHWSQrN,(pOu@-a|q%$FlRY6PwWr:',
            'loggedinsalt' => 'MY5`,b(![#.Ni_P))zF*pOK3n7[F5k!Yr`DoDPyh@2]p#yS3`)SQq@xNR;!2KtVL',
            'noncesalt' => '2[]7?kLGY`e-,6B:EU,ul;w(:HJlo1v;>.5{pc)8vxknaVi|Q&luz|>pW3w*8lL0',
        ];


        $this->assertEquals($secretkey['authkey'], $configManager->getConstant('AUTH_KEY'));
        $this->assertEquals($secretkey['secureauthkey'], $configManager->getConstant('SECURE_AUTH_KEY'));
        $this->assertEquals($secretkey['loggedinkey'], $configManager->getConstant('LOGGED_IN_KEY'));
        $this->assertEquals($secretkey['noncekey'], $configManager->getConstant('NONCE_KEY'));
        $this->assertEquals($secretkey['authsalt'], $configManager->getConstant('AUTH_SALT'));
        $this->assertEquals($secretkey['secureauthsalt'], $configManager->getConstant('SECURE_AUTH_SALT'));
        $this->assertEquals($secretkey['loggedinsalt'], $configManager->getConstant('LOGGED_IN_SALT'));
        $this->assertEquals($secretkey['noncesalt'], $configManager->getConstant('NONCE_SALT'));
    }

    public function test_set_environment(): void
    {
        $configManager = new ConstantBuilder();
        $siteManager = new SiteManager($configManager);
        $siteManager->setSwitcher(new Switcher($configManager));

        $siteManager->setEnvironment();

        $this->assertEquals('theme', $configManager->getConstant('WP_DEVELOPMENT_MODE'));
    }
}
