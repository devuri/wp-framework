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

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Exception;
use Terminate;
use WPframework\Http\HttpFactory;
use WPframework\Http\Tenancy;

trait AppMakerTrait
{
    /**
     * Initializes the App Kernel with optional multi-tenant support.
     *
     * Sets up the application kernel based on the provided application directory path.
     * In multi-tenant configurations, it dynamically adjusts the environment based on
     * the current HTTP host and tenant-specific settings. It ensures all required
     * environment variables for the landlord (main tenant) are set and terminates
     * execution with an error message if critical configurations are missing or if
     * the tenant's domain is not recognized.
     *
     * @param string $app_path The base directory path of the application (e.g., __DIR__).
     *
     * @throws Exception If there are issues loading environment variables or initializing the App.
     * @throws Exception If required multi-tenant environment variables are missing or if the tenant's domain is not recognized.
     *
     * @return Kernel The initialized application kernel.
     */
    public static function init(string $app_path): Kernel
    {
        if ( ! \defined('SITE_CONFIGS_DIR')) {
            \define('SITE_CONFIGS_DIR', 'configs');
        }

        if ( ! \defined('APP_DIR_PATH')) {
            \define('APP_DIR_PATH', $app_path);
        }

        if ( ! \defined('APP_HTTP_HOST')) {
            \define('APP_HTTP_HOST', HttpFactory::init()->get_http_host());
        }

        if ( ! \defined('RAYDIUM_ENVIRONMENT_TYPE')) {
            \define('RAYDIUM_ENVIRONMENT_TYPE', null);
        }

        // Use 204 for No Content, or 404 for Not Found
        \define('FAVICON_RESPONSE_TYPE', 404);

        // Enable cache
        \define('FAVICON_ENABLE_CACHE', true);

        // Cache time in seconds (e.g., 2 hours = 7200 seconds)
        \define('FAVICON_CACHE_TIME', 7200);

        $app_options         = [];
        $supported_env_files = _supportedEnvFiles();

        // Filters out environment files that do not exist.
        $_env_files = _envFilesFilter($supported_env_files, APP_DIR_PATH);

        // load env from dotenv early.
        $_dotenv = Dotenv::createImmutable(APP_DIR_PATH, $_env_files, true);

        try {
            $_dotenv->load();
        } catch (InvalidPathException $e) {
            tryRegenerateEnvFile(APP_DIR_PATH, APP_HTTP_HOST, $_env_files);

            $debug = [
                'path'        => APP_DIR_PATH,
                'line'        => __LINE__,
                'exception'   => $e,
                'invalidfile' => "Missing env file: {$e->getMessage()}",
            ];

            Terminate::exit([ "Missing env file: {$e->getMessage()}", 500, $debug ]);
        } catch (Exception $e) {
            $debug = [
                'path'      => APP_DIR_PATH,
                'line'      => __LINE__,
                'exception' => $e,
            ];
            Terminate::exit([ $e->getMessage(), 500, $debug ]);
        }// end try

        /**
         * @var Tenancy
         */
        $tenancy = new Tenancy(APP_DIR_PATH, SITE_CONFIGS_DIR);
        $tenancy->initialize($_dotenv);

        try {
            $app = new self(APP_DIR_PATH, SITE_CONFIGS_DIR);
        } catch (Exception $e) {
            $debug = [
                'path'      => APP_DIR_PATH,
                'line'      => __LINE__,
                'exception' => $e,
            ];
            Terminate::exit([ 'Framework Initialization Error:', 500, $debug ]);
        }

        // @phpstan-ignore-next-line
        return $app->kernel();
    }
}
