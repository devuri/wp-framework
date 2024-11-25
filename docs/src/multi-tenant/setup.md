# Getting Started

## Installation

## Overview

The framework is designed to support multiple tenants (websites), each with its unique configuration and customization capabilities, on a shared infrastructure.

> [!IMPORTANT]
> Raydoum Multi-Tenant WordPress applications are functional, but we are in the early stages of developing this feature. While it works, it is important to proceed with caution. Be sure to thoroughly test your applications.

## Step 1: Backup Your Site

Before making any changes, ensure you have a full backup of your WordPress files and database.

Install and activate the [Tenancy plugin](#), this will create the required database tables and admin area on the main site ( referred to as the Landlord site ).

## Step 2: Enabling Multi-Tenancy

The framework relies on `composer.json` for initial setup and `configs/tenancy.json` for global tenant configurations. Tenant-specific settings can be stored in a database (default) or `configs/tenants.json` (optional).

### Initial Setup in `composer.json`

To activate the multi-tenant functionality within your setup, follow these steps:

1. **Access Configuration Files:**

Ensure you're in your application's root directory. Look for the `configs` directory. If it doesn't exist, you'll need to create it to store your configuration files.

To enable multi-tenancy, add the following configuration to the `extras` section of your `composer.json` file:

```json
"extra": {
    "multitenant": {
        "is_active": true,
        "uuid": "81243057"
    }
}
```

> `81243057` is the main tenant uuid, be sure to use a unique value here.

- **`is_active`**: A boolean flag to enable or disable multi-tenancy.
- **`uuid`**: The unique identifier for the landlord tenant.

**Activate Multi-Tenant Mode:**

- To enable the multi-tenant, you need to change the `is_active` setting from `false` to `true`. This action activates the multi-tenancy capabilities of your application, allowing it to handle requests for multiple tenants.


**Set the Landlord UUID:**
- Additionally, you'll need to specify the UUID for the landlord (main tenant). This unique identifier is typically provided at the bottom of the plugin's main page after you've enabled the Tenancy Manager Plugin. If you have this information, update the following line accordingly:

2. **Modify Tenancy Configuration:**

All tenant configurations are stored in `configs/tenancy.json`. The framework automatically loads tenant configurations from `configs/tenancy.json` during runtime.

The `configs/tenancy.json` file is **optional** as the framework will use default tenancy.json file. Configurations defined are global settings for the multi-tenant setup. If a custom `tenancy.json` is present in the `configs/` directory, it overrides the default configuration provided by the framework.

**Configuring `tenancy.json` (optional)**

   - In the `configs` directory, locate the `tenancy.json` file. If it's not present, you should create it. This file will hold your tenancy-related configurations.
   - Here's an example of default values you might find or include in `tenancy.json`:

   ### Example `tenancy.json`

   ```json
   {
     "require-config": false,
     "web-root": "public",
     "database": {
       "use_env": true,
       "default": "mysql"
     },
     "tenant-management": {
       "isolation": "database",
       "creation-strategy": "auto"
     },
     "id": {
       "strategy": "random",
       "random_length": 6,
       "collision_policy": "append_random_suffix"
     },
     "cache": {
       "enabled": true,
       "adapter": "redis",
       "prefix": "tenantcache"
     },
     "logging": {
       "level": "info",
       "per-tenant-logs": true
     },
     "features": {
       "tenant-specific-config": true,
       "cross-tenant-data-access": false
     },
     "security": {
       "encryption": {
         "enabled": true,
         "type": "AES-256"
       },
       "rate_limiting": {
         "enabled": true,
         "requests_per_minute": 100
       }
     }
   }
   ```

### Step 3: Configuring Landlord Environment Settings

To properly set up the Landlord environment for your multi-tenant application installation, follow these steps to ensure a proper database connection:

1. **Backup Your Existing Environment File**: Before making any changes, it's crucial to back up your current `.env` file.

2. **Create a New `.env` File**: In the root directory of application installation, create a new `.env` [environment file](../customization/environment-file). This file will store the environment-specific configurations for the Landlord database (settings in this env file are discarded after initial setup of the Landlord).

3. **Configure Landlord Database Settings**: Inside the newly created `.env` file, input the following configuration settings. These settings should match those of the main site (also referred to as the Landlord site) where the Tenancy plugin is installed. Adjust the values to reflect your specific Landlord database credentials:

   ```php
   # Landlord Database Configuration
   LANDLORD_DB_NAME=      # The name of your Landlord database
   LANDLORD_DB_USER=      # The username for your Landlord database access
   LANDLORD_DB_PASSWORD=  # The password for your Landlord database access
   LANDLORD_DB_HOST=localhost  # The hostname for your Landlord database server, typically 'localhost'
   LANDLORD_DB_PREFIX=wp_lo6j2n6v_  # The prefix for your Landlord database tables, adjust as needed
   ```

### Step 4: Managing Tenant Information

We can now create a new tenant in the main landlord site backend or use the tenants.json file.

whichever method we use once we create our first tenant for example `alpha.domain1.local` we can navigate to `alpha.domain1.local` in the browser, at first you may see error screen, this is usually because the `.env` file was not yet created, simply click retry as the tenant will auto setup and create `.env` file. After initial `.env` file has been created you will find it in `configs/{tenant_id}/.env` open the file and add the relavant database connection details.
After that you can go back to your browser navigate to `alpha.domain1.local` and follow the installation steps as per usual for standard wordpress installation process.

> [!IMPORTANT]
> You'll need to adjust the `.env` [environment file](../customization/environment-file) to align with the tenant database settings and site URL. Ensure the `WP_HOME` variable accurately reflects your site's URL for the tenant.

### Directory Structure

```
configs/
│
├── tenancy.json
├── tenants.json (optional)
├── {tenant_id}/
│   ├── .env
│   ├── app.php (optional)
│   ├── config.php (optional)
```


**Database-Driven Management (Default)**

Tenant information, such as UUIDs, domains, and statuses, is managed by default through the **Tenancy Plugin**. Tenant data is stored in a database table, enabling dynamic updates and scalability.

This approach is recommended for:
- Large-scale applications.
- Scenarios requiring frequent tenant updates.

**File-Based Management with `tenants.json` (Optional)**

For a small application with fewer than 50 tenants and limited need for frequent updates, `configs/tenants.json` JSON is a practical, fast, and easy choice. However, as the application grows or requires more dynamic tenant management, transitioning to a database would be a better long-term strategy.

> For simpler setups or development environments, tenant information can be defined in `configs/tenants.json`. This file provides an alternative to database-driven management but is less scalable.

#### Example `tenants.json`

```json
{
	"alpha": {
        "id": 1,
        "uuid": "h456i789j012",
        "name": "Alpha Version Limited",
        "domain": "alpha.domain1.local",
        "user_id": 100,
        "status": "active"
    },
}

```

## What's Next?

- Dive deeper into the functionalities and features of Raydium's multitenant setup by exploring the documentation.
- Connect with the Raydium community for additional support, insights, and to share your experiences and best practices.
