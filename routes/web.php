<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TagController;

use App\Http\Controllers\Author\AuthorCommentController;
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
    Route::post('/comments/{comment}/toggle-replies', [CommentController::class, 'toggleReplies'])->name('comments.toggleReplies');

    // Like Post or Comment
    Route::post('/like', [LikeController::class, 'toggleLike'])->name('like.toggle');
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

    // Comments routes
    Route::get('/comments', [AuthorCommentController::class, 'index'])->name('author.comments.index');
    Route::get('/comments/{comment}/edit', [AuthorCommentController::class, 'edit'])->name('author.comments.edit');
    Route::patch('/comments/{comment}/update', [AuthorCommentController::class, 'update'])->name('author.comments.update');
    Route::patch('/comments/{comment}/approve', [AuthorCommentController::class, 'approve'])->name('author.comments.approve');
    Route::post('/comments/{comment}/mark-spam', [AuthorCommentController::class, 'markAsSpam'])->name('author.comments.markSpam');
    Route::delete('/comments/{comment}', [AuthorCommentController::class, 'destroy'])->name('author.comments.destroy');

    // Delete All Spam
    Route::post('/comments/delete-all-spam', [AuthorCommentController::class, 'deleteAllSpam'])
        ->name('author.comments.deleteAllSpam');
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

    Route::patch('/posts/{post}/approve', [AdminPostController::class, 'approve'])->name('admin.posts.approve');

    // Admin Categories Management
    Route::resource('/categories', CategoryController::class)->names('admin.categories');

    // Admin Tags Management
    Route::resource('/tags', TagController::class)->names('admin.tags');

    // Admin Users Management
    Route::resource('/users', AdminUserController::class)->names('admin.users');

    // Admin Settings Management
    Route::get('/settings', [SettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});