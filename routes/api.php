<?php

use App\Http\Controllers\API\v1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\BannerAPIController;

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
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Route::prefix('surat')->group(function () {
        //     Route::prefix('masuk')->group(function () {
        //         Route::get('/', [SuratMasukController::class, 'index']);
        //         Route::get('/code', [SuratMasukController::class, 'code']);
        //         Route::post('/', [SuratMasukController::class, 'create']);
        //         Route::get('/{no_agenda}', [SuratMasukController::class, 'show']);
        //         Route::post('/{no_agenda}', [SuratMasukController::class, 'update']);
        //         Route::delete('/{no_agenda}', [SuratMasukController::class, 'delete']);
        //         Route::post('/{no_agenda}/disposisi-dir', [SuratMasukController::class, 'disposisiDir']);
        //         Route::post('/{no_agenda}/disposisi-op', [SuratMasukController::class, 'disposisiOp']);
        //     });
        //     Route::prefix('keluar')->group(function () {
        //         Route::get('/', [SuratKeluarController::class, 'index']);
        //         Route::get('/code', [SuratKeluarController::class, 'code']);
        //         Route::post('/', [SuratKeluarController::class, 'create']);
        //         Route::get('/{kode_surat}', [SuratKeluarController::class, 'show']);
        //         Route::post('/{kode_surat}', [SuratKeluarController::class, 'update']);
        //         Route::delete('/{kode_surat}', [SuratKeluarController::class, 'delete']);
        //         Route::post('/{kode_surat}/validate', [SuratKeluarController::class, 'validateSuratKeluar']);
        //     });
        //     Route::prefix('disposisi')->group(function () {
        //         Route::get('/', [DisposisiController::class, 'index']);
        //     });
        // });
        // Route::prefix('user')->group(function () {
        //     Route::post('update', [UserController::class, 'update']);
        //     Route::post('update-password', [UserController::class, 'updatePassword']);
        // });
    });
    Route::middleware('guest')->group(function () {
        Route::post('auth/login', [AuthController::class, 'login']);
    });
    Route::prefix('master')->group(function () {
        // Route::get('pejabat', [MasterController::class, 'pejabat']);
        // Route::get('unit', [MasterController::class, 'unit']);
    });
});



Route::resource('banners', BannerAPIController::class);


Route::resource('complaint_categories', App\Http\Controllers\API\v1\ComplaintCategoryAPIController::class);


Route::resource('complaints', App\Http\Controllers\API\v1\ComplaintAPIController::class);


Route::resource('complaint_replies', App\Http\Controllers\API\v1\ComplaintReplyAPIController::class);


Route::resource('messages', App\Http\Controllers\API\v1\MessageAPIController::class);


Route::resource('notification_categories', App\Http\Controllers\API\v1\NotificationCategoryAPIController::class);


Route::resource('notifications', App\Http\Controllers\API\v1\NotificationAPIController::class);


Route::resource('units', App\Http\Controllers\API\v1\UnitAPIController::class);


Route::resource('billings', App\Http\Controllers\API\v1\BillingAPIController::class);


Route::resource('billing_transactions', App\Http\Controllers\API\v1\BillingTransactionAPIController::class);


Route::resource('billing_import_logs', App\Http\Controllers\API\v1\BillingImportLogAPIController::class);
