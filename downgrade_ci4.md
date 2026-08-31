# Panduan Downgrade CodeIgniter 4 ke Versi 4.4.8 (Kompatibel PHP 8.0)

Dokumen ini berisi panduan langkah demi langkah untuk menurunkan (*downgrade*) versi CodeIgniter 4 dari **v4.7.x** ke **v4.4.8** agar aplikasi dapat berjalan dengan stabil di server produksi yang menggunakan **PHP 8.0**.

---

## 📌 Mengapa CodeIgniter 4.4.8?

| Versi CodeIgniter | Dukungan Minimum PHP | Status untuk PHP 8.0 |
| :--- | :--- | :--- |
| **CI 4.7.x / 4.6.x** | PHP 8.2+ | ❌ Tidak Kompatibel |
| **CI 4.5.x** | PHP 8.1+ | ❌ Tidak Kompatibel |
| **CI 4.4.8** | PHP 7.4 – 8.2 | ✅ **Sangat Kompatibel dengan PHP 8.0** |

---

## 🛠️ Langkah-Langkah yang Telah Diterapkan

### 1. Backup Proyek Anda
Sebelum melakukan perubahan, buat backup atau commit pada Git repository:
```bash
git add .
git commit -m "Backup sebelum downgrade ke CI 4.4.8"
```

---

### 2. Perbarui `composer.json`
Pada file [`composer.json`](composer.json):
- Ubah requirement PHP menjadi: `"php": "^8.0 || ^8.1 || ^8.2"`
- Ubah framework menjadi: `"codeigniter4/framework": "~4.4.8"`
- Ubah PHPUnit ke versi yang mendukung PHP 8.0: `"phpunit/phpunit": "^9.6"`

```json
{
    "name": "codeigniter4/appstarter",
    "description": "CodeIgniter4 starter app",
    "license": "MIT",
    "type": "project",
    "homepage": "https://codeigniter.com",
    "support": {
        "forum": "https://forum.codeigniter.com/",
        "source": "https://github.com/codeigniter4/CodeIgniter4",
        "slack": "https://codeigniterchat.slack.com"
    },
    "require": {
        "php": "^8.0 || ^8.1 || ^8.2",
        "codeigniter4/framework": "~4.4.8"
    },
    "require-dev": {
        "fakerphp/faker": "^1.9",
        "mikey179/vfsstream": "^1.6",
        "phpunit/phpunit": "^9.6"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Config\\": "app/Config/"
        },
        "exclude-from-classmap": [
            "**/Database/Migrations/**"
        ]
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\Support\\": "tests/_support"
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true
    }
}
```

---

### 3. Sesuaikan Bootstrap File (`public/index.php` dan `spark`)

Pada CI 4.4.8, mekanisme bootstrapping menggunakan `bootstrap.php` bawaan CI 4.4 (bukan class `CodeIgniter\Boot` yang baru ada di CI 4.5+).

#### A. File [`public/index.php`](public/index.php)
```php
<?php

// Check PHP version.
$minPhpVersion = '7.4'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION
    );

    exit($message);
}

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Load our paths config file
require FCPATH . '../app/Config/Paths.php';

$paths = new Config\Paths();

// Location of the framework bootstrap file.
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

// Load environment settings from .env files into $_SERVER and $_ENV
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

// Define ENVIRONMENT
if (! defined('ENVIRONMENT')) {
    define('ENVIRONMENT', env('CI_ENVIRONMENT', 'production'));
}

$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

$app->run();

exit(EXIT_SUCCESS);
```

#### B. File [`spark`](spark)
Menggunakan bootstrap `bootstrap.php` dan `CodeIgniter\CLI\Console` bawaan CI 4.4.8.

---

### 4. Sesuaikan Konfigurasi Filter (`app/Config/Filters.php`)

Pada CI 4.4.8, `Config\Filters` mewarisi `CodeIgniter\Config\BaseConfig`:

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public array $aliases = [
        'auth'          => \App\Filters\AuthFilter::class,
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
    ];

    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    public array $methods = [];
    public array $filters = [];
}
```

---

### 5. Sesuaikan Konfigurasi Kint (`app/Config/Kint.php`)

Pada CI 4.4.8, class `Config\Kint` mewarisi `BaseConfig` dan menyertakan properti `$richSort`:
```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use Kint\Parser\ConstructablePluginInterface;
use Kint\Renderer\AbstractRenderer;
use Kint\Renderer\Rich\TabPluginInterface;
use Kint\Renderer\Rich\ValuePluginInterface;

class Kint extends BaseConfig
{
    public $plugins;
    public int $maxDepth           = 6;
    public bool $displayCalledFrom = true;
    public bool $expanded          = false;

    public string $richTheme = 'aante-light.css';
    public bool $richFolder  = false;
    public int $richSort     = AbstractRenderer::SORT_FULL;

    public $richObjectPlugins;
    public $richTabPlugins;

    public bool $cliColors      = true;
    public bool $cliForceUTF8   = false;
    public bool $cliDetectWidth = true;
    public int $cliMinWidth     = 40;
}
```

---

### 6. Jalankan Update Composer

```bash
composer update
```

---

### 7. Verifikasi

Jalankan perintah spark berikut untuk memastikan framework berjalan sempurna:
```bash
php spark
php spark routes
```
Hasil:
- Output versi: `CodeIgniter v4.4.8 Command Line Tool`
- Semua routes ter-load dengan benar tanpa error.

---

## 🚀 Checklist Deploy ke Server Produksi (PHP 8.0)

1. **Modul PHP 8.0 yang Wajib Terpasang di Server:**
   - `php8.0-intl` *(wajib untuk CodeIgniter 4)*
   - `php8.0-mbstring` *(wajib untuk CodeIgniter 4)*
   - `php8.0-mysql` *(untuk koneksi database)*
   - `php8.0-curl`
   - `php8.0-xml`
   - `php8.0-gd`

2. **Pengaturan `.env` di Server Produksi:**
   ```ini
   CI_ENVIRONMENT = production
   app.baseURL = 'https://domain-anda.com/'
   ```

3. **Izin Akses Folder (*Permissions*):**
   ```bash
   chmod -R 775 writable/
   chown -R www-data:www-data writable/
   ```

4. **Web Server Document Root:**
   - DocumentRoot web server (Nginx / Apache) harus mengarah ke folder **`public/`**.
