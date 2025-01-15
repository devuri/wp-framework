# **Framework Constants**

## Constants & Environment Switching

The Raydium Framework defines and overrides a variety of constants to customize behavior in different environments (development, staging, production, secure, debug). This happens through two main components:

1. A **Constant Builder** that sets or overrides constants based on environment variables and default values.
2. An **Environment Switcher** (the `Switcher`) that applies environment-specific debug, caching, and security settings.

Below is an overview of the most common constants that get defined, their typical defaults, and how the environment switcher enables different runtime behaviors—especially for debugging.


## **1. Core Constants & Defaults**

These constants are set by the framework at bootstrap. The framework checks for a matching environment variable in `.env`, and if not found, it often falls back to an internal default.

| Constant                    | Source / Default             | Description                                                                                                   |
|----------------------------|------------------------------|---------------------------------------------------------------------------------------------------------------|
| **`WP_ENVIRONMENT_TYPE`**  | `env('ENVIRONMENT_TYPE')`    | Indicates the environment (e.g., `production`, `staging`, `development`). Defaults to `production` if invalid. |
| **`WP_DEVELOPMENT_MODE`**  | `env('WP_DEVELOPMENT_MODE')` | Tells WordPress if you’re developing themes, plugins, or core (e.g., `theme`, `plugin`).                      |
| **`HOME_URL`, `WP_HOME`**  | `env('HOME_URL')`            | The site’s base URL. No internal default—intended to be specified in `.env`.                                  |
| **`WP_SITEURL`**           | `env('WP_SITEURL')`          | WordPress’s “siteurl.” Often `HOME_URL + /wp`.                                                                |
| **`ASSET_URL`**            | `env('ASSET_URL')`           | Points to a CDN or external path for loading static assets (CSS, JS).                                         |
| **`DEVELOPER_ADMIN`**      | `env('DEVELOPER_ADMIN')`     | A custom toggle (string) for developer-specific logic. Defaults to `'0'`.                                     |

### **Database Constants**
| Constant       | Source / Default      | Description                                    |
|----------------|-----------------------|------------------------------------------------|
| **`DB_NAME`**  | `env('DB_NAME')`     | Database name.                                 |
| **`DB_USER`**  | `env('DB_USER')`     | Database username.                             |
| **`DB_PASSWORD`** | `env('DB_PASSWORD')` | Database password.                              |
| **`DB_HOST`**  | `env('DB_HOST')` or `'localhost'` | Database host, defaults to `'localhost'`.       |
| **`DB_CHARSET`**| `env('DB_CHARSET')` or `utf8mb4` | Character set, defaults to `utf8mb4`.           |
| **`DB_COLLATE`**| `env('DB_COLLATE')` or `''`      | Collation, defaults to empty string.            |

### **Security & SSL**
| Constant             | Source / Default | Description                                      |
|----------------------|------------------|--------------------------------------------------|
| **`FORCE_SSL_ADMIN`**| `env('FORCE_SSL_ADMIN')` or `true` | Forces SSL in `/wp-admin`. Defaults to `true`.    |
| **`FORCE_SSL_LOGIN`**| `env('FORCE_SSL_LOGIN')` or `true` | Forces SSL in login forms. Defaults to `true`.    |

### **Memory & Performance**
| Constant                | Source / Default | Description                                                |
|-------------------------|------------------|------------------------------------------------------------|
| **`WP_MEMORY_LIMIT`**   | `env('MEMORY_LIMIT')` or `256M` | PHP memory limit for WordPress. Defaults to `256M`.        |
| **`WP_MAX_MEMORY_LIMIT`**| `env('MAX_MEMORY_LIMIT')` or `256M` | Max memory for bigger tasks, also defaults to `256M`.  |
| **`CONCATENATE_SCRIPTS`**| `env('CONCATENATE_SCRIPTS')` or `true` | If `true`, WordPress concatenates scripts (production). |

### **Autosave & Revisions**
| Constant              | Source / Default | Description                                                      |
|-----------------------|------------------|------------------------------------------------------------------|
| **`AUTOSAVE_INTERVAL`**| `env('AUTOSAVE_INTERVAL')` or `180` | Time (in seconds) between autosaves, default `180`.              |
| **`WP_POST_REVISIONS`**| `env('WP_POST_REVISIONS')` or `10`   | Number of revisions WordPress will store, default `10`.          |

### **Cookie & Login Names**
| Constant               | Source                          | Description                                                            |
|------------------------|----------------------------------|------------------------------------------------------------------------|
| **`COOKIEHASH`**       | `md5(env('HOME_URL'))`          | Unique hash for cookie names.                                          |
| **`USER_COOKIE`**, etc.| Derived from `COOKIEHASH`        | Creates cookie prefixes like `wpx_user_...`, `wpx_auth_...`, etc.      |
| **`TEST_COOKIE`**      | `md5('wpx_test_cookie' + HOME_URL)` | Used by WP to test if cookies are enabled in the browser.              |

### **WordPress Salts & Keys**
| Constant                | Source                       | Description                                         |
|-------------------------|------------------------------|-----------------------------------------------------|
| **`AUTH_KEY`**, **`LOGGED_IN_KEY`**, etc. | `env('AUTH_KEY')`, etc. | Standard WP security keys for session encryption.   |
| **Default**            | *None*                       | Usually must be set in `.env` or auto-generated.    |

---

## **2. Environment Switcher**

Beyond setting constants directly from `.env`, the framework also **switches** to environment-specific configurations. The `Switcher` adjusts debugging, caching, and security constants based on your environment.

Each environment (`production`, `secure`, `staging`, `debug`, `development`) sets additional WordPress/PHP constants or `ini_set` values. For instance:

### **Production**
- **`DISALLOW_FILE_EDIT`**: `true` (no theme/plugin editor)
- **`SCRIPT_DEBUG`**: `false` (use minified scripts)
- **`WP_DEBUG`**: Often `false` unless an error log directory is set, in which case `true` + `WP_DEBUG_LOG`
- **`WP_CACHE`**: `true` (if `SWITCH_OFF_CACHE` is not defined or not `true`)
- **`CONCATENATE_SCRIPTS`**: `true`
- **`COMPRESS_SCRIPTS`**: `true`
- **`COMPRESS_CSS`**: `true`

### **Secure**
- Similar to production but more restrictive:
  - **`DISALLOW_FILE_MODS`**: `true` (no updates, installs)
  - **`DISALLOW_FILE_EDIT`**: `true`
  - **`WP_DEBUG`**: `true` if an error log path is set, else `false`
  - **`EMPTY_TRASH_DAYS`**: `10` (quicker trash cleanup)

### **Staging**
- **`DISALLOW_FILE_EDIT`**: `false` (allow limited file editing)
- **`WP_DEBUG`**: `true`
- **`SAVEQUERIES`**: `true`
- **`SCRIPT_DEBUG`**: `false` (still minified scripts)
- **`WP_DEBUG_DISPLAY`**: `true` but `display_errors` is set to `'0'` (errors not shown publicly)

### **Development**
- **`WP_DEBUG`**: `true`
- **`SAVEQUERIES`**: `true`
- **`WP_DISABLE_FATAL_ERROR_HANDLER`**: `true`
- **`SCRIPT_DEBUG`**: `true` (unminified scripts)
- **`ini_set('display_errors', '1')`** (show errors in the browser)

### **Debug**
- **`WP_DEBUG`**: `true`
- **`WP_DEBUG_DISPLAY`**: `true`
- **`CONCATENATE_SCRIPTS`**: `false` (make debugging scripts easier)
- **`SAVEQUERIES`**: `true`
- **`error_reporting(E_ALL)`**, **`ini_set('display_errors','1')`**, etc.
- **`EMPTY_TRASH_DAYS`**: `50` (retain trash for a longer period)

**Note:**  
For `debug` or `deb` environment, the framework sets extremely verbose PHP error reporting and ensures WP logs most queries, making it easier to diagnose problems.

## **How Debug Is Set Up**

When you specify `debug`, `deb`, or `local` as your environment:

**Key Steps**  
1. **`WP_DEBUG = true`**: Enables WordPress debugging mode.  
2. **`CONCATENATE_SCRIPTS = false`**: Ensures scripts aren’t combined, making it easier to trace errors or conflicts.  
3. **`SAVEQUERIES = true`**: Logs all database queries for analysis (potential performance overhead).  
4. **PHP `error_reporting(E_ALL)`**: Captures all possible PHP errors, warnings, and notices.  
5. **`ini_set('display_errors', '1')`**: Displays errors in the browser for immediate visibility.  
6. **`WP_DEBUG_LOG`**: Points to an error log file if provided, otherwise `true` (log to default location).

This environment is ideal for local development or debugging sessions where you want to see every possible error or notice.


## **Putting It Together**

1. **`.env` Variables**: Define core environment variables, e.g., `ENVIRONMENT_TYPE=debug`, `HOME_URL`, `DB_NAME`, etc.  
2. **Bootstrap**: The framework loads these environment variables, applying defaults if needed.  
3. **Switcher**: Depending on `ENVIRONMENT_TYPE`, sets the matching environment (`production`, `debug`, etc.).  
4. **Final Outcome**: WordPress constants (e.g., `WP_DEBUG`, `FORCE_SSL_ADMIN`, `DB_HOST`) and relevant PHP ini settings (e.g., `display_errors`) are set to reflect your chosen environment.

**Example**  
- If `ENVIRONMENT_TYPE=dev`, you’ll automatically get `development` logic: `WP_DEBUG = true`, unminified scripts, `display_errors = '1'`, etc.  
- If `ENVIRONMENT_TYPE=prod`, you get `production`: minimal debugging, script concatenation, caching, etc.

## **Key Takeaways**

- **Seamless Configuration**: The framework checks `.env` for specific variables, setting or overriding WordPress constants accordingly.  
- **Environment-Specific**: The `Switcher` tailors debugging, caching, file modification permissions, and trash retention based on environment names (`production`, `staging`, `dev`, `debug`, etc.).  
- **Flexible Defaults**: Many constants fallback to internal defaults if not set in `.env`, ensuring a safe baseline for memory limits, SSL usage, and script handling.  
- **Debug Mode**: In debug environments (`debug`, `local`, etc.), WordPress and PHP error reporting become as verbose as possible, aiding rapid troubleshooting.



> **Note:** You can also set the constant `RAYDIUM_ENVIRONMENT_TYPE` in wp-config.php
> If the environment type is not provided, it defaults to `null` and will fallback to `.env` file setup.
> This can be useful in scenarios where you dont have access to `.env` file.



The environment switcher allows you to run WordPress in a locked-down production mode or a fully verbose debug mode—all by simply changing `ENVIRONMENT_TYPE` in your `.env` file.
