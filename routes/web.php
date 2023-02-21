<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(AppController::class)->name("app.")->group(function () {
    Route::get("/", 'index')->name('homepage');
    Route::get("/search/{q}", 'search')->name('search');
});

Route::controller(SecurityController::class)->prefix('security')->name('security.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'handleLogin');

        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'handleRegister');
    });

    Route::get('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::controller(TagController::class)->name('tags.')->prefix('tags')->group(function () {
    Route::get('/', 'list')->name('list');
    Route::get('/{tag:name}')->name('view');
});

Route::controller(AuthorController::class)->name('authors.')->prefix('authors')->group(function () {
    Route::get('/', 'list')->name('list');
    Route::get('/{user:username}')->name('view');
});

Route::controller(ArticleController::class)->name('articles.')->prefix('articles')->group(function () {
    Route::get('/', 'list')->name('list');
    Route::get('/{article:slug}')->name('view');
});

Route::post('/submit-report', [ReportController::class, 'list'])->name('submitReport')->middleware('auth');
