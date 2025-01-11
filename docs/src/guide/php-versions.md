# Specify PHP Version

### Using a Specific PHP Version with Composer

## **Why You Might Need This**
PHP is a flexible language that frequently releases new versions, each with different features, performance improvements, and sometimes breaking changes. As a developer, you might encounter situations where:

- **Projects Use Different PHP Versions**: One project might require PHP 7.4, while another needs PHP 8.1.
- **Default PHP Version Doesn't Match Requirements**: Your system's default PHP version may not support all dependencies in a `composer.json` file.
- **Dependency Resolution Issues**: Some Composer packages may fail to install if the wrong PHP version or missing extensions are used.

By learning how to specify the PHP version for Composer and other CLI tools, you gain better control over your development environment, avoid compatibility issues, and streamline project management.

## **How to Use a Specific PHP Version with Composer**

### **1. Find Installed PHP Versions**
The first step is identifying which PHP versions are available on your system.

- **Check the Default PHP Version**:
  ```bash
  php -v
  ```

- **List All PHP Binaries**:
  ```bash
  which php
  ls /usr/bin/php*
  ```

### Example Output:
```bash
/usr/bin/php
/usr/bin/php7.4
/usr/bin/php8.1
```

This tells you where PHP binaries are installed and helps you determine which version you want to use.


### **2. Run Composer with a Specific PHP Version**
Once you’ve identified the path to your desired PHP version, prepend it to the Composer command:

```bash
/path/to/php /path/to/composer.phar install
```

#### Example:
```bash
/usr/bin/php8.1 /usr/local/bin/composer install
```

This ensures Composer uses the specified PHP version for the command.


### **3. Temporarily Set PHP Version for Your Terminal Session**
You can temporarily prioritize a specific PHP version in your terminal by modifying the `PATH` variable.

1. Update `PATH` to point to the desired PHP version:
   ```bash
   export PATH=/path/to/php/bin:$PATH
   ```

2. Verify the active PHP version:
   ```bash
   php -v
   ```

3. Run Composer:
   ```bash
   composer install
   ```

#### Example:
```bash
export PATH=/usr/bin/php8.1/bin:$PATH
php -v        # Should now show PHP 8.1
composer install
```

This method only affects the current terminal session.


### **4. Use `COMPOSER_PHP` to Define PHP for Composer**
If you’re running multiple Composer commands or scripts, you can set the `COMPOSER_PHP` environment variable to define the PHP binary Composer should use.

1. Set the environment variable:
   ```bash
   export COMPOSER_PHP=/path/to/php
   ```

2. Run Composer:
   ```bash
   composer install
   ```

#### Example:
```bash
export COMPOSER_PHP=/usr/bin/php8.1
composer install
```

This is useful when you want to standardize PHP usage for a session or script.


### **5. Verify the PHP Version Composer is Using**
To confirm which PHP version Composer is using, add the `-vvv` flag for verbose output:

```bash
composer about -vvv
```

Look for the PHP version details in the output.

## **Troubleshooting Common Issues**

### 1. **Composer Uses the Wrong PHP Version**
- Verify the active PHP version:
  ```bash
  php -v
  ```
- Explicitly call the desired PHP binary:
  ```bash
  /path/to/php /path/to/composer.phar install
  ```

### 2. **PHP Binary Not Found**
- Check for the PHP binary:
  ```bash
  which php8.1
  ls /usr/bin/php*
  ```
- If missing, install the desired PHP version using your package manager:
  ```bash
  sudo apt install php8.1
  ```

### 3. **Missing PHP Extensions**
- Check loaded PHP modules:
  ```bash
  /path/to/php -m
  ```
- Install required extensions:
  ```bash
  sudo apt install php8.1-<extension>
  ```

### 4. **Composer Dependency Issues**
- Use Composer’s `platform` configuration to simulate a specific PHP version for dependency resolution:
  ```json
  {
    "config": {
      "platform": {
        "php": "8.1.0"
      }
    }
  }
  ```

### 5. **Global PHP Version Conflicts**
If switching PHP versions globally is necessary:
- Use `update-alternatives` (Linux) to configure the default PHP version:
  ```bash
  sudo update-alternatives --config php
  ```

Always invoke Composer with the desired PHP version explicitly to avoid dependency on the system default:

```bash
/path/to/php /path/to/composer.phar [command]
```

This approach ensures your development workflow remains predictable and compatible across different projects.
