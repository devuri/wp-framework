# Environment Variables

## Overview

This [environment file](../customization/environment-file) contains the environment variables used to configure the settings for your website. The variables define various aspects such as database connections, security settings, backup options, and more. Please review and modify the values accordingly to suit your specific needs.

## Instructions

1. In your project directory, create a file named `.env`.
2. Populate your `.env` file with the contents from this template.
3. Substitute the placeholder values with your actual configuration details to align with your environment and security needs.

```shell
# Core WordPress Settings
HOME_URL='http://yourwebsite.com'
WP_SITEURL="${HOME_URL}/wp"

# Basic Authentication Credentials
BASIC_AUTH_USER='yourUsername'
BASIC_AUTH_PASSWORD='yourStrongPassword'

# Theme and Environment Configuration
USE_APP_THEME=false
ENVIRONMENT_TYPE='production'  # Options: development, staging, production

# Plugin and Backup Preferences
BACKUP_PLUGINS=false

# Email and Communication Settings
SEND_EMAIL_CHANGE_EMAIL=false
SENDGRID_API_KEY='yourSendgridApiKey'

# Administrative Settings
SUDO_ADMIN='0'  # 1 for enabled, 0 for disabled

# Security Keys and Tokens
WPENV_AUTO_LOGIN_SECRET_KEY='yourAutoLoginSecretKey'
WEB_APP_PUBLIC_KEY='yourWebAppPublicKey'

# Premium Feature Keys
ELEMENTOR_PRO_LICENSE='yourElementorProLicenseKey'
AVADAKEY='yourAvadaThemeLicenseKey'

# Performance Settings
MEMORY_LIMIT='256M'
MAX_MEMORY_LIMIT='256M'

# SSL Enforcement for Security
FORCE_SSL_ADMIN=false
FORCE_SSL_LOGIN=false

# Amazon S3 Backup Configuration
ENABLE_S3_BACKUP=false
S3_BACKUP_KEY='yourS3AccessKey'
S3_BACKUP_SECRET='yourS3SecretKey'
S3_BACKUP_BUCKET='yourS3BucketName'
S3_BACKUP_REGION='yourS3BucketRegion'
S3_BACKUP_DIR='yourS3BackupDirectory'
DELETE_LOCAL_S3BACKUP=false
S3ENCRYPTED_BACKUP=false

# Database Connection Details
DB_NAME='yourDatabaseName'
DB_USER='yourDatabaseUsername'
DB_PASSWORD='yourDatabasePassword'
DB_HOST='yourDatabaseHost'
DB_PREFIX='wp_'
```

## Configuration Details

### WordPress Core Settings

- `HOME_URL` & `WP_SITEURL`: Define your website's home URL and the location of WordPress core files, respectively.

### Authentication

- `BASIC_AUTH_USER` & `BASIC_AUTH_PASSWORD`: Credentials for HTTP basic authentication, protecting your site during development or staging.

### Site Appearance and Mode

- `USE_APP_THEME`: Toggle to use a custom theme. `false` disables this feature.
- `ENVIRONMENT_TYPE`: Specify the environment, such as `production`, `development`, or `staging` etc.

### Email Communication

- `SEND_EMAIL_CHANGE_EMAIL`: Enable or disable email notifications for email address changes.
- `SENDGRID_API_KEY`: The API key for SendGrid service, used for outbound emails.

### Admin Privileges

- `SUDO_ADMIN`: Grant a user super admin privileges with user_id (`1`) .

### Security and Keys

- `WEB_APP_PUBLIC_KEY` & `WPENV_AUTO_LOGIN_SECRET_KEY`: Public keys and secret tokens for convenience.

### Premium Plugins

- Include license keys for premium themes and plugins, such as `ELEMENTOR_PRO_LICENSE` and `AVADAKEY`.

### Performance and Resources

- `MEMORY_LIMIT` & `MAX_MEMORY_LIMIT`: Define memory allocations for your site's operations.

### SSL Configuration

- `FORCE_SSL_ADMIN` & `FORCE_SSL_LOGIN`: Enforce SSL for admin and login pages to secure credentials and sessions.

### Backup Options

- Configure Amazon S3 backups with `ENABLE_S3_BACKUP`, access keys, bucket details, and backup preferences.

### Database Settings

- Database connection configurations, including `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_HOST`, and `DB_PREFIX`.

## Important Note

Ensure the `.env` file is securely stored and not publicly accessible, as it contains sensitive information crucial for your website's operation and security. Regularly back up this file and your database to prevent data loss or configuration issues.

## Automatic Initialization

In many scenarios, the Raydium framework is designed to streamline the setup process of your WordPress environment by automatically generating the `.env` file if it doesn't already exist. This automation is a part of Raydium's effort to enhance developer experience and efficiency, ensuring that the essential configurations are set up right from the get-go.

When the framework creates the `.env` file, it doesn't just create an empty file. It intelligently populates it with default settings that are essential for a standard WordPress setup. Among these automatically filled values are the WordPress security keys and salts. These are crucial for securing your WordPress installation, as they ensure better encryption of user data and session information.

The security keys and salts are generated using the WordPress API, which provides uniquely generated values each time they're requested. This means that the values provided in your `.env` file are unique to your installation, enhancing the security of your site by preventing any unauthorized access that could result from using default or predictable keys.

Here's what typically happens during this automatic setup:

1. **Initialization**: Upon detecting the absence of an `.env` file during the setup or deployment process, Raydium triggers an automatic initial `.env` file.

2. **File Creation**: The framework creates a new `.env` file within your project's root directory or `{tenant-config}` directory, ensuring it's placed correctly for configuration management.

3. **Auto-Population**: Raydium then populates the file with a set of predefined variables. These include environment-specific settings, database connection parameters (with placeholders), and crucially, the security keys and salts fetched from the WordPress API.

4. **Customization**: While the auto-generated file includes sensible defaults and unique security keys, it's expected that you'll review and customize other settings like `HOME_URL`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` etc to match your specific project and environment.

This feature is particularly helpful in scenarios where quick deployment or testing is needed, and manual configuration of every detail could be time-consuming or prone to errors.

However, it's always recommended to review the auto-generated `.env` file to ensure all settings are correctly aligned with your project's requirements and security standards. Remember, while the framework handles the initial heavy lifting, fine-tuning and securing your configuration is key to a robust environment.
