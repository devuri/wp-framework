# Disabling Updates


The `DISABLE_AND_BLOCK_WPSERVER_UPDATES` constant is a configuration option that, when enabled, blocks WordPress updates and any outgoing requests to wordpress.org. This feature aligns with our framework's design philosophy, which centralizes updates through Composer and GitHub workflows. By disabling the default WordPress update mechanism, we ensure consistency in version control and avoid potential conflicts with our Git workflow and CI/CD pipeline.

> [!WARNING]
> Once this constant is enabled, users will experience limited functionality in the WordPress admin area regarding updates, plugin installation, and theme management. It is crucial that your CI/CD pipeline takes over all update responsibilities to ensure plugins, themes, and the WordPress core are kept up to date.


## Purpose

In this framework, managing updates directly from the WordPress admin area is discouraged. Instead, Composer is used to maintain plugin and theme versions, aligning with our version-controlled GitHub-based workflow. Disabling the WordPress update functionality helps in the following ways:

1. **Prevent Redundancy**: Updates from wordpress.org are redundant because Composer handles all dependencies and plugin updates. Blocking these requests reduces unnecessary network calls.
2. **Avoid Synchronization Issues**: Users attempting to update or install plugins from the WordPress admin can create discrepancies between the actual state of the code and `composer.json`. By preventing updates through the admin area, we maintain consistency across environments.
3. **CI/CD Compliance**: Allowing updates from the WordPress admin would contradict our CI/CD pipeline and automation objectives. This setting helps enforce a consistent, automated deployment workflow.

## Benefits of Using This Approach

### 1. Enhanced Control Over Updates
- By managing all updates through Composer and a CI/CD pipeline, you gain full control over what gets updated and when. This ensures that updates are deliberate and well-tested before being applied.

### 2. Improved Consistency Across Environments
- Blocking WordPress updates helps maintain consistency across different environments, such as development, staging, and production. All updates are performed in a version-controlled manner, reducing the likelihood of inconsistencies caused by ad-hoc updates from the WordPress admin.

### 3. Streamlined Workflow
- This approach enforces a streamlined workflow where all updates are performed using the same tools—Composer and GitHub workflows—resulting in a clean and predictable process. Developers know exactly where updates are made and how they are deployed, which minimizes confusion and errors.

### 4. Reduced Risk of Breaking Changes
- Updates from wordpress.org can sometimes introduce breaking changes that could disrupt your website unexpectedly. By taking control of the update process, you can ensure updates are properly tested in a staging environment, reducing the risk of issues in production.

### 5. Increased Security
- While blocking automatic updates might seem risky, it can enhance security when properly managed. You can schedule and test updates in a controlled environment, ensuring all changes are vetted for vulnerabilities before deploying them. This approach minimizes the chances of unexpected updates introducing new vulnerabilities.

### 6. Alignment with CI/CD Best Practices
- This approach directly supports continuous integration and continuous deployment best practices. Automating updates via a CI/CD pipeline allows you to maintain an ongoing deployment strategy where updates are integrated, tested, and deployed systematically.

## Side Effects and Considerations

### 1. Admin Area Limitations
- Once this constant is enabled, users will experience limited functionality in the WordPress admin area regarding updates, plugin installation, and theme management.
- The "Add New Plugin" and "Add New Theme" screens will not function as expected, and users may see errors or incomplete functionality. It is essential to provide documentation explaining why these features are disabled and how the new workflow should be used.

### 2. Security and Compatibility Risks
- **Security**: Disabling updates can introduce security vulnerabilities if the CI/CD pipeline does not fully manage updates. It is crucial that your CI/CD pipeline takes over all update responsibilities to ensure plugins, themes, and the WordPress core are kept up to date.
- **Compatibility**: If updates are not managed regularly, compatibility issues can arise. Ensuring Composer is used frequently and systematically to update dependencies is critical to avoid these risks.

### 3. Dependency on CI/CD Pipeline
- Enabling this constant means that your CI/CD pipeline must handle updates entirely. Ensure that Composer scripts and actions (e.g., GitHub Actions or other CI/CD tools) are configured to check for updates regularly and deploy them.
- It is also recommended to test these updates in a staging environment before deploying them to production to mitigate any unexpected issues.

### 4. Developer Documentation
- Developers, site administrators, and stakeholders must understand how this change affects their workflow. Manual installations or updates through the WordPress admin will no longer be available.
- Clear documentation should be provided to all stakeholders, explaining how to install or update plugins and themes via Composer.

### 5. Plugin Compatibility
- This setting aims to be compatible with premium plugins and themes. However, some plugins may require communication with wordpress.org for other functionalities.
- Ensure that all critical plugins continue to function as expected when updates are blocked. Testing and verification are important before enabling this setting in a production environment.

## Configuration

The `DISABLE_AND_BLOCK_WPSERVER_UPDATES` constant is **disabled by default**. To enable it, add the following line to your `wp-config.php` or another upstream configuration file:

```php
define('DISABLE_AND_BLOCK_WPSERVER_UPDATES', true);
```

By default, the constant is set to `false`, which means the standard WordPress update mechanism is retained.

## Requirements

To use this feature, you must also install the "WP Auto Updates" plugin, which provides additional control over update settings. This plugin can be installed via Composer:

- [WP Auto Updates Plugin](https://wordpress.org/plugins/wp-auto-updates/)


The `DISABLE_AND_BLOCK_WPSERVER_UPDATES` constant is an advanced feature designed for users who wish to take full control of WordPress updates using Composer and a CI/CD pipeline. While it can streamline updates and enhance consistency, it requires careful implementation and ongoing maintenance to mitigate the risks of outdated plugins and compatibility issues.

Enabling this constant means:
- No more updates from the WordPress admin area.
- A requirement to use Composer for all update management.
- Full reliance on your CI/CD pipeline to handle security patches and compatibility.

It is essential to be cautious with this setting and ensure that all stakeholders fully understand the new update workflow and their roles in maintaining the system's security and compatibility.
