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

namespace WPframework\Http;

class Asset
{
    /**
     * The Asset url.
     *
     * You can configure the asset URL by setting the ASSET_URL in your .env
     * Or optionally in the main constants file.
     *
     * @param string      $asset path to the asset like: "/images/thing.png"
     * @param null|string $path
     *
     * @return string
     */
    public static function url(string $asset, ?string $path = null): string
    {
        if ($path) {
            return HOME_URL . $path . $asset;
        }

        if (! \defined('ASSET_URL')) {
            return HOME_URL . '/assets/dist' . $asset;
        }

        return ASSET_URL . '/dist' . $asset;
    }
}
