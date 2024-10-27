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
use WPframework\Interfaces\ConfigInterface;

final class Config implements ConfigInterface
{
    /**
     * @return (null|mixed|(null|bool|mixed|(mixed|string)[]|string)[]|string)[]
     *
     * @psalm-return array{error_handler: mixed, config_file: 'config', terminate: array{debugger: false}, directory: array{wp_dir_path: 'wp', web_root_dir: mixed, content_dir: mixed, plugin_dir: mixed, mu_plugin_dir: mixed, sqlite_dir: mixed, sqlite_file: mixed, theme_dir: mixed, asset_dir: mixed, publickey_dir: mixed}, default_theme: mixed, disable_updates: mixed, can_deactivate: mixed, security: array{sucuri_waf: false, encryption_key: null, 'brute-force': true, 'two-factor': true, 'no-pwned-passwords': true, 'admin-ips': array<never, never>}, mailer: array{brevo: array{apikey: mixed}, postmark: array{token: mixed}, sendgrid: array{apikey: mixed}, mailerlite: array{apikey: mixed}, mailgun: array{domain: mixed, secret: mixed, endpoint: mixed, scheme: 'https'}, ses: array{key: mixed, secret: mixed, region: mixed}}, sudo_admin: mixed, sudo_admin_group: null, s3uploads: array{bucket: mixed, key: mixed, secret: mixed, region: mixed, 'bucket-url': mixed, 'object-acl': mixed, expires: mixed, 'http-cache': mixed}, redis: array{disabled: mixed, host: mixed, port: mixed, password: mixed, adminbar: mixed, 'disable-metrics': mixed, 'disable-banners': mixed, prefix: mixed, database: mixed, timeout: mixed, 'read-timeout': mixed}, publickey: array{'app-key': mixed}}
     */
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
            $siteConfigs = require $options_file;
        } else {
            $siteConfigs = [];
        }

        if ( ! \is_array($siteConfigs)) {
            throw new InvalidArgumentException('Error: Config::siteConfig must be of type array', 1);
        }

        return self::multiMerge(self::getDefault(), $siteConfigs);
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
