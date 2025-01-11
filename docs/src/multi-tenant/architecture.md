# Multi-Tenancy in Raydium

In Raydium's multi-tenant architecture, each tenant operates as an independent entity within a shared framework, ensuring maximum flexibility and customization. A key aspect of this architecture is that each tenant has its own dedicated database and the ability to utilize its own [configuration options](../guide/customization/config-overview) settings and `.env` file. This setup provides several advantages, including data isolation, security, and tailored experiences for each tenant.

## Tenant-Specific Databases

### Isolation and Security

Each tenant having its own database means that the data for each tenant is completely isolated from others. This isolation enhances security by ensuring that no tenant can access the data of another, intentionally or accidentally.

### Customization and Scalability

Dedicated databases allow for customization at the data structure level, enabling tenants to have unique schemas that best fit their specific needs. It also aids in scaling, as each database can be scaled independently based on the tenant's requirements.

### Maintenance and Backup

With separate databases, maintenance operations (like backups, updates, or optimizations) can be performed on a per-tenant basis, reducing the risk of affecting other tenants and enabling more tailored maintenance schedules.

## Tenant-Specific Configurations

### Flexibility

Tenants can define their own `app.php` [configuration options](../guide/customization/config-overview) within their designated configuration directories (e.g., `config/{tenant_id}/app.php`). This flexibility allows for tenant-specific settings like themes, plugins, performance optimizations, and feature toggles.

> In multi-tenant mode customizing some [configuration options](../guide/customization/config-overview) may be limited.

### Independence

Tenants can operate independently from each other, making changes to their configurations without the risk of impacting other tenants. This independence is crucial for businesses that cater to diverse clients with varying requirements.

### Streamlined Management

While tenants have the freedom to customize their configurations, central policies and updates can still be enforced at the framework level, ensuring consistency where necessary while allowing for customization.

## Implementation Considerations

### Unique Tenant Identification

Each tenant is typically identified by a unique identifier (UUID), which is used to associate the correct configuration settings for each tenant.

### Configuration Precedence

The Raydium framework ensures that tenant-specific [configuration options](../guide/customization/config-overview) take precedence over global configuration options, allowing for granular control at the tenant level.

### Environment Variables

Tenants also utilize environment variables defined in their `.env` [environment files](../guide/customization/environments) for sensitive information, ensuring that configuration files remain secure and version-controllable.

> Multi-tenancy in Raydium offers a powerful paradigm for managing multiple sites with efficiency and security. By providing each tenant with its own database and the ability to use custom [configuration options](../guide/customization/config-overview), Raydium ensures that tenants can enjoy a tailored, secure, and isolated environment.
