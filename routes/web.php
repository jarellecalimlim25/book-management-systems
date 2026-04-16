<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CourseController;

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Home route
Route::get('/', function () {
    return auth()->check() ? view('dashboard') : redirect()->route('login');
})->name('home');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes - authenticated users only
Route::middleware('auth')->group(function () {
    // Books
    Route::resource('books', BookController::class);

    // Posts - users can only see their own, admins see all
    Route::resource('posts', PostController::class);

    // Admin only - User management
    Route::middleware('admin.only')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Admin only - Course management
    Route::middleware('admin.only')->group(function () {
        Route::resource('courses', CourseController::class);
        Route::post('/courses/{course}/enroll', [CourseController::class, 'enrollUser'])->name('courses.enroll');
        Route::delete('/courses/{course}/users/{user}', [CourseController::class, 'removeUser'])->name('courses.removeUser');
    });
});

