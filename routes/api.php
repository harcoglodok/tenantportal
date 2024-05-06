<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\AuthAPIController;
use App\Http\Controllers\API\v1\CronAPIController;
use App\Http\Controllers\API\v1\UnitAPIController;
use App\Http\Controllers\API\v1\UserAPIController;
use App\Http\Controllers\API\v1\BannerAPIController;
use App\Http\Controllers\API\v1\BillingAPIController;
use App\Http\Controllers\API\v1\MessageAPIController;
use App\Http\Controllers\API\v1\ComplaintAPIController;
use App\Http\Controllers\API\v1\ComplaintReplyAPIController;
use App\Http\Controllers\API\v1\ComplaintCategoryAPIController;
use App\Http\Controllers\API\v1\BillingTransactionAPIController;
use App\Http\Controllers\API\v1\NotificationCategoryAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthAPIController::class, 'logout']);
        Route::prefix('user')->group(function () {
            Route::post('update', [UserAPIController::class, 'update']);
            Route::post('update-password', [UserAPIController::class, 'updatePassword']);
            Route::post('update-token', [UserAPIController::class, 'updateFirebaseToken']);
            Route::apiResource('units', UnitAPIController::class);
            Route::apiResource('complaints', ComplaintAPIController::class);
            Route::apiResource('complaint_replies', ComplaintReplyAPIController::class);
            Route::apiResource('messages', MessageAPIController::class);
            Route::post('messages-read/{id}', [MessageAPIController::class, 'read']);
            Route::get('messages-unread', [MessageAPIController::class, 'unreadCount']);
            Route::apiResource('billings', BillingAPIController::class);
            Route::apiResource('billing_transactions', BillingTransactionAPIController::class);
        });
    });
    Route::middleware('guest')->group(function () {
        Route::post('auth/login', [AuthAPIController::class, 'login']);
        Route::post('auth/register', [AuthAPIController::class, 'register']);
        Route::post('auth/forgot-password', [AuthAPIController::class, 'forgotPassword']);
    });
    Route::prefix('master')->group(function () {
        Route::apiResource('banners', BannerAPIController::class);
        Route::apiResource('complaint_categories', ComplaintCategoryAPIController::class);
        Route::apiResource('notification_categories', NotificationCategoryAPIController::class);
    });
    Route::prefix('cron')->group(function () {
        Route::get('scheduled_notification', [CronAPIController::class, 'scheduledNotification']);
        Route::get('birthday_notification', [CronAPIController::class, 'birthdayNotification']);
        Route::get('done_complaint', [CronAPIController::class, 'doneComplaint']);
    });
});
