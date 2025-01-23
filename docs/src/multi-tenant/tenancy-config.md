# Configuring Multi-Tenancy


> [!WARNING] ⚠ Some configurations in this guide are experimental and may not be fully supported in all environments. Use caution when enabling or modifying these settings and thoroughly test in a staging environment before deploying to production.

The `tenancy.json` file is the central configuration file for managing multi-tenancy in your application. This guide explains the purpose of each configuration and how to use it effectively.
Ensure you're in your application's root directory. Look for the `configs` directory. If it doesn't exist, you'll need to create it to store `tenancy.json` configuration file.


## **Key Sections of `tenancy.json`**

### 1. **General Settings**
```json
{
  "require-config": false,
  "web-root": "public"
}
```

- **`require-config`**:
  - Determines if tenant-specific configuration files are mandatory.
  - **`true`**: Requires each tenant to have its own configuration files ( `app.php`).
  - **`false`**: Falls back to default global configurations if tenant-specific files are missing.

- **`web-root`**:
  - Specifies the root directory for web assets. Commonly set to `"public"`.

---

### 2. **ID Generation**
```json
{
  "id": {
    "format": "random",
    "random_length": 6,
    "random_retries": 5,
    "prefix": "id",
    "suffix": "t",
    "delimiter": "-",
    "sequence_start": 1000,
    "id_length": 16,
    "hash_algorithm": "sha256",
    "collision_policy": "append_random_suffix",
    "constraints": {
      "min_length": 6,
      "max_length": 16,
      "allowed_characters": "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-",
      "restricted_characters": "!@#$%^&*()"
    },
    "strategy": "random",
    "audit_logs": true
  }
}
```

- **`format`**:
  - Defines the format for tenant IDs. Options include `random`, `sequential`, or `hash`.

- **`random_length`**:
  - Length of the random part of the tenant ID.

- **`prefix` & `suffix`**:
  - Optional strings added before or after the tenant ID for readability or grouping.

- **`delimiter`**:
  - Character (like: `-` or `_`) used to separate parts of the tenant ID (e.g., `"id-12345-t"`).

- **`hash_algorithm`**:
  - Algorithm used for hashing IDs (e.g., `sha256`, `md5`).
  - See https://www.php.net/manual/en/function.hash-algos.php

- **`collision_policy`**:
  - How to handle ID collisions:
    - `append_random_suffix`: Adds a random suffix to resolve collisions.
    - `error`: Throws an error on collision.

- **`audit_logs`**:
  - **`true`**: Enables logging of ID generation for debugging or audit purposes.

---

### 3. **Tenant Management**
```json
{
  "tenant-management": {
    "isolation": "database",
    "creation-strategy": "auto",
    "fallback-tenant": null
  }
}
```

- **`isolation`** (experimental):
  - Specifies the isolation level for tenants:
    - `database`: Each tenant has its own database.
    - `schema` : Tenants share a database but have separate schemas.

- **`creation-strategy`** (experimental):
  - **`auto`**: Automatically creates resources for new tenants.
  - **`manual`**: Requires manual intervention for tenant resource creation.

- **`fallback-tenant`**:
  - Specifies a default tenant to handle requests that do not match any tenant configuration.

---

### 4. **Cache Settings**
```json
{
  "cache": {
    "enabled": true,
    "adapter": "redis",
    "prefix": "tenantcache"
  }
}
```

- **`enabled`**:
  - **`true`**: Enables caching for tenant operations.
  - **`false`**: Disables caching.

- **`adapter`**:
  - Specifies the cache backend (e.g., `redis`, `memcached`).

- **`prefix`**:
  - A string prefix added to cache keys to avoid collisions.

---

### 5. **Logging**
```json
{
  "logging": {
    "level": "info",
    "dir": "storage/logs/multitenant",
    "per-tenant-logs": true
  }
}
```

- **`level`**:
  - Logging level (e.g., `info`, `debug`, `error`).

- **`dir`**:
  - Directory where logs are stored.

- **`per-tenant-logs`**:
  - **`true`**: Stores logs separately for each tenant.
  - **`false`**: Logs all tenant activity in a single log file.

---

### 6. **Feature Toggles**
```json
{
  "features": {
    "tenant-specific-config": true,
    "cross-tenant-data-access": false,
    "tenant-domains": true,
    "tenant-plugins": []
  }
}
```

- **`tenant-specific-config`**:
  - Enables or disables tenant-specific configuration overrides.

- **`cross-tenant-data-access`**:
  - **`true`**: Allows tenants to access data across tenants (not recommended).
  - **`false`**: Restricts data access to the tenant’s own resources.

- **`tenant-domains`**:
  - **`true`**: Enables domain-based tenant resolution.

- **`tenant-plugins`**:
  - Lists plugins available to tenants.

---

### 7. **Security**
```json
{
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

- **Encryption**:
  - **`enabled`**: Toggles encryption for sensitive tenant data.
  - **`type`**: Specifies the encryption algorithm (e.g., `AES-256`).

- **Rate Limiting**:
  - **`enabled`**: Limits requests per tenant to prevent abuse.
  - **`requests_per_minute`**: Number of allowed requests per minute per tenant.

## **Best Practices**

1. **Experimental Settings**:
   - Be cautious when using experimental settings such as advanced `id` strategies. Test thoroughly before enabling in production.

2. **Secure the Configuration**:
   - Restrict access to the `tenancy.json` file to prevent unauthorized modifications.

3. **Start with Defaults**:
   - Use default settings and gradually enable advanced features as needed.

4. **Monitor Logs**:
   - Enable `logs` for better visibility into tenant-specific issues.

5. **Test in Staging**:
   - Validate all changes in a staging environment to ensure stability and compatibility.

---

- **Security and Isolation**: Ensure that tenant-specific data and configurations are securely isolated to prevent data leakage between tenants.
- **Documentation**: Maintain thorough documentation for each tenant configuration to facilitate management and troubleshooting.
- **Testing**: Rigorously test multi-tenant functionalities in a controlled environment before deploying to production to ensure stability and performance.
- **Backup and Recovery**: Implement robust backup and recovery strategies to protect tenant data and configurations against loss or corruption.

> The `tenancy.json` file is a flexible and centralized way to configure a multi-tenant application. While some configurations are experimental, they provide powerful options for managing tenants.
