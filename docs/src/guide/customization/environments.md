# **Environment File**

The Raydium Framework uses environment files (e.g., `env`, `.env`, `.env.local`, etc.) to configure settings for different deployment contexts, like: development, staging, production, and beyond. These files let you isolate environment-specific configurations and keep them organized.

## **Naming Conventions**

Regardless of name, all environment files serve the same essential purpose: initializing the Raydium Framework according to the environment in which they run. The names (e.g., `.env.dev`, `.env.prod`) are primarily for your own reference and do not impose any special content rules.

> The naming convention is a practical convenience: it helps you and your team identify which file belongs to which environment, but the framework itself doesn’t enforce different “types” of content per file name.

## **Supported File Names & Common Uses**

1. **`env` or `.env`**  
   Often used as a generic or “base” environment file, sometimes for production.

2. **`.env.local` or `env.local`**  
   Stores local development overrides. Typically includes DB credentials, debugging flags, or other machine-specific details. Usually excluded from version control.

3. **`.env.dev`**  
   Focuses on development setups with extra logging, debugging, or testing configurations.

4. **`.env.staging`**  
   Mirrors production closely but may allow for final checks or partial debug output.

5. **`.env.prod`**  
   Optimized for production: security, performance, caching, minimal debug.

6. **`.env.debug`**  
   Used during intense troubleshooting sessions needing verbose logging or error displays.

7. **`.env.secure`**  
   Suggests stricter security settings, but it’s simply another file in the load order.

## **Loading Order & Precedence**

By default, the framework searches for environment files in this order, using **the first one it finds**:
1. **`env`**
2. **`.env`**
3. **`.env.secure`**
4. **`.env.prod`**
5. **`.env.staging`**
6. **`.env.dev`**
7. **`.env.debug`**
8. **`.env.local`**

Once a file is found, it’s loaded, and **the search stops**—the framework does not merge or proceed to the next. This straightforward approach ensures only one file is ever in control, preventing conflicts between multiple `.env` variants.

**Example**  
- If you have both a `env` file and a `.env.local` file, but `env` is discovered first, the framework will stop there. It won’t look for `.env.local`.  
- Conversely, if you remove or rename `env` so that `.env.local` appears first in the search order, then `.env.local` will be loaded.

## **Loading Behavior Examples**

- **Local Development**  
  You might rename or remove the default `env` or `.env` file so the framework proceeds until it finds `.env.local`, ensuring local overrides are used.
- **Production**  
  Typically keep a single `env` or `.env.prod` file. Because it appears earlier in the search sequence than `.env.local`, it will be loaded first if present.

## **Recommendations & Best Practices**

- **Version Control**  
  You can include a generic `env` file in your repo for reference, but exclude environment-specific ones (like `.env.local`, `.env.prod`) to avoid leaking sensitive data.
- **Clarity & Consistency**  
  Maintain consistent structure and variable naming across all your environment files. This reduces confusion when switching between them.
- **Documentation**  
  Let your team know which file your environment expects. For instance, “Use `.env.local` for your local DB credentials, remove or rename `env` if you want to load `.env.local`.”

> By using a simple, first-file-wins loading strategy, Raydium provides a clear, unambiguous way to manage environment-specific configurations without the complexity of merging multiple files. This ensures each environment (development, staging, production) can be fully configured via a single, uniquely named file.


---


# Environment Configuration (`.env`)

This file defines environment-specific values for your project. It is typically placed at the root of your application and **excluded from version control** for security reasons.

> By tailoring each variable, you can optimize the application for local development, staging, or production environments.

### Auto-Generation  
The framework can auto-generate this file on first boot. You may see an error screen initially; after clicking “retry,” the framework should create a new `.env` file. It will always generate one if it doesn’t find one.  

### Multi-Tenant Note
If you’re running a multi-tenant setup, each tenant might have its own `.env` in a directory named after the tenant UUID, for example:  
```
configs/{tenant-uuid}/.env
```

## **How to Use**
1. **Create/Edit `.env`:** If you don’t already have a `.env` in your project root (or tenant directory for multi-tenant), the framework may generate one automatically. Otherwise, you can copy a sample file and rename it to `.env`.
2. **Update Values:** Replace default placeholders with settings matching your local or production environment, such as database credentials, site URLs, and security keys.
3. **Environment-Specific:** Adjust `ENVIRONMENT_TYPE` and any other environment-based variables as needed for local, staging, or production deployments.
4. **Security:** `.env` contains sensitive information—never commit it to public repositories.

## **URL & Site Settings**
| Variable          | Example                        | Description                                                                            |
|-------------------|--------------------------------|----------------------------------------------------------------------------------------|
| `HOME_URL`        | `https://domain1.local`        | The base URL of your site. Used to construct other URLs like `WP_SITEURL`.             |
| `WP_SITEURL`      | `${HOME_URL}/wp`               | Full WordPress admin URL; often `HOME_URL + /wp`.                                      |
| `ADMIN_LOGIN_URL` | `${HOME_URL}/wp/wp-login.php`   | Direct URL for the WordPress login page.                                              |
**Notes**  
Changing `HOME_URL` affects internal references. `WP_SITEURL` typically appends `/wp` to `HOME_URL` for cleaner separation of WP core files.

## **Environment & Debugging**
| Variable                      | Example   | Description                                                                     |
|-------------------------------|-----------|---------------------------------------------------------------------------------|
| `ENVIRONMENT_TYPE`            | `prod`    | Controls the environment type (`prod`, `dev`, `staging`, `debug`, `secure`, etc.). |
| `WP_DEVELOPMENT_MODE`         | `theme`   | WordPress development mode (`theme`, `plugin`, `core`).                        |
| `DISABLE_WP_APPLICATION_PASSWORDS` | `true`   | If `true`, disables Application Passwords.                                      |
**Notes**  
`ENVIRONMENT_TYPE` is key to how the framework configures caching, debug logs, and file mods. `WP_DEVELOPMENT_MODE` refines WordPress dev behavior.

## **Administrative Access**
| Variable            | Example | Description                                                        |
|---------------------|---------|--------------------------------------------------------------------|
| `SUDO_ADMIN`        | `1`     | User ID of the “super admin.”                                      |
| `BASIC_AUTH_USER`   | `admin` | Username for basic HTTP auth.                                      |
| `BASIC_AUTH_PASSWORD` | `demo` | Password for basic HTTP auth.                                      |
**Notes**  
`SUDO_ADMIN` defines a top-tier admin beyond the standard roles. `BASIC_AUTH_*` is often used to protect staging or dev sites behind a login prompt.

## **Tenant & Multisite**
| Variable               | Example | Description                                                     |
|------------------------|---------|-----------------------------------------------------------------|
| `APP_TENANT_ID`        | `null`  | Identifier for a tenant in multi-tenant setups.                 |
| `IS_MULTI_TENANT_APP`  | `false` | If `true`, the application supports multiple tenant instances.  |
**Notes**  
Leave these at default if your project is not multi-tenant. Otherwise, each tenant may hold its own `.env` file.

## **Email & Premium Plugins**
| Variable                      | Example           | Description                                                                |
|-------------------------------|-------------------|----------------------------------------------------------------------------|
| `SEND_EMAIL_CHANGE_EMAIL`     | `false`           | Toggles sending email-change notifications.                                |
| `SENDGRID_API_KEY`            | (empty)          | API key for SendGrid if using SendGrid for sending emails.                 |
| `ELEMENTOR_PRO_LICENSE`       | (empty)          | License key for Elementor Pro.                                             |
| `ELEMENTOR_AUTO_ACTIVATION`   | `true`           | Automatically activate Elementor Pro if `true`.                             |
**Notes**  
Use whichever mail provider’s key your `app.php` configuration references. Elementor settings only matter if you use Elementor Pro.

## **PHP Memory & SSL**
| Variable          | Example  | Description                                                       |
|-------------------|----------|-------------------------------------------------------------------|
| `MEMORY_LIMIT`    | `256M`   | PHP memory limit for WordPress.                                   |
| `MAX_MEMORY_LIMIT`| `512M`   | Max memory limit for tasks like uploads or updates.               |
| `FORCE_SSL_ADMIN` | `false`  | Forces SSL on `/wp-admin` if `true`.                              |
| `FORCE_SSL_LOGIN` | `false`  | Forces SSL on login if `true`.                                    |
**Notes**  
Configure memory based on your hosting constraints. SSL forcing is recommended for production sites over HTTPS.

## **Theming & Backups**
| Variable        | Example | Description                                                       |
|-----------------|---------|-------------------------------------------------------------------|
| `USE_APP_THEME` | `false` | If `true`, enforces a custom “app theme” override.                |
| `BACKUP_PLUGINS`| `false` | Toggles plugin backups in your workflow.                          |
**Notes**  
`USE_APP_THEME` helps if you’re shipping a custom-coded theme. `BACKUP_PLUGINS` is a user-defined toggle to manage plugin backups.

## **S3 Backup Configuration**
| Variable             | Example          | Description                                                                 |
|----------------------|------------------|-----------------------------------------------------------------------------|
| `S3_BACKUP_KEY`      | `null`          | AWS Access Key ID for S3 backups.                                          |
| `S3_BACKUP_SECRET`   | `null`          | AWS Secret Access Key for S3 backups.                                      |
| `S3_BACKUP_DIR`      | `null`          | S3 bucket directory for backups.                                           |
| `ENABLE_S3_BACKUP`   | `false`         | Toggles the S3 backup functionality.                                       |
| `S3ENCRYPTED_BACKUP` | `false`         | Encrypts backups on S3 if `true`.                                          |
| `S3_BACKUP_BUCKET`   | `wp-s3snaps`    | S3 bucket name for backup storage.                                         |
| `S3_BACKUP_REGION`   | `us-west-1`     | S3 region for storing backups.                                             |
| `DELETE_LOCAL_S3BACKUP` | `false`      | Deletes local backup files once uploaded if `true`.                        |
**Notes**  
Set these only if you integrate an S3-based backup strategy.

## **Database Settings**
| Variable       | Example         | Description                                                   |
|----------------|-----------------|---------------------------------------------------------------|
| `DB_NAME`      | `local`         | Database name.                                                |
| `DB_USER`      | `root`          | Database username.                                            |
| `DB_PASSWORD`  | `password`      | Database user’s password.                                     |
| `DB_HOST`      | `localhost`     | Database server hostname/IP.                                  |
| `DB_PREFIX`    | `wp_mcbq1uoa_`  | WordPress table prefix.                                       |
| `DB_CHARSET`   | `'utf8'`        | Character set for the database.                               |
| `DB_ROOT_PASS` | `nkouwqaebfgr3lbn` | Root password, often used in local Docker setups.               |
**Notes**  
For production, secure these credentials carefully. `DB_ROOT_PASS` is typically not used in production setups.

## **Security Keys & Salts**
```
AUTH_KEY
SECURE_AUTH_KEY
LOGGED_IN_KEY
NONCE_KEY
AUTH_SALT
SECURE_AUTH_SALT
LOGGED_IN_SALT
NONCE_SALT
```
Each should be unique and random. These constants secure sessions, cookies, and more. These will also be generated on first boot if no .env file exists.

## **Additional Secrets**
| Variable                 | Description                                             |
|--------------------------|---------------------------------------------------------|
| `ADMINER_SECRET`         | Key used for signing or generating Adminer tokens/URLs.|
| `AUTO_LOGIN_SECRET_KEY`  | Key used for autologin or bypass links if enabled.     |
| `APP_TENANT_SECRET`      | Multi-tenant secret for tenant-specific operations.    |
**Notes**  
Keep these secrets secure and consider rotating them periodically.

## **Example `.env` File**
```plaintext

HOME_URL='https://domain1.local'
WP_SITEURL="${HOME_URL}/wp"
ADMIN_LOGIN_URL="${HOME_URL}/wp/wp-login.php"
ENVIRONMENT_TYPE='prod'

WP_DEVELOPMENT_MODE='theme'
DISABLE_WP_APPLICATION_PASSWORDS=true

SUDO_ADMIN='1'
APP_TENANT_ID=null
IS_MULTI_TENANT_APP=false

BASIC_AUTH_USER='admin'
BASIC_AUTH_PASSWORD='demo'
SEND_EMAIL_CHANGE_EMAIL=false

SENDGRID_API_KEY=''
ELEMENTOR_PRO_LICENSE=''
ELEMENTOR_AUTO_ACTIVATION=true

MEMORY_LIMIT='256M'
MAX_MEMORY_LIMIT='512M'

FORCE_SSL_ADMIN=false
FORCE_SSL_LOGIN=false
USE_APP_THEME=false

BACKUP_PLUGINS=false
S3_BACKUP_KEY=null
S3_BACKUP_SECRET=null
S3_BACKUP_DIR=null
ENABLE_S3_BACKUP=false
S3ENCRYPTED_BACKUP=false
S3_BACKUP_BUCKET='wp-s3snaps'
S3_BACKUP_REGION='us-west-1'
DELETE_LOCAL_S3BACKUP=false

DB_NAME=local
DB_USER=root
DB_PASSWORD=password
DB_HOST=localhost
DB_PREFIX=wp_mcbq1uoa_
DB_CHARSET='utf8'
DB_ROOT_PASS=

AUTH_KEY=
SECURE_AUTH_KEY=
LOGGED_IN_KEY=
NONCE_KEY=
AUTH_SALT=
SECURE_AUTH_SALT=
LOGGED_IN_SALT=
NONCE_SALT=

ADMINER_SECRET=
AUTO_LOGIN_SECRET_KEY=
APP_TENANT_SECRET=
```

## **Best Practices**
- **Version Control**  
  Make sure `.env` is in `.gitignore` so it isn’t pushed to public repos.
- **Environment Consistency**  
  Maintain similar configs for staging and production, only changing what’s necessary (e.g., DB credentials, URLs).
- **Refresh Keys**  
  Periodically rotate secrets like `AUTH_KEY` or `ADMINER_SECRET`.
- **Local vs. Production**  
  Disable debugging in production (`ENVIRONMENT_TYPE='prod'`) for performance and security.
- **Docker & Automation**  
  If using Docker or CI/CD, set environment variables via container config (e.g., `docker-compose.yml`, GitHub Actions secrets).
