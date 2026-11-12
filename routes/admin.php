<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CostingController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SeminarController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes  (/admin)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // ---------- Admin Auth ----------
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:10,1');

    Route::middleware(AdminMiddleware::class)->group(function () {

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // ---------- Dashboard ----------
        Route::get('/', DashboardController::class)->name('dashboard');

        // ---------- Student ----------
        Route::get('students/print', [StudentController::class, 'print'])->name('students.print');
        Route::resource('students', StudentController::class)->except('show');
        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::patch('applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
        Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

        // ---------- Finance ----------
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
        Route::patch('transactions/{transaction}/status', [TransactionController::class, 'status'])->name('transactions.status');
        Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'status'])->name('orders.status');
        Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        // ---------- Add Payment (student -> course order -> due -> amount) ----------
        Route::get('payments/add', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('payments/add', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('payments/students', [PaymentController::class, 'students'])->name('payments.students');
        Route::get('payments/students/{user}/orders', [PaymentController::class, 'studentOrders'])->name('payments.student-orders');

        Route::get('costing', [CostingController::class, 'index'])->name('costing.index');
        Route::post('costing', [CostingController::class, 'store'])->name('costing.store');
        Route::delete('costing/{costing}', [CostingController::class, 'destroy'])->name('costing.destroy');

        // ---------- Course ----------
        Route::resource('courses', CourseController::class)->except('show');

        // ---------- Gallery ----------
        Route::resource('gallery', GalleryController::class)->except('show')->parameters(['gallery' => 'gallery']);

        // ---------- Seminar (website upcoming seminar section) ----------
        Route::resource('seminars', SeminarController::class)->except('show');

        // ---------- Class Videos (website video section) ----------
        Route::resource('videos', VideoController::class)->except('show');

        // ---------- Contact Messages (website contact form) ----------
        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/export', [MessageController::class, 'export'])->name('messages.export');
        Route::patch('messages/{message}', [MessageController::class, 'update'])->name('messages.update');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        // ---------- Reports ----------
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        // ---------- Settings / Profile ----------
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});
