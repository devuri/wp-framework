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

final class Config implements ConfigInterface
{
    protected $settings = [];

    public function set(string $key, $value): void
    {
        $this->settings[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    public static function getDefault(): array
    {
        return [
            'error_handler'    => env('ERROR_HANDLER', false),
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
                'prefix'          => env('WP_REDIS_PREFIX', md5(env('WP_HOME')) . 'redis-cache'),
                'database'        => env('WP_REDIS_DATABASE', 0),
                'timeout'         => env('WP_REDIS_TIMEOUT', 1),
                'read-timeout'    => env('WP_REDIS_READ_TIMEOUT', 1),
            ],

            'publickey'        => [
                'app-key' => env('WEB_APP_PUBLIC_KEY', null),
            ],
        ];
    }

    public static function siteConfig(string $appPath): array
    {
        $options_file = $appPath . '/' . siteConfigsDir() . '/app.php';

        if (file_exists($options_file) && \is_array(@require $options_file)) {
            return require $options_file;
        }

        return self::getDefault();
    }
}


// $config = new Config();
//
// $config->set('WP_DEBUG', getenv('WP_DEBUG') ?: false);
// $config->set('DB_NAME', getenv('DB_NAME'));
// $config->set('DB_USER', getenv('DB_USER'));
// $config->set('DB_PASSWORD', getenv('DB_PASSWORD'));
// $config->defineConstants();
