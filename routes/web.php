<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\CompanyController;

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
});

require __DIR__.'/auth.php';
