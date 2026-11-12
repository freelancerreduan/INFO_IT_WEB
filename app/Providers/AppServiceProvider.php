<?php

namespace App\Providers;

use App\Models\Setting;
use App\View\Composers\AdminLayoutComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // admin helpers (money(), status_pill(), setting() ...) — composer dump-autoload na korleo kaj korbe
        require_once app_path('Helpers/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin Settings -> timezone
        $tz = Setting::allCached()['timezone'] ?? null;
        if ($tz) {
            config(['app.timezone' => $tz]);
            date_default_timezone_set($tz);
        }

        // Website pagination -> Bootstrap 5 (admin panel nijer partial use kore)
        Paginator::useBootstrapFive();

        // Admin topbar notification bell
        View::composer('admin.layouts.partials.topbar', AdminLayoutComposer::class);

        // Website footer er course list
        View::composer('layouts.app', function ($view) {
            $view->with('footerCourses', \App\Models\Course::where('status', 'active')->orderBy('title')->take(6)->get(['slug', 'title']));
        });
    }
}
