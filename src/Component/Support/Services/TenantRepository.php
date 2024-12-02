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

use WPframework\Config;

class TenantRepository
{
    private static $loadTenants = null;

    public function __construct(array $tenants = [])
    {
        if (null === self::$loadTenants) {
            self::$loadTenants = new Config();
        }
    }

    public function findById(array $tenantDomain)
    {
        return self::getTenant($tenantDomain[0]);
    }

    public static function getTenant(string $domain)
    {
        if ( ! self::$loadTenants->tenants) {
            return null;
        }

        return self::tenants()->get($domain, null);
    }

    private static function tenants()
    {
        return self::$loadTenants->tenants;
    }
}
