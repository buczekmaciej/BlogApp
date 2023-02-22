<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/get-token', [UserController::class, 'getToken'])->middleware('guest');

Route::middleware('api')->group(function () {
    Route::post('/add-comment', [CommentController::class, 'comment']);
    Route::post('/user/{user:username}', [UserController::class, 'fetchUser']);

    Route::controller(ArticleController::class)->prefix('articles')->group(function () {
        Route::get('/fetch', 'fetchAll');
        Route::post('/{article:slug}', 'getArticle');
        Route::post('/{article:slug}/like', 'like');
    });

    Route::controller(AuthorController::class)->prefix('articles')->group(function () {
        Route::get('/fetch', 'fetchAll');
        Route::post('/{user:username}', 'getAuthor');
    });

    Route::controller(ReportController::class)->prefix('articles')->group(function () {
        Route::get('/get-reasons', 'getReasons');
        Route::post('/submit-report', 'submitReport');
    });

    Route::controller(TagController::class)->prefix('articles')->group(function () {
        Route::get('/fetch', 'fetchAll');
        Route::post('/{tag:name}', 'getTag');
    });
});
