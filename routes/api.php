<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,
    CallButtonSettingController,
    ClientController,
    FeedbackController,
    FeedBackManagerController,
    MembershipSettingController,
    TableController,
    UserController,
    GiftCodeController,
    GiftController,
    DiscountCodeController,
    CouponsController,
    VisitController};

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

    // Client Endpoints (clients are used, not customers)
    Route::apiResource('clients', ClientController::class)->only(['index','store','destroy','show']);
    // create client visit
    Route::post('client-visit', [ClientController::class, 'store'])->name('client.visit.store');


    // Visit Endpoints
    Route::apiResource('visits', VisitController::class)->only(['index','store','destroy']);
    Route::post('visits/{visitId}/assign-table', [VisitController::class, 'assignTable'])
        ->name('visits.assignTable');

    // Table Endpoints
    Route::apiResource('tables', TableController::class)->only(['index','store','destroy']);
    Route::post('tables/{tableId}/free', [TableController::class, 'free'])
        ->name('tables.free');

    // Membership Settings Endpoints
    Route::apiResource('membership-settings', MembershipSettingController::class)->only(['index','store','destroy']);
    Route::get('membership-settings/current', [MembershipSettingController::class, 'current'])
        ->name('membership-settings.current');

    // Call Button Settings Endpoints
    Route::apiResource('call-button-settings', CallButtonSettingController::class)->only(['index','store','destroy']);
    Route::get('call-button-settings/suitable', [CallButtonSettingController::class, 'findSuitable'])
        ->name('call-button-settings.suitable');

});
