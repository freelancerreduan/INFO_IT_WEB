<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\EnrollmentController as UserEnrollmentController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\PaymentController as UserPaymentController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Frontend (public) routes
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/courses', [FrontendController::class, 'courses'])->name('courses.index');
Route::get('/courses/{course:slug}', [FrontendController::class, 'courseDetails'])->name('courses.show');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('gallery');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'contactStore'])->name('contact.store');
Route::get('/apply', [FrontendController::class, 'apply'])->name('apply');
Route::post('/apply', [FrontendController::class, 'applyStore'])->name('apply.store');

/*
|--------------------------------------------------------------------------
| Uploaded file serve (storage symlink na thakleo image dekhabe)
|--------------------------------------------------------------------------
| `php artisan storage:link` cholle web server nijei file ta serve korbe,
| ei route tokhon kokhono hit hobe na. Link na thakle (shared hosting /
| zip copy kora project e) ei route theke file ta serve hoy.
*/
Route::get('storage/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);

    return response()->file(Storage::disk('public')->path($path));
})->where('path', '.*')->name('storage.serve');

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Admin routes  (admin panel -> routes/admin.php)
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Student (user) dashboard routes
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('enrollments', [UserEnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::get('payments', [UserPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/add', [UserPaymentController::class, 'create'])->name('payments.create');
    Route::post('payments/add', [UserPaymentController::class, 'store'])->name('payments.store')->middleware('throttle:10,1');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
});
