<?php

use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController;

use App\Http\Controllers\Author\PostController as AuthorPostController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;

use App\Http\Controllers\Public\PostController as PublicPostController;

use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\LikeController;

use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest Access)
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicPostController::class, 'index'])->name('home');
Route::get('/posts/{post:slug}', [PublicPostController::class, 'show'])->name('posts.show');

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (All Logged-in Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Post Interactions
    Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('likes.store');
});

/*
|--------------------------------------------------------------------------
| Author Routes (For Authors Only)
|--------------------------------------------------------------------------
*/
Route::prefix('author')->middleware(['auth', 'verified', 'role.author'])->group(function () {
    // Author Dashboard
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('author.dashboard');

    // Author Posts Management
    Route::resource('/posts', AuthorPostController::class)->names('author.posts');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (For Admins Only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'verified', 'role.admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Posts Management
    Route::resource('/posts', AdminPostController::class)->names('admin.posts');

    // Admin Users Management
    Route::resource('/users', AdminUserController::class)->names('admin.users');

    // Additional user routes
    Route::post('/users/{user}/change-role', [AdminUserController::class, 'changeRole'])
        ->name('admin.users.change-role');

    Route::post('/users/bulk-action', [AdminUserController::class, 'bulkAction'])
        ->name('admin.users.bulk-action');

    // Admin Settings Management
    Route::get('/settings', [SettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});
