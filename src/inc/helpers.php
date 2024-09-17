<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Defuse\Crypto\Key;
use Symfony\Component\Filesystem\Filesystem;
use Urisoft\DotAccess;
use Urisoft\Encryption;
use Urisoft\Env;
use WPframework\App;
use WPframework\EnvGenerator;
use WPframework\Framework;
use WPframework\Http\Asset;
use WPframework\Terminate;

// @codingStandardsIgnoreFile.

/**
 * The Asset url.
 *
 * You can configure the asset URL by setting the ASSET_URL in your .env
 * Or optionally in the main config file.
 *
 * @param string      $asset path to the asset like: "/images/thing.png"
 * @param null|string $path
 *
 * @return string
 */
function asset(string $asset, ?string $path = null): string
{
    return Asset::url($asset, $path);
}

/**
 * The Asset url only.
 *
 * @param null|string $path
 *
 * @return string
 */
function assetUrl(?string $path = null): string
{
    return Asset::url('/', $path);
}

/**
 * Retrieves a sanitized, and optionally encrypted or modified, environment variable by name.
 *
 * @param string $name       The name of the environment variable to retrieve.
 * @param mixed  $default    Default value to return if the environment variable is not set.
 * @param bool   $encrypt    Indicate if the value should be encrypted. Defaults to false.
 * @param bool   $strtolower Whether to convert the retrieved value to lowercase. Defaults to false.
 *
 * @throws InvalidArgumentException If the requested environment variable name is not in the whitelist
 *                                  or if encryption is requested but the encryption path is not defined.
 *
 * @return mixed The sanitized environment variable value, possibly encrypted or typecast,
 *               or transformed to lowercase if specified.
 */
function env($name, $default = null, $encrypt = false, $strtolower = false)
{
    $encryption_path = \defined('APP_PATH') ? APP_PATH : APP_DIR_PATH;
    $env_var = null;

    static $env_instance = null;
    if (null === $env_instance) {
        $env_instance = new Env(envWhitelist(), $encryption_path, false);
    }

    try {
        $env_var = $env_instance->get($name, $default, $encrypt, $strtolower);
    } catch (Exception $e) {
        $debug_info = [
            'path'      => $encryption_path,
            'line'      => __LINE__,
            'exception' => $e,
            'invalidfile' => "Missing env file: {$e->getMessage()}",
        ];
        Terminate::exit([ "Missing env() var: {$e->getMessage()}", 500, $debug_info ]);
    }

    return $env_var;
}

/**
 * Start and load core plugin.
 *
 * @return null|Framework
 */
function wpframeworkCore(): ?Framework
{
    if (! \defined('ABSPATH')) {
        exit;
    }

    return _wpframework();
}

/**
 * Get default app config values.
 *
 * @return (null|bool|mixed|(mixed|(mixed|string)[]|true)[]|string)[]
 *
 * @psalm-return array{security: array{'brute-force': true, 'two-factor': true, 'no-pwned-passwords': true, 'admin-ips': array<empty, empty>}, mailer: array{brevo: array{apikey: mixed}, postmark: array{token: mixed}, sendgrid: array{apikey: mixed}, mailerlite: array{apikey: mixed}, mailgun: array{domain: mixed, secret: mixed, endpoint: mixed, scheme: 'https'}, ses: array{key: mixed, secret: mixed, region: mixed}}, sudo_admin: mixed, sudo_admin_group: null, web_root: 'public', s3uploads: array{bucket: mixed, key: mixed, secret: mixed, region: mixed, 'bucket-url': mixed, 'object-acl': mixed, expires: mixed, 'http-cache': mixed}, asset_dir: 'assets', content_dir: 'app', plugin_dir: 'plugins', mu_plugin_dir: 'mu-plugins', sqlite_dir: 'sqlitedb', sqlite_file: '.sqlite-wpdatabase', default_theme: 'brisko', disable_updates: true, can_deactivate: false, theme_dir: 'templates', error_handler: null, redis: array{disabled: mixed, host: mixed, port: mixed, password: mixed, adminbar: mixed, 'disable-metrics': mixed, 'disable-banners': mixed, prefix: mixed, database: mixed, timeout: mixed, 'read-timeout': mixed}, publickey: array{'app-key': mixed}}
 */
function appConfig(?string $file_path = null, ?string $filename = null): array
{
    $site_configs_dir = siteConfigsDir();

    if (! $file_path && ! $filename) {
        // return default app array.
        return _defaultConfigs();
    }

    $options_file = "{$file_path}/{$site_configs_dir}/{$filename}.php";

    if (file_exists($options_file) && \is_array(@require $options_file)) {
        return require $options_file;
    }
    if (! file_exists($options_file)) {
        return _defaultConfigs();
    }

    return [];
}

function _defaultConfigs(): array
{
    $defaultConfigs_dir = _configsDir();

    return require $defaultConfigs_dir . '/app.php';
}

function envWhitelist(): array
{
    static $whitelist;
    static $whitelisted;

    if (\is_null($whitelist)) {
        $framework = new Urisoft\SimpleConfig(_configsDir(), ['whitelist']);
        // $app = new Urisoft\SimpleConfig( siteConfigsDir(), ['whitelist'] );
        $whitelist = $framework->get('whitelist');
    }

    if (\is_null($whitelisted)) {
        $whitelisted = array_merge($whitelist['framework'], $whitelist['wp']);
    }

    return $whitelisted;
}

function siteConfigsDir(): ?string
{
    return \defined('SITE_CONFIGS_DIR') ? SITE_CONFIGS_DIR : null;
}

/**
 * Retrieve configuration data using dot notation.
 *
 * This function provides a convenient way to access nested data stored in a configuration file
 * using dot notation. It uses the DotAccess library to facilitate easy access to the data.
 *
 * @param null|string $key     The dot notation key to access the data. If null, the entire
 *                             configuration data will be returned.
 * @param mixed       $default The default value to return if the key is not found.
 *
 * @return mixed The value associated with the specified key or the default value if the key
 *               is not found. If no key is provided (null), the entire configuration data is
 *               returned.
 *
 * @see https://github.com/devuri/dot-access DotAccess library used for dot notation access.
 */
function config(?string $key = null, $default = null)
{
    $_options = _wpframework()->options();

    if (\is_null($key)) {
        return $_options;
    }

    return $_options->get($key, $default);
}

/**
 * Gets hash of given string.
 *
 * If no secret key is provided we will use the SECURE_AUTH_KEY wp key.
 *
 * @param string $data      Message to be hashed.
 * @param string $secretkey Secret key used for generating the HMAC variant.
 * @param string $algo      Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..)
 *
 * @return string Returns a string containing the calculated hash value.
 *
 * @see https://www.php.net/manual/en/function.hash-hmac.php
 */
function envHash($data, ?string $secretkey = null, string $algo = 'sha256'): string
{
    if (\is_null($secretkey)) {
        return hash_hmac($algo, $data, env('SECURE_AUTH_KEY'));
    }

    return hash_hmac($algo, $data, $secretkey);
}

/**
 * Basic Sanitize and prepare for a string input for safe usage in the application.
 *
 * This function sanitizes the input by removing leading/trailing whitespace,
 * stripping HTML and PHP tags, converting special characters to HTML entities,
 * and removing potentially dangerous characters for security.
 *
 * @param string $input The input string to sanitize.
 *
 * @return string The sanitized input ready for safe usage within the application.
 */
function wpSanitize(string $input): string
{
    $input = trim($input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    $input = str_replace(["'", "\"", "--", ";"], "", $input);

    return filter_var($input, FILTER_UNSAFE_RAW, FILTER_FLAG_NO_ENCODE_QUOTES);
}

/**
 * Cleans up sensitive environment variables.
 *
 * This function removes specified environment variables from the $_ENV superglobal
 * and the environment to help secure sensitive information.
 *
 * @param array $sensitives An array of environment variable names to be cleaned up.
 */
function cleanSensitiveEnv(array $sensitives): void
{
    foreach ($sensitives as $var) {
        unset($_ENV[$var]);
        // Ensure to concatenate '=' to effectively unset it
        putenv($var . '=');
    }
}

/**
 * Retrieves all packages listed in the 'require' section of the composer.json file.
 *
 * @param string $app_path The path to the application root directory.
 *
 * @return array An array of required packages, or an empty array if the file doesn't exist or on error.
 */
function get_packages(string $app_path): array
{
    $composer_path = $app_path . DIRECTORY_SEPARATOR . 'composer.json';

    // Check if the composer.json file exists.
    if (! is_file($composer_path)) {
        return [];
    }

    // Attempt to decode the JSON content from composer.json.
    $composer_json = json_decode(file_get_contents($composer_path), true);

    // Check for JSON errors.
    if (JSON_ERROR_NONE !== json_last_error()) {
        error_log('json error');

        return [];
    }

    return $composer_json['require'] ?? [];
}

function _configsDir(): string
{
    return  __DIR__ . '/configs';
}

function _wpframework(?string $app_path = null): ?Framework
{
    static $framework;

    if (\is_null($framework)) {
        $framework = new Framework($app_path);
    }

    return $framework;
}


/**
 * Retrieves the default file names for environment configuration.
 *
 * This is designed to return an array of default file names
 * used for environment configuration in a WordPress environment.
 * These file names include various formats and stages of environment setup,
 * such as production, staging, development, and local environments.
 *
 * @since [version number]
 *
 * @return array An array of default file names for environment configurations.
 *               The array includes the following file names:
 *               - 'env'
 *               - '.env'
 *               - '.env.secure'
 *               - '.env.prod'
 *               - '.env.staging'
 *               - '.env.dev'
 *               - '.env.debug'
 *               - '.env.local'
 *               - 'env.local'
 */
function _supportedEnvFiles(): array
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
function _envFilesFilter(array $env_files, string $app_path): array
{
    foreach ($env_files as $key => $file) {
        if (! file_exists($app_path . '/' . $file)) {
            unset($env_files[ $key ]);
        }
    }

    return $env_files;
}

/**
 * Determines if the application is configured to operate in multi-tenant mode.
 *
 * This is based on the presence and value of the `ALLOW_MULTITENANT` constant.
 * If `ALLOW_MULTITENANT` is defined and set to `true`, the application is
 * considered to be in multi-tenant mode.
 *
 * @return bool Returns `true` if the application is in multi-tenant mode, otherwise `false`.
 */
function isMultitenantApp(): bool
{
    return \defined('ALLOW_MULTITENANT') && ALLOW_MULTITENANT === true;
}

function getWpframeworkHttpEnv(): ?string
{
    if (! \defined('HTTP_ENV_CONFIG')) {
        return null;
    }

    return strtoupper(HTTP_ENV_CONFIG);
}

/**
 * Sets the upload directory to a tenant-specific location.
 *
 * This function modifies the default WordPress upload directory paths
 * to store tenant-specific uploads in a separate folder based on the tenant ID.
 * It ensures that each tenant's uploads are organized and stored in an isolated directory.
 *
 * @param array $dir The array containing the current upload directory's path and URL.
 *
 * @return array The modified array with the new upload directory's path and URL for the tenant.
 */
function setMultitenantUploadDirectory($dir)
{
    $custom_dir = '/tenant/' . APP_TENANT_ID . '/uploads';

    // Set the base directory and URL for the uploads.
    $dir['basedir'] = WP_CONTENT_DIR . $custom_dir;
    $dir['baseurl'] = content_url() . $custom_dir;

    // Append the subdirectory to the base path and URL, if any.
    $dir['path'] = $dir['basedir'] . $dir['subdir'];
    $dir['url']  = $dir['baseurl'] . $dir['subdir'];

    return $dir;
}

/**
 * Custom admin footer text.
 *
 * @return string The formatted footer text.
 */
function _frameworkFooterLabel(): string
{
    $home_url   = esc_url(home_url());
    $date_year  = gmdate('Y');
    $site_name  = esc_html(get_bloginfo('name'));

    // admin only info.
    if (current_user_can('manage_options')) {
        $tenant_id = esc_html(APP_TENANT_ID);
        $http_env  = strtolower(esc_html(HTTP_ENV_CONFIG));
    } else {
        $tenant_id = null;
        $http_env  =  null;
    }

    return wp_kses_post("&copy; $date_year <a href=\"$home_url\" target=\"_blank\">$site_name</a> " . __('All Rights Reserved.', 'wp-framework') . " $tenant_id $http_env");
}

function _frameworkCurrentThemeInfo(): array
{
    $current_theme = wp_get_theme();

    // Check if the current theme is available
    if ($current_theme->exists()) {
        return [
            'available'  => true,
            'theme_info' => $current_theme->get('Name') . ' is available.',
        ];
    }

    return [
        'available'     => false,
        'error_message' => 'The current active theme is not available.',
    ];
}

/**
 * Regenerates the tenant-specific .env file if it doesn't exist.
 *
 * @param string $app_path
 * @param string $app_http_host
 * @param array  $available_files
 */
function tryRegenerateEnvFile(string $app_path, string $app_http_host, array $available_files = []): void
{
    $app_main_env_file = "{$app_path}/.env";
    if (! file_exists($app_main_env_file) && empty($available_files)) {
        $generator = new EnvGenerator(new Filesystem());
        $generator->create($app_main_env_file, $app_http_host);
    }
}
