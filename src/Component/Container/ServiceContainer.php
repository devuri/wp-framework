<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Container;

use Psr\Container\ContainerInterface;
use WPframework\Exceptions\ServiceContainerException;
use WPframework\Exceptions\ServiceNotFoundException;

class ServiceContainer implements ContainerInterface
{
    /**
     * The array of bindings in the container.
     *
     * @var array
     */
    protected array $bindings = [];

    /**
     * The array of resolved instances.
     *
     * @var array
     */
    protected array $instances = [];

    /**
     * Register a service or value in the container.
     *
     * @param string         $id
     * @param callable|mixed $resolver
     */
    public function set(string $id, $resolver): void
    {
        // Ensure the service can be bound as a callable or a raw value
        if ( ! \is_callable($resolver) && ! \is_object($resolver)) {
            throw new ServiceContainerException("Service '{$id}' must be callable or an object.");
        }

        // Clear resolved instance if re-registering
        unset($this->instances[$id]);

        $this->bindings[$id] = $resolver;
    }

    /**
     * Retrieve a service or value from the container.
     *
     * @param string $id
     *
     * @throws ServiceNotFoundException
     * @throws ServiceContainerException
     *
     * @return mixed
     */
    public function get(string $id)
    {
        // Return the resolved instance if it exists
        if (\array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        // Ensure the service exists in bindings
        if ( ! \array_key_exists($id, $this->bindings)) {
            throw new ServiceNotFoundException("Service '{$id}' not found in the container.");
        }

        $resolver = $this->bindings[$id];

        try {
            // If the resolver is callable, invoke it with the container for dependency injection
            $resolved = \is_callable($resolver) ? $resolver($this) : $resolver;

            // Cache the resolved instance
            $this->instances[$id] = $resolved;

            return $resolved;
        } catch (Throwable $e) {
            throw new ServiceContainerException(
                "An error occurred while resolving the service '{$id}'.",
                0,
                $e
            );
        }
    }

    /**
     * Check if a service or value exists in the container.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->bindings);
    }

    /**
     * Remove a service from the container.
     *
     * @param string $id
     */
    public function unset(string $id): void
    {
        unset($this->bindings[$id], $this->instances[$id]);
    }

    /**
     * Reset all bindings and resolved instances in the container.
     */
    public function reset(): void
    {
        $this->bindings = [];
        $this->instances = [];
    }
}
