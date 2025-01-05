<?php

/**
 * Plugin Name:       Headless CMS Mode
 * Plugin URI:        https://github.com/devuri/wpframework
 * Description:       Runs WordPress as a headless CMS when HEADLESS_MODE is set to true.
 * Version:           0.7
 * Requires at least: 5.3.0
 * Requires PHP:      7.3.5
 * Author:            uriel
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Network: true
 */

namespace WPframework\Headless;

if (! \defined('ABSPATH')) {
    exit;
}

if (! \defined('HEADLESS_MODE')) {
    \define('HEADLESS_MODE', true);
}

if (! \defined('DISABLE_PAGE_EDITOR_FIELD')) {
    \define('DISABLE_PAGE_EDITOR_FIELD', false);
}

if (! \defined('DISABLE_PAGE_EDITOR_FIELD')) {
    \define('DISABLE_PAGE_EDITOR_FIELD', false);
}

final class HeadlessMode
{
    public function __construct()
    {
        // Disable editor for post/page if constants are defined
        add_action('init', [$this, 'removeEditorFields']);

        if (HEADLESS_MODE) {
            add_action('template_redirect', [$this, 'redirectNonApiRequests']);
            add_action('wp_enqueue_scripts', [$this, 'removeScriptsAndStyles'], 100);
            add_filter('xmlrpc_enabled', '__return_false');

            // Remove REST API links, oEmbed, generator, etc.
            remove_action('wp_head', 'rest_output_link_wp_head');
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
            remove_action('wp_head', 'wp_oembed_add_host_js');
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'wlwmanifest_link');
            remove_action('wp_head', 'rsd_link');
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
            add_filter('emoji_svg_url', '__return_false');


            add_filter('show_admin_bar', [$this, 'disableAdminBarForNonAdmins']);
            add_action('init', [$this, 'removeComments']);
        }

        // PHP < 8 compatibility for str_starts_with
        $this->polyfillStrStartsWith();
    }

    public static function init()
    {
        return new self();
    }

    /**
     * Redirect all non-API requests (except wp-admin) to 404.
     */
    public function redirectNonApiRequests(): void
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (! is_admin() && ! $this->strStartsWith($_SERVER['REQUEST_URI'] ?? '', '/wp-json/')) {
            // Uncomment if you want a real 404 response:
            // wp_die('Headless mode active. Frontend is disabled.', 'Headless Mode', ['response' => 404]);
        }
    }

    /**
     * Remove unnecessary scripts and styles.
     */
    public function removeScriptsAndStyles(): void
    {
        wp_dequeue_script('wp-embed');
        wp_dequeue_style('wp-block-library');
    }

    /**
     * Disable the admin bar for users who cannot manage options.
     *
     * @param bool $show Original admin bar display value.
     *
     * @return bool
     */
    public function disableAdminBarForNonAdmins($show)
    {
        return current_user_can('manage_options') ? $show : false;
    }

    /**
     * Remove comments and pingback features from posts and pages.
     */
    public function removeComments(): void
    {
        remove_post_type_support('post', 'comments');
        remove_post_type_support('page', 'comments');
        add_filter('comments_open', '__return_false');
        add_filter('pings_open', '__return_false');
    }

    /**
     * Remove the WordPress editor for post/page if DISABLE_POST_EDITOR_FIELD
     * or DISABLE_PAGE_EDITOR_FIELD constants are defined.
     */
    public function removeEditorFields(): void
    {
        if (\defined('DISABLE_POST_EDITOR_FIELD') && DISABLE_POST_EDITOR_FIELD) {
            remove_post_type_support('post', 'editor');
        }

        if (\defined('DISABLE_PAGE_EDITOR_FIELD') && DISABLE_PAGE_EDITOR_FIELD) {
            remove_post_type_support('page', 'editor');
        }
    }

    /**
     * Polyfill str_starts_with for PHP < 8.
     */
    private function polyfillStrStartsWith()
    {
        if (! \function_exists('str_starts_with')) {
            function str_starts_with($haystack, $needle)
            {
                return substr($haystack, 0, \strlen($needle)) === $needle;
            }
        }
    }

    /**
     * Safe wrapper for str_starts_with call (handles the polyfill name conflict).
     *
     * @param string $haystack The string to search in.
     * @param string $needle   The substring to search for.
     *
     * @return bool
     */
    private function strStartsWith($haystack, $needle)
    {
        // If running PHP 8+, this calls the native function; otherwise, calls the polyfill.
        return str_starts_with($haystack, $needle);
    }
}

HeadlessMode::init();
