<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('register', [RegisterController::class, 'register'])->middleware('guest');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth', 'verified']);

Route::middleware(['auth'])->group(function () {
    // Profile routes for all authenticated users
    Route::get('profile/edit', [App\Http\Controllers\Auth\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [App\Http\Controllers\Auth\ProfileController::class, 'update'])->name('profile.update');

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class);
    });

    // Routes for Admin and Staff
    Route::middleware(['admin', 'staff'])->group(function () {
        Route::resource('books', App\Http\Controllers\BookController::class);
        Route::post('books/{book}/add-stock', [App\Http\Controllers\BookController::class, 'addStock'])->name('books.addStock');
        Route::resource('borrows', App\Http\Controllers\BorrowController::class);
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        Route::resource('databorrows', App\Http\Controllers\DataBorrowController::class);
    });
});
    // Route::get('borrows', App\Http\Controllers\BorrowController::class);
    // Route::post('borrows', App\Http\Controllers\BorrowController::class);

    //     Route::get('books', App\Http\Controllers\BookController::class);
    // Route::post('books', App\Http\Controllers\BookController::class);

    //     Route::get('categories', App\Http\Controllers\CategoryController::class);
    // Route::post('categories', App\Http\Controllers\CategoryController::class);

    //     Route::get('databorrows', App\Http\Controllers\DataBorrowController::class);
    // Route::post('databorrows', App\Http\Controllers\DataBorrowController::class);