<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardWebController;
use App\Http\Controllers\Web\TraderWebController;
use App\Http\Controllers\Web\DebtWebController;
use App\Http\Controllers\Web\LicenseWebController;
use App\Http\Controllers\Web\SmsWebController;
use App\Http\Controllers\ImportController;

// Authentication routes
Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::get('/register', [App\Http\Controllers\Auth\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect('/dashboard');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('dashboard');

    // Traders routes
    Route::get('/traders', [TraderWebController::class, 'index'])->name('traders.index');
    Route::get('/traders/create', [TraderWebController::class, 'create'])->name('traders.create');
    Route::post('/traders', [TraderWebController::class, 'store'])->name('traders.store');
    Route::get('/traders/export', [TraderWebController::class, 'export'])->name('traders.export');
    Route::post('/traders/import', [TraderWebController::class, 'import'])->name('traders.import');
    Route::get('/traders/{trader}', [TraderWebController::class, 'show'])->name('traders.show');
    Route::get('/traders/{trader}/edit', [TraderWebController::class, 'edit'])->name('traders.edit');
    Route::put('/traders/{trader}', [TraderWebController::class, 'update'])->name('traders.update');

    // Debts routes
    Route::get('/debts', [DebtWebController::class, 'index'])->name('debts.index');
    Route::get('/debts/create', [DebtWebController::class, 'create'])->name('debts.create');
    Route::post('/debts', [DebtWebController::class, 'store'])->name('debts.store');
    Route::get('/debts/{debt}/edit', [DebtWebController::class, 'edit'])->name('debts.edit');
    Route::put('/debts/{debt}', [DebtWebController::class, 'update'])->name('debts.update');
    Route::patch('/debts/{debt}/status', [DebtWebController::class, 'updateStatus'])->name('debts.update-status');

    // SMS routes
    Route::get('/sms', [SmsWebController::class, 'index'])->name('sms.index');
    Route::get('/sms/create', [SmsWebController::class, 'create'])->name('sms.create');
    Route::post('/sms', [SmsWebController::class, 'store'])->name('sms.store');
    Route::get('/sms/{smsLog}', [SmsWebController::class, 'show'])->name('sms.show');
    Route::get('/sms/bulk', [SmsWebController::class, 'bulk'])->name('sms.bulk');
    Route::post('/sms/bulk', [SmsWebController::class, 'sendBulk'])->name('sms.send-bulk');

    // Licenses routes
    Route::get('/licenses', [LicenseWebController::class, 'index'])->name('licenses.index');
    Route::get('/licenses/create', [LicenseWebController::class, 'create'])->name('licenses.create');
    Route::post('/licenses', [LicenseWebController::class, 'store'])->name('licenses.store');
    Route::get('/licenses/{license}/edit', [LicenseWebController::class, 'edit'])->name('licenses.edit');
    Route::put('/licenses/{license}', [LicenseWebController::class, 'update'])->name('licenses.update');
    Route::patch('/licenses/{license}/renew', [LicenseWebController::class, 'renew'])->name('licenses.renew');

    // Payments routes
    Route::get('/payments', [App\Http\Controllers\Web\PaymentWebController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [App\Http\Controllers\Web\PaymentWebController::class, 'create'])->name('payments.create');
    Route::post('/payments', [App\Http\Controllers\Web\PaymentWebController::class, 'store'])->name('payments.store');

    // Reports routes
    Route::get('/reports', [App\Http\Controllers\Web\ReportWebController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [App\Http\Controllers\Web\ReportWebController::class, 'generate'])->name('reports.generate');

    // Settings routes
    Route::get('/settings', [App\Http\Controllers\Web\SettingsWebController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Web\SettingsWebController::class, 'update'])->name('settings.update');

    // Import routes
    Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
    Route::post('/imports/traders', [ImportController::class, 'importTraders'])->name('imports.traders');
    Route::get('/imports/traders/template', [ImportController::class, 'downloadTradersTemplate'])->name('imports.traders.template');
});
