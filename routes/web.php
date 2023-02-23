<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WarrantController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
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
    Route::get("/search", 'search')->name('search');
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
Route::get('/profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');

// Admin routes
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::controller(AdminArticleController::class)->prefix('articles')->name('articles.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{uuid}/edit', 'edit')->name('edit');
        Route::post('/{uuid}/edit', 'handleEdit');
        Route::get('/{uuid}/delete', 'delete')->name('delete');
    });

    Route::controller(CommentController::class)->prefix('comments')->name('comments.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{uuid}/delete', 'delete')->name('delete');
    });

    Route::controller(AdminReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{uuid}', 'view')->name('view');
        Route::get('/{uuid}/delete', 'delete')->name('delete');
    });

    Route::controller(AdminTagController::class)->prefix('tags')->name('tags.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::post('/{uuid}/edit', 'handleEdit');
        Route::get('/{uuid}/delete', 'delete')->name('delete');
    });

    Route::controller(AdminUserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{user:username}/disable', 'disable')->name('disable');
        Route::get('/{user:username}/delete', 'delete')->name('delete');
    });

    Route::controller(WarrantController::class)->prefix('warrants')->name('warrants.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{uuid}', 'view')->name('view');
        Route::get('/{uuid}/edit', 'edit')->name('edit');
        Route::post('/{uuid}/edit', 'handleEdit');
        Route::get('/{uuid}/delete', 'delete')->name('delete');
    });
});
