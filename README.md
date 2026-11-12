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

## Page -> Route -> Controller -> View

| Page | URL | Controller | View |
|---|---|---|---|
| Login / Logout | /admin/login | AuthController | auth/login |
| Dashboard | /admin | DashboardController | dashboard/index |
| Add / Edit Student | /admin/students/create, /{id}/edit | StudentController | students/create |
| Student List | /admin/students | StudentController | students/index |
| Transactions (+Export) | /admin/transactions | TransactionController | transactions/index |
| Orders (+Export) | /admin/orders | OrderController | orders/index |
| Add / Edit Course | /admin/courses/create, /{id}/edit | CourseController | courses/create |
| Course List | /admin/courses | CourseController | courses/index |
| Add / Edit Gallery | /admin/gallery/create, /{id}/edit | GalleryController | gallery/create |
| Gallery List | /admin/gallery | GalleryController | gallery/index |
| Reports (+Export) | /admin/reports | ReportController | reports/index |
| Settings | /admin/settings | SettingController | settings/index |
| Profile | /admin/profile | ProfileController | profile/index |

## Kivabe data ashe

- **Earnings** = `transactions` table er *Completed* payment er sum.
- **Costing** = `expenses` table er sum (seeder e demo data ache). Design e costing add er form chilo na, tai UI banano hoyni — chaile `Expense::create([...])` diye ba notun page banaye add korte parben.
- **Profit** = Earnings − Costing. Dashboard er % change = ei mash vs ager mash.
- Notun student add korle automatic ekta **Pending order** toiri hoy (course er discount price diye).
- Transaction *Complete* korle linked order er payment `Paid`, *Reject* korle `Failed` hoy.
- Reports: *Monthly* = selected year er Jan–Dec (month select korle shudhu oi mash), *Yearly* = last 6 year. Status "All" = Completed + Pending.
- **Export Excel** = UTF-8 CSV (Excel e direct khule). `.xlsx` lagle `maatwebsite/excel` use korte paren.
- Settings er Institute Name → sidebar brand, title, footer; Currency → shob jaygay ৳/$; Timezone → app timezone.
- Topbar bell = pending order + pending transaction (dot shudhu pending thakle dekhay).
- Admin access: `users.is_admin = 1` thakle login korte parbe.
