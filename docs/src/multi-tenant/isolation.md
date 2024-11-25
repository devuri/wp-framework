# Dedicated Isolation Architecture

Raydium's approach to multi-tenancy in WordPress is sophisticated, particularly in how it handles databases and media for each tenant. Unlike traditional setups where a single database might contain multiple sites (as seen in WordPress Multisite), Raydium assigns each tenant its own dedicated database. This strategy enhances security, scalability, and data integrity across the multi-tenant landscape.

## Tenant Database Initialization

Upon determining that it's operating in a multi-tenant mode, Raydium engages a sequence of steps to ensure each tenant interacts exclusively with its designated database:

1. **Tenant Identification**: Raydium identifies the incoming request's associated tenant, typically through domain mapping or a path identifier.

2. **Environment Configuration**: For each tenant, there's a corresponding `.env` [environment file](../customization/environment-file) within its configuration directory (e.g., `configs/<tenant_id>/.env`). This file contains environment-specific variables, including unique database credentials for the tenant.

3. **Dynamic Database Connection**: Before handing off to WordPress, Raydium reads the tenant's `.env` file and establishes a connection to the tenant's specific database. This ensures that all queries, operations, and data storage are performed within the tenant's database, completely isolated from other tenants.

## Advantages of Separate Databases

This architecture offers several key benefits:

- **Security**: Data for each tenant is stored in a separate database, minimizing the risk of data leakage or unauthorized access between tenants.
- **Customization**: Tenants can have entirely customized setups, including different plugins, themes, and other configurations, without any overlap.
- **Scalability**: As each tenant operates independently, system resources and databases can be scaled individually based on each tenant's needs.
- **Maintenance and Backups**: Administrative tasks such as backups, migrations, and updates can be performed per tenant, reducing complexity and downtime.

## Handling Media and Uploads

In addition to database isolation, Raydium ensures that media and uploads are tenant-specific. Each tenant's media files are stored in a segregated directory within the content directory, keyed by the tenant's unique identifier. This approach maintains organizational clarity and reduces any cross-tenant media access, reducing complexity and easy media transfers.

## Framework and WordPress Handoff

Once Raydium has initialized the environment and connected to the appropriate tenant database, it hands over control to WordPress. At this stage, WordPress operates as it typically would but within the context of the tenant's isolated environment. All standard functionality, including theme rendering, plugin operations, and content management, operates on a per-tenant basis, using the tenant's database and file system paths.

> Raydium's multi-tenant isolation architecture, particularly its approach to database management, offers a robust and flexible framework for operating multiple WordPress applications within a single installation. By ensuring each tenant has its own database and environment configurations, Raydium provides a powerful solution for developers and organizations looking to efficiently manage a portfolio of WordPress sites with maximum security, customization, and scalability.
