<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpirationController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderAccountController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — TecnoStar
|--------------------------------------------------------------------------
*/

// Rutas de Invitados (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Rutas Protegidas (Requieren Sesión Activa)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tablero Operativo de Vencimientos y Cobros WhatsApp
    Route::get('/expirations', [ExpirationController::class, 'index'])->name('expirations.index');
    Route::post('/expirations/{subscription}/renew', [ExpirationController::class, 'quickRenew'])->name('expirations.renew');

    // CRUDs de Operación
    Route::resource('clients', ClientController::class);
    Route::resource('provider-accounts', ProviderAccountController::class);
    Route::patch('/profiles/{profile}', [ProfileController::class, 'update'])->name('profiles.update');

    Route::resource('subscriptions', SubscriptionController::class);
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

    // Ajustes del Sistema
    Route::resource('services', ServiceController::class)->only(['index', 'store', 'update']);
    Route::resource('message-templates', MessageTemplateController::class)->only(['index', 'update']);
});
