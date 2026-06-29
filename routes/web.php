<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ClearCacheController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\Shop\CategoryController as ShopCategoryController;
use App\Http\Controllers\Admin\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Admin\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResendVerificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): View => view('welcome'));

Route::get('/{page:slug}', [PageController::class, 'show'])->name('page.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:10,1');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/email/verify', VerifyEmailController::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', ResendVerificationController::class)
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('/upload/image', ImageUploadController::class)->name('upload.image');

    Route::get('/admin', fn () => redirect()->route('admin.main'));

    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::middleware(AdminMiddleware::class)->group(function (): void {
            Route::get('/', DashboardController::class)->name('main');
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
            Route::post('/settings/backup', BackupController::class)->name('settings.backup');
            Route::post('/settings/clear-cache', ClearCacheController::class)->name('settings.clear-cache');

            Route::resource('pages', AdminPageController::class)
                ->except(['show'])
                ->names('pages');
        });

        Route::resource('blog-posts', BlogPostController::class)
            ->except(['show'])
            ->names('blog-posts');

        Route::middleware('feature:ecommerce_enabled')->prefix('shop')->name('shop.')->group(function (): void {
            Route::resource('products', ShopProductController::class)->names('products');
            Route::resource('orders', ShopOrderController::class)->only(['index', 'show', 'update'])->names('orders');
            Route::resource('categories', ShopCategoryController::class)->names('categories');
        });
    });
});
