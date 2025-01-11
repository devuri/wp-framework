# Getting Started

## Installation

#### Prerequisites

To get started with Raydium, make sure you have the following prerequisites:

- PHP version 7.4 or higher.
- Composer to manage PHP dependencies.
- A MySQL or MariaDB database.
- Terminal access to execute Raydium and other related commands.
- A text editor or IDE that supports PHP and WordPress development, such as [VSCode](https://code.visualstudio.com/) with appropriate extensions.


To integrate Raydium into your WordPress project, you have several options, depending on your specific needs and preferences. Raydium provides three distinct setups via Composer, each serving different purposes and project requirements:

- **Raydium**: Core framework with essential features for secure and scalable WordPress development.
- **RaydiumX**: Extended template with more opinionated setups for a comprehensive starting point.
- **RaydiumXE**: Minimal version for a lightweight and highly customizable foundation.


  > Each option offers different levels of configuration and customization.

#### Raydium

[Raydium](https://github.com/devuri/radium/)  offers a solid foundation for developing secure, scalable, and modular WordPress applications. Use the following Composer command to create a new project with the core framework:

```shell
composer create-project devuri/raydium your-project-name
```

#### RaydiumX

[RaydiumX](https://github.com/devuri/radiumx/)  is an extended template that includes more opinionated setups, incorporating additional tooling, configurations.
Install RaydiumX with the following Composer command:

```shell
composer create-project devuri/raydiumx your-project-name
```

#### RaydiumXE

[RaydiumXE](https://github.com/devuri/raydiumxe)  is a minimal version of Raydium, providing a stripped-down base to start from scratch. This option is ideal for keeping things simple and build upon a minimal setup, adding only the components necessary. You can install RaydiumXE with this command:

```shell
composer create-project devuri/raydiumxe your-project-name
```

> After the installation, you can start testing Raydium locally by using this command:

```shell
php -S localhost:8000 -t public -c .user.ini
```


## Setup

After installing Raydium, proceed by navigating to your project directory to configure your environment:

```bash
cd your-project-name
```

You'll need to adjust the `.env` [environment file](../customization/environment-file) to align with your database settings and site URL. Ensure the `HOME_URL` variable accurately reflects your site's URL.

## File Structure

Raydium provides an optimized file structure tailored for a modular and secure approach to development. It helps you maintain a clean and organized codebase, with a clear separation between the core, vendor libraries, and publicly accessible resources. Here's an overview of the default project file structure in Raydium:

```
├── .env
├── composer.json
├── vendor/                   # Composer dependencies
└── public/
    ├── index.php
    ├── wp-config.php
    ├── wp/                   # WordPress core
    └── wp-content/           # Equivalent to wp-content
        ├── mu-plugins/       # Must-Use plugins
        ├── plugins/          # Regular plugins
        └── themes/           # Themes

```

Key Components:
- `.env`: Located at the root, away from the public directory, this file stores environment variables like database credentials and API keys, keeping sensitive information out of public access.
- `composer.json`: Defines dependencies and autoload settings managed by Composer.
- `wp/`: Contains the WordPress core files. This separation ensures core files are not mixed with content or configuration files.
- `public/`: Serves as the web root directory of your application. It contains the WordPress core and the content directory, which are exposed to the web.
  - `wp-content/`: Replaces the standard `wp-content` directory in WordPress. It is structured to hold your themes and plugins, segregating them from core files.
    - `mu-plugins/`: Houses plugins that are always activated by default and cannot be disabled through the WordPress dashboard.
    - `plugins/`: Contains the installable plugins that can be activated or deactivated as needed.
    - `themes/`: Stores your WordPress theme directories.
- `vendor/`: Managed by Composer, this directory is safely placed outside the public scope. It includes all PHP dependencies and libraries required by your project.

This structure reinforces security by minimizing exposure of critical files and simplifies maintenance by providing a logical organization for your application's components.

## The Config File

In Raydium, the primary configuration is handled through the `.env` file, offering a more secure and flexible way to manage settings compared to WordPress's traditional `wp-config.php`.

```shell
# Sample .env file content
HOME_URL='https://yourdomain.com'
WP_SITEURL="${HOME_URL}/wp"

# Setting the environment
ENVIRONMENT_TYPE='sec'

DB_NAME='your_db_name'
DB_USER='your_db_user'
DB_PASSWORD='your_db_password'
DB_HOST='localhost'
```
> [!IMPORTANT]
> The `ENVIRONMENT_TYPE` setting is a crucial setting that defines different [states of the application](../customization/environments) such as development, staging, or production. Each state has its own specific configurations and behaviors. You can find more details [here](../customization/environments).
>
> You can also override the `ENVIRONMENT_TYPE` setting by using the `RAYDIUM_ENVIRONMENT_TYPE` constant. To do this, set the `RAYDIUM_ENVIRONMENT_TYPE` in the `wp-config.php` file.

## Source Files

Themes and plugins will reside within the `public/wp-content/themes` and `public/wp-content/plugins` directories.

## Up and Running

To launch your Raydium-based WordPress site, ensure your web server (such as Apache or Nginx) is correctly configured to serve your project directory. Then, visit your site's URL to go through the WordPress installation and initial setup.

## What's Next?

- Dive deeper into the functionalities and features of Raydium by exploring its documentation.
- Get acquainted with the modular design principles of Raydium to efficiently extend and customize it.
- Connect with the Raydium community for additional support, insights, and to share your experiences and best practices.
