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
