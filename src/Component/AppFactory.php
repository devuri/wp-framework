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

use Pimple\Container as PimpleContainer;
use Psr\Http\Message\RequestInterface;
use Throwable;
use WPframework\Http\HttpFactory;
use WPframework\Http\Request;
use WPframework\Logger\FileLogger;
use WPframework\Logger\Log;

class AppFactory
{
    private static $app;
    private static $request;

    /**
     * Initializes and returns an instance of App.
     *
     * @param string      $appDirPath  The directory path for the application.
     * @param null|string $environment The environment setting, e.g., 'development', 'production'.
     *
     * @return App An initialized App instance.
     */
    public static function create(string $appDirPath, ?string $environment = null): App
    {
        \define('CONFIGS_DIR_PATH', \dirname(__DIR__) . '/inc/configs/');

        // Retrieve the HTTP host using HttpFactory
        $httpHost = HttpFactory::init()->get_http_host();

        // Create the initial request object
        self::$request = Request::create();

        // Mandatory application-wide constants
        self::defineMandatoryConstants($appDirPath, $httpHost);

        // set container bindings.
        $containerBindings = Bindings::init(new PimpleContainer());
        $psrContainer = $containerBindings->getPsrContainer();

        $envType = new EnvType($psrContainer->get('filesystem'));

        $envFiles = $envType->filterFiles(
            EnvType::supportedFiles(),
            APP_DIR_PATH
        );

        self::loadDotEnv($envFiles, $envType);

        self::$request = self::$request->withAttribute('envFiles', $envFiles);

        // Initialize logging with FileLogger
        Log::init($psrContainer->get('logger'));

        // Set the environment configuration
        self::setEnvironment($environment);

        // Instantiate the main application object
        self::$app = new App(self::$request, $containerBindings);

        // Set the error handler for the application
        self::setErrorHandler();

        return self::$app;
    }

    public static function run(): void
    {
        self::$app->run();
    }

    protected static function defineMandatoryConstants(string $appDirPath, string $httpHost): void
    {
        if (\defined('APP_TEST_PATH')) {
            return;
        }

        // Mandatory application-wide constants
        \define('SITE_CONFIGS_DIR', 'configs');
        \define('APP_DIR_PATH', $appDirPath);
        \define('APP_HTTP_HOST', $httpHost);
    }

    protected static function loadDotEnv(array $envFiles, EnvType $envType): void
    {
        if (\defined('APP_TEST_PATH')) {
            return;
        }

        EnvLoader::init(APP_DIR_PATH, APP_HTTP_HOST)->load($envFiles, $envType);
    }

    /**
     * Set the environment type for the application.
     *
     * This function sets the environment type by defining the `RAYDIUM_ENVIRONMENT_TYPE` constant.
     * If the environment type is not provided, it defaults to `null` and can fallback to `.env` file setup.
     *
     * Usage:
     * - If `$environment` is provided, it will define the `RAYDIUM_ENVIRONMENT_TYPE`.
     * - If `$environment` is `null`, the function will allow the `.env` file to define the environment type.
     *
     * @param null|string $environment The environment type, which can be a string (e.g., 'production', 'development') or null to use the .env file setup.
     *
     * @return void
     */
    private static function setEnvironment(?string $environment = null): void
    {
        if ( ! \defined('RAYDIUM_ENVIRONMENT_TYPE')) {
            \define('RAYDIUM_ENVIRONMENT_TYPE', $environment);
        }
    }

    private static function setErrorHandler(): void
    {
        self::$app->setErrorHandler(function (Throwable $e, RequestInterface $request, \Psr\Http\Message\ResponseInterface $response) {
            $response = $response->withStatus(
                $e->getCode(),
                $e->getMessage()
            );

            Log::error($e->getMessage(), [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'headers' => $request->getHeaders(),
            ]);

            Log::info('Request URI: ' . $request->getUri(), [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'headers' => $request->getHeaders(),
            ]);

            // TODO only send this back in dev mode, security issue
            // $response = $response->withHeader('Exception', $e->getMessage());

            return $response;
        });
    }
}
