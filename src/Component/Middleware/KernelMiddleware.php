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
use WPframework\Http\Message\Response;
use WPframework\Support\Configs;
use WPframework\Support\KernelConfig;

class KernelMiddleware extends AbstractMiddleware
{
    private $kernelConfig;
    private $pathError;
    private $configs;

    /**
     * Constructor to inject the response factory.
     *
     * @param KernelConfig $kernelConfig
     */
    public function __construct(KernelConfig $kernelConfig)
    {
        $this->kernelConfig = $kernelConfig;
    }

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
        $this->configs = new Configs();

        $this->kernelConfig->setKernelConstants($this->configs);

        $isProd = $request->getAttribute('isProd', false);

        if ( ! $this->isValidInstallerPath($isProd)) {
            throw new Exception(
                'config file and composer `installer-paths` did not match ' . $this->pathError
            );
        }

        if ($this->inMaintenanceMode()) {
            throw new Exception(self::getMaintenanceMessage());
        }

        return $handler->handle($request);
    }

    protected function isValidInstallerPath(bool $isProd): bool
    {
        $publicWebRoot = $this->configs->config['app']->get('directory.web_root_dir');
        $contentDir = $this->configs->config['app']->get('directory.content_dir');
        $plugin = $this->configs->config['app']->get('directory.plugin_dir');
        $muPlugins = $this->configs->config['app']->get('directory.mu_plugin_dir');

        // set by the framework.
        $installerPaths = [
            "$publicWebRoot/$muPlugins/{\$name}/",
            "$publicWebRoot/$plugin/{\$name}/",
            "public/$contentDir/themes/{\$name}/",
        ];

        if ( ! $isProd) {
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
        $configs_dir = SITE_CONFIGS_DIR;
        $maintenanceChecks = [
            // Affects the entire tenant network.
            PUBLIC_WEB_DIR . '/.maintenance' => 'Will affect the entire tenant network.',
            APP_DIR_PATH . "/{$configs_dir}/.maintenance" => 'Will affect the entire tenant network.',

            // Affects a single tenant.
            // $this->siteSetup->getAppPath() . '/.maintenance' => 'For single tenant.',
        ];

        foreach ($maintenanceChecks as $path => $scope) {
            if (file_exists($path)) {
                return true;

                break;
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
