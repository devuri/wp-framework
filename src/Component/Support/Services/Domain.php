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

namespace WPframework\Support\Services;

use Psr\Http\Message\ServerRequestInterface;

class Domain
{
    protected string $baseDomain;
    protected string $targetSubdomain;

    /**
     * Constructor.
     *
     * @param string $baseDomain      The base domain (e.g., "example.com").
     * @param string $targetSubdomain The subdomain to detect (e.g., "admin").
     */
    public function __construct(string $baseDomain, string $targetSubdomain)
    {
        $this->baseDomain = $baseDomain;
        $this->targetSubdomain = $targetSubdomain;
    }

    /**
     * Get the full subdomain from a given host, handling nested subdomains.
     *
     * @param string $host The host name (e.g., "admin.staging.example.com").
     *
     * @return null|string The full subdomain (e.g., "admin.staging"), or null if no subdomain exists.
     */
    public function getFullSubdomain(string $host): ?string
    {
        if (!$this->strEndsWith($host, $this->baseDomain)) {
            return null;
        }

        $subdomainPart = rtrim(substr($host, 0, -\strlen($this->baseDomain)), '.');

        return empty($subdomainPart) ? null : $subdomainPart;
    }

    /**
     * Detect if the target subdomain exists in the subdomain.
     *
     * @param string $host The host name (e.g., "admin.staging.example.com").
     *
     * @return bool True if the target subdomain is present, false otherwise.
     */
    public function hasTargetSubdomain(string $host): bool
    {
        $fullSubdomain = $this->getFullSubdomain($host);

        if (null === $fullSubdomain) {
            return false;
        }

        return WPframework\Support\Str::contains($fullSubdomain, $this->targetSubdomain);
    }

    /**
     * Detect if the target subdomain exists in the subdomain from a PSR-7 ServerRequest.
     *
     * @param ServerRequestInterface $request The PSR-7 request.
     *
     * @return bool True if the target subdomain is present, false otherwise.
     */
    public function detectTargetSubdomain(ServerRequestInterface $request): bool
    {
        $host = $request->getUri()->getHost();

        return $this->hasTargetSubdomain($host);
    }

    /**
     * Polyfill for str_ends_with for PHP 7.4, using native function if available.
     *
     * @param string $haystack The string to check.
     * @param string $needle   The substring to search for.
     *
     * @return bool True if $haystack ends with $needle, false otherwise.
     */
    private function strEndsWith(string $haystack, string $needle): bool
    {
        if (\function_exists('str_ends_with')) {
            return str_ends_with($haystack, $needle);
        }

        $needleLength = \strlen($needle);

        return 0 === $needleLength || (substr($haystack, -$needleLength) === $needle);
    }
}
