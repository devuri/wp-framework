# Managing Environments

The Raydium Framework offers a sophisticated way to handle different environments, such as development, staging, and production. This guide will help you understand how to effectively manage these environments within your Raydium-powered application.

## Understanding Environments

Environments represent different **states** your application can be in, each tailored with specific configurations and behaviors to match its purpose. Common environments include:

- **Production** (`'production'`, `'prod'`): The live environment where the application is optimized for performance and security, with debugging tools disabled.
- **Staging** (`'staging'`): A near-replica of production, used for final testing before updates are made live.
- **Development** (`'development'`, `'dev'`): Used during the development phase, enabling logging, error reporting, and debugging tools.
- **Debug** (`'deb'`, `'debug'`, `'local'`): Similar to development but with extra debugging capabilities for in-depth troubleshooting.
- **Secure** (`'secure'`, `'sec'`): Emphasizes heightened security, typically for sensitive applications.

## Best Practices

- **Keep the `.env` File Secure**: The `.env` [environment file](../customization/environment-file) contains sensitive information. Ensure it's excluded from version control (e.g., using `.gitignore`).
- **Maintain Environment Consistency**: Strive for consistency between development, staging, and production environments to minimize "it works on my machine" problems.
- **Leverage Environment-Specific Configurations**: Utilize environment-specific settings for database connections, API keys, and other configurations to streamline development and deployment.

## Configuring Environments

### Using the `.env` File

The Raydium Framework uses a `.env` [environment file](../customization/environment-file) located at the root of your project to define environment variables. The key variable for setting the environment is `WP_ENVIRONMENT_TYPE`.

Example `.env` file configuration:

```plaintext
WP_ENVIRONMENT_TYPE='prod'
```

> [!IMPORTANT]
> Optionally, You can override the `.env` setup of `WP_ENVIRONMENT_TYPE` by defining the `RAYDIUM_ENVIRONMENT_TYPE` constant in the bootstrap file or in `wp-config.php`. Note that the `.env` file takes precedence, so ensure `WP_ENVIRONMENT_TYPE='prod'` is removed from the file if you plan to override it.

Depending on this setting, the Raydium Framework adjusts its behavior for performance, security, or debugging.

### Supported Environment Values

- `prod` or `production`: Sets the environment to production.
- `dev` or `development`: Sets the environment to development.
- `staging`: Sets the environment to staging.
- `debug` or `deb`: Activates the debug environment.
- `secure` or `sec`: Activates the secure environment.

### Switching Environments

To switch environments, simply modify the `WP_ENVIRONMENT_TYPE` value in your `.env` file. Afterward, redeploy your application if necessary. The Raydium Framework will automatically detect and apply the corresponding configuration settings.

## Environment-Specific Behaviors

Depending on the `WP_ENVIRONMENT_TYPE` value, the Raydium Framework configures the application accordingly:

- **Production**: Optimized for maximum performance and security. Disables debugging tools and hides errors.
- **Staging**: Similar to production but with some logging enabled for testing purposes.
- **Development**: Enables extensive error reporting and debugging tools while turning off performance optimizations.
- **Debug**: Builds upon the development environment by enabling additional debugging features such as script debugging, query logging, and disabling script concatenation.
- **Secure**: Similar to production, but with additional security features, like file modification restrictions and tighter error handling.
- **Cache Settings**: The production and secure environments also enable caching to improve performance, unless explicitly disabled by defining the `SWITCH_OFF_CACHE` constant as `true`.

## Environment Constants Documentation

The Raydium Framework offers several constants to tailor the application for different environments, ensuring the best balance between performance, security, and debugging.

### Production Environment

**Environment Identifiers**: `'production'`, `'prod'`

The production environment is optimized for performance and security.

#### Defined Constants

- **`DISALLOW_FILE_EDIT`**: `true`  
  *Prevents file editing via the WordPress admin panel to enhance security.*

- **`WP_DEBUG_DISPLAY`**: `false`  
  *Hides debug messages on the frontend.*

- **`SCRIPT_DEBUG`**: `false`  
  *Uses minified JavaScript and CSS files for faster load times.*

- **`WP_CRON_LOCK_TIMEOUT`**: `60`  
  *Defines the time (in seconds) before a cron job is marked as failed and can be retried.*

- **`EMPTY_TRASH_DAYS`**: `15`  
  *Automatically deletes trashed items after 15 days.*

- **`WP_DEBUG`**: `true` if `$error_logs_dir` is provided; otherwise `false`.  
  *Enables or disables WordPress debugging.*

- **`WP_DEBUG_LOG`**: Path specified by `$error_logs_dir` if provided; otherwise `false`.  
  *Defines where to log debug messages.*

- **`WP_CACHE`**: `true`  
  *Enables caching for improved performance.*

- **`COMPRESS_SCRIPTS`**: `true`  
  *Enables compression of JavaScript files for improved performance.*

- **`COMPRESS_CSS`**: `true`  
  *Enables compression of CSS files for improved performance.*

- **`CONCATENATE_SCRIPTS`**: `true`  
  *Enables concatenation of scripts to reduce HTTP requests.*

#### PHP Settings

- **`display_errors`**: `'0'`  
  *Prevents PHP errors from being displayed to users.*

### Staging Environment

**Environment Identifier**: `'staging'`

The staging environment mirrors production, with additional error visibility for testing.

#### Defined Constants

- **`DISALLOW_FILE_EDIT`**: `false`  
  *Allows editing plugin and theme files in the admin panel.*

- **`WP_DEBUG_DISPLAY`**: `true`  
  *Shows debug messages on the frontend.*

- **`SCRIPT_DEBUG`**: `false`  
  *Uses minified scripts.*

- **`SAVEQUERIES`**: `true`  
  *Saves database queries for analysis.*

- **`WP_DEBUG`**: `true`  
  *Enables WordPress debugging.*

- **`WP_DEBUG_LOG`**: Path specified by `$error_logs_dir` if provided; otherwise `true`.  
  *Logs debug messages to the specified file.*

#### PHP Settings

- **`display_errors`**: `'0'`  
  *Keeps PHP errors hidden from users.*

### Development Environment

**Environment Identifiers**: `'development'`, `'dev'`

Configured for building and testing features.

#### Defined Constants

- **`WP_DEBUG`**: `true`  
  *Enables WordPress debugging.*

- **`SAVEQUERIES`**: `true`  
  *Saves database queries for analysis.*

- **`WP_DEBUG_DISPLAY`**: `true`  
  *Shows debug messages on the frontend.*

- **`WP_DISABLE_FATAL_ERROR_HANDLER`**: `true`  
  *Disables the fatal error handler to display errors.*

- **`SCRIPT_DEBUG`**: `true`  
  *Uses unminified scripts for easier debugging.*

- **`WP_DEBUG_LOG`**: Path specified by `$error_logs_dir` if provided; otherwise `true`.  
  *Logs debug messages.*

#### PHP Settings

- **`display_errors`**: `'1'`  
  *Displays PHP errors on the frontend.*

### Debug Environment

**Environment Identifiers**: `'deb'`, `'debug'`, `'local'`

Extensive debugging capabilities for detailed troubleshooting.

#### Defined Constants

- **`WP_DEBUG`**: `true`  
  *Enables WordPress debugging.*

- **`WP_DEBUG_DISPLAY`**: `true`  
  *Displays debug messages.*

- **`CONCATENATE_SCRIPTS`**: `false`  
  *Prevents concatenation of scripts for easier debugging.*

- **`SAVEQUERIES`**: `true`  
  *Saves database queries.*

- **`WP_CRON_LOCK_TIMEOUT`**: `120`  
  *Sets a longer cron lock timeout for debugging.*

- **`EMPTY_TRASH_DAYS`**: `50`  
  *Extends trash retention period for easier recovery during debugging.*

- **`WP_DEBUG_LOG`**: Path specified by `$error_logs_dir` if provided; otherwise `true`.  
  *Logs debug messages.*

#### PHP Settings

- **`error_reporting`**: `E_ALL`  
  *Reports all errors.*

- **`log_errors`**: `'1'`  
  *Enables error logging.*

- **`log_errors_max_len`**: `'0'`  
  *Removes the limit on the length of logged errors.*

- **`display_errors`**: `'1'`  
  *Displays PHP errors on the frontend.*

- **`display_startup_errors`**: `'1'`  
  *Displays startup sequence errors.*

### Secure Environment

**Environment Identifiers**: `'secure'`, `'sec'`

Optimized for maximum security.

#### Defined Constants

- **`DISALLOW_FILE_EDIT`**: `true`  
  *Disables editing files through the admin panel.*

- **`DISALLOW_FILE_MODS`**: `true`  
  *Prevents updates and installations of plugins and themes.*

- **`WP_DEBUG_DISPLAY`**: `false`  
  *Hides debug messages.*

- **`SCRIPT_DEBUG`**: `false`  
  *Uses minified scripts.*

- **`WP_CRON_LOCK_TIMEOUT`**: `120`  
  *Sets a longer cron lock timeout.*

- **`EMPTY_TRASH_DAYS`**: `10`  
  *Reduces trash retention period.*

- **`WP_DEBUG`**: `true` if `$error_logs_dir` is provided; otherwise `false`.  
  *Enables or disables debugging.*

- **`WP_CACHE`**: `true`  
  *Enables caching for improved performance.*

- **`COMPRESS_SCRIPTS`**: `true`  
  *Enables compression of JavaScript files.*

- **`COMPRESS_CSS`**: `true`  
  *Enables compression of CSS files.*

- **`CONCATENATE_SCRIPTS`**: `true`  
  *Enables concatenation of scripts.*

#### PHP Settings

- **`display_errors`**: `'0'`  
  *Ensures errors are not displayed to users.*

## Commonly Used Constants Explained

- **`DISALLOW_FILE_EDIT`**: Prevents file editing via the admin panel.
- **`DISALLOW_FILE_MODS`**: Disables all file modifications, including updates.
- **`WP_DEBUG`**: Activates WordPress debugging mode.
- **`WP_DEBUG_DISPLAY`**: Controls whether debug messages are shown.
- **`WP_DEBUG_LOG`**: Specifies whether debug messages are logged.
- **`SCRIPT_DEBUG`**: Uses unminified versions of scripts and styles.
- **`SAVEQUERIES`**: Saves database queries for debugging.
- **`CONCATENATE_SCRIPTS`**: Disables or enables script concatenation.
- **`WP_DISABLE_FATAL_ERROR_HANDLER`**: Disables the fatal error handler during development.
- **`WP_CRON_LOCK_TIMEOUT`**: Adjusts the retry timeout for cron jobs.
- **`EMPTY_TRASH_DAYS`**: Sets auto-delete period for trashed items.
- **`WP_CACHE`**: Enables caching for improved performance, especially in production and secure environments.
- **`COMPRESS_SCRIPTS`**: Enables compression of JavaScript for faster loading.
- **`COMPRESS_CSS`**: Enables compression of CSS for faster loading.

### Overriding Constants

Constants can be overridden in the `wp-config.php` file. The Raydium Framework only defines these if they haven't been set, allowing custom configurations.

For more on WordPress constants:

- [WordPress Debugging Tools](https://wordpress.org/support/article/debugging-in-wordpress/)
- [Editing wp-config.php](https://wordpress.org/support/article/editing-wp-config-php/)

## PHP Settings Explained

- **`error_reporting`**: Specifies which PHP errors are reported.
- **`display_errors`**: Controls whether errors are displayed to users.
- **`display_startup_errors`**: Shows errors that occur during PHP's startup sequence.
- **`log_errors`**: Enables or disables error logging.
- **`log_errors_max_len`**: Defines the maximum length of log messages.

## Notes

- **Error Logs Directory (`$error_logs_dir`)**: If specified, this path is used for logging debug messages.
- **`ini_set` Function**: Used to modify runtime settings like error display and logging.
- **Error Handling**: The production and secure environments hide errors, while development and debug environments display them for easier troubleshooting.
- **Caching**: Caching is enabled in production and secure environments by default to improve performance, unless explicitly disabled by the `SWITCH_OFF_CACHE` constant.

> By understanding and properly configuring each environment, developers and administrators can optimize their application for security, performance, and ease of debugging.
