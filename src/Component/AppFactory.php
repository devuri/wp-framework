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
        $httpHost =  HttpFactory::init()->get_http_host();
        \define('SITE_CONFIGS_DIR', 'configs');
        \define('APP_DIR_PATH', $appDirPath);
        \define('APP_HTTP_HOST', $httpHost);
        Log::init(new FileLogger());
        self::setEnvironment($environment);
        self::$app = new AppInit();
        self::setErrorHandler();

        return self::$app;
    }

    public static function run(): void
    {
        $request = self::createRequest(new RequestFactory());

        self::$app->run($request);
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
            Log::critical($e->getMessage(), [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'headers' => $request->getHeaders(),
            ]);

            Log::info('Request URI: ' . $request->getUri(), [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'headers' => $request->getHeaders(),
            ]);

            $response = $response->withHeader('Raydium-Exception', $e->getMessage());

            return $response->withStatus(503);
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
