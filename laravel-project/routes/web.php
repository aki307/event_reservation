<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::get('/', function () {
    return Auth::check() ? redirect('/events/today') : redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/registration-completed', function () {
        return view('users.registration-completed');
    })->name('registration.completed');
    Route::get('/logout', function () {
        return view('logout');
    })->name('logout');
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('users.index');
        Route::get('/{user}', [UsersController::class, 'show'])->where('user', '[0-9]+')->name('users.show');
    });
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index')->middleware('auth');
    Route::post('/favorite/{event}', [FavoriteController::class, 'store'])->name('events.favorite');
    Route::prefix('events')->group(function () {
        Route::get('/today', [EventsController::class, 'todaysEvents'])->name('events.today');
        Route::get('/', [EventsController::class, 'index'])->name('events.index');
        Route::get('/{event}', [EventsController::class, 'show'])->where('event', '[0-9]+')->name('events.show');
        Route::get('events/create', [EventsController::class, 'create'])->name('events.create');
        Route::post('/', [EventsController::class, 'store'])->name('events.store');
        Route::get('/{event}/edit', [EventsController::class, 'edit'])->where('event', '[0-9]+')->name('events.edit');
        Route::put('events/{event}', [EventsController::class, 'update'])->name('events.update');
        Route::delete('events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');
        Route::post('/{event}/attend', [AttendanceController::class, 'store'])->name('events.attend');
        Route::delete('/{event}/unattend', [AttendanceController::class, 'destroy'])->name('events.unattend');
        Route::post('/{event}/post', [CommentController::class, 'create'])->name('comment.post');

        Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comment.update');
    });
    Route::prefix('comments')->group(function () {
        Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->where('comment', '[0-9]+');
    });
});
// adminユーザーのみがアクセス可能なルート
Route::middleware('admin')->group(function () {
    Route::resource('users', UsersController::class)->only(['edit', 'update', 'destroy']);
});
require __DIR__ . '/auth.php';

Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);

Route::get('login/google', [AuthenticatedSessionController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [AuthenticatedSessionController::class, 'handleGoogleCallback']);

Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->where('comment', '[0-9]+');
Route::get('/users/export', 'App\Http\Controllers\UsersController@exportCsv')->name('users.export');