<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Loader\FilesystemLoader;
use WPframework\Support\Services\Router;

class ShortInitMiddleware extends AbstractMiddleware
{
    private \Twig\Environment $twig;
    private $initConfig;
    private $twigOptions;
    private $appDirPath;
    private $coreTemplatesDir;
    private $templatesDir;
    private $tinyQuery;
    private $passThroughOn404;


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->appDirPath = APP_DIR_PATH;
        $this->tinyQuery = $this->services->get('tiny');
        $cfgs = $this->configs->app();
        $this->initConfig = $cfgs->config['shortinit'];
        $uriSlug = $request->getUri()->getPath();
        $uriPaths = explode('/', $uriSlug);
        $uriHandler = array_filter($uriPaths);
        $defineHandler = end($uriHandler);

        if ($this->isAdminRoute($request)) {
            return $handler->handle($request);
        }

        $this->tinyQuery->boot();
        $post = $this->tinyQuery->query(['name' => $defineHandler], OBJECT_K);
        $postObject = $post[1];

        // dump($request);
        // dump($postObject->post_type);

        // dump($post);
        // global $wpdb;
        // dump($GLOBALS);
        // dump($wpdb);
        // dd($this->tinyQuery);

        // $this->isShortInit = $request->getAttribute('isShortInit', false);
        $this->twigOptions = array_merge(
            self::defaultTwigOptions(),
            $this->initConfig->get('views.twig', [])
        );

        // If SHORTINIT is disabled.
        // if (!$this->isShortInit) {
        if (!\defined('SHORTINIT') || false === \constant('SHORTINIT')) {
            return $handler->handle($request);
        }

        $router = new Router($this->twig());

        // get static routes first
        // $router->get('/', 'home');
        // $router->get('/sample-page', 'sample-page');
        // $router->get('/demo', 'demo');
        // $router->get('/users', 'users');
        // $router->get('/analytics', 'analytics');
        // $router->get('/logs', 'logs');
        // $router->get('/htmltest', 'htmltest');

        // then try dynamic routes.
        if ('/' === $uriSlug) {
            $router->get($uriSlug, 'home');
        } else {
            $router->get($uriSlug, $defineHandler);
        }

        $router->setPostItem($postObject);

        return $router->process($request, $handler);
    }

    /**
     * Creates and returns a Twig environment instance.
     *
     * @throws Exception If the templates directory does not exist or if there is an error
     *                   initializing the Twig loader.
     *
     * @return \Twig\Environment The initialized Twig environment instance.
     */
    public function twig(): \Twig\Environment
    {
        $this->templatesDir = $this->setTemplatesDir();
        $this->coreTemplatesDir = \dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'inc/templates/views';
        $cached = $this->debugMode() ? $this->appDirPath . '/templates/cache/views' : false;

        $this->validateTemplatesDirectory($this->templatesDir);
        $loader = new FilesystemLoader([$this->templatesDir, $this->coreTemplatesDir]);
        $this->twig = new \Twig\Environment($loader, array_merge(
            $this->twigOptions,
            ['cache' => $cached]
        ));

        // $this->registerExtensions();

        return $this->twig;
    }

    /**
     * Environment Options.
     *
     * @return array
     *
     * @see https://twig.symfony.com/doc/3.x/api.html#environment-options
     */
    protected static function defaultTwigOptions()
    {
        return [
            'debug' => false,
            'charset' => 'utf-8',
            'cache' => false,
            'auto_reload' => null,
            'strict_variables' => false,
            'autoescape' => 'html',
            'optimizations' => -1,
        ];
    }

    private function debugMode(): void
    {
        $this->initConfig->get('views.twig.debug', false);
    }

    /**
     * Validates that the templates directory exists.
     *
     * @throws Exception If the templates directory does not exist.
     */
    private function validateTemplatesDirectory(string $templatesDir, bool $withException = true): bool
    {
        $isValidDirectory = is_dir($templatesDir);

        if (! $isValidDirectory && $withException) {
            throw new Exception("Templates directory does not exist: {$templatesDir}");
        }

        return $isValidDirectory;
    }

    private function setTemplatesDir(): string
    {
        $templatesdir = "{$this->appDirPath}/templates/views";

        if (! $this->validateTemplatesDirectory($templatesdir, false)) {
            if (! mkdir($templatesdir, 0777, true)) {
                throw new Exception("Failed to create `templates/views` directory");
            }
        }

        return $templatesdir;
    }
}
