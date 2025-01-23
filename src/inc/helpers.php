<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Psr\Http\Message\RequestInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Urisoft\DotAccess;
use Urisoft\Encryption;
use Urisoft\Env;
use WPframework\App;
use WPframework\Http\Asset;
use WPframework\Logger\FileLogger;
use WPframework\Logger\Log;
use WPframework\Support\Configs;
use WPframework\Terminate;

/**
 * The Asset url.
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
    $encryptionPath = \defined('APP_ENCRYPTION_PATH') ? APP_ENCRYPTION_PATH : null;
    $envVal = null;

    if (\is_null($encryptionPath) || ! is_dir($encryptionPath)) {
        $encryptionPath = APP_DIR_PATH;
    }

    if (empty($_ENV)) {
        return $default;
    }

    static $envInstance;
    if (null === $envInstance) {
        $cfgs = configs();
        $envInstance = new Env($cfgs->config['whitelist'], $encryptionPath, false);
    }

    try {
        $envVal = $envInstance->get($name, $default, $encrypt, $strtolower);
    } catch (Exception $e) {
        throw new \InvalidArgumentException($e->getMessage());
    }

    return $envVal;
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

function localConfigsDir(): string
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
 * @return (mixed|string)[]
 *
 * @psalm-return array{basedir: 'public/content/id/uploads', baseurl: string, path: string, url: string,...}
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


if (! \function_exists('logMessage')) {
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

function customHeaderMiddleware(App $app): void
{
    $app->withMiddleware(function (RequestInterface $request, $handler) {
        $response = $handler->handle($request);

        return $response->withHeader('X-Custom-Header', 'MyCustomValue');
    }, 'custom-header');
}

function toMillisecond(float $seconds)
{
    return $seconds * 1000;
}

/**
 * Retrieves the Twig configuration file path.
 *
 * This function allows users to define their own Twig configuration file.
 * If a custom configuration file exists at the specified path, it will be used.
 * Otherwise, the default framework Twig configuration file is returned.
 *
 * @return null|Twigit\Twigit The file path to the Twig configuration file.
 */
function twigit(): ?Twigit\Twigit
{
    $userTwigFile = APP_DIR_PATH . '/configs/twig.php';
    $coreTwigFile = SRC_CONFIGS_DIR . DIRECTORY_SEPARATOR . 'twig.php';

    if (file_exists($userTwigFile)) {
        $twig = $userTwigFile;
    } elseif (file_exists($coreTwigFile)) {
        $twig = $coreTwigFile;
    }

    // @phpstan-ignore-next-line
    $twigInstance = require $twig;

    if ($twigInstance instanceof Twigit\Twigit) {
        return $twigInstance;
    }

    return null;
}

/**
 * Initializes and returns a Twig environment instance.
 *
 * This function configures the Twig environment using the specified templates directory path
 * and optional environment settings.
 *
 * Twig environment options can be passed as an associative array to customize the
 * behavior of the environment. Refer to the Twig documentation for a full list
 * of available options.
 *
 * @see https://twig.symfony.com/doc/3.x/api.html#environment-options Official Twig Environment Documentation.
 * @see  https://github.com/twigphp/Twig/blob/3.x/src/Environment.php#L112 Twig Environment Source Code.
 *
 * @param array $options
 * @param array $templates
 *
 * @throws Exception If the templates directory does not exist or if an error occurs while
 *                   initializing the Twig loader.
 *
 * @return Twigit\Twigit The initialized Twig environment instance.
 */
function twig(array $options = [], array $templates = []): Twigit\Twigit
{
    $cfgs = configs()->app();
    /*
     * https://twig.symfony.com/doc/3.x/api.html#environment-options
     * @see https://github.com/twigphp/Twig/blob/3.x/src/Environment.php#L112
     */
    if (empty($options)) {
        $env_options = $cfgs->config['app']->get('twig.env_options', []);
    } else {
        $env_options = $options;
    }

    if (!class_exists(Twigit\Twigit::class)) {
        throw new LogicException('The "devuri/twigit" package is required to use Twig. Run the command "composer require devuri/twigit".');
    }

    return Twigit\Twigit::init(APP_DIR_PATH, $env_options, $templates);
}
