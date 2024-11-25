# Multi-Tenant Guide

Multi-tenancy in Raydium allows you to operate multiple sites from a single installation, making it ideal for managing a network of sites with centralized resources.

This guide provides a  overview of the architecture and operational flow of the Multi-Tenant Application framework. Designed to support multiple tenants (websites) with unique configurations and customizations, the framework operates on a shared infrastructure.

> **Important**: Raydium's Multi-Tenant applications are functional but still in early development. Proceed with caution, and thoroughly test your applications before deploying them to production.


### Step 1: Backup Your Site

Before proceeding, create a complete backup of your files and database to ensure you can restore your site in case of any issues.

Next, install and activate the [Tenancy Plugin](#). This plugin creates the necessary database tables and provides an admin interface for managing tenants on the "Landlord" site.

> **Note**: The plugin is optional in some setups, especially when tenant data is managed through configuration files instead of the database.

### Step 2: Enabling Multi-Tenancy

The framework relies on `composer.json` for initial setup and `configs/tenancy.json` for global tenant configurations. Tenant-specific settings can be stored in a database (default) or `configs/tenants.json` (optional).

### Initial Setup in `composer.json`

To enable multi-tenancy, add the following configuration to the `extras` section of your `composer.json` file:

```json
"extra": {
    "multitenant": {
        "is_active": true,
        "uuid": "81243057"
    }
}
```

- **`is_active`**: A boolean flag to enable or disable multi-tenancy.
- **`uuid`**: The unique identifier for the landlord tenant.


### Step 3: Configuring `tenancy.json`

All tenant configurations are stored in `configs/tenancy.json`. The framework automatically loads tenant configurations from `configs/tenancy.json` during runtime.

The `configs/tenancy.json` file is **required** and defines global settings for the multi-tenant framework. If a custom `tenancy.json` is present in the `configs/` directory, it overrides the default configuration provided by the framework.

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
> **Note:** The `require-config` mandates the presence of tenant-specific [configuration options](../reference/configuration) `app.php`. When enabled (`true`), it requires that each tenant must have their own configuration file located at `config/{tenant_id}/app.php`. Conversely, when disabled (`false`), we can use a global `app.php` file. The default setting is `false`.

The `require-config` toggles the enforcement of tenant-specific settings within the application. When enabled (`true`), it requires each tenant to have a dedicated `config/{tenant_id}/app.php` [configuration options](../reference/configuration) file, ensuring tailored settings per tenant. In the absence of a tenant-specific [configuration options](../reference/configuration) file, the application will signal an error, highlighting the necessity for individual configurations. By default, this setting is disabled (`false`), allowing for a shared `app.php` [configuration options](../reference/configuration) across tenants, simplifying setup for environments where distinct tenant configurations are not critical.

### Step 4: Managing Tenant Information

### Database-Driven Management (Default)

Tenant information, such as UUIDs, domains, and statuses, is managed by default through the **Tenancy Plugin**. Tenant data is stored in a database table, enabling dynamic updates and scalability.

This approach is recommended for:
- Large-scale applications.
- Scenarios requiring frequent tenant updates.

### File-Based Management with `tenants.json` (Optional)

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
- **Simplicity**: All configurations are centralized in JSON, reducing file clutter.
- **Flexibility**: Supports both landlord-level and tenant-specific customizations.
- **Scalability**: Easily add new tenants by appending to `tenancy.json`.
- **Portability**: JSON files are easy to move across environments.

> **Note**: Use `tenants.json` when database access is unavailable or unnecessary.


### Step 5: Setting Up Tenant-Specific Files

The framework supports per-tenant configurations for greater flexibility. Tenant-specific files are stored in `configs/{tenant_id}/`.

### Directory Structure

```
configs/
│
├── tenancy.json
├── tenants.json (optional)
├── {tenant_id}/
│   ├── .env
│   ├── app.php
│   ├── config.php
```

### File Loading Mechanism

**Tenant-Specific Files**:
   - The framework first attempts to load configurations from the tenant’s directory (`configs/{tenant_id}/`).

**Fallback**:
   - If tenant-specific and global files are unavailable, the framework uses its default settings.


### Step 6: Domain and Tenant Mapping

### Default Mapping (Database-Driven)

By default, tenant domains (e.g., `tenant1.example.com`) are mapped to UUIDs using the Tenancy Plugin and database queries.

### File-Based Mapping (Optional)

If using `tenants.json`, domains are mapped directly based on file contents. For example:

```json
{
	"gamma": {
        "id": 3,
        "uuid": "u14v56w7",
        "name": "Gamma Platform",
        "domain": "gamma.local",
        "user_id": 202,
        "created_at": "2023-02-10T09:00:00Z",
        "status": "inactive"
    },
	"delta": {
        "id": 4,
        "uuid": "e15f67g8",
        "name": "Delta Insights",
        "domain": "delta.domain1.local",
        "user_id": 303,
        "created_at": "2023-03-20T08:45:00Z",
        "status": "active"
    }
}
```


### Step 7: Landlord `.env` Configuration

To configure the landlord database, create or update the `.env` file in the root directory with the following settings:

```php
# Landlord Database Configuration
TENANT_DB_NAME=landlord_db
TENANT_DB_USER=landlord_user
TENANT_DB_PASSWORD=securepassword
TENANT_DB_HOST=localhost
TENANT_DB_PREFIX=wp_prefix_
```

> **Tip**: Always back up your `.env` file before making changes.


### Step 8: Isolated File Storage

Each tenant’s media files are stored in an isolated directory:

```
wp-content/{tenant_id}/uploads
```

- Tenant-specific configuration files are located as follows:
  - `.env`: `"path/configs/a345ea9515c/.env"`
  - `app.php`: `"path/configs/a345ea9515c/app.php"`
  - `config.php`: `"path/configs/a345ea9515c/config.php"`

  - **Environment File**: Located at `path/configs/{tenant_id}/.env`, it stores environment-specific variables.
  - **PHP Configuration**: Found at `path/configs/{tenant_id}/config.php`, this file contains PHP configuration file overrides.
  - **Framework Options**: Found at `path/configs/{tenant_id}/app.php`, this file contains an array of [configuration options](../reference/configuration) specific to the tenant.

This ensures strict separation of tenant data, preventing accidental cross-tenant access.

### Step 9: Plugin and Theme Management

### Shared Resources

Plugins and themes are shared across tenants to optimize resource usage. Shared paths are defined in `app.php` and `composer.json`.

### Resource Control

The `IS_MULTITENANT` constant allows dynamic control over plugin and theme availability.

### Advantages of `tenants.json` (Optional)

- **Quick Setup**: Simplifies development or small-scale applications.
- **Portability**: Easy to transfer across environments.

## Suitability for Multi-Tenant Architecture

While the framework is efficient and scalable, consider your application’s needs:

1. **Use Cases for Multi-Tenancy**:
   - Centralized management with shared infrastructure.
   - Applications requiring isolated tenant configurations.

2. **Scenarios Requiring Alternatives**:
   - Strong isolation needs (consider dedicated hosting for each tenant).
   - High-demand tenants requiring separate resources.

> The Multi-Tenant Application framework offers a robust and flexible solution for managing multiple tenants efficiently. With its database-driven architecture and optional file-based configurations, it adapts to a variety of use cases.
