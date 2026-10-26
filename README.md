# TastyIgniter Web Installer

A standalone one-file web installer for [TastyIgniter](https://tastyigniter.com/) v4.x.
Upload a single PHP file and install TastyIgniter entirely through your browser — no SSH required.

## Requirements

- PHP 8.3+
- PHP extensions: `pdo`, `pdo_mysql`, `mbstring`, `json`, `openssl`, `curl`, `zip`, `tokenizer`, `xml`, `ctype`, `bcmath`
- MySQL database
- Writable web directory

## Before you upload: set your installer password

Open `install.php` in a text editor. Near the top you will find:

```php
define('INSTALL_TOKEN', '');
define('ALERT_EMAIL', '');
```

- **`INSTALL_TOKEN`** — type a password of your choice (at least 12 characters) between the quotes.
  The installer stays locked until you do, and asks for this password before it runs.
- **`ALERT_EMAIL`** — optional. Your email address, if you want a daily alert when PHP or `.htaccess`
  files on your site change. Leave it empty to only write alerts to `storage/logs/file_alerts.log`.

## Usage

### Fresh install (empty server)
1. Set your installer password (see above)
2. Upload `install.php` to your web root
3. Open `https://yourdomain.com/install.php` in your browser and enter your password
4. Follow the steps

### Re-install (existing `.htaccess` routing to `public/`)
1. Set your installer password (see above)
2. Upload `install.php` to the `public/` folder
3. Open `https://yourdomain.com/install.php` in your browser and enter your password
4. Follow the steps

## What it does

1. **Requirements** — checks PHP version and extensions
2. **Download** — downloads TastyIgniter from GitHub (git or ZIP)
3. **Configuration** — collects database credentials and site URL
4. **Install** — runs automatically:
   - Creates `.env` file
   - Downloads and runs Composer
   - Adds the avisaas Marketplace connector, so you can browse and install extensions from your admin panel
   - Sets storage permissions
   - Creates root `.htaccess`
   - Runs database migrations and `artisan igniter:install`
   - Applies security hardening (see below)
   - Caches config and routes
5. **Complete** — links to admin login

The Marketplace connector reports your site's domain and version to avisaas.

## Security

The installer applies these protections:

- PHP execution blocked in storage directories
- Double-extension uploads blocked (`shell.php.jpg`)
- Sensitive files blocked (`.env`, `.sql`, `.log`, `.sh`, `.zip`, `composer.json`, `composer.lock`)
- Security headers: `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, Content Security Policy
- `X-Powered-By` header removed
- Daily checks for suspicious code and changed files, plus a weekly Composer security audit (scheduled with cron when the server allows it)

**Delete `install.php` from your server immediately after installation.**
Leaving it accessible allows anyone who knows your installer password to re-run the installer and overwrite your database.

## Credits

Built by [AVi SaaS](https://avisaas.com/)
