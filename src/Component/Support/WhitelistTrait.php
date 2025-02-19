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

use InvalidArgumentException;

trait WhitelistTrait
{
    protected function setEnvWhitelist(array $defaultWhitelist): array
    {
        $whitelistFile = $this->configsPath . '/whitelist.php';

        if (file_exists($whitelistFile) && \is_array(@require $whitelistFile)) {
            $appWhitelist = require $whitelistFile;
        } else {
            $appWhitelist = [];
        }

        if (! \is_array($appWhitelist)) {
            throw new InvalidArgumentException('Error: Config::$appWhitelist must be of type array', 1);
        }

        $whitelisted = array_merge($defaultWhitelist['framework'], $defaultWhitelist['wp']);

        return array_merge($whitelisted, $appWhitelist);
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{framework: list{'HOME_URL', 'ADMIN_LOGIN_URL', 'KIOSK_DOMAIN_ID', 'ERROR_HANDLER', 'ADMINER_URI', 'ADMINER_SECRET', 'HEALTH_STATUS_SECRET', 'ENVIRONMENT_TYPE', 'WEB_ROOT_DIR', 'CONTENT_DIR', 'PLUGIN_DIR', 'MU_PLUGIN_DIR', 'SQLITE_DIR', 'SQLITE_FILE', 'THEME_DIR', 'ASSET_DIR', 'PUBLICKEY_DIR', 'DEFAULT_THEME', 'DISABLE_UPDATES', 'CAN_DEACTIVATE', 'APP_ASSETS_DIR', 'APP_CONTENT_DIR', 'APP_HTTP_HOST', 'APP_PATH', 'APP_TENANT_ID', 'APP_TENANT_SECRET', 'ASSET_URL', 'DEVELOPER_ADMIN', 'AVADAKEY', 'AWS_ACCESS_KEY_ID', 'AWS_DEFAULT_REGION', 'AWS_SECRET_ACCESS_KEY', 'BACKUP_PLUGINS', 'BASIC_AUTH_PASSWORD', 'BASIC_AUTH_USER', 'BREVO_API_KEY', 'CAN_DEACTIVATE_PLUGINS', 'DB_DIR', 'DB_FILE', 'DELETE_LOCAL_S3BACKUP', 'DISABLE_WP_APPLICATION_PASSWORDS', 'ELEMENTOR_AUTO_ACTIVATION', 'ELEMENTOR_PRO_LICENSE', 'ENABLE_S3_BACKUP', 'ENABLE_SUCURI_WAF', 'IS_MULTI_TENANT_APP', 'IS_MULTITENANT', 'LANDLORD_DB_HOST', 'LANDLORD_DB_NAME', 'LANDLORD_DB_USER', 'LANDLORD_DB_PASSWORD', 'LANDLORD_DB_PREFIX', 'MAILERLITE_API_KEY', 'MAILGUN_DOMAIN', 'MAILGUN_ENDPOINT', 'MAILGUN_SECRET', 'MAX_MEMORY_LIMIT', 'MEMORY_LIMIT', 'POSTMARK_TOKEN', 'PUBLIC_WEB_DIR', 'S3_BACKUP_BUCKET', 'S3_BACKUP_DIR', 'S3_BACKUP_KEY', 'S3_BACKUP_REGION', 'S3_BACKUP_SECRET', 'S3ENCRYPTED_BACKUP', 'S3_UPLOADS_BUCKET', 'S3_UPLOADS_BUCKET_URL', 'S3_UPLOADS_HTTP_CACHE_CONTROL', 'S3_UPLOADS_HTTP_EXPIRES', 'S3_UPLOADS_KEY', 'S3_UPLOADS_OBJECT_ACL', 'S3_UPLOADS_REGION', 'S3_UPLOADS_SECRET', 'SENDGRID_API_KEY', 'SEND_EMAIL_CHANGE_EMAIL', 'SUDO_ADMIN', 'SUDO_ADMIN_GROUP', 'TEST_COOKIE', 'USE_APP_THEME', 'WEB_APP_PUBLIC_KEY', 'WP_DIR_PATH', 'WPENV_AUTO_LOGIN_SECRET_KEY', 'WP_REDIS_DATABASE', 'WP_REDIS_DISABLE_ADMINBAR', 'WP_REDIS_DISABLE_BANNERS', 'WP_REDIS_DISABLE_METRICS', 'WP_REDIS_HOST', 'WP_REDIS_PASSWORD', 'WP_REDIS_PORT', 'WP_REDIS_PREFIX', 'WP_REDIS_TIMEOUT', 'WP_REDIS_READ_TIMEOUT', 'WP_REDIS_DISABLED', 'WP_SUDO_ADMIN', 'USE_STRICT_ENV_VARS'}, wp: list{'WP_DEVELOPMENT_MODE', 'WP_MEMORY_LIMIT', 'WP_MAX_MEMORY_LIMIT', 'WP_ALLOW_REPAIR', 'DO_NOT_UPGRADE_GLOBAL_TABLES', 'DB_HOST', 'DB_NAME', 'DB_PASSWORD', 'DB_PREFIX', 'DB_ROOT_PASS', 'DB_USER', 'DB_CHARSET', 'DB_COLLATE', 'WP_SITEURL', 'WP_DEFAULT_THEME', 'RELOCATE', 'FS_METHOD', 'FS_CHMOD_DIR', 'FS_CHMOD_FILE', 'WP_TEMP_DIR', 'WP_CONTENT_URL', 'WP_CONTENT_DIR', 'WP_PLUGIN_DIR', 'WP_PLUGIN_URL', 'PLUGINDIR', 'WPMU_PLUGIN_DIR', 'WPMU_PLUGIN_URL', 'MUPLUGINDIR', 'TEMPLATEPATH', 'STYLESHEETPATH', 'WP_POST_REVISIONS', 'AUTOSAVE_INTERVAL', 'COOKIE_DOMAIN', 'COOKIEHASH', 'COOKIEPATH', 'SITECOOKIEPATH', 'ADMIN_COOKIE_PATH', 'PLUGINS_COOKIE_PATH', 'USER_COOKIE', 'PASS_COOKIE', 'AUTH_COOKIE', 'SECURE_AUTH_COOKIE', 'LOGGED_IN_COOKIE', 'RECOVERY_MODE_COOKIE', 'WP_DEBUG', 'WP_DEBUG_LOG', 'WP_DEBUG_DISPLAY', 'WP_LOCAL_DEV', 'CONCATENATE_SCRIPTS', 'SCRIPT_DEBUG', 'SAVEQUERIES', 'COMPRESS_SCRIPTS', 'COMPRESS_CSS', 'ENFORCE_GZIP', 'DISABLE_WP_CRON', 'ALTERNATE_WP_CRON', 'WP_CRON_LOCK_TIMEOUT', 'FORCE_SSL_LOGIN', 'FORCE_SSL_ADMIN', 'WP_HTTP_BLOCK_EXTERNAL', 'WP_ACCESSIBLE_HOSTS', 'DISALLOW_FILE_EDIT', 'DISALLOW_FILE_MODS', 'IMAGE_EDIT_OVERWRITE', 'AUTOMATIC_UPDATER_DISABLED', 'WP_AUTO_UPDATE_CORE', 'WPLANG', 'WP_LANG_DIR', 'EMPTY_TRASH_DAYS', 'MEDIA_TRASH', 'SHORTINIT', 'WP_USE_THEMES', 'WP_SANDBOX_SCRAPING', 'WP_START_TIMESTAMP', 'RECOVERY_MODE_EMAIL', 'AUTH_KEY', 'AUTH_SALT', 'LOGGED_IN_KEY', 'LOGGED_IN_SALT', 'NONCE_KEY', 'NONCE_SALT', 'SECURE_AUTH_KEY', 'SECURE_AUTH_SALT'}}
     */
    protected static function getDefaultWhitelist(): array
    {
        return [
            'framework' => [
                'HOME_URL',
                'ADMIN_LOGIN_URL',
                'KIOSK_DOMAIN_ID',
                'ERROR_HANDLER',
                'ADMINER_URI',
                'ADMINER_SECRET',
                'HEALTH_STATUS_SECRET',
                'ENVIRONMENT_TYPE',
                'WEB_ROOT_DIR',
                'CONTENT_DIR',
                'PLUGIN_DIR',
                'MU_PLUGIN_DIR',
                'SQLITE_DIR',
                'SQLITE_FILE',
                'THEME_DIR',
                'ASSET_DIR',
                'PUBLICKEY_DIR',
                'DEFAULT_THEME',
                'DISABLE_UPDATES',
                'CAN_DEACTIVATE',
                'APP_ASSETS_DIR',
                'APP_CONTENT_DIR',
                'APP_HTTP_HOST',
                'APP_PATH',
                'APP_TENANT_ID',
                'APP_TENANT_SECRET',
                'ASSET_URL',
                'DEVELOPER_ADMIN',
                'AVADAKEY',
                'AWS_ACCESS_KEY_ID',
                'AWS_DEFAULT_REGION',
                'AWS_SECRET_ACCESS_KEY',
                'BACKUP_PLUGINS',
                'BASIC_AUTH_PASSWORD',
                'BASIC_AUTH_USER',
                'BREVO_API_KEY',
                'CAN_DEACTIVATE_PLUGINS',
                'DB_DIR',
                'DB_FILE',
                'DELETE_LOCAL_S3BACKUP',
                'DISABLE_WP_APPLICATION_PASSWORDS',
                'ELEMENTOR_AUTO_ACTIVATION',
                'ELEMENTOR_PRO_LICENSE',
                'ENABLE_S3_BACKUP',
                'ENABLE_SUCURI_WAF',
                'IS_MULTI_TENANT_APP',
                'IS_MULTITENANT',
                'LANDLORD_DB_HOST',
                'LANDLORD_DB_NAME',
                'LANDLORD_DB_USER',
                'LANDLORD_DB_PASSWORD',
                'LANDLORD_DB_PREFIX',
                'MAILERLITE_API_KEY',
                'MAILGUN_DOMAIN',
                'MAILGUN_ENDPOINT',
                'MAILGUN_SECRET',
                'MAX_MEMORY_LIMIT',
                'MEMORY_LIMIT',
                'POSTMARK_TOKEN',
                'PUBLIC_WEB_DIR',
                'S3_BACKUP_BUCKET',
                'S3_BACKUP_DIR',
                'S3_BACKUP_KEY',
                'S3_BACKUP_REGION',
                'S3_BACKUP_SECRET',
                'S3ENCRYPTED_BACKUP',
                'S3_UPLOADS_BUCKET',
                'S3_UPLOADS_BUCKET_URL',
                'S3_UPLOADS_HTTP_CACHE_CONTROL',
                'S3_UPLOADS_HTTP_EXPIRES',
                'S3_UPLOADS_KEY',
                'S3_UPLOADS_OBJECT_ACL',
                'S3_UPLOADS_REGION',
                'S3_UPLOADS_SECRET',
                'SENDGRID_API_KEY',
                'SEND_EMAIL_CHANGE_EMAIL',
                'SUDO_ADMIN',
                'SUDO_ADMIN_GROUP',
                'TEST_COOKIE',
                'USE_APP_THEME',
                'WEB_APP_PUBLIC_KEY',
                'WP_DIR_PATH',
                'WPENV_AUTO_LOGIN_SECRET_KEY',
                'WP_REDIS_DATABASE',
                'WP_REDIS_DISABLE_ADMINBAR',
                'WP_REDIS_DISABLE_BANNERS',
                'WP_REDIS_DISABLE_METRICS',
                'WP_REDIS_HOST',
                'WP_REDIS_PASSWORD',
                'WP_REDIS_PORT',
                'WP_REDIS_PREFIX',
                'WP_REDIS_TIMEOUT',
                'WP_REDIS_READ_TIMEOUT',
                'WP_REDIS_DISABLED',
                'WP_SUDO_ADMIN',
                'USE_STRICT_ENV_VARS',
                'GITHUB_SECRET',
            ],
            'wp'        => [
                'WP_DEVELOPMENT_MODE',
                'WP_MEMORY_LIMIT',
                'WP_MAX_MEMORY_LIMIT',
                'WP_ALLOW_REPAIR',
                'DO_NOT_UPGRADE_GLOBAL_TABLES',
                'DB_HOST',
                'DB_NAME',
                'DB_PASSWORD',
                'DB_PREFIX',
                'DB_ROOT_PASS',
                'DB_USER',
                'DB_CHARSET',
                'DB_COLLATE',
                'WP_SITEURL',
                'WP_DEFAULT_THEME',
                'RELOCATE',
                'FS_METHOD',
                'FS_CHMOD_DIR',
                'FS_CHMOD_FILE',
                'WP_TEMP_DIR',
                'WP_CONTENT_URL',
                'WP_CONTENT_DIR',
                'WP_PLUGIN_DIR',
                'WP_PLUGIN_URL',
                'PLUGINDIR',
                'WPMU_PLUGIN_DIR',
                'WPMU_PLUGIN_URL',
                'MUPLUGINDIR',
                'TEMPLATEPATH',
                'STYLESHEETPATH',
                'WP_POST_REVISIONS',
                'AUTOSAVE_INTERVAL',
                'COOKIE_DOMAIN',
                'COOKIEHASH',
                'COOKIEPATH',
                'SITECOOKIEPATH',
                'ADMIN_COOKIE_PATH',
                'PLUGINS_COOKIE_PATH',
                'USER_COOKIE',
                'PASS_COOKIE',
                'AUTH_COOKIE',
                'SECURE_AUTH_COOKIE',
                'LOGGED_IN_COOKIE',
                'RECOVERY_MODE_COOKIE',
                'WP_DEBUG',
                'WP_DEBUG_LOG',
                'WP_DEBUG_DISPLAY',
                'WP_LOCAL_DEV',
                'CONCATENATE_SCRIPTS',
                'SCRIPT_DEBUG',
                'SAVEQUERIES',
                'COMPRESS_SCRIPTS',
                'COMPRESS_CSS',
                'ENFORCE_GZIP',
                'DISABLE_WP_CRON',
                'ALTERNATE_WP_CRON',
                'WP_CRON_LOCK_TIMEOUT',
                'FORCE_SSL_LOGIN',
                'FORCE_SSL_ADMIN',
                'WP_HTTP_BLOCK_EXTERNAL',
                'WP_ACCESSIBLE_HOSTS',
                'DISALLOW_FILE_EDIT',
                'DISALLOW_FILE_MODS',
                'IMAGE_EDIT_OVERWRITE',
                'AUTOMATIC_UPDATER_DISABLED',
                'WP_AUTO_UPDATE_CORE',
                'WPLANG',
                'WP_LANG_DIR',
                'EMPTY_TRASH_DAYS',
                'MEDIA_TRASH',
                'SHORTINIT',
                'WP_USE_THEMES',
                'WP_SANDBOX_SCRAPING',
                'WP_START_TIMESTAMP',
                'RECOVERY_MODE_EMAIL',
                'AUTH_KEY',
                'AUTH_SALT',
                'LOGGED_IN_KEY',
                'LOGGED_IN_SALT',
                'NONCE_KEY',
                'NONCE_SALT',
                'SECURE_AUTH_KEY',
                'SECURE_AUTH_SALT',
            ],
        ];
    }
}
