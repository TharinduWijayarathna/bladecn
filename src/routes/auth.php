<?php

use BladeCN\BladeCN\Http\Controllers\Auth\AuthenticatedSessionController;
use BladeCN\BladeCN\Http\Controllers\Auth\NewPasswordController;
use BladeCN\BladeCN\Http\Controllers\Auth\PasswordResetLinkController;
use BladeCN\BladeCN\Http\Controllers\Auth\RegisteredUserController;
use BladeCN\BladeCN\Http\Controllers\ProfileController;
use BladeCN\BladeCN\Http\Controllers\AppearanceController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Settings routes
    Route::prefix('settings')->group(function () {
        Route::get('profile', [ProfileController::class, 'edit'])->name('settings.profile');
        Route::get('password', [ProfileController::class, 'editPassword'])->name('settings.password');
        Route::get('appearance', [AppearanceController::class, 'edit'])->name('settings.appearance');
        
        Route::put('appearance', [AppearanceController::class, 'update'])->name('settings.appearance.update');
    });

    // Profile routes (backward compatibility)
    Route::get('profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('password', [ProfileController::class, 'updatePassword'])
        ->name('password.update');

    Route::delete('profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Dashboard route
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
