# Multisite

The **Raydium Framework** offers a robust, secure, and scalable foundation for WordPress development, designed to enhance modularity and maintainability. While **WordPress Multisite** is not officially supported by Raydium at this time, the framework's flexibility makes it possible to configure and experiment with Multisite.

> The Raydium team is actively exploring official Multisite support for future releases, so stay tuned for updates!  

If you're interested in implementing Multisite on Raydium, follow this guide while keeping its unique architecture in mind.  

---

### Use Subdomains

Raydium's **directory structure** organizes files differently from the default setup, making **subdomains** the optimal choice for your Multisite configuration. Here’s why:  

1. **Simplified Routing**  
   Subdomains avoid the potential routing conflicts that can occur with subdirectories (e.g., `example.com/site1`), ensuring seamless integration with the framework architecture.  

2. **SEO Advantages**  
   Subdomains are treated as separate entities by search engines, allowing for more targeted SEO strategies tailored to specific audiences or niches.  

3. **Scalability and Flexibility**  
   Subdomains make it easier to manage multiple unique brands or websites within your network, offering a future-proof solution as your business grows.  

To use subdomains, ensure your **DNS** is configured to handle **wildcard** subdomains (e.g., `*.example.com`).  


### Configuring Multisite with Subdomains  

#### 1. **Prepare Your Environment**  
   Ensure your hosting meets the requirements for WordPress Multisite and wildcard subdomains:  
   - **PHP** 7.4 or higher.  
   - **MySQL** 5.7 or higher, or **MariaDB** 10.3 or higher.  
   - **Apache** or **Nginx** with `mod_rewrite` enabled.  
   - DNS wildcard subdomains configured:  
     ```
     *.example.com A <server-ip>
     ```

#### 2. **Install Raydium**  
   Set up your application using Raydium’s standard process:  
   ```bash
   composer create-project devuri/raydium your-project-name
   ```

#### 3. **Enable Multisite**  
   Add the following line to your `wp-config.php` file to enable Multisite functionality:  
   ```php
   define('WP_ALLOW_MULTISITE', true);
   ```  
   Save the file and refresh your admin dashboard.  

#### 4. **Configure the Multisite Network**  
   - Navigate to **Tools > Network Setup** in your WordPress admin panel.  
   - Choose **Subdomains** as your Multisite configuration type.  
   - Follow the provided instructions to add the necessary code snippets to your `wp-config.php` and `.htaccess` files.  

#### 5. **Access the Network Admin Dashboard**  
   Log back in to access the **Network Admin** menu, where you can manage your Multisite network and configure additional sites.  


### Additional Tips for Using Multisite with Raydium  

1. **Compatibility with Subdomains**  
   Subdomains work harmoniously with Raydium’s directory structure, eliminating conflicts and ensuring smooth operation.  

2. **Security Practices**  
   Regularly audit your setup to manage user roles and permissions across sites securely.  

3. **SEO and Scalability**  
   Subdomains allow for better SEO by creating distinct content silos while providing the flexibility to scale your network without path-based limitations.  

4. **Staging and Testing**  
   Always test your Multisite setup in a staging environment before deploying it live to ensure compatibility with your chosen subdomain configuration.  


### Looking Ahead  

Raydium is actively working to enhance compatibility with WordPress Multisite. Your feedback and experiences will help shape the framework's future support for advanced use cases, including Multisite. For now, subdomains offer the most reliable and efficient path to leverage Multisite with Raydium.   
