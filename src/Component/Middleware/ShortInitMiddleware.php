<?php

declare(strict_types=1);

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

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->appDirPath = APP_DIR_PATH;
        $this->tinyQuery = $this->services->get('query');
        $cfgs = $this->configs->app();
        $this->initConfig = $cfgs->config['shortinit'];
        $uriSlug = $request->getUri()->getPath();
        $uriPaths = explode('/', $uriSlug);
        $uriHandler = array_filter($uriPaths);
        $defineHandler = end($uriHandler);
        $this->isShortInit = $request->getAttribute('isShortInit', false);

        // admin bypass.
        if (!$this->isShortInit || $this->isAdminRoute($request)) {
            return $handler->handle($request);
        }

        // bootloader and query
        $this->tinyQuery->boot();
        $post = $this->tinyQuery->query(['name' => $defineHandler]);

        $this->twigOptions = array_merge(
            self::defaultTwigOptions(),
            $this->initConfig->get('views.twig', [])
        );

        // If SHORTINIT is disabled.
        if (!$this->isShortInit) {
            return $handler->handle($request);
        }

        $router = new Router($this->twig());

        if ('/' === $uriSlug) {
            $router->get($uriSlug, 'home');
        } else {
            $router->get($uriSlug, $defineHandler);
        }

        $router->setPostItem(($post[0] ?? null));

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
        $this->coreTemplatesDir = SRC_PATH_DIR . DIRECTORY_SEPARATOR . 'inc/templates/views';
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
     * @return (null|false|int|string)[]
     *
     * @see https://twig.symfony.com/doc/3.x/api.html#environment-options
     *
     * @psalm-return array{debug: false, charset: 'utf-8', cache: false, auto_reload: null, strict_variables: false, autoescape: 'html', optimizations: -1}
     */
    protected static function defaultTwigOptions(): array
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
