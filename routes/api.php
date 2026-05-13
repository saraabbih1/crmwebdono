<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\SubscriptionApiController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:api');

Route::middleware(['api.token', 'throttle:api'])->group(function (): void {
    Route::get('me', [AuthController::class, 'me']);
    Route::apiResource('clients', ClientApiController::class)->names('api.clients');
    Route::apiResource('subscriptions', SubscriptionApiController::class)->names('api.subscriptions');
    Route::get('notifications', [NotificationApiController::class, 'index']);
});
