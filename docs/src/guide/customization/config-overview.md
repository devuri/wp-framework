# Configurations

This guide explains how to modify the [configuration options](../reference/framework/configuration) of the framework used in your project. The configuration options are defined in the `configs/app.php` file.

## Using Environment Variables

Most configuration values can be set using environment variables defined in the `.env` [environment file](../customization/environment-file). This approach provides flexibility and allows easy customization for different environments.

## Accessing Configuration Options

Configuration options can be accessed in two ways:

1. **Using the `config()` Helper Function:** This function provides easy access to the configuration values throughout the application.

2. **Using the `get_config()` Method:** This method, available in the framework's Kernel, returns the configuration options as an array.

## Configuration Options Overview

The `configs/app.php` config file contains various configuration options organized into sections. Here's a brief overview of some key sections:

### Error Handler

- Defines the error handler for the project.
- Can be set to 'oops', 'symfony', or `false` to disable error handling.

### Directory Structure

- Defines the directory structure for the project, including web root, content directory, plugins directory, etc.

### Default Theme

- Sets the default fallback theme for the project.

### Security Settings

- Contains various security settings to enhance the security of the application.

### Email SMTP Configuration

- Configures the mailer settings for sending emails using different providers such as Brevo, Postmark, SendGrid, Mailgun, and SES.

### Redis Cache Configuration

- Contains configuration settings for the Redis cache integration in WordPress.

### Public Key

- Represents a public key used for encryption or verification purposes.

## Modifying Configuration Options

To modify the configuration options:

1. Open the `app.php` file located in the project's root `configs` directory.
2. Find the section containing the configuration option you want to modify.
3. Update the value according to your requirements.
4. Save the file.

## Using Environment Variables

If you prefer to use environment variables:

1. Open the `.env` file located in the project's root directory.
2. Set the desired environment variable(s) corresponding to the configuration option(s) you want to modify.
3. Save the file.

## Example

To illustrate, let's say you want to modify the default theme:

```php
// app.php
return [
    'default_theme' => env( 'DEFAULT_THEME', 'fallback-theme' ),
    // Other configuration options...
];
```

and then in your `.env`:

```dotenv
# .env
DEFAULT_THEME=my-custom-theme
```

## Notes

- Always ensure to follow best practices and security guidelines when modifying configuration options.
- Make sure to test the changes, especially in different environments.

> [!IMPORTANT]
> The `configs/app.php` file includes sensible default settings. While you don't need to include all default configurations in your custom `app.php` file, it's best to add the full block of any setting you wish to override. This helps avoid unwanted errors or unexpected behavior.

**Example:** If you want to change the `directory` settings, include the entire block in your `app.php`:
 ```php
 return [
     'directory'        => [
         'web_root_dir'  => 'public',
         'content_dir'   => 'wp-content',
         'plugin_dir'    => 'wp-content/plugins',
         'mu_plugin_dir' => 'wp-content/mu-plugins',
         'sqlite_dir'    => 'sqlitedb',
         'sqlite_file'   => '.sqlite-wpdatabase',
         'theme_dir'     => 'templates',
         'asset_dir'     => 'assets',
         'publickey_dir' => 'pub_keys',
     ]
 ];
 ```

## Configuration Example:

```php
<?php

return [
    'error_handler'    => null,
    'terminate'        => [
        'debugger' => false,
    ],

    'directory'        => [
        'web_root_dir'      => 'public',
        'content_dir'   => 'wp-content',
        'plugin_dir'    => 'wp-content/plugins',
        'mu_plugin_dir' => 'wp-content/mu-plugins',
        'sqlite_dir'    => 'sqlitedb',
        'sqlite_file'   => '.sqlite-wpdatabase',
        'theme_dir'     => 'templates',
        'asset_dir'     => 'assets',
        'publickey_dir' => 'pubkeys',
    ],

    'default_theme'    => 'brisko',
    'disable_updates'  => true,
    'can_deactivate'   => true,

    'security'         => [
        'encryption_key'     => null,
        'brute-force'        => true,
        'two-factor'         => true,
        'no-pwned-passwords' => true,
        'admin-ips'          => [],
    ],

    'mailer'           => [
        'brevo'      => [
            'apikey' => env( 'BREVO_API_KEY' ),
        ],
        'postmark'   => [
            'token' => env( 'POSTMARK_TOKEN' ),
        ],
        'sendgrid'   => [
            'apikey' => env( 'SENDGRID_API_KEY' ),
        ],
        'mailerlite' => [
            'apikey' => env( 'MAILERLITE_API_KEY' ),
        ],
        'mailgun'    => [
            'domain'   => env( 'MAILGUN_DOMAIN' ),
            'secret'   => env( 'MAILGUN_SECRET' ),
            'endpoint' => env( 'MAILGUN_ENDPOINT', 'api.mailgun.net' ),
            'scheme'   => 'https',
        ],
        'ses'        => [
            'key'    => env( 'AWS_ACCESS_KEY_ID' ),
            'secret' => env( 'AWS_SECRET_ACCESS_KEY' ),
            'region' => env( 'AWS_DEFAULT_REGION', 'us-east-1' ),
        ],
    ],
    'sudo_admin'       => env( 'SUDO_ADMIN', 1 ),
    'sudo_admin_group' => null,
    's3uploads'        => [
        'bucket'     => env( 'S3_UPLOADS_BUCKET', 'site-uploads' ),
        'key'        => env( 'S3_UPLOADS_KEY', '' ),
        'secret'     => env( 'S3_UPLOADS_SECRET', '' ),
        'region'     => env( 'S3_UPLOADS_REGION', 'us-east-1' ),
        'bucket-url' => env( 'S3_UPLOADS_BUCKET_URL', 'https://example.com' ),
        'object-acl' => env( 'S3_UPLOADS_OBJECT_ACL', 'public' ),
        'expires'    => env( 'S3_UPLOADS_HTTP_EXPIRES', '2 days' ),
        'http-cache' => env( 'S3_UPLOADS_HTTP_CACHE_CONTROL', '300' ),
    ],

    'redis'            => [
        'disabled'        => env( 'WP_REDIS_DISABLED', false ),
        'host'            => env( 'WP_REDIS_HOST', '127.0.0.1' ),
        'port'            => env( 'WP_REDIS_PORT', 6379 ),
        'password'        => env( 'WP_REDIS_PASSWORD', '' ),
        'adminbar'        => env( 'WP_REDIS_DISABLE_ADMINBAR', false ),
        'disable-metrics' => env( 'WP_REDIS_DISABLE_METRICS', false ),
        'disable-banners' => env( 'WP_REDIS_DISABLE_BANNERS', false ),
        'prefix'          => env( 'WP_REDIS_PREFIX', md5( env( 'WP_HOME' ) ) . 'redis-cache' ),
        'database'        => env( 'WP_REDIS_DATABASE', 0 ),
        'timeout'         => env( 'WP_REDIS_TIMEOUT', 1 ),
        'read-timeout'    => env( 'WP_REDIS_READ_TIMEOUT', 1 ),
    ],

    'publickey'        => [
        'app-key' => env( 'WEB_APP_PUBLIC_KEY', null ),
    ],
];
```

Check out full configuration example: [Config Reference](../reference/configuration)
