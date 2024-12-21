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
use WPframework\Exceptions\TenantNotFoundException;
use WPframework\Support\Services\TenantRepository;
use WPframework\Support\Services\TenantResolver;

class TenantIdMiddleware extends AbstractMiddleware
{
    private $configs;
    private $tenantResolver;
    private $tenantSetup;
    private $isMultitenant;
    private $constManager;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->constManager = $this->services->get('const_builder');
        $this->configs = $this->services->get('configs');
        $this->isMultitenant = self::isMultitenantApp($this->configs->config['composer']);

        if ( ! $this->isMultitenant) {
            return $handler->handle($request);
        }

        $dbTenants = [];
        $this->tenantResolver = $this->tenantResolver($dbTenants);

        $tenantDomain = [];

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
        \define('LANDLORD_UUID', $this->configs->config['composer']->get('extra.multitenant.uuid', null));

        // allow overrides.
        $this->constManager->define('REQUIRE_TENANT_CONFIG', $this->configs->config['tenancy']->get('require-config', false));
        $this->constManager->define('TENANCY_WEB_ROOT', $this->configs->config['tenancy']->get('web-root', 'public'));
        $this->constManager->define('PUBLIC_WEB_DIR', $this->configs->getAppPath() . '/' . TENANCY_WEB_ROOT);
        $this->constManager->define('APP_CONTENT_DIR', 'wp-content');

        $request = $request->withAttribute('isMultitenant', $this->isMultitenant);

        return $handler->handle($request);
    }

    public function tenantResolver(array $tenants): TenantResolver
    {
        $repository = new TenantRepository($this->configs);
        $repository->addTenants($tenants);

        return new TenantResolver($repository);
    }

    /**
     * Checks if the provided tenant ID matches the landlord's UUID.
     *
     * This function determines if a given tenant ID is equivalent to the predefined
     * LANDLORD_UUID constant, identifying if the tenant is the landlord.
     *
     * @param null|string $tenantId The tenant ID to check against the landlord's UUID. Default is null.
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
     * Determines the database prefix for the tenant.
     *
     * @param string $tenantId Tenant's UUID.
     *
     * @return null|string Database prefix or null if not the main site.
     */
    private function getDBPrefix(string $tenantId): ?string
    {
        if (self::isLandlord()) {
            return env('LANDLORD_DB_PREFIX');
        }

        return null;
    }
}
