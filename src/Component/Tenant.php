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

use Exception;
use WPframework\Interfaces\TenantInterface;

class Tenant implements TenantInterface
{
    protected $appPath;

    public function __construct(string $appPath)
    {
        $this->appPath = $this->determineEnvPath($appPath);
    }

    /**
     * Get the current set wp app env.
     *
     * This is used in the compose mu plugin.
     *
     * @return null|string the current app env set, or null if not defined
     */
    public function getHttpEnv(): ?string
    {
        if ( ! \defined('HTTP_ENV_CONFIG')) {
            return null;
        }

        return strtoupper(HTTP_ENV_CONFIG);
    }

    /*
     * The tenant ID for the application.
     *
     * This sets the tenant ID based on the environment configuration. The `APP_TENANT_ID`
     * can be configured in the `.env` file. Setting `APP_TENANT_ID` to false will disable the
     * custom uploads directory behavior that is typically used in a multi-tenant setup. In a
     * multi-tenant environment, `APP_TENANT_ID` is required and must always be set. The method
     * uses `envTenantId()` function to retrieve the tenant ID from the environment settings.
     */
    public static function envTenantId(): ?string
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
     * Retrieves the path for a tenant-specific file, with an option to enforce strict finding.
     *
     * In a multi-tenant application, this function attempts to find a file specific to the current tenant.
     * If the file is not found and 'find_or_fail' is set to true, the function will return null.
     * If the tenant-specific file does not exist (and 'find_or_fail' is false), it falls back to a default file path.
     * If neither file is found, or the application is not in multi-tenant mode, null is returned.
     *
     * @param string $dir          The directory within the app path where the file should be located.
     * @param bool   $find_or_fail Whether to fail if the tenant-specific file is not found.
     *
     * @return null|string The path to the file if found, or null otherwise.
     */
    public function getTenantFilePath(string $dir, bool $find_or_fail = false): ?string
    {
        if ($this->isMultitenantApp() && \defined('APP_TENANT_ID')) {
            $tenant_id = APP_TENANT_ID;
        } else {
            return null;
        }

        // Construct the path for the tenant-specific file
        $tenant_file_path = "{$this->getCurrentPath()}/app.php";

        // Check for the tenant file's existence
        if (file_exists($tenant_file_path)) {
            return $tenant_file_path;
        }
        if ($find_or_fail) {
            throw new Exception('REQUIRE_TENANT_CONFIG requires that each tenant must have their own configuration.', 1);
        }

        // Construct the path for the fallback/default file
        $fallback_file_path = "{$dir}/configs/app.php";

        // Return the fallback file path if it exists
        return file_exists($fallback_file_path) ? $fallback_file_path : null;
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
    public function isMultitenantApp(): bool
    {
        return \defined('ALLOW_MULTITENANT') && true === \constant('ALLOW_MULTITENANT');
    }

    /**
     * Checks if the provided tenant ID matches the landlord's UUID.
     *
     * This function determines if a given tenant ID is equivalent to the predefined
     * LANDLORD_UUID constant, identifying if the tenant is the landlord.
     *
     * @param null|string $tenant_id The tenant ID to check against the landlord's UUID. Default is null.
     *
     * @return bool True if the tenant ID matches the landlord's UUID, false otherwise.
     */
    public function isLandlord(?string $tenant_id = null): bool
    {
        return \defined('LANDLORD_UUID') && LANDLORD_UUID === $tenant_id;
    }

    public function getCurrentPath(): string
    {
        return $this->appPath;
    }

    /**
     * Determines the env file application path, accounting for multi-tenancy.
     *
     * @param string $base_path The base application directory path.
     *
     * @return string The determined application path.
     */
    private function determineEnvPath($base_path): string
    {
        if ($this->isMultitenantApp() && \defined('APP_TENANT_ID')) {
            $configs_dir = SITE_CONFIGS_DIR;

            return "{$base_path}/{$configs_dir}/" . APP_TENANT_ID;
        }

        return $base_path;
    }
}
