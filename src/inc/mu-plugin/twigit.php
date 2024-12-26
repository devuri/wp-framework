<?php
/**
 * Plugin Name:       Twigit
 * Plugin URI:        https://github.com/devuri/wpframework
 * Description:       Framework Twig templates.
 * Version:           0.1
 * Requires at least: 5.3.0
 * Requires PHP:      7.3.5
 * Author:            uriel
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Network: true
 */

if ( ! \defined('ABSPATH')) {
    exit;
}

// Load Twig template renderer
if (\defined('USE_TWIGIT') && true === \constant('USE_TWIGIT')) {
	// get \WP_Theme
	$theme = wp_get_theme();
    $twig = twigit();
	$twig->templateFilter();
}
