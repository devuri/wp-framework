<?php

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
    private $tenants = null;

    public function __construct(array $tenants = [])
    {
        if (empty($tenants)) {
            $this->tenants = (new Configs())->config['tenants'];
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
        if ( ! $this->tenants) {
            return null;
        }

        return $this->tenants()->get($domain, null);
    }

    private function tenants()
    {
        return $this->tenants;
    }
}
