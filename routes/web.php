<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;

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

    // Staff and Admin routes for users (staff can access but with role restrictions)
    Route::middleware(['staff'])->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::patch('users/{user}/toggle-active', [App\Http\Controllers\UserController::class, 'toggleActive'])->name('users.toggleActive');
        Route::get('users-export', [App\Http\Controllers\UserController::class, 'export'])->name('users.export');
        Route::post('users-bulk-delete', [App\Http\Controllers\UserController::class, 'bulkDelete'])->name('users.bulkDelete');
        Route::post('users-bulk-toggle-active', [App\Http\Controllers\UserController::class, 'bulkToggleActive'])->name('users.bulkToggleActive');
    });

    // Booking routes for authenticated users
    Route::middleware(['auth'])->group(function () {
        Route::get('bookings', [App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/create', [App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    });

    // Routes for Admin and Staff
    Route::middleware(['staff'])->group(function () {
        Route::resource('books', App\Http\Controllers\BookController::class);
        Route::post('books/{book}/add-stock', [App\Http\Controllers\BookController::class, 'addStock'])->name('books.addStock');
        Route::post('books/{book}/reduce-stock', [App\Http\Controllers\BookController::class, 'reduceStock'])->name('books.reduceStock');
        Route::get('books-export', [App\Http\Controllers\BookController::class, 'exportCsv'])->name('books.export');
        Route::post('books-bulk-update-stock', [App\Http\Controllers\BookController::class, 'bulkUpdateStock'])->name('books.bulkUpdateStock');
        Route::delete('books-bulk-delete', [App\Http\Controllers\BookController::class, 'bulkDelete'])->name('books.bulkDelete');
        Route::resource('borrows', App\Http\Controllers\BorrowController::class)->middleware('staff');
        Route::patch('borrows/{borrow}/status', [App\Http\Controllers\BorrowController::class, 'updateStatus'])->name('borrows.updateStatus')->middleware('staff');
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        Route::post('categories-bulk-delete', [App\Http\Controllers\CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
        Route::resource('databorrows', App\Http\Controllers\DataBorrowController::class);

        // Staff/Admin booking management routes
        Route::get('bookings/{booking}', [App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
        Route::get('bookings/{booking}/edit', [App\Http\Controllers\BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('bookings/{booking}', [App\Http\Controllers\BookingController::class, 'update'])->name('bookings.update');
        Route::delete('bookings/{booking}', [App\Http\Controllers\BookingController::class, 'destroy'])->name('bookings.destroy');
    });
});

// Settings routes
Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index')->middleware('auth');
Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update')->middleware('auth');

// Language switching route
Route::post('/language/switch', [App\Http\Controllers\LanguageController::class, 'switchLanguage'])->name('language.switch')->middleware('auth');

// Notification routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
});
