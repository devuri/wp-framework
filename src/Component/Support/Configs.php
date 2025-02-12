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

namespace WPframework\Support;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use Urisoft\DotAccess;
use WPframework\Error\ErrorHandler;
use WPframework\Interfaces\ConfigsInterface;

class Configs implements ConfigsInterface
{
    use WhitelistTrait;

    /**
     * Configuration array used by the application.
     *
     * Gives us back an array with DotAccess
     *
     * @var mixed Configuration settings.
     */
    public $config;

    /**
     * The absolute path to the application directory.
     *
     * @var string Path to the application directory.
     */
    protected $appPath;

    /**
     * The absolute path to the application configurations directory.
     *
     * @var string Path to the configuration directory.
     */
    protected $configsPath;

    /**
     * Cache for loaded configurations.
     *
     * @var array An array holding cached configuration data.
     */
    protected array $configCache = [];

    /**
     * The absolute path to the framework's configuration files.
     *
     * @var string Path to the framework's configuration directory.
     */
    protected static $frameworkConfigsPath;

    /**
     * Default whitelist settings for the application.
     *
     * @var array Default whitelist settings.
     */
    protected static $defaultWhitelist;

    /**
     * Default middlewares applied to the application.
     *
     * @var array Default middlewares.
     */
    protected static $defaultMiddlewares;

    /**
     * Constructor for the configuration manager.
     *
     * Initializes the application paths, framework configuration paths,
     * and preloads specific configuration files. Also sets default values
     * for the whitelist and middlewares, and caches the configuration.
     *
     * @param array       $preloadConfigs Optional. List of configuration files to preload.
     *                                    Defaults to ['tenancy', 'tenants', 'kiosk'].
     * @param null|string $appPath        Optional. Custom application directory path.
     *                                    If not provided, defaults to the `APP_DIR_PATH` constant.
     */
    public function __construct(array $preloadConfigs = ['tenancy', 'tenants', 'kiosk', 'shortinit'], ?string $appPath = null)
    {
        $this->appPath     = $appPath ?? APP_DIR_PATH;
        self::$frameworkConfigsPath = SRC_CONFIGS_DIR;
        $this->configsPath = $this->getConfigsPath($this->appPath);
        self::$defaultWhitelist = self::getDefaultWhitelist();
        self::$defaultMiddlewares = self::getDefaultMiddlewares();

        foreach ($preloadConfigs as $config) {
            $this->loadConfigFile($config);
        }

        $this->loadConfigFile('composer');
        $this->configCache['path'] = [
            'app' => $this->appPath,
            'configs' => $this->configsPath,
        ];
        $this->configCache['whitelist'] = $this->setEnvWhitelist(self::$defaultWhitelist);
        $this->configCache['middlewares'] = $this->setMiddlewares(self::$defaultMiddlewares);
        // $this->configCache['hybrid'] = new DotAccess(['enabled' => null]);
        $this->config = $this->configCache;
    }

    /**
     * Initializes the configuration manager with a specific application path.
     *
     * Creates a new instance of the class, preloading the default configuration
     * files ('tenancy', 'tenants', 'kiosk') and setting the provided application path.
     *
     * @param string $appPath The absolute path to the application directory.
     *
     * @return self Returns an instance of the class.
     */
    public static function init(?string $appPath = null): self
    {
        return new self(['tenancy', 'tenants', 'kiosk', 'shortinit'], $appPath);
    }

    /**
     * Loads the `app` options separately to avoid side effects.
     *
     * This method initializes the `app` configuration options and caches it.
     * Afterward, it refreshes the entire configuration.
     *
     * @return static Returns the current instance of the class after refreshing the configuration.
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

    public static function wpdb(?string $tableNameNoPrefix = null)
    {
        return DBFactory::create($tableNameNoPrefix);
    }

    /**
     * Load a configuration file by name.
     *
     * @param string $file The configuration file name (without extension).
     *
     * @throws InvalidArgumentException If the file does not exist.
     *
     * @return void
     */
    public static function load(string $file): void
    {
        $filePath = self::$frameworkConfigsPath . DIRECTORY_SEPARATOR . $file . '.php';

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Configuration file '$file.php' not found in directory.");
        }

        require $filePath;
    }

    /**
     * Load the Adminer script.
     *
     * @throws RuntimeException If the Adminer script cannot be found.
     *
     * @return void
     */
    public static function dbAdminer(): void
    {
        $cfgs = self::init()->app();
        if ($cfgs->config['app']->get('dbadmin.autologin', null)) {
            $_GET['username'] = env('DB_USER');
            $_GET['db'] = env('DB_NAME');
        }

        $defaultAdminer = self::$frameworkConfigsPath . DIRECTORY_SEPARATOR . 'dbadmin' . DIRECTORY_SEPARATOR . 'adminer.php';
        $customAdminer =  APP_DIR_PATH . DIRECTORY_SEPARATOR . 'configs/dbadmin' . DIRECTORY_SEPARATOR . 'adminer.php';

        if (file_exists($customAdminer)) {
            self::load('adminer/adminer');
        } elseif (file_exists($defaultAdminer)) {
            require $defaultAdminer;
        } else {
            throw new RuntimeException(
                'Adminer script not found. Please ensure "configs/dbadmin/adminer.php" exists in the configuration directory.'
            );
        }
    }

    /**
     * @return (null|mixed|(null|bool|mixed|(null|bool|int|mixed|string|string[])[]|string)[])[]
     *
     * @psalm-return array{error_handler: array{class: ErrorHandler::class, quit: true, logs: true}, dbadmin: array{enabled: true, uri: string, validate: true, autologin: true, secret: array{key: mixed, type: 'jwt'}}, health_status: array{enabled: true, secret: mixed, route: 'up'}, prod: list{'secure', 'sec', 'production', 'prod'}, terminate: array{debugger: false}, twig: array{env_options: array{debug: false, charset: 'utf-8', cache: false, auto_reload: null, strict_variables: false, autoescape: 'html', optimizations: -1}}, directory: array{wp_dir_path: 'wp', web_root_dir: mixed, content_dir: mixed, plugin_dir: mixed, mu_plugin_dir: mixed, sqlite_dir: mixed, sqlite_file: mixed, theme_dir: mixed, asset_dir: mixed, publickey_dir: mixed}, default_theme: mixed, disable_updates: mixed, can_deactivate: mixed, security: array{restrict_wpadmin: array{enabled: false, secure: false, allowed: list{'admin-ajax.php'}}, sucuri_waf: false, encryption_key: null, 'brute-force': true, 'two-factor': true, 'no-pwned-passwords': true, 'admin-ips': array<never, never>}, mailer: array{brevo: array{apikey: mixed}, postmark: array{token: mixed}, sendgrid: array{apikey: mixed}, mailerlite: array{apikey: mixed}, mailgun: array{domain: mixed, secret: mixed, endpoint: mixed, scheme: 'https'}, ses: array{key: mixed, secret: mixed, region: mixed}}, sudo_admin: mixed, sudo_admin_group: null, s3uploads: array{bucket: mixed, key: mixed, secret: mixed, region: mixed, 'bucket-url': mixed, 'object-acl': mixed, expires: mixed, 'http-cache': mixed}, redis: array{disabled: mixed, host: mixed, port: mixed, password: mixed, adminbar: mixed, 'disable-metrics': mixed, 'disable-banners': mixed, prefix: mixed, database: mixed, timeout: mixed, 'read-timeout': mixed}, publickey: array{'app-key': mixed}, headless: array{enabled: false, rest_api: array{enabled: true, cache: false}, graphql: array{enabled: false}, themes: false, plugins: array{load: array<never, never>}, debug: false, error_handling: 'log', security: array{cors: true, allowed_origins: list{'*'}}}, shortinit: array{enabled: false, cache: true, debug: false, components: array{database: true, user: false}, error_handling: 'log', api: array{enabled: false, routes: array<never, never>}}}
     */
    public static function getDefault(): array
    {
        return [
            'error_handler' => [
                'class'   => ErrorHandler::class,
                'quit'    => true,
                'logs'    => true,
            ],
            'dbadmin'     => [
                'enabled'   => true,
                'uri'       => self::dbUrl('fnv1a64'),
                'validate'  => true,
                'autologin' => ADMINER_ALLOW_AUTOLOGIN,
                'secret' => [
                    'key' => env('ADMINER_SECRET', null),
                    'type' => 'jwt',
                ],
            ],
            'health_status' => [
                'enabled' => true,
                'secret' => env('HEALTH_STATUS_SECRET', null),
                'route' => 'up',
                'critical' => [],
            ],
            'prod'             => [ 'secure', 'sec', 'production', 'prod' ],
            'terminate'        => [
                'debugger' => false,
            ],
            'twig' => [
                // https://twig.symfony.com/doc/3.x/api.html#environment-options
                'env_options' => [
                    'debug' => false,
                    'charset' => 'utf-8',
                    'cache' => false,
                    'auto_reload' => null,
                    'strict_variables' => false,
                    'autoescape' => 'html',
                    'optimizations' => -1,
                ],

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
                'restrict_wpadmin' => [
                    'enabled' => false,
                    'secure' => false,
                    'allowed' => [
                        'admin-ajax.php',
                    ],
                ],
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
                'prefix'          => env('WP_REDIS_PREFIX', md5(env('HOME_URL', APP_HTTP_HOST)) . 'redis-cache'),
                'database'        => env('WP_REDIS_DATABASE', 0),
                'timeout'         => env('WP_REDIS_TIMEOUT', 1),
                'read-timeout'    => env('WP_REDIS_READ_TIMEOUT', 1),
            ],

            'publickey'        => [
                'app-key' => env('WEB_APP_PUBLIC_KEY', null),
            ],

            'headless' => [
                'enabled' => false,
                'rest_api' => [
                    'enabled' => true,
                    'cache' => false,
                ],
                'graphql' => [
                    'enabled' => false,
                ],
                'themes' => false,
                'plugins' => [
                    'load' => [],
                ],
                'debug' => false,
                'error_handling' => 'log',
                'security' => [
                    'cors' => true,
                    'allowed_origins' => ['*'],
                ],
            ],

            'shortinit' => [
                'enabled' => false,
                'cache' => true,
                'debug' => false,
                'components' => [
                    'database' => true,
                    'user' => false,
                ],
                'error_handling' => 'log',
                'api' => [
                    'enabled' => false,
                    'routes' => [],
                ],
            ],
        ];
    }

    public function get(?string $key = null, $default = null)
    {
        if (! isset($this->configCache['app'])) {
            $this->configCache['app'] = new DotAccess($this->appOptions());
        }

        if (null === $key) {
            return $this->configCache['app'];
        }

        return $this->configCache['app']->get($key, $default);
    }

    /**
     * Determines if the given environment is a production environment.
     *
     * This method checks whether the provided environment string matches
     * one of the predefined production environment names. If the environment
     * is `null`, it defaults to assuming a production environment for safety,
     * ensuring sensitive data is not accidentally exposed.
     *
     * @param null|string $environment The current environment name. Can be `null`.
     * @param array       $production  An array of environment names that are
     *                                 considered production. Defaults to
     *                                 ['secure', 'sec', 'production', 'prod'].
     *
     * @return bool Returns `true` if the environment is `null` or matches one
     *              of the production names, otherwise returns `false`.
     */
    public static function isProd(?string $environment, array $production = ['secure', 'sec', 'production', 'prod']): bool
    {
        if (\is_null($environment) || \in_array($environment, $production, true)) {
            return true;
        }

        return false;
    }

    /**
     * Determines if the application is running in a production environment.
     *
     * This method checks the current environment against a list of production
     * environment identifiers. The list of identifiers can be configured via the
     * `prod` configuration key or will default to common production identifiers
     * such as 'secure', 'sec', 'production', and 'prod'.
     *
     * @return bool True if the application is in a production environment, false otherwise.
     */
    public static function isInProdEnvironment(array $prodEnvironments = ['secure', 'sec', 'production', 'prod']): bool
    {
        return self::isProd(env('ENVIRONMENT_TYPE', null), $prodEnvironments);
    }

    public function json(?string $filePath = null)
    {
        $jsonFilePath = $filePath;

        if (! file_exists($jsonFilePath)) {
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
     * @return null|(null|mixed|(null|bool|mixed|(null|false|int|mixed|string)[]|string)[]|string)[]|string
     *
     * @psalm-return array{error_handler: array{class: ErrorHandler::class, quit: true, logs: true}, prod: list{'secure', 'sec', 'production', 'prod'}, config_file: 'config', terminate: array{debugger: false}, twig: array{env_options: array{debug: false, charset: 'utf-8', cache: false, auto_reload: null, strict_variables: false, autoescape: 'html', optimizations: -1}}, directory: array{wp_dir_path: 'wp', web_root_dir: mixed, content_dir: mixed, plugin_dir: mixed, mu_plugin_dir: mixed, sqlite_dir: mixed, sqlite_file: mixed, theme_dir: mixed, asset_dir: mixed, publickey_dir: mixed}, default_theme: mixed, disable_updates: mixed, can_deactivate: mixed, security: array{sucuri_waf: false, encryption_key: null, 'brute-force': true, 'two-factor': true, 'no-pwned-passwords': true, 'admin-ips': array<never, never>}, mailer: array{brevo: array{apikey: mixed}, postmark: array{token: mixed}, sendgrid: array{apikey: mixed}, mailerlite: array{apikey: mixed}, mailgun: array{domain: mixed, secret: mixed, endpoint: mixed, scheme: 'https'}, ses: array{key: mixed, secret: mixed, region: mixed}}, sudo_admin: mixed, sudo_admin_group: null, s3uploads: array{bucket: mixed, key: mixed, secret: mixed, region: mixed, 'bucket-url': mixed, 'object-acl': mixed, expires: mixed, 'http-cache': mixed}, redis: array{disabled: mixed, host: mixed, port: mixed, password: mixed, adminbar: mixed, 'disable-metrics': mixed, 'disable-banners': mixed, prefix: mixed, database: mixed, timeout: mixed, 'read-timeout': mixed}, publickey: array{'app-key': mixed}}|null|string
     */
    public function getTenantFilePath(string $dir, bool $find_or_fail = false)
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
        $appOptions = $this->appSettingsFileArray();

        if (! \is_array($appOptions)) {
            throw new InvalidArgumentException('Error: Config::siteConfig must be of type array', 1);
        }

        return self::multiMerge(self::getDefault(), $appOptions);
    }

    /**
     * Determines if a given file is a PHP file by checking its extension and searching
     * for valid PHP opening tags in its content.
     *
     * @param string $filePath  The file path or filename.
     * @param int    $readBytes Maximum number of bytes to read from the start of the file (default: 1024).
     *
     * @return bool True if the file is recognized as a PHP file, False otherwise.
     */
    public static function isPhpFile($filePath, $readBytes = 1024)
    {
        if (!\is_string($filePath) || '' === trim($filePath)) {
            return false;
        }

        if (!file_exists($filePath) || is_dir($filePath)) {
            return false;
        }

        if (!is_readable($filePath)) {
            return false;
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ('php' !== $extension) {
            return false;
        }

        if (0 === filesize($filePath)) {
            return false;
        }

        $fileContent = @file_get_contents($filePath, false, null, 0, $readBytes);
        if (false === $fileContent) {
            return false;
        }

        $possibleTags = ['<?php', '<?=', '<? '];

        foreach ($possibleTags as $tag) {
            if (false !== strpos($fileContent, $tag)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Safely require a file that should return an array.
     *
     * @param string $filePath The path to the PHP file that returns an array.
     *
     * @throws RuntimeException         If the file does not exist or is not readable.
     * @throws UnexpectedValueException If the file does not return an array.
     *
     * @return array The array loaded from the file.
     */
    public static function loadArrayFile(string $filePath): array
    {
        if (! self::isPhpFile($filePath)) {
            return [];
        }

        // 'require' the file, which should return an array
        $data = require $filePath;

        if (!\is_array($data)) {
            throw new \UnexpectedValueException("Expected array, got " . \gettype($data) . " from file: {$filePath}");
        }

        return $data;
    }

    protected function appSettingsFileArray(): array
    {
        $optionsFile = $this->configsPath . '/app.php';

        return self::loadArrayFile($optionsFile);
    }

    protected function setMiddlewares(array $defaultMiddlewares): array
    {
        $middlewareFile = $this->configsPath . '/middleware.php';
        $appMiddleware = self::loadArrayFile($middlewareFile);

        return array_merge($defaultMiddlewares, $appMiddleware);
    }

    /**
     * @return static
     */
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

    /**
     * Generates a hashed Adminer URL using a specified hashing algorithm.
     *
     * @param string $hashAlgo The hashing algorithm to use (e.g., 'fnv1a64', 'sha256', 'md5').
     *
     * @return string The hashed database URL.
     */
    private static function dbUrl(string $hashAlgo): string
    {
        if (env('SECURE_AUTH_SALT')) {
            return hash($hashAlgo, urlencode(env('SECURE_AUTH_SALT')));
        }

        // TODO we need alternative when running in hibridx mode.
        return 'dbadmin';
    }

    private static function getDefaultMiddlewares()
    {
        return [
            'security' => \WPframework\Middleware\SecurityHeadersMiddleware::class,
            'spam' => \WPframework\Middleware\SpamDetectionMiddleware::class,
            'tenant' => \WPframework\Middleware\TenantMiddleware::class,
            'ignit' => \WPframework\Middleware\IgnitionMiddleware::class,
            'kiosk' => \WPframework\Middleware\KioskMiddleware::class,
            'status' => \WPframework\Middleware\StatusMiddleware::class,
            'config' => \WPframework\Middleware\ConstMiddleware::class,
            'kernel' => \WPframework\Middleware\KernelMiddleware::class,
            'auth' => \WPframework\Middleware\AuthMiddleware::class,
            'logger' => \WPframework\Middleware\LoggingMiddleware::class,
            'shortinit' => \WPframework\Middleware\ShortInitMiddleware::class,
            'adminer' => \WPframework\Middleware\AdminerMiddleware::class,
            'webhook' => \WPframework\Middleware\GitHubWebhookMiddleware::class,
            'whoops' => \WPframework\Middleware\WhoopsMiddleware::class,
        ];
    }
}
