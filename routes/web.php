<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\CompanyController;
use App\Models\Driver;

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

// Rutas públicas para actualización de documentos de vehículos
Route::get('/truck/appeal/{token}', [App\Http\Controllers\TruckAppealController::class, 'show'])
    ->name('truck.appeal.show');
Route::put('/truck/appeal/{token}', [App\Http\Controllers\TruckAppealController::class, 'update'])
    ->name('truck.appeal.update');
Route::get('/truck/appeal-success', [App\Http\Controllers\TruckAppealController::class, 'success'])
    ->name('truck.appeal.success');

// Rutas públicas para actualización de documentos de chassis
Route::get('/chassis/appeal/{token}', [App\Http\Controllers\ChassisAppealController::class, 'show'])
    ->name('chassis.appeal.show');
Route::put('/chassis/appeal/{token}', [App\Http\Controllers\ChassisAppealController::class, 'update'])
    ->name('chassis.appeal.update');
Route::get('/chassis/appeal-success', [App\Http\Controllers\ChassisAppealController::class, 'success'])
    ->name('chassis.appeal.success');

// Rutas públicas para restablecimiento de contraseña
Route::get('/password/reset/{token}', [App\Http\Controllers\PasswordResetController::class, 'show'])
    ->name('password.reset.show');
Route::put('/password/reset/{token}', [App\Http\Controllers\PasswordResetController::class, 'update'])
    ->name('password.reset.update');
Route::get('/password/reset-success', [App\Http\Controllers\PasswordResetController::class, 'success'])
    ->name('password.reset.success');

Route::get('/', function () {
    return view('welcome');
});

// Rutas para registro de empresa
Route::middleware('guest')->group(function () {
    Volt::route('registrar-empresa', 'company.register')
        ->name('company.register');
    
    Volt::route('olvide-contrasena', 'auth.forgot-password')
        ->name('password.request');
});

Route::middleware('auth')->group(function () {
    Volt::route('dashboard', 'dashboard')
        ->name('dashboard');
    
    Volt::route('drivers', 'drivers.drivers')
        ->name('drivers');
    
    Volt::route('trucks', 'trucks.trucks')
        ->name('trucks');
    
    Volt::route('chassis', 'chassis.chassis')
        ->name('chassis');
});

require __DIR__.'/auth.php';
