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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WPframework\Support\KernelConfig;
use WPframework\Terminate;

class KernelMiddleware extends AbstractMiddleware
{
    /**
     * @var KernelConfig
     */
    private $kernelConfig;

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
        $this->kernelConfig->setKernelConstants();

        $this->inMaintenanceMode();

        return $handler->handle($request);
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
    protected function inMaintenanceMode(): void
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
                Terminate::exit([ self::getMaintenanceMessage(), 503 ]);

                break;
            }
        }
    }

    /**
     * Get the maintenance message.
     *
     * This method returns a predefined maintenance message indicating that
     * the server is temporarily unavailable due to maintenance. It's used to
     * inform users about the temporary unavailability of the service.
     *
     * @return string The maintenance message to be displayed to users.
     *
     * @psalm-return 'Service Unavailable: <br>The server is currently unable to handle the request due to temporary maintenance of the server.'
     */
    private static function getMaintenanceMessage(): string
    {
        return 'Service Unavailable: <br>The server is currently unable to handle the request due to temporary maintenance of the server.';
    }
}
