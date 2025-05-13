<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication routes
Auth::routes();

// Home page - show all posts
Route::get('/', [PostController::class, 'index'])->name('home');

// Post routes
Route::get('/p/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/p', [PostController::class, 'store'])->name('posts.store');
Route::get('/p/my', [PostController::class, 'myPosts'])->name('posts.my-posts');
Route::get('/p/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/p/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/p/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/p/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// User routes
Route::get('/user/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/user/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/search', [UserController::class, 'search'])->name('users.search');

// Comment routes
Route::post('/p/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Like routes
Route::post('/p/{post}/like', [LikeController::class, 'toggle'])->name('likes.toggle');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
