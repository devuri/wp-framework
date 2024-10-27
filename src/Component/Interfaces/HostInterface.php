<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Interfaces;

interface HostInterface
{
    /**
     * Determines if the current request is made over HTTPS.
     *
     * @return bool True if the request is over HTTPS, false otherwise.
     */
    public function is_https_secure(): bool;

    /**
     * Retrieves the sanitized HTTP host if available, otherwise a default value.
     *
     * @return string The sanitized host name or a default value.
     */
    public function get_http_host(): string;

    /**
     * Extracts the host domain and determines the protocol prefix.
     *
     * @return array An associative array with 'prefix' (protocol) and 'domain' (host domain).
     */
    public function get_server_host(): array;

    /**
     * Constructs the full request URL based on the current protocol and app host.
     *
     * @return null|string The full request URL or null if the app host is not available.
     */
    public function get_request_url(): ?string;
}
