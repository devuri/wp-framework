# Common Constants

### Common Constants Defined by the Framework**

The Raydium Framework automatically sets or overrides a series of constants based on environment variables (found in `.env`) and internal defaults. Below is an overview of the **most common** constants that get defined, their typical usages, and their default values where applicable.

## **1. Environment & Debug Constants**

**`WP_ENVIRONMENT_TYPE`**  
- **Source**: The framework’s internal environment detection or `ENVIRONMENT_TYPE` from `.env`  
- **Usage**: Tells WordPress (and plugins) the current environment (e.g., `production`, `staging`, `development`).  
- **Default**: `production` if environment is not recognized.

**`WP_DEVELOPMENT_MODE`**  
- **Source**: `env('WP_DEVELOPMENT_MODE')`  
- **Usage**: Refines WordPress’s development behavior (e.g., `theme`, `plugin`, or `core`).  
- **Default**: Empty string if not set.

**`DEVELOPER_ADMIN`**  
- **Source**: `env('DEVELOPER_ADMIN')`  
- **Usage**: An additional flag (string or `0`) that the framework can leverage for special developer-specific logic.  
- **Default**: `'0'` if not set in `.env`.


## **2. Site URLs & Assets**

**`HOME_URL`** / **`WP_HOME`**  
- **Source**: `env('HOME_URL')`  
- **Usage**: The home/base URL of the WordPress site.  
- **Default**: No default if not defined (the framework expects `.env` to provide it).

**`WP_SITEURL`**  
- **Source**: `env('WP_SITEURL')`  
- **Usage**: WordPress’s “siteurl” (often `HOME_URL + /wp`).  
- **Default**: None if not defined in `.env`.

**`ASSET_URL`**  
- **Source**: `env('ASSET_URL')`  
- **Usage**: Points to a CDN or external path where assets (CSS, JS, images) are loaded from.  
- **Default**: None if not defined.


## **3. Database Constants**

**`DB_NAME`**  
- **Source**: `env('DB_NAME')`  
- **Usage**: Database name for WordPress.  

**`DB_USER`**  
- **Source**: `env('DB_USER')`  
- **Usage**: Username to connect to the database.  

**`DB_PASSWORD`**  
- **Source**: `env('DB_PASSWORD')`  
- **Usage**: Password for the specified DB user.  

**`DB_HOST`**  
- **Source**: `env('DB_HOST')` or framework default  
- **Default**: `'localhost'` if not defined.  

**`DB_CHARSET`**  
- **Source**: `env('DB_CHARSET')`  
- **Default**: `utf8mb4` if not defined.  

**`DB_COLLATE`**  
- **Source**: `env('DB_COLLATE')`  
- **Default**: `''` (empty) if not defined.

## **4. Security & SSL**

**`FORCE_SSL_ADMIN`**  
- **Source**: `env('FORCE_SSL_ADMIN')` or framework default  
- **Usage**: Forces SSL on `/wp-admin` if `true`.  
- **Default**: `true` (from the snippet’s `ssl_admin` => `true`).

**`FORCE_SSL_LOGIN`**  
- **Source**: `env('FORCE_SSL_LOGIN')` or framework default  
- **Usage**: Forces SSL on the login page if `true`.  
- **Default**: `true` (from the snippet’s `ssl_login` => `true`).

## **5. Memory & Performance**

**`WP_MEMORY_LIMIT`**  
- **Source**: `env('MEMORY_LIMIT')` or framework default  
- **Default**: `'256M'` (from `memory` => `256M`).

**`WP_MAX_MEMORY_LIMIT`**  
- **Source**: `env('MAX_MEMORY_LIMIT')` or framework default  
- **Default**: `'256M'` (same fallback as `WP_MEMORY_LIMIT` unless overridden).

**`CONCATENATE_SCRIPTS`**  
- **Source**: `env('CONCATENATE_SCRIPTS')` or framework default  
- **Usage**: If `true`, WordPress concatenates scripts to reduce requests (common in production).  
- **Default**: `true` (from `optimize` => `true`).


## **6. Autosave & Revisions**

**`AUTOSAVE_INTERVAL`**  
- **Source**: `env('AUTOSAVE_INTERVAL')` or framework default  
- **Usage**: Time (in seconds) between post autosaves.  
- **Default**: `180` (from `autosave` => `180`).

**`WP_POST_REVISIONS`**  
- **Source**: `env('WP_POST_REVISIONS')` or framework default  
- **Usage**: Number of revisions WordPress stores for each post.  
- **Default**: `10` (from `revisions` => `10`).


## **7. Cookie & Login Constants**

**`COOKIEHASH`**  
- **Source**: `md5(env('HOME_URL'))`  
- **Usage**: A hash used to make cookie names unique to each site.  

**`USER_COOKIE`, `PASS_COOKIE`, `AUTH_COOKIE`, `SECURE_AUTH_COOKIE`, `RECOVERY_MODE_COOKIE`, `LOGGED_IN_COOKIE`**  
- **Source**: Appended with `wpx_… + COOKIEHASH`  
- **Usage**: WordPress cookie names used for user sessions, authentication, and recovery.  

**`TEST_COOKIE`**  
- **Source**: `md5('wpx_test_cookie' . env('HOME_URL'))`  
- **Usage**: Cookie used by WordPress to test if cookies are enabled in the browser.


## **8. WordPress Salts & Keys**

**`AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`**  
- **Source**: `env('…')`  
- **Usage**: Standard WordPress security keys and salts for session encryption.  
- **Default**: None. Usually must be set (or automatically generated) in your `.env`.


## **9. Debug & Error Handling**

Typically sets WordPress/PHP debug constants (e.g., `WP_DEBUG`, `WP_DEBUG_DISPLAY`) based on environment (production vs. staging vs. dev).  

- The framework’s “environment switcher” handleS these.  
- For example, production might set `WP_DEBUG` to false, while dev environments might set `WP_DEBUG` to true etc.

## **Default Values Summary (From the Framework internals)**

Below is a quick reference for the built-in defaults as seen in the framework:

- **`environment`**: `'production'`  
- **`debug`**: `true`  
- **`db_host`**: `'localhost'`  
- **`optimize`**: `true` (used for `CONCATENATE_SCRIPTS`)  
- **`memory`**: `'256M'` (both `WP_MEMORY_LIMIT` and `WP_MAX_MEMORY_LIMIT`)  
- **`ssl_admin`**: `true` (for `FORCE_SSL_ADMIN`)  
- **`ssl_login`**: `true` (for `FORCE_SSL_LOGIN`)  
- **`autosave`**: `180` (for `AUTOSAVE_INTERVAL`)  
- **`revisions`**: `10` (for `WP_POST_REVISIONS`)

If the environment is **not** set in `.env`, these defaults are used by the framework.

## **Key Takeaways**

1. **Environment Variables**  
   The framework first checks if an environment variable (e.g., `env('SOME_VAR')`) is set; if it’s not, it falls back to an internal default.  
2. **Production vs. Dev**  
   `ENVIRONMENT_TYPE` determines many behind-the-scenes debug settings. The framework environment “switcher” applies WordPress and PHP debug constants accordingly.  
3. **Security & Consistency**  
   Cookie constants and Salts ensure WordPress sessions are unique and protected. Make sure you have secure, randomly generated salts in your `.env`.  
4. **Customization**  
   You can override any constant in your `.env`. If a variable is missing.  

By defining these constants during bootstrap, the Raydium Framework ensures that WordPress is consistently configured based on your environment and your project’s `.env` file—providing a seamless, flexible approach to both development and production setups.
