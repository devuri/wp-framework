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

class KernelConfig
{
    private $appPath;
    private $appHttpHost;
    private $configManager;
    private $configsDir;
    private $configFilename;
    private $tenantId;

    public function __construct(ConstantBuilder $configManager)
    {
        $this->appPath = APP_DIR_PATH;
        $this->appHttpHost = APP_HTTP_HOST;
        $this->configManager = $configManager;
        $this->configsDir = SITE_CONFIGS_DIR;
        $this->tenantId = $this->envTenantId();

        /*
         * Sets the name of the configuration `configs/config.php` file based on arguments.
         *
         * This method defines the configuration file name within the framework. 'config' is the default name, leading to 'config.php'.
         * This name can be customized via the 'config_file' argument, allowing for files like 'constant.php'.
         */
        $this->configFilename = 'config';

        // set config override file.
        $this->configurationOverrides();
    }

    /**
     * @param Configs $configs
     *
     * @return void
     */
    public function setKernelConstants(Configs $configs): void
    {
        $this->configManager->addConstant('RAYDIUM_INSTALL_PROTECTION', true);

        // set app http host.
        $this->configManager->addConstant('APP_HTTP_HOST', $this->appHttpHost);

        // define public web root dir.
        $this->configManager->addConstant('PUBLIC_WEB_DIR', $this->appPath . '/' . $configs->config['app']->get('directory.web_root_dir'));

        // wp dir path
        $this->configManager->addConstant('WP_DIR_PATH', PUBLIC_WEB_DIR . '/' . $configs->config['app']->get('directory.wp_dir_path'));

        // define assets dir.
        $this->configManager->addConstant('APP_ASSETS_DIR', PUBLIC_WEB_DIR . '/' . $configs->config['app']->get('directory.asset_dir'));

        // Directory PATH.
        $this->configManager->addConstant('APP_CONTENT_DIR', $configs->config['app']->get('directory.content_dir'));
        $this->configManager->addConstant('WP_CONTENT_DIR', PUBLIC_WEB_DIR . '/' . APP_CONTENT_DIR);
        $this->configManager->addConstant('WP_CONTENT_URL', env('WP_HOME') . '/' . APP_CONTENT_DIR);

        /*
         * Themes, prefer '/templates'
         *
         * This requires mu-plugin or add `register_theme_directory( APP_THEME_DIR );`
         *
         * path should be a folder within WP_CONTENT_DIR
         *
         * @link https://github.com/devuri/custom-wordpress-theme-dir
         */
        if ($configs->config['app']->get('directory.theme_dir')) {
            $this->configManager->addConstant('APP_THEME_DIR', $configs->config['app']->get('directory.theme_dir'));
        }

        // Plugins.
        $this->configManager->addConstant('WP_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $configs->config['app']->get('directory.plugin_dir'));
        $this->configManager->addConstant('WP_PLUGIN_URL', env('WP_HOME') . '/' . $configs->config['app']->get('directory.plugin_dir'));

        // Must-Use Plugins.
        $this->configManager->addConstant('WPMU_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $configs->config['app']->get('directory.mu_plugin_dir'));
        $this->configManager->addConstant('WPMU_PLUGIN_URL', env('WP_HOME') . '/' . $configs->config['app']->get('directory.mu_plugin_dir'));

        // Disable any kind of automatic upgrade.
        // this will be handled via composer.
        $this->configManager->addConstant('AUTOMATIC_UPDATER_DISABLED', $configs->config['app']->get('disable_updates'));

        // Sudo admin (granted more privilages uses user ID).
        $this->configManager->addConstant('WP_SUDO_ADMIN', $configs->config['app']->get('sudo_admin'));

        // A group of users with higher administrative privileges.
        $this->configManager->addConstant('SUDO_ADMIN_GROUP', $configs->config['app']->get('sudo_admin_group'));

        /*
         * Prevent Admin users from deactivating plugins, true or false.
         *
         * @link https://gist.github.com/devuri/034ccb7c833f970192bb64317814da3b
         */
        $this->configManager->addConstant('CAN_DEACTIVATE_PLUGINS', $configs->config['app']->get('can_deactivate'));

        // SQLite database location and filename.
        $this->configManager->addConstant('DB_DIR', $this->appPath . '/' . $configs->config['app']->get('directory.sqlite_dir'));
        $this->configManager->addConstant('DB_FILE', $configs->config['app']->get('directory.sqlite_file'));

        /*
         * Slug of the default theme for this installation.
         * Used as the default theme when installing new sites.
         * It will be used as the fallback if the active theme doesn't exist.
         *
         * @see WP_Theme::get_core_default_theme()
         */
        $this->configManager->addConstant('WP_DEFAULT_THEME', $configs->config['app']->get('default_theme'));

        // SUCURI
        $this->configManager->addConstant('ENABLE_SUCURI_WAF', $configs->config['app']->get('security.sucuri_waf'));
        // $this->configManager->addConstant( 'SUCURI_DATA_STORAGE', ABSPATH . '../../storage/logs/sucuri' );

        /*
         * Redis cache configuration for the WordPress application.
         *
         * This array contains configuration settings for the Redis cache integration in WordPress.
         * For detailed installation instructions, refer to the documentation at:
         * @link https://github.com/rhubarbgroup/redis-cache/blob/develop/INSTALL.md
         *
         * @return void
         */
        $this->configManager->addConstant('WP_REDIS_DISABLED', $configs->config['app']->get('redis.disabled'));

        $this->configManager->addConstant('WP_REDIS_PREFIX', $configs->config['app']->get('redis.prefix'));
        $this->configManager->addConstant('WP_REDIS_DATABASE', $configs->config['app']->get('redis.database'));
        $this->configManager->addConstant('WP_REDIS_HOST', $configs->config['app']->get('redis.host'));
        $this->configManager->addConstant('WP_REDIS_PORT', $configs->config['app']->get('redis.port'));
        $this->configManager->addConstant('WP_REDIS_PASSWORD', $configs->config['app']->get('redis.password'));

        $this->configManager->addConstant('WP_REDIS_DISABLE_ADMINBAR', $configs->config['app']->get('redis.adminbar'));
        $this->configManager->addConstant('WP_REDIS_DISABLE_METRICS', $configs->config['app']->get('redis.disable-metrics'));
        $this->configManager->addConstant('WP_REDIS_DISABLE_BANNERS', $configs->config['app']->get('redis.disable-banners'));

        $this->configManager->addConstant('WP_REDIS_TIMEOUT', $configs->config['app']->get('redis.timeout'));
        $this->configManager->addConstant('WP_REDIS_READ_TIMEOUT', $configs->config['app']->get('redis.read-timeout'));

        // web app security key
        $this->configManager->addConstant('WEBAPP_ENCRYPTION_KEY', $configs->config['app']->get('security.encryption_key'));
    }

    protected static function envTenantId(): ?string
    {
        if (\defined('APP_TENANT_ID')) {
            return APP_TENANT_ID;
        }
        if (env('APP_TENANT_ID')) {
            return env('APP_TENANT_ID');
        }

        return null;
    }

    /**
     * Determines the configuration file to use based on the application's mode and tenant ID.
     * Falls back to the default configuration if no tenant-specific configuration is found.
     *
     * @return static
     */
    protected function configurationOverrides(): self
    {
        $configOverrideFile = $this->getTenantConfigFile();

        if (empty($configOverrideFile)) {
            $configOverrideFile = $this->getDefaultConfigFile();
        }

        if (! empty($configOverrideFile)) {
            require_once $configOverrideFile;
        }

        return $this;
    }

    /**
     * Attempts to get the tenant-specific configuration file if multi-tenant mode is active.
     *
     * @return null|string Path to the tenant-specific configuration file or null if not found/applicable.
     */
    protected function getTenantConfigFile(): ?string
    {
        if (isMultitenantApp() && ! empty($this->tenantId)) {
            $tenantConfigFile = "{$this->appPath}/{$this->configsDir}/{$this->tenantId}/{$this->configFilename}.php";
            if (file_exists($tenantConfigFile)) {
                return $tenantConfigFile;
            }
        }

        return null;
    }

    /**
     * Gets the default configuration file, preferring the one in the configs directory.
     *
     * @return null|string Path to the default configuration file.
     */
    protected function getDefaultConfigFile(): ?string
    {
        $defaultConfigFile = "{$this->appPath}/{$this->configFilename}.php";
        $userConfigFile = "{$this->appPath}/{$this->configsDir}/{$this->configFilename}.php";

        if (file_exists($userConfigFile)) {
            return $userConfigFile;
        }
        if (file_exists($defaultConfigFile)) {
            return $defaultConfigFile;
        }

        return null;
    }
}
