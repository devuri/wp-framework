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

namespace WPframework\Support\Services\AdminKiosk;

use Psr\Http\Message\ServerRequestInterface;
use WPframework\Http\Message\HtmlResponse;

class PanelHandler
{
    public function dashboard(ServerRequestInterface $request, array $args = []): HtmlResponse
    {
        return new HtmlResponse('<h1>Admin Dashboard</h1><a href="/admin/tables">Manage Tables</a>');
    }

    public function renderTemplate(ServerRequestInterface $request, array $vars = [])
    {
        return "dashboard";
    }
}
