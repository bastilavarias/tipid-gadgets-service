<?php

use App\Http\Controllers\api\ItemBookmarkController;
use App\Http\Controllers\api\ItemController;
use App\Http\Controllers\api\ItemLikeController;
use App\Http\Controllers\api\ItemViewController;
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

Route::prefix('item')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::post('/', [ItemController::class, 'store'])->middleware('auth:api');
    Route::get('/drafts', [ItemController::class, 'getDrafts'])->middleware('auth:api');
    Route::get('/{slug}', [ItemController::class, 'show']);
    Route::post('/drafts', [ItemController::class, 'storeDraft'])->middleware('auth:api');
    Route::post('/views', [ItemViewController::class, 'store']);
    Route::post('/bookmarks', [ItemBookmarkController::class, 'store'])->middleware(
        'auth:api'
    );
    Route::get('/bookmarks/check/{itemID}', [
        ItemBookmarkController::class,
        'check',
    ])->middleware('auth:api');
    Route::post('/likes', [ItemLikeController::class, 'store'])->middleware('auth:api');
    Route::get('/likes/check/{itemID}', [ItemLikeController::class, 'check'])->middleware(
        'auth:api'
    );
    Route::delete('/drafts/{id}', [ItemController::class, 'deleteDraft'])->middleware(
        'auth:api'
    );
    Route::get('/{id}/images', [ItemController::class, 'getImages']);
});
