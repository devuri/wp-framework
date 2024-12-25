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
use WPframework\App;
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

    static $env_instance;
    static $whitelisted;
    if (null === $env_instance) {
        $cfgs = configs();
        $env_instance = new Env($cfgs->config['whitelist'], $encryption_path, false);
    }

    try {
        $env_var = $env_instance->get($name, $default, $encrypt, $strtolower);
    } catch (Exception $e) {
        throw new \InvalidArgumentException($e->getMessage());
    }

    return $env_var;
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
 * @param string $template The template name or identifier (currently unused in this function).
 *
 * @return string The file path to the Twig configuration file.
 */
function twigit($template)
{
    $userTwigFile = APP_DIR_PATH . '/configs/twig.php';

    if (file_exists($userTwigFile)) {
        return $userTwigFile;
    }

    return CONFIGS_DIR_PATH . 'twig.php';
}

/**
 * Initializes and returns a Twig environment instance.
 *
 * This function sets up the Twig environment using the templates directory
 * defined in the application's configuration. If the templates directory
 * does not exist, the application is terminated with an exception.
 * Any errors encountered while creating the Twig loader also terminate the application.
 *
 * @throws Exception If the templates directory does not exist or if there is an error
 *                   initializing the Twig loader.
 *
 * @return Twig\Environment The initialized Twig environment instance.
 */
function twig(array $options = [])
{
    $cfgs = configs()->app();
    $templatesDir = $cfgs->getAppPath() . '/templates';
    $coreTemplatesDir = __DIR__ . '/templates';

    $options = array_merge([
        'autoescape' => false,
        'cache' => APP_DIR_PATH . '/templates/cache',
    ], $options);

    /*
     * https://twig.symfony.com/doc/3.x/api.html#environment-options
     * @see https://github.com/twigphp/Twig/blob/3.x/src/Environment.php#L112
     */
    if (empty($options)) {
        $env_options = $cfgs->config['app']->get('twig.env_options', []);
    } else {
        $env_options = $options;
    }

    if ( ! is_dir($templatesDir)) {
        Terminate::exit(new Exception("Templates directory does not exist: {$templatesDir}"));
    }

    try {
        $loader = new Twig\Loader\FilesystemLoader([$templatesDir, $coreTemplatesDir]);
        // $loader->addPath($coreTemplatesDir, 'kiosk');
    } catch (Exception $e) {
        Terminate::exit($e);
    }

    return new Twig\Environment($loader, $env_options);
}

/**
 * Renders the appropriate Twig template based on WordPress conditional tags.
 *
 * @param Twig\Environment $twig      The Twig environment instance.
 * @param array            $context   The context data to pass to the template.
 * @param array            $templates Optional. An associative array mapping WordPress conditional functions
 *                                    to their corresponding Twig template filenames.
 *                                    Defaults to predefined mappings.
 *
 * @throws Exception If the selected template file does not exist.
 */
function renderTwigTemplate(Twig\Environment $twig, array $context, array $templates = []): void
{
    $defaultTemplates = [
        'is_embed'             => 'embed.html.twig',
        'is_404'               => '404.html.twig',
        'is_search'            => 'search.html.twig',
        'is_front_page'        => 'front_page.html.twig',
        'is_home'              => 'home.html.twig',
        'is_privacy_policy'    => 'privacy_policy.html.twig',
        'is_post_type_archive' => 'post_type_archive.html.twig',
        'is_tax'               => 'taxonomy.html.twig',
        'is_attachment'        => 'attachment.html.twig',
        'is_single'            => 'single.html.twig',
        'is_page'              => 'page.html.twig',
        'is_category'          => 'category.html.twig',
        'is_tag'               => 'tag.html.twig',
        'is_author'            => 'author.html.twig',
        'is_date'              => 'date.html.twig',
        'is_archive'           => 'archive.html.twig',
    ];

    $templates = array_merge($defaultTemplates, $templates);

    $selectedTemplate = array_reduce(array_keys($templates), function ($selected, $condition) use ($templates) {
        if (\function_exists($condition) && $condition()) {
            return $templates[$condition];
        }

        return $selected;
    }, 'index.html.twig');

    $rendered = $twig->render($selectedTemplate, $context);

    twigLayout($rendered);
}

function twigContext(): array
{
    return [
        // Site Information
        'site' => [
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url(),
        ],

        // User Information (if logged in)
        'user' => is_user_logged_in() ? [
            'name' => wp_get_current_user()->display_name,
            'email' => wp_get_current_user()->user_email,
            'logged_in' => true,
        ] : [
            'logged_in' => false,
        ],

        // Navigation Menu (Example for a menu registered as 'primary')
        'menu' => wp_get_nav_menu_items('primary') ?: [],

        // Page Information
        'page' => is_page() ? [
            'title' => get_the_title(),
            'content' => apply_filters('the_content', get_post_field('post_content')),
            'id' => get_the_ID(),
        ] : null,

        // Single Post Information
        'post' => is_single() ? [
            'title' => get_the_title(),
            'content' => apply_filters('the_content', get_post_field('post_content')),
            'author' => [
                'name' => get_the_author(),
                'url' => get_author_posts_url(get_the_author_meta('ID')),
            ],
            'date' => get_the_date(),
            'categories' => get_the_category(),
            'tags' => get_the_tags(),
        ] : null,

        // Archive Information
        'archive' => is_archive() ? [
            'title' => get_the_archive_title(),
            'description' => get_the_archive_description(),
            'posts' => array_map(function ($post) {
                return [
                    'title' => get_the_title($post),
                    'url' => get_permalink($post),
                    'excerpt' => get_the_excerpt($post),
                ];
            }, get_posts([
                'posts_per_page' => 10,
            ])),
        ] : null,

        // Search Information
        'search_query' => is_search() ? get_search_query() : null,
        'search_results' => is_search() ? array_map(function ($post) {
            return [
                'title' => get_the_title($post),
                'url' => get_permalink($post),
                'excerpt' => get_the_excerpt($post),
            ];
        }, get_posts([
            's' => get_search_query(),
            'posts_per_page' => 10,
        ])) : null,

        // Category or Tag Information
        'taxonomy' => is_category() || is_tag() || is_tax() ? [
            'name' => single_term_title('', false),
            'description' => term_description(),
            'posts' => array_map(function ($post) {
                return [
                    'title' => get_the_title($post),
                    'url' => get_permalink($post),
                    'excerpt' => get_the_excerpt($post),
                ];
            }, get_posts([
                'posts_per_page' => 10,
            ])),
        ] : null,

        // Date Archive Information
        'date' => is_date() ? [
            'year' => get_query_var('year'),
            'month' => get_query_var('monthnum'),
            'day' => get_query_var('day'),
            'posts' => array_map(function ($post) {
                return [
                    'title' => get_the_title($post),
                    'url' => get_permalink($post),
                    'excerpt' => get_the_excerpt($post),
                ];
            }, get_posts([
                'posts_per_page' => 10,
            ])),
        ] : null,

        // Author Information
        'author' => is_author() ? [
            'name' => get_the_author(),
            'bio' => get_the_author_meta('description'),
            'posts' => array_map(function ($post) {
                return [
                    'title' => get_the_title($post),
                    'url' => get_permalink($post),
                    'excerpt' => get_the_excerpt($post),
                ];
            }, get_posts([
                'author' => get_the_author_meta('ID'),
                'posts_per_page' => 10,
            ])),
        ] : null,
    ];
}


function twigLayout($rendered): void
{
    ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php echo wp_get_document_title(); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php echo $rendered; ?>

<?php wp_footer(); ?>
</body>
</html><?php
}
