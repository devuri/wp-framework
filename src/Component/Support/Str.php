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

namespace WPframework\Support;

use Exception;

class Str
{
    /**
     * Basic Sanitize and prepare for a string input for safe usage in the application.
     *
     * This function sanitizes the input by removing leading/trailing whitespace,
     * stripping HTML and PHP tags, converting special characters to HTML entities,
     * and removing potentially dangerous characters for security.
     *
     * @param string $input The input string to sanitize.
     *
     * @return string The sanitized input ready for safe usage within the application.
     */
    public static function sanitize(string $input): string
    {
        $input = trim($input);
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        $input = str_replace(["'", "\"", "--", ";"], "", $input);

        return filter_var($input, FILTER_UNSAFE_RAW, FILTER_FLAG_NO_ENCODE_QUOTES);
    }

    /**
     * Polyfill for str_contains for PHP 7.4, using the native function if available.
     *
     * @param string $haystack The string to search in.
     * @param string $needle   The substring to search for.
     *
     * @return bool True if $haystack contains $needle, false otherwise.
     */
    public static function contains(string $haystack, string $needle): bool
    {
        if (\function_exists('str_contains')) {
            return str_contains($haystack, $needle);
        }

        return '' === $needle || false !== strpos($haystack, $needle);
    }

    /**
     * Polyfill for str_starts_with for PHP versions before 8.0, using the native function if available.
     *
     * @param string $haystack The string to search in.
     * @param string $needle   The substring to search for.
     *
     * @return bool True if $haystack starts with $needle, false otherwise.
     */
    public static function startsWith(string $haystack, string $needle): bool
    {
        if (\function_exists('str_starts_with')) {
            return str_starts_with($haystack, $needle);
        }

        return '' === $needle || 0 === strpos($haystack, $needle);
    }

    public static function sanitizeKey(string $key)
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $key);
    }

    /**
     * Sanitizes the HTTP host.
     *
     * @param string $httpHost The HTTP host to sanitize.
     *
     * @return null|string The sanitized host or null if invalid.
     */
    public function sanitizeHttpHost(string $httpHost): ?string
    {
        $sanitizedHost = filter_var($httpHost, FILTER_SANITIZE_URL);

        if (preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $sanitizedHost)) {
            return $sanitizedHost;
        }

        return null;
    }

    /**
     * Gets hash of given string.
     *
     * @param string $data      Message to be hashed.
     * @param string $secretkey Secret key used for generating the HMAC variant.
     * @param string $algo      Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..)
     *
     * @return false|string Returns a string containing the calculated hash value.
     *
     * @see https://www.php.net/manual/en/function.hash-hmac.php
     */
    public static function hmac(string $data, string $secretkey, string $algo = 'sha256')
    {
        return hash_hmac($algo, $data, $secretkey);
    }

    /**
     * Retrieves the system's current external IP address.
     *
     * This method attempts to fetch the external IP address of the system
     * by sending a GET request to the "https://icanhazip.com/" service.
     *
     * @param string      $userAgent Optional. The User-Agent string to use for the HTTP request.
     *                               Defaults to 'Mozilla/5.0 (compatible; CustomBot/1.0)'.
     * @param null|string $referrer  Optional. The referrer URL to include in the HTTP request.
     *                               Defaults to the value of the `HOME_URL` environment variable, if available.
     *
     * @return null|string The external IP address if successfully retrieved and valid,
     *                     or null if the address could not be fetched or is invalid.
     */
    public static function getExternalIP(string $userAgent = 'Mozilla/5.0 (compatible; CustomBot/1.0)', ?string $referrer = null): ?string
    {
        $ipAddress = null;
        $referrerUrl = $referrer ? $referrer : env('HOME_URL');

        try {
            $httpClient = new \WPframework\Http\HttpClient('https://icanhazip.com/');
            $httpClient->setUserAgent($userAgent);

            // Set referrer if available from environment.
            if ($referrerUrl) {
                $httpClient->setReferrer($referrerUrl);
            }

            // Make a GET request to fetch the IP.
            $response = $httpClient->get('/');

            // Ensure the response status is successful.
            if (200 === $response['status']) {
                $ipAddress = trim($response['body'] ?? '');
            }

            // Validate that the result is a valid IP address.
            if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                $ipAddress = null;
            }
        } catch (Exception $e) {
            error_log('Error fetching external IP: ' . $e->getMessage());
        }

        return $ipAddress;
    }
}
