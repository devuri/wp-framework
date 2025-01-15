# SSH Deployment Keys 

#### Generating and Adding SSH Keys for Deployment with GitHub Actions

You’ll generate a new SSH key pair, add the public key to your remote server, and add the private key to your GitHub repository secrets. Follow these steps to securely configure your deployment process.


### Step 1: Generate SSH Key Pair

1. **Open a Terminal:**
   Open a terminal on your local machine (Linux, macOS, or Windows with Git Bash/WSL).

2. **Generate the SSH Key Pair:**
   There are two common ways to generate a key. One specifies an email comment, and one does not. Use whichever fits your use case:

   **Option A (Default Location with Comment):**
   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   ```
   - `-t rsa`: Generates an RSA key.
   - `-b 4096`: Creates a 4096-bit key for increased security.
   - `-C "your_email@example.com"`: Adds a comment (often used for identification, such as your email).

   When prompted to "Enter file in which to save the key," press **Enter** to accept `~/.ssh/id_rsa`.

   **Option B (Custom Location and Filename):**
   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com" -f /path/to/custom_key
   ```
   - `-f /path/to/custom_key`: Specifies a custom file path and name for the key pair.

3. **Follow the Prompts:**
   - Optionally, enter a passphrase for the key when prompted. This adds an extra layer of security.
   - If you don’t want a passphrase, just press Enter at the passphrase prompts.



### Step 2: Add the Public Key to Your Remote Server

1. **Copy the Public Key:**
   Display the public key using the following command and copy its output:
   ```bash
   cat ~/.ssh/id_rsa.pub
   ```
   *(If you used a custom path, replace `~/.ssh/id_rsa.pub` with `/path/to/custom_key.pub`.)*

2. **Log In to Your Remote Server:**
   ```bash
   ssh user@yourserver.com
   ```

3. **Add the Public Key to the Remote Server:**
   Append the copied public key to the `~/.ssh/authorized_keys` file on your remote server:
   ```bash
   echo "your-copied-public-key" >> ~/.ssh/authorized_keys
   ```

4. **Set Permissions (Optional):**
   Ensure the `authorized_keys` file has the correct permissions:
   ```bash
   chmod 600 ~/.ssh/authorized_keys
   ```


### Step 3: Add the Private Key to GitHub Secrets

1. **Display the Private Key:**
   Use the following command to display your private key and copy its output:
   ```bash
   cat ~/.ssh/id_rsa
   ```
   *(If you used a custom path, replace `~/.ssh/id_rsa` with `/path/to/custom_key`.)*

2. **Add the Private Key to GitHub:**
   - Go to your **GitHub repository**.
   - Navigate to **Settings** > **Secrets and variables** > **Actions**.
   - Click on **New repository secret**.
   - Name the secret (for example, `SSH_PRIVATE_KEY`).
   - Paste the private key content you copied earlier.
   - Click **Add secret**.


### Step 4: Verify SSH Key Access

1. **Test SSH Access from Local Machine:**
   Verify that you can SSH into your remote server using the generated key:
   ```bash
   ssh -i ~/.ssh/id_rsa user@yourserver.com
   ```
   *(Or use `ssh -i /path/to/custom_key user@yourserver.com` if you used a custom path.)*

2. **Troubleshoot if Necessary:**
   - Make sure the `authorized_keys` file on the server contains the correct public key.
   - Check file permissions on `~/.ssh` and `authorized_keys`.
   - Confirm that your server’s SSH configuration allows key-based authentication.


### Using the SSH Key in GitHub Actions

With your SSH keys set up, you can reference them in your GitHub Actions workflow to automate deployments. Below is an example of how to configure a workflow to use the SSH key:

```yaml
name: Deploy to Server

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

    - name: Set up SSH
      uses: webfactory/ssh-agent@v0.5.3
      with:
        ssh-private-key: ${{ secrets.SSH_PRIVATE_KEY }}

    - name: Deploy to Server
      run: |
        ssh user@yourserver.com 'cd /path/to/bare-repo/repo.git && git pull origin main'
```

1. **`webfactory/ssh-agent@v0.5.3`**: Sets up an SSH agent in the GitHub Actions environment.  
2. **`ssh-private-key: ${{ secrets.SSH_PRIVATE_KEY }}`**: Loads the private key you added as a repository secret.  
3. **`ssh user@yourserver.com`**: Uses SSH to connect and run deployment commands on the remote server.

This configuration ensures that your deployment process is both secure and automated. By combining SSH keys with GitHub Actions, you can deploy to your server with minimal manual intervention.
