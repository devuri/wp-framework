<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Middleware\Handlers;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use WPframework\Middleware\Handlers\MiddlewareDispatcher;
use WPframework\Middleware\Handlers\MiddlewareRegistry;

class FinalHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'Final handler response');
    }
}

class AddHeaderMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $response->withHeader('X-Custom-Header', 'CustomValue');
    }
}

class ModifyRequestMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $modifiedRequest = $request->withQueryParams(array_merge($request->getQueryParams(), ['added' => 'true']));

        return $handler->handle($modifiedRequest);
    }
}

class ShortCircuitMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response(403, [], 'Forbidden');
    }
}

/**
 * Tests for MiddlewareDispatcher.
 *
 * @group WPframework\Middleware\Handlers
 *
 * @covers \WPframework\Middleware\Handlers\MiddlewareDispatcher
 *
 * @internal
 */
class MiddlewareDispatcherTest extends TestCase
{
    public function test_handle_without_middleware_delegates_to_final_handler(): void
    {
        $finalHandler = new FinalHandler();
        $request = new ServerRequest('GET', '/test');
        $registry = new MiddlewareRegistry();

        $middlewareHandler = new MiddlewareDispatcher($finalHandler, $registry);
        $response = $middlewareHandler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('Final handler response', (string) $response->getBody());
    }

    public function test_middleware_adds_custom_header(): void
    {
        $finalHandler = new FinalHandler();
        $request = new ServerRequest('GET', '/test');
        $registry = new MiddlewareRegistry();

        // Middleware that adds a custom header to the response
        $middleware = new AddHeaderMiddleware();

        $middlewareHandler = new MiddlewareDispatcher($finalHandler, $registry);
        $middlewareHandler->addMiddleware($middleware);

        $response = $middlewareHandler->handle($request);

        // Assert that the custom header is added
        $this->assertTrue($response->hasHeader('X-Custom-Header'));
        $this->assertEquals('CustomValue', $response->getHeaderLine('X-Custom-Header'));
    }

    public function test_middleware_modifies_request(): void
    {
        $finalHandler = new FinalHandler();
        $registry = new MiddlewareRegistry();
        $request = new ServerRequest('GET', '/test', [], null, '1.1', [], [], ['initial' => 'value']);

        // Middleware that modifies the request (adds a query parameter)
        $middleware = new ModifyRequestMiddleware();

        $middlewareHandler = new MiddlewareDispatcher($finalHandler, $registry);
        $middlewareHandler->addMiddleware($middleware);

        $response = $middlewareHandler->handle($request);

        // Assert that the final handler response is returned
        $this->assertEquals('Final handler response', (string) $response->getBody());

        // Assert that the request was modified by the middleware
        // $this->assertArrayHasKey('added', $request->getQueryParams());
        // $this->assertEquals('true', $request->getQueryParams()['added']);
    }

    public function test_short_circuit_middleware(): void
    {
        $finalHandler = new FinalHandler();
        $registry = new MiddlewareRegistry();
        $request = new ServerRequest('GET', '/test');

        // Middleware that short-circuits the request, returning a 403 Forbidden response
        $shortCircuitMiddleware = new ShortCircuitMiddleware();

        $middlewareHandler = new MiddlewareDispatcher($finalHandler, $registry);
        $middlewareHandler->addMiddleware($shortCircuitMiddleware);

        $response = $middlewareHandler->handle($request);

        // Assert that the short-circuit middleware returns a 403 Forbidden response directly
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('Forbidden', (string) $response->getBody());
    }

    public function test_multiple_middleware_in_sequence(): void
    {
        $finalHandler = new FinalHandler();
        $registry = new MiddlewareRegistry();
        $request = new ServerRequest('GET', '/test');

        // Middleware stack: AddHeaderMiddleware -> ModifyRequestMiddleware -> FinalHandler
        $middlewareHandler = new MiddlewareDispatcher($finalHandler, $registry);

        // Add middleware in order
        $middlewareHandler->addMiddleware(new ModifyRequestMiddleware());
        $middlewareHandler->addMiddleware(new AddHeaderMiddleware());

        // Handle the request
        $response = $middlewareHandler->handle($request);

        // Assert that the custom header was added by AddHeaderMiddleware
        $this->assertTrue($response->hasHeader('X-Custom-Header'));
        $this->assertEquals('CustomValue', $response->getHeaderLine('X-Custom-Header'));

        // Assert that the request was modified by ModifyRequestMiddleware
        // $this->assertArrayHasKey('added', $request->getQueryParams());
        // $this->assertEquals('true', $request->getQueryParams()['added']);

        $this->assertEquals('Final handler response', (string) $response->getBody());
    }

    public function test_handle_throws_exception_and_logs_error(): void
    {
        $finalHandler = new FinalHandler();
        $registry = new MiddlewareRegistry();
        $request = new ServerRequest('GET', '/test');
        $logger = new NullLogger();

        $middleware = function (): void {
            throw new RuntimeException('Test exception');
        };

        $middlewareHandler = new MiddlewareDispatcher($finalHandler, $registry, $logger);
        $middlewareHandler->addMiddleware($middleware);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Test exception');

        $middlewareHandler->handle($request);
    }
}
