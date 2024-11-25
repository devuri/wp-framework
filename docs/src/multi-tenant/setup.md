# Getting Started with Multi-Tenancy in Raydium

## Installation Overview

The multi-tenant framework in **Raydium** allows you to operate multiple websites (tenants) on a shared infrastructure, with unique configurations and customizations for each tenant. This setup is ideal for managing a network of sites, such as SaaS applications or multi-tenant environments.

> [!IMPORTANT]  
> **Raydium Multi-Tenant** functionality is in early development. While functional, we recommend proceeding with caution and thoroughly testing your applications before deploying to production.

---

## Step 1: Backup Your Site

Before making any changes, create a complete backup of your files and database to ensure you can restore your site if needed.

### Install the Tenancy Plugin
1. Download and activate the [Tenancy Plugin](#).
2. This plugin will create the required database tables and provide an admin interface on the main site (referred to as the "Landlord" site) for managing tenants.

---

## Step 2: Enable Multi-Tenancy

The multi-tenant setup requires modifications to your `composer.json` file.

The framework relies on `composer.json` for initial setup and `configs/tenancy.json` for global tenant configurations. Tenant-specific settings can be stored in a database (default) or `configs/tenants.json` (optional).

### Initial Setup in `composer.json`
To activate multi-tenant functionality:

1. **Modify `composer.json`**:  
   Update the following to the `extra` section of your `composer.json` file:
   ```json
   "extra": {
       "multitenant": {
           "is_active": true,
           "uuid": "81243057"
       }
   }
   ```
   - **`is_active`**: Enables (`true`) or disables (`false`) multi-tenancy.
   - **`uuid`**: The unique identifier for the "Landlord" (main) tenant. Replace `81243057` with a unique value.

> `81243057` is the main tenant uuid, be sure to use a unique value here.

2. **Activate Multi-Tenant Mode**:  
   - Set `is_active` to `true` to enable multi-tenancy.
   - Specify the Landlord UUID, which can be found in the Tenancy Plugin after activation.

3. **(Optional) Locate or Create Configuration Files**:  
    Ensure your application's root directory contains a `configs` directory. If not, create one to store the required configuration files.

	All tenant configurations are stored in `configs/tenancy.json`. The framework automatically loads tenant configurations from `configs/tenancy.json` during runtime.

	The `configs/tenancy.json` file is **optional** as the framework will use default tenancy.json file. Configurations defined are global settings for the multi-tenant setup. If a custom `tenancy.json` is present in the `configs/` directory, it overrides the default configuration provided by the framework.
---

### Configuring `tenancy.json` (Optional)
Tenant configurations can be stored in a `tenancy.json` file under the `configs` directory. If absent, the framework uses default settings.

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

---

## Step 3: Configure the Landlord Environment

The Landlord site serves as the primary installation that manages all tenants. To set up its environment:

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

These settings should match those of the main site (also referred to as the Landlord site) where the Tenancy plugin is installed. Adjust the values to reflect your specific Landlord database credentials

3. **Save Changes**  
   The `configs/.env` file is used for initial setup. Ensure it matches the Landlord site's database credentials.

---

## Step 4: Managing Tenants

### Creating a Tenant
Tenants can be created:
1. **Via the Landlord Admin Interface**: Use the Tenancy Plugin to add a new tenant with details like domain and UUID.
2. **Using `tenants.json` (Optional)**:  
   For file-based setups, define tenants in `configs/tenants.json`.

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
  }
}
```

### Setting Up a Tenant
1. Access the new tenant domain (e.g., `alpha.domain1.local`) in a browser.
2. If an error appears, retry. The system will auto-generate a `.env` file at `configs/{tenant_id}/.env`.
3. Edit the `configs/{tenant_id}/.env` file to include tenant-specific database credentials:
   ```php
   DB_NAME=       # Tenant database name
   DB_USER=       # Tenant database username
   DB_PASSWORD=   # Tenant database password
   DB_HOST=localhost  # Database host
   ```
> [!IMPORTANT]
> This should be a **new database** for the new tenant that we just created, **DO NOT** use the same database credentials as the main/Landlord tenant.

4. Revisit the tenant domain in the browser and complete the installation process as usual.

---

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

---

## Management Options

### Database-Driven Management (Default)
Tenant information is stored in the database for dynamic updates and scalability. Recommended for:
- Applications with frequent tenant changes.
- Large-scale deployments.

### File-Based Management (Optional)
Define tenants in `configs/tenants.json` for simpler setups. Suitable for:
- Small applications with fewer than 50 tenants.
- Development or testing environments.

---

## Next Steps

1. **Explore Additional Features**: Learn about caching, logging, and advanced security options in Raydium's documentation.
2. **Join the Community**: Engage with other developers for tips, support, and best practices.  

By following these steps, you'll have a functional multi-tenant setup ready for use.
