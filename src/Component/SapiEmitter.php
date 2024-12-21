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
     * Sends HTTP headers based on the provided response object and configuration.
     *
     * This method processes the headers from the provided `ResponseInterface` object and emits them
     * using PHP's `header()` function. It supports appending multiple values for specific headers
     * (e.g., `Set-Cookie`) and can either replace or append headers based on the specified behavior.
     *
     * The status line is also emitted as the first header.
     *
     * @param ResponseInterface $response         The response object containing headers to emit.
     *                                            The response must implement `ResponseInterface`,
     *                                            providing methods like `getProtocolVersion()`,
     *                                            `getStatusCode()`, `getReasonPhrase()`, and `getHeaders()`.
     * @param bool              $replaceByDefault Optional. Whether to replace existing headers by default.
     *                                            Headers not listed in `$appendHeaders` will follow
     *                                            this behavior. Defaults to false.
     * @param array             $appendHeaders    Optional. An array of header names (case-insensitive)
     *                                            for which multiple values should be appended
     *                                            instead of replacing the header. Defaults to `['set-cookie']`.
     *
     * @return void This method does not return any value.
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
