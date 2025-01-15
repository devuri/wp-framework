# Deploy Your Raydium Site

Deploying a Raydium-powered WordPress site involves several considerations, tailored to accommodate Raydium's unique structure and dependencies, including Composer management:

- Your project, inclusive of Raydium and WordPress, resides in the root directory, with `public` serving as the web root containing the `public/wp-content` directory for WordPress themes, plugins, and uploads.
- The `vendor` directory and `.env` file, essential for Raydium and WordPress configurations, are located outside the web root for enhanced security.
- Key deployment scripts are defined in your `composer.json`, facilitating tasks such as dependency installation and environment setup.

## Build and Test Locally

To ensure a smooth deployment, thoroughly test your site in a local environment:

1. Confirm that your local server is configured to serve the `public` directory as the web root.
2. Conduct a comprehensive review of your site's functionality, including themes, plugins, and custom content, to ensure everything operates as intended.
3. While WordPress dynamically renders pages, ensure any asset build processes (for custom themes or plugins) are executed, and the resulting assets are correctly placed within the `public/content` directory.
4. Run Composer to install or update dependencies, ensuring compatibility and functionality:

   ```sh
   composer install
   ```

## Setting a Public Base Path

For sites served from a subdirectory (e.g., `https://yourdomain.com/blog`), adjust the `HOME_URL` and `WP_SITEURL` in your `.env` file:

```shell
HOME_URL='https://yourdomain.com/blog'
WP_SITEURL="${HOME_URL}/wp"
```

This adjustment is needed for WordPress to generate accurate URLs for assets and pages.

## Platform Guides

### General Web Hosting (Shared, VPS, Dedicated)

For traditional web hosting platforms:

1. Upload your entire project, excluding the `vendor` directory. Ensure the `public` directory is set as the web root.

2. Run Composer on the server (if supported) to install dependencies:

   ```sh
   composer install --no-dev --optimize-autoloader
   ```

3. Migrate your database if moving from a different environment. Update the `.env` [environment file](../guide/customization/environments) with production environment settings.

### Managed WordPress Hosting

For managed WordPress hosting that supports Composer:

1. Check if your host supports Git-based deployments or direct Composer usage on the server.

2. Deploy your project via Git or other supported methods, ensuring the `public` directory aligns with the web root configuration.

3. Use SSH or hosting tools to run Composer on the server, installing necessary PHP dependencies.

### Cloud Platforms (AWS, Google Cloud, Azure)

Cloud platforms offer flexibility for deploying Composer-managed WordPress sites:

1. Choose a suitable service (e.g., VM instances, container services) that supports custom configurations and Composer.

2. Configure the service to set the `public` directory as the web root and secure the `vendor` and `.env` files outside the web root.

3. Utilize cloud-based CI/CD pipelines to automate deployments, including Composer dependency installations and environment configurations.

## What's Next?

- Post-deployment, actively monitor your site's performance and security, adjusting configurations as necessary.
- Evaluate CDN integration for further performance enhancements, especially for global audiences.
- Regularly update WordPress core, plugins, themes, and Composer dependencies to ensure ongoing site integrity and performance.
