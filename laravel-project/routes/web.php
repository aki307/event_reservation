<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Auth;






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

    Route::prefix('events')->group(function () {
        Route::get('/today', [EventsController::class, 'todaysEvents'])->name('events.today');
        Route::get('/', [EventsController::class, 'index'])->name('events.index');
        Route::get('/{event}', [EventsController::class, 'show'])->where('event', '[0-9]+')->name('events.show');
        Route::get('events/create', [EventsController::class, 'create'])->name('events.create');
        Route::post('/', [EventsController::class, 'store'])->name('events.store');
        Route::get('/{event}/edit', [EventsController::class, 'edit'])->where('event', '[0-9]+')->name('events.edit');
        Route::resource('events', EventsController::class)->only(['update', 'destroy']);
        Route::post('/{event}/attend', [AttendanceController::class, 'store'])->name('events.attend');
        Route::delete('/{event}/unattend', [AttendanceController::class, 'destroy'])->name('events.unattend');
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
