<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest pages (Authentication)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Protected pages (Handled client-side via JWT check in AlpineJS)
Route::get('/profile/settings', function () {
    return view('profile.settings');
})->name('profile.settings');

Route::get('/profile/history', function () {
    return view('profile.history');
})->name('profile.history');

// Admin pages (Handled client-side via JWT admin check in AlpineJS)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/tickets', function () {
        return view('admin.tickets');
    })->name('admin.tickets');

    Route::get('/facilities', function () {
        return view('admin.facilities');
    })->name('admin.facilities');

    Route::get('/articles', function () {
        return view('admin.articles');
    })->name('admin.articles');

    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
});
