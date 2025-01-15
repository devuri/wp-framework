# Deploying Projects with Deploy Keys

Deploy keys let you grant limited access to a single repository on GitHub without using personal credentials. This keeps your deployment process secure and automated.

> [!DANGER]
> It is crucial to keep your private keys secure at all times. Private keys provide access to your repositories and servers, and if compromised, can lead to significant security risks, including unauthorized access to your codebase and sensitive data.

## What Are Deploy Keys?

Deploy keys are SSH keys that provide read-only or read-write access to a single GitHub repository. They are:
- **Secure**: Isolated to one repository, reducing broader credential exposure.
- **Automated**: Used by CI/CD pipelines (e.g., GitHub Actions) without needing personal tokens.
- **Simple**: Easy to set up and maintain for deployment workflows.

## Why Use Deploy Keys?

1. **Security**: Restricts access to a specific repository.  
2. **Automation**: Facilitates CI/CD deployments without human intervention.  
3. **Simplicity**: Avoids managing user-level SSH keys or personal access tokens.

## Step-by-Step Deployment Process

### Step 1: Generating an SSH Key Pair

1. **Open a Terminal** (Linux, macOS, or Windows using Git Bash/WSL).
2. **Generate the SSH Key Pair**. Two common options:

   **Option A (Ed25519 Key with Comment)**:
   ```bash
   ssh-keygen -t ed25519 -C "your_email@example.com"
   ```
   - **`-t ed25519`**: Creates a modern, secure Ed25519 key.
   - **`-C "your_email@example.com"`**: Adds a comment (often an email).

   **Option B (Using a Custom Path/Filename)**:
   ```bash
   ssh-keygen -t ed25519 -C "your_email@example.com" -f /path/to/custom_key
   ```
   - **`-f /path/to/custom_key`**: Places the generated keys at a specified path.

3. **Follow the Prompts**:
   - Press **Enter** to accept the default location (or use your custom path).
   - Optionally set a passphrase for extra security (or press Enter twice to skip).

You now have two files:
- **Private key** (e.g., `id_ed25519` or `custom_key`)
- **Public key** (e.g., `id_ed25519.pub` or `custom_key.pub`)


### Step 2: Adding the Public Key to GitHub

1. **Go to Your Repository** on GitHub and select **Settings**.
2. Click **Deploy keys** in the left sidebar.
3. Click **Add deploy key**.
4. Provide a **Title** (e.g., “Deploy Key for My Server”).
5. Open your public key (e.g., `id_ed25519.pub`) in a text editor; copy its contents.
6. Paste the public key into the **Key** field on GitHub.
7. Check **Allow write access** if needed (optional).
8. Click **Add key**.

### Step 3: Configure Your Raydium-powered Project

1. **Set Up Your Environment**: Create or update a `.env` file with production credentials or other relevant settings.
2. **Deployment Script**: Ensure you have a script or process ready to handle tasks like pulling changes and installing dependencies (Composer, etc.).


### Step 4: Creating a Deployment Script on Your Server

Create a `deploy.sh` script on your server to automate the deployment steps:

```bash
#!/bin/bash

# Navigate to the project directory
cd /path/to/your/project

# Pull the latest changes from the GitHub repository
git pull origin main

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Set correct permissions (example for a Linux server)
chown -R www-data:www-data /path/to/your/project
```

Make your script executable:
```bash
chmod +x deploy.sh
```

### Step 5: Automating Deployment with GitHub Actions

Use GitHub Actions for continuous deployment. Create a file named `deploy.yml` in `.github/workflows/`:

```yaml
name: Deploy Raydium-powered WordPress Application

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
    - name: Checkout code
      uses: actions/checkout@v2
      with:
        ssh-key: ${{ secrets.SSH_PRIVATE_KEY }}

    - name: Set up PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.0'


    - name: Deploy on Server
      run: |
        ssh -o StrictHostKeyChecking=no user@server 'bash -s' < ./deploy.sh
      env:
        ssh-private-key: ${{ secrets.SSH_PRIVATE_KEY }}
```

> [GitHub Actions Documentation](https://github.com/webfactory/ssh-agent)

**Key points**:
- **`actions/checkout@v2`**: Checks out your repository code.
- **`ssh-key: ${{ secrets.SSH_PRIVATE_KEY }}`**: Automatically configures the deploy key for Git operations.
- **`ssh -o StrictHostKeyChecking=no user@server 'bash -s' < ./deploy.sh`**: Connects to your server and runs the `deploy.sh` script.


### Step 6: Setting Secrets in GitHub

To securely use your **private** key in GitHub Actions, add it as a repository secret:

1. **Go to Settings** > **Secrets and variables** > **Actions** in your GitHub repository.
2. Click **New repository secret**.
3. Name the secret, for example, `SSH_PRIVATE_KEY`.
4. Open your private key (`id_ed25519` or `custom_key`) in a text editor; copy its contents.
5. Paste it into the **Value** field.
6. Click **Add secret**.


## Additional Resources

- [GitHub Docs: Managing deploy keys](https://docs.github.com/en/developers/overview/managing-deploy-keys)  
- [GitHub Actions Documentation](https://docs.github.com/en/actions)  
- [SSH Key Generation](https://www.ssh.com/academy/ssh/keygen)


## Best Practices for Keeping Private Keys Secure

- **Never Share** your private keys; store them in a secure location.  
- **Use Strong Passphrases** if possible, for added encryption.  
- **Restrict Key Permissions** (e.g., `chmod 600`) so only authorized processes can read them.  
- **Regular Key Rotation**: Periodically regenerate and update your keys.  
- **Monitor Access**: Check repository logs and server logs for unusual activity.

> **Security:** Always create a **new** SSH key with the appropriate access permissions, avoid using your personal SSH key and instead generate a dedicated key specifically for GitHub Actions.
