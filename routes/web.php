<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;


Route::get('/', function () {
    return view('auth.login');
});



Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth', 'verified']);

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    // Routes for Admin and Staff
    Route::middleware([''])->group(function () {
        Route::resource('books', App\Http\Controllers\BookController::class);

        Route::resource('borrows', App\Http\Controllers\BorrowController::class)->middleware('');

        Route::resource('categories', App\Http\Controllers\CategoryController::class);
});
});


