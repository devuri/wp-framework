<?php

/**
 * Plugin Name:       WP-Framework Core
 * Plugin URI:        https://github.com/devuri/wpframework
 * Description:       Framework Core with `wpframework_init` hook.
 * Version:           0.7
 * Requires at least: 5.3.0
 * Requires PHP:      7.3.5
 * Author:            uriel
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Network: true
 */

if (! \defined('ABSPATH')) {
    exit;
}

if (defined('WP_INSTALLING') && WP_INSTALLING) {
    return;
}

if (! defined('APP_TENANT_ID')) {
    define('APP_TENANT_ID', null);
}

/**
 * Must-use plugins are loaded before normal plugins.
 *
 * Must-use plugins are typically used for critical functionality or site-wide customizations
 * that should always be active which makes this hook a good place to add critical functionality
 */
do_action('wpframework_init');

// custom theme directory.
if (\defined('APP_THEME_DIR')) {
    register_theme_directory(APP_THEME_DIR);
}

// Missing theme fix.
$theme_info = frameworkCurrentThemeInfo();
if (false === $theme_info['available']) {
    exitWithThemeError($theme_info);
}

if (isMultitenantApp()) {
    // separate uploads for multi tenant.
    add_filter('upload_dir', 'setMultitenantUploadDirectory');
}

add_filter('admin_footer_text', 'frameworkFooterLabel');
