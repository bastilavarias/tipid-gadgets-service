<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ReferenceController;
use App\Http\Controllers\api\AuthenticationController;

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

Route::prefix('reference')->group(function () {
    Route::get('/locations', [ReferenceController::class, 'locations']);
    Route::get('/item/sections', [ReferenceController::class, 'itemSections']);
    Route::get('/item/categories', [ReferenceController::class, 'itemCategories']);
    Route::get('/item/conditions', [ReferenceController::class, 'itemConditions']);
    Route::get('/item/warranties', [ReferenceController::class, 'itemWarranties']);
});

Route::prefix('authentication')->group(function () {
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/oauth2/github', [
        AuthenticationController::class,
        'githubAuthentication',
    ]);
});
