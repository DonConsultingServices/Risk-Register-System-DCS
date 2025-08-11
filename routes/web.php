<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiskAssessmentController;
use App\Http\Controllers\ClientRiskController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Dashboard as landing page
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Risk Assessment Routes
Route::resource('risk-assessments', RiskAssessmentController::class);

// Client Risk Assessment Routes
Route::resource('client-risk', ClientRiskController::class);
Route::get('/client-risk/risk-register', [ClientRiskController::class, 'riskRegister'])->name('client-risk.risk-register');

// Report Routes
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/pdf', [ReportController::class, 'generatePdf'])->name('reports.pdf');
Route::get('/reports/csv', [ReportController::class, 'exportCsv'])->name('reports.csv');
Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');

// Settings Routes
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::post('/settings/reset', [SettingsController::class, 'reset'])->name('settings.reset');
Route::get('/settings/export', [SettingsController::class, 'export'])->name('settings.export');
Route::post('/settings/import', [SettingsController::class, 'import'])->name('settings.import');

// User Management Routes
Route::resource('users', UserController::class);
Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
Route::get('/users/export', [UserController::class, 'export'])->name('users.export'); 