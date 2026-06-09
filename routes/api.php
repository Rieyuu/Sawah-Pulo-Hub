<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AdminFacilityController;
use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\TicketOrderController;
use App\Http\Controllers\AdminOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Guest (Unauthenticated) Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/categories', function () {
    return response()->json([
        'status' => 200,
        'message' => 'Categories retrieved successfully',
        'data' => \App\Models\Category::all()
    ]);
});

// Authenticated Routes (Protected by Custom Stateful JWT Middleware)
Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User Profile Routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Ticket Orders (Tourist)
    Route::post('/orders', [TicketOrderController::class, 'store']);
    Route::post('/orders/{id}/upload-payment', [TicketOrderController::class, 'uploadPayment']);
    Route::get('/orders/history', [TicketOrderController::class, 'history']);
    Route::get('/orders/{id}', [TicketOrderController::class, 'show']);

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

        // Ticket Orders Management
        Route::get('/orders', [AdminOrderController::class, 'index']);
        Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
        Route::post('/orders/{id}/approve', [AdminOrderController::class, 'approve']);
        Route::post('/orders/{id}/reject', [AdminOrderController::class, 'reject']);
    });
});


