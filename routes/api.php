<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('banners', BannerAPIController::class);


Route::resource('complaint_categories', App\Http\Controllers\API\v1\ComplaintCategoryAPIController::class);


Route::resource('complaints', App\Http\Controllers\API\v1\ComplaintAPIController::class);


Route::resource('complaint_replies', App\Http\Controllers\API\v1\ComplaintReplyAPIController::class);


Route::resource('messages', App\Http\Controllers\API\v1\MessageAPIController::class);
