# TastyIgniter Web Installer

A standalone one-file web installer for [TastyIgniter](https://tastyigniter.com/) v4.x.
Upload a single PHP file and install TastyIgniter entirely through your browser — no SSH required.

## Requirements

- PHP 8.3+
- PHP extensions: `pdo`, `pdo_mysql`, `mbstring`, `json`, `openssl`, `curl`, `zip`, `tokenizer`, `xml`, `ctype`, `bcmath`
- MySQL database
- Writable web directory

## Usage

### Fresh install (empty server)
1. Upload `install.php` to your web root
2. Open `https://yourdomain.com/install.php` in your browser
3. Follow the steps

### Re-install (existing `.htaccess` routing to `public/`)
1. Upload `install.php` to the `public/` folder
2. Open `https://yourdomain.com/install.php` in your browser
3. Follow the steps

## What it does

1. **Requirements** — checks PHP version and extensions
2. **Download** — clones TastyIgniter from GitHub (git or ZIP)
3. **Configuration** — collects database credentials and site URL
4. **Install** — runs automatically:
   - Creates `.env` file
   - Downloads and runs Composer
   - Publishes vendor assets
   - Sets storage permissions
   - Creates root `.htaccess`
   - Runs database migrations
   - Runs `artisan igniter:install`
   - Caches config and routes
5. **Complete** — links to admin login

## Security

**Delete `install.php` from your server immediately after installation.**
Leaving it accessible allows anyone to re-run the installer and overwrite your database.

## Credits

Built by [AVi SaaS](https://avisaas.com/)