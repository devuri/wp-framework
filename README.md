# WPFramework for Raydium

> This repository contains the foundational core framework for Raydium. If you're developing an application using the wpframework, use the pre-built version available at [Raydium](https://github.com/devuri/raydium/).

<div align="center">

[![Unit Tests](https://github.com/devuri/wpframework/actions/workflows/unit-tests.yml/badge.svg)](https://github.com/devuri/wpframework/actions/workflows/unit-tests.yml) [![Static Analysis](https://github.com/devuri/wpframework/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/devuri/wpframework/actions/workflows/static-analysis.yml)

</div>

WPframework is a composer package that serves as the core framework for Raydium. Designed to provide secure and modular WordPress development, The framework equips developers with a solid, flexible foundation for crafting scalable **single** or **multi-tenant** web applications.


## Prerequisites

Before you begin the installation process, ensure you have the following prerequisites:

**PHP**: Raydium requires PHP version 7.4 or higher.

**Composer**: Raydium uses Composer for dependency management. 

**MySQL or MariaDB Database**: You'll need the database credentials during the WordPress setup.

**Web Server**: Any standard web server like Apache or Nginx.

**Command Line Access**: You'll need terminal or command line access to execute Composer commands.

> [!CAUTION]
> The core framework is designed to be used with Raydium. While it's possible to use it as-is, it's generally recommended to utilize one of the pre-built versions [here](https://github.com/devuri/raydium/).

## Installation

### Create a New Raydium Project

Start by creating a new Raydium project using Composer. Open your terminal or command line tool and run the following command:

```bash
composer create-project devuri/raydium your-project-name
```

## Documentation

Explore the extensive [Raydium Documentation](https://devuri.github.io/wpframework/) to learn about its installation, configuration, and the features it offers. The documentation includes detailed guides, API references, and best practices to help you maximize your use of Raydium.


## Support

Should you encounter any issues or have questions about the framework, don't hesitate to open an issue on our GitHub repository.


> [!WARNING]  
> 🛠️ **Work in Progress**  
> This project is currently in the `0.x` phase of development, and we’re actively refining and enhancing it. While it’s fully usable, please be aware that this is a **pre-release** version, and as such, features, APIs, and behavior are subject to change as we work toward the `1.0` milestone.  
>  
> ⚠️ **Breaking Changes**: Updates during this phase may include breaking changes as we iterate and improve.  
>  
> 💡 **Your Feedback Matters**: Contributions and feedback are not only welcome but vital during this stage. By sharing your thoughts and ideas, you can directly help shape the future of the project as we aim for a stable and robust `1.x` release.  
>  
> 🚀 **Jump In**: Try it out and let us know how it works for you—we’re building this together!



## License

Licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
