<?php

use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Author\PostController as AuthorPostController;
use App\Http\Controllers\Public\PostController as PublicPostController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\LikeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest Access)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicPostController::class, 'index'])->name('home')->middleware('guest');
Route::get('/posts/{post:slug}', [PublicPostController::class, 'show'])->name('posts.show')->middleware('guest');

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| User Routes (Logged-in Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Comments & Likes
    Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('likes.store');
});

/*
|--------------------------------------------------------------------------
| Author Routes (Role: Author)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:author'])->group(function () {
    // Post Management
    Route::resource('/author/posts', AuthorPostController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Role: Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Post Management
    Route::resource('/admin/posts', AdminPostController::class)->except(['show']);

    // User Management
    Route::resource('/admin/users', AdminUserController::class);
});