<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PaymentController as ApiPaymentController;
use App\Http\Controllers\Api\V1\ConvocationController as ApiConvocationController;
use App\Http\Controllers\Api\V1\DashboardController as ApiDashboardController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Public routes (Authentication)
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

    // Protected routes (require Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Dashboard
        Route::get('/dashboard', [ApiDashboardController::class, 'index']);

        // Payments
        Route::apiResource('payments', ApiPaymentController::class);
        Route::get('/payments/{payment}/receipt', [ApiPaymentController::class, 'downloadReceipt']);

        // Convocations
        Route::apiResource('convocations', ApiConvocationController::class)->only(['index', 'show']);
        Route::get('/convocations/{convocation}/download', [ApiConvocationController::class, 'download']);

        // Messages & Chat
        Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index']);
        Route::get('/messages/fetch', [App\Http\Controllers\MessageController::class, 'getMessages']);
        Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store']);
        Route::put('/messages/{message}', [App\Http\Controllers\MessageController::class, 'update']);
        Route::delete('/messages/{message}', [App\Http\Controllers\MessageController::class, 'destroy']);
        Route::post('/messages/{message}/react', [App\Http\Controllers\MessageController::class, 'react']);
        Route::post('/messages/{message}/hide', [App\Http\Controllers\MessageController::class, 'hide']);
        Route::post('/messages/{message}/report', [App\Http\Controllers\MessageController::class, 'report']);

        // Conversations
        Route::apiResource('conversations', App\Http\Controllers\ConversationController::class);
        Route::post('/conversations/{conversation}/read', [App\Http\Controllers\ConversationController::class, 'markAsRead']);
        Route::post('/conversations/{conversation}/participants', [App\Http\Controllers\ConversationController::class, 'addParticipant']);
        Route::delete('/conversations/{conversation}/participants/{user}', [App\Http\Controllers\ConversationController::class, 'removeParticipant']);
        Route::post('/conversations/{conversation}/leave', [App\Http\Controllers\ConversationController::class, 'leaveConversation']);
    });
});

// Webhooks (secured by middleware)
Route::post('/webhooks/mvola', [PaymentController::class, 'webhook'])->middleware('verify.webhook:mvola')->name('webhook.mvola');
Route::post('/webhooks/orange', [PaymentController::class, 'webhook'])->middleware('verify.webhook:orange')->name('webhook.orange');
