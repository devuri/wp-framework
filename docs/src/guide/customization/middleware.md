# Middlewares

Middlewares are classes that sit between the request and the final application logic. They can modify the request or response, perform authentication or logging, handle errors, apply caching, and so forth. Each middleware can be registered in `configs/middleware.php`, and when the framework boots, it merges your custom middlewares with its own defaults.

> [!IMPORTANT]  
> Proceed with caution when modifying middleware configurations, as improper setup can disrupt the request lifecycle and cause unintended side effects.

## Creating `middleware.php`

Inside your `configs` directory, create a file named `middleware.php`. This file returns an array of **alias keys** mapped to **fully qualified class names** of your custom or third-party middleware:

```php
<?php

return [
    // Example: custom security headers middleware
    'security_headers' => MyProject\Middleware\SecurityHeadersMiddleware::class,

    // Example: custom logging middleware
    'request_logger'   => MyProject\Middleware\RequestLoggingMiddleware::class,

    // Example: a custom authentication middleware
    'basic_auth'       => MyProject\Middleware\BasicAuthMiddleware::class,

    // Example: a performance profiling middleware
    'profiler'         => MyProject\Middleware\ProfilerMiddleware::class,
];
```

- Each **key** (like `'security_headers'` or `'basic_auth'`) is simply an alias that helps identify the middleware.  
- The **value** should be a fully qualified class name implementing the PSR-15 `MiddlewareInterface`.  
- The framework will **merge** these with its default middlewares; you only need to include your custom additions or overrides.

---

## How the Framework Loads Middleware

When a request comes in, the framework (using its internal dispatcher) assembles a pipeline of middleware in a specific order:

1. **Core (default) middlewares** – Provided by the framework (e.g., handling environment setup, default security checks, etc.).  
2. **Any custom or overridden middlewares** – Defined in your `configs/middleware.php`.  

> **Important:** If you specify a middleware with the **same key** as a default framework middleware, yours will override the default. If you use a **new key**, it will be added in **addition** to the defaults.

## Writing Your Own Middleware

> You can place your middleware classes anywhere, but a common convention is to have them in a `src/Middleware` or `app/Middleware` directory.

All custom middleware should implement the **PSR-15** interface, `Psr\Http\Server\MiddlewareInterface`. This interface requires a single method:

```php
public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler
): ResponseInterface;
```

Within `process()`, you can inspect and/or modify the incoming request, perform additional logic (e.g., authentication checks, caching, logging), and finally pass control to the **next** middleware or final handler by calling `$handler->handle($request)`.

Below are two illustrative examples: a **simple** middleware that sets a header and an **advanced** middleware that implements a form of rate limiting and logging.

### 1. Simple Middleware Example

This straightforward example adds a custom header to the outgoing response. It can be useful for setting basic security headers, custom debugging headers, or meta information about the application.

```php
namespace MyProject\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SecurityHeadersMiddleware implements MiddlewareInterface
{
    /**
     * Simple example of adding security headers to the response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Pass the request to the next middleware / final handler
        $response = $handler->handle($request);

        // Inject a security header; e.g., prevent clickjacking
        return $response->withHeader('X-Frame-Options', 'DENY');
    }
}
```

- We call `$handler->handle($request)` to get the **existing** response before modifying it.  
- We return a response with the new header appended.  
- This approach is **non-invasive**: the middleware only changes the headers without altering the main application logic.

### 2. Advanced Middleware Example

The following example demonstrates a more **advanced** use case: **rate limiting** combined with **logging**. Imagine you have an API endpoint that should only receive a certain number of requests per user in a given timeframe. This middleware leverages a cache or datastore (via a `$cache` service) and logs attempts (via a `$logger` service).

```php
namespace MyProject\Middleware;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RateLimiterMiddleware implements MiddlewareInterface
{
    private CacheInterface $cache;
    private LoggerInterface $logger;
    private int $maxAttempts;
    private int $decaySeconds;

    /**
     * @param CacheInterface  $cache        A PSR-16 simple cache for storing request counts.
     * @param LoggerInterface $logger       PSR-3 logger to log rate-limit events.
     * @param int             $maxAttempts  The max number of allowed attempts in the time window.
     * @param int             $decaySeconds The number of seconds before resetting the attempt count.
     */
    public function __construct(
        CacheInterface $cache,
        LoggerInterface $logger,
        int $maxAttempts = 60,
        int $decaySeconds = 60
    ) {
        $this->cache        = $cache;
        $this->logger       = $logger;
        $this->maxAttempts  = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Define your own logic for retrieving user info (JWT token, IP address, etc.)
        $userKey = $this->getUserKey($request);

        // Construct a cache key, e.g.: "rate_limit:192.168.1.10"
        $cacheKey = sprintf('rate_limit:%s', $userKey);

        // Retrieve current attempt count
        $attempts = (int) $this->cache->get($cacheKey, 0);

        if ($attempts >= $this->maxAttempts) {
            // Log the blocked request
            $this->logger->warning('Rate limit exceeded', ['userKey' => $userKey]);

            // Create and return a "429 Too Many Requests" response
            return new \GuzzleHttp\Psr7\Response(
                429,
                ['Content-Type' => 'application/json'],
                json_encode(['error' => 'Too Many Requests'])
            );
        }

        // Increment attempts
        $this->cache->set($cacheKey, $attempts + 1, $this->decaySeconds);

        // Pass the request on
        $response = $handler->handle($request);

        // Optionally log each successful request
        $this->logger->info('Request processed', [
            'userKey'   => $userKey,
            'remaining' => $this->maxAttempts - ($attempts + 1),
        ]);

        return $response;
    }

    /**
     * Helper function to parse a user key from the request.
     * Could be an IP address, a user ID from a token, etc.
     */
    private function getUserKey(ServerRequestInterface $request): string
    {
        // For demonstration, use the client's IP address.
        // In production, consider more robust methods (JWT, session, etc.).
        return $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';
    }
}
```

1. **Dependency Injection**:  
   - A **PSR-16** `CacheInterface` is used to store and retrieve rate-limit counters.  
   - A **PSR-3** `LoggerInterface` is used to record events.  
2. **Rate Limiting Logic**:  
   - The middleware checks how many requests a user/key has made.  
   - If the limit is exceeded, it returns a `429 Too Many Requests` response **immediately** without calling `$handler->handle($request)`.  
3. **Expiration** (`$decaySeconds`):  
   - We store the attempt count with a TTL (Time to Live), so it resets automatically.  
4. **Logging**:  
   - We log both blocked attempts (`warning`) and successful requests (`info`).  

This pattern shows how you can integrate external services (like caching or logging) into middleware to handle *common cross-cutting concerns*.


## Example Middleware Configuration

Here’s a sample `configs/middleware.php` file demonstrating different kinds of custom middlewares. Feel free to rename the aliases and class paths to suit your project:

```php
<?php

return [
    // Adds additional security headers to each response
    'security_headers' => \App\Middleware\SecurityHeadersMiddleware::class,

    // Checks for a custom user token or session
    'auth_token'       => \App\Middleware\TokenAuthMiddleware::class,

    // Logs incoming requests and responses (for debugging or analytics)
    'logger'           => \App\Middleware\LoggerMiddleware::class,

    // Custom "maintenance mode" middleware
    'maintenance'      => \App\Middleware\MaintenanceModeMiddleware::class,

    // Custom "rate limit" middleware to mitigate spam or abusive requests
    'rate_limiter'     => \App\Middleware\RateLimiterMiddleware::class,
];
```

You only need to define the **custom** middlewares you want to add (or override). The framework will take care of the rest.


## Overriding Default Middleware

If the framework ships with a middleware key—for example `'security'`—and you want to replace it entirely with your own class, just use the same key in `configs/middleware.php`:

```php
return [
    // This overrides the framework's default "security" middleware
    'security' => \MyApp\Middleware\CustomSecurityMiddleware::class,
];
```

When the application boots, your `CustomSecurityMiddleware` class replaces the framework’s default. If instead you give it a **new** key (e.g., `'security_v2'`), it simply adds a second security-related middleware **in addition** to the default.


## Disabling Middleware

The simplest way to **disable** a default framework middleware is to override its key with a “null” or intentionally invalid class. The framework’s architecture allows for graceful handling of `null` middlewares, so you can do something like:

```php
return [
    'security' => null,
];
```

> When the framework encounters a `null` middleware in the pipeline, it moves on to the next until it finds a valid middleware to use.


## Using Environment Variables

Like other configuration files, `middleware.php` can make use of environment variables:

```php
return [
    'request_logger' => env('ENABLE_LOGGER', true)
        ? \App\Middleware\LoggerMiddleware::class
        : null,
];
```

If `ENABLE_LOGGER` is set to `false` in your `.env` file, the logger middleware will not be loaded.

> **IMPORTANT:** Depending on your setup, environment variables may not always be available at this stage. Check the framework’s documentation on *when* and *how* environment variables are loaded so you can safely reference them in `middleware.php`.


## Inline Middleware

In some cases, you may prefer **not** to create a separate class for your middleware, especially if you only need a small piece of logic or you want to pass custom arguments. The framework allows you to supply a **callable** instead of a class name in your `configs/middleware.php`. This is referred to as **inline middleware**.

Since the dispatcher supports both **PSR-15** middlewares and **callables**, the simplest approach is to define your middleware as an **anonymous function (closure)** in `configs/middleware.php`, rather than a plain string. Inside that function, you can instantiate the middleware class with **whatever arguments** you need.

### How It Works

When the framework’s dispatcher encounters a callable in the middleware queue, it will pass the `ServerRequestInterface` and a `RequestHandlerInterface` to that callable. You can then create and invoke any logic you need, including instantiating a PSR-15 middleware **within** the closure.

**Inline Middleware Example**:

```php
<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use MyApp\Middleware\RateLimiterMiddleware;

return [
    'rate_limiter' => function (
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) {
        // Instantiate with custom arguments (e.g., a logger, cache, or config values)
        $logger = new \MyApp\Services\Logger();
        $cache  = new \MyApp\Services\InMemoryCache();

        $middleware = new RateLimiterMiddleware($cache, $logger, 60, 60);

        // Manually call the middleware’s process() method
        return $middleware->process($request, $handler);
    },
];
```

> Because you provided a closure, the dispatcher will invoke it with `$request` and `$handler`. Inside that closure, you can do anything—from instantiating a new PSR-15 middleware class to returning a custom `ResponseInterface` immediately.

### Why Use Inline Middleware?

- **Simplicity**: A quick piece of logic can be declared directly without creating a full class.  
- **Flexibility**: You can instantiate and configure objects however you like—pulling in environment variables, config data, or global utilities.  
- **One-off Logic**: If you don’t plan to reuse a middleware’s functionality elsewhere, it can be simpler to define it inline rather than in a dedicated file.

```php
<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use MyApp\Middleware\AdvancedSecurityMiddleware;

return [
    'inline_security' => function (
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) {
        // Fetch environment variables or config
        $hmacKey    = getenv('SECURITY_HMAC_KEY') ?: 'defaultKey';
        $allowedIPs = ['192.168.0.10', '192.168.0.50'];

        // Instantiate a PSR-15 middleware with custom arguments
        $middleware = new AdvancedSecurityMiddleware($hmacKey, $allowedIPs);

        // Manually call process()
        return $middleware->process($request, $handler);
    },
];
```

### Considerations

1. **Dependency Management**:  
   - Inline middleware is a quick way to inject arguments (logs, caches, config).  
   - For more complex setups, you might prefer a dedicated PSR-15 class that receives dependencies.

2. **Reusability**:  
   - Inline closures are best for single-use logic. If you need the same middleware in multiple places, consider extracting it into a reusable class.

3. **Performance**:  
   - Repeatedly building heavy objects in an inline middleware might affect performance under high load. If needed, consider caching or memoizing these objects, or manage them in a service container.

4. **Testing & Maintenance**:  
   - The logic is inline, so ensure you have the right coverage in your tests. If inline code grows too complex, move it into a dedicated middleware class.


- **Create/modify** `configs/middleware.php` to add or override middlewares.  
- Each array **key** is an alias, and the **value** is either:  
  - A fully qualified class name **implementing `Psr\Http\Server\MiddlewareInterface`**, **or**  
  - An **inline** callable (closure) that manually processes the request.  
- The framework **merges** these custom entries with its built-in middlewares at runtime.  
- **Override** a default by using the same key; **add** new ones by using unique keys; **disable** by setting the key to `null` (where supported).  
- If you rely on environment variables, confirm they are loaded before referencing them in your middleware config.

Below is a minimal example you might start with:

```php
<?php

return [
    'my_custom_security' => \MyApp\Middleware\SecurityHeadersMiddleware::class,
    'my_logger'          => \MyApp\Middleware\LoggerMiddleware::class,
];
```

From there, you can expand and refine your pipeline to meet your application’s needs.

## Tips for Production

- **Graceful Degradation**: Consider how your middleware should behave if external services (cache, database, etc.) are unavailable.  
- **Error Handling**: Wrap potentially failing operations in try-catch blocks to avoid bringing down the entire request.  
- **Configuration**: Store limits, environment-specific settings, or any other variable data in config files or environment variables.  
- **Testing**: Thoroughly test your middleware in local and staging environments to confirm it behaves as expected under various conditions and loads.

### Notes & Best Practices

1. **Environments & Globals**  
   - If you need environment variables or configuration data, ensure they’re loaded before defining the closure or fetch them via static/global methods within the closure.

2. **Testing**  
   - Inline logic can be tested with unit or integration tests. If your closure does something complex, consider moving it into a dedicated middleware class for clarity.

3. **Performance**  
   - Constantly creating large objects in a closure might be expensive under high load. Where relevant, consider patterns like singletons or memoization to avoid repeated heavy instantiation.


## ⚠ **Middlewares Affect Application Flow**  

Middleware plays a critical role in processing requests and responses within the framework. Misconfiguring or improperly implementing middleware **can lead to unexpected behaviors, performance issues, or even security vulnerabilities**.  

### **Things to Keep in Mind:**  
- **Order Matters:** Middleware is executed in a specific sequence. Ensure that critical middleware (such as authentication or security headers) is **executed before** others that depend on them.  
- **Blocking Requests:** If a middleware **does not call** `$handler->handle($request)`, it will **halt** request execution, potentially causing blank pages, failed API responses, or missing functionality.  
- **Performance Impact:** Middleware that performs expensive operations (such as database queries, file reads, or external API calls) on every request **can slow down** your application. Consider **caching or optimizing** such operations.  
- **Error Handling:** Improper exception handling in middleware **can break request flow**. Always wrap error-prone logic in try-catch blocks and log errors where necessary.  
- **Disabling Middleware:** Setting middleware to `null` in `configs/middleware.php` disables it. Ensure that this does not unintentionally remove essential functionality.  
- **Testing & Debugging:** Always **test middleware configurations in a development environment** before deploying to production. Unexpected middleware behavior can lead to difficult-to-debug issues.  
