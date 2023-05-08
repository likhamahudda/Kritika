<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CronJobController;
use App\Http\Controllers\Api\ApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::controller(CronJobController::class)->group(function () {
    //Route::post('subscription_check', 'subscription_check');
    Route::get('send_notification', 'send_notification');
});


Route::post('/countryList', [ApiController::class, 'countryList']); 
Route::post('/stateList', [ApiController::class, 'getStateByCountry']);
Route::post('/districtList', [ApiController::class, 'getDistricts']);
Route::post('/tehsilsList', [ApiController::class, 'getTehsils']);
Route::post('/panchayatList', [ApiController::class, 'getPanchayat']);
Route::post('/villageList', [ApiController::class, 'getVillage']);











