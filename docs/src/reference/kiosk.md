# Developer Kiosk `kiosk.json`

The `kiosk.json` file is a configuration file used to manage developer access to the kiosk system. Each developer's unique details, roles, permissions, and active status are stored under their username, providing a structured and organized format for managing access. This file is located in the `configs/` directory and serves as the primary reference for access control.

## File Structure
```json
{
  "username": {
    "id": "string",
    "username": "string",
    "email": "string",
    "name": "string",
    "role": "string",
    "permissions": ["string", ...],
    "active": boolean
  }
}
```

## Fields Description

### **Top-Level Field**
- Each top-level key represents a **username**, which maps to the corresponding developer's details.

### **Developer Fields**
Each developer object contains the following fields:

1. **`id`**:
   - **Type**: String
   - **Description**: A unique identifier for the developer.
   - **Example**: `"23566"`

2. **`username`**:
   - **Type**: String
   - **Description**: Developer’s username for the kiosk system. This is the key under which the developer’s data is stored.
   - **Example**: `"alice"`

3. **`email`**:
   - **Type**: String
   - **Description**: Email address for communication or notifications.
   - **Example**: `"alice.johnson@example.com"`

4. **`name`**:
   - **Type**: String
   - **Description**: Full name of the developer.
   - **Example**: `"Alice Johnson"`

5. **`role`**:
   - **Type**: String
   - **Description**: The developer's role within the kiosk system.
   - **Accepted Values**:
     - `"admin"`: Full access, including managing the kiosk system.
     - `"editor"`: Can read, write, and deploy, but cannot manage the system.
     - `"viewer"`: Read-only access.

6. **`permissions`**:
   - **Type**: Array of Strings
   - **Description**: Specific actions the developer is allowed to perform.
   - **Example**: `["read", "write", "deploy", "manage_kiosk"]`

7. **`active`**:
   - **Type**: Boolean
   - **Description**: Indicates whether the developer currently has active access to the kiosk.
   - **Example**: `true`


## Example File
```json
{
  "alice": {
    "id": "23566",
    "username": "alice",
    "email": "alice.johnson@example.com",
    "name": "Alice Johnson",
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
    "name": "Bob Smith",
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
    "name": "Charlie Lee",
    "role": "viewer",
    "permissions": [
      "read"
    ],
    "active": false
  }
}
```


## Usage Instructions
1. **Configuration Management**:
   - Add or remove developers by modifying entries in `kiosk.json`.
   - Ensure each `id` is unique to avoid conflicts.

2. **Role Definitions**:
   - Assign roles based on the developer’s responsibilities:
     - Use `"admin"` for system managers.
     - Use `"editor"` for developers needing write and deploy access.
     - Use `"viewer"` for users requiring read-only access.

3. **Contact Information**:
   - Use the `email` field to notify developers of updates, alerts, or changes to their access.


## Best Practices
- **Consistency**: Use consistent naming conventions for `username` to make identification straightforward.
- **Security**: Periodically review access and set `"active": false` for developers no longer needing access.
- **Backup**: Backup `kiosk.json` before making significant changes to preserve existing configurations.
