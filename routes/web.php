<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\CompanyController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para registro de empresa
Route::get('/registro-empresa', [CompanyController::class, 'showRegistrationForm'])->name('company.register');
Route::post('/registro-empresa', [CompanyController::class, 'store'])->name('company.store');

Route::middleware('auth')->group(function () {
    Volt::route('dashboard', 'dashboard')
        ->name('dashboard');
});

require __DIR__.'/auth.php';
