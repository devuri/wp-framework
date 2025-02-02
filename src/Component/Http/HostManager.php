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

namespace WPframework\Http;

use WPframework\Interfaces\HostInterface;

class HostManager implements HostInterface
{
    /**
     * Determines if the current request is made over HTTPS.
     *
     * @return bool True if the request is over HTTPS, false otherwise.
     */
    public function isHttpsSecure(): bool
    {
        if (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }

        // Check for the 'X-Forwarded-Proto' header in case of reverse proxy setups.
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' === strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            return true;
        }

        return false;
    }

    /**
     * Retrieves the sanitized HTTP host if available, otherwise a default value.
     *
     * @return string The sanitized host name or a default value.
     */
    public function getHttpHost(): string
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $httpHost = $this->sanitizeHttpHost($_SERVER['HTTP_HOST']);

            if ($httpHost) {
                return strtolower(rtrim($httpHost, '/'));
            }
        }

        return 'domain1.local';
    }

    /**
     * Extracts the host domain and determines the protocol prefix.
     *
     * @return (false|string)[] An associative array with 'prefix' (protocol) and 'domain' (host domain).
     *
     * @psalm-return array{prefix: 'http'|'https', domain: false|string}
     */
    public function getServerHost(): array
    {
        $host_domain = $this->getHttpHost();

        // Remove port information if present
        $portPosition = strrpos($host_domain, ':');
        if (false !== $portPosition) {
            $host_domain = substr($host_domain, 0, $portPosition);
        }

        $prefix = $this->isHttpsSecure() ? 'https' : 'http';

        return [
            'prefix' => $prefix,
            'domain' => $host_domain,
        ];
    }

    /**
     * Constructs the full request URL based on the current protocol and app host.
     *
     * @return string The full request URL or null if the app host is not available.
     */
    public function getRequestUrl(): string
    {
        $isHttps  = $this->isHttpsSecure();
        $app_host = $this->getHttpHost();

        $protocol = $isHttps ? 'https' : 'http';

        return filter_var($protocol . '://' . $app_host, FILTER_SANITIZE_URL);
    }

    /**
     * Sanitizes the HTTP host.
     *
     * @param string $httpHost The HTTP host to sanitize.
     *
     * @return null|string The sanitized host or null if invalid.
     */
    protected function sanitizeHttpHost(string $httpHost): ?string
    {
        $sanitizedHost = filter_var($httpHost, FILTER_SANITIZE_URL);

        if (preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $sanitizedHost)) {
            return $sanitizedHost;
        }

        return null;
    }
}
