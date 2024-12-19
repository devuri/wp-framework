# Set Up an `auth.json` File

### Set Up Your Raydium Project with GitHub Using an `auth.json` File

> [!IMPORTANT]
> Always keep your tokens secure. Never commit your `auth.json` file to version control or share it publicly to maintain the security of your GitHub account.


When working on a Composer-based PHP project, especially when dealing with private repositories or avoiding rate limits on GitHub, setting up an `auth.json` file can streamline your workflow. This tutorial will guide you through creating and using the `auth.json` file to integrate GitHub seamlessly into your Composer projects.

## Step 1: Understanding the `auth.json` File
The `auth.json` file is a configuration file used by Composer to store credentials for accessing various services like GitHub. This file allows Composer to authenticate on your behalf, thereby facilitating smoother interactions with repositories, especially when you need to go beyond the public access limits.

## Step 2: Generating Your GitHub Token
First things first, you'll need a GitHub token. This token acts as a password, allowing Composer to access GitHub on your behalf without needing your username and password every time.

- **Navigate to GitHub:** Log in to your account.
- **Access Settings:** Click on your profile at the top right corner, then select "Settings."
- **Developer Settings:** On the left sidebar, click on "Developer settings."
- **Personal Access Tokens:** Find "Personal access tokens" and click on "Generate new token."
- **Set Permissions:** Select the scopes or permissions you need. For most Composer interactions, selecting `repo` is sufficient, as it allows access to private repositories.
- **Generate Token:** Click on "Generate token." Make sure to copy your new personal access token. You won’t be able to see it again!

## Step 3: Creating the `auth.json` File
With your GitHub token in hand, you're ready to create the `auth.json` file.

- **Create the File:** Open a text editor and create a new file named `auth.json`.
- **Add Your GitHub Token:** Fill it with the following JSON structure, replacing `YOUR_GITHUB_TOKEN_HERE` with your actual GitHub token:
    ```json
    {
        "github-oauth": {
            "github.com": "YOUR_GITHUB_TOKEN_HERE"
        }
    }
    ```
- **Save the File:** Save this file in your Composer project directory or globally in your Composer home directory (`~/.composer/` on Unix-based systems).

## Step 4: Using the `auth.json` File
Simply having the `auth.json` file in the right place allows Composer to authenticate with GitHub automatically. When you run installations or updates, Composer will use the token from `auth.json` to bypass rate limits or access private repos.


Setting up an `auth.json` file for your Composer project is a straightforward way to enhance your development workflow by ensuring uninterrupted access to GitHub repositories. This setup not only avoids potential disruptions due to rate limiting but also keeps your project connected to private resources seamlessly. Happy coding!
