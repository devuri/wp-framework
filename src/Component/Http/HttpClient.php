<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Http;

use Exception;
use Urisoft\DotAccess;

// https://github.com/devuri/http
class HttpClient
{
    private string $base_url;
    private ?string $api_key;
    private array $headers;
    private array $stream_opts;
    private array $http_response;
    private DotAccess $context;
    private ?string $referrer;
    private ?string $user_agent;
    private array $agents = [
        'moz'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
        'chrome' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36',
        'safari' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Safari/602.1.50',
    ];

    public function __construct(string $base_url, array $context = [])
    {
        $this->base_url = $base_url;
        $this->context  = new DotAccess(
            array_merge(
                [
                    'user_agent' => 'chrome',
                    'api_key'    => null,
                    'timeout'    => 10,
                ],
                $context
            )
        );
        $this->api_key  = $this->context->get('api_key');
        $this->referrer = null;
        $agent_key      = $this->context->get('user_agent');

        if (\array_key_exists($agent_key, $this->agents)) {
            $this->user_agent = $this->agents[$agent_key];
        }
    }

    public function setUserAgent(string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }

    public function setReferrer(string $referrer): void
    {
        $this->referrer = $referrer;
    }

    public function context(): DotAccess
    {
        return $this->context;
    }

    public function get(string $endpoint, array $headers = []): array
    {
        return $this->request($endpoint, 'GET', [], $headers);
    }

    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request($endpoint, 'POST', $data, $headers);
    }

    public function curlRequest(string $endpoint, string $method = 'GET', array $data = [], array $headers = [])
    {
        return $this->curl($endpoint, $method, $data, $headers);
    }

    /**
     * @psalm-return array<never, never>
     *
     * @param null|mixed $responseBody
     */
    protected function setHttpResponse(array $http_response_header, $responseBody = null): array
    {
        if (! empty($http_response_header)) {
            $this->http_response['referrer'] = $this->referrer;
            $this->http_response['response'] = $http_response_header;
            $this->http_response['http']     = explode(' ', $http_response_header[0], 3);
            $this->http_response['code']     = (int) $this->http_response['http'][1];
            $this->http_response['message']  = $this->http_response['http'][2];
            $this->http_response['status']   = $this->http_response['code'];
            $this->http_response['body']   = $responseBody;
        }

        return [];
    }

    /**
     * @return string[]
     *
     * @psalm-return list{0?: string}
     */
    protected function getDefaultHeaders(): array
    {
        return $this->api_key ? [ 'Authorization: Bearer ' . $this->api_key ] : [];
    }

    protected function setHeaders(array $headers = []): void
    {
        $this->headers = $headers;
    }

    private function request(string $endpoint, string $method, array $data = [], array $headers = []): array
    {
        if (! $this->user_agent) {
            $this->user_agent = $this->agents['chrome'];
        }

        $url      = $this->base_url . $endpoint;
        $defaults = $this->getDefaultHeaders();
        $headers  = array_merge($defaults, $headers, [ 'User-Agent' => $this->user_agent ]);

        if ($this->referrer && ! empty($this->referrer)) {
            $headers[] = 'Referer: ' . $this->referrer;
        }

        $this->setHeaders($headers);

        $opts = [
            'http' => [
                'method'  => $method,
                'header'  => implode("\r\n", $headers),
                'content' => $this->prepareHttpContent($method, $data),
                'timeout' => $this->context->get('timeout'),
            ],
            'ssl'  => [
                'verify_peer'       => true,
                'verify_peer_name'  => true,
                'allow_self_signed' => false,
            ],
        ];

        $_ca_path_file = $this->getCaBundle();

        if (is_dir($_ca_path_file)) {
            $opts['ssl']['capath'] = $_ca_path_file;
        } else {
            $opts['ssl']['cafile'] = $_ca_path_file;
        }

        $this->stream_opts = $opts;
        $context = stream_context_create($opts);

        try {
            $responseBody = @file_get_contents($url, false, $context);
            if (false === $responseBody) {
                $error = error_get_last();

                throw new Exception('HTTP request failed: ' . $error['message']);
            }
        } catch (Exception $e) {
            // error_log( $e->getMessage() );
            if (! isset($http_response_header)) {
                return [
                    'status'   => 0,
                    'message'  => 'unknown error',
                    'response' => [],
                ];
            }

            $this->setHttpResponse($http_response_header);

            return [
                'status'  => $this->http_response['code'],
                'message' => $this->http_response['message'],
            ];
        }// end try

        // https://www.php.net/manual/en/reserved.variables.httpresponseheader.php
        $this->setHttpResponse($http_response_header, $responseBody);

        return $this->http_response;
    }

    /**
     * @return (bool|mixed|string)[]
     *
     * @psalm-return array{status?: mixed, response?: bool|string, error?: 'curl_init not found'}
     */
    private function curl(string $endpoint, string $method, array $data = [], array $headers = []): array
    {
        $url = $this->base_url . $endpoint;

        if (! $this->isCurlAvailable()) {
            return [ 'error' => 'curl_init not found' ];
        }

        $ch = curl_init();

        // Set HTTP method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // Set the headers
        array_push($headers, 'User-Agent: ' . $this->user_agent);
        if ($this->api_key) {
            array_push($headers, 'Authorization: Bearer ' . $this->api_key);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Enable HTTP/2
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        // Enable Keep-Alive
        curl_setopt($ch, CURLOPT_TCP_KEEPALIVE, 1);

        // Set POST fields
        if ('POST' === $method) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        // Set other options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->context->get('timeout'));

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status'   => $httpcode,
            'response' => $response,
        ];
    }

    private function isCurlAvailable(): bool
    {
        return \function_exists('curl_init');
    }

    private function parseHttpStatus(array $http_response_header): int
    {
        if (! empty($http_response_header)) {
            $status_line = explode(' ', $http_response_header[0], 3);

            return (int) $status_line[1];
        }

        return 0;
    }

    private function getCaBundle(): string
    {
        return \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath();
    }

    /**
     * Prepare HTTP content based on method type.
     *
     * @param string $method HTTP method.
     * @param array  $data   Data to send in the request.
     *
     * @return null|string Returns the URL-encoded query string if method is 'POST'; otherwise, null.
     */
    private function prepareHttpContent($method, $data)
    {
        return 'POST' === $method ? http_build_query($data) : null;
    }
}
