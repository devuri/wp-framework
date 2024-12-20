### Configuring Using Environment Variables

Here is a detailed reference of the various configuration options available in the `configs/app.php` file and how they can be set using environment variables in the `.env` file:

#### Error Handling

```shell
# Set the error handler for the project (options: 'oops', 'symfony')
ERROR_HANDLER=oops
```

#### Directory Configuration

```shell
# Define the web root directory
WEB_ROOT_DIR=public_html

# Set the content directory
CONTENT_DIR=wp-content

# Specify the plugins directory
PLUGIN_DIR=wp-content/plugins

# Define the must-use plugins directory
MU_PLUGIN_DIR=wp-content/mu-plugins

# Configure SQLite database directory and file
SQLITE_DIR=database
SQLITE_FILE=.sqlite-wordpress

# Set the theme directory
THEME_DIR=themes

# Define the global assets directory
ASSET_DIR=static_assets

# Set the directory for public keys
PUBLICKEY_DIR=keys
```

#### Default Theme

```shell
# Specify the default fallback theme
DEFAULT_THEME=mycustomtheme
```

#### WordPress Updates

```shell
# Disable WordPress updates (true to disable, false to enable)
DISABLE_UPDATES=true
```

#### Plugin Deactivation

```shell
# Control plugin deactivation (true to allow deactivation, false to prevent it)
CAN_DEACTIVATE=false
```

#### Email SMTP Configuration

```shell
# Set the API key for Brevo mailer
BREVO_API_KEY=your_brevo_api_key

# Set the token for Postmark mailer
POSTMARK_TOKEN=your_postmark_token

# Set the API key for SendGrid mailer
SENDGRID_API_KEY=your_sendgrid_api_key

# Set the API key for MailerLite mailer
MAILERLITE_API_KEY=your_mailerlite_api_key

# Set the domain, secret, and endpoint for Mailgun mailer
MAILGUN_DOMAIN=your_mailgun_domain
MAILGUN_SECRET=your_mailgun_secret
MAILGUN_ENDPOINT=api.mailgun.net

# Set the AWS credentials for SES mailer
AWS_ACCESS_KEY_ID=your_aws_access_key_id
AWS_SECRET_ACCESS_KEY=your_aws_secret_access_key
AWS_DEFAULT_REGION=us-east-1
```

#### Sudo Admin

```shell
# Define the user ID for the sudo admin
SUDO_ADMIN=1
```

#### S3 Uploads

```shell
# Set the S3 bucket name
S3_UPLOADS_BUCKET=my-s3-bucket

# Set the S3 access key and secret
S3_UPLOADS_KEY=my_s3_key
S3_UPLOADS_SECRET=my_s3_secret

# Set the S3 region
S3_UPLOADS_REGION=us-east-1

# Set the base URL of the S3 bucket
S3_UPLOADS_BUCKET_URL=https://examplebucket.com

# Set the access control list for uploaded objects
S3_UPLOADS_OBJECT_ACL=private

# Set the expiration time for HTTP caching headers
S3_UPLOADS_HTTP_EXPIRES=1 day

# Set the HTTP cache control value
S3_UPLOADS_HTTP_CACHE_CONTROL=600
```

#### Redis Cache Configuration

```shell
# Enable or disable Redis cache (true to disable, false to enable)
WP_REDIS_DISABLED=false

# Set the Redis server host and port
WP_REDIS_HOST=127.0.0.1
WP_REDIS_PORT=6379

# Set the Redis server password
WP_REDIS_PASSWORD=your_redis_password

# Disable Redis cache for the WordPress admin bar (true to disable, false to enable)
WP_REDIS_DISABLE_ADMINBAR=false

# Disable Redis cache metrics (true to disable, false to enable)
WP_REDIS_DISABLE_METRICS=false

# Disable Redis cache banners (true to disable, false to enable)
WP_REDIS_DISABLE_BANNERS=false

# Set the Redis cache key prefix
WP_REDIS_PREFIX=my_redis_prefix

# Set the Redis database index
WP_REDIS_DATABASE=1

# Set the Redis connection timeout (in seconds)
WP_REDIS_TIMEOUT=1

# Set the Redis read timeout (in seconds)
WP_REDIS_READ_TIMEOUT=1
```

#### Public Key Configuration

```shell
# Set the public key environment variable
WEB_APP_PUBLIC_KEY=your_public_key
```
