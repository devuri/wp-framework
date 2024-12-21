<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware;

use Pimple\Psr11\Container as PsrContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    /**
     * @var PsrContainer
     */
    protected $services;

    public function __construct(?PsrContainer $serviceContainer = null)
    {
        $this->services = $serviceContainer;
    }

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    abstract public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;

    /**
     * @return LoggerInterface
     */
    protected function log(): LoggerInterface
    {
        return $this->services->get('logger');
    }

    protected function when(): void
    {
        // $this->log()->info('middleware(' . time() . '): ' . static::class);
    }

    /**
     * Merges two multi-dimensional arrays recursively.
     *
     * This function will recursively merge the values of `$array2` into `$array1`.
     * If the same key exists in both arrays, and both corresponding values are arrays,
     * the values are recursively merged.
     * Otherwise, values from `$array2` will overwrite those in `$array1`.
     *
     * @param array $array1 The base array that will be merged into.
     * @param array $array2 The array with values to merge into `$array1`.
     *
     * @return array The merged array.
     */
    protected static function multiMerge(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (isset($merged[$key]) && \is_array($merged[$key]) && \is_array($value)) {
                $merged[$key] = self::multiMerge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * Determines if the application is configured to operate in multi-tenant mode.
     *
     * @param mixed $composerConfig
     *
     * @return bool Returns `true` if the application is in multi-tenant mode, otherwise `false`.
     */
    protected static function isMultitenantApp($composerConfig): bool
    {
        return $composerConfig->get('extra.multitenant.is_active', false);
    }
}
