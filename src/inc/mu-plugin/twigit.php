<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if ( ! \defined('ABSPATH')) {
    exit;
}

// Load Twig template renderer
if (\defined('USE_TWIGIT') && true === \constant('USE_TWIGIT')) {
    add_filter('template_include', function ($template) {
        return twigit($template);
    });
}
