# SSH Keys for GitHub Actions

#### Why Create Multiple SSH Key Pairs?
Having more than one SSH key pair can help you:
- Isolate access to different services (e.g., GitHub, other Git platforms, or personal servers).
- Keep security organized (one key per project or environment).
- Avoid accidental overwriting of existing SSH keys.


## Step 1: Open a Terminal
This guide works on Linux, macOS, or Windows (using Git Bash or WSL). Make sure you have an up-to-date OpenSSH client installed.


## Step 2: Generate a New SSH Key Pair
Use the `ssh-keygen` command to create a new key pair. You can customize the directory and file name so each key pair is unique. Two common variations:

1. **Without a comment (email address)**:
   ```sh
   ssh-keygen -t rsa -b 4096 -f /path/to/my_keys/unique_key_name
   ```

2. **With a comment (email address)**:
   ```sh
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com" -f /path/to/my_keys/unique_key_name
   ```

### Explanation of Flags:
- **`-t rsa`**: Defines the key type (RSA).
- **`-b 4096`**: Specifies the key length (4096 bits for stronger security).
- **`-C "your_email@example.com"`**: Adds a comment to the key, often used to identify the key’s owner or purpose (such as an email address). This is optional.
- **`-f /path/to/my_keys/unique_key_name`**: Sets a custom file path and name for your private key. The public key will have the same name with a `.pub` extension.

> **Note (GitHub Actions)**: If you plan to use this key in a GitHub Actions workflow, you will typically store the private key content (the entire file) in a GitHub Secret. Then, in your workflow, you can echo the secret into a file (e.g., `id_rsa`) and use `ssh-agent` to load it. Make sure your `.gitignore` or repository settings prevent the private key from being accidentally committed.


## Step 3: (Optional) Enter a Passphrase
When prompted, you can create a passphrase to add an extra layer of security. This passphrase will be required any time you use the key.

```
Enter passphrase (empty for no passphrase):
Enter same passphrase again:
```

Press **Enter** at both prompts if you prefer no passphrase.

## Step 4: View Your Key Pair
Your newly generated keys will be saved where you specified:
- Private key (keep this secret!): `/path/to/my_keys/unique_key_name`
- Public key (safe to share): `/path/to/my_keys/unique_key_name.pub`

To see your **public** key content:
```sh
cat /path/to/my_keys/unique_key_name.pub
```


## Step 5: Add Your SSH Key to the SSH Agent
Load your private key into the SSH agent so you don’t have to type your passphrase repeatedly (if you set one).

```sh
eval "$(ssh-agent -s)"
ssh-add /path/to/my_keys/unique_key_name
```

> **Note (GitHub Actions)**: In a workflow, you can use the `ssh-agent` action (e.g., `- name: Start ssh-agent`) and then add your private key using:
> ```yaml
> - name: Add SSH Key
>   run: |
>     ssh-add - <<< "${{ secrets.MY_PRIVATE_KEY }}"
> ```


## Step 6: Add the Public Key to Remote Servers
You need to place your **public** key (`.pub` file) on the remote server you want to access.

1. Copy the contents of `/path/to/my_keys/unique_key_name.pub`.
2. Append or paste it into `~/.ssh/authorized_keys` on the remote server.

Or use `ssh-copy-id` for a more automated approach:
```sh
ssh-copy-id -i /path/to/my_keys/unique_key_name.pub username@remote_host
```


## Step 7: (Optional) Organize Keys in SSH Config
If you have many SSH keys, it’s convenient to store server connection info in `~/.ssh/config`. This way, you can just type `ssh myserver` instead of `ssh -i /path/to/my_keys/unique_key_name username@remote_host`.

Open (or create) the file:
```sh
nano ~/.ssh/config
```

Add an entry:
```plaintext
Host myserver
    HostName remote_host
    User username
    IdentityFile /path/to/my_keys/unique_key_name
```

> **Note (GitHub Actions)**: Typically, you don’t need this for GitHub Actions unless you’re using multiple hosts or advanced SSH usage in your workflows.


## Step 8: Connect Using Your SSH Key
After configuring the SSH config file (if you chose to do so), simply run:
```sh
ssh myserver
```

If you didn’t set up an alias, you can connect like this:
```sh
ssh -i /path/to/my_keys/unique_key_name username@remote_host
```


## Summary
You’ve successfully created a unique SSH key pair, optionally secured it with a passphrase, and added it to your SSH agent. By customizing the file path and name (`-f /path/to/my_keys/unique_key_name`), you can generate multiple keys without overwriting your existing ones. You can also include your email or any identifier with the `-C` flag if you want a comment in the key.

To use these keys in GitHub Actions, store your private key in a GitHub Secret, then configure the `ssh-agent` within your workflow. For regular usage, remember to place your public key on the remote server and optionally configure `~/.ssh/config` for quick access.

Repeat these steps for every additional SSH key pair you need.
