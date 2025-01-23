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
    private $tenant;
    private $tenantResolver;
    private $constManager;
    private $kioskConfig;
    private array $dbTenants = [];
    private ?array $tenantDomain;

    /**
     * Process the incoming request and manage multitenant or kiosk-specific logic.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @throws Exception If tenant is disabled or other issues arise.
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->constManager  = $this->services->get('const_builder');
        $this->isMultitenant = self::isMultitenantApp($this->configs->config['composer']);
        $this->kioskConfig   = $this->configs->config['kiosk'];
        $this->tenantDomain  = $this->resolveTenantIdFromRequest($request);
        $this->isAdminKiosk  = $this->isKiosk($this->tenantDomain);
        $this->isShortInit   = $this->isShortInit();

        // Add attributes to the request
        $request = $request
            ->withAttribute('isAdminKiosk', $this->isAdminKiosk)
            ->withAttribute('isMultitenant', $this->isMultitenant)
            ->withAttribute('isShortInit', $this->isShortInit)
            ->withAttribute('tenant', $this->tenant);

        // check secure modes.
        if (self::isSecureMode() && 'https' !== $request->getUri()->getScheme()) {
            throw new Exception('Access to this resource requires HTTPS.', 403);
        }

        // If not a multitenant application or not an admin kiosk,
        // handle the request normally
        if (!$this->isMultitenant && false === $this->isAdminKiosk) {
            return $handler->handle($request);
        }

        // Set the current tenant
        $this->tenant = $this->setCurrentTenant();
        $request = $request->withAttribute('tenant', $this->tenant);

        // @phpstan-ignore-next-line
        if ($this->isAdminKiosk && $this->kioskConfig->get('panel.enabled', null)) {
            return $handler->handle($request);
        }

        // Check if the tenant is disabled
        if ('disabled' === ($this->tenant['status'] ?? null)) {
            $tenantStatus = ucfirst($this->tenant['status']);

            throw new Exception("Tenant {$this->tenantDomain[0]}: {$tenantStatus}", 404);
        }

        // Define required constants
        \define('APP_TENANT_ID', $this->tenant['uuid']);
        \define('IS_MULTITENANT', true);
        \define('LANDLORD_UUID', $this->configs->config['composer']->get('extra.multitenant.uuid', null));

        // Allow overrides via configuration
        $this->constManager->define('REQUIRE_TENANT_CONFIG', $this->configs->config['tenancy']->get('require-config', false));
        $this->constManager->define('TENANCY_WEB_ROOT', $this->configs->config['tenancy']->get('web-root', 'public'));
        $this->constManager->define('PUBLIC_WEB_DIR', $this->configs->getAppPath() . '/' . TENANCY_WEB_ROOT);
        $this->constManager->define('APP_CONTENT_DIR', 'wp-content');

        // Handle the request and return the response
        return $handler->handle($request);
    }

    public function tenantResolver(array $tenants): TenantResolver
    {
        $repository = new TenantRepository($this->configs);
        $repository->addTenants($tenants);

        return new TenantResolver($repository);
    }

    /**
     * Set the current tenant for the application.
     *
     * @param null|array $tenant Optional tenant data to directly set.
     *
     * @throws Exception If tenant cannot be resolved.
     *
     * @return null|array The resolved tenant.
     */
    protected function setCurrentTenant(?array $tenant = null): ?array
    {
        // If a tenant is provided, assign and return it directly.
        if (null !== $tenant) {
            $this->tenant = $tenant;

            return $this->tenant;
        }

        // Check if the application is in Admin Kiosk mode.
        if ($this->isAdminKiosk) {
            $this->tenant = $this->kioskTenant();

            return $this->tenant;
        }

        // Initialize the Tenant Resolver.
        $this->tenantResolver = $this->tenantResolver($this->dbTenants);

        try {
            $this->tenant = $this->tenantResolver->getTenant($this->tenantDomain);
        } catch (TenantNotFoundException $e) {
            throw new Exception(\sprintf(
                "Tenant not found for domain: %s",
                $this->tenantDomain[0] ?? 'unknown'
            ), 404, $e);
        }

        return $this->tenant;
    }

    protected function isKiosk(array $tenantDomain): ?bool
    {
        if (empty($tenantDomain)) {
            return false;
        }

        $kioskDomain = env('KIOSK_DOMAIN_ID', $this->kioskConfig->get('panel.id', 'kiosk'));

        return $kioskDomain === ($tenantDomain[0] ?? null);
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

    /**
     * Process the incoming request and enforce HTTPS for specific routes.
     *
     * @param ServerRequestInterface $request
     */
    protected function httpsOnlyRoute(ServerRequestInterface $request): void
    {
        if ($this->isAdminRouteRestricted($request) && 'https' !== $request->getUri()->getScheme()) {
            throw new Exception('Access to this resource requires HTTPS.', 403);
        }
    }

    private function kioskTenant(): array
    {
        return [
            'id' => $this->kioskConfig->get('panel.id', null),
            'uuid' => $this->kioskConfig->get('panel.uuid', null),
            'enabled' => $this->kioskConfig->get('panel.enabled', false),
            'framework' => $this->kioskConfig->get('panel.framework', 'kiosk'),
        ];
    }

    /**
     * @return null|string[]
     *
     * @psalm-return list{string, string}|null
     */
    private function resolveTenantIdFromRequest(ServerRequestInterface $request): array
    {
        $host = $request->getUri()->getHost();
        $domainOrSubdomain = explode('.', $host)[0];

        if ($this->isValidTenantId($domainOrSubdomain)) {
            return [$domainOrSubdomain,$host];
        }

        return [];
    }

    private function isShortInit(): bool
    {
        return \defined('SHORTINIT') && true === \constant('SHORTINIT');
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
