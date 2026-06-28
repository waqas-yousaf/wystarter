<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BlogPostController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/blog', [BlogPostController::class, 'index'])->name('blog.index');
Route::get('/blog/{blogPost:slug}', [BlogPostController::class, 'show'])->name('blog.show');
Route::get('/tags', [TagController::class, 'index']);

Route::middleware('auth')->post('/upload/image', ImageUploadController::class);
