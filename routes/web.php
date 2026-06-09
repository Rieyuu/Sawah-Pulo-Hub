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

// Tourist Pages (Public Informational)
Route::get('/profil-wisata', function () {
    return view('about');
})->name('about');

Route::get('/facilities', function () {
    return view('facilities');
})->name('facilities');

Route::get('/tickets', function () {
    $tickets = \App\Models\Ticket::where('is_active', true)->paginate(6);
    return view('tickets.index', compact('tickets'));
})->name('tickets.index');

Route::get('/articles', function () {
    $articles = \App\Models\Article::latest()->paginate(6);
    return view('articles.index', compact('articles'));
})->name('articles.index');

Route::get('/articles/{id}', function ($id) {
    $article = \App\Models\Article::with('category')->findOrFail($id);
    return view('articles.show', compact('article'));
})->name('articles.show');

// Protected pages (Handled client-side via JWT check in AlpineJS)
Route::get('/profile/settings', function () {
    return view('profile.settings');
})->name('profile.settings');

Route::get('/profile/history', function () {
    return view('profile.history');
})->name('profile.history');

// Checkout & Payment pages
Route::get('/tickets/checkout/{id}', function ($id) {
    return view('tickets.checkout', ['ticketId' => $id]);
})->name('tickets.checkout');

Route::get('/tickets/payment/{order_id}', function ($orderId) {
    return view('tickets.payment', ['orderId' => $orderId]);
})->name('tickets.payment');

Route::get('/tickets/print/{order_id}', function ($orderId) {
    return view('tickets.print', ['orderId' => $orderId]);
})->name('tickets.print');

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

    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('admin.orders');

    Route::get('/scan', function () {
        return view('admin.scan');
    })->name('admin.scan');

    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');
});
