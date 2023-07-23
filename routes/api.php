<?php

use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\Api\StoriesController;
use App\Http\Controllers\Api\BlogsController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

Route::get('/home', [ShopifyController::class, 'home'])->middleware('shopify.auth');

Route::post('/update/settings', [ShopifyController::class, 'updateSettings'])->middleware('shopify.auth');

Route::get('/user', [ShopifyController::class, 'user']);

Route::post('webhook/theme/publish/{shop}', [WebhookController::class, 'handleThemePublish']);

Route::get('webhook/theme/test/publish', [WebhookController::class, 'handleTestPublish']);

Route::post('/add/story', [ShopifyController::class, 'store'])->middleware('shopify.auth');



Route::post('login',    [RegisterController::class, 'login']);
Route::post('delete',   [StoriesController::class, 'deleteStory']);
Route::get('add/proxy', [StoriesController::class, 'addProxy']);
Route::get('get/shop/stories', [StoriesController::class, 'getShopStories']);


Route::middleware(['auth:api', 'api'])->group(function () {
    Route::resource('stories', StoriesController::class);
    Route::resource('blogs', BlogsController::class);
    Route::get('blogs/get/all', [BlogsController::class, 'getAllBlogs']);
});





	
