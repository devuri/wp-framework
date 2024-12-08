<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Http\Message;

use JsonException;
use Nyholm\Psr7\Response;

// @phpstan-ignore-next-line
class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     *
     * @param mixed $data       Data to encode as JSON.
     * @param int   $statusCode HTTP status code.
     * @param array $headers    Additional headers.
     *
     * @throws JsonException If encoding fails.
     */
    public function __construct($data, int $statusCode = 200, array $headers = [])
    {
        $headers = array_merge(['Content-Type' => 'application/json'], $headers);
        $body = json_encode($data, JSON_THROW_ON_ERROR);

        parent::__construct($statusCode, $headers, $body);
    }
}
