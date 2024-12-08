# Multi-Tenancy Setup

## Overview

The multi-tenant framework in **Raydium** allows you to operate multiple websites (tenants) on a shared infrastructure, with unique configurations and customizations for each tenant. This setup is ideal for managing a network of sites, such as SaaS applications or multi-tenant environments.

> [!IMPORTANT]  
> **Raydium Multi-Tenant** functionality is in early development. While functional, we recommend proceeding with caution and thoroughly testing your applications before deploying to production.

## Step 1: Backup Your Site

Before making any changes, create a complete backup of your files and database to ensure you can restore your site if needed.

## Step 2: Prerequisites

Before configuring multi-tenancy, complete the following:

> The framework relies on `composer.json` for initial setup and `configs/tenancy.json` for global tenant configurations. Tenant-specific settings can be stored in a database (default) or `configs/tenants.json` (optional).

### 1. Enable Multi-Tenancy in `composer.json`
Modify your `composer.json` file to activate multi-tenant functionality:
```json
"extra": {
    "multitenant": {
        "is_active": true,
        "uuid": "81243057"
    }
}
```
- **`is_active`**: Set to `true` to enable multi-tenancy.
- **`uuid`**: A unique identifier for the "Landlord" (main) site. Replace `81243057` with a unique value.

> `81243057` is the main tenant uuid, for convenience its also visible in the back-end admin footer.

Save the file to apply changes.

### 2. Configure the Landlord Database
The Landlord site (main site) manages the tenants. Update your `.env` file with the Landlord database credentials:

1. **Backup Existing `configs/.env` File**  
   Before making changes, back up your current `.env` file.

2. **Create or Update `.env` File**  
   Add the Landlord database configuration:
   ```php
   # Landlord Database Configuration
   LANDLORD_DB_NAME=      # Landlord database name
   LANDLORD_DB_USER=      # Landlord database username
   LANDLORD_DB_PASSWORD=  # Landlord database password
   LANDLORD_DB_HOST=localhost  # Database host, usually 'localhost'
   LANDLORD_DB_PREFIX=wp_lo6j2n6v_  # Adjust table prefix as needed
   ```

These settings should match those of the main site also referred to as the Landlord site. Adjust the values to reflect your specific Landlord database credentials

3. **Save Changes**  
   The `configs/.env` file is used for initial setup. Ensure it matches the Landlord site's database credentials.

### 3. (Optional) Use `tenancy.json` for Global Configuration
You can optionally create a `configs/tenancy.json` file to define global tenant settings. If absent, the system uses its built-in defaults. Example configuration:

#### Example `tenancy.json`
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
- **`require-config`**: Determines if tenant-specific configuration files are mandatory.
- **`tenant-management.isolation`**: Defines whether tenant data is stored in separate databases or shared within one.

> **Note**: `tenancy.json` is optional and unrelated to the tenant setup method. It provides fine-grained control for advanced users.


## Step 2: Choose a Tenant Management Method

Raydium provides two primary methods for managing tenants. Choose the one that best suits your use case:


### Method 1: Using `tenants.json` (File-Based Setup)

This method is ideal for smaller setups or development environments.

#### 1. Create a `tenants.json` File
Define tenants manually in `configs/tenants.json`:
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
  "beta": {
    "id": 2,
    "uuid": "i789j012k345",
    "name": "Beta Solutions",
    "domain": "beta.domain2.local",
    "user_id": 101,
    "status": "active"
  }
}
```

### Method 2: Using the Tenancy Plugin (Database-Driven Setup)

This method is recommended for larger or dynamic setups where frequent tenant updates are required.

#### 1. Install and Activate the Tenancy Plugin
1. Download and activate the [Tenancy Plugin](#).
2. The plugin will create the necessary database tables and add an admin interface to the "Landlord" site.

#### 2. Add and Manage Tenants via the Plugin
1. Navigate to the plugin interface in the Landlord admin dashboard.
2. Use the interface to:
   - Add new tenants (e.g., define the domain, UUID, and basic details).
   - Manage existing tenants (update, delete, or deactivate tenants as needed).
3. We can now add tenant using the admin dashboard interface.


## Step 3: Configure Tenant Databases

> Whichever method lets assume we add a new tenant `alpha.domain1.local`

1. Access the tenant domain (e.g., `alpha.domain1.local`) in a browser.
2. If an error occurs, retry. The system will auto-generate a `.env` file at `configs/{tenant_id}/.env`.
3. Edit the generated `.env` file for each tenant to include tenant-specific database credentials:
    ```php
    DB_NAME=       # Tenant database name
    DB_USER=       # Tenant database username
    DB_PASSWORD=   # Tenant database password
    DB_HOST=localhost  # Database host
    ```

> [!IMPORTANT]
> This should be a **new database** for the new tenant that we just created, **DO NOT** use the same database credentials as the main/Landlord tenant.

4. Revisit the tenant domain and complete the installation process.

## Step 4: Directory Structure Overview

After setup, your directory structure will look like this:

```
configs/
│
├── tenants.json       # Required only for file-based setup
├── {tenant_id}/       # Tenant-specific configurations
│   ├── .env           # Tenant-specific environment variables
```

Or if using advanced setups:

```
configs/
│
├── tenancy.json       # Optional global configuration
├── tenants.json       # Required only for file-based setup
├── {tenant_id}/       # Tenant-specific configurations
│   ├── .env           # Tenant-specific environment variables
│   ├── app.php        # Optional tenant-specific app settings
│   ├── config.php     # Optional tenant-specific config settings
```


## Choosing the Right Method

- **File-Based (`tenants.json`)**: Best for:
  - Small-scale setups with fewer than 50 tenants.
  - Development or testing environments.
- **Plugin-Based**: Best for:
  - Large-scale setups requiring frequent tenant updates.
  - Dynamic deployments with an easy-to-use admin interface.

## Next Steps

1. Test your setup thoroughly.
2. Explore advanced features such as caching, logging, and tenant-specific configurations.
3. Join the community for tips and support.

By following these steps, you'll have a multi-tenant setup tailored to your needs.
