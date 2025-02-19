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
use WPframework\Support\Configs;

class KernelMiddleware extends AbstractMiddleware
{
    private $kernel;
    private $pathError;

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->kernel = $this->services->get('kernel');
        $isProd = $request->getAttribute('isProd', false);
        $this->kernel->setKernelConstants($this->configs->app());
        $this->kernel->setupCorePlugins($this->services->get('filesystem'));

        if (! $this->isValidInstallerPath($isProd)) {
            throw new Exception(
                'app configurations file and composer `installer-paths` did not match ' . $this->pathError
            );
        }

        if ($this->inMaintenanceMode()) {
            throw new Exception(self::getMaintenanceMessage());
        }

        return $handler->handle($request);
    }

    protected function isValidInstallerPath(bool $isProd): bool
    {
        $publicWebRoot = $this->configs->app()->config['app']->get('directory.web_root_dir');
        $contentDir = $this->configs->app()->config['app']->get('directory.content_dir');
        $plugin = $this->configs->app()->config['app']->get('directory.plugin_dir');
        $muPlugins = $this->configs->app()->config['app']->get('directory.mu_plugin_dir');

        // set by the framework.
        $installerPaths = [
            "$publicWebRoot/$muPlugins/{\$name}/",
            "$publicWebRoot/$plugin/{\$name}/",
            "public/$contentDir/themes/{\$name}/",
        ];

        if (! $isProd) {
            $this->pathError = 'config: ' . implode(' ', $installerPaths);
        }

        // from site composer.json
        $extrasPath = array_keys(configs()->config['composer']->get('extra.installer-paths'));

        return $installerPaths === $extrasPath;
    }

    /**
     * Checks for maintenance mode across different scopes and terminates execution if enabled.
     *
     * This function checks for a .maintenance file in various locations, affecting different
     * scopes of the application:
     * - The entire tenant network (when located in PUBLIC_WEB_DIR or APP_PATH/configs_dir).
     * - A single tenant (when located in the current application path).
     * If a .maintenance file is found, it terminates the execution with a maintenance message
     * and sends a 503 Service Unavailable status code.
     */
    protected function inMaintenanceMode(): bool
    {
        $configsDir = SITE_CONFIGS_DIR;
        $tenantId = \defined('APP_TENANT_ID') ? APP_TENANT_ID : null;
        $maintenanceChecks = [
            // Affects the entire tenant network.
            PUBLIC_WEB_DIR . '/.maintenance' => 'Will affect the entire tenant network.',
            APP_DIR_PATH . "/{$configsDir}/.maintenance" => 'Will affect the entire tenant network.',

            // Affects a single tenant.
            APP_DIR_PATH . "/{$configsDir}/{$tenantId}/.maintenance" => 'Will affect the a single tenant.',
        ];

        foreach ($maintenanceChecks as $path => $scope) {
            if (file_exists($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the maintenance message.
     *
     * This method returns a predefined maintenance message indicating that
     * the server is temporarily unavailable due to maintenance. It's used to
     * inform users about the temporary unavailability of the service.
     *
     * @psalm-return 'The server is currently unable to handle the request due to temporary maintenance.'
     */
    private static function getMaintenanceMessage(): string
    {
        return 'The server is currently unable to handle the request due to temporary maintenance.';
    }
}
