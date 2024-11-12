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
 * This constant `DISABLE_AND_BLOCK_WPSERVER_UPDATES` disables and blocks WordPress updates and any outgoing requests to wordpress.org.
 * In our framework, these calls are mostly redundant since updates are managed through Composer and GitHub workflows.
 * This setting is designed to be compatible with premium plugins and themes.
 *
 * By disabling these updates, we also get the added benefit of preventing potential synchronization issues with composer.json.
 * When a user attempts to install plugins or themes directly from the WordPress admin area, those screens will not function as expected.
 * This behavior may confuse some users, so it is crucial to provide clear documentation explaining why this is the case and why it is acceptable.
 * Plugins and themes should not be installed directly from the WordPress admin area, as doing so would conflict with our Git workflow and CI/CD pipeline goals.
 *
 * ## Side Effects and Considerations ##
 * 1. **Admin Area Limitations**:
 *    - Users will experience limited functionality within the WordPress admin area regarding updates, plugin installation, and theme updates.
 *    - The "Add New Plugin" or "Add New Theme" screens will likely show errors or fail to load properly. This can be confusing, so it's important to explain that all plugin and theme management should be handled through Composer.
 *
 * 2. **Security and Compatibility Risks**:
 *    - By blocking updates, there is a potential security risk if your CI/CD pipeline does not fully take over the update process. Outdated plugins, themes, or WordPress core files can introduce vulnerabilities.
 *    - Compatibility issues can arise if plugins are not regularly updated. It is crucial to ensure that your Composer-based update strategy is robust and frequent enough to mitigate these risks.
 *    - Be very cautious when applying this setting in environments where regular security and compatibility updates are not guaranteed by your deployment pipeline.
 *
 * 3. **Dependency on CI/CD Pipeline**:
 *    - Once this setting is enabled, you must rely entirely on your CI/CD pipeline to manage all updates. This means your team needs to configure Composer scripts and GitHub actions (or equivalent tools) to check for updates regularly and deploy them in a controlled manner.
 *    - Ensure that dependency updates are tested properly in a staging environment before pushing them to production to avoid unexpected issues.
 *
 * 4. **Developer Documentation**:
 *    - Document the new workflow clearly so that developers, site administrators, and other stakeholders understand how updates should be managed.
 *    - Developers need to be informed that manual plugin or theme installations from the WordPress dashboard will no longer be supported, and all changes must be made through Composer.
 *
 * 5. **Plugin Compatibility**:
 *    - This setting is designed to be compatible with premium plugins and themes. However, some plugins may have built-in mechanisms that require communication with wordpress.org for license verification or other features.
 *    - Care should be taken to verify that all critical plugins continue to function correctly when updates are blocked.
 *
 * Note that this feature is available but disabled by default to retain the standard WordPress update mechanism.
 * It will only be activated if explicitly configured in wp-config.php or another upstream configuration file.
 *
 * Additionally, this feature requires the "WP Auto Updates" plugin v0.7, which can be installed via Composer:
 * https://wordpress.org/plugins/wp-auto-updates/
 */
if (!defined('DISABLE_AND_BLOCK_WPSERVER_UPDATES')) {
    define('DISABLE_AND_BLOCK_WPSERVER_UPDATES', false);
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
