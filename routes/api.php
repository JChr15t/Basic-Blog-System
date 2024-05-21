<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::apiResource('posts', PostController::class)->middleware('auth:api');
Route::apiResource('categories', CategoryController::class)->middleware('auth:api');


Route::middleware('auth:api')->group(function () {
    Route::post('posts/{post}/comments', [PostCommentController::class, 'store']);
    Route::get('posts/{post}/comments', [PostCommentController::class, 'index']);
    Route::get('posts/{post}/comments/{comment}', [PostCommentController::class, 'show']);
    Route::put('posts/{post}/comments/{comment}', [PostCommentController::class, 'update']);
    Route::delete('posts/{post}/comments/{comment}', [PostCommentController::class, 'destroy']);
});
