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

use Psr\Http\Message\RequestInterface;
use Throwable;
use WPframework\Http\HttpFactory;
use WPframework\Http\Message\Foundation;
use WPframework\Http\Message\RequestFactory;
use WPframework\Http\Message\Response;
use WPframework\Logger\FileLogger;
use WPframework\Logger\Log;

class AppFactory
{
    /**
     * @var AppInit
     */
    private static $app;

    /**
     * @return AppInit
     */
    public static function create(string $appDirPath, ?string $environment = null): AppInit
    {
        Log::init(new FileLogger());
        self::setEnvironment($environment);
        self::defineConstant($appDirPath);
        self::$app = new AppInit();
        self::registerDefaultMiddlewares();
        self::setErrorHandler();

        return self::$app;
    }

    public static function run(): void
    {
        $request = self::createRequest(new RequestFactory());

        self::$app->run($request);
    }

    public static function defineConstant(string $appDirPath): void
    {
        if ( ! \defined('SITE_CONFIGS_DIR')) {
            \define('SITE_CONFIGS_DIR', 'configs');
        }

        if ( ! \defined('APP_DIR_PATH')) {
            \define('APP_DIR_PATH', $appDirPath);
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
    private static function setEnvironment(?string $environment): void
    {
        if ( ! \defined('RAYDIUM_ENVIRONMENT_TYPE')) {
            \define('RAYDIUM_ENVIRONMENT_TYPE', $environment ?? null);
        }
    }

    private static function registerDefaultMiddlewares(): void
    {
        self::$app->addMiddleware(function (RequestInterface $request, $handler) {
            error_log('Request URI: ' . $request->getUri());

            return $handler->handle($request);
        }, 'req-uri');
    }

    private static function setDefaultHandler(): void
    {
        self::$app->setDefaultHandler(function (RequestInterface $request) {
            $response = new Response();
            $response->getBody()->write("Welcome to MyApp!");

            return $response;
        });
    }

    private static function setErrorHandler(): void
    {
        self::$app->setErrorHandler(function (Throwable $e, RequestInterface $request, \Psr\Http\Message\ResponseInterface $response) {
            Log::critical($e->getMessage(), [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'headers' => $request->getHeaders(),
            ]);

            return $response->withStatus(500);
        });
    }

    /**
     * @return RequestInterface
     */
    private static function createRequest(RequestFactory $psr17Factory): RequestInterface
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
