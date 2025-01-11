## Enable and Use Adminer

**Adminer** is a lightweight database management tool seamlessly integrated into **Raydium**. It allows **superadmin** users to access and manage the database from a web interface. However, for security reasons, Adminer must be properly enabled and configured in several places:

1. `kiosk.json` for **user roles** and **privileges**.
2. `configs/app.php` (specifically the `dbadmin` section) for **site-wide Adminer settings**.

This guide walks you through each step to ensure a secure and efficient Adminer setup.

## 1. Overview of Adminer Access

Adminer is accessed via a unique URL that follows this pattern:

```
http://example.com/wp/wp-admin/{uriID}
```

By default, Adminer is **not accessible** for security reasons (you’ll see a 404 error if you try). You must enable it and ensure the user accessing it has the **superadmin** role.


## 2. Prerequisites

1. **Superadmin Privileges**  
   The user must have `"role": "superadmin"` in the `kiosk.json` file.

2. **Matching WordPress User**  
   A corresponding WordPress user with the **same username** must exist, remain active, and have sufficient permissions in WordPress (e.g., Administrator-level capability).

3. **Application Mode**  
   Adminer may be unavailable in “secure” environments if the application is running with the secure flag set, regardless of other settings.

## 3. Step-by-Step Configuration in `kiosk.json`

### 3.1 Open `kiosk.json`

Locate the `kiosk.json` file (or create it) in your configuration directory. Inside, you will find a `users` section like:

```json
"users": {
  "alice": {
    "id": "23566",
    "username": "alice",
    "email": "alice.johnson@example.com",
    "role": "admin",
    "permissions": [
      "read",
      "write",
      "deploy",
      "manage_kiosk"
    ],
    "active": true
  }
}
```

### 3.2 Update the User Role

Change `"role": "admin"` to `"role": "superadmin"`:

```diff
"role": "admin",
```

becomes:

```json
"role": "superadmin",
```

Save your changes.

### 3.3 Verify User Alignment

- Ensure there is an **active WordPress user** with the **exact** username, such as `"alice"`.  
- Confirm this user can log in to WordPress normally and has full site permissions.

## 4. Additional Adminer Configuration in `configs/app.php`

Alongside `kiosk.json`, Adminer access is controlled by the `dbadmin` section in `configs/app.php`. Below is an example configuration:

```php
/*
 * Configuration settings for the Adminer database administrator interface.
 *
 * Note: If the application is running with the `secure` flag set,
 * Adminer will never be accessible, regardless of these settings.
 */
'dbadmin' => [
    /*
     * Whether or not to enable the Adminer interface.
     * @var bool $enabled Default true.
     */
    'enabled' => true,

    /*
     * The URI path for accessing the Adminer interface.
     * The resulting URL will be `example.com/wp/wp-admin/{uri}`.
     * @var string $uri Must be a valid string. Default is 'dbadmin'.
     */
    'uri' => 'dbadmin',

    /*
     * Whether to validate that the WordPress user is authenticated.
     * Only users in the `kiosk` list with the `manage_database` capability will be allowed.
     * @var bool $validate Default false.
     */
    'validate' => true,

    /*
     * Whether to enable autologin for the Adminer interface.
     * @var bool $autologin Default true (based on ADMINER_ALLOW_AUTOLOGIN constant).
     */
    'autologin' => ADMINER_ALLOW_AUTOLOGIN,

    /*
     * Optional passkey for generating signed access URLs.
     * Allows the creation of signed URLs that bypass authentication under specific conditions.
     * @var string|null $secret Default null.
     */
    'secret' => [
        'key' => env('ADMINER_SECRET', null),
        'type' => 'jwt',
    ],
],
```

### 4.1 Key Settings Explanation

1. **`enabled`**: Controls whether Adminer is **globally enabled**.  
2. **`uri`**: Defines the **path segment** for Adminer. For instance, if set to `'dbadmin'`, your Adminer URL becomes `http://example.com/wp/wp-admin/dbadmin`.  
3. **`validate`**: If `true`, **WordPress user authentication** is strictly enforced, requiring users to be logged in and have the `manage_database` capability.  
4. **`autologin`**: If `true`, **bypasses** the Adminer login screen.  
   - Use with caution, as it can allow anyone with the URL to access Adminer.  
   - It relies on the `ADMINER_ALLOW_AUTOLOGIN` constant, which can be overridden in `wp-config.php`.  
5. **`secret`**:  
   - When set, you can create **signed Adminer URLs** that bypass normal authentication for debugging or temporary access.  
   - The key is validated before granting Adminer access.

> **Important**: In highly secure environments (if the app runs with the `secure` flag), Adminer remains inaccessible **regardless** of the above settings.


## 5. Applying Your Changes

1. **Restart or Reload**  
   After modifying `kiosk.json` and `configs/app.php`, restart or reload the application to apply the updated settings.

2. **Access the Adminer URL**  
   - If `uri` is set to `dbadmin`, visit:  
     ```
     http://example.com/wp/wp-admin/dbadmin
     ```  
   - Or, if you have a custom `uri` like `df78cd0d37a143dd`, it becomes:  
     ```
     http://example.com/wp/wp-admin/df78cd0d37a143dd
     ```


## 6. Troubleshooting

### 6.1 404 Errors

- **Check `dbadmin.enabled`**: Confirm that `'enabled' => true`.  
- **Superadmin Role**: The WordPress user must have the `superadmin` role in `kiosk.json`.  
- **`secure` Flag**: Ensure your application is not in a mode that automatically disables Adminer.

### 6.2 Authentication Failure

- **`dbadmin.validate`**: If `true`, you must be logged in as a user who has the `manage_database` capability.  
- **WordPress Credentials**: Verify the username and password match the **active** WordPress user.  

### 6.3 `kiosk.json` Updates Not Applied

- **Restart/Reload**: Always restart or reload your application after making changes.


## 7. Security Best Practices

1. **Restrict Access to Trusted Users**  
   Grant **superadmin** privileges only to those who genuinely require database-level access.  

2. **Keep the URL Private**  
   If `autologin` is true, anyone with the URL can access your database interface.  

3. **Use Signed URLs Carefully**  
   If you enable `secret.key`, treat generated URLs as highly sensitive.  

4. **Secure Environments**  
   If your environment is running with the `secure` flag, Adminer will remain inaccessible for an added layer of protection.

By following these steps and properly configuring both `kiosk.json` and `configs/app.php`, you can safely **enable and use Adminer** in the Raydium WPFramework for easy, web-based database management.
