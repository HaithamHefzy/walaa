<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,FeedbackController,FeedBackManagerController,
    UserController,GiftCodeController,GiftController,DiscountCodeController,CouponsController};

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// User Management Routes (Resource Controller)
Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('users', UserController::class);

    // Feedback Route
    Route::apiResource('feedbacks', FeedbackController::class)->only(['index', 'store', 'destroy']);
    Route::post('assign/{feedbackId}/{managerId}', [FeedbackController::class, 'assignToManager'])->name('feedbacks.assign');
    // FeedBack Manager Route
    Route::apiResource('feedback-managers', FeedbackManagerController::class)->only(['index', 'store', 'destroy']);

    // gifts
    Route::apiResource('gift-codes',GiftCodeController::class);
    Route::apiResource('gifts',GiftController::class);
    // coupons
    Route::apiResource('discount-codes',DiscountCodeController::class);
    Route::apiResource('coupons',CouponsController::class);
});