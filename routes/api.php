<?php

use App\Http\Controllers\api\ItemBookmarkController;
use App\Http\Controllers\api\ItemController;
use App\Http\Controllers\api\ItemLikeController;
use App\Http\Controllers\api\ItemViewController;
use App\Http\Controllers\api\InsightController;
use App\Http\Controllers\api\TopicBookmarkController;
use App\Http\Controllers\api\TopicCommentController;
use App\Http\Controllers\api\TopicLikeController;
use App\Http\Controllers\api\TopicViewController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ReferenceController;
use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\TopicController;

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
    Route::get('/search-types', [ReferenceController::class, 'searchTypes']);
    Route::get('/item/sections', [ReferenceController::class, 'itemSections']);
    Route::get('/item/categories', [ReferenceController::class, 'itemCategories']);
    Route::get('/item/conditions', [ReferenceController::class, 'itemConditions']);
    Route::get('/item/warranties', [ReferenceController::class, 'itemWarranties']);
    Route::get('/topic/sections', [ReferenceController::class, 'topicSections']);
});

Route::prefix('authentication')->group(function () {
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/oauth2/github', [
        AuthenticationController::class,
        'githubAuthentication',
    ]);
    Route::put('/password', [
        AuthenticationController::class,
        'changePassword',
    ])->middleware('auth:api');
});

Route::prefix('item')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::post('/', [ItemController::class, 'store'])->middleware('auth:api');
    Route::get('/drafts', [ItemController::class, 'getDrafts'])->middleware('auth:api');
    Route::get('/bookmarks', [ItemBookmarkController::class, 'index']);
    Route::get('/{slug}', [ItemController::class, 'show']);
    Route::post('/drafts', [ItemController::class, 'storeDraft'])->middleware('auth:api');
    Route::post('/views', [ItemViewController::class, 'store']);
    Route::get('/views/count/{itemID}', [ItemViewController::class, 'count']);
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
    Route::get('/likes/count/{itemID}', [ItemLikeController::class, 'count']);
    Route::delete('/drafts/{id}', [ItemController::class, 'deleteDraft'])->middleware(
        'auth:api'
    );
    Route::get('/{id}/images', [ItemController::class, 'getImages']);
});

Route::prefix('insight')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('/items/{id}', [InsightController::class, 'showItem']);
        Route::get('/topics/{id}', [InsightController::class, 'showTopic']);
    });

Route::prefix('topic')->group(function () {
    Route::get('/', [TopicController::class, 'index']);
    Route::post('/', [TopicController::class, 'store'])->middleware('auth:api');
    Route::get('/drafts', [TopicController::class, 'getDrafts'])->middleware('auth:api');
    Route::get('/bookmarks', [TopicBookmarkController::class, 'index']);
    Route::get('/{slug}', [TopicController::class, 'show']);
    Route::get('/comments/{topicID}', [TopicCommentController::class, 'index']);
    Route::get('/comments/count/{topicID}', [TopicCommentController::class, 'count']);
    Route::post('/drafts', [TopicController::class, 'storeDraft'])->middleware(
        'auth:api'
    );
    Route::post('/views', [TopicViewController::class, 'store']);
    Route::post('/comments', [TopicCommentController::class, 'store'])->middleware(
        'auth:api'
    );
    Route::get('/views/count/{topicID}', [TopicViewController::class, 'count']);
    Route::post('/bookmarks', [TopicBookmarkController::class, 'store'])->middleware(
        'auth:api'
    );
    Route::get('/bookmarks/check/{topicID}', [
        TopicBookmarkController::class,
        'check',
    ])->middleware('auth:api');
    Route::post('/likes', [TopicLikeController::class, 'store'])->middleware('auth:api');
    Route::get('/likes/check/{topicID}', [
        TopicLikeController::class,
        'check',
    ])->middleware('auth:api');
    Route::get('/likes/count/{topicID}', [TopicLikeController::class, 'count']);
    Route::delete('/drafts/{topicID}', [
        TopicController::class,
        'deleteDraft',
    ])->middleware('auth:api');
});

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/username/{username}', [UserController::class, 'showByUsername']);
    Route::put('/', [UserController::class, 'update'])->middleware('auth:api');
});
