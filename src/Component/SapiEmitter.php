<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

use Psr\Http\Message\ResponseInterface;

class SapiEmitter
{
    /**
     * Emit the given response to the browser/client.
     *
     * @param ResponseInterface $response The finalized response.
     *
     * @return void
     */
    public function emitBody(ResponseInterface $response): void
    {
        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }

        while ( ! $body->eof()) {
            echo $body->read(8192);
        }
    }

    /**
     * Sends HTTP headers based on the provided array of headers.
     *
     * This method iterates over the given headers and sends them using the `header()` function.
     * For headers specified in the `$appendHeaders` list, multiple values are appended rather than replaced.
     * For other headers, replacement behavior is controlled by the `$replaceByDefault` parameter.
     *
     * @param array $headers          An associative array of headers to send. The array key is the header name,
     *                                and the value is an array of header values. Example:
     *                                [
     *                                'Content-Type' => ['text/html; charset=UTF-8'],
     *                                'Set-Cookie' => ['cookie1=value1', 'cookie2=value2']
     *                                ].
     * @param bool  $replaceByDefault Whether to replace existing headers by default.
     *                                If true, headers not in `$appendHeaders` will replace existing headers.
     *                                Defaults to false.
     * @param array $appendHeaders    A list of headers (case-insensitive) for which multiple values
     *                                should be appended rather than replaced.
     *                                Defaults to `['set-cookie']`.
     *
     * @return void
     */
    public function emitHeaders(ResponseInterface $response, bool $replaceByDefault = false, array $appendHeaders = ['set-cookie']): void
    {
        $statusLine = \sprintf(
            'HTTP/%s %d %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        header($statusLine);

        foreach ($response->getHeaders() as $name => $values) {
            $lowerName = strtolower($name);
            $replace = ! \in_array($lowerName, $appendHeaders, true) ? $replaceByDefault : false;

            foreach ($values as $index => $value) {
                $header = \sprintf('%s: %s', $name, $value);
                header($header, $replace);
                $replace = false;
            }
        }
    }
}
