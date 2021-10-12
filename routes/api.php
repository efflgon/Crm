<?php

use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OfferController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group(
    ['prefix' => 'v1', 'namespace' => 'Api'],
    function () {
        //auth
        Route::get('/user', [AuthController::class, 'info'])->middleware('auth:sanctum');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('/auth', [AuthController::class, 'auth']);
        Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');

        // leads
        Route::group(
            ['prefix' => 'lead'],
            function () {
                Route::post('/', [LeadController::class, 'create']);
                Route::get('/', [LeadController::class, 'getAll'])->middleware('auth:sanctum');
                Route::put('/{id}', [LeadController::class, 'update'])->middleware('auth:sanctum');
            }
        );

        // offer
        Route::group(
            ['prefix' => 'offers', 'middleware' => 'auth:sanctum'],
            function () {
                Route::get('/', [OfferController::class, 'gelAll']);
                Route::post('/', [OfferController::class, 'create']);
                Route::get('/{id}', [OfferController::class, 'get']);
                Route::put('/{id}', [OfferController::class, 'update']);
                Route::delete('/{id}', [OfferController::class, 'delete']);
            }
        );

        // affiliates
        Route::group(
            ['prefix' => 'affiliates', 'middleware' => 'auth:sanctum'],
            function () {
                Route::get('/', [AffiliateController::class, 'getAll']);
                Route::post('/', [AffiliateController::class, 'create']);
                Route::get('/{id}', [AffiliateController::class, 'get']);
                Route::put('/{id}', [AffiliateController::class, 'update']);
                Route::delete('/{id}', [AffiliateController::class, 'delete']);
            }
        );

        // integrations
        Route::group(
            ['prefix' => 'integrations', 'middleware' => 'auth:sanctum'],
            function () {
                Route::get('/', [IntegrationController::class, 'getAll']);
            }
        );
    }
);
