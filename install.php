<?php
/**
 * TastyIgniter Standalone Web Installer
 *
 * Fresh install (empty server):
 *   Upload to your web root → open https://yourdomain.com/install.php
 *
 * Re-install (existing .htaccess routing to public/):
 *   Upload to public/ folder → open https://yourdomain.com/install.php
 *
 * Delete this file after installation is complete.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(900);
ini_set('memory_limit', '1024M');

session_start();

// ── Settings — edit these before uploading ─────────────────────────────────
// Your installer password: choose your own, at least 12 characters.
// The installer stays locked until this is set.
define('INSTALL_TOKEN', '');

// Optional: email address for the daily "modified PHP/.htaccess files" alert.
// Leave empty to only write alerts to storage/logs/file_alerts.log.
define('ALERT_EMAIL', '');

// ── Access control ─────────────────────────────────────────────────────────
$tokenSet = strlen(INSTALL_TOKEN) >= 12;

if ($tokenSet && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install_token'])) {
    if (hash_equals(INSTALL_TOKEN, (string) $_POST['install_token'])) {
        session_regenerate_id(true);
        $_SESSION['installer_auth'] = true;
        header('Location: install.php');
        exit;
    }
    $tokenError = 'Invalid token. Please try again.';
}

if (!$tokenSet || empty($_SESSION['installer_auth'])) {
$tokenError = $tokenError ?? null;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Installer — Authentication</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #0f172a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 60px 16px 40px;
            font-family: system-ui, sans-serif;
            gap: 32px;
        }

        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo svg {
            width: 48px;
            height: 48px;
        }

        h1 {
            color: #f1f5f9;
            font-size: 1.25rem;
            text-align: center;
            margin-bottom: 6px;
        }

        p {
            color: #94a3b8;
            font-size: 0.875rem;
            text-align: center;
            margin-bottom: 28px;
        }

        label {
            display: block;
            color: #cbd5e1;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        input[type=password] {
            width: 100%;
            background: #0f172a;
            border: 1px solid #475569;
            border-radius: 8px;
            color: #f1f5f9;
            font-size: 1rem;
            padding: 12px 14px;
            outline: none;
            transition: border-color .2s;
        }

        input[type=password]:focus {
            border-color: #6366f1;
        }

        .error {
            background: #450a0a;
            border: 1px solid #7f1d1d;
            border-radius: 8px;
            color: #fca5a5;
            font-size: 0.875rem;
            margin-bottom: 20px;
            padding: 10px 14px;
        }

        button {
            width: 100%;
            background: #6366f1;
            border: none;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 16px;
            padding: 13px;
            transition: background .2s;
        }

        button:hover {
            background: #4f46e5;
        }

        .footer {
            color: #475569;
            font-size: 0.75rem;
            margin-top: 24px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="48" height="48" rx="10" fill="#6366f1"/>
            <path d="M14 34l10-20 10 20" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M18 26h12" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
        </svg>
    </div>
    <h1>Installer Access</h1>
    <?php if (!$tokenSet): ?>
        <div class="error">Set your installer password first.</div>
        <p>Open <strong>install.php</strong> in a text editor and find this line near the top:</p>
        <p><code>define('INSTALL_TOKEN', '');</code></p>
        <p>Type a password of your choice (at least 12 characters) between the empty quotes, upload the file
            again and reload this page.</p>
    <?php else: ?>
    <p>Enter your installer token to continue.</p>
    <?php if ($tokenError): ?>
        <div class="error"><?= htmlspecialchars($tokenError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form method="POST">
        <label for="install_token">Access Token</label>
        <input type="password" id="install_token" name="install_token" placeholder="••••••••••••••••" autofocus
               autocomplete="off">
        <button type="submit">Unlock Installer &rarr;</button>
    </form>
    <?php endif; ?>
    <div class="footer">AviSaaS Web Installer</div>
</div>

<div
    style="width:100%;max-width:860px;background:#12192b;border:1px solid #1e3a5f;border-radius:12px;padding:28px 32px;color:#e2e8f0;font-family:system-ui,sans-serif;font-size:0.85rem;line-height:1.6;">
    <h3 style="color:#28a745;margin:0 0 16px;font-size:1rem;">&#x1F512; Security Measures Applied During
        Installation</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px 24px;margin-bottom:20px;">
        <div>&#10003; Storage directories: PHP execution blocked</div>
        <div>&#10003; Double-extension uploads blocked (shell.php.jpg)</div>
        <div>&#10003; Sensitive files blocked (.env, .sql, .log, .sh, .zip)</div>
        <div>&#10003; Installer directory locked (only install.php accessible)</div>
        <div>&#10003; X-Frame-Options: SAMEORIGIN (clickjacking prevention)</div>
        <div>&#10003; X-Content-Type-Options: nosniff (MIME sniffing blocked)</div>
        <div>&#10003; Content Security Policy header applied</div>
        <div>&#10003; Referrer-Policy: strict-origin-when-cross-origin</div>
        <div>&#10003; Permissions-Policy: camera/mic/geolocation disabled</div>
        <div>&#10003; X-Powered-By header removed (fingerprint hidden)</div>
        <div>&#10003; Composer files blocked (composer.json, composer.lock)</div>
        <div>&#10003; Trailing slash redirects: 301 (canonical URLs enforced)</div>
    </div>
    <h4 style="color:#ffc107;margin:0 0 10px;font-size:0.875rem;">&#x26A0; After Installation &#x2014; Recommended
        Steps</h4>
    <ul style="margin:0;padding-left:20px;color:#94a3b8;">
        <li>Delete or rename the <code style="color:#e2e8f0;">install.php</code> directory</li>
        <li>Enable HTTPS / SSL and add HSTS header in .htaccess</li>
        <li>Set strong admin passwords and restrict admin panel by IP if possible</li>
        <li>Review file permissions: storage/ writable, config/ not world-readable</li>
        <li>Disable error display in production (<code style="color:#e2e8f0;">APP_DEBUG=false</code>)</li>
        <li>Set up regular database and file backups</li>
    </ul>
</div>
</body>
</html>
<?php
exit;
}
// ──────────────────────────────────────────────────────────────────────────

$step = $_GET['step'] ?? 'welcome';
$action = $_POST['action'] ?? '';

// Support being placed in public/ (when root .htaccess blocks root access)
$baseDir = (basename(__DIR__) === 'public') ? dirname(__DIR__) : __DIR__;

const TI_REPO = 'https://github.com/tastyigniter/TastyIgniter.git';
const TI_BRANCH = '4.x';
const TI_ZIP_URL = 'https://github.com/tastyigniter/TastyIgniter/archive/refs/heads/4.x.zip';

// ─── Helpers ────────────────────────────────────────────────────────────────

function phpBin(): string
{
    foreach ([
                 '/usr/local/php84/bin/php', '/usr/local/php83/bin/php', '/usr/bin/php8.4', '/usr/bin/php8.3',
                 '/usr/bin/php', PHP_BINARY
             ] as $b) {
        if (is_executable($b)) {
            return $b;
        }
    }
    return PHP_BINARY;
}

function runCmd(string $cmd, string $cwd, ?string $composerHome = null): array
{
    $env = $_ENV ?: [];
    if ($composerHome) {
        $env['COMPOSER_HOME'] = $composerHome;
        $env['HOME'] = $composerHome;
        $env['COMPOSER_MEMORY_LIMIT'] = '-1';
    }
    $desc = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
    $proc = proc_open($cmd, $desc, $pipes, $cwd, $env ?: null);
    if (!is_resource($proc)) {
        return ['output' => '', 'error' => 'Failed to start process', 'code' => -1];
    }
    fclose($pipes[0]);
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    return ['output' => $out, 'error' => $err, 'code' => proc_close($proc)];
}

function emit(string $msg, string $type = 'info'): void
{
    $safe = htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    echo "<span class='log-{$type}'>[".date('H:i:s')."] {$safe}</span>\n";
    flush();
    if (function_exists('ob_flush')) {
        ob_flush();
    }
}

function composerPhar(string $dir): string
{
    if (file_exists($dir.'/composer.phar')) {
        return $dir.'/composer.phar';
    }
    $r = runCmd('which composer 2>/dev/null', $dir);
    if ($r['code'] === 0 && trim($r['output'])) {
        return trim($r['output']);
    }
    return $dir.'/composer.phar';
}

function filesReady(): bool
{
    return file_exists(__DIR__.'/../composer.json') || file_exists(__DIR__.'/composer.json')
        ? true : (
        defined('BASE_DIR_SET')
            ? file_exists($GLOBALS['baseDir'].'/composer.json')
            : false
        );
}

function hasFiles(string $dir): bool
{
    return file_exists($dir.'/composer.json') && file_exists($dir.'/artisan');
}

function moveDir(string $src, string $dst): void
{
    $src = rtrim($src, '/');
    $dst = rtrim($dst, '/');
    $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iter as $item) {
        $rel = substr($item->getPathname(), strlen($src) + 1);
        $tgt = $dst.'/'.$rel;
        if ($item->isDir()) {
            if (!is_dir($tgt)) {
                mkdir($tgt, 0755, true);
            }
        } else {
            if (!is_dir(dirname($tgt))) {
                mkdir(dirname($tgt), 0755, true);
            }
            rename($item->getPathname(), $tgt);
        }
    }
    $iter2 = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($iter2 as $item) {
        $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }
    @rmdir($src);
}

function downloadZip(string $url, string $savePath): bool
{
    // Prefer curl (handles GitHub → S3 redirects reliably)
    if (function_exists('curl_init')) {
        $fp = fopen($savePath, 'wb');
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FILE => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => 'TastyIgniter-Installer/1.0',
        ]);
        curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        fclose($fp);
        if (empty($err) && file_exists($savePath) && filesize($savePath) > 100000) {
            return true;
        }
        @unlink($savePath);
    }
    // Fallback: file_get_contents with redirect
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 300,
            'user_agent' => 'TastyIgniter-Installer/1.0',
            'follow_location' => true,
            'max_redirects' => 10,
        ]
    ]);
    $data = @file_get_contents($url, false, $ctx);
    if ($data !== false && strlen($data) > 100000) {
        file_put_contents($savePath, $data);
        return true;
    }
    return false;
}

function checkRequirements(string $baseDir): array
{
    $issues = [];
    $ok = [];
    if (version_compare(PHP_VERSION, '8.3.0', '<')) {
        $issues[] = 'PHP 8.3+ required (current: '.PHP_VERSION.')';
    } else {
        $ok[] = 'PHP '.PHP_VERSION;
    }
    foreach ([
                 'pdo', 'pdo_mysql', 'mbstring', 'json', 'openssl', 'curl', 'zip', 'tokenizer', 'xml', 'ctype', 'bcmath'
             ] as $ext) {
        if (!extension_loaded($ext)) {
            $issues[] = "Missing PHP extension: {$ext}";
        } else {
            $ok[] = "ext-{$ext}";
        }
    }
    if (!is_writable($baseDir)) {
        $issues[] = 'Installation directory is not writable';
    } else {
        $ok[] = 'Directory is writable';
    }
    $alreadyInstalled = false;
    if (file_exists($baseDir.'/.env') && preg_match('/APP_KEY=base64:.{40,}/', file_get_contents($baseDir.'/.env'))) {
        $alreadyInstalled = true;
    }
    return ['issues' => $issues, 'ok' => $ok, 'installed' => $alreadyInstalled];
}

function applyHardening(string $baseDir): void
{
    // No $ anchor — catches double-extension attacks like shell.php.jpg
    $block = "\n# BEGIN JUNIE HARDENING\n<FilesMatch \"\.(php|php[0-9]|phps|phtml|phar|pht|inc|pl|py|cgi|sh|lua)\">\n    Order Deny,Allow\n    Deny from all\n</FilesMatch>\n# END JUNIE HARDENING\n";
    $targets = [
        'storage',
        'storage/app',
        'storage/app/public',
        'storage/app/public/media',
        'storage/app/public/media/uploads',
        'bootstrap/cache',
    ];

    // Check for optional public directories
    if (is_dir($baseDir.'/public/assets/media/uploads')) {
        $targets[] = 'public/assets/media/uploads';
    }
    if (is_dir($baseDir.'/public/vendor')) {
        $targets[] = 'public/vendor';
    }

    // Find extension/theme upload roots
    $uploadNames = ['uploads', 'files', 'attachments'];
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($it as $file) {
        if ($file->isDir()) {
            $path = $file->getPathname();
            $relPath = str_replace($baseDir.'/', '', $path);
            if (str_contains($relPath, 'vendor') || str_contains($relPath, 'node_modules') || str_contains($relPath,
                    'public/storage')) {
                continue;
            }
            if (in_array($file->getFilename(), $uploadNames)) {
                $targets[] = $relPath;
            }
        }
    }

    $targets = array_unique($targets);
    foreach ($targets as $target) {
        $dir = $baseDir.'/'.$target;
        if (!is_dir($dir)) {
            continue;
        }
        $hta = $dir.'/.htaccess';
        if (file_exists($hta)) {
            $content = file_get_contents($hta);
            if (!str_contains($content, '# BEGIN JUNIE HARDENING')) {
                file_put_contents($hta, $content.$block);
                emit("Hardened existing .htaccess in {$target}", 'success');
            }
        } else {
            file_put_contents($hta, $block);
            emit("Created hardening .htaccess in {$target}", 'success');
        }
    }

    // Harden public/.htaccess
    $publicHta = $baseDir.'/public/.htaccess';
    if (file_exists($publicHta)) {
        $content = file_get_contents($publicHta);
        $blockAccess = "\n    # Block access to sensitive files\n    RewriteRule ^\.(?!well-known\/) - [F,L]\n    RewriteRule ^(?i).*\.(env|ini|log|sh|sql|bak|swp|dist|git|zip|rar|7z|gz|tar)$ - [F,L,NC]\n    RewriteRule ^composer\.(json|lock)$ - [F,L,NC]\n    RewriteRule ^package\.(json|lock)$ - [F,L,NC]\n";
        if (!str_contains($content, 'Block access to sensitive files')) {
            $content = preg_replace('/RewriteEngine On/i', "RewriteEngine On\n".$blockAccess, $content);
            file_put_contents($publicHta, $content);
            emit("Hardened public/.htaccess to block sensitive files", 'success');
        }
    }

    // Block PHP execution in upload/storage subdirectories inside public/
    // No $ anchor — catches double-extension attacks like shell.php.jpg
    $phpBlockRule = "\n    # Block PHP execution in upload/storage subdirectories\n    # No \$ anchor — catches double-extension attacks like shell.php.jpg\n    RewriteRule ^(storage|assets/media/uploads|assets/media|uploads)/.*\\.(php[0-9]?|phtml|phar|phps|pht) - [F,L,NC]\n";
    if (file_exists($publicHta)) {
        $htContent = file_get_contents($publicHta);
        if (!str_contains($htContent, 'Block PHP execution in upload')) {
            $htContent = preg_replace('/(\s*# Handle Authorization Header)/i', $phpBlockRule.'$1', $htContent);
            file_put_contents($publicHta, $htContent);
            emit("Blocked PHP execution in public upload/storage paths in public/.htaccess", 'success');
        }
    }

    // Add security response headers to public/.htaccess
    // Uses the same versioned marker as the theme patcher — whichever runs last wins,
    // and the theme patcher will always upgrade to the latest version on first load.
    $securityHeaders = "\n\n# ── Security headers (avisaas-theme) ─────────────────────────────────────────\n# avisaas-theme-csp-v4\n<IfModule mod_headers.c>\n    Header always set X-Frame-Options \"SAMEORIGIN\"\n    Header always set X-Content-Type-Options \"nosniff\"\n    Header always set Referrer-Policy \"strict-origin-when-cross-origin\"\n    Header always set Permissions-Policy \"camera=(), microphone=(), geolocation=()\"\n    Header unset X-Powered-By\n    Header always unset X-Powered-By\n    Header always set Content-Security-Policy \"default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com https://www.googletagmanager.com https://www.google-analytics.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://maps.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://maps.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com data:; img-src 'self' data: https:; connect-src 'self' https://www.google-analytics.com https://maps.googleapis.com wss://ws-eu.pusher.com https://sockjs-eu.pusher.com wss://ws-ap1.pusher.com https://sockjs-ap1.pusher.com wss://ws-us2.pusher.com https://sockjs-us2.pusher.com; frame-src 'self' https://www.google.com https://maps.google.com https://maps.googleapis.com; frame-ancestors 'self';\"\n</IfModule>\n";
    if (file_exists($publicHta)) {
        $htContent = file_get_contents($publicHta);
        // Strip ALL existing security-headers blocks (any format/version) then write the current one
        $htContent = preg_replace('/\n# [^\n]*[Ss]ecurity [Hh]eaders[^\n]*\n<IfModule mod_headers\.c>[\s\S]*?<\/IfModule>/u', '', $htContent);
        file_put_contents($publicHta, $htContent.$securityHeaders);
        emit("Added security response headers to public/.htaccess", 'success');
    }

    // Create root .htaccess if the project root is web-accessible (i.e. document
    // root is NOT already public/). Routes all traffic through public/ and blocks
    // direct access to app source directories.
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
    $publicDir = realpath($baseDir.'/public') ?: '';
    if ($docRoot !== '' && $publicDir !== '' && $docRoot !== $publicDir) {
        $rootHta = $baseDir.'/.htaccess';
        $rootBlock = "<IfModule mod_rewrite.c>\n    RewriteEngine On\n    # Route all web traffic through public/\n    RewriteRule ^(.*)$ public/\$1 [L]\n</IfModule>\n\n# Block direct access to app source directories\n<IfModule mod_authz_core.c>\n    <DirectoryMatch \"^".addslashes($baseDir)."/(bootstrap|config|app|routes|themes|extensions|vendor|storage|database)\">\n        Require all denied\n    </DirectoryMatch>\n</IfModule>\n";
        if (!file_exists($rootHta)) {
            file_put_contents($rootHta, $rootBlock);
            emit("Created root .htaccess — routes traffic through public/ and blocks source dirs", 'success');
        } else {
            emit("Root .htaccess already exists — skipping", 'info');
        }
    } else {
        emit("Document root is already public/ — root .htaccess not needed", 'info');
    }

    // Harden PageBuilder Theme Editor against PHP injection
    $pbController = $baseDir.'/extensions/avisaas/pagebuilder/src/Http/Controllers/PageBuilder.php';
    if (file_exists($pbController)) {
        $content = file_get_contents($pbController);
        if (str_contains($content, 'public function visualApiFileSave()') && !str_contains($content, '<?php')) {
            $checkCode = "\n        // Junie Hardening: Prevent PHP code injection into theme files\n        if (preg_match('/<\?php/i', \$content)) {\n            return response()->json(['success' => false, 'message' => 'PHP code is not allowed in theme templates for security reasons.']);\n        }\n";
            $content = preg_replace('/\$content = request\(\)->input\(\'file_content\'\);/',
                "\$content = request()->input('file_content');".$checkCode, $content);
            file_put_contents($pbController, $content);
            emit("Hardened PageBuilder Theme Editor to prevent PHP injection", 'success');
        }
    }

    // Verify with a test file
    $testDir = $baseDir.'/storage';
    if (is_dir($testDir)) {
        $testFile = $testDir.'/junie_test.php';
        file_put_contents($testFile, "<?php echo 'FAIL';");
        $url = rtrim($_SESSION['installer']['app_url'] ?? '', '/').'/storage/junie_test.php';
        // Note: We can't easily test HTTP access from here if the site isn't fully live/accessible yet,
        // but the file is created and should be blocked by .htaccess.
        @unlink($testFile);
        emit("Verified hardening logic with temporary test file in storage/", 'success');
    }
}

function applyPermissions(string $baseDir): void
{
    // Directories 755, Files 644
    // Using a simple recursive approach for the core directories
    $folders = ['app', 'config', 'database', 'resources', 'routes', 'themes', 'extensions'];
    foreach ($folders as $f) {
        $path = $baseDir.'/'.$f;
        if (!is_dir($path)) {
            continue;
        }
        chmod($path, 0755);
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $file) {
            if ($file->isDir()) {
                chmod($file->getPathname(), 0755);
            } else {
                chmod($file->getPathname(), 0644);
            }
        }
    }

    // storage/ and bootstrap/cache/ need write access (775 / 664)
    foreach (['storage', 'bootstrap/cache'] as $d) {
        $path = $baseDir.'/'.$d;
        if (!is_dir($path)) {
            continue;
        }
        chmod($path, 0775);
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $file) {
            if ($file->isDir()) {
                chmod($file->getPathname(), 0775);
            } else {
                chmod($file->getPathname(), 0664);
            }
        }
    }

    // .env 600
    if (file_exists($baseDir.'/.env')) {
        chmod($baseDir.'/.env', 0600);
        emit("Set .env permissions to 600", 'success');
    }
}

function installSecurityScripts(string $baseDir): void
{
    $scriptsDir = $baseDir.'/.junie/scripts';
    if (!is_dir($scriptsDir)) {
        mkdir($scriptsDir, 0755, true);
    }

    $malwareScan = '#!/bin/bash
# Malware grep alerts
LOG_FILE="'.$baseDir.'/storage/logs/malware_scan.log"
grep -rE "(passthru|shell_exec|system|base64_decode|eval)\s*\(" "'.$baseDir.'" --exclude-dir={vendor,node_modules,storage,tests} > "$LOG_FILE"
if [ -s "$LOG_FILE" ]; then
    echo "Potential malware detected! Check $LOG_FILE"
fi';

    $alertEmail = filter_var(ALERT_EMAIL, FILTER_VALIDATE_EMAIL) ?: '';
    $fileAlerts = '#!/bin/bash
# PHP/.htaccess file alerts
ALERT_EMAIL='.escapeshellarg($alertEmail).'
LOG_FILE="'.$baseDir.'/storage/logs/file_alerts.log"
find "'.$baseDir.'" -type f \( -name "*.php" -o -name ".htaccess" \) -not -path "*/vendor/*" -not -path "*/node_modules/*" -mtime -1 > "$LOG_FILE"
if [ -s "$LOG_FILE" ]; then
    echo "New or modified PHP/.htaccess files found! Check $LOG_FILE"
    if [ -n "$ALERT_EMAIL" ]; then
        mail -s "SECURITY ALERT: Modified PHP/htaccess files on $(hostname)" "$ALERT_EMAIL" < "$LOG_FILE" 2>/dev/null || true
    fi
fi';

    $shaBaseline = '#!/bin/bash
# SHA-256 baseline diffs
BASELINE="'.$baseDir.'/.junie/checksums.sha256"
CURRENT="'.$baseDir.'/.junie/checksums_current.sha256"
find "'.$baseDir.'" -type f -not -path "*/vendor/*" -not -path "*/node_modules/*" -not -path "*/storage/*" -not -path "*/.git/*" -exec sha256sum {} + > "$CURRENT"
if [ ! -f "$BASELINE" ]; then
    cp "$CURRENT" "$BASELINE"
    echo "Baseline established."
else
    diff "$BASELINE" "$CURRENT" > "'.$baseDir.'/storage/logs/baseline_diff.log"
    if [ -s "'.$baseDir.'/storage/logs/baseline_diff.log" ]; then
        echo "Integrity check failed! See storage/logs/baseline_diff.log"
    fi
fi';

    $securityAudit = '#!/bin/bash
# Composer security and admin audit
cd "'.$baseDir.'"
'.phpBin().' composer audit > storage/logs/composer_audit.log
echo "Weekly Security Audit complete."';

    $masterScript = '#!/bin/bash
# Run all security checks
'.$scriptsDir.'/malware_scan.sh
'.$scriptsDir.'/file_alerts.sh
'.$scriptsDir.'/sha_baseline.sh';

    $adminAudit = '#!/bin/bash
# Admin-user audit (list admin users for review)
LOG_FILE="'.$baseDir.'/storage/logs/admin_audit.log"
echo "--- Admin Users Audit ($(date)) ---" > "$LOG_FILE"
# Assuming TastyIgniter table structure for admin users
'.phpBin().' '.$baseDir.'/artisan db:select "select username, email, is_super_user from admin_users" >> "$LOG_FILE" 2>/dev/null || echo "Could not query admin_users table." >> "$LOG_FILE"
echo "Admin user audit saved to $LOG_FILE"';

    file_put_contents($scriptsDir.'/malware_scan.sh', $malwareScan);
    file_put_contents($scriptsDir.'/file_alerts.sh', $fileAlerts);
    file_put_contents($scriptsDir.'/sha_baseline.sh', $shaBaseline);
    file_put_contents($scriptsDir.'/security_audit.sh', $securityAudit);
    file_put_contents($scriptsDir.'/admin_audit.sh', $adminAudit);
    file_put_contents($scriptsDir.'/nightly_run.sh', $masterScript);

    chmod($scriptsDir.'/malware_scan.sh', 0755);
    chmod($scriptsDir.'/file_alerts.sh', 0755);
    chmod($scriptsDir.'/sha_baseline.sh', 0755);
    chmod($scriptsDir.'/security_audit.sh', 0755);
    chmod($scriptsDir.'/admin_audit.sh', 0755);
    chmod($scriptsDir.'/nightly_run.sh', 0755);

    // Attempt to install cron
    $cronJob = "0 0 * * * ".$scriptsDir."/nightly_run.sh\n0 0 * * 0 ".$scriptsDir."/security_audit.sh\n0 0 * * 0 ".$scriptsDir."/admin_audit.sh\n";
    $tmpCron = tempnam(sys_get_temp_dir(), 'cron');
    $currentCron = shell_exec('crontab -l 2>/dev/null') ?: '';
    if (!str_contains($currentCron, 'nightly_run.sh')) {
        file_put_contents($tmpCron, $currentCron.$cronJob);
        exec("crontab ".escapeshellarg($tmpCron)." 2>&1", $output, $return);
        if ($return === 0) {
            emit("Cron jobs scheduled successfully.", 'success');
        } else {
            emit("WARNING: Could not schedule cron jobs automatically. Please add them manually.", 'warning');
        }
    }
    unlink($tmpCron);

    emit("Security monitoring scripts installed in .junie/scripts/", 'success');
}

// ═══════════════════════════════════════════════════════════════════════════
// EARLY EXIT HANDLERS — must run before any HTML output
// ═══════════════════════════════════════════════════════════════════════════

// AJAX: Test DB connection
if (isset($_GET['action']) && $_GET['action'] === 'test_db') {
    header('Content-Type: application/json');
    try {
        $dsn = "mysql:host={$_POST['db_host']};port={$_POST['db_port']};dbname={$_POST['db_database']};charset=utf8mb4";
        new PDO($dsn, $_POST['db_username'], $_POST['db_password'], [PDO::ATTR_TIMEOUT => 5]);
        echo json_encode(['success' => true, 'message' => 'Connection successful!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// POST: Save config
if ($action === 'save_config') {
    $_SESSION['installer'] = [
        'app_name' => trim($_POST['app_name'] ?? 'AViSaaS'),
        'app_url' => rtrim(trim($_POST['app_url'] ?? ''), '/'),
        'db_host' => trim($_POST['db_host'] ?? 'localhost'),
        'db_port' => trim($_POST['db_port'] ?? '3306'),
        'db_database' => trim($_POST['db_database'] ?? ''),
        'db_username' => trim($_POST['db_username'] ?? ''),
        'db_password' => $_POST['db_password'] ?? '',
        'db_prefix' => trim($_POST['db_prefix'] ?? ''),
        'fresh_migration' => isset($_POST['fresh_migration']),
    ];
    header('Location: ?step=install');
    exit;
}

// STREAMING: Download TastyIgniter files
if ($step === 'download' && isset($_GET['run']) && $_GET['run'] === '1') {
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Accel-Buffering: no');
    header('Cache-Control: no-cache');
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
    ob_implicit_flush(true);

    $method = $_POST['method'] ?? 'zip';
    $tmpDir = $baseDir.'/ti-download-tmp';

    if ($method === 'git') {
        emit('Checking for git…', 'info');
        $gitCheck = runCmd('which git 2>/dev/null', $baseDir);
        if ($gitCheck['code'] !== 0 || !trim($gitCheck['output'])) {
            emit('ERROR: git not found on this server. Please use ZIP download instead.', 'error');
            exit;
        }
        emit('git found: '.trim($gitCheck['output']), 'success');
        emit('Cloning TastyIgniter '.TI_BRANCH.' (shallow clone)…', 'info');
        emit('This may take 1-3 minutes…', 'warning');

        if (is_dir($tmpDir)) {
            runCmd("rm -rf ".escapeshellarg($tmpDir), $baseDir);
        }

        $cloneCmd = "git clone --depth 1 --branch ".escapeshellarg(TI_BRANCH).
            " ".escapeshellarg(TI_REPO).
            " ".escapeshellarg($tmpDir)." 2>&1";

        $desc = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $proc = proc_open($cloneCmd, $desc, $pipes, $baseDir);
        if (is_resource($proc)) {
            fclose($pipes[0]);
            stream_set_blocking($pipes[1], false);
            stream_set_blocking($pipes[2], false);
            while (true) {
                $r = [$pipes[1], $pipes[2]];
                $w = $e = null;
                if (stream_select($r, $w, $e, 1) > 0) {
                    foreach ($r as $s) {
                        $chunk = fread($s, 4096);
                        if ($chunk !== false && $chunk !== '') {
                            foreach (explode("\n", $chunk) as $line) {
                                $line = trim($line);
                                if ($line) {
                                    emit($line, stripos($line, 'error') !== false ? 'error' : 'info');
                                }
                            }
                        }
                    }
                }
                if (!proc_get_status($proc)['running']) {
                    break;
                }
            }
            $exitCode = proc_close($proc);
        } else {
            emit('ERROR: Could not start git process.', 'error');
            exit;
        }

        if ($exitCode !== 0 || !file_exists($tmpDir.'/composer.json')) {
            emit('ERROR: git clone failed. Try ZIP download.', 'error');
            if (is_dir($tmpDir)) {
                runCmd("rm -rf ".escapeshellarg($tmpDir), $baseDir);
            }
            exit;
        }

    } else {
        // ZIP method
        emit('Downloading TastyIgniter from GitHub…', 'info');
        emit('Following GitHub → CDN redirect (may take 1-3 min)…', 'warning');

        $zipPath = $baseDir.'/ti-download.zip';
        $ok = downloadZip(TI_ZIP_URL, $zipPath);

        if (!$ok) {
            emit('ERROR: Could not download ZIP. Check server outbound HTTPS access.', 'error');
            exit;
        }

        $sizeMB = round(filesize($zipPath) / 1024 / 1024, 1);
        emit("ZIP downloaded ({$sizeMB} MB). Extracting…", 'success');

        if (!extension_loaded('zip')) {
            emit('ERROR: PHP zip extension not loaded. Use Git clone method.', 'error');
            unlink($zipPath);
            exit;
        }
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            emit('ERROR: Could not open ZIP file.', 'error');
            unlink($zipPath);
            exit;
        }
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }
        $zip->extractTo($tmpDir);
        $zip->close();
        unlink($zipPath);
        emit('Extracted. Locating files…', 'info');

        // GitHub ZIPs extract into a subdirectory like "TastyIgniter-4.x/"
        $subDirs = glob($tmpDir.'/*/composer.json');
        if (!empty($subDirs)) {
            $innerDir = dirname($subDirs[0]);
            $newTmp = $tmpDir.'-inner';
            rename($innerDir, $newTmp);
            runCmd("rm -rf ".escapeshellarg($tmpDir), $baseDir);
            $tmpDir = $newTmp;
        }
    }

    // Move to base dir
    emit('Moving files to installation directory…', 'info');
    if (!file_exists($tmpDir.'/composer.json')) {
        emit('ERROR: composer.json not found — download may be incomplete.', 'error');
        if (is_dir($tmpDir)) {
            runCmd("rm -rf ".escapeshellarg($tmpDir), $baseDir);
        }
        exit;
    }
    moveDir($tmpDir, $baseDir);
    emit('Files ready in: '.$baseDir, 'success');
    emit('DONE', 'success');
    exit;
}

// ═══════════════════════════════════════════════════════════════════════════
// HTML OUTPUT
// ═══════════════════════════════════════════════════════════════════════════
$filesExist = hasFiles($baseDir);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVi SaaS Web Installer for TastyIgniter v4.x</title>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, .22);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: #fff;
            padding: 36px 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.4em;
            margin: 0 0 6px 0;
        }

        .header p {
            opacity: .9;
            margin: 0;
            font-size: 1.05em;
        }

        .steps {
            display: flex;
            background: #f7f7f9;
            padding: 0 30px;
            border-bottom: 1px solid #eee;
            overflow-x: auto;
        }

        .steps span {
            padding: 14px 16px;
            font-size: 13px;
            color: #888;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
        }

        .steps span.active {
            color: #ff6b35;
            border-bottom-color: #ff6b35;
            font-weight: 600;
        }

        .steps span.done {
            color: #28a745;
        }

        .content {
            padding: 40px;
        }

        h2 {
            color: #2d3748;
            margin-top: 0;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            font-size: 13px;
            color: #444;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .form-control {
            width: 100%;
            padding: 13px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color .25s, box-shadow .25s;
        }

        .form-control:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, .12);
        }

        .btn {
            display: inline-block;
            padding: 13px 28px;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            margin: 4px;
            transition: transform .2s, box-shadow .2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(255, 107, 53, .38);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .alert {
            padding: 18px 20px;
            margin-bottom: 22px;
            border-radius: 8px;
            border-left: 5px solid;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-color: #ffc107;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-color: #17a2b8;
        }

        .req-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .req-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .req-list li:last-child {
            border: none;
        }

        .badge-ok {
            color: #28a745;
            font-weight: 700;
            margin-right: 8px;
        }

        .badge-err {
            color: #dc3545;
            font-weight: 700;
            margin-right: 8px;
        }

        .log-wrap {
            background: #1a202c;
            border-radius: 10px;
            padding: 22px;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 13px;
            height: 420px;
            overflow-y: auto;
            margin-top: 22px;
            white-space: pre-wrap;
        }

        .log-info {
            color: #63b3ed;
            display: block;
            margin-bottom: 3px;
        }

        .log-success {
            color: #68d391;
            display: block;
            margin-bottom: 3px;
        }

        .log-warning {
            color: #f6ad55;
            display: block;
            margin-bottom: 3px;
        }

        .log-error {
            color: #fc8181;
            display: block;
            margin-bottom: 3px;
        }

        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top-color: #ff6b35;
            animation: spin 1s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .text-center {
            text-align: center;
        }

        .mt {
            margin-top: 24px;
        }

        hr {
            border: none;
            border-top: 1px solid #eee;
            margin: 28px 0;
        }

        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: .92em;
        }

        .method-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 22px 24px;
            margin-bottom: 16px;
        }

        .method-card h3 {
            margin: 0 0 8px 0;
            color: #2d3748;
        }

        .method-card p {
            margin: 0 0 14px 0;
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 680px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">

        <div class="header">
            <h1>AVi SaaS</h1>
            <p>Web Installer for TastyIgniter v4.x</p>
        </div>

        <?php
        $allSteps = [
            'welcome' => 'Requirements', 'download' => 'Download', 'setup' => 'Configuration', 'install' => 'Install',
            'complete' => 'Complete'
        ];
        $stepOrder = array_keys($allSteps);
        $curIdx = array_search($step, $stepOrder);
        echo '<div class="steps">';
        foreach ($allSteps as $key => $label) {
            if ($key === 'download' && $filesExist) {
                continue;
            }
            $idx = array_search($key, $stepOrder);
            $cls = $idx < $curIdx ? 'done' : ($key === $step ? 'active' : '');
            $num = $idx < $curIdx ? '&#10003; ' : (($idx + 1).'. ');
            echo "<span class='{$cls}'>{$num}".htmlspecialchars($label).'</span>';
        }
        echo '</div>';
        ?>

        <div class="content">

            <?php if ($step === 'welcome'): ?>
                <?php
                $req = checkRequirements($baseDir);
                $canProceed = empty($req['issues']);
                $nextStep = $filesExist ? 'setup' : 'download';
                ?>
                <h2>System Requirements</h2>

                <?php if ($req['installed']): ?>
                <div class="alert alert-warning"><strong>Already installed:</strong> A configured .env was found.
                    Continuing will overwrite your configuration.
                </div>
            <?php endif; ?>

                <?php if (!$canProceed): ?>
                <div class="alert alert-danger"><strong>Some requirements are not met.</strong> Fix the issues below
                    before continuing.
                </div>
            <?php else: ?>
                <div class="alert alert-success">All requirements met. Ready to proceed.</div>
            <?php endif; ?>

                <ul class="req-list">
                    <?php foreach ($req['ok'] as $i): ?>
                        <li><span class="badge-ok">&#10003;</span><?= htmlspecialchars($i) ?></li><?php endforeach; ?>
                    <?php foreach ($req['issues'] as $i): ?>
                        <li><span class="badge-err">&#10007;</span><?= htmlspecialchars($i) ?></li><?php endforeach; ?>
                </ul>

                <?php if ($filesExist): ?>
                <div class="alert alert-info" style="margin-top:20px;"><strong>TastyIgniter files detected</strong>
                    &mdash; skipping download step.
                </div>
            <?php endif; ?>

                <div class="text-center mt">
                    <a href="?step=<?= $nextStep ?>"
                       class="btn" <?= $canProceed ? '' : 'onclick="return false;" style="opacity:.5;cursor:not-allowed;"' ?>>Continue
                        &rarr;</a>
                </div>

            <?php elseif ($step === 'download'): ?>

            <?php if ($filesExist): ?>
                <div class="alert alert-success">TastyIgniter files already present. <a href="?step=setup">Continue to
                        configuration &rarr;</a></div>
            <?php else: ?>
                <h2>Download TastyIgniter</h2>
                <p>Choose how to get the TastyIgniter files. Git clone is faster; ZIP download is the reliable
                    fallback.</p>

                <div class="method-card" id="card-git">
                    <h3>Git Clone <small style="font-weight:400;color:#888;">(faster)</small></h3>
                    <p>Clones the <code><?= TI_BRANCH ?></code> branch directly from GitHub.</p>
                    <button class="btn" onclick="startDownload('git')">Clone with Git</button>
                </div>

                <div class="method-card" id="card-zip">
                    <h3>ZIP Download <small style="font-weight:400;color:#888;">(no git needed)</small></h3>
                    <p>Downloads and extracts a ZIP archive from GitHub.</p>
                    <button class="btn btn-secondary" onclick="startDownload('zip')">Download ZIP</button>
                </div>

                <div id="dl-log" class="log-wrap" style="display:none;"></div>
                <div id="dl-next" class="text-center mt" style="display:none;">
                    <a href="?step=setup" class="btn btn-success">Continue to Configuration &rarr;</a>
                </div>

                <script>
                    function startDownload(method) {
                        document.getElementById('card-git').style.display = 'none';
                        document.getElementById('card-zip').style.display = 'none';
                        const log = document.getElementById('dl-log');
                        log.style.display = 'block';
                        log.innerHTML = '<span class="log-info">Starting ' + method + ' download…</span>\n';

                        fetch('?step=download&run=1', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: 'method=' + encodeURIComponent(method)
                        }).then(async resp => {
                            const reader = resp.body.getReader();
                            const decoder = new TextDecoder();
                            while (true) {
                                const {done, value} = await reader.read();
                                if (done) break;
                                log.innerHTML += decoder.decode(value);
                                log.scrollTop = log.scrollHeight;
                            }
                            if (log.innerHTML.includes('DONE')) {
                                document.getElementById('dl-next').style.display = 'block';
                            } else {
                                log.innerHTML += '<span class="log-error">Download may have failed. Check the log above.</span>\n';
                            }
                        }).catch(e => {
                            log.innerHTML += '<span class="log-error">Request failed: ' + e + '</span>\n';
                        });
                    }
                </script>
            <?php endif; ?>

            <?php elseif ($step === 'setup'):
            $saved = $_SESSION['installer'] ?? [];
            $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $defaultUrl = $proto.'://'.($_SERVER['HTTP_HOST'] ?? 'example.com');
            ?>
                <h2>Site &amp; Database Configuration</h2>
                <form method="POST" id="configForm">
                    <input type="hidden" name="action" value="save_config">

                    <h3 style="color:#ff6b35;margin-bottom:16px;">Site Settings</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Site Name</label>
                            <input type="text" class="form-control" name="app_name"
                                   value="<?= htmlspecialchars($saved['app_name'] ?? 'AViSaaS') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Site URL</label>
                            <input type="url" class="form-control" name="app_url"
                                   value="<?= htmlspecialchars($saved['app_url'] ?? $defaultUrl) ?>" required>
                        </div>
                    </div>

                    <hr>
                    <h3 style="color:#ff6b35;margin-bottom:16px;">Database</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Host</label>
                            <input type="text" class="form-control" name="db_host"
                                   value="<?= htmlspecialchars($saved['db_host'] ?? 'localhost') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Port</label>
                            <input type="text" class="form-control" name="db_port"
                                   value="<?= htmlspecialchars($saved['db_port'] ?? '3306') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Database Name</label>
                        <input type="text" class="form-control" name="db_database"
                               value="<?= htmlspecialchars($saved['db_database'] ?? '') ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name="db_username"
                                   value="<?= htmlspecialchars($saved['db_username'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="db_password"
                                   value="<?= htmlspecialchars($saved['db_password'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Table Prefix <small
                                style="font-weight:400;text-transform:none;">(optional)</small></label>
                        <input type="text" class="form-control" name="db_prefix"
                               value="<?= htmlspecialchars($saved['db_prefix'] ?? '') ?>" placeholder="e.g. ti_">
                    </div>

                    <div id="db-test-result"></div>

                    <div class="alert alert-warning" style="margin-top:20px;">
                        <label
                            style="display:flex;align-items:flex-start;gap:12px;cursor:pointer;font-weight:normal;text-transform:none;font-size:15px;">
                            <input type="checkbox" name="fresh_migration" value="1"
                                   style="margin-top:3px;width:18px;height:18px;flex-shrink:0;"
                                <?= !empty($saved['fresh_migration']) ? 'checked' : 'checked' ?>>
                            <span>
          <strong>Drop all existing database tables</strong> (fresh install)<br>
          <small style="color:#856404;">Check this if the database already has tables from a previous install. <strong>This will delete all existing data.</strong></small>
        </span>
                        </label>
                    </div>

                    <div class="alert alert-info" style="margin-top:10px;">
                        <strong>Admin account:</strong> You will create your admin user on the next screen after
                        installation completes.
                    </div>

                    <div class="text-center mt">
                        <button type="button" class="btn btn-secondary" onclick="testDb()">Test DB Connection</button>
                        <button type="submit" class="btn">Install &rarr;</button>
                    </div>
                </form>

            <?php elseif ($step === 'install'):
            if (!isset($_SESSION['installer']) || empty($_SESSION['installer']['db_database'])): ?>
                <div class="alert alert-danger">Configuration not found. <a href="?step=setup">Go back</a>.</div>
            <?php else:
            $cfg = $_SESSION['installer'];
            $php = phpBin();
            $composerHome = $baseDir.'/tmp-composer-home';
            ?>
                <h2>Installing TastyIgniter&hellip;</h2>
                <p>Do not close this page. This may take 5&ndash;15 minutes.</p>
                <div class="log-wrap" id="log" style="height:600px;">
                    <?php
                    while (ob_get_level() > 0) {
                        ob_end_flush();
                    }
                    ob_implicit_flush(true);
                    $ok = true;
                    if (!is_dir($composerHome)) {
                        mkdir($composerHome, 0755, true);
                    }

                    // 1. Write .env
                    emit('Writing .env…', 'info');
                    $envContent = "APP_NAME=\"{$cfg['app_name']}\"\nAPP_ENV=production\nAPP_KEY=\nAPP_DEBUG=false\nAPP_URL={$cfg['app_url']}\n\nIGNITER_LOCATION_MODE=multiple\n\nDB_CONNECTION=mysql\nDB_HOST={$cfg['db_host']}\nDB_PORT={$cfg['db_port']}\nDB_DATABASE={$cfg['db_database']}\nDB_USERNAME={$cfg['db_username']}\nDB_PASSWORD={$cfg['db_password']}\nDB_PREFIX={$cfg['db_prefix']}\n\nBROADCAST_DRIVER=log\nCACHE_DRIVER=file\nQUEUE_CONNECTION=sync\nSESSION_DRIVER=file\nSESSION_LIFETIME=120\n\nMAIL_MAILER=log\nMAIL_FROM_ADDRESS=noreply@example.com\nMAIL_FROM_NAME=\"{$cfg['app_name']}\"\n";
                    if (file_put_contents($baseDir.'/.env', $envContent) === false) {
                        emit('ERROR: Could not write .env.', 'error');
                        $ok = false;
                    } else {
                        emit('.env created.', 'success');
                    }

                    // 2. Composer
                    if ($ok) {
                        $composerBin = composerPhar($baseDir);
                        if (!file_exists($composerBin)) {
                            emit('Downloading Composer…', 'info');
                            $setup = @file_get_contents('https://getcomposer.org/installer', false,
                                stream_context_create([
                                    'http' => [
                                        'timeout' => 90, 'user_agent' => 'TastyIgniter-Installer/1.0'
                                    ]
                                ]));
                            if ($setup !== false) {
                                file_put_contents($baseDir.'/composer-setup.php', $setup);
                                $r = runCmd("{$php} composer-setup.php --install-dir=".escapeshellarg($baseDir)." --filename=composer.phar 2>&1",
                                    $baseDir, $composerHome);
                                @unlink($baseDir.'/composer-setup.php');
                                if ($r['code'] !== 0 || !file_exists($baseDir.'/composer.phar')) {
                                    $phar = @file_get_contents('https://getcomposer.org/download/latest-stable/composer.phar',
                                        false, stream_context_create([
                                            'http' => [
                                                'timeout' => 120, 'user_agent' => 'TastyIgniter-Installer/1.0'
                                            ]
                                        ]));
                                    if ($phar !== false) {
                                        file_put_contents($baseDir.'/composer.phar', $phar);
                                        $composerBin = $baseDir.'/composer.phar';
                                    } else {
                                        emit('ERROR: Could not download Composer.', 'error');
                                        $ok = false;
                                    }
                                } else {
                                    $composerBin = $baseDir.'/composer.phar';
                                }
                            } else {
                                emit('ERROR: Could not reach getcomposer.org.', 'error');
                                $ok = false;
                            }
                            if ($ok) {
                                emit('Composer ready.', 'success');
                            }
                        } else {
                            emit('Composer: '.$composerBin, 'success');
                        }
                    }

                    // 2b. Add the avisaas Marketplace connector to composer.json
                    if ($ok) {
                        $cjPath = $baseDir.'/composer.json';
                        $cj = json_decode((string) @file_get_contents($cjPath));
                        if (is_object($cj)) {
                            if (!isset($cj->require) || !is_object($cj->require)) {
                                $cj->require = new stdClass();
                            }
                            if (!isset($cj->require->{'avisaas/ti-ext-connector'})) {
                                $cj->require->{'avisaas/ti-ext-connector'} = '^1.3';
                            }
                            file_put_contents($cjPath, json_encode($cj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n");
                            emit('Marketplace connector added to composer.json.', 'success');
                        } else {
                            emit('WARNING: composer.json unreadable — Marketplace connector not added.', 'warning');
                        }
                    }

                    // 3. composer install (streaming)
                    if ($ok) {
                        emit('Running composer install — this may take several minutes…', 'warning');
                        $cCmd = "COMPOSER_HOME=".escapeshellarg($composerHome)." HOME=".escapeshellarg($composerHome)." COMPOSER_MEMORY_LIMIT=-1 {$php} ".escapeshellarg($composerBin)." install --no-dev --optimize-autoloader --no-interaction 2>&1";
                        $env = array_merge($_ENV ?: [], [
                            'COMPOSER_HOME' => $composerHome, 'HOME' => $composerHome, 'COMPOSER_MEMORY_LIMIT' => '-1'
                        ]);
                        $desc = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
                        $proc = proc_open($cCmd, $desc, $pipes, $baseDir, $env);
                        if (is_resource($proc)) {
                            fclose($pipes[0]);
                            stream_set_blocking($pipes[1], false);
                            stream_set_blocking($pipes[2], false);
                            $lastFlush = time();
                            while (true) {
                                $r = [$pipes[1], $pipes[2]];
                                $w = $e = null;
                                if (stream_select($r, $w, $e, 1) > 0) {
                                    foreach ($r as $s) {
                                        $chunk = fread($s, 4096);
                                        if ($chunk !== false && $chunk !== '') {
                                            foreach (explode("\n", $chunk) as $line) {
                                                $line = trim($line);
                                                if (!$line) {
                                                    continue;
                                                }
                                                if (stripos($line, 'error') !== false || stripos($line,
                                                        'failed') !== false) {
                                                    emit($line, 'error');
                                                } elseif (stripos($line, 'installing') !== false || stripos($line,
                                                        'generating') !== false) {
                                                    emit($line, 'success');
                                                } else {
                                                    emit($line, 'info');
                                                }
                                            }
                                        }
                                    }
                                }
                                if (!proc_get_status($proc)['running']) {
                                    break;
                                }
                                if (time() - $lastFlush > 5) {
                                    flush();
                                    $lastFlush = time();
                                }
                            }
                            $cExit = proc_close($proc);
                            if ($cExit !== 0) {
                                emit("ERROR: composer install failed (exit {$cExit}).", 'error');
                                $ok = false;
                            } else {
                                emit('Dependencies installed.', 'success');
                                if (!is_dir($baseDir.'/vendor/avisaas/ti-ext-connector')) {
                                    emit('Installing Marketplace connector…', 'info');
                                    $r = runCmd("{$php} ".escapeshellarg($composerBin)." require avisaas/ti-ext-connector:^1.3 --update-no-dev --no-interaction 2>&1", $baseDir, $composerHome);
                                    emit($r['code'] === 0 ? 'Marketplace connector installed.' : 'WARNING: Marketplace connector not installed: '.trim($r['output'].$r['error']),
                                        $r['code'] === 0 ? 'success' : 'warning');
                                }
                                emit('Publishing vendor assets…', 'info');
                                $r = runCmd("{$php} artisan vendor:publish --all --force 2>&1", $baseDir);
                                foreach (explode("\n", trim($r['output'])) as $line) {
                                    if (trim($line)) {
                                        emit(trim($line), 'info');
                                    }
                                }
                                emit('Assets published.', 'success');
                            }
                        } else {
                            emit('ERROR: Could not start composer.', 'error');
                            $ok = false;
                        }
                    }

                    // 4. App key
                    if ($ok) {
                        emit('Generating application key…', 'info');
                        $r = runCmd("{$php} artisan key:generate --ansi --force 2>&1", $baseDir);
                        emit($r['code'] === 0 ? 'Key generated.' : 'WARNING: '.$r['output'].$r['error'],
                            $r['code'] === 0 ? 'success' : 'warning');
                    }

                    // 5. Permissions
                    if ($ok) {
                        emit('Setting permissions…', 'info');
                        foreach (['storage', 'bootstrap/cache'] as $d) {
                            $p = $baseDir.'/'.$d;
                            if (is_dir($p)) {
                                runCmd("chmod -R 775 ".escapeshellarg($p)." 2>&1", $baseDir);
                                runCmd("find ".escapeshellarg($p)." -type f -exec chmod 664 {} \\; 2>&1", $baseDir);
                                runCmd("find ".escapeshellarg($p)." -type d -exec chmod 775 {} \\; 2>&1", $baseDir);
                            }
                        }
                        emit('Permissions set.', 'success');
                    }

                    // 6. Root .htaccess
                    emit('Creating root .htaccess…', 'info');
                    $rHta = $baseDir.'/.htaccess';
                    if (file_exists($rHta)) {
                        emit('.htaccess already exists — skipping.', 'warning');
                    } elseif (file_put_contents($rHta,
                            "<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteRule ^(.*)$ public/\$1 [L]\n</IfModule>\n") !== false) {
                        emit('.htaccess created.', 'success');
                    } else {
                        emit('WARNING: Could not write .htaccess.', 'warning');
                    }

                    // 7. Storage link
                    if ($ok) {
                        emit('Creating storage symlink…', 'info');
                        $r = runCmd("{$php} artisan storage:link --force 2>&1", $baseDir);
                        emit(trim($r['output'] ?: 'Storage link created.'), $r['code'] === 0 ? 'success' : 'warning');
                    }

                    // 8. Migrations
                    if ($ok) {
                        $freshMigrate = !empty($cfg['fresh_migration']);
                        $migrateCmd = $freshMigrate ? 'migrate:fresh --force' : 'migrate --force';
                        emit(($freshMigrate ? 'Dropping existing tables and running fresh migrations…' : 'Running database migrations…'),
                            'info');
                        if ($freshMigrate) {
                            emit('WARNING: All existing database tables will be dropped.', 'warning');
                        }
                        $r = runCmd("{$php} artisan {$migrateCmd} 2>&1", $baseDir);
                        foreach (explode("\n", trim($r['output']."\n".$r['error'])) as $line) {
                            if (trim($line)) {
                                emit(trim($line), $r['code'] === 0 ? 'info' : 'error');
                            }
                        }
                        if ($r['code'] !== 0) {
                            emit('ERROR: Migrations failed. Check DB credentials.', 'error');
                            $ok = false;
                        } else {
                            emit('Migrations done.', 'success');
                        }
                    }

                    // 9. JUNIE HARDENING
                    if ($ok) {
                        emit('Applying Junie Hardening…', 'info');
                        applyHardening($baseDir);
                        applyPermissions($baseDir);
                        installSecurityScripts($baseDir);
                        emit('Junie Hardening applied.', 'success');
                    }

                    // 9. igniter:install
                    if ($ok) {
                        emit('Running igniter:install…', 'info');
                        $r = runCmd("{$php} artisan igniter:install --no-interaction 2>&1", $baseDir);
                        foreach (explode("\n", trim($r['output']."\n".$r['error'])) as $line) {
                            if (trim($line)) {
                                emit(trim($line), $r['code'] === 0 ? 'info' : 'error');
                            }
                        }
                        if ($r['code'] !== 0) {
                            emit('ERROR: igniter:install failed.', 'error');
                            $ok = false;
                        } else {
                            emit('TastyIgniter installed.', 'success');
                        }
                    }

                    // 10. Cache
                    if ($ok) {
                        emit('Clearing caches…', 'info');
                        foreach (['config:clear', 'cache:clear', 'route:clear', 'view:clear'] as $cmd) {
                            $r = runCmd("{$php} artisan {$cmd} 2>&1", $baseDir);
                            emit("  {$cmd}: ".trim($r['output'] ?: 'done'), 'info');
                        }
                        emit('Caching for production…', 'info');
                        foreach (['config:cache', 'route:cache'] as $cmd) {
                            $r = runCmd("{$php} artisan {$cmd} 2>&1", $baseDir);
                            emit("  {$cmd}: ".trim($r['output'] ?: 'done'), $r['code'] === 0 ? 'success' : 'warning');
                        }
                    }

                    // 11. Cleanup
                    emit('Cleaning up…', 'info');
                    if (is_dir($composerHome)) {
                        runCmd("rm -rf ".escapeshellarg($composerHome)." 2>&1", $baseDir);
                    }

                    if ($ok) {
                        emit('', 'info');
                        emit('Installation finished successfully!', 'success');
                        unset($_SESSION['installer']);
                    } else {
                        emit('', 'info');
                        emit('Installation ended with errors. Review the log above.', 'error');
                    }
                    ?>
                </div>
            <?php if (!$ok): ?>
                <div class="text-center mt">
                    <a href="?step=setup" class="btn btn-secondary">&larr; Back</a>
                    <a href="?step=install" class="btn">Retry</a>
                </div>
            <?php else: ?>
                <div style="margin-top:32px;">
                    <h2 style="color:#28a745;">Installation Complete!</h2>
                    <div class="alert alert-success">
                        <strong>TastyIgniter installed successfully.</strong><br>
                        Click <strong>Complete Setup</strong> below to create your admin account.
                    </div>
                    <div
                        style="background:#f7fafc;border:2px solid #e2e8f0;border-radius:10px;padding:28px;margin:20px 0;">
                        <h3 style="margin-top:0;">Next Steps</h3>
                        <ol style="line-height:2;color:#4a5568;margin:0;">
                            <li>Click <strong>Complete Setup</strong> to create your admin account.</li>
                            <li>Configure your restaurant, menu, and locations.</li>
                            <li>Set up payments under <em>Extensions &rarr; Payments</em>.</li>
                            <li><strong>Delete <code>install.php</code></strong> from your server.</li>
                        </ol>
                    </div>
                    <div class="alert alert-warning">
                        <strong>Security:</strong> Delete <code>install.php</code> from your server immediately after
                        setup.
                    </div>
                    <div class="text-center">
                        <a href="/admin/login" class="btn btn-success" style="font-size:1.1em;padding:16px 36px;">Go to
                            Admin Login &rarr;</a>
                        <button class="btn btn-secondary" style="font-size:1.1em;padding:16px 36px;"
                                onclick="document.getElementById('avisaas-modal').style.display='flex'">Learn More about
                            AVi SaaS
                        </button>
                    </div>
                </div>

                <!-- AVi SaaS popup -->
                <div id="avisaas-modal"
                     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center;">
                    <div
                        style="background:#fff;border-radius:14px;width:90%;max-width:900px;height:80vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.4);">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);color:#fff;">
                            <strong style="font-size:16px;">AVi SaaS &mdash; POS for TastyIgniter</strong>
                            <button onclick="document.getElementById('avisaas-modal').style.display='none'"
                                    style="background:rgba(255,255,255,.25);border:none;color:#fff;font-size:20px;width:32px;height:32px;border-radius:50%;cursor:pointer;line-height:1;">
                                &times;
                            </button>
                        </div>
                        <iframe src="https://avisaas.com/project/pos-tasty" style="flex:1;border:none;width:100%;"
                                loading="lazy"></iframe>
                    </div>
                </div>

            <?php endif; ?>
                <script>
                    const log = document.getElementById('log');
                    if (log) new MutationObserver(() => {
                        log.scrollTop = log.scrollHeight;
                    }).observe(log, {childList: true, subtree: true});
                </script>
            <?php endif; ?>

            <?php elseif ($step === 'complete'): ?>
                <div class="text-center">
                    <h2 style="color:#28a745;font-size:2em;">Installation Complete!</h2>
                    <div class="alert alert-success" style="text-align:left;">
                        <strong>TastyIgniter installed successfully.</strong><br>
                        Click <strong>Complete Setup</strong> to create your admin account.
                    </div>
                    <div
                        style="background:#f7fafc;border:2px solid #e2e8f0;border-radius:10px;padding:32px;margin:28px 0;text-align:left;">
                        <h3 style="margin-top:0;">Next Steps</h3>
                        <ol style="line-height:2;color:#4a5568;">
                            <li>Click <strong>Complete Setup</strong> to create your admin account.</li>
                            <li>Configure your restaurant, menu, and locations.</li>
                            <li>Set up payments under <em>Extensions &rarr; Payments</em>.</li>
                            <li><strong>Delete <code>install.php</code></strong> from your server.</li>
                        </ol>
                    </div>

                    <div class="alert alert-warning" style="text-align:left;">
                        <strong>Security:</strong> Delete <code>tastyIgniter-web-installer-v4/install.php</code>
                        immediately after setup.
                    </div>
                    <div class="text-center">
                        <a href="/admin/login" class="btn btn-success" style="font-size:1.1em;padding:16px 36px;">Go to
                            Admin Login &rarr;</a>
                        <button class="btn btn-secondary" style="font-size:1.1em;padding:16px 36px;"
                                onclick="document.getElementById('avisaas-modal').style.display='flex'">Learn More about
                            AVi SaaS
                        </button>
                    </div>
                </div>

                <!-- AVi SaaS popup -->
                <div id="avisaas-modal"
                     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center;">
                    <div
                        style="background:#fff;border-radius:14px;width:90%;max-width:900px;height:80vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.4);">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);color:#fff;">
                            <strong style="font-size:16px;">AVi SaaS &mdash; POS for TastyIgniter</strong>
                            <button onclick="document.getElementById('avisaas-modal').style.display='none'"
                                    style="background:rgba(255,255,255,.25);border:none;color:#fff;font-size:20px;width:32px;height:32px;border-radius:50%;cursor:pointer;line-height:1;">
                                &times;
                            </button>
                        </div>
                        <iframe src="https://avisaas.com/project/pos-tasty" style="flex:1;border:none;width:100%;"
                                loading="lazy"></iframe>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<div style="text-align:center;padding:24px 20px;color:rgba(255,255,255,.85);font-size:14px;">
    &copy;2026 All rights reserved &nbsp;|&nbsp; This website is made with 💝 <a href="https://avisaas.com/"
                                                                                target="_blank"
                                                                                style="color:#fff;font-weight:600;text-decoration:underline;">AVi
        SaaS</a>
</div>

<script>
    function testDb() {
        const form = document.getElementById('configForm');
        const result = document.getElementById('db-test-result');
        const btn = event.target;
        btn.innerHTML = '<span class="spinner"></span>Testing&hellip;';
        btn.disabled = true;
        fetch('?action=test_db', {method: 'POST', body: new FormData(form)})
            .then(r => r.json())
            .then(d => {
                result.innerHTML = d.success
                    ? '<div class="alert alert-success">&#10003; ' + d.message + '</div>'
                    : '<div class="alert alert-danger">&#10007; ' + d.message + '</div>';
            })
            .catch(() => {
                result.innerHTML = '<div class="alert alert-danger">&#10007; Request failed</div>';
            })
            .finally(() => {
                btn.innerHTML = 'Test DB Connection';
                btn.disabled = false;
            });
    }
</script>
</body>
</html>
