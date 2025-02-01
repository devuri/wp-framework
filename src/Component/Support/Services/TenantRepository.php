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

use WPframework\Support\Configs;

class TenantRepository
{
    private $configs;
    private $tenants;

    public function __construct(Configs $configs)
    {
        $this->configs = $configs;
    }

    public function addTenants(array $tenants): void
    {
        if (empty($tenants)) {
            $this->tenants = $this->configs->config['tenants'];
        } else {
            $this->tenants = $tenants;
        }
    }

    public function findById(array $tenantDomain)
    {
        return self::getTenant($tenantDomain[0]);
    }

    public function getTenant(string $domain)
    {
        if (! $this->tenants) {
            return null;
        }

        return $this->tenants()->get($domain, null);
    }

    private function tenants()
    {
        return $this->tenants;
    }
}
