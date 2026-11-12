# Training Institute Admin Panel (Laravel)

Apnar `index.html` er **same design**, shob section Laravel diye dynamic.
Laravel 10 / 11 / 12 e cholbe (PHP 8.1+). Laravel 11 + SQLite e test kora hoyeche.

## Setup

1. Ei zip er `app/`, `database/`, `resources/`, `routes/` folder gula apnar project e copy korun (merge).
2. `composer.json` e helper file add korun, tarpor `composer dump-autoload`:
   ```json
   "autoload": {
       "files": ["app/Helpers/helpers.php"],
       ...
   }
   ```
3. Service provider register:
   - Laravel 11/12 -> `bootstrap/providers.php` e `App\Providers\AdminPanelServiceProvider::class,`
   - Laravel 10   -> `config/app.php` er `providers` array te
4. `routes/web.php` er sheshe: `require __DIR__.'/admin.php';`
5. Commands:
   ```bash
   php artisan migrate
   php artisan db:seed --class=AdminPanelSeeder   # demo data + admin user
   php artisan storage:link                       # uploaded photo dekhanor jonno
   ```
6. `/admin/login` -> **admin@example.com / password** (login er por Profile theke password change korun)
