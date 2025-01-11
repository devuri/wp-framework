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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WPframework\Support\Configs;

class ConstMiddleware extends AbstractMiddleware
{
    private $constManager;
    private $siteManager;

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->constManager = $this->services->get('const_builder');
        $this->siteManager = $this->services->get('site_manager');

        $this->siteManager->setSwitcher($this->services->get('switcher'));

        $this->siteManager->appSetup($request)->constants();

        $this->constManager->setMap();



        if (! self::isValidHomeUrl()) {
            throw new \Exception("Error: WP_HOME or WP_SITEURL is not set or invalid check your .env files", 1);
        }

        $request = $request->withAttribute('isProd', $this->isProd());

        return $handler->handle($request);
    }

    private function isProd(): bool
    {
        return Configs::isProd($this->siteManager->getEnvironment());
    }

	/**
	 * Validates the `WP_HOME` and `WP_SITEURL` constants.
	 *
	 * This method ensures that both `WP_HOME` and `WP_SITEURL` are strictly validated
	 * to prevent ambiguous error messages caused by invalid configurations.
	 * It checks whether these constants are defined and whether they contain
	 * valid URL formats. If either validation fails, an error message is logged.
	 *
	 * ## Notes:
	 * - Issues can arise if `.env` file values are incorrectly set. For example:
	 *   - `HOME_URL='http://localhost/'`
	 *   - `WP_SITEURL="${WP_HOME}/wp"` should be "${HOME_URL}/wp"
	 * - Ensure that these values are properly configured and point to valid, resolvable URLs.
	 *
	 * @return bool True if both `WP_HOME` and `WP_SITEURL` (if defined) are valid URLs, false otherwise.
	 */
    private static function isValidHomeUrl(): bool
    {
        if (!defined('WP_HOME') || !filter_var(constant('WP_HOME'), FILTER_VALIDATE_URL)) {
			error_log('Invalid WP_HOME: Ensure it is defined and a valid URL.');
			return false;
        }

        if (defined('WP_SITEURL') && !filter_var(constant('WP_SITEURL'), FILTER_VALIDATE_URL)) {
			error_log('Invalid WP_SITEURL: Ensure it is defined and a valid URL.');
			return false;
        }

        return true;
    }
}
