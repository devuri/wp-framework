# Defining Constants

In the Raydium Framework, constants play a crucial role in configuring and customizing your WordPress application. Raydium is designed to streamline the setup process, automatically defining most of the required constants to get your site up and running efficiently. However, there might be scenarios where you need to define additional constants or customize existing ones to suit your project's specific requirements.

## Customizing Constants

### Adding Constants to `wp-config.php`

When using the Raydium framework, you can still add custom constants to your `wp-config.php` file as needed. This is useful for defining settings required by plugins or other custom configurations.

To add constants, open your `wp-config.php` file, located in the web root directory of your installation. Insert your custom constants above the line that reads `/* That's all, stop editing! Happy publishing. */`. This ensures that your constants are set before the Raydium framework and core WordPress settings are initialized.

For example, if you need to define a constant for a plugin, you can do so like this:
```php
define( 'YOUR_CUSTOM_CONSTANT', 'value' );
```

Your `wp-config.php` file might look like this:
```php

use WPframework\AppFactory;

// This is the bootstrap file for the web application.
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
} else {
    exit('Cannot find the vendor autoload file.');
}

// Add your custom constants here
define( 'YOUR_CUSTOM_CONSTANT', 'value' );


/* That's all, stop editing! Happy publishing. */

$siteAppFactory = AppFactory::create(dirname(__DIR__));
AppFactory::run();

// Set the table prefix.
$table_prefix = env('DB_PREFIX');

```

> [!CAUTION]
> The Raydium framework already defines many standard WordPress constants, and it only does so if they are not already set. Defining these constants yourself could lead to ***unexpected behavior or conflicts***. If you do need to define a constant, ensure it does not overlap with those managed by Raydium to avoid any issues.

After making changes, save the file and test your WordPress site to ensure that everything functions as expected and that your custom settings are applied correctly.


### Environment File (.env)

Raydium utilizes a `.env` [environment file](../customization/environment-file) at the root of your project for environment-specific configurations. This file is the first place to look when you need to customize values related to database connections, site URLs, environment types, and more. Changes made here reflect across your application, providing a centralized location for critical configurations.

### Application Configurations (`configs/app.php`)

For further customization, Raydium offers the `configs/app.php` file for [configuration options](../reference/configuration). This file is intended for more granular application-level configurations that might not fit within the scope of the `.env` [environment file](../customization/environment-file). Here, you can adjust settings that the framework or WordPress core might reference during runtime.

## Defining Additional Constants

### Using `configs/config.php`

 While you can still add custom constants to your `wp-config.php` file just as you would with a standard setup, the `config.php` file in the Raydium Framework is a dedicated space for defining additional [application constants](../reference/app-constants) and customizing your WordPress application settings. This flexibility allows you to tailor application behavior, aligning it with specific requirements.

For constants that extend beyond the foundational setups provided by Raydium and the `.env` file, you can use the `configs/config.php` file.

### Using `.env` file references

The `configs/config.php` file is an ideal location for storing sensitive information, as it allows for better security and flexibility. Many plugins may require you to define API keys or other sensitive credentials. By placing these values in the `config.php` file, you gain full access to the Raydium `env` file, enabling you to securely manage sensitive data.

For example, you can use the `env` file to store sensitive data and then reference it in the `config.php` file like this:

```php
define('MY_SMTP_API_KEY', env('SMTP_API_KEY'));
```

This approach ensures that sensitive information is not hardcoded into your project, reducing the risk of exposure in version control systems or during code sharing. Instead, your credentials are safely stored in the environment file and dynamically accessed as needed.

## Creating and Modifying `config.php`

If `config.php` doesn't exist in your project, you can create it within the `configs` directory. This file will be automatically recognized and loaded by Raydium, applying your custom configurations.

### Configuration Customization

`config.php` is pivotal for adjusting your application's configurations without modifying the core or default settings established by the framework. It's designed for:

- Defining new constants or variables.
- Customizing application behavior.
- Overriding default WordPress constants set by Raydium, such as `WP_DEBUG`.

### Overriding Framework Constants

It's important to exercise caution when defining constants in `configs/config.php`. Constants defined here have the potential to override the framework's default constants. This feature is powerful but must be used judiciously to avoid unintended behaviors or conflicts within your application.

- Review the [WordPress Constants](https://gist.github.com/MikeNGarrett/e20d77ca8ba4ae62adf5) to understand the implications of changing constants.
- Ensure that overrides do not conflict with Raydium's core functionalities.

### Maintenance and Documentation

When adding or modifying settings in `config.php`:

- Clearly document each change to maintain clarity and ease future maintenance.
- Consider version control to track changes and facilitate rollbacks if necessary.

### Example Customizations

To set a constant in your application, you might use:

```php
define('JWT_AUTH_SECRET_KEY', 'XHkoqEykIrQNQdwLmBNMErCFSiIqAGUlGYkA');

// or keep sensitive data secure in your .env file
define('JWT_AUTH_SECRET_KEY', env('RAYDIUM_JWT_AUTH_SECRET_KEY') );

```

Or, to override SSL settings established by the framework:

```php
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);
```

> [!CAUTION]
> While `config.php` allows for the overriding of certain constants, it's essential to use this capability judiciously. Constants defined here have the potential to override the frameworks settings.


### Security Considerations

Sensitive information such as API keys and database credentials should reside in the `.env` file, not in `config.php`. The `.env` approach helps keep sensitive data secure and separate from the codebase.


## Best Practices for Defining Constants

- **Clarity and Documentation**: Clearly document any constants you define in `configs/config.php`, explaining their purpose and potential impact on the application.
- **Avoid Duplication**: Before defining a new constant, ensure it's not already defined by Raydium or within the `.env` file to prevent conflicts.
- **Test Changes**: Thoroughly test any changes made through constant definitions, especially if they override framework defaults, to ensure they don't disrupt the application's intended behavior.
- **Version Control**: Keep `configs/config.php` under version control, ensuring any changes to constants are tracked and can be reviewed or reverted if necessary.

> Raydium Framework provides a flexible and efficient way to manage configurations through constants, balancing automation with customization. Whether through the `.env` file, `configs/app.php`, or `configs/config.php`, you have multiple avenues to tailor your application. Remember to define and override constants with care, ensuring they align with your project's goals and maintain the integrity and performance of your WordPress application.
