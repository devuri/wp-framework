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

use Dotenv\Dotenv;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use WPframework\ConstantBuilder;
use WPframework\EnvType;
use WPframework\Http\HttpFactory;
use WPframework\Tenant;
use WPframework\Terminate;

class Tenancy
{
    private $appPath;
    private $configsDir;
    private $tenant;
    private $envType;
    private $constant;

    /**
     * Tenancy constructor.
     *
     * @param string $appPath       The base directory path of the application (e.g., __DIR__).
     * @param string $siteConfigDir The site config directory name
     */
    public function __construct(string $appPath, string $siteConfigDir)
    {
        $this->tenant     = new Tenant($appPath);
        $this->appPath    = $this->tenant->getCurrentPath();
        $this->configsDir = $siteConfigDir;
        $this->envType    = new EnvType(new Filesystem());
        $this->constant   = new ConstantBuilder();
    }

    /**
     * Initializes the App Kernel with optional multi-tenant support.
     *
     * @throws Exception If there are issues loading environment variables or initializing the App.
     *
     * @return Dotenv
     */
    public function init(Dotenv $_dotenv): Dotenv
    {
        if (file_exists("{$this->appPath}/{$this->configsDir}/tenancy.php")) {
            require_once "{$this->appPath}/{$this->configsDir}/tenancy.php";
        }

        if (\defined('ALLOW_MULTITENANT') && true === ALLOW_MULTITENANT) {
            $this->setupMultiTenant($_dotenv);
        }

        return $_dotenv;
    }

    /**
     * Sets up the environment for a multi-tenant configuration.
     */
    protected function setupMultiTenant(Dotenv $_dotenv): void
    {
        $appHttpHost = HttpFactory::init()->get_http_host();

        try {
            $_dotenv->required('LANDLORD_DB_HOST')->notEmpty();
            $_dotenv->required('LANDLORD_DB_NAME')->notEmpty();
            $_dotenv->required('LANDLORD_DB_USER')->notEmpty();
            $_dotenv->required('LANDLORD_DB_PASSWORD')->notEmpty();
            $_dotenv->required('LANDLORD_DB_PREFIX')->notEmpty();
        } catch (Exception $e) {
            Terminate::exit([ 'Landlord info is required for multi-tenant', 403 ]);
        }

        $landlord = new DB('tenant', env('LANDLORD_DB_HOST'), env('LANDLORD_DB_NAME'), env('LANDLORD_DB_USER'), env('LANDLORD_DB_PASSWORD'), env('LANDLORD_DB_PREFIX'));
        $hostd    = $landlord->where('domain', $appHttpHost);

        if ( ! $hostd) {
            Terminate::exit([ 'The website is not defined. Please review the URL and try again.', 403 ]);
        } else {
            $this->tenant = $hostd[0];
            $this->defineTenantConstants();
            $this->maybeRegenerateEnvFile(APP_TENANT_ID);
        }

        // Clean up sensitive environment variables
        cleanSensitiveEnv([ 'LANDLORD_DB_HOST', 'LANDLORD_DB_NAME', 'LANDLORD_DB_USER', 'LANDLORD_DB_PASSWORD', 'LANDLORD_DB_PREFIX' ]);
    }

    /**
     * Defines constants based on the tenant's information.
     */
    private function defineTenantConstants(): void
    {
        \define('APP_HTTP_HOST', $this->tenant->domain);
        \define('APP_TENANT_ID', md5($this->tenant->uuid));
        \define('IS_MULTITENANT', true);

        // allow overrides.
        $this->constant->define('REQUIRE_TENANT_CONFIG', false);
        $this->constant->define('TENANCY_WEB_ROOT', 'public');
        $this->constant->define('PUBLIC_WEB_DIR', $this->appPath . '/' . TENANCY_WEB_ROOT);
        $this->constant->define('APP_CONTENT_DIR', 'app');
    }

    /**
     * Regenerates the tenant-specific .env file if it doesn't exist.
     *
     * @param string $tenantId Tenant's UUID.
     */
    private function maybeRegenerateEnvFile(string $tenantId): void
    {
        $tenantEnvPath = "{$this->appPath}/{$this->configsDir}/{$tenantId}/.env";
        if ( ! file_exists($tenantEnvPath)) {
            $dbPrefix = $this->getDBPrefix($tenantId);
            $this->envType->tryRegenerateFile($tenantEnvPath, APP_HTTP_HOST, $dbPrefix);
        }
    }

    /**
     * Determines the database prefix for the tenant.
     *
     * @param string $tenantId Tenant's UUID.
     *
     * @return null|string Database prefix or null if not the main site.
     */
    private function getDBPrefix(string $tenantId): ?string
    {
        if (\defined('LANDLORD_UUID') && LANDLORD_UUID === $tenantId) {
            return env('LANDLORD_DB_PREFIX');
        }

        return null;
    }
}
