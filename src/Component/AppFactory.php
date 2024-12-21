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
use Pimple\Container as PimpleContainer;
use Psr\Http\Message\RequestInterface;
use Throwable;
use WPframework\Http\HttpFactory;
use WPframework\Http\Message\Foundation;
use WPframework\Http\Message\RequestFactory;
use WPframework\Logger\FileLogger;
use WPframework\Logger\Log;

class AppFactory
{
    private static $app;
    private static $request;

    /**
     * Initializes and returns an instance of AppInit.
     *
     * @param string      $appDirPath  The directory path for the application.
     * @param null|string $environment The environment setting, e.g., 'development', 'production'.
     *
     * @return AppInit An initialized AppInit instance.
     */
    public static function create(string $appDirPath, ?string $environment = null): AppInit
    {
        // Retrieve the HTTP host using HttpFactory
        $httpHost = HttpFactory::init()->get_http_host();

        // Create the initial request object
        self::$request = self::createRequest(new RequestFactory());

        // Mandatory application-wide constants
        \define('SITE_CONFIGS_DIR', 'configs');
        \define('APP_DIR_PATH', $appDirPath);
        \define('APP_HTTP_HOST', $httpHost);

        // set container bindings.
        $containerBindings = Bindings::init(new PimpleContainer());
        $container = $containerBindings->getPsrContainer();

        $envType = new EnvType($container->get('filesystem'));

        $envFiles = $envType->filterFiles(
            EnvType::supportedFiles(),
            APP_DIR_PATH
        );

        $_dotenv = Dotenv::createImmutable(APP_DIR_PATH, $envFiles);

        try {
            $_dotenv->load();
        } catch (InvalidPathException $e) {
            $envType->tryRegenerateFile(APP_DIR_PATH, APP_HTTP_HOST, $envFiles);

            Terminate::exit(new InvalidPathException($e->getMessage()));
        }

        self::$request = self::$request->withAttribute('envFiles', $envFiles);

        // Initialize logging with FileLogger
        Log::init($container->get('logger'));

        // Set the environment configuration
        self::setEnvironment($environment);

        // Instantiate the main application object
        self::$app = new AppInit(self::$request, $container);

        // Set the error handler for the application
        self::setErrorHandler();

        return self::$app;
    }

    public static function run(): void
    {
        self::$app->run();
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

    private static function createRequest(RequestFactory $psr17Factory): \Psr\Http\Message\ServerRequestInterface
    {
        $requestCreator = Foundation::create(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        return $requestCreator->fromGlobals();
    }
}
