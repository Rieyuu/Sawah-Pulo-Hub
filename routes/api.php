<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AdminFacilityController;
use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\AdminSettingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Guest (Unauthenticated) Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes (Protected by Custom Stateful JWT Middleware)
Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User Profile Routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Admin Master Data Routes
    Route::middleware('jwt:admin')->prefix('admin')->group(function () {
        // Tickets CRUD
        Route::get('/tickets', [AdminTicketController::class, 'index']);
        Route::post('/tickets', [AdminTicketController::class, 'store']);
        Route::get('/tickets/{id}', [AdminTicketController::class, 'show']);
        Route::put('/tickets/{id}', [AdminTicketController::class, 'update']);
        Route::post('/tickets/{id}', [AdminTicketController::class, 'update']);
        Route::delete('/tickets/{id}', [AdminTicketController::class, 'destroy']);
        Route::post('/tickets/{id}/restore', [AdminTicketController::class, 'restore']);

        // Facilities CRUD
        Route::get('/facilities', [AdminFacilityController::class, 'index']);
        Route::post('/facilities', [AdminFacilityController::class, 'store']);
        Route::get('/facilities/{id}', [AdminFacilityController::class, 'show']);
        Route::put('/facilities/{id}', [AdminFacilityController::class, 'update']);
        Route::post('/facilities/{id}', [AdminFacilityController::class, 'update']);
        Route::delete('/facilities/{id}', [AdminFacilityController::class, 'destroy']);
        Route::post('/facilities/{id}/restore', [AdminFacilityController::class, 'restore']);

        // Articles CRUD
        Route::get('/articles', [AdminArticleController::class, 'index']);
        Route::post('/articles', [AdminArticleController::class, 'store']);
        Route::get('/articles/{id}', [AdminArticleController::class, 'show']);
        Route::put('/articles/{id}', [AdminArticleController::class, 'update']);
        Route::post('/articles/{id}', [AdminArticleController::class, 'update']);
        Route::delete('/articles/{id}', [AdminArticleController::class, 'destroy']);
        Route::post('/articles/{id}/restore', [AdminArticleController::class, 'restore']);

        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index']);
        Route::post('/settings', [AdminSettingController::class, 'update']);
    });
});

