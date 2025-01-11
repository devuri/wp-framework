# Installation Guide for Raydium

Welcome to the installation guide, the framework is designed to enhance WordPress development. This guide will walk you through the prerequisites, installation steps, and initial configuration to get your Raydium-powered WordPress site up and running.

## Prerequisites

Before you begin the installation process, ensure you have the following prerequisites:

1. **PHP**: Raydium requires PHP version 7.4 or higher. Verify your PHP version using the command: `php -v`.

2. **Composer**: Raydium uses Composer for dependency management. Install Composer from [getcomposer.org](https://getcomposer.org/download/) if you haven't already.

3. **MySQL or MariaDB Database**: Ensure you have access to a MySQL or MariaDB database. You'll need the database credentials during the WordPress setup.

4. **Web Server**: Any standard web server like Apache or Nginx capable of serving PHP applications. Make sure it's configured to serve the `public` directory of your Raydium project as the web root.

5. **Command Line Access**: You'll need terminal or command line access to execute Composer commands.

## Installation Steps

### Step 1: Create a New Raydium Project

Start by creating a new Raydium project using Composer. Open your terminal or command line tool and run the following command:

```bash
composer create-project devuri/raydium your-project-name
```

Replace `your-project-name` with your desired directory name for the new project. This command downloads the Raydium framework and sets up a new WordPress project in the specified directory.

### Step 2: Configure Your Environment

Navigate to your project directory:

```bash
cd your-project-name
```

Within this directory, you'll find an `.env` [environment file](../customization/environment-file). Open this file in your text editor and configure the following settings:

- `HOME_URL`: Set this to your site's URL.
- `WP_SITEURL`: This will be your WordPress core directory URL, typically `HOME_URL` appended with `/wp`.
- `DB_NAME`: The name of your database.
- `DB_USER`: Your database username.
- `DB_PASSWORD`: Your database password.
- `DB_HOST`: Your database host, usually `localhost`.

Example `.env` configuration:

```dotenv
HOME_URL='http://yourdomain.com'
WP_SITEURL="${HOME_URL}/wp"
DB_NAME='your_db_name'
DB_USER='your_db_user'
DB_PASSWORD='your_db_password'
DB_HOST='localhost'
```

### Step 3: Serve Your Project

Configure your web server (Apache, Nginx, etc.) to serve the `public` directory of your project as the web root. This setup ensures that the web server correctly serves the WordPress site managed by Raydium.

> [!IMPORTANT]
> For Apache, ensure you have an `.htaccess` file within the `public` directory with the necessary rewrite rules. For Nginx, configure your site's server block to point the root to the `public` directory.


#### Local Server Setup

To serve your Raydium-powered WordPress application locally, use PHP's built-in server by executing the following command:

```shell
php -S localhost:8000 -t public -c .user.ini
```

Here's what each part does:
- `php -S localhost:8000`: Starts the PHP built-in server on localhost at port 8000.
- `-t public`: Sets the document root to the `public` directory.
- `-c .user.ini`: Uses the `.user.ini` file for PHP configuration settings.

#### Access Your Project

Open a web browser and navigate to `http://localhost:8000`. You should see your new Raydium project running.

> Remember, this local setup is ideal for development and testing but NOT suitable for a production environment.

### Step 4: Complete WordPress Setup

Once your web server is serving the Raydium project, navigate to your site's URL in a web browser. You should be greeted with the WordPress installation wizard. Follow the steps to select a language, set up your WordPress admin account, and complete the installation.

> [!TIP]
> In Raydium, the `public` directory serves as the default document root, housing the main entry point and other publicly accessible assets of your application. Recognizing the need for flexibility, Raydium allows you to customize this setup by altering the document root to better fit your project’s requirements or security measures.

## Change the Public Directory
Modifying the public directory can be essential for various reasons, including:

1. **Enhanced Security**: Adjusting the location of your public directory can help protect sensitive files and directories from unauthorized public access, bolstering your application's security.

2. **Organizational Preferences**: Different organizations may have unique preferences for how they structure their projects. Changing the public directory can align the project structure with these organizational standards.

3. **Integration Needs**: Adjusting your directory structure might be necessary to accommodate the integration with other systems or services, particularly when these systems impose specific directory requirements.

### Steps to Change the Public Directory

Changing the public directory in Raydium involves several clear steps:

1. **Select a New Directory**: Identify a suitable name and location within your project for the new public directory.

2. **Create or Modify the Directory**: If it does not already exist, establish the new directory at the chosen location. Transfer necessary files such as `index.php`, assets, and `.htaccess` (for Apache servers) from the existing `public` directory to the new one.

3. **Update the Composer Configuration**:
    - Adjust the `wordpress-install-dir` in your `composer.json` file to the new directory path.
    - Update the `installer-paths` to reflect changes in the directory for themes, plugins, and must-use plugins. For example:
      ```json
      "extra": {
          "wordpress-install-dir": "webroot/wp",
          "installer-paths": {
              "webroot/content/mu-plugins/{$name}/": ["type:wordpress-muplugin"],
              "webroot/content/plugins/{$name}/": ["type:wordpress-plugin"],
              "webroot/content/themes/{$name}/": ["type:wordpress-theme"]
          }
      }
      ```

4. **Adjust Application Configuration**: Update  [configurations](../reference/configuration) in your `app.php` or any relevant configuration files to reflect the new directory structure. For instance, change the `web_root` path:
    ```php
    'directory' => [
        'web_root_dir' => 'webroot', // previously 'public'
        ...
    ],
    ```

5. **Reconfigure the Web Server**: Depending on your setup (Apache, Nginx, built-in PHP server), update the web server configuration to point to the new public directory.

6. **Test the Changes**: After making these updates, thoroughly test your application to ensure everything functions correctly. Verify that the server accurately serves the pages and that all resources load properly.

> [!NOTE]
> You can tailor the directory structure of your Raydium project to meet your specific needs, while maintaining its full functionality and enhancing its security. This flexibility is one of the many features that make Raydium a robust framework for developing scalable and secure WordPress applications.


## What's Next?

After completing the installation, you're ready to start developing with Raydium. Explore the Raydium documentation to learn about its features, including modular architecture, enhanced security, and streamlined development workflow.

Happy developing!
