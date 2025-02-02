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

namespace WPframework;

use Psr\Http\Message\ResponseInterface;

/**
 * Responsible for emitting a PSR-7 Response to the SAPI.
 */
class SapiEmitter
{
    private const BUFFER_SIZE = 8192;

    /**
     * Emit headers and body from the provided PSR-7 response.
     *
     * @param ResponseInterface $response         The finalized response.
     * @param bool              $replaceByDefault Whether to replace existing headers by default.
     *                                            Headers not listed in `$appendHeaders` will follow
     *                                            this behavior. Defaults to false.
     * @param array             $appendHeaders    An array of header names (case-insensitive) for which
     *                                            multiple values should be appended instead of replacing.
     *                                            Defaults to ['set-cookie'].
     *
     * @return void
     */
    public function emit(ResponseInterface $response, bool $replaceByDefault = false, array $appendHeaders = ['set-cookie']): void
    {
        $this->emitHeaders($response, $replaceByDefault, $appendHeaders);
        $this->emitBody($response);
    }

    /**
     * Emit the body of the response to the browser/client in chunks.
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

        while (! $body->eof()) {
            echo $body->read(self::BUFFER_SIZE);
        }
    }

    /**
     * Sends HTTP headers based on the provided response object and configuration.
     *
     * Emits the status line as well as all headers from the response. Supports
     * appending values for specific headers (e.g., "Set-Cookie") if specified
     * via `$appendHeaders`; otherwise follows `$replaceByDefault`.
     *
     * @param ResponseInterface $response         The response object containing headers to emit.
     * @param bool              $replaceByDefault Whether to replace existing headers by default.
     * @param array             $appendHeaders    An array of header names (case-insensitive)
     *                                            for which multiple values should be appended.
     *
     * @return void
     */
    public function emitHeaders(ResponseInterface $response, bool $replaceByDefault = false, array $appendHeaders = ['set-cookie']): void
    {
        if (headers_sent()) {
            return;
        }

        $statusLine = \sprintf(
            'HTTP/%s %d %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        header($statusLine);

        // Emit remaining headers
        foreach ($response->getHeaders() as $name => $values) {
            $lowerName = strtolower($name);
            // Determine if this header should replace or append
            $replace = !\in_array($lowerName, $appendHeaders, true) ? $replaceByDefault : false;

            foreach ($values as $value) {
                $headerValue = \sprintf('%s: %s', $name, $value);
                header($headerValue, $replace);
                $replace = false;
            }
        }
    }
}
