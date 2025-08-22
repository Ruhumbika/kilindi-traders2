<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TraderController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Dashboard routes
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

// Trader routes
Route::apiResource('traders', TraderController::class);

// Debt routes
Route::apiResource('debts', DebtController::class);

// License routes
Route::apiResource('licenses', LicenseController::class);
Route::get('/licenses/expiring', [LicenseController::class, 'expiring']);

// Payment routes
Route::apiResource('payments', PaymentController::class);

// Additional utility routes
Route::get('/traders/{trader}/debts', function ($trader) {
    return response()->json(\App\Models\Debt::where('trader_id', $trader)->with('trader')->get());
});

Route::get('/traders/{trader}/licenses', function ($trader) {
    return response()->json(\App\Models\License::where('trader_id', $trader)->with('trader')->get());
});

Route::get('/traders/{trader}/payments', function ($trader) {
    return response()->json(\App\Models\Payment::where('trader_id', $trader)->with(['debt', 'license'])->get());
});
