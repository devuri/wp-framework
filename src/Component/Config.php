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

use InvalidArgumentException;
use RuntimeException;
use Urisoft\DotAccess;
use WPframework\Interfaces\ConfigInterface;
use WPframework\Support\DBFactory;

final class Config implements ConfigInterface
{
    public $composer;
    public $tenancy;
    public $tenants;
    private $appPath;
    private $configsDir;
    private static $composerJson;
    private static $tenancyJson;
    private static $tenantsJson;

    public function __construct(?string $appPath = null)
    {
        $this->appPath     = $appPath ?? APP_DIR_PATH;
        $this->configsPath = $this->getConfigsPath($this->appPath);
        $this->composer    = $this->composer();
        $this->tenancy     = $this->tenancy();
        $this->tenants     = $this->tenants();
    }

    public function getAppPath()
    {
        return $this->appPath;
    }

    public function getConfigsDir()
    {
        return $this->configsPath;
    }

    public static function wpdb()
    {
        return DBFactory::create();
    }

    /**
     * @return (null|mixed|(null|bool|mixed|(mixed|string)[]|string)[]|string)[]
     *
     * @psalm-return array{error_handler: mixed, config_file: 'config', terminate: array{debugger: false}, directory: array{wp_dir_path: 'wp', web_root_dir: mixed, content_dir: mixed, plugin_dir: mixed, mu_plugin_dir: mixed, sqlite_dir: mixed, sqlite_file: mixed, theme_dir: mixed, asset_dir: mixed, publickey_dir: mixed}, default_theme: mixed, disable_updates: mixed, can_deactivate: mixed, security: array{sucuri_waf: false, encryption_key: null, 'brute-force': true, 'two-factor': true, 'no-pwned-passwords': true, 'admin-ips': array<never, never>}, mailer: array{brevo: array{apikey: mixed}, postmark: array{token: mixed}, sendgrid: array{apikey: mixed}, mailerlite: array{apikey: mixed}, mailgun: array{domain: mixed, secret: mixed, endpoint: mixed, scheme: 'https'}, ses: array{key: mixed, secret: mixed, region: mixed}}, sudo_admin: mixed, sudo_admin_group: null, s3uploads: array{bucket: mixed, key: mixed, secret: mixed, region: mixed, 'bucket-url': mixed, 'object-acl': mixed, expires: mixed, 'http-cache': mixed}, redis: array{disabled: mixed, host: mixed, port: mixed, password: mixed, adminbar: mixed, 'disable-metrics': mixed, 'disable-banners': mixed, prefix: mixed, database: mixed, timeout: mixed, 'read-timeout': mixed}, publickey: array{'app-key': mixed}}
     */
    public static function getDefault(): array
    {
        return [
            'error_handler'    => env('ERROR_HANDLER', false),
            'prod'             => [ 'secure', 'sec', 'production', 'prod' ],
            'config_file'      => 'config',
            'terminate'        => [
                'debugger' => false,
            ],
            'directory'        => [
                'wp_dir_path'   => 'wp',
                'web_root_dir'  => env('WEB_ROOT_DIR', 'public'),
                'content_dir'   => env('CONTENT_DIR', 'wp-content'),
                'plugin_dir'    => env('PLUGIN_DIR', 'wp-content/plugins'),
                'mu_plugin_dir' => env('MU_PLUGIN_DIR', 'wp-content/mu-plugins'),
                'sqlite_dir'    => env('SQLITE_DIR', 'sqlitedb'),
                'sqlite_file'   => env('SQLITE_FILE', '.sqlite-wpdatabase'),
                'theme_dir'     => env('THEME_DIR', 'templates'),
                'asset_dir'     => env('ASSET_DIR', 'assets'),
                'publickey_dir' => env('PUBLICKEY_DIR', 'pubkeys'),
            ],

            'default_theme'    => env('DEFAULT_THEME', 'twentytwentythree'),
            'disable_updates'  => env('DISABLE_UPDATES', true),
            'can_deactivate'   => env('CAN_DEACTIVATE', true),

            'security'         => [
                'sucuri_waf'          => false,
                'encryption_key'     => null,
                'brute-force'        => true,
                'two-factor'         => true,
                'no-pwned-passwords' => true,
                'admin-ips'          => [],
            ],

            'mailer'           => [
                'brevo'      => [
                    'apikey' => env('BREVO_API_KEY'),
                ],

                'postmark'   => [
                    'token' => env('POSTMARK_TOKEN'),
                ],

                'sendgrid'   => [
                    'apikey' => env('SENDGRID_API_KEY'),
                ],

                'mailerlite' => [
                    'apikey' => env('MAILERLITE_API_KEY'),
                ],

                'mailgun'    => [
                    'domain'   => env('MAILGUN_DOMAIN'),
                    'secret'   => env('MAILGUN_SECRET'),
                    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
                    'scheme'   => 'https',
                ],

                'ses'        => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
                ],
            ],
            'sudo_admin'       => env('SUDO_ADMIN', 1),
            'sudo_admin_group' => null,
            's3uploads'        => [
                'bucket'     => env('S3_UPLOADS_BUCKET', 'site-uploads'),
                'key'        => env('S3_UPLOADS_KEY', ''),
                'secret'     => env('S3_UPLOADS_SECRET', ''),
                'region'     => env('S3_UPLOADS_REGION', 'us-east-1'),
                'bucket-url' => env('S3_UPLOADS_BUCKET_URL', 'https://example.com'),
                'object-acl' => env('S3_UPLOADS_OBJECT_ACL', 'public'),
                'expires'    => env('S3_UPLOADS_HTTP_EXPIRES', '2 days'),
                'http-cache' => env('S3_UPLOADS_HTTP_CACHE_CONTROL', '300'),
            ],

            'redis'            => [
                'disabled'        => env('WP_REDIS_DISABLED', false),
                'host'            => env('WP_REDIS_HOST', '127.0.0.1'),
                'port'            => env('WP_REDIS_PORT', 6379),
                'password'        => env('WP_REDIS_PASSWORD', ''),
                'adminbar'        => env('WP_REDIS_DISABLE_ADMINBAR', false),
                'disable-metrics' => env('WP_REDIS_DISABLE_METRICS', false),
                'disable-banners' => env('WP_REDIS_DISABLE_BANNERS', false),
                'prefix'          => env('WP_REDIS_PREFIX', md5(env('WP_HOME', APP_HTTP_HOST)) . 'redis-cache'),
                'database'        => env('WP_REDIS_DATABASE', 0),
                'timeout'         => env('WP_REDIS_TIMEOUT', 1),
                'read-timeout'    => env('WP_REDIS_READ_TIMEOUT', 1),
            ],

            'publickey'        => [
                'app-key' => env('WEB_APP_PUBLIC_KEY', null),
            ],
        ];
    }

    public function siteConfig(): array
    {
        $options_file = $this->configsPath . '/app.php';

        if (file_exists($options_file) && \is_array(@require $options_file)) {
            $siteConfigs = require $options_file;
        } else {
            $siteConfigs = [];
        }

        if ( ! \is_array($siteConfigs)) {
            throw new InvalidArgumentException('Error: Config::siteConfig must be of type array', 1);
        }

        return self::multiMerge(self::getDefault(), $siteConfigs);
    }

    public function get(?string $key = null, $default = null)
    {
        static $_options = null;

        if (null === $_options) {
            $_options = new DotAccess($this->siteConfig());
        }

        if (null === $key) {
            return $_options;
        }

        return $_options->get($key, $default);
    }

    /**
     * @return mixed
     */
    public function composer(?string $key = null)
    {
        $composer = self::loadComposerFile();

        if ( ! empty($key)) {
            return $composer->get($key);
        }

        return self::loadComposerFile();
    }

    /**
     * @return mixed
     */
    public function tenancy(?string $key = null)
    {
        $this->loadTenancyFile();

        if ( ! empty($key)) {
            return $tenancy->get($key);
        }

        return $this->loadTenancyFile();
    }

    /**
     * @return mixed
     */
    public function tenants()
    {
        return $this->loadTenants();
    }

    public static function isProd(?string $environment): bool
    {
        if (\in_array($environment, [ 'secure', 'sec', 'production', 'prod' ], true)) {
            return true;
        }

        return false;
    }

    public function json(?string $filePath = null)
    {
        $jsonFilePath = $filePath;

        if ( ! file_exists($jsonFilePath)) {
            throw new RuntimeException("json file not found at {$jsonFilePath}");
        }

        $json = json_decode(file_get_contents($jsonFilePath), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException("Error decoding json file {$jsonFilePath}: " . json_last_error_msg());
        }
        $jsonData = $json;

        return new DotAccess($jsonData);
    }

    public function getActivePlugins()
    {
        return self::wpdb()->table('options')->getOption('active_plugins');
    }

    /**
     * Retrieves the path for a tenant-specific file, with an option to enforce strict finding.
     *
     * In a multi-tenant application, this function attempts to find a file specific to the current tenant.
     * If the file is not found and 'find_or_fail' is set to true, the function will return null.
     * If the tenant-specific file does not exist (and 'find_or_fail' is false), it falls back to a default file path.
     * If neither file is found, or the application is not in multi-tenant mode, null is returned.
     *
     * @param string $dir          The directory within the app path where the file should be located.
     * @param bool   $find_or_fail Whether to fail if the tenant-specific file is not found.
     *
     * @return null|string The path to the file if found, or null otherwise.
     */
    public function getTenantFilePath(string $dir, bool $find_or_fail = false): ?string
    {
        if ($this->composer->get('extra.multitenant.is_active', false) && \defined('APP_TENANT_ID')) {
            $tenant_id = APP_TENANT_ID;
        } else {
            return null;
        }

        $tenant_file_path = $this->configsPath . APP_TENANT_ID . "/app.php";

        if (file_exists($tenant_file_path)) {
            return $tenant_file_path;
        }

        if ($find_or_fail) {
            throw new Exception('tenant config requires that each tenant must have their own app.php configuration.', 1);
        }

        return self::getDefault();
    }

    /*
     * The tenant ID for the application.
     *
     * This sets the tenant ID based on the environment configuration. The `APP_TENANT_ID`
     * can be configured in the `.env` file. Setting `APP_TENANT_ID` to false will disable the
     * custom uploads directory behavior that is typically used in a multi-tenant setup. In a
     * multi-tenant environment, `APP_TENANT_ID` is required and must always be set. The method
     * uses `envTenantId()` function to retrieve the tenant ID from the environment settings.
     */
    public static function envTenantId(): ?string
    {
        if (\defined('APP_TENANT_ID')) {
            return APP_TENANT_ID;
        }
        if (env('APP_TENANT_ID')) {
            return env('APP_TENANT_ID');
        }

        return null;
    }

    public static function defaultConfigPath()
    {
        return _configsDir();
    }

    protected function loadTenancyFile()
    {
        $userTenancyfile = $this->configsPath . "/tenancy.json";
        $defaultTenancyfile = self::defaultConfigPath() . "/tenancy.json";

        if ( ! self::$tenancyJson) {
            if (file_exists($userTenancyfile)) {
                self::$tenancyJson = $this->json($userTenancyfile);
            } else {
                self::$tenancyJson = $this->json($defaultTenancyfile);
            }
        }

        return self::$tenancyJson;
    }

    /**
     * Loads tenants from a JSON file for faster setup of multi-tenancy.
     * This method looks for a tenants.json file in the config directory.
     * If the file does not exist, it defaults to an empty array.
     *
     * @return array The list of tenants loaded from the JSON file or an empty array.
     */
    protected function loadTenants()
    {
        $definedTenants = $this->configsPath . "/tenants.json";

        if ( ! self::$tenantsJson) {
            if (file_exists($definedTenants)) {
                self::$tenantsJson = $this->json($definedTenants);
            } else {
                self::$tenantsJson = [];
            }
        }

        return self::$tenantsJson;
    }

    protected function loadComposerFile()
    {
        if ( ! self::$composerJson) {
            self::$composerJson = $this->json($this->appPath . "/composer.json");
        }

        return self::$composerJson;
    }

    private function getConfigsPath(string $appPath)
    {
        return $appPath . '/' . siteConfigsDir();
    }

    /**
     * Merges two multi-dimensional arrays recursively.
     *
     * This function will recursively merge the values of `$array2` into `$array1`.
     * If the same key exists in both arrays, and both corresponding values are arrays,
     * the values are recursively merged.
     * Otherwise, values from `$array2` will overwrite those in `$array1`.
     *
     * @param array $array1 The base array that will be merged into.
     * @param array $array2 The array with values to merge into `$array1`.
     *
     * @return array The merged array.
     */
    private static function multiMerge(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (isset($merged[$key]) && \is_array($merged[$key]) && \is_array($value)) {
                $merged[$key] = self::multiMerge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
