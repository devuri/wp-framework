# Developer Guide for the `Setup` Class

This guide is intended for developers who wish to understand, contribute to, or extend the functionality of the `Setup` class. It provides detailed insights into the class structure, coding conventions, dependencies, and guidelines for contributing to the project.

## Table of Contents

- [Introduction](#introduction)
- [Class Overview](#class-overview)
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Code Structure](#code-structure)
- [Coding Conventions](#coding-conventions)
- [Methods and Functionality](#methods-and-functionality)
  - [Constructor](#constructor)
  - [Initialization](#initialization)
  - [Configuration](#configuration)
  - [Environment Handling](#environment-handling)
  - [Error Handling](#error-handling)
  - [Database Configuration](#database-configuration)
  - [URL Configuration](#url-configuration)
  - [Performance Optimization](#performance-optimization)
  - [SSL Enforcement](#ssl-enforcement)
  - [Autosave and Revisions](#autosave-and-revisions)
  - [Authentication Salts](#authentication-salts)
- [Extending the Class](#extending-the-class)
- [Testing](#testing)
- [Contributing Guidelines](#contributing-guidelines)
- [License](#license)
- [Contact](#contact)

## Introduction

The `Setup` class is a foundational component designed to streamline the initialization and configuration of WordPress applications. It leverages environment variables for configuration, supports multiple environments, and provides mechanisms for error handling, database setup, and performance optimization.

This guide is for developers who are interested in contributing to the `Setup` class, whether by adding new features, fixing bugs, or improving documentation.

## Class Overview

- **Namespace**: `WPframework`
- **Implements**: `SetupInterface`
- **Traits Used**:
  - `ConstantBuilderTrait`
  - `TenantTrait`
- **Purpose**: To manage the setup and configuration of WordPress applications using environment variables and to provide an interface for initializing different environments.

## Dependencies

The `Setup` class relies on several external dependencies:

- **PHP Version**: 7.4 or higher
- **Composer Packages**:
  - `vlucas/phpdotenv`: For loading environment variables from `.env` files.
  - `symfony/error-handler`: For error handling during development.
  - `filp/whoops`: For detailed error pages in development environments.
- **Internal Interfaces and Traits**:
  - `WPframework\Env\EnvType`
  - `WPframework\Http\EnvSwitcherInterface`
  - `WPframework\Traits\ConstantBuilderTrait`
  - `WPframework\Traits\TenantTrait`

## Installation

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/your-repo/wpframework.git
   ```

2. **Install Dependencies**:

   Navigate to the project directory and run:

   ```bash
   composer install
   ```

3. **Set Up Environment Files**:

   Place your `.env` files in the root directory of your application.

## Code Structure

The `Setup` class is organized into several methods, each responsible for a specific aspect of the application configuration:

- **Constructor and Initialization**: Handles initial setup, such as loading environment files.
- **Configuration Methods**: Methods that set up different parts of the application, like the database, URLs, and performance settings.
- **Helper Methods**: Private and protected methods that assist in the main configuration tasks, such as normalizing environment inputs and handling errors.

## Coding Conventions

The code follows the PSR-12 coding standard:

- **Naming Conventions**:
  - Classes: `PascalCase`
  - Methods and variables: `camelCase`
  - Constants: `UPPER_SNAKE_CASE`
- **Indentation**: 4 spaces
- **Line Length**: Maximum of 120 characters
- **Braces**: Opening braces on the same line for classes and methods.

**Note**: External dependencies and methods that are not under our control maintain their original naming conventions to ensure compatibility.

## Methods and Functionality

### Constructor

```php
public function __construct(string $appPath, array $envFileNames = [], bool $shortCircuit = true)
```

- **Purpose**: Initializes the `Setup` instance by determining the application path, loading environment files, and setting up the constant map.
- **Parameters**:
  - `$appPath`: The root path of the application.
  - `$envFileNames`: An array of custom `.env` file names to load.
  - `$shortCircuit`: Determines whether to use short-circuit mode when loading environment variables.

### Initialization

#### `init`

```php
public static function init(string $appPath): self
```

- **Purpose**: Creates a singleton instance of the `Setup` class.
- **Usage**:

  ```php
  $setup = Setup::init(__DIR__);
  ```

### Configuration

#### `config`

```php
public function config($environment = null, ?bool $setup = true): SetupInterface
```

- **Purpose**: Configures the application environment and sets up various settings based on the provided environment.
- **Parameters**:
  - `$environment`: An array or string specifying the environment settings.
  - `$setup`: A boolean indicating whether to perform the full setup.

- **Usage**:

  ```php
  $setup->config([
      'environment' => 'development',
      'error_log'   => '/path/to/error.log',
      'errors'      => 'whoops',
  ]);
  ```

### Environment Handling

#### `setEnvironment`

```php
public function setEnvironment(): SetupInterface
```

- **Purpose**: Defines the `WP_ENVIRONMENT_TYPE` constant based on the current environment.
- **Behavior**:
  - If the environment is not set, it attempts to use the `WP_ENVIRONMENT_TYPE` from the environment variables.
  - If still not set, it defaults to the value from `getConstant('environment')`.

### Error Handling

#### `setErrorHandler`

```php
public function setErrorHandler(?string $handler = null): SetupInterface
```

- **Purpose**: Sets up the error handler based on the environment and specified handler.
- **Supported Handlers**:
  - `'symfony'`: Uses Symfony's Debug component.
  - `'oops'`: Uses Whoops for error handling.
- **Usage**:

  ```php
  $setup->setErrorHandler('symfony');
  ```

### Database Configuration

#### `database`

```php
public function database(): SetupInterface
```

- **Purpose**: Defines the database constants using environment variables.
- **Constants Set**:
  - `DB_NAME`
  - `DB_USER`
  - `DB_PASSWORD`
  - `DB_HOST`
  - `DB_CHARSET`
  - `DB_COLLATE`

### URL Configuration

#### `siteUrl`

```php
public function siteUrl(): SetupInterface
```

- **Purpose**: Defines the `WP_HOME` and `WP_SITEURL` constants.
- **Usage**:

  ```php
  $setup->siteUrl();
  ```

#### `assetUrl`

```php
public function assetUrl(): SetupInterface
```

- **Purpose**: Defines the `ASSET_URL` constant for loading assets.

### Performance Optimization

#### `optimize`

```php
public function optimize(): SetupInterface
```

- **Purpose**: Sets the `CONCATENATE_SCRIPTS` constant to optimize script loading.

#### `memory`

```php
public function memory(): SetupInterface
```

- **Purpose**: Defines memory limits for the application.
- **Constants Set**:
  - `WP_MEMORY_LIMIT`
  - `WP_MAX_MEMORY_LIMIT`

### SSL Enforcement

#### `forceSsl`

```php
public function forceSsl(): SetupInterface
```

- **Purpose**: Enforces SSL on admin and login pages.
- **Constants Set**:
  - `FORCE_SSL_ADMIN`
  - `FORCE_SSL_LOGIN`

### Autosave and Revisions

#### `autosave`

```php
public function autosave(): SetupInterface
```

- **Purpose**: Configures autosave intervals and post revision limits.
- **Constants Set**:
  - `AUTOSAVE_INTERVAL`
  - `WP_POST_REVISIONS`

### Authentication Salts

#### `salts`

```php
public function salts(): SetupInterface
```

- **Purpose**: Defines the authentication keys and salts required by WordPress.
- **Constants Set**:
  - `AUTH_KEY`
  - `SECURE_AUTH_KEY`
  - `LOGGED_IN_KEY`
  - `NONCE_KEY`
  - `AUTH_SALT`
  - `SECURE_AUTH_SALT`
  - `LOGGED_IN_SALT`
  - `NONCE_SALT`

## Extending the Class

Developers can extend the `Setup` class to add additional configuration methods or override existing ones. When extending, ensure that:

- New methods adhere to the coding conventions.
- The class remains compatible with the `SetupInterface`.
- Any new dependencies are added to `composer.json`.

Example of extending:

```php
class CustomSetup extends Setup
{
    public function customConfiguration(): SetupInterface
    {
        // Custom configuration logic
        return $this;
    }
}
```

## Testing

Testing is crucial to ensure the reliability of the `Setup` class. Tests should cover:

- Loading and parsing environment variables.
- Setting up constants correctly.
- Handling different environments.
- Error handling mechanisms.

**Testing Framework**: PHPUnit

### Running Tests

1. **Install PHPUnit**:

   ```bash
   composer require --dev phpunit/phpunit
   ```

2. **Run Tests**:

   ```bash
   vendor/bin/phpunit tests
   ```

### Writing Tests

Tests are located in the `tests` directory. Each method should have corresponding test cases that cover different scenarios, including edge cases.

Example test case:

```php
class SetupTest extends \PHPUnit\Framework\TestCase
{
    public function testEnvironmentIsSet()
    {
        $setup = new Setup(__DIR__);
        $setup->config('development');

        $this->assertEquals('development', $setup->getEnvironment());
    }
}
```

## Contributing Guidelines

We welcome contributions from the community! Please follow these guidelines to ensure a smooth collaboration.

### Reporting Issues

- Use the GitHub [issue tracker](https://github.com/your-repo/wpframework/issues) to report bugs or request features.
- Provide as much detail as possible, including steps to reproduce the issue.

### Submitting Pull Requests

1. **Fork the Repository**:

   Click the "Fork" button on the repository page.

2. **Create a Branch**:

   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make Changes**:

   - Follow the coding conventions.
   - Write tests for new features or bug fixes.
   - Ensure all tests pass before committing.

4. **Commit Changes**:

   ```bash
   git commit -m "Add your commit message here"
   ```

5. **Push to Your Fork**:

   ```bash
   git push origin feature/your-feature-name
   ```

6. **Open a Pull Request**:

   Go to your fork on GitHub and open a pull request to the `main` branch of the original repository.

### Code Review Process

- Pull requests will be reviewed by maintainers.
- Feedback will be provided, and changes may be requested.
- Once approved, the pull request will be merged.

### Coding Style

- Adhere to PSR-12 coding standards.
- Use meaningful variable and method names.
- Include docblocks for classes and methods.

### Commit Messages

- Use clear and descriptive commit messages.
- Follow the [Conventional Commits](https://www.conventionalcommits.org/) specification if possible.

## License

This project is licensed under the [MIT License](LICENSE). By contributing, you agree that your contributions will be licensed under the MIT License.

## Contact

For questions or support, please open an issue or contact the maintainer:

- **Email**: [maintainer@example.com](mailto:maintainer@example.com)
- **GitHub**: [github.com/your-username](https://github.com/your-username)
