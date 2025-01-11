# Kiosk Configuration (`kiosk.json`)

The `kiosk.json` file is a configuration file used to manage developer access to the kiosk system. Each developer's unique details, roles, permissions, and active status are stored under their username, providing a structured and organized format for managing access. This file is located in the `configs/` directory and serves as the primary reference for access control.

## How the Kiosk Works

A kiosk is typically accessed via a dedicated subdomain. If your `panel.id` is set to `"mykiosk"`, you’ll usually create a subdomain called `mykiosk.example.com` pointing to the same server or hosting environment as your main framework application. This configuration allows a clean separation of the kiosk interface, while still leveraging the same underlying codebase and middleware.  

### To summarize:
1. **Choose a Subdomain**: The `panel.id` in `kiosk.json` often matches the desired subdomain (e.g., `"mykiosk"` → `mykiosk.example.com`).  
2. **DNS Records**: In your DNS provider, create an `A` or `CNAME` record that directs `mykiosk.example.com` to the IP or domain of your hosting environment where the framework is deployed.  
3. **Deploy**: Ensure your server or hosting environment is prepared to route requests on that subdomain to the application.

## How to Use

1. **Location**  
   `kiosk.json` resides in the `configs/` directory of your application.

2. **Subdomain Setup**  
   Decide on a kiosk identifier (e.g., `"mykiosk"`) and create a matching subdomain (`mykiosk.example.com`). Point it to the same environment as your application/site.

3. **Single Source of Truth**  
   Unlike other framework configurations that merge, the kiosk subsystem reads everything from this one JSON file. Define all kiosk-related settings here.

4. **Customization**  
   Update or extend each section—`branding`, `security`, `features`, `users`, etc.—to match your application’s needs. The kiosk application will consume these settings during initialization.

## Kiosk Configuration Overview

Below is a detailed look at each key inside the main `panel` object. Each section includes a table describing its purpose and an example snippet.

### Panel-Level Keys

| Input    | Description                                                                                                   | Example                   |
|----------|---------------------------------------------------------------------------------------------------------------|---------------------------|
| id       | Kiosk identifier, often matches your chosen subdomain.                                                       | `"mykiosk"`              |
| enabled  | Toggles whether the kiosk is active (`true`) or disabled (`false`).                                          | `true`                    |
| version  | Version of the kiosk configuration. Helps track changes over time.                                           | `"1.0.0"`                 |
| framework| Indicates the kiosk framework name.                                                                           | `"kiosk"`                 |
| uuid     | A unique identifier for the kiosk instance.                                                                  | `"ukiosk_ec1b25b5a836f174ea"` |

```json
"panel": {
  "id": "mykiosk",
  "enabled": true,
  "version": "1.0.0",
  "framework": "kiosk",
  "uuid": "ukiosk_ec1b25b5a836f174ea",
  ...
}
```
> **Note**: If `panel.id` is `"mykiosk"`, the kiosk will typically live at `mykiosk.example.com`.

### Branding

| Input  | Description                                                                  | Example                     |
|--------|------------------------------------------------------------------------------|-----------------------------|
| logo   | Path (relative or absolute) to the kiosk’s logo image.                       | `"asset/kiosk/logo.png"`    |
| title  | Title text displayed in the kiosk UI.                                        | `"Admin Panel Kiosk"`       |
| theme  | Color theme or style variant; often `"dark"` or `"light"`.                   | `"dark"`                    |

```json
"branding": {
  "logo": "asset/kiosk/logo.png",
  "title": "Admin Panel Kiosk",
  "theme": "dark"
}
```

### Twig Configuration

Manages [Twig](https://twig.symfony.com/) templating for the kiosk templated views.

> you can override these in your application `templates` in the `{rootpath}/templates/kiosk`

| Input            | Example or Default | Description                                                         |
|------------------|--------------------|---------------------------------------------------------------------|
| debug            | `false`           | Enables extra Twig debugging features if `true`.                    |
| charset          | `"utf-8"`         | Character encoding for rendered templates.                          |
| cache            | `false`           | If a string path, Twig compiles templates to that directory.        |
| auto_reload      | `null`            | Auto-recompile templates on change; defaults to `true` in debug.    |
| strict_variables | `false`           | Throws errors on undefined variables if `true`.                     |
| autoescape       | `"html"`          | Strategy for escaping template output (e.g., `html`, `js`).         |
| optimizations    | `-1`              | Applies all Twig optimizations if `-1`.                             |

```json
"twig": {
  "debug": false,
  "charset": "utf-8",
  "cache": false,
  "auto_reload": null,
  "strict_variables": false,
  "autoescape": "html",
  "optimizations": -1
}
```

### Security

Houses authentication, encryption, and allowed IP settings for the kiosk.

| Input                       | Description                                                                                         | Example                                   |
|----------------------------|-----------------------------------------------------------------------------------------------------|-------------------------------------------|
| authentication.method      | Authentication approach (e.g., `"OAuth2"`, `"Basic"`).                                              | `"OAuth2"`                                |
| authentication.loginEndpoint | URL where the kiosk performs login/token requests.                                                  | `"https://example.com/api/login"`         |
| authentication.tokenExpiration | Validity (in seconds) of the generated token.                                                      | `3600`                                    |
| encryption.enabled         | Toggles kiosk encryption features.                                                                   | `true`                                    |
| encryption.algorithm       | Encryption algorithm (e.g., `"AES-256"`).                                                            | `"AES-256"`                               |
| allowedIPs                 | Array of IP addresses allowed to access the kiosk. Empty means no IP-based restrictions.             | `[]`                                      |

```json
"security": {
  "authentication": {
    "method": "OAuth2",
    "loginEndpoint": "https://example.com/api/login",
    "tokenExpiration": 3600
  },
  "encryption": {
    "enabled": true,
    "algorithm": "AES-256"
  },
  "allowedIPs": []
}
```

### Features

Defines which modules the kiosk exposes and how they behave.

#### Dashboard

| Input            | Description                                                                    | Example              |
|------------------|--------------------------------------------------------------------------------|----------------------|
| enabled          | Toggles the entire dashboard feature.                                           | `true`               |
| widgets          | Array of dashboard widgets. Each widget has its own `name`, `type`, and refresh options. | (see snippet below)  |

```json
"dashboard": {
  "enabled": true,
  "widgets": [
    {
      "name": "System Status",
      "type": "status",
      "refreshInterval": 60
    },
    {
      "name": "User Activity",
      "type": "chart",
      "refreshInterval": 300
    }
  ]
}
```

#### User Management

| Input       | Description                                        | Example                                             |
|-------------|----------------------------------------------------|-----------------------------------------------------|
| enabled     | Toggles user management features.                  | `true`                                              |
| permissions | Dictates actions like creating, editing, or deleting users. | `{"createUser": true, "editUser": true, "deleteUser": false}` |

```json
"userManagement": {
  "enabled": true,
  "permissions": {
    "createUser": true,
    "editUser": true,
    "deleteUser": false
  }
}
```

### Logging

Controls where logs go, how verbose they are, and how long they’re kept.

| Input     | Description                                                                  | Example                 |
|-----------|------------------------------------------------------------------------------|-------------------------|
| level     | Minimum log level (e.g., `"debug"`, `"info"`, `"warn"`, `"error"`).          | `"info"`                |
| output    | Path or destination for kiosk logs.                                          | `"/var/log/kiosk.log"`  |
| retention | Number of days to retain logs.                                               | `7`                     |

```json
"logging": {
  "level": "info",
  "output": "/var/log/kiosk.log",
  "retention": 7
}
```

### Support

Specifies contact details for support/assistance or inquiries.

| Input  | Description                                      | Example                    |
|--------|--------------------------------------------------|----------------------------|
| email  | Support email address.                           | `"support@example.com"`    |
| number | Phone number for urgent help.                    | `"+1234567890"`            |

```json
"support": {
  "email": "support@example.com",
  "number": "+1234567890"
}
```

### Users

Lists user accounts, roles, permissions, and their active status. The kiosk reads this to handle logins and manage access.

| Input       | Description                                                                      | Example                     |
|-------------|----------------------------------------------------------------------------------|-----------------------------|
| id          | Unique identifier for the user.                                                  | `"23566"`                   |
| username    | The user’s kiosk username.                                                       | `"alice"`                   |
| email       | User’s contact address.                                                          | `"alice.johnson@example.com"` |
| role        | Role within the kiosk, e.g., `"admin"`, `"editor"`, `"viewer"`.                  | `"admin"`                   |
| permissions | Array of specific actions allowed (`["read", "write", "deploy", ...]`).          | `["read","write","deploy","manage_kiosk"]` |
| active      | `true` if the user can log in; `false` if disabled.                              | `true`                      |

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

## Example File

Below is a complete example of `kiosk.json`, showing how all these sections come together:

```json
{
  "panel": {
    "id": "mykiosk",
    "enabled": true,
    "version": "1.0.0",
    "framework": "kiosk",
    "uuid": "ukiosk_ec1b25b5a836f174ea",
    "branding": {
      "logo": "asset/kiosk/logo.png",
      "title": "Admin Panel Kiosk",
      "theme": "dark"
    },
    "twig": {
      "debug": false,
      "charset": "utf-8",
      "cache": false,
      "auto_reload": null,
      "strict_variables": false,
      "autoescape": "html",
      "optimizations": -1
    },
    "security": {
      "authentication": {
        "method": "OAuth2",
        "loginEndpoint": "https://example.com/api/login",
        "tokenExpiration": 3600
      },
      "encryption": {
        "enabled": true,
        "algorithm": "AES-256"
      },
      "allowedIPs": []
    },
    "features": {
      "dashboard": {
        "enabled": true,
        "widgets": [
          {
            "name": "System Status",
            "type": "status",
            "refreshInterval": 60
          },
          {
            "name": "User Activity",
            "type": "chart",
            "refreshInterval": 300
          }
        ]
      },
      "userManagement": {
        "enabled": true,
        "permissions": {
          "createUser": true,
          "editUser": true,
          "deleteUser": false
        }
      }
    },
    "logging": {
      "level": "info",
      "output": "/var/log/kiosk.log",
      "retention": 7
    },
    "support": {
      "email": "support@example.com",
      "number": "+1234567890"
    },
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
      },
      "bsmith": {
        "id": "325523",
        "username": "bsmith",
        "email": "bob.smith@example.com",
        "role": "editor",
        "permissions": [
          "read",
          "write",
          "deploy"
        ],
        "active": true
      },
      "charlie": {
        "id": "523563",
        "username": "charlie",
        "email": "charlie.lee@example.com",
        "role": "viewer",
        "permissions": [
          "read"
        ],
        "active": false
      }
    }
  }
}
```

## Best Practices & Notes

- **Subdomain Configuration**  
  Point `mykiosk.example.com` (or your chosen subdomain) to the same hosting environment where the application is deployed. This ensures your kiosk routes work properly.
- **Security**  
  If public-facing, consider restricting `allowedIPs` or ensuring robust authentication. Regularly review user `active` statuses.
- **Branding**  
  Update `"logo"`, `"title"`, and `"theme"` to match your organization’s identity or internal guidelines.
- **Logging**  
  Keep an eye on file size. Adjust `"retention"` and log level (`"info"`, `"error"`, etc.) as needed.
- **User Lifecycle**  
  Instead of deleting users, set `"active": false`. This preserves history while preventing logins.
- **Extend Features**  
  Feel free to add custom feature blocks under `"features"` to enable more kiosk functionalities.

By tailoring `kiosk.json` and ensuring the proper DNS/subdomain setup, you can create a secure, branded, and fully functional kiosk panel accessible at `mykiosk.example.com` (or your preferred subdomain).
