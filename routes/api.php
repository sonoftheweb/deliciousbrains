<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1/public')->group(function () {

    Route::resource('application', 'Api\ApplicationController')->only(['index']);
    Route::post('login', 'Api\AuthenticateController@login');

});

Route::middleware(['auth:api', 'throttle:60,1'])->prefix('v1')->group(function () {

    Route::resource('application', 'Api\ApplicationController')->only(['index']);

    Route::resource('users', 'Api\UserController')->only(['show']);

    Route::resource('account-activity', 'Api\AccountActivityController')->only(['index', 'store', 'update']);

});

Route::fallback(function(){
    return response()->json([
        'message' => 'No such resource...'], 404);
});
