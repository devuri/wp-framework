<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Interfaces;

interface TenantInterface
{
    /**
     * Determines if the application is configured to operate in multi-tenant mode.
     *
     * This is based on the presence and value of the `ALLOW_MULTITENANT` constant.
     * If `ALLOW_MULTITENANT` is defined and set to `true`, the application is
     * considered to be in multi-tenant mode.
     *
     * @return bool Returns `true` if the application is in multi-tenant mode, otherwise `false`.
     */
    public function isMultitenantApp(): bool;

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
    public function isLandlord(?string $tenant_id = null): bool;
}
