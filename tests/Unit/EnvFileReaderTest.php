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
use Symfony\Component\Filesystem\Filesystem;
use WPframework\EnvType;

/**
 * @internal
 *
 * @coversNothing
 */
class EnvFileReaderTest extends TestCase
{
    private string $testFilePath;
    private Filesystem $filesystem;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->testFilePath = APP_TEST_PATH . '/test.env';

        // Write the test .env file
        $this->filesystem->dumpFile($this->testFilePath, self::envContent());
    }

    protected function tearDown(): void
    {
        if ($this->filesystem->exists($this->testFilePath)) {
            $this->filesystem->remove($this->testFilePath);
        }
    }

    public function test_read_env_file(): void
    {
        $reader = new EnvType($this->filesystem);

        // Read without grouping by sections.
        $envData = $reader->read($this->testFilePath);

        $this->assertArrayHasKey('HOME_URL', $envData);
        $this->assertEquals('https://example.com', $envData['HOME_URL']);
        $this->assertArrayHasKey('WP_SITEURL', $envData);
        $this->assertEquals('${HOME_URL}/wp', $envData['WP_SITEURL']);
        $this->assertArrayHasKey('ADMIN_LOGIN_URL', $envData);
        $this->assertEquals('${HOME_URL}/wp/wp-login.php', $envData['ADMIN_LOGIN_URL']);
        $this->assertArrayHasKey('ENABLE_S3_BACKUP', $envData);
        $this->assertEquals('false', $envData['ENABLE_S3_BACKUP']);
    }

    private static function envContent(): string
    {
        return <<<EOD
HOME_URL='https://example.com'
WP_SITEURL="\${HOME_URL}/wp"
ADMIN_LOGIN_URL="\${HOME_URL}/wp/wp-login.php"

WP_ENVIRONMENT_TYPE=debug
WP_DEVELOPMENT_MODE='theme'
APP_TENANT_ID=envTenant123
IS_MULTITENANT=false

ASSET_URL='https://cdn.example.com'
CONCATENATE_SCRIPTS=1

BASIC_AUTH_USER='admin'
BASIC_AUTH_PASSWORD='demo'

USE_APP_THEME=false
WP_ENVIRONMENT_TYPE='debug'
BACKUP_PLUGINS=false

SEND_EMAIL_CHANGE_EMAIL=false
SENDGRID_API_KEY=null
SUDO_ADMIN='1'

WPENV_AUTO_LOGIN_SECRET_KEY='2bf011c00c2d08b46d2a2a4d11eb7bd01f535f83f33ed254d7e5ddad67ac04a3'
WEB_APP_PUBLIC_KEY='b75b666f-ac11-4342-b001-d2546f1d3a5b'
SEND_EMAIL_CHANGE_EMAIL=false

# Premium
ELEMENTOR_PRO_LICENSE=''
ELEMENTOR_AUTO_ACTIVATION=false
AVADAKEY=''
BACKUP_PLUGINS=false

MEMORY_LIMIT='256M'
MAX_MEMORY_LIMIT='512M'

DISABLE_WP_APPLICATION_PASSWORDS=true
FORCE_SSL_ADMIN=1
FORCE_SSL_LOGIN=0

AUTOSAVE_INTERVAL=300
WP_POST_REVISIONS=5

# s3backup
ENABLE_S3_BACKUP=false
S3ENCRYPTED_BACKUP=false
S3_BACKUP_KEY=null
S3_BACKUP_SECRET=null
S3_BACKUP_BUCKET='wp-s3snaps'
S3_BACKUP_REGION='us-west-1'
S3_BACKUP_DIR=null
DELETE_LOCAL_S3BACKUP=false

DB_NAME=wordpress_db
DB_USER=root
DB_PASSWORD=password
DB_HOST='127.0.0.1'
DB_PREFIX=wp_cyd69vxo_
DB_COLLATE=''

# Tenant
LANDLORD_DB_HOST=localhost
LANDLORD_DB_NAME=domain1_main
LANDLORD_DB_USER=root
LANDLORD_DB_PASSWORD=password
LANDLORD_DB_PREFIX=wp_lblcxox8_

AUTH_KEY=']6)=4CVbrGB}]D>A0@qy7wudtjT}*cx=KD@tpj+Pn)nZsdb<;8Zf5k6t-U*BrA#'
SECURE_AUTH_KEY=':pH+lj&BhxwIs1xIq_2J64C-*e3K|C1!JP/Mju@D<<*.chibS7;7ncp]r@(dD|Gr'
LOGGED_IN_KEY='IFTi<sI>g0R*(AHke!zQ%7=swR2iJ}i|M55/bnuA!(RBE)m&=tt#mKEn`&PHyrwg'
NONCE_KEY='x(<yeYGhz4Uxop8B)IQn4?|SWmH>+>4xKycqI14-PA(x-re[.rYXe.|QrAadD+[z'
AUTH_SALT='V2#iZwkzb7DQYLbR]Xgk6tjVg6Psp#GuaSSxR4;,okWb>AHeU4qvWPN]WXAL]%'
SECURE_AUTH_SALT='Wjvt)Z1(n6qbAO|Y4IYEP)}{L5q?iR}pqWWHWSQrN,(pOu@-a|q%FlRY6PwWr:'
LOGGED_IN_SALT='MY5`,b(![#.Ni_P))zF*pOK3n7[F5k!Yr`DoDPyh@2]p#yS3`)SQq@xNR;!2KtVL'
NONCE_SALT='2[]7?kLGY`e-,6B:EU,ul;w(:HJlo1v;>.5{pc)8vxknaVi|Q&luz|>pW3w*8lL0'
EOD;
    }
}
