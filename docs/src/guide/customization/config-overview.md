# Configurations

This guide explains how to modify the configuration options of the framework. These options live in the `configs/app.php` file. the framework supplies sensible default settings for all sections—error handling, directories, security, etc.—so you only need to override what you want to change. Anything you omit in your custom `app.php` will continue using the framework’s defaults.

## Using Environment Variables
Most configuration values can be set using environment variables in your `.env` file. This approach makes it easy to customize for multiple environments (e.g., local, staging, production). If you aren’t using `.env`, simply replace calls like `env('KEY', 'default')` with static values.

## Accessing Configuration Options
**Using the `configs()` Helper**  
   Within your theme or plugins, call `configs()->config['app']->get('key.subkey')` to retrieve specific settings.

## Merging Logic: Override Only What You Need
The framework merges your `configs/app.php` on top of its internal defaults at runtime:

- If you **don’t** include a setting, the default persists.  
- If you **do** include it, your value overrides the default.

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
Here, only the error handler and a few directory paths are changed; everything else uses the framework’s defaults.

## Configuration Options Overview

Below is a closer look at key sections you might want to modify. Each section includes a table with the **Key** (array key), **Default**, and a **Description**, plus a brief code example. Remember, you only need to copy over (and override) the keys you actually want to change.

## 1. Error Handler
Controls error-handling behavior. By default, the framework uses [Whoops](https://filp.github.io/whoops/).

| Key | Default                                    | Description                                                                                                 |
|-------|--------------------------------------------|-------------------------------------------------------------------------------------------------------------|
| class | `WPframework\Error\ErrorHandler::class`    | Fully qualified error handler class (must extend `AbstractError` or implement `HandlerInterface`).           |
| quit  | `true`                                     | Determines if the handler should terminate script execution (`allowQuit(true)`).                             |
| logs  | `true`                                     | Enables logging of errors for easier debugging.                                                              |

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
Enables [Adminer](https://www.adminer.org/) for managing the database.

| Key     | Default                      | Description                                                                                                   |
|-----------|------------------------------|---------------------------------------------------------------------------------------------------------------|
| enabled   | `true`                       | Toggles Adminer access.                                                                                       |
| uri       | `dbadmin`                   | Slug for accessing Adminer (e.g., `example.com/wp/wp-admin/dbadmin`).                                         |
| validate  | `true`                       | Requires WordPress user authentication for access.                                                            |
| autologin | `ADMINER_ALLOW_AUTOLOGIN`   | Bypasses the Adminer login screen if `true` (be cautious in production).                                      |
| secret    | `['key' => null, 'type' => 'jwt']` | Allows generating signed URLs for temporary or debug access.                                       |

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
Adds a simple route for automated health checks (default `/up`).

| Key   | Default | Description                                                                   |
|---------|---------|-------------------------------------------------------------------------------|
| enabled | `true`  | Activates the health-check middleware.                                         |
| secret  | `null`  | Optional secret for restricting access.                                        |
| route   | `up`    | Health-check endpoint path (e.g., `example.com/up`).                           |

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
Defines paths for WordPress and other framework directories.

| Key          | Default                 | Description                                                                                                     |
|--------------- |-------------------------|-----------------------------------------------------------------------------------------------------------------|
| wp_dir_path    | `wp`                   | Where WordPress core files live.                                                                                |
| web_root_dir   | `public`               | The public web root directory (hosting `index.php`).                                                            |
| content_dir    | `wp-content`           | Main content directory for WordPress.                                                                           |
| plugin_dir     | `wp-content/plugins`   | Location of standard plugins.                                                                                   |
| mu_plugin_dir  | `wp-content/mu-plugins`| Must-Use plugins directory.                                                                                     |
| sqlite_dir     | `sqlitedb`             | Directory for SQLite database files (if you’re using SQLite).                                                   |
| sqlite_file    | `.sqlite-wpdatabase`   | SQLite database filename (if using the WordPress SQLite drop-in).                                               |
| theme_dir      | `templates`            | Directory for custom themes.                                                                                    |
| asset_dir      | `assets`               | Global assets directory for images, CSS, JavaScript, etc.                                                       |
| publickey_dir  | `pubkeys`              | Where public key files are stored if you’re using encryption or verification.                                   |

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
Specifies a fallback theme when no other theme is set.

| Key         | Default              | Description                                                                |
|---------------|----------------------|----------------------------------------------------------------------------|
| default_theme | `twentytwentythree` | The theme WordPress uses if none is set via admin or code.                 |

**Example:**
```php
return [
    'default_theme' => 'my-custom-theme',
];
```

## 6. Security Settings
Contains multiple security-related options.

| Key                | Default                                                   | Description                                                                             |
|----------------------|-----------------------------------------------------------|-----------------------------------------------------------------------------------------|
| restrict_wpadmin     | `['enabled' => false, 'secure' => false, 'allowed' => ['admin-ajax.php']]` | Restricts `wp-admin` access (or can block all except allowed paths).                     |
| encryption_key       | `null`                                                    | Full path to an encryption key file (if applicable).                                    |
| brute-force          | `true`                                                    | Enables brute-force login protection.                                                   |
| two-factor           | `true`                                                    | Enables two-factor authentication.                                                      |
| no-pwned-passwords   | `true`                                                    | Checks passwords against known data breaches.                                           |
| admin-ips            | `[]`                                                      | Array of IPs allowed for admin tasks (empty means no IP restriction).                   |

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
Allows sending emails through different providers.

| Key      | Default                        | Description                                              |
|------------|--------------------------------|----------------------------------------------------------|
| brevo      | `['apikey' => null]`           | Brevo (Sendinblue) API key.                              |
| postmark   | `['token' => null]`            | Postmark token.                                          |
| sendgrid   | `['apikey' => null]`           | SendGrid API key.                                        |
| mailerlite | `['apikey' => null]`           | MailerLite API key.                                      |
| mailgun    | `[... various keys ...]`        | Mailgun domain, secret, endpoint, scheme, etc.           |
| ses        | `[... various keys ...]`        | AWS SES credentials (access key, secret, region).        |

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
Enables or disables Redis caching ( if using the redis plugin).

> https://github.com/rhubarbgroup/redis-cache/blob/develop/INSTALL.md

| Key           | Default                                    | Description                                                                  |
|-----------------|--------------------------------------------|------------------------------------------------------------------------------|
| disabled        | `false`                                    | Disables Redis caching if `true`.                                            |
| host            | `127.0.0.1`                                | Redis server hostname/IP.                                                    |
| port            | `6379`                                     | Redis server port.                                                           |
| password        | `''`                                       | Redis password if required.                                                 |
| adminbar        | `false`                                    | Disables caching for the WP admin bar if `true`.                             |
| disable-metrics | `false`                                    | Disables Redis metrics if `true`.                                            |
| disable-banners | `false`                                    | Hides Redis banners in WP admin if `true`.                                   |
| prefix          | `md5(env('HOME_URL')) . 'redis-cache'`     | Cache key prefix.                                                            |
| database        | `0`                                        | Redis DB index (0–15).                                                       |
| timeout         | `1`                                        | Redis connection timeout (seconds).                                          |
| read-timeout    | `1`                                        | Redis read timeout (seconds).                                               |

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
Defines references to public key(s) used for encryption or verification, if needed.

| Key   | Default             | Description                                                       |
|---------|---------------------|-------------------------------------------------------------------|
| app-key | `null`             | UUID or filename of the public key stored in WordPress options.    |

**Example:**
```php
return [
    'publickey' => [
        'app-key' => 'my-public-key.pem',
    ],
];
```

## 10. Sudo Admin & Group
Specifies an elevated “super admin” user or group for advanced privileges.

| Key             | Default | Description                                                                |
|-------------------|---------|----------------------------------------------------------------------------|
| sudo_admin        | `1`     | The user ID of the primary “super admin.”                                  |
| sudo_admin_group  | `null`  | An array of additional user IDs with similar elevated privileges.          |

**Example:**
```php
return [
    'sudo_admin'       => 1,
    'sudo_admin_group' => [2, 3, 4],
];
```

## 11. S3 Uploads
Integrates [S3 Uploads](https://github.com/humanmade/S3-Uploads) for media storage in an S3 bucket.

| Key         | Default                 | Description                                                       |
|-------------- |-------------------------|-------------------------------------------------------------------|
| bucket        | `site-uploads`         | Name of your S3 bucket.                                          |
| key           | `''`                   | AWS Access Key ID.                                               |
| secret        | `''`                   | AWS Secret Access Key.                                           |
| region        | `us-east-1`            | AWS region.                                                      |
| bucket-url    | `https://example.com`  | Base URL of your S3 bucket.                                      |
| object-acl    | `public`               | Access control setting for uploaded objects.                      |
| expires       | `2 days`               | HTTP caching expiration for uploaded files.                       |
| http-cache    | `300`                  | `Cache-Control` header value.                                     |

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

## Modifying Configuration Options

1. **Open `configs/app.php`** in your project.
2. **Find or create the relevant array key** (e.g., `error_handler`, `directory`) for the setting you want to change.
3. **Update the values** to match your requirements.
4. **Save and test** your changes locally or on a staging environment.

## Example
Below is a short example showing how you might disable the error handler, set new directory paths, and pick a custom theme:

```php
<?php

return [

    'directory' => [
        'web_root_dir'  => 'public',
        'content_dir'   => 'wp-content',
        'plugin_dir'    => 'wp-content/plugins',
        'theme_dir'     => 'templates',
    ],
];
```
Anything not explicitly defined remains at its default setting.

## Notes
- **Test carefully**: Always verify that your configuration changes work correctly in different environments before deploying.
- **Security best practices**: Pay special attention to keys like `restrict_wpadmin`, `two-factor`, and `encryption_key`.
- **Composer and updates**: If managing WordPress core or plugins via Composer, you may want to disable WordPress’s native updates (`'disable_updates' => true`).
- **Environment variables**: Confirm that each environment variable is set correctly in your `.env` (if in use) or in your hosting environment.
