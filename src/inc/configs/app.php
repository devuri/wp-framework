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

/*
 * Manage the framework’s application configuration settings.
 *
 * @see https://devuri.github.io/wpframework/guide/customization/config-overview
 */
return [

    /*
     * Configures the error handler for the project.
     *
     * This section defines how Errors are handled. You can specify:
     *   - The error handler class, which must extend AbstractError or implement
     *     Whoops\Handler\HandlerInterface.
     *   - Whether Whoops should quit after handling an error.
     *   - Whether error details should be logged.
     *   - Whether the error handler should be enabled at all (often tied to WP_DEBUG).
     *
     * Note: The error handler generally only runs in 'debug', 'development',
     * or 'local' environments, depending on your project's environment checks.
     */
    'error_handler' => [
        // Must be an instance of AbstractError or Whoops\Handler\HandlerInterface.
        // Possible Whoops handlers:
        //   WPframework\Error\ErrorHandler::class (default)
        //   WPframework\Error\TextHandler::class
        // Or custom Whoops handlers, such as:
        //   Whoops\Handler\JsonResponseHandler::class
        //   Whoops\Handler\PlainTextHandler::class
        //   Whoops\Handler\PrettyPageHandler::class
        'class'   => WPframework\Error\ErrorHandler::class,

        // Determines if whoops->allowQuit(true) is called.
        'quit'    => true,

        // Enables logging of errors.
        'logs'    => true,
    ],

    /*
     * Configuration settings for the Adminer database administrator interface.
     *
     * Adminer provides a web-based interface for managing databases. These settings
     * control its availability and behavior within the application. Note that regardless
     * of these settings, Adminer will never be accessible in secure environments (when
     * the application is running with the `secure` flag set). In such cases, these
     * settings will have no effect.
     */
    'dbadmin'     => [
        /*
         * Whether or not to enable the Adminer interface.
         *
         * Controls whether Adminer is accessible for database management.
         *
         * @var bool $enabled Default true.
         */
        'enabled' => true,

        /*
         * The URI path for accessing the Adminer interface.
         *
         * Defines the URI path that will be used to access Adminer.
         * The resulting URL will take the form `example.com/wp/wp-admin/{uri}`.
         *
         * @var string $uri Must be a valid string. Default is 'dbadmin'.
         */
        'uri' => env('ADMINER_URI', 'dbadmin'),

        /*
         * Whether to validate that the WordPress user is authenticated.
         *
         * Adds an additional layer of security by requiring authentication
         * before granting access to Adminer. This setting is useful when
         * `autologin` is enabled, as it ensures only authenticated users
         * with the necessary capabilities can access the interface.
         *
         * Note: Only users in the `kiosk` list with the `manage_database`
         * capability will be allowed access.
         *
         * @var bool $validate Default false.
         */
        'validate' => true,

        /*
         * Whether to enable autologin for the Adminer interface.
         *
         * When set to true, users can bypass the Adminer login screen.
         * Use with caution, as this can bypasses WordPress authentication,
         * potentially allowing access to anyone with the URL.
         *
         * The default behavior is to use ADMINER_ALLOW_AUTOLOGIN constant.
         * This constant is set by the framework but you can override it with
         * your own value in the `wp-config.php` or other upstream file.
         *
         * @var bool $autologin Default true.
         */
        'autologin' => ADMINER_ALLOW_AUTOLOGIN,

        /*
         * Optional passkey for generating signed access URLs.
         *
         * When set, this secret key allows for the creation of signed URLs
         * that bypass authentication for Adminer access. This is useful for
         * debugging or granting temporary access without requiring database
         * credentials. The framework validates these signed URLs before
         * granting access.
         *
         * @var string|null $secret Default null.
         */
        'secret' => [
            'key' => env('ADMINER_SECRET', null),
            'type' => 'jwt',
        ],
    ],

    /*
     * Configuration for the application's health status middleware.
     *
     * This configuration determines whether the application should run an internal middleware
     * to verify the application's health status. The middleware is triggered exclusively for
     * requests to the `/up` route (or the custom route defined in this configuration).
     *
     * When enabled:
     * - The middleware checks if the application is operational.
     * - Verifies database connectivity.
     *
     * ## Conditions for Activation
     * 1. The `enabled` key must be set to `true`.
     * 2. The route in the request must explicitly match the `route` key in this configuration.
     *
     * If these conditions are satisfied, the application performs a partial boot to verify
     * its status. Only a JSON response is returned, allowing monitoring tools to efficiently
     * query the application's health, e.g., `example.com/up`.
     *
     * ## Configuration Options
     * - `enabled` (bool): Whether to enable the health status middleware. Defaults to `true`.
     * - `secret` (string|null): An optional secret key for securing health status checks.
     *   Use `env('HEALTH_STATUS_SECRET')` to configure via environment variables. Defaults to `null`.
     * - `route` (string): The route for the health status endpoint. Defaults to `'up'`.
     *
     * ## Future Considerations
     * - Adding authentication, such as a secret key, to restrict access to the health status endpoint.
     * - Implementing rate limiting to prevent misuse or abuse of the health status endpoint.
     *
     * Example Usage:
     * ```
     * 'health_status' => [
     *     'enabled' => true,
     *     'secret' => env('HEALTH_STATUS_SECRET', null),
     *     'route' => 'up',
     * ],
     * ```
     */
    'health_status' => [
        'enabled' => true,
        'secret' => env('HEALTH_STATUS_SECRET', null),
        'route' => 'up',
    ],

    /*
     * List of production environment identifiers.
     *
     * This configuration specifies the identifiers that represent production
     * environments. These identifiers are used to determine the application's
     * operational environment and tailor functionality accordingly.
     *
     * @var array<string> $prod List of production environment identifiers.
     */
    'prod' => ['secure', 'sec', 'production', 'prod'],

    /*
     * Determines whether to display error details upon application termination.
     * Enable this setting only during development, it should never be active in a production environment.
     * Always ensure this is set to false in production for security and privacy.
     */
    'terminate'        => [
        'debugger' => false,
    ],

    'twig' => [
        // https://twig.symfony.com/doc/3.x/api.html#environment-options
        'env_options' => [
            /*
             * Enables debugging.
             * When set to true, generated templates have a __toString() method that displays the
             * generated nodes for easier debugging.
             * Default: false.
             */
            'debug' => false,

            /*
             * Specifies the character encoding used by the templates.
             * Default: 'utf-8'.
             */
            'charset' => 'utf-8',

            /*
             * Defines the path to store compiled templates.
             * Use an absolute path or set to false to disable caching.
             * Default: false (caching disabled).
             */
            'cache' => false,

            /*
             * Automatically recompiles templates whenever the source code changes.
             * If not set, this value defaults to true when 'debug' is enabled, and false otherwise.
             */
            'auto_reload' => null, // Defaults to the value of 'debug'.

            /*
             * Controls how invalid variables are handled.
             * If set to false, Twig replaces invalid variables with null without throwing an error.
             * If true, Twig throws an exception for undefined variables or attributes.
             * Default: false.
             */
            'strict_variables' => false,

            /*
             * Sets the default strategy for auto-escaping content in templates.
             * Options include 'html', 'js', 'css', 'url', 'html_attr', or a custom PHP callback.
             * Use false to disable auto-escaping.
             * Default: determined automatically based on template filename extensions.
             */
            'autoescape' => 'html', // Default: 'html'.

            /*
             * Controls template compilation optimizations.
             * A value of -1 applies all optimizations, while 0 disables them.
             * Default: -1 (all optimizations enabled).
             */
            'optimizations' => -1,
        ],

    ],

    'directory'        => [
        'wp_dir_path'   => 'wp',
        /*
         * Web Root: the public web directory.
         *
         * By default, the project's web root is set to "public". If you change this to something other than "public",
         * you will also need to edit the composer.json file. For example, if our web root is "public_html", the relevant
         * composer.json entries would be:
         *
         * "wordpress-install-dir": "public_html/wp",
         * "installer-paths": {
         *     "public_html/content/mu-plugins/{$name}/": [
         *         "type:wordpress-muplugin"
         *     ],
         *     "public_html/content/plugins/{$name}/": [
         *         "type:wordpress-plugin"
         *     ],
         *     "public_html/template/{$name}/": [
         *         "type:wordpress-theme"
         *     ]
         * }
         */
        'web_root_dir'  => env('WEB_ROOT_DIR', 'public'),

        /*
         * Sets the content directory for the project.
         *
         * By default, the project uses the 'app' directory as the content directory.
         * The 'app' directory is equivalent to the 'wp-content' directory.
         * However, this can be modified to use a different directory, such as 'content'.
         */
        'content_dir'   => env('CONTENT_DIR', 'wp-content'),

        /*
         * Sets the plugins directory.
         *
         * The plugins directory is located outside the project directory and
         * allows for installation and management of plugins using Composer.
         */
        'plugin_dir'    => env('PLUGIN_DIR', 'wp-content/plugins'),

        /*
         * Sets the directory for Must-Use (MU) plugins.
         *
         * The MU plugins directory is used to include custom logic that is considered essential for the project.
         * It provides a way to include functionality that should always be active and cannot be deactivated by site administrators.
         *
         * By default, the framework includes the 'compose' MU plugin, which includes the 'web_app_config' hook.
         * This hook can be leveraged to configure the web application in most cases.
         */
        'mu_plugin_dir' => env('MU_PLUGIN_DIR', 'wp-content/mu-plugins'),

        /*
         * SQLite Configuration
         *
         * WordPress supports SQLite via a plugin (which might soon be included in core).
         * These options need to be set when using the drop-in SQLite database with WordPress.
         * The SQLite database location and filename can be configured here.
         * The `sqlite_dir` directory is relative to `APP_PATH`.
         *
         * @see https://github.com/aaemnnosttv/wp-sqlite-db
         */
        'sqlite_dir'    => env('SQLITE_DIR', 'sqlitedb'),
        'sqlite_file'   => env('SQLITE_FILE', '.sqlite-wpdatabase'),

        /*
         * Sets the directory for additional themes.
         *
         * In addition to the default 'themes' directory, we can utilize the 'templates' directory
         * to include our own custom themes for the project. This provides flexibility and allows
         * us to have a separate location for our custom theme files.
         */
        'theme_dir'     => env('THEME_DIR', 'templates'),

        /*
         * Global assets directory.
         *
         * This configuration allows us to define a directory for globally accessible assets.
         * If we are using build tools like webpack, mix, vite, etc., this directory can be used to store compiled assets.
         * The path is relative to the `web_root` setting, so if our web root is `public`, assets would be in `public/assets`.
         *
         * The asset URL can be configured by setting the ASSET_URL in your .env file.
         *
         * Global helpers can be used in the web application to interact with these assets:
         *
         * - asset($asset): Returns the full URL of the asset. The $asset parameter is the path to the asset, e.g., "/images/thing.png".
         *   Example: asset("/images/thing.png") returns "https://example.com/assets/dist/images/thing.png".
         *
         * - assetUrl($path): Returns the asset URL without the filename. The $path parameter is the path to the asset.
         *   Example: assetUrl("/dist") returns "https://example.com/assets/dist/".
         */
        'asset_dir'     => env('ASSET_DIR', 'assets'),

        /*
         * Defines the public key directory.
         *
         * This is the directory where we store out public key files.
         * the directory here is relative to the application root path
         */
        'publickey_dir' => env('PUBLICKEY_DIR', 'pubkeys'),
    ],

    /*
     * Sets the default fallback theme for the project.
     *
     * By default, WordPress uses one of the "twenty*" themes as the fallback theme.
     * However, in our project, we have the flexibility to define our own custom fallback theme.
     */
    'default_theme'    => env('DEFAULT_THEME', 'twentytwentythree'),

    /*
     * Disable WordPress updates.
     *
     * Since we will manage updates with Composer,
     * it is recommended to disable all updates within WordPress.
     */
    'disable_updates'  => env('DISABLE_UPDATES', true),

    /*
     * Controls whether we can deactivate plugins.
     *
     * This setting determines whether the option to deactivate plugins is available.
     * Setting it to false will hide the control to deactivate plugins,
     * but it does not remove the functionality itself.
     *
     * Setting it to true brings back the ability to deactivate plugins.
     * The default setting is true.
     */
    'can_deactivate'   => env('CAN_DEACTIVATE', true),

    /*
     * Security settings for the WordPress application.
     *
     * This array contains various security settings to enhance the security of the WordPress application.
     *
     * @var array $security {
     *     An array of security settings.
     *
     *     @type string|null $encryption_key  Full path to encryption key file (.txt) e.g., 'home/user/etc/.myweb-app-secret'
     *                                        This will become home/user/etc/.myweb-app-secret.txt.
     *                                        Set to null if encryption key is not defined.
     *     @type bool $brute-force            Whether to enable brute force protection.
     *     @type bool $two-factor             Whether to enable two-factor authentication.
     *     @type bool $no-pwned-passwords     Whether to check for passwords that have been exposed in data breaches.
     *     @type array|null $admin-ips        An array of IP addresses allowed for administrative access.
     *                                        Set to null or an empty array to disable the feature.
     *                                        Format: ['192.168.000.41', '192.168.000.34']
     * }
     */
    'security'         => [
        /*
         * Configuration for restricting access to the WordPress admin area.
         *
         * This array defines settings to control access to the wp-admin area, with options
         * for enabling restrictions, securing access, and specifying allowed paths.
         *
         * @property array $restrict_wpadmin The settings for restricting wp-admin access.
         * @property bool  $restrict_wpadmin['enabled'] Whether wp-admin restrictions are enabled. Default false.
         * @property bool  $restrict_wpadmin['secure'] When true, all wp-admin access is disallowed,
         *                                             ignoring any allowed paths. Use with caution. Default false.
         * @property array $restrict_wpadmin['allowed'] A list of wp-admin paths that are allowed even when
         *                                              restrictions are enabled. This is particularly useful for
         *                                              paths like `admin-ajax.php` that may be needed for plugins
         *                                              or unauthenticated data processing. Plugins may also have
         *                                              created their own endpoints relative to `wp-admin`, for
         *                                              those use case you can also add them to this array.
         *                                              Example:
         *                                              - `'admin-ajax.php'`: Typically used by plugins to process data
         *                                                without authentication.
         *
         * Note: Paths in the 'allowed' array should not include the full URL. Instead, specify only the relative path,
         * e.g., `'admin-ajax.php'`. The wp-admin URL is generally structured as:
         * `example.com/wp/wp-admin/{path_to_allow}`.
         */

        'restrict_wpadmin' => [
            'enabled' => false,
            'secure' => false,
            'allowed' => [
                'admin-ajax.php',
            ],
        ],
        'sucuri_waf'         => false,
        'encryption_key'     => null,
        'brute-force'        => true,
        'two-factor'         => true,
        'no-pwned-passwords' => true,
        'admin-ips'          => [],
    ],

    /*
     * Email SMTP configuration for WordPress.
     *
     * Configure the mailer settings for sending emails in WordPress using various providers such as Brevo, Postmark,
     * SendGrid, Mailgun, and SES.
     *
     * Available providers:
     * - 'brevo': Brevo mailer using the API key specified in the environment variable 'BREVO_API_KEY'.
     * - 'postmark': Postmark mailer using the token specified in the environment variable 'POSTMARK_TOKEN'.
     * - 'sendgrid': SendGrid mailer using the API key specified in the environment variable 'SENDGRID_API_KEY'.
     * - 'mailgun': Mailgun mailer using the domain, secret, endpoint, and scheme specified in the respective
     *              environment variables 'MAILGUN_DOMAIN', 'MAILGUN_SECRET', 'MAILGUN_ENDPOINT', and 'MAILGUN_SCHEME'.
     * - 'ses': SES (Amazon Simple Email Service) mailer using the access key, secret access key, and region specified
     *          in the respective environment variables 'AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', and 'AWS_DEFAULT_REGION'.
     *
     * Note: Make sure to set the required environment variables for each mailer provider.
     */

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

    /*
     * Sudo Admin: The main administrator or developer.
     *
     * By default, all admin users are considered equal in WordPress. However, this option allows us to create
     * a higher level of administrative privileges for a specific user.
     *
     * @var int|null The user ID of the sudo admin. Setting it to null disables the sudo admin feature.
     *
     * @default null
     */
    'sudo_admin'       => env('SUDO_ADMIN', 1),

    /*
     * Sudo Admin Group: A group of users with higher administrative privileges.
     *
     * This option allows us to define a group of users with elevated administrative privileges,
     * in addition to the main sudo admin user defined in the 'sudo_admin' option.
     * The value should be an array of user IDs.
     *
     * @var array|null An array of user IDs representing the sudo admin group. Setting it to null disables the sudo admin group feature.
     *
     * @default null
     */
    'sudo_admin_group' => null,

    /*
     * Configuration settings for the S3 Uploads plugin.
     *
     * @var array $s3_uploads
     *   Configuration options for S3 Uploads.
     *
     * @param string $s3_uploads['bucket']
     *   The name of the S3 bucket to upload files to. Defaults to 'site-uploads'.
     *
     * @param string $s3_uploads['key']
     *   The AWS access key ID. Defaults to an empty string.
     *
     * @param string $s3_uploads['secret']
     *   The AWS secret access key. Defaults to an empty string.
     *
     * @param string $s3_uploads['region']
     *   The AWS region to use. Defaults to 'us-east-1'.
     *
     * @param string $s3_uploads['bucket-url']
     *   The base URL of the S3 bucket. Defaults to 'https://example.com'.
     *
     * @param string $s3_uploads['object-acl']
     *   The access control list for uploaded objects. Defaults to 'public'.
     *
     * @param string $s3_uploads['expires']
     *   The expiration time for HTTP caching headers. Defaults to '2 days'.
     *
     * @param string $s3_uploads['http-cache']
     *   The value for the 'Cache-Control' header. Defaults to '300'.
     */
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

    /*
     * Redis cache configuration for the WordPress application.
     *
     * This array contains configuration settings for the Redis cache integration in WordPress.
     * For detailed installation instructions, refer to the documentation at:
     * {@link https://github.com/rhubarbgroup/redis-cache/blob/develop/INSTALL.md}
     *
     * @var array $redis {
     *     An array of Redis cache configuration settings.
     *
     *     @type bool $disabled            Whether Redis cache is disabled.
     *                                    Default: false if the environment variable 'WP_REDIS_DISABLED' is not set.
     *     @type string $host              The Redis server hostname or IP address.
     *                                    Default: '127.0.0.1' if the environment variable 'WP_REDIS_HOST' is not set.
     *     @type int $port                 The Redis server port number.
     *                                    Default: 6379 if the environment variable 'WP_REDIS_PORT' is not set.
     *     @type string $password          The password to authenticate with Redis.
     *                                    Default: '' (empty string) if the environment variable 'WP_REDIS_PASSWORD' is not set.
     *                                    Using the phpredis extension for Redis.
     *     @type bool $adminbar            Whether to disable Redis cache for the WordPress admin bar.
     *                                    Default: false if the environment variable 'WP_REDIS_DISABLE_ADMINBAR' is not set.
     *     @type bool $disable-metrics     Whether to disable Redis cache metrics.
     *                                    Default: false if the environment variable 'WP_REDIS_DISABLE_METRICS' is not set.
     *     @type bool $disable-banners     Whether to disable Redis cache banners.
     *                                    Default: false if the environment variable 'WP_REDIS_DISABLE_BANNERS' is not set.
     *     @type string $prefix            The Redis cache key prefix.
     *                                    Default: MD5 hash of 'HOME_URL' environment variable concatenated with 'redis-cache'
     *                                    if the environment variable 'WP_REDIS_PREFIX' is not set.
     *     @type int $database             The Redis database index to use (0-15).
     *                                    Default: 0 if the environment variable 'WP_REDIS_DATABASE' is not set.
     *     @type int $timeout              The Redis connection timeout in seconds.
     *                                    Default: 1 if the environment variable 'WP_REDIS_TIMEOUT' is not set.
     *     @type int $read-timeout         The Redis read timeout in seconds.
     *                                    Default: 1 if the environment variable 'WP_REDIS_READ_TIMEOUT' is not set.
     * }
     */
    'redis'            => [
        'disabled'        => env('WP_REDIS_DISABLED', false),
        'host'            => env('WP_REDIS_HOST', '127.0.0.1'),
        'port'            => env('WP_REDIS_PORT', 6379),
        'password'        => env('WP_REDIS_PASSWORD', ''),
        'adminbar'        => env('WP_REDIS_DISABLE_ADMINBAR', false),
        'disable-metrics' => env('WP_REDIS_DISABLE_METRICS', false),
        'disable-banners' => env('WP_REDIS_DISABLE_BANNERS', false),
        'prefix'          => env('WP_REDIS_PREFIX', md5((string) env('HOME_URL')) . 'redis-cache'),
        'database'        => env('WP_REDIS_DATABASE', 0),
        'timeout'         => env('WP_REDIS_TIMEOUT', 1),
        'read-timeout'    => env('WP_REDIS_READ_TIMEOUT', 1),
    ],

    /*
     * Represents a public key used for encryption or verification purposes.
     * The public key can be stored as an option in the WordPress options table.
     *
     * The framework assumes that the public keys are stored in a top-level directory called "publickeys" in either the .pub or .pem format.
     * The keys can be retrieved and used as needed. Plugins can be used to fetch and save the keys.
     *
     * IMPORTANT: If you decide to save these keys, use the base64_encode function.
     * base64_encode is a function commonly used to encode binary data into a text format that can be safely stored or transmitted in various systems.
     * It takes binary data as input and returns a string consisting of characters from a predefined set (64 characters).
     * This encoding process ensures that the encoded data remains intact and can be decoded back into its original form when needed.
     *
     * use the command to generate key files: php nino config create-public-key
     * This will generate a sample key with uuid filename, replace the sample key with your own and add the filename to env file.
     *
     * @var array $publickey An array containing the UUID of the public key stored as an option in the WordPress options table.
     */
    'publickey'        => [
        'app-key' => env('WEB_APP_PUBLIC_KEY', null),
    ],

    /*
     * Configuration array for enabling and customizing headless mode in the application.
     *
     * This configuration allows developers to enable or disable headless mode, manage
     * REST API and GraphQL settings, control plugin loading, handle localization,
     * enable debugging, customize error handling, and configure security features
     * such as CORS.
     *
     * Array structure:
     * - 'enabled' (bool): Toggles the entire headless mode functionality.
     *
     * - 'rest_api' (array): Configures the REST API settings.
     *   - 'enabled' (bool): Enables or disables the REST API.
     *   - 'cache' (bool): Determines whether API responses should be cached. Optional.
     *
     * - 'graphql' (array): Configures the GraphQL API settings.
     *   - 'enabled' (bool): Enables or disables the GraphQL API, if available.
     *
     * - 'themes' (bool): When `false`, theme loading is skipped, which can improve
     *   performance in headless setups.
     *
     * - 'plugins' (array): Configures plugin loading behavior.
     *   - 'load' (array): An explicit list of plugins to load. Leave empty to prevent
     *     any plugins from loading.
     *
     * - 'debug' (bool): Toggles debug mode for API requests and responses. Useful
     *   for troubleshooting.
     *
     * - 'error_handling' (string): Specifies how errors should be handled in the
     *   application. Possible values:
     *   - 'log': Logs errors to a file or system logger.
     *   - 'throw': Throws exceptions for errors.
     *   - 'silent': Suppresses errors without any output or logging.
     *
     * - 'security' (array): Configures security-related settings.
     *   - 'cors' (bool): Enables or disables CORS (Cross-Origin Resource Sharing) headers
     *     for API requests.
     *   - 'allowed_origins' (array): Defines a list of allowed origins for cross-domain
     *     requests. Use `['*']` to allow requests from any origin.
     */
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

    /*
     * Configuration for the `SHORTINIT` mode in WordPress.
     *
     * This array defines the behavior of the `SHORTINIT` constant, which is used to initialize WordPress
     * with minimal functionality. When `SHORTINIT` is set to `true`, WordPress loads only core components,
     * bypassing themes, plugins, and other optional features. This can be useful for performance-critical
     * tasks, such as custom scripts or lightweight integrations.
     *
     * **Retained Features:**
     * - Core settings and configuration
     * - Database operations via the `$wpdb` object
     * - Minimal bootstrap functionality
     *
     * **Skipped Features:**
     * - Themes, plugins, and widgets
     * - REST API (disabled by default unless explicitly enabled)
     * - Localization and translation
     *
     * @see https://github.com/WordPress/wordpress-develop/blob/bcb3299a37712b61eb9b2a92c0b2fcc81e5d3d9d/src/wp-settings.php#L149
     *
     * @var array $shortinit {
     *     Configuration options for the `SHORTINIT` mode.
     *
     *     @type bool   $enabled         Whether the `SHORTINIT` mode is enabled. Default true.
     *     @type bool   $cache           Toggle basic caching for minimal initialization. Default true.
     *     @type bool   $debug           Enable debug mode for additional error reporting. Default false.
     *     @type array  $components      Components to retain or skip during initialization.
     *
     *     @type bool   $components['database']
     *           Whether to retain the `$wpdb` object for database operations. Default true.
     *
     *     @type bool   $components['user']
     *           Whether to include user-related functionality. Default false.
     *
     *     @type string $error_handling  Specifies the error-handling mode. Options include:
     *                                   'log' (default), 'throw', or 'silent'.
     *
     *     @type array  $api             REST API-related configuration.
     *
     *     @type bool   $api['enabled']
     *           Enable or disable limited REST API functionality. Default false.
     *
     *     @type array  $api['routes']
     *           Specify allowed REST API routes. Provide an array of route strings, if any. Default empty array.
     * }
     */
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
