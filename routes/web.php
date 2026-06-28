<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): View => view('welcome'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::post('/upload/image', ImageUploadController::class)->name('upload.image');

    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::middleware(AdminMiddleware::class)->group(function (): void {
            Route::get('/dashbaord', DashboardController::class)->name('dashboard');
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        Route::resource('blog-posts', BlogPostController::class)
            ->except(['show'])
            ->names('blog-posts');
    });
});
