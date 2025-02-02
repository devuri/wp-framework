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

// use Twigit\Extensions\Filters;
// use Twigit\Extensions\Functions;

class KioskMiddleware extends AbstractMiddleware
{
    private \Twig\Environment $twig;
    private $kioskConfig;
    private $twigOptions;
    private $appDirPath;
    private $coreTemplatesDir;
    private $templatesDir;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->appDirPath = APP_DIR_PATH;
        $cfgs = $this->configs->app();
        $this->kioskConfig = $cfgs->config['kiosk'];
        $this->isAdminKiosk = $request->getAttribute('isAdminKiosk', false);
        $this->twigOptions = array_merge(
            self::defaultTwigOptions(),
            $this->kioskConfig->get('panel.twig', [])
        );

        // If database admin is disabled.
        if (!$this->isAdminKiosk) {
            return $handler->handle($request);
        }

        $isAuthenticated = $request->getAttribute('authCheck', false);

        // Validate authentication
        if (!$isAuthenticated) {
            // TODO implement `TinyAuth` or OAuth
            // throw new Exception("Authentication is required", 401);
        }

        $router = new Router($this->twig());

        $router->get('/', 'home');
        $router->get('/settings', 'settings');
        $router->get('/users', 'users');
        $router->get('/analytics', 'analytics');
        $router->get('/logs', 'logs');
        $router->get('/htmltest', 'htmltest');

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
        $this->coreTemplatesDir = SRC_PATH_DIR . DIRECTORY_SEPARATOR . 'inc/templates/kiosk';
        $cached = $this->debugMode() ? $this->appDirPath . '/templates/cache/kiosk' : false;

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
        $this->kioskConfig->get('panel.twig.debug', false);
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
        $templatesdir = "{$this->appDirPath}/templates/kiosk";

        if (! $this->validateTemplatesDirectory($templatesdir, false)) {
            if (! mkdir($templatesdir, 0777, true)) {
                throw new Exception("Failed to create `templates/kiosk` directory");
            }
        }

        return $templatesdir;
    }
}
