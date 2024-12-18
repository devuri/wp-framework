<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Support;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use Urisoft\DotAccess;
use WPframework\Interfaces\ConfigsInterface;

class Configs implements ConfigsInterface
{
    use WhitelistTrait;

    public $config;
    protected $appPath;
    protected $configsPath;
    protected $configsDir;
    protected array $configCache = [];
    protected static $defaultWhitelist;

    public function __construct(array $preloadConfigs = ['tenancy', 'tenants', 'kiosk'], ?string $appPath = null)
    {
        $this->appPath     = $appPath ?? APP_DIR_PATH;
        $this->configsPath = $this->getConfigsPath($this->appPath);
        self::$defaultWhitelist = self::getDefaultWhitelist();

        foreach ($preloadConfigs as $config) {
            $this->loadConfigFile($config);
        }

        $this->loadConfigFile('composer');
        $this->configCache['whitelist'] = $this->setEnvWhitelist(self::$defaultWhitelist);
        $this->config = $this->configCache;
    }

    /**
     * Load `app` options separately to avoid side effects.
     *
     * @return self
     */
    public function app(): self
    {
        $this->configCache['app'] = new DotAccess($this->appOptions());

        return $this->refreshConfig();
    }

    /**
     * @param string $file The base name of the configuration file (without the `.json` extension).
     */
    public function addConfig(string $file): void
    {
        $this->loadConfigFile($file);
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

    public function get(?string $key = null, $default = null)
    {
        if ( ! isset($this->configCache['app'])) {
            $this->configCache['app'] = new DotAccess($this->appOptions());
        }

        if (null === $key) {
            return $this->configCache['app'];
        }

        return $this->configCache['app']->get($key, $default);
    }

    public static function isProd(?string $environment, array $production = [ 'secure', 'sec', 'production', 'prod' ]): bool
    {
        if (\in_array($environment, $production, true)) {
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

        $jsonData = json_decode(file_get_contents($jsonFilePath), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException("Error decoding json file {$jsonFilePath}: " . json_last_error_msg());
        }

        return $jsonData;
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
        if ($this->config['composer']->get('extra.multitenant.is_active', false) && \defined('APP_TENANT_ID')) {
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
        return localConfigsDir();
    }

    /**
     * Get a loaded configuration by key.
     */
    public function getConfig(string $key): ?DotAccess
    {
        return $this->configCache[$key] ?? null;
    }

    /**
     * Clear the configuration cache or optionally for a specific key.
     */
    public function clearConfigCache(?string $key = null): void
    {
        if ($key) {
            unset($this->configCache[$key]);
        } else {
            $this->configCache = [];
        }

        $this->refreshConfig();
    }

    /**
     * Add to the configuration cache.
     */
    public function updateConfigCache(string $key, DotAccess $item): void
    {
        $this->configCache[$key] = $item;
    }

    public function appOptions(): array
    {
        $optionsFile = $this->configsPath . '/app.php';

        if (file_exists($optionsFile) && \is_array(@require $optionsFile)) {
            $appOptions = require $optionsFile;
        } else {
            $appOptions = [];
        }

        if ( ! \is_array($appOptions)) {
            throw new InvalidArgumentException('Error: Config::siteConfig must be of type array', 1);
        }

        return self::multiMerge(self::getDefault(), $appOptions);
    }

    protected function refreshConfig(): self
    {
        $this->config = $this->configCache;

        return $this;
    }

    /**
     * Loads and merges a JSON configuration file into the configuration cache.
     *
     * This method attempts to load a configuration file from multiple sources,
     * merges configuration data from user-defined and default paths, and caches
     * the result for future use. Special handling is provided for `composer.json`,
     * which is directly processed if it exists.
     *
     * @param string      $file        The base name of the configuration file (without the `.json` extension).
     * @param null|string $defaultPath Optional. A fallback path to look for the configuration file
     *                                 if it is not found in the user-defined configuration path.
     *                                 Defaults to the result of `self::defaultConfigPath()`.
     *
     * @throws RuntimeException If the configuration file does not exist in any of the expected locations.
     *
     * @return null|DotAccess Returns an instance of `DotAccess` containing the merged configuration data,
     *                        or `null` if the configuration file does not exist.
     */
    protected function loadConfigFile(string $file, ?string $defaultPath = null)
    {
        $fileName = "$file.json";

        // Special handling for composer.json
        if ('composer.json' === $fileName && ! isset($this->configCache[$file])) {
            $composerFile = $this->appPath . "/" . $fileName;
            if (file_exists($composerFile)) {
                $configData = $this->json($composerFile);
                $this->configCache[$file] = new DotAccess($configData);
            }

            return $this->configCache[$file] ?? null;
        }

        // General configuration file handling
        $userFile = $this->configsPath . "/" . $fileName;
        $defaultFile = $defaultPath ? $defaultPath . "/" . $fileName : self::defaultConfigPath() . "/" . $fileName;

        // Load and merge data from user and default files
        $defaultData = file_exists($defaultFile) ? $this->json($defaultFile) : [];
        $userData = file_exists($userFile) ? $this->json($userFile) : [];

        // Merge the data and cache it
        $configData = self::multiMerge($defaultData, $userData);
        $this->configCache[$file] = new DotAccess($configData);

        return $this->configCache[$file];
    }

    protected function getConfigsPath(string $appPath)
    {
        return $appPath . '/' . appOptionsDir();
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
    protected static function multiMerge(array $array1, array $array2): array
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
