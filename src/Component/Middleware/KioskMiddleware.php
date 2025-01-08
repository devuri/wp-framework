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
use WPframework\Http\Message\HtmlResponse;
use WPframework\Support\Services\AdminKiosk\PanelHandler;
use WPframework\Support\Services\Router;

class KioskMiddleware extends AbstractMiddleware
{
    private $isAdminKiosk;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cfgs = $this->services->get('configs');
        $kioskConfig = $cfgs->config['kiosk'];
        $this->isAdminKiosk = $request->getAttribute('isAdminKiosk', false);


        // If database admin is disabled.
        if (!$this->isAdminKiosk) {
            return $handler->handle($request);
        }

        $isAuthenticated = $request->getAttribute('authCheck', false);

        // Validate authentication
        if (!$isAuthenticated) {
            // throw new Exception("Authentication is required", 401);
        }

        $loader = new \Twig\Loader\FilesystemLoader(
            \dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'inc/kiosk/templates'
        );
        $twig = new \Twig\Environment($loader);
        $router = new Router($twig);

        // $router->get('/', [PanelHandler::class, 'dashboard']);
        // $router->get('/admin/{name}', [PanelHandler::class, 'admin']);
        $router->get('/', 'home');
        $router->get('/settings', 'settings');
        $router->get('/users', 'users');
        $router->get('/analytics', 'analytics');
        $router->get('/logs', 'logs');

        // $router->get('/hello/{name}', function (ServerRequestInterface $request, $name) {
        //     return new HtmlResponse("Hello! {$name}");
        // });

        // // Serve the database admin page
        // if ($isDbAdminRequest && $isAdmin) {
        //     require \dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'inc/configs/dbadmin/index.php';
        //     exit;
        // }

        return $router->process($request, $handler);
    }
}
