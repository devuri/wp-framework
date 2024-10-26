<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use WPframework\Http\HttpClient;

/**
 * Tests for HttpClient.
 *
 * @group WPframework\Http
 *
 * @covers \WPframework\Http\HttpClient
 *
 * @internal
 */
class HttpClientTest extends TestCase
{
    private $httpClient;

    public function a_curl_request(): void
    {
        $request = new HttpClient('https://jsonplaceholder.typicode.com/');
        $response = $request->curl_request('posts/1');
    }

    public function test_referrer_request(): void
    {
        $refer = new HttpClient('https://jsonplaceholder.typicode.com/');
        $refer->set_referrer("https://refer.example.com");
        $response = $refer->get('posts/1');

        $this->assertEquals(200, $response['status']);
        $this->assertEquals('OK', $response['message']);
        $this->assertEquals('https://refer.example.com', $response['referrer']);
    }

    public function test_json_placeholder_request(): void
    {
        $placeholder = new HttpClient('https://jsonplaceholder.typicode.com/', [ 'timeout' => 5 ]);

        $this->assertEquals(5, $placeholder->context()->get('timeout'));

        $response = $placeholder->get('posts/1');

        $this->assertEquals(200, $response['status']);
        $this->assertEquals('OK', $response['message']);
    }

    public function test_youtube_400_bad_request(): void
    {
        $badrequest = new HttpClient('https://www.googleapis.com', [ 'timeout' => 5 ]);

        $this->assertEquals(5, $badrequest->context()->get('timeout'));

        $response = $badrequest->get('/youtube/v3/playlists');

        $this->assertEquals(400, $response['status']);
        $this->assertEquals('Bad Request', $response['message']);
    }

    public function test_fake_url(): void
    {
        $dummyjson = new HttpClient('https://fake.api.example.com/', [ 'timeout' => 5 ]);
        $this->assertEquals(5, $dummyjson->context()->get('timeout'));

        $response = $dummyjson->get('products/1');

        $this->assertEquals(0, $response['status']);
    }

    public function test_client_defaults(): void
    {
        // $user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36';
        $defaultClient = new HttpClient('https://api.example.com/');

        $this->assertNull($defaultClient->context()->get('api_key'));
        $this->assertEquals('chrome', $defaultClient->context()->get('user_agent'));
        $this->assertEquals(10, $defaultClient->context()->get('timeout'));
    }

    public function test_http_client(): void
    {
        $this->httpClient = new HttpClient('https://api.example.com/', ['timeout' => 30]);

        $this->assertEquals(30, $this->httpClient->context()->get('timeout'));
    }

    public function test_get_request(): void
    {
        // Mocking file_get_contents to return a JSON response
        $url = 'https://api.example.com/data/endpoint';
        $response = json_encode(['success' => true, 'data' => 'Test data']);

        $this->httpClient = $this->createMock(HttpClient::class);
        $this->httpClient->method('get')
            ->with($this->equalTo('/data/endpoint'))
            ->willReturn(['status' => 200, 'body' => $response]);

        $result = $this->httpClient->get('/data/endpoint');

        $this->assertIsArray($result);
        $this->assertEquals(200, $result['status']);
        $this->assertEquals($response, $result['body']);
    }

    public function test_post_request(): void
    {
        // Mocking file_get_contents to simulate a POST response
        $url = 'https://api.example.com/data/post';
        $postData = ['key' => 'value'];
        $response = json_encode(['success' => true]);

        $this->httpClient = $this->createMock(HttpClient::class);
        $this->httpClient->method('post')
            ->with(
                $this->equalTo('/data/post'),
                $this->equalTo($postData)
            )
            ->willReturn(['status' => 200, 'body' => $response]);

        $result = $this->httpClient->post('/data/post', $postData);

        $this->assertIsArray($result);
        $this->assertEquals(200, $result['status']);
        $this->assertEquals($response, $result['body']);
    }

    public function test_valid_ssl_certificate(): void
    {
        $httpClient = new HttpClient('https://www.googleapis.com');

        $response = $httpClient->get('/');
        $this->assertEquals(400, $response['status'], 'Should return HTTP 400 for valid SSL');
    }

    // public function testInvalidSSLCertificate()
    // {
    // 	$httpClient = new HttpClient('https://expired.badssl.com');
    //
    // 	$response = $httpClient->get('/');
    // 	$this->assertEquals(500, $response['status'], 'Should return HTTP 500 for invalid SSL');
    // }
}
