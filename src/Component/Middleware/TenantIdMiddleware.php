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

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WPframework\Config;
use WPframework\Exceptions\TenantNotFoundException;
use WPframework\Support\ConstantBuilder;
use WPframework\Support\Services\TenantRepository;
use WPframework\Support\Services\TenantResolver;

class TenantIdMiddleware extends AbstractMiddleware
{
    private $config;
    private $tenantResolver;
    private $tenantSetup;
    private $isMultitenant;
    private $configManager;

    public function __construct(ConstantBuilder $configManager)
    {
        $this->configManager = $configManager;
        $this->config        = new Config();
        $this->isMultitenant = self::isMultitenantApp($this->config->composer);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ( ! $this->isMultitenant) {
            return $handler->handle($request);
        }

        $this->tenantResolver = self::tenantResolver();

        try {
            $tenantDomain = $this->resolveTenantIdFromRequest($request);
            $tenant = $this->tenantResolver->getTenant($tenantDomain);
            $request = $request->withAttribute('tenant', $tenant);
        } catch (TenantNotFoundException $e) {
            throw new Exception("Tenant not found: {$tenantDomain[0]}", 404);
        }

        // required.
        \define('APP_TENANT_ID', $tenant['uuid']);
        \define('IS_MULTITENANT', true);
        \define('LANDLORD_UUID', $this->config->composer->get('extra.multitenant.uuid', null));

        // allow overrides.
        $this->configManager->define('REQUIRE_TENANT_CONFIG', $this->config->tenancy->get('require-config', false));
        $this->configManager->define('TENANCY_WEB_ROOT', $this->config->tenancy->get('web-root', 'public'));
        $this->configManager->define('PUBLIC_WEB_DIR', $this->config->getAppPath() . '/' . TENANCY_WEB_ROOT);
        $this->configManager->define('APP_CONTENT_DIR', 'wp-content');

        return $handler->handle($request);
    }

    public static function tenantResolver(): TenantResolver
    {
        return new TenantResolver(new TenantRepository());
    }

    /**
     * Checks if the provided tenant ID matches the landlord's UUID.
     *
     * This function determines if a given tenant ID is equivalent to the predefined
     * LANDLORD_UUID constant, identifying if the tenant is the landlord.
     *
     * @param null|string $tenant_id The tenant ID to check against the landlord's UUID. Default is null.
     *
     * @return bool True if the tenant ID matches the landlord's UUID, false otherwise.
     */
    protected static function isLandlord(?string $tenantId = null): bool
    {
        return \defined('LANDLORD_UUID') && \constant('LANDLORD_UUID') === $tenantId;
    }

    private function resolveTenantIdFromRequest(ServerRequestInterface $request): ?array
    {
        $host = $request->getUri()->getHost();
        $domainOrSubdomain = explode('.', $host)[0];

        if ($this->isValidTenantId($domainOrSubdomain)) {
            return [$domainOrSubdomain,$host];
        }

        return null;
    }

    private function isValidTenantId(string $tenantId): bool
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $tenantId);
    }

    /**
     * Determines if the application is configured to operate in multi-tenant mode.
     *
     * @param mixed $composerConfig
     *
     * @return bool Returns `true` if the application is in multi-tenant mode, otherwise `false`.
     */
    private static function isMultitenantApp($composerConfig): bool
    {
        return $composerConfig->get('extra.multitenant.is_active', false);
    }

    /**
     * Determines the database prefix for the tenant.
     *
     * @param string $tenantId Tenant's UUID.
     *
     * @return null|string Database prefix or null if not the main site.
     */
    private function getDBPrefix(string $tenantId): ?string
    {
        if (self::isLandlord()) {
            return env('TENANT_DB_PREFIX');
        }

        return null;
    }
}
