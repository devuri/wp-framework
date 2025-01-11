# Multi-Tenant Overview

Multi-tenancy in **Raydium** is an architecture that enables managing multiple independent websites or tenants within a single installation. It centralizes resources while maintaining tenant-specific customizations and configurations, making it ideal for SaaS applications, multi-tenant networks, or scenarios requiring efficient and scalable management of multiple entities.

---

## What is Multi-Tenancy?

Multi-tenancy allows a single application instance to support multiple tenants (independent websites or applications). Each tenant operates as if they have their own isolated environment, while sharing common resources like plugins, themes, and the core application. This setup minimizes redundancy and streamlines operations.

Examples of where this architecture is useful include:
- Regional websites for a single organization.
- SaaS platforms where each client has their own custom instance.
- A network of blogs or ecommerce stores with unique settings per site.

---

## Key Benefits of Multi-Tenancy

- **Efficiency**: Reduces resource duplication by sharing common infrastructure.
- **Flexibility**: Provides tenant-specific configurations while maintaining a unified base.
- **Scalability**: Easily add or manage tenants without significant overhead.
- **Maintainability**: Centralized management of updates and patches, reducing maintenance effort.

---

## Multi-Tenant Framework Architecture

### Shared Infrastructure
- Tenants operate on a single installation of Raydium, sharing plugins, themes, and core files.
- This approach simplifies resource management and ensures uniformity across tenants.

### Tenant Isolation
Tenants maintain independence through:
- **Database Isolation**: Tenant data can be stored in separate databases or isolated logically within a single database.
- **Configuration Isolation**: Unique configurations can be assigned to each tenant for customization.
- **File Storage Isolation**: Media files and other assets are stored in tenant-specific directories (e.g., `/wp-content/{tenant_id}/uploads`).

### Centralized Configuration
Global settings for all tenants are defined in `configs/tenancy.json`, while tenant-specific overrides are stored in dedicated directories. This structure ensures centralized control with the flexibility to customize for each tenant.

---

## Key Components of the Multi-Tenant Framework

### Global Configuration (`configs/tenancy.json`)
This file defines how the multi-tenant framework operates, including settings for database isolation, caching, logging, and security.

#### Example: `configs/tenancy.json`
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
  "cache": {
    "enabled": true,
    "adapter": "redis",
    "prefix": "tenantcache"
  },
  "logging": {
    "level": "info",
    "per-tenant-logs": true
  }
}
```

Key settings include:
- **`database.isolation`**: Determines whether tenants share a single database or use separate ones.
- **`cache.adapter`**: Specifies caching mechanisms (e.g., Redis for performance improvements).
- **`tenant-management.creation-strategy`**: Controls how tenants are created (e.g., automatically when a new domain is added).
- **`logging.per-tenant-logs`**: Enables tenant-specific logging for easier troubleshooting.

---

### Tenant-Specific Configuration
Each tenant can have a dedicated directory (`configs/{tenant_id}/`) containing custom configuration files. These configurations override the global settings where applicable.

#### Example Directory Structure
```
configs/
├── tenancy.json
├── tenants.json (optional)
├── alpha/
│   ├── .env
│   ├── app.php
│   ├── constants.php
```

File descriptions:
- **`.env`**: Stores environment-specific variables like database credentials and secrets.
- **`app.php`**: Contains application-specific settings for the tenant.
- **`constants.php`**: Holds other tenant-specific constants required by the application.

---

### Tenant Management

#### Database-Driven Management (Default)
Tenant data, such as UUIDs, domains, and statuses, is stored in a database table. This dynamic and scalable approach is recommended for large applications or scenarios with frequent updates.

#### File-Based Management (Optional)
For smaller applications or development environments, tenant data can be stored in a JSON file (`configs/tenants.json`).

##### Example: `configs/tenants.json`
```json
{
  "alpha": {
    "id": 1,
    "uuid": "h456i789j012",
    "name": "Alpha Version Limited",
    "domain": "alpha.domain1.local",
    "status": "active"
  }
}
```

---

### Domain Mapping
Tenants are associated with unique domains or subdomains (e.g., `tenant1.example.com`). This mapping can be:
- **Database-Driven**: Handled dynamically via backend queries.
- **File-Based**: Defined in `tenants.json` for simpler setups.

---

### Media File Storage
Tenant media files are stored in isolated directories to prevent conflicts and ensure separation.

Example structure:
```
/wp-content/{tenant_id}/uploads
```

This setup ensures each tenant has its own space for uploads and assets.

---

### Plugins and Themes
Plugins and themes are shared across tenants to optimize resource usage. Tenant-specific customizations can be achieved through:
- **Dynamic Configuration**: Adjust behavior using tenant-specific environment variables.
- **Overrides**: Apply tenant-specific code or settings as needed.

---

## Setting Up Multi-Tenancy

### 1. Enable Multi-Tenancy
Update the `composer.json` file to activate multi-tenancy. Add the following to the `extra` section:
```json
"extra": {
    "multitenant": {
        "is_active": true,
        "uuid": "81243057"
    }
}
```

### 2. Configure Global Settings
Define your global settings in `configs/tenancy.json`. Include database configurations, caching preferences, and tenant management strategies.

### 3. Add Tenant-Specific Files
For each tenant, create a directory under `configs/{tenant_id}/` and include:
- **`.env`** for environment variables.
- **`app.php`** for tenant-specific application settings.

### 4. Map Domains to Tenants
Ensure each tenant’s domain is properly mapped to their configuration. Use database-driven mapping or define mappings in `tenants.json`.

---

## Best Practices

1. **Backup Regularly**: Always create backups of files and databases before making significant changes.
2. **Test Thoroughly**: Test each tenant to ensure proper isolation and functionality.
3. **Choose Meaningful Tenant IDs**: Use unique identifiers (e.g., UUIDs) for each tenant to avoid conflicts.
4. **Monitor Performance**: Shared infrastructure can lead to resource bottlenecks. Use caching and database optimization to maintain performance.
5. **Plan for Growth**: For large-scale deployments, prioritize database-driven management and scalable infrastructure.

---

## When to Use Multi-Tenancy

### Suitable Use Cases
- Centralized management of multiple websites or applications.
- SaaS platforms requiring unique configurations for each client.
- Applications with shared resources and moderate tenant overlap.

### Scenarios Requiring Alternatives
- Strict data isolation requirements (consider single-tenant setups).
- High-demand tenants needing dedicated resources (e.g., separate installations).

---

The multi-tenant framework in Raydium provides a powerful and flexible solution for managing multiple tenants efficiently. By combining shared infrastructure with isolated tenant configurations, it supports diverse use cases while optimizing resource usage. Proper planning and adherence to best practices will ensure a smooth and scalable implementation.
