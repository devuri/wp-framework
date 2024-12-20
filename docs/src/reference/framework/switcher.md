# Raydium's Environment Switcher

## Overview

The Environment Switcher (`Switcher`) component within the Raydium Framework is essential for tailoring the application's behavior to match the requirements of different operational environments, such as development, staging, production, and debugging. It dynamically configures the application based on predefined environment types, optimizing performance, debugging capabilities, and security settings accordingly.

## Key Functionalities

### Dynamic Environment Configuration
- **Configurable Environments**: Supports multiple predefined environments, including production, staging, development, secure, and debug.
- **Environment-Specific Settings**: Applies a distinct set of configurations for each environment, influencing error handling, debugging options, file editing permissions, and more.

### Error Handling and Debugging
- **Debug Logging**: Configures the path and behavior of the debug log file based on the environment, aiding in error tracking and resolution.
- **Error Display and Reporting**: Controls the visibility and reporting level of errors, tailored to the needs of each environment.

## Environment Types and Configurations

### Production (`production` or `prod`)
- Disables the Plugin and Theme Editor (`DISALLOW_FILE_EDIT`) to enhance security.
- Suppresses error display (`WP_DEBUG_DISPLAY`) and script debugging (`SCRIPT_DEBUG`).
- Sets a specific cron lock timeout (`WP_CRON_LOCK_TIMEOUT`) and trash emptying interval (`EMPTY_TRASH_DAYS`).
- Configures debug logging based on the availability of a specified error log directory.

### Staging (`staging`)
- Allows Plugin and Theme editing by not setting `DISALLOW_FILE_EDIT`.
- Enables error display (`WP_DEBUG_DISPLAY`) but disables script debugging (`SCRIPT_DEBUG`).
- Turns on debug mode (`WP_DEBUG`) but suppresses error display in the browser.
- Configures debug logging to a specified error log directory, if available.

### Development (`development` or `dev`)
- Enables comprehensive debugging (`WP_DEBUG`, `SAVEQUERIES`) and script debugging (`SCRIPT_DEBUG`).
- Displays errors directly (`WP_DEBUG_DISPLAY`) and disables the fatal error handler (`WP_DISABLE_FATAL_ERROR_HANDLER`).
- Shows all PHP errors and warnings and configures error logging to a specified directory or enables it if no directory is provided.

### Debug (`debug` or `local`)
- Activates debug mode (`WP_DEBUG`) and ensures all PHP errors, warnings, and notices are reported.
- Enables error display (`WP_DEBUG_DISPLAY`) and query saving (`SAVEQUERIES`) for performance analysis.
- Disables script concatenation (`CONCATENATE_SCRIPTS`) to ease script debugging.
- Configures detailed error logging to the specified directory or enables standard logging.

### Secure (`secure` or `sec`)
- Enhances security by disabling file modifications (`DISALLOW_FILE_EDIT` and `DISALLOW_FILE_MODS`).
- Hides errors from display (`WP_DEBUG_DISPLAY`) and disables script debugging (`SCRIPT_DEBUG`).
- Configures a specific cron lock timeout (`WP_CRON_LOCK_TIMEOUT`) and trash emptying interval (`EMPTY_TRASH_DAYS`).
- Enables debug logging to a specified directory, if provided, while ensuring errors are not displayed on the site.

## Integration in the Raydium Framework

The Switcher component is integrated into the Raydium Framework to facilitate seamless transitions between different operational environments, ensuring that each environment is configured with the appropriate settings for error handling, debugging, and performance optimization. It allows developers to specify the current environment, automatically applying the relevant configurations.

## Usage Guidelines

To utilize the Environment Switcher within the Raydium Framework:
- Determine the current operational environment of the application (e.g., development, staging, production).
- Invoke the `createEnvironment` method of the Switcher component, passing the current environment and an optional error log directory.
- The Switcher component will automatically apply the configurations associated with the specified environment.

> The Environment Switcher is a critical component of the Raydium Framework, providing dynamic and flexible environment configuration to optimize the application for various operational contexts. By defining clear boundaries and settings for each environment, the Switcher ensures that applications developed with the Raydium Framework are secure, debuggable, and performant, aligned with the best practices for modern web development.
