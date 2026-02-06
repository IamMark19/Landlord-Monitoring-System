<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    UserController, LandlordController, PropertyController,
    UnitController, TenantController, PaymentController,
    MaintenanceRequestController, AuthController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Logged-in user info
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    /*
    |-------------------------
    | ADMIN ONLY
    |-------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('landlords', LandlordController::class);
    });

    /*
    |-------------------------
    | LANDLORD ONLY
    |-------------------------
    */
    Route::middleware('role:admin,landlord')->group(function () {
        Route::apiResource('properties', PropertyController::class);
        Route::apiResource('units', UnitController::class);
        Route::apiResource('tenants', TenantController::class);
    });

    /*
    |-------------------------
    | TENANT ONLY
    |-------------------------
    */
    Route::middleware('role:tenant')->group(function () {
        Route::apiResource('payments', PaymentController::class);
        Route::apiResource('maintenance-requests', MaintenanceRequestController::class);
    });

    // Logout (any logged user)
    Route::post('/logout', [AuthController::class, 'logout']);
});
