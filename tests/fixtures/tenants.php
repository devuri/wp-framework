<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    "tenants" => [
        [
            "id" => 1,
            "uuid" => "1e1e1e1e-1e1e-1e1e-1e1e-1e1e1e1e1e1e",
            "name" => "Tenant One",
            "domain" => "appone.domain1.local",
            "user_id" => 101,
            "created_at" => "2023-01-01 10:00:00",
            "status" => "active",
        ],
        [
            "id" => 2,
            "uuid" => "2e2e2e2e-2e2e-2e2e-2e2e-2e2e2e2e2e2e",
            "name" => "Tenant Two",
            "domain" => "apptwo.domain1.local",
            "user_id" => 201,
            "created_at" => "2023-01-02 11:00:00",
            "status" => "active",
        ],
        [
            "id" => 3,
            "uuid" => "3e3e3e3e-3e3e-3e3e-3e3e-3e3e3e3e3e3e",
            "name" => "Tenant Three",
            "domain" => "appthree.domain1.local",
            "user_id" => 301,
            "created_at" => "2023-01-03 12:00:00",
            "status" => "inactive",
        ],
    ],
    "users" => [
        [
            "id" => 101,
            "email" => "user1@appone.com",
            "name" => "User One",
            "role" => "admin",
            "created_at" => "2023-01-01 10:10:00",
        ],
        [
            "id" => 201,
            "email" => "user1@apptwo.com",
            "name" => "User Two",
            "role" => "admin",
            "created_at" => "2023-01-02 11:10:00",
        ],
        [
            "id" => 301,
            "email" => "user1@appthree.com",
            "name" => "User Three",
            "role" => "admin",
            "created_at" => "2023-01-03 12:10:00",
        ],
    ],
];
