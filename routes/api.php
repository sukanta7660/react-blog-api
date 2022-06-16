<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/authenticated', function () {
        return true;
    });

    Route::get('/user', [UserController::class, 'getUser']);

    Route::post('/store-post', [PostController::class, 'store_post']);
    Route::get('/single-post/{id}/{slug}', [PostController::class, 'fetch_single_post']);
    Route::post('/update-post', [PostController::class, 'update_post']);
    Route::get('/delete-post/{id}', [PostController::class, 'delete_post']);

    Route::get('category-list', [CategoryController::class, 'categories']);
    Route::post('store-category', [CategoryController::class, 'store_category']);
    Route::get('/single-category/{id}/{slug}', [CategoryController::class, 'fetch_single_category']);
    Route::post('/update-category', [CategoryController::class, 'update_category']);
    Route::get('/delete-category/{id}', [CategoryController::class, 'delete_category']);
});

Route::get('/all-posts', [PostController::class, 'all_posts']);
Route::get('/post-details/{id}/{slug}', [PostController::class, 'fetch_single_post']);
Route::get('categories', [PostController::class, 'categories']);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
