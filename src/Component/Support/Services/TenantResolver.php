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

use WPframework\Exceptions\TenantNotFoundException;

class TenantResolver
{
    protected $tenantRepository;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function getTenant(array $tenantDomain)
    {
        $tenant = $this->tenantRepository->findById($tenantDomain);

        if (! $tenant) {
            throw new TenantNotFoundException("Tenant with ID {$tenantDomain[0]} not found.");
        }

        return $tenant;
    }
}
