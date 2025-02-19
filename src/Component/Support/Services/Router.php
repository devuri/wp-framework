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

use Exception;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WPframework\Http\Message\HtmlResponse;

class Router implements MiddlewareInterface
{
    private array $routes = [];
    private array $postItem = [];
    private ?Dispatcher $dispatcher = null;
    private ?\Twig\Environment $twig;

    public function __construct(?\Twig\Environment $twig = null)
    {
        $this->twig = $twig;
    }

    /**
     * @param false|string $handler
     */
    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    public function setPostItem(?array $postItem = null): void
    {
        if ($postItem && \is_array($postItem)) {
            $this->postItem = $postItem;
        }
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->initDispatcher();

        $method = $request->getMethod();
        $uri = rawurldecode($request->getUri()->getPath());
        $routeInfo = $this->dispatcher->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return $this->handle404($request);

            case Dispatcher::METHOD_NOT_ALLOWED:
                return $this->createResponse(405, "405 Method Not Allowed");

            case Dispatcher::FOUND:
                return $this->handleFoundRoute($routeInfo[1], $routeInfo[2], $request);

            default:
                throw new Exception("Error Processing Route", 1);
        }
    }

    protected function handleFoundRoute($callback, array $vars, ServerRequestInterface $request): ResponseInterface
    {
        if (\is_string($callback) && $this->twig) {
            $statusCode = 200;
            $template = $this->resolveTemplate($callback, $vars);

            $loaderPath = $this->twig->getLoader()->getPaths();

            if (!file_exists($loaderPath[0] . "/{$template}") && !file_exists($loaderPath[1] . "/{$template}")) {
                $statusCode = 404;
                $template = '404.twig';
            }

            return $this->createResponse($statusCode, $this->twig->render($template, $this->postItem));
        }

        $handler = self::resolve($callback);
        $response = $handler($request, ...array_values($vars));

        if (!$response instanceof ResponseInterface) {
            throw new Exception("Error: Internal Server Error: Handler did not return a valid ResponseInterface", 1);
        }

        return $response;
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[] = [$method, $path, $handler];
    }

    private function handle404(ServerRequestInterface $request): ResponseInterface
    {
        return $this->createResponse(404, $this->twig->render('404.twig', []));
    }

    private static function resolve($callback)
    {
        if (\is_callable($callback)) {
            return $callback;
        }

        if (\is_array($callback) && 2 === \count($callback)) {
            [$class, $method] = $callback;

            if (\is_string($class)) {
                $class = new $class();
            }

            if (\is_callable([$class, $method])) {
                return [$class, $method];
            }
        }

        throw new InvalidArgumentException('Invalid callback provided. It must be a callable or a valid [Class, method] pair.');
    }

    private function resolveTemplate(string $handler, array $vars): string
    {
        $page = $vars['page'] ?? 'index';
        $templateName = str_replace(['{page}'], $page, $handler);
        // @phpstan-ignore-next-line
        $loaderPath = $this->twig->getLoader()->getPaths();

        return "{$templateName}.twig";
    }

    private function createResponse(int $status, string $body): HtmlResponse
    {
        return new HtmlResponse($body, $status);
    }

    private function initDispatcher(): void
    {
        if (!$this->dispatcher) {
            $this->dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r): void {
                foreach ($this->routes as $route) {
                    [$method, $path, $handler] = $route;
                    $r->addRoute($method, $path, $handler);
                }
            });
        }
    }
}
