# Configurations

This guide provides a comprehensive overview of how to modify and manage the framework’s configuration settings. All configuration values reside in the `configs/app.php` file. The framework supplies sensible defaults for each section—spanning error handling, directory paths, security, and beyond—so you only need to override the specific options you wish to customize. Any setting you do **not** include in your own `app.php` will continue using the framework’s defaults.


## Using Environment Variables

Most configuration parameters accept environment variables defined in your `.env` file. This approach simplifies customizing settings across different environments—local development, staging, and production, for example. If you’re not using a `.env` file, you can replace calls like `env('KEY', 'default')` with fixed values in your configuration.


## Accessing Configuration Options

**Using the `configs()` Helper**  
Within your plugins or theme, you can retrieve specific configuration values by calling:
```php
configs()->app()->config['app']->get('key.subkey');
```
This ensures your code references the correct settings from `app.php` (or the defaults if an entry is not overridden).

## Override Only What You Need

When the framework loads, it merges any settings in your `configs/app.php` file with its built-in defaults:

- **Omitted settings**: The framework’s default values remain in effect.  
- **Included settings**: Your custom entries override the defaults.


## Example of a Partial Override

```php
return [
    'error_handler' => [
        'class' => Whoops\Handler\PrettyPageHandler::class,
        'quit'  => true,
        'logs'  => true,
    ],
    'directory' => [
        'content_dir'   => 'content',
        'plugin_dir'    => 'content/plugins',
        'mu_plugin_dir' => 'content/mu-plugins',
    ],
];
```

In this example, only the error handler configuration and certain directory paths are updated. All other settings remain unchanged, inheriting the defaults.


## Configuration Options Overview

Below is a closer look at some of the primary configuration sections. Each section outlines a table of keys, their default values, and a description of their purpose. You only need to copy and override the keys you truly want to modify.

> [!IMPORTANT]
> Not all application-level settings are directly used by the framework. Many exist to provide a centralized location for managing configurations, making them easily accessible for third-party plugins or internal integrations as needed. This flexibility allows you to define and organize settings in one place, even if they are specific to your custom requirements.

> The application-level settings are an ideal place to store custom configurations, as the framework automatically loads the configuration array at runtime. You can conveniently retrieve these values using the global `configs()->app()` function, example: `configs()->app()->config['app']->get('key.subkey');`


## 1. Error Handler

This section determines how the framework handles errors. By default, it uses [Whoops](https://filp.github.io/whoops/).

| Key   | Default                                     | Description                                                                                                     |
|-------|---------------------------------------------|-----------------------------------------------------------------------------------------------------------------|
| class | `WPframework\Error\ErrorHandler::class`     | A fully qualified class name that implements or extends the framework’s error-handling interfaces.              |
| quit  | `true`                                      | Specifies whether the handler should stop script execution (`allowQuit(true)`).                                 |
| logs  | `true`                                      | Enables logging of errors, useful for debugging.                                                                |

> **class**: You can switch to any handler that suits your needs, for example:
> - `WPframework\Error\ErrorHandler::class` (default)  
> - `WPframework\Error\TextHandler::class`  
> - `Whoops\Handler\JsonResponseHandler::class`  
> - `Whoops\Handler\PlainTextHandler::class`  
> - `Whoops\Handler\PrettyPageHandler::class`  

> **Warning**: Handlers like `PrettyPageHandler` display detailed debugging information and should typically be reserved for non-production environments. If the framework detects it in production, it may automatically override it to protect sensitive data.

**Example:**
```php
return [
    'error_handler' => [
        'class' => Whoops\Handler\PrettyPageHandler::class,
        'quit'  => true,
        'logs'  => true,
    ],
];
```


## 2. Adminer Database Interface

Allows you to manage your database through the [Adminer](https://www.adminer.org/) UI.

| Key       | Default                       | Description                                                                                     |
|-----------|-------------------------------|-------------------------------------------------------------------------------------------------|
| enabled   | `true`                        | Determines whether Adminer is accessible.                                                       |
| uri       | `dbadmin`                    | URL slug to access Adminer, e.g., `example.com/wp/wp-admin/dbadmin`.                           |
| validate  | `true`                        | Restricts access to logged-in WordPress users only.                                            |
| autologin | `ADMINER_ALLOW_AUTOLOGIN`    | Skips Adminer’s login screen if set to `true`; use with caution in production.                  |
| secret    | `['key' => null, 'type' => 'jwt']` | Allows generating signed URLs for temporary or debug access.                             |

**Example:**
```php
return [
    'dbadmin' => [
        'enabled'   => true,
        'uri'       => 'my-dbadmin',
        'validate'  => true,
        'autologin' => true,
        'secret'    => [
            'key'  => 'mysecretkey',
            'type' => 'jwt',
        ],
    ],
];
```

## 3. Health Status

Adds a basic health-check endpoint (default `/up`), commonly used by uptime monitors and automated scripts.

| Key     | Default | Description                                                         |
|---------|---------|---------------------------------------------------------------------|
| enabled | `true`  | Toggles the health-check middleware.                                |
| secret  | `null`  | If specified, the endpoint requires a matching secret key.          |
| route   | `up`    | The path to access the health check, e.g., `example.com/up`.        |

**Example:**
```php
return [
    'health_status' => [
        'enabled' => true,
        'secret'  => 'my-secret-value',
        'route'   => 'healthcheck',
    ],
];
```


## 4. Directory Structure

Specifies key directories for WordPress, plugins, and additional framework files.

| Key           | Default                  | Description                                                                                       |
|---------------|--------------------------|---------------------------------------------------------------------------------------------------|
| wp_dir_path   | `wp`                     | Location of core WordPress files.                                                                 |
| web_root_dir  | `public`                 | The public-facing web root, typically holding `index.php`.                                         |
| content_dir   | `wp-content`             | Root WordPress content directory.                                                                  |
| plugin_dir    | `wp-content/plugins`     | Standard WordPress plugins directory.                                                             |
| mu_plugin_dir | `wp-content/mu-plugins`  | Must-Use plugins directory.                                                                        |
| sqlite_dir    | `sqlitedb`               | Directory storing SQLite database files (if using the SQLite drop-in).                             |
| sqlite_file   | `.sqlite-wpdatabase`     | SQLite database filename.                                                                          |
| theme_dir     | `templates`              | Directory for your custom themes.                                                                 |
| asset_dir     | `assets`                 | Location for global assets like CSS, JS, images, etc.                                             |
| publickey_dir | `pubkeys`                | Directory for public keys (used for encryption or signature verification, if applicable).          |

**Example:**
```php
return [
    'directory' => [
        'web_root_dir' => 'public_html',
        'content_dir'  => 'my-content',
        'plugin_dir'   => 'my-content/plugins',
    ],
];
```


## 5. Default Theme

Specifies which WordPress theme to activate if no other theme is configured.

| Key          | Default              | Description                                                             |
|--------------|----------------------|-------------------------------------------------------------------------|
| default_theme | `twentytwentythree` | The fallback theme WordPress will use if none is set in the admin area. |

**Example:**
```php
return [
    'default_theme' => 'my-custom-theme',
];
```


## 6. Security Settings

Holds several options for enhancing site security.

| Key                 | Default                                                             | Description                                                         |
|---------------------|---------------------------------------------------------------------|---------------------------------------------------------------------|
| restrict_wpadmin    | `['enabled' => false, 'secure' => false, 'allowed' => ['admin-ajax.php']]` | Restricts access to `wp-admin`. You can allow specific paths.       |
| encryption_key      | `null`                                                              | Full path to a file holding an encryption key (if your setup needs one). |
| brute-force         | `true`                                                              | Enables brute-force login protection.                               |
| two-factor          | `true`                                                              | Enables two-factor authentication.                                  |
| no-pwned-passwords  | `true`                                                              | Prevents users from using passwords found in data breaches.         |
| admin-ips           | `[]`                                                                | Array of IP addresses that can access administrative areas. An empty array means unrestricted. |

**Example:**
```php
return [
    'security' => [
        'restrict_wpadmin' => [
            'enabled' => true,
            'secure'  => false,
            'allowed' => ['admin-ajax.php'],
        ],
        'two-factor' => true,
    ],
];
```


## 7. Email (SMTP) Configuration

Allows sending emails through a variety of email service providers.

| Key        | Default                           | Description                                                             |
|------------|-----------------------------------|-------------------------------------------------------------------------|
| brevo      | `['apikey' => null]`             | Brevo (Sendinblue) API key.                                            |
| postmark   | `['token' => null]`              | Postmark token.                                                         |
| sendgrid   | `['apikey' => null]`             | SendGrid API key.                                                       |
| mailerlite | `['apikey' => null]`             | MailerLite API key.                                                     |
| mailgun    | `[... various keys ...]`          | Mailgun domain, secret, endpoint, scheme, etc.                          |
| ses        | `[... various keys ...]`          | AWS SES credentials (access key, secret, region).                       |

**Example:**
```php
return [
    'mailer' => [
        'sendgrid' => [
            'apikey' => 'your-sendgrid-api-key',
        ],
    ],
];
```


## 8. Redis Cache Configuration

Enables or disables Redis-based caching if you have the necessary plugin installed.

> For more details, see [Redis Cache Plugin Installation](https://github.com/rhubarbgroup/redis-cache/blob/develop/INSTALL.md).

| Key             | Default                                      | Description                                                       |
|-----------------|----------------------------------------------|-------------------------------------------------------------------|
| disabled        | `false`                                      | Disable Redis caching entirely if set to `true`.                  |
| host            | `127.0.0.1`                                  | Hostname or IP address of the Redis server.                       |
| port            | `6379`                                       | TCP port for Redis.                                               |
| password        | `''`                                         | Redis server password, if required.                               |
| adminbar        | `false`                                      | If `true`, excludes the admin bar from caching.                   |
| disable-metrics | `false`                                      | If `true`, disables Redis metrics.                                |
| disable-banners | `false`                                      | If `true`, hides Redis notices in the WordPress admin area.       |
| prefix          | `md5(env('HOME_URL')) . 'redis-cache'`       | Custom prefix for Redis cache keys.                               |
| database        | `0`                                          | Redis database index (0–15).                                      |
| timeout         | `1`                                          | Connection timeout in seconds.                                    |
| read-timeout    | `1`                                          | Read timeout in seconds.                                          |

**Example:**
```php
return [
    'redis' => [
        'disabled' => false,
        'host'     => '192.168.1.100',
        'port'     => 6380,
        'database' => 1,
    ],
];
```


## 9. Public Key

Refers to public key(s) used for encryption or data validation, if applicable.

| Key     | Default             | Description                                                           |
|---------|---------------------|-----------------------------------------------------------------------|
| app-key | `null`             | A UUID or filename referencing the public key stored in WordPress.    |

**Example:**
```php
return [
    'publickey' => [
        'app-key' => 'my-public-key.pem',
    ],
];
```


## 10. Sudo Admin & Group

Specifies a primary super-administrator and, optionally, a group of additional privileged administrators.

| Key              | Default | Description                                                     |
|------------------|---------|-----------------------------------------------------------------|
| sudo_admin       | `1`     | User ID of the main “super admin.”                              |
| sudo_admin_group | `null`  | An array of user IDs to grant similarly elevated privileges.     |

**Example:**
```php
return [
    'sudo_admin'       => 1,
    'sudo_admin_group' => [2, 3, 4],
];
```

---

## 11. S3 Uploads

Integrates [S3 Uploads](https://github.com/humanmade/S3-Uploads) for storing uploads and media files in an S3-compatible bucket.

| Key        | Default                | Description                                                              |
|------------|------------------------|--------------------------------------------------------------------------|
| bucket     | `site-uploads`        | Name of the S3 bucket.                                                  |
| key        | `''`                  | AWS Access Key ID.                                                      |
| secret     | `''`                  | AWS Secret Access Key.                                                  |
| region     | `us-east-1`           | AWS region.                                                             |
| bucket-url | `https://example.com` | Base URL for the S3 bucket.                                             |
| object-acl | `public`              | Access control for uploaded objects.                                    |
| expires    | `2 days`              | HTTP caching expiration for uploaded files.                              |
| http-cache | `300`                 | `Cache-Control` header value for served files.                           |

**Example:**
```php
return [
    's3uploads' => [
        'bucket'     => 'my-s3-bucket',
        'region'     => 'us-west-2',
        'bucket-url' => 'https://bucket.example.com',
    ],
];
```

## 12. Headless Mode

Headless mode optimizes WordPress for use as a backend API by disabling unnecessary features and customizing API-related behaviors.

| **Key**              | **Default**              | **Description**                                                                                   |
|----------------------|--------------------------|---------------------------------------------------------------------------------------------------|
| `enabled`            | `false`                 | Enables or disables the headless mode entirely.                                                  |
| `rest_api.enabled`   | `true`                  | Activates or deactivates the REST API.                                                           |
| `rest_api.cache`     | `false`                 | Enables caching for REST API responses.                                                          |
| `graphql.enabled`    | `false`                 | Activates or deactivates the GraphQL API, if available.                                          |
| `themes`             | `false`                 | Disables theme loading for improved performance in headless environments.                        |
| `plugins.load`       | `[]`                    | Specifies a list of plugins to load. Leave empty to skip loading any plugins.                    |
| `debug`              | `false`                 | Activates debug mode for API-related logs, useful for development and troubleshooting.            |
| `error_handling`     | `'log'`                 | Determines how errors are handled: `'log'`, `'throw'`, or `'silent'`.                            |
| `security.cors`      | `true`                  | Enables or disables Cross-Origin Resource Sharing (CORS) headers.                                |
| `security.allowed_origins` | `['*']`           | Specifies allowed origins for cross-domain requests. Use `['*']` to allow requests from any origin. |

**Example:**
```php
return [
    'headless' => [
        'enabled' => true,
        'rest_api' => [
            'enabled' => true,
            'cache' => true,
        ],
        'graphql' => [
            'enabled' => true,
        ],
        'themes' => false,
        'plugins' => [
            'load' => ['plugin-name'],
        ],
        'debug' => true,
        'error_handling' => 'log',
        'security' => [
            'cors' => true,
            'allowed_origins' => ['https://example.com'],
        ],
    ],
];
```


## 13. SHORTINIT Mode

`SHORTINIT` mode initializes WordPress with minimal features, bypassing unnecessary components for performance-critical tasks.

| **Key**              | **Default**              | **Description**                                                                                   |
|----------------------|--------------------------|---------------------------------------------------------------------------------------------------|
| `enabled`            | `false`                 | Enables the `SHORTINIT` mode.                                                                    |
| `cache`              | `true`                  | Enables basic caching for lightweight initialization.                                            |
| `debug`              | `false`                 | Activates debug mode for additional error reporting.                                             |
| `components.database`| `true`                  | Retains the `$wpdb` object for database operations.                                              |
| `components.user`    | `false`                 | Enables user-related functionalities, such as authentication.                                    |
| `error_handling`     | `'log'`                 | Determines how errors are handled: `'log'`, `'throw'`, or `'silent'`.                            |
| `api.enabled`        | `false`                 | Enables limited REST API functionality in `SHORTINIT` mode.                                      |
| `api.routes`         | `[]`                    | Specifies allowed REST API routes, if any.                                                       |

**Example:**
```php
return [
    'shortinit' => [
        'enabled' => true,
        'cache' => true,
        'debug' => true,
        'components' => [
            'database' => true,
            'user' => false,
        ],
        'error_handling' => 'throw',
        'api' => [
            'enabled' => true,
            'routes' => ['wp/v2/posts'],
        ],
    ],
];
```

#### Using Environment Variables

Configuration parameters can also accept environment variables defined in your `.env` file.

**Example:**
In `.env`:
```env
HEADLESS_ENABLED=true
REST_API_ENABLED=true
```

In `app.php`:
```php
return [
    'headless' => [
        'enabled' => env('HEADLESS_ENABLED', false),
        'rest_api' => [
            'enabled' => env('REST_API_ENABLED', true),
        ],
    ],
];
```


## Modifying Configuration Options

1. **Open `configs/app.php`** in your project directory.  
2. **Locate or create the corresponding array key** (e.g., `'error_handler'`, `'directory'`) for the specific settings you want to adjust.  
3. **Apply your custom values** as needed.  
4. **Save and test** your configuration in a local or staging environment to ensure everything works smoothly before releasing to production.

### Example

Below is a brief configuration file that adjusts directory paths:

```php
<?php

return [
    'directory' => [
        'web_root_dir' => 'public',
        'content_dir'  => 'wp-content',
        'plugin_dir'   => 'wp-content/plugins',
        'theme_dir'    => 'templates',
    ],
];
```

Any settings not explicitly listed here will fall back to the framework’s default values.


## Notes

- **Thorough Testing**: Always confirm your updated configurations perform as intended across different environments before deploying to production.  
- **Security Considerations**: Pay close attention to sensitive keys like `restrict_wpadmin`, `two-factor`, and `encryption_key`.  
- **Composer & Updates**: If you manage WordPress core or plugins through Composer, you might disable WordPress’s built-in updates by setting `'disable_updates' => true`.  
- **Environment Variables**: Make sure each environment variable is properly defined in your `.env` file or in your hosting environment if you are using them.
