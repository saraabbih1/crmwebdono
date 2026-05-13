<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailDebugController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Mail;

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login');
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->middleware('throttle:register');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email')->middleware('throttle:password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('clients', ClientController::class);
    Route::get('subscriptions/export', [SubscriptionController::class, 'export'])->name('subscriptions.export');
    Route::resource('subscriptions', SubscriptionController::class)->except(['show']);

    Route::middleware('role:admin')->group(function (): void {
        Route::resource('notifications', NotificationController::class)->except(['show']);
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/test-email', [EmailDebugController::class, 'sendTestEmail'])->name('email.test');
    });
    Route::get('/test-email', function () {

    Mail::raw('test email', function ($message) {
        $message->to('abbihsar30@gmail.com')
                ->subject('Laravel Test');
    });

    return 'Email sent';
});
});
