<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,
    CallButtonSettingController,
    ClientController,
    DashboardController,
    FeedbackController,
    FeedbackManagerController,
    MembershipSettingController,
    ReportsController,
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

    // Call by button (A,B,C,...)
    Route::post('visits/call-button/{buttonType}', [VisitController::class, 'callButton'])
        ->name('visits.callButton');

    // Special call
    Route::post('visits/special-call/{visitId}', [VisitController::class, 'specialCall'])
        ->name('visits.specialCall');

    // Waiting visits
    Route::get('visits/waiting', [VisitController::class, 'getWaitingVisits'])
        ->name('visits.getWaiting');

    // Statistics endpoint
    Route::get('visits/stats', [VisitController::class, 'stats'])
        ->name('visits.stats');
     // get the best client
    Route::get('best-client', [VisitController::class, 'bestClient'])->name('best-client.index');


    // Table Endpoints
    Route::apiResource('tables', TableController::class)->only(['index','store','destroy']);
    Route::post('tables/{tableId}/free', [TableController::class, 'free'])
        ->name('tables.free');

    // Membership Settings Endpoints
    Route::apiResource('membership-settings', MembershipSettingController::class)->only(['index','store','destroy']);
    Route::get('membership-settings/current', [MembershipSettingController::class, 'current'])
        ->name('membership-settings.current');
    Route::post('membership-settings/update-multiple', [MembershipSettingController::class, 'updateMultiple']);


    // Call Button Settings: index, findSuitable, updateMultiple
    Route::get('call-button-settings', [CallButtonSettingController::class, 'index'])
        ->name('call-button-settings.index');

    Route::get('call-button-settings/suitable', [CallButtonSettingController::class, 'findSuitable'])
        ->name('call-button-settings.suitable');

    // Update multiple (A, B, C) in one request
    Route::post('call-button-settings/update-multiple', [CallButtonSettingController::class, 'updateMultiple'])
        ->name('call-button-settings.updateMultiple');

    // Last Called Visit
    Route::get('visits/last-called', [VisitController::class, 'lastCalled'])
        ->name('visits.lastCalled');

    Route::get('dashboard/stats', [DashboardController::class, 'stats'])
        ->name('dashboard.stats');

    // Reports Endpoint
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
});
