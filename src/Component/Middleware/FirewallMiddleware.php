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

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;

class FirewallMiddleware
{
    public function isBot(): bool
    {
        $userAgent = $this->getUserAgent();
        $clientHints = $this->getClientHints();

        $deviceDetector = $this->initializeDeviceDetector($userAgent, $clientHints);

        return $deviceDetector->isBot();
    }

    private function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    private function getClientHints(): ClientHints
    {
        return ClientHints::factory($_SERVER);
    }

    private function initializeDeviceDetector(string $userAgent, ClientHints $clientHints): DeviceDetector
    {
        $dd = new DeviceDetector($userAgent, $clientHints);
        $dd->discardBotInformation(); // Improves performance by skipping bot details
        $dd->parse();

        return $dd;
    }
}
