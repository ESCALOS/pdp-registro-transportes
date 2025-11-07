<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Rutas públicas para apelación de empresas rechazadas
Route::get('/company/appeal/{token}', [App\Http\Controllers\CompanyAppealController::class, 'show'])
    ->name('company.appeal.show');
Route::put('/company/appeal/{token}', [App\Http\Controllers\CompanyAppealController::class, 'update'])
    ->name('company.appeal.update');
Route::get('/company/appeal-success', [App\Http\Controllers\CompanyAppealController::class, 'success'])
    ->name('company.appeal.success');

// Rutas públicas para actualización de documentos de conductores
Route::get('/driver/appeal/{token}', [App\Http\Controllers\DriverAppealController::class, 'show'])
    ->name('driver.appeal.show');
Route::put('/driver/appeal/{token}', [App\Http\Controllers\DriverAppealController::class, 'update'])
    ->name('driver.appeal.update');
Route::get('/driver/appeal-success', [App\Http\Controllers\DriverAppealController::class, 'success'])
    ->name('driver.appeal.success');

Route::get('/', function () {
    return view('welcome');
});

// Rutas para registro de empresa
Route::middleware('guest')->group(function () {
    Volt::route('registrar-empresa', 'company.register')
        ->name('company.register');
});

Route::middleware('auth')->group(function () {
    Volt::route('dashboard', 'dashboard')
        ->name('dashboard');

    Volt::route('drivers', 'drivers.drivers')
        ->name('drivers');
});

require __DIR__.'/auth.php';
