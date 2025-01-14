<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

use Exception;
use InvalidArgumentException;
use stdClass;
use Symfony\Component\Filesystem\Filesystem;

final class EnvType
{
    public const SECURE      = 'secure';
    public const SEC         = 'sec';
    public const PRODUCTION  = 'production';
    public const PROD        = 'prod';
    public const STAGING     = 'staging';
    public const DEVELOPMENT = 'development';
    public const DEV         = 'dev';
    public const DEBUG       = 'debug';
    public const DEB         = 'deb';
    public const LOCAL       = 'local';

    protected $filesystem;

    /**
     * An array of all environment types.
     *
     * @var string[]
     */
    private static array $envTypes = [
        self::SECURE,
        self::SEC,
        self::PRODUCTION,
        self::PROD,
        self::STAGING,
        self::DEVELOPMENT,
        self::DEV,
        self::DEBUG,
        self::DEB,
        self::LOCAL,
    ];

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Checks if the given type is a valid environment type.
     *
     * @param null|string $type The environment type to check.
     *
     * @return bool True if valid, false otherwise.
     */
    public static function isValid(?string $type): bool
    {
        return \in_array($type, self::$envTypes, true);
    }

    /**
     * Get all environment types.
     *
     * @return string[] The list of environment types.
     */
    public static function getAll(): array
    {
        return self::$envTypes;
    }

    /**
     * Retrieves the default file names for environment configuration.
     *
     * @since [version number]
     *
     * @return string[] An array of default file names for environment configurations.
     *
     * @psalm-return list{'env', '.env', '.env.secure', '.env.prod', '.env.staging', '.env.dev', '.env.debug', '.env.local', 'env.local'}
     */
    public static function supportedFiles(): array
    {
        return [
            'env',
            '.env',
            '.env.secure',
            '.env.prod',
            '.env.staging',
            '.env.dev',
            '.env.debug',
            '.env.local',
            'env.local',
        ];
    }

    /**
     * Filters out environment files that do not exist to avoid warnings.
     */
    public function filterFiles(array $envFiles, string $appPath): array
    {
        foreach ($envFiles as $key => $file) {
            if (! file_exists($appPath . '/' . $file)) {
                unset($envFiles[$key]);
            }
        }

        return $envFiles;
    }

    /**
     * Regenerates the tenant-specific .env file if it doesn't exist.
     *
     * @param string $appPath
     * @param string $appHttpHost
     * @param array  $availableFiles
     */
    public function tryRegenerateFile(string $appPath, string $appHttpHost, array $availableFiles = [], ?string $dbPrefix = null): void
    {
        $mainEnvFile = "{$appPath}/.env";
        if (! $this->filesystem->exists($mainEnvFile)) {
            $this->createFile($mainEnvFile, $appHttpHost, $dbPrefix);
        }
    }

    public function createFile(string $filePath, string $domain, ?string $prefix = null): void
    {
        // handle kiosk file if framework is kiosk.
        if ('kiosk' === $prefix && ! $this->filesystem->exists($filePath)) {
            $this->filesystem->dumpFile($filePath, $this->generateKioskFile($domain));

            return;
        }

        if (! $this->filesystem->exists($filePath)) {
            $this->filesystem->dumpFile($filePath, $this->generateFileContent($domain, $prefix));
        }
    }

    /**
     * Generate a cryptographically secure password.
     *
     * @param int  $length          The length of the password to generate.
     * @param bool $useSpecialChars Whether to include special characters in the password.
     * @param bool $startWithLetter Whether to start the password with a letter.
     *
     * @return string The generated password.
     */
    public static function randStr(int $length = 8, bool $useSpecialChars = false, bool $startWithLetter = true): string
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = $letters . '1234567890';

        if ($useSpecialChars) {
            $characters .= '!@#$%^&*()';
        }

        $charactersLength = \strlen($characters);
        $password = '';

        // Ensure the first character is a letter
        if ($startWithLetter && $length > 0) {
            $password .= $letters[random_int(0, \strlen($letters) - 1)];
            $length--;
        }

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $password;
    }

    public function read(string $envFilePath, bool $sanitized = true, $groupBySections = false): array
    {
        if (!$this->filesystem->exists($envFilePath)) {
            throw new InvalidArgumentException("The .env file does not exist at: {$envFilePath}");
        }

        $content = file_get_contents($envFilePath);
        $content = str_replace(["\r\n", "\r"], "\n", $content); // Normalize line endings
        $lines = explode("\n", $content);

        if ($groupBySections) {
            return $this->parseWithSections($lines);
        }

        return $this->parseWithoutSections($lines, $sanitized);
    }

    protected function generateFileContent(?string $wpdomain = null, ?string $prefix = null): string
    {
        $salt            = null;
        $autoLoginSecret = bin2hex(random_bytes(32));
        $appTenantSecret = bin2hex(random_bytes(32));
        $adminerSecret   = bin2hex(random_bytes(32));
        $dbrootpass      = strtolower(self::randStr(16));
        $kioskpanelId    = strtolower(self::randStr(10));

        try {
            $salt = (object) self::wpSalts();
        } catch (Exception $e) {
            // Handle exception if needed
        }

        $homeUrl = "https://$wpdomain";
        $siteUrl = '${HOME_URL}/wp';
        $dbprefix = $prefix ? "wp_{$prefix}_" : strtolower('wp_' . self::randStr(8) . '_');

        return <<<END
        HOME_URL='$homeUrl'
        WP_SITEURL="{$siteUrl}"
        ADMIN_LOGIN_URL="{$siteUrl}/wp-login.php"

        ENVIRONMENT_TYPE='prod'
        WP_DEVELOPMENT_MODE='theme'
        DISABLE_WP_APPLICATION_PASSWORDS=true
        SUDO_ADMIN='1'

        APP_TENANT_ID=null
        IS_MULTI_TENANT_APP=false

        BASIC_AUTH_USER='admin'
        BASIC_AUTH_PASSWORD='demo'

        # Email
        SEND_EMAIL_CHANGE_EMAIL=false
        SENDGRID_API_KEY=''

        # Premium
        ELEMENTOR_PRO_LICENSE=''
        ELEMENTOR_AUTO_ACTIVATION=true

        MEMORY_LIMIT='256M'
        MAX_MEMORY_LIMIT='512M'

        FORCE_SSL_ADMIN=false
        FORCE_SSL_LOGIN=false

        USE_APP_THEME=false
        BACKUP_PLUGINS=false

        # s3backup
        S3_BACKUP_KEY=null
        S3_BACKUP_SECRET=null
        S3_BACKUP_DIR=null
        ENABLE_S3_BACKUP=false
        S3ENCRYPTED_BACKUP=false
        S3_BACKUP_BUCKET='wp-s3snaps'
        S3_BACKUP_REGION='us-west-1'
        DELETE_LOCAL_S3BACKUP=false

        DB_NAME=local
        DB_USER=root
        DB_PASSWORD=password
        DB_HOST=localhost
        DB_PREFIX=$dbprefix
        DB_CHARSET='utf8'

        # optional (for docker environments)
        DB_ROOT_PASS=$dbrootpass

        AUTH_KEY='$salt->AUTH_KEY'
        SECURE_AUTH_KEY='$salt->SECURE_AUTH_KEY'
        LOGGED_IN_KEY='$salt->LOGGED_IN_KEY'
        NONCE_KEY='$salt->NONCE_KEY'
        AUTH_SALT='$salt->AUTH_SALT'
        SECURE_AUTH_SALT='$salt->SECURE_AUTH_SALT'
        LOGGED_IN_SALT='$salt->LOGGED_IN_SALT'
        NONCE_SALT='$salt->NONCE_SALT'

        ADMINER_SECRET='$adminerSecret'
        AUTO_LOGIN_SECRET_KEY='$autoLoginSecret'
        APP_TENANT_SECRET='$appTenantSecret'

        END;
    }

    /**
     * @return string[]
     *
     * @psalm-return array<string, string>
     */
    protected static function wpSalts(): array
    {
        $saltsUrl     = 'https://api.wordpress.org/secret-key/1.1/salt/';
        $saltsContent = @file_get_contents($saltsUrl);

        if (false === $saltsContent) {
            throw new Exception('Unable to retrieve salts from WordPress API.');
        }

        $string  = str_replace(["\r", "\n"], '', $saltsContent);
        $pattern = "/define\('([^']*)',\s*'([^']*)'\);/";
        $result  = [];

        if (preg_match_all($pattern, $string, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $result[$match[1]] = $match[2];
            }
        } else {
            throw new Exception('Failed to parse the salts string.');
        }

        return $result;
    }

    private function generateKioskFile(?string $wpdomain = null): string
    {
        $salt              = null;
        $autoLoginSecret = bin2hex(random_bytes(32));
        $appTenantSecret = bin2hex(random_bytes(32));
        $dbrootpass        = strtolower(self::randStr(16));
        $kioskpanelId      = strtolower(self::randStr(10));
        $homeUrl = "https://$wpdomain";

        try {
            $salt = (object) self::wpSalts();
        } catch (Exception $e) {
            // Handle exception if needed
        }

        return <<<END
        HOME_URL='$homeUrl'
        ADMIN_LOGIN_URL="{$homeUrl}/login"
        KIOSK_DOMAIN_ID={$kioskpanelId}
        ENVIRONMENT_TYPE='prod'

        # Email
        SENDGRID_API_KEY=''

        # s3backup
        S3_BACKUP_KEY=null
        S3_BACKUP_SECRET=null
        S3_BACKUP_DIR=null
        ENABLE_S3_BACKUP=false
        S3ENCRYPTED_BACKUP=false
        S3_BACKUP_BUCKET='wp-s3snaps'
        S3_BACKUP_REGION='us-west-1'
        DELETE_LOCAL_S3BACKUP=false

        DB_NAME=local
        DB_USER=root
        DB_PASSWORD=password
        DB_HOST=localhost
        DB_CHARSET='utf8'

        # optional (for docker environments)
        DB_ROOT_PASS=$dbrootpass

        AUTH_KEY='$salt->AUTH_KEY'
        SECURE_AUTH_KEY='$salt->SECURE_AUTH_KEY'
        LOGGED_IN_KEY='$salt->LOGGED_IN_KEY'
        NONCE_KEY='$salt->NONCE_KEY'
        AUTH_SALT='$salt->AUTH_SALT'
        SECURE_AUTH_SALT='$salt->SECURE_AUTH_SALT'
        LOGGED_IN_SALT='$salt->LOGGED_IN_SALT'
        NONCE_SALT='$salt->NONCE_SALT'

        AUTO_LOGIN_SECRET_KEY='$autoLoginSecret'
        APP_TENANT_SECRET='$appTenantSecret'

        END;
    }

    private function parseLine(string $line): array
    {
        [$key, $value] = array_map('trim', explode('=', $line, 2));

        if (preg_match('/^"(.*?)"$/', $value, $matches)) {
            $value = $matches[1];
        } elseif (preg_match("/^'(.*?)'$/", $value, $matches)) {
            $value = $matches[1];
        } else {
            // Validate unquoted values
            if (!preg_match('/^[a-zA-Z0-9_\.\-\/]*$/', $value)) {
                throw new InvalidArgumentException("Invalid value detected in .env file: {$value}");
            }
        }

        // Validate key
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
            throw new InvalidArgumentException("Invalid key detected in .env file: {$key}");
        }

        return [$key, $value];
    }

    private function parseWithSections(array $lines): array
    {
        $sections = [];
        $currentSection = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || str_starts_with($line, '#')) {
                if (!empty($currentSection)) {
                    $sections[] = $currentSection;
                    $currentSection = [];
                }

                continue;
            }

            $currentSection[] = $this->parseLine($line);
        }

        // Add the last section if it exists
        if (!empty($currentSection)) {
            $sections[] = $currentSection;
        }

        return $this->convertSectionsToObjects($sections);
    }

    private function parseWithoutSections(array $lines, $sanitizeVal = true): array
    {
        $parsed = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = $this->parseLine($line);
            $sanitizedKey = self::sanitizeKey($key);
            $itemValue = $this->getValue($value, $sanitizeVal);

            if (null !== $sanitizedKey && '' !== $sanitizedKey) {
                $parsed[$sanitizedKey] = $itemValue;
            }
        }

        return $parsed;
    }

    private static function sanitizeKey(string $key)
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $key);
    }

    private function convertSectionsToObjects(array $sections, bool $sanitizeVal = true): array
    {
        $sectionObjects = [];

        foreach ($sections as $section) {
            $sectionObject = new stdClass();

            foreach ($section as [$key, $value]) {
                // Sanitize key and value
                $sanitizedKey = self::sanitizeKey($key);
                $itemValue = $this->getValue($value, $sanitizeVal);

                if (null !== $sanitizedKey && '' !== $sanitizedKey) {
                    $sectionObject->{$sanitizedKey} = $itemValue;
                }
            }

            $sectionObjects[] = $sectionObject;
        }

        return $sectionObjects;
    }

    private function getValue($value, bool $sanitized = true)
    {
        if ($sanitized) {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }

        return $value;
    }
}
