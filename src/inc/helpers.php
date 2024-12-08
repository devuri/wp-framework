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
use Psr\Http\Message\RequestInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Urisoft\DotAccess;
use Urisoft\Encryption;
use Urisoft\Env;
use WPframework\AppInit;
use WPframework\Http\Asset;
use WPframework\Logger\FileLogger;
use WPframework\Logger\Log;
use WPframework\Support\Configs;
use WPframework\Terminate;

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

    if (empty($_ENV)) {
        return $default;
    }

    static $env_instance = null;
    if (null === $env_instance) {
        $env_instance = new Env(envWhitelist(), $encryption_path, false);
    }

    try {
        $env_var = $env_instance->get($name, $default, $encrypt, $strtolower);
    } catch (Exception $e) {
        Terminate::exit($e, 500);
    }

    return $env_var;
}

function envWhitelist(): array
{
    static $whitelist;
    static $whitelisted;

    if (\is_null($whitelist)) {
        $framework = new Urisoft\SimpleConfig(_configsDir(), ['whitelist']);
        // $app = new Urisoft\SimpleConfig( appOptionsDir(), ['whitelist'] );
        $whitelist = $framework->get('whitelist');
    }

    if (\is_null($whitelisted)) {
        $whitelisted = array_merge($whitelist['framework'], $whitelist['wp']);
    }

    return $whitelisted;
}

function appOptionsDir(): ?string
{
    return \defined('SITE_CONFIGS_DIR') ? SITE_CONFIGS_DIR : null;
}

/**
 * Retrieves configuration data using dot notation.
 *
 * This function allows easy access to nested configuration data through dot notation syntax.
 *
 * @return mixed The full configuration object is returned.
 *
 * @see https://github.com/devuri/dot-access DotAccess library for dot notation access.
 */
function configs()
{
    static $siteConfig = null;

    if (null === $siteConfig) {
        $siteConfig = new Configs(['tenancy', 'tenants', 'kiosk'], APP_DIR_PATH);
    }

    return $siteConfig;
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
 * @return false|string Returns a string containing the calculated hash value.
 *
 * @see https://www.php.net/manual/en/function.hash-hmac.php
 */
function envHash($data, ?string $secretkey = null, string $algo = 'sha256')
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

function _configsDir(): string
{
    return  __DIR__ . '/configs';
}

/**
 * Determines if the application is configured to operate in multi-tenant mode.
 *
 * This is based on the presence and value of the `IS_MULTITENANT` constant.
 * If `IS_MULTITENANT` is defined and set to `true`, the application is
 * considered to be in multi-tenant mode.
 *
 * @return bool Returns `true` if the application is in multi-tenant mode, otherwise `false`.
 */
function isMultitenantApp(): bool
{
    return \defined('IS_MULTITENANT') && true === \constant('IS_MULTITENANT');
}

function getWpframeworkHttpEnv(): ?string
{
    if ( ! \defined('HTTP_ENV_CONFIG')) {
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
 * @return (mixed|string)[]
 *
 * @psalm-return array{basedir: 'public/content/tenant/id/uploads', baseurl: string, path: string, url: string,...}
 */
function setMultitenantUploadDirectory($dir): array
{
    $custom_dir = '/' . APP_TENANT_ID . '/uploads';

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
function frameworkFooterLabel(): string
{
    $home_url   = esc_url(home_url());
    $date_year  = gmdate('Y');
    $site_name  = esc_html(get_bloginfo('name'));

    $httpEnvConfig = \defined('HTTP_ENV_CONFIG') ? HTTP_ENV_CONFIG : null;
    $tenantId = \defined('APP_TENANT_ID') ? APP_TENANT_ID : null;

    // admin only info.
    if (current_user_can('manage_options')) {
        $tenant_id = $tenantId;
        $http_env  = strtolower(esc_html($httpEnvConfig));
    } else {
        $tenant_id = null;
        $http_env  =  null;
    }

    return wp_kses_post("&copy; $date_year <a href=\"$home_url\" target=\"_blank\">$site_name</a> " . __('All Rights Reserved.', 'wp-framework') . " $tenant_id $http_env");
}

/**
 * @return (bool|string)[]
 *
 * @psalm-return array{available: bool, error_message?: 'The current active theme is not available.', theme_info?: string}
 */
function frameworkCurrentThemeInfo(): array
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

function exitWithThemeError(array $themeInfo): void
{
    $activeTheme = wp_get_theme();

    Terminate::exit(
        new Exception($themeInfo['error_message'] . ' -> ' . $activeTheme->template)
    );
}


if ( ! \function_exists('logMessage')) {
    /**
     * Logs a message with the specified level and an optional log file.
     *
     * @param string      $level   The log level (e.g., 'info', 'error', 'debug', etc.).
     * @param string      $message The log message.
     * @param array       $context Optional context data for the log message.
     * @param null|string $logFile Optional log file to use. If null, the default or fallback will be used.
     */
    function logMessage(string $message, string $level = 'info', array $context = [], ?string $logFile = null): void
    {
        if ($logFile) {
            Log::init(new FileLogger($logFile));
        } else {
            Log::init(new FileLogger());
        }

        switch ($level) {
            case LogLevel::EMERGENCY:
                Log::emergency($message, $context);

                break;
            case LogLevel::ALERT:
                Log::alert($message, $context);

                break;
            case LogLevel::CRITICAL:
                Log::critical($message, $context);

                break;
            case LogLevel::ERROR:
                Log::error($message, $context);

                break;
            case LogLevel::WARNING:
                Log::warning($message, $context);

                break;
            case LogLevel::NOTICE:
                Log::notice($message, $context);

                break;
            case LogLevel::INFO:
                Log::info($message, $context);

                break;
            case LogLevel::DEBUG:
                Log::debug($message, $context);

                break;
            default:
                // Handle invalid log level
                throw new InvalidArgumentException("Invalid log level: $level");
        }
    }
}

function logWithStackTrace(): void
{
    $trace = debug_backtrace();
    // error_log('Requested URI: ' . $_SERVER['REQUEST_URI']);
    foreach ($trace as $index => $frame) {
        $file = $frame['file'] ?? '[internal function]';
        $line = $frame['line'] ?? 'N/A';
        $function = $frame['function'] ?? 'N/A';
        error_log("#{$index} {$file}({$line}): {$function}()");
    }
}

function customHeaderMiddleware(AppInit $app): void
{
    $app->withMiddleware(function (RequestInterface $request, $handler) {
        $response = $handler->handle($request);

        return $response->withHeader('X-Custom-Header', 'MyCustomValue');
    }, 'custom-header');
}
