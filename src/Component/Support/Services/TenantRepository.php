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

class TenantRepository
{
    public function findById(array $tenantDomain)
    {
        return self::tenants($tenantDomain[0]);
    }

    public static function tenants(string $domain): ?array
    {
        $tenants = [
            "domain1" => [
                "id" => 1,
                "uuid" => "81243057",
                "name" => "Tenant One",
                "domain" => "domain1.local",
                "user_id" => 100,
                "created_at" => "2023-01-01 10:00:00",
                "status" => "active",
            ],
            "appone" => [
                "id" => 2,
                "uuid" => "1e1e1e1e-1e1e-1e1e-1e1e-1e1e1e1e1e1e",
                "name" => "Tenant One",
                "domain" => "appone.domain1.local",
                "user_id" => 101,
                "created_at" => "2023-01-01 10:00:00",
                "status" => "active",
            ],
            "apptwo" => [
                "id" => 3,
                "uuid" => "2e2e2e2e-2e2e-2e2e-2e2e-2e2e2e2e2e2e",
                "name" => "Tenant Two",
                "domain" => "apptwo.domain2.local",
                "user_id" => 201,
                "created_at" => "2023-01-02 11:00:00",
                "status" => "active",
            ],
            "appthree" => [
                "id" => 4,
                "uuid" => "3e3e3e3e-3e3e-3e3e-3e3e-3e3e3e3e3e3e",
                "name" => "Tenant Three",
                "domain" => "appthree.domain3.local",
                "user_id" => 301,
                "created_at" => "2023-01-03 12:00:00",
                "status" => "inactive",
            ],
            "aplus" => [
                "id" => 5,
                "uuid" => "4e4e4e4e-4e4e-4e4e-4e4e-4e4e4e4e4e4e",
                "name" => "Aplus Tenant",
                "domain" => "aplus.domain3.local",
                "user_id" => 401,
                "created_at" => "2024-01-03 12:00:00",
                "status" => "active",
            ],
        ];

        return $tenants[$domain] ?? null;
    }
}
