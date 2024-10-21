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
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\ErrorHandler\Debug;
use Urisoft\Env;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use WPframework\Http\Message\Foundation;
use WPframework\Http\Message\RequestFactory;
use WPframework\Logger\FileLogger;
use WPframework\Middleware\FaviconCache;
use WPframework\Middleware\FinalHandler;
use WPframework\Middleware\LoggingMiddleware;
use WPframework\Middleware\MiddlewareHandler;

/**
 * The App class serves as the main entry point for initializing the WordPress application.
 * It sets up the application environment, loads configuration settings, and establishes
 * error handling mechanisms based on the environment. This class ensures that the application
 * is correctly configured before it starts running.
 */
class App
{
    use AppMakerTrait;

    /**
     * The base path of the application.
     * This path is used to locate all necessary files and directories within the application.
     *
     * @var string
     */
    protected $app_path;

    /**
     * The Setup object responsible for initializing and managing the application's environment and configuration.
     * This object provides methods to access environment variables, configuration files, and other setup-related functionalities.
     *
     * @var Setup
     */
    protected $setup;

    /**
     * The application's configuration array, loaded from the specified configuration file.
     * This array contains settings and parameters required for the application's operation.
     *
     * @var array
     */
    protected $config;

    /**
     * The directory path where configuration files are stored.
     * This path is used in conjunction with `$app_path` to locate configuration files.
     *
     * @var string
     */
    protected $configs_dir;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Initializes the App class with essential configuration and setup.
     *
     * It loads the specified configuration options from a file and initializes error handling.
     * The constructor requires the base application path, the configuration directory location,
     * and an optional filename for configuration options.
     *
     * @param string $app_path    The base path of the application (e.g., __DIR__).
     * @param string $site_config The directory where configuration files are stored.
     *
     * @throws Exception If the configuration file is missing or the loaded configuration is not an array.
     */
    public function __construct(string $app_path, string $site_config)
    {
        $this->app_path    = $app_path;
        $this->configs_dir = $site_config;

        // Setup here gives us access to our env values.
        $this->setup = self::defineSetup($this->app_path);

        // Middleware.
        $this->response = self::responseGate();

        /**
         * setup params.
         *
         * @var string
         */
        $tenant_options = $this->setup->tenant()->getTenantFilePath($this->app_path, self::is_required_tenant_config());

        if ( ! empty($tenant_options)) {
            $this->config = require $tenant_options;
        } else {
            $this->config = Config::siteConfig($this->app_path);
        }

        if ( ! \is_array($this->config)) {
            throw new Exception('Options array is undefined, not array.', 1);
        }

        // handle errors early.
        $this->setAppErrors();
    }

    /**
     * Initializes and returns a Kernel object with the application's configuration.
     *
     * This method ensures the configuration (`$this->config`) is an array before
     * passing it to the Kernel constructor. If `$this->config` is not an array,
     * the application is terminated with an error message to prevent type errors.
     *
     * @return Kernel Returns an instance of Kernel initialized with the application path, configuration array, and setup object.
     */
    public function kernel(): Kernel
    {
        if ( ! \is_array($this->config)) {
            $debug = [
                'class'  => static::class,
                'object' => $this,
                'path'   => $this->app_path,
                'line'   => __LINE__,
            ];
            Terminate::exit([ 'Uncaught TypeError: Kernel($args) must be of type array', 500, $debug ]);
        }

        return new Kernel($this->app_path, $this->config, $this->setup);
    }

    public static function responseGate(): ResponseInterface
    {
        $psr17Factory      = new RequestFactory();
        $requestCreator    = Foundation::create(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        $middlewareHandler = new MiddlewareHandler(new FinalHandler());

        // add middleware.
        // $middlewareHandler->addMiddleware(new FaviconCache($psr17Factory));
        // $middlewareHandler->addMiddleware(new LoggingMiddleware(new FileLogger()));


        $request = $requestCreator->fromGlobals();

        return $middlewareHandler->handle($request);
    }

    /**
     * Factory method for creating a Setup object with the specified application path.
     *
     * This method abstracts the instantiation of a Setup object, allowing for
     * centralized management of Setup object creation. It's particularly useful
     * for encapsulating any future changes in the Setup class instantiation process.
     *
     * @param string $app_path The application path to be used in the Setup object.
     *
     * @return Setup Returns a new Setup object configured with the provided application path.
     */
    protected static function defineSetup(string $app_path): Setup
    {
        return new Setup($app_path);
    }

    /**
     * Set up the application error handling based on environment settings.
     */
    protected function setAppErrors(): void
    {
        if ( ! \in_array(env('WP_ENVIRONMENT_TYPE'), [ 'debug', 'development', 'dev', 'local' ], true)) {
            return;
        }

        if (\defined('WP_INSTALLING')) {
            return;
        }

        if (false === $this->config['error_handler']) {
            return;
        }

        if (true === $this->config['error_handler']) {
            Debug::enable();

            return;
        }

        if (\is_null($this->config['error_handler']) || 'symfony' === $this->config['error_handler']) {
            Debug::enable();
        } elseif ('oops' === $this->config['error_handler']) {
            $whoops = new Run();
            $whoops->pushHandler(new PrettyPageHandler());
            $whoops->register();
        }
    }

    private static function is_required_tenant_config(): bool
    {
        return \defined('REQUIRE_TENANT_CONFIG') && true === \constant('REQUIRE_TENANT_CONFIG');
    }
}
