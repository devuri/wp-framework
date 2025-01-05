# Compatibility and Dependency

## Plugin Compatibility and Dependency Management

WordPress plugins often include their own Composer-managed dependencies, which can lead to conflicts when used in conjunction with Raydium's upstream dependencies. This guide explains these challenges, provides guidance for developers evaluating plugins, and outlines best practices for plugin authors to ensure compatibility with Raydium’s modular framework.

## Potential Conflicts with Plugin Dependencies

Raydium leverages Composer to manage its dependencies, ensuring a secure, scalable, and modular foundation for WordPress applications. However, when plugins also use Composer to include popular libraries from Packagist (e.g., `guzzlehttp/guzzle`, `symfony/console`), differing versions of the same dependencies can cause:

- **Class Loading Conflicts**: Composer cannot autoload multiple versions of the same class, resulting in fatal errors.
- **Unexpected Behavior**: Incompatible methods or interfaces between versions may lead to bugs or crashes.
- **Deployment Issues**: Resolving these conflicts at runtime or in production can delay deployments and increase maintenance complexity.

> [!IMPORTANT]
> Note: This issue is not unique to Raydium but is a broader limitation of WordPress itself. The WordPress architecture was not originally designed with Composer or modern dependency management tools in mind, primarily because Composer did not exist when WordPress was first developed. This inherent limitation affects the entire WordPress ecosystem when it comes to handling dependency conflicts in a modular and scalable manner.

## Guidelines for Plugin Evaluation

When selecting plugins for use in a Raydium-based application, developers should:

### 1. **Analyze Dependency Usage**
   - Review the plugin’s `composer.json` file to identify its dependencies and compare them with Raydium’s dependencies.
   - Focus on libraries prone to version mismatches, such as:
     - `symfony/*`
     - `guzzlehttp/guzzle`
     - `psr/log`

### 2. **Test in Isolation**
   - Install the plugin in a staging environment using Raydium’s framework.
   - Monitor for PHP errors, broken functionality, or unexpected interactions.

### 3. **Favor Isolated Plugins**
   - Prioritize plugins that implement dependency isolation strategies (e.g., dependency namespacing) to avoid shared library conflicts.


## Best Practices for Plugin Authors

Plugin authors should take proactive measures to prevent conflicts with Raydium and other plugins by following these best practices.

### 1. Namespace Dependencies
Isolating Composer dependencies ensures compatibility. Use a tool like [PHP-Scoper](https://github.com/humbug/php-scoper) to prefix your plugin’s dependencies with unique namespaces.

#### Workflow Example:
1. Install PHP-Scoper:
   ```bash
   composer require humbug/php-scoper --dev
   ```
2. Prefix dependencies:
   ```bash
   php-scoper add-prefix --output-dir=build/
   ```
3. Distribute the namespaced build to users.

This approach ensures your dependencies do not interfere with global or other plugin autoloaders.


### 2. Minimize Dependency Footprint
Reduce the risk of conflicts by using fewer dependencies or selecting lightweight alternatives. For example:
- Replace `guzzlehttp/guzzle` with native PHP HTTP clients where possible.
- Leverage PHP polyfills, such as [Symfony Polyfills](https://github.com/symfony/polyfill), to support older PHP versions without pulling in heavy libraries.


### 3. Use the `composer/installers` Package
When managing dependencies in a WordPress plugin, use [composer/installers](https://github.com/composer/installers) to properly structure files within the WordPress ecosystem.

#### Example `composer.json`:
```json
{
  "name": "my-plugin/example-plugin",
  "type": "wordpress-plugin",
  "require": {
    "guzzlehttp/guzzle": "^7.0"
  },
  "autoload": {
    "psr-4": {
      "ExamplePlugin\\": "src/"
    }
  }
}
```


### 4. Document Your Dependencies
Provide clear documentation on the dependencies your plugin uses, including versions. This helps developers assess compatibility with Raydium or other plugins.

## Tools for Dependency Management

The following tools can help plugin authors and developers manage dependency conflicts:

- **[PHP-Scoper](https://github.com/humbug/php-scoper)**: Isolate plugin dependencies with prefixed namespaces.
- **[Composer Merge Plugin](https://github.com/wikimedia/composer-merge-plugin)**: Combine multiple `composer.json` files into a single dependency tree.
- **[Composer Version Checker](https://github.com/mlebkowski/composer-version-check)**: Identify version mismatches in dependencies.


> Raydium’s modular framework, with Composer, provides a robust foundation for WordPress applications. However, dependency conflicts can arise when integrating plugins that use Composer independently. Developers should evaluate plugins carefully, and plugin authors should adopt dependency isolation and optimization strategies. By following these practices, compatibility issues can be minimized, ensuring a stable and efficient WordPress environment powered by Raydium.
