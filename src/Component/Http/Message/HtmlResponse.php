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

namespace WPframework\Http\Message;

use Nyholm\Psr7\Response;

// @phpstan-ignore-next-line
class HtmlResponse extends Response
{
    public function __construct(string $html = '', int $status = 200, array $headers = ['Content-Type' => 'text/html; charset=UTF-8'])
    {
        parent::__construct($status, $headers);
        $this->getBody()->write($html);
    }
}
