<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UsersController;
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
    return Auth::check() ? redirect('/events/index') : redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/registration-completed', function () {
        return view('users.registration-completed');
    })->name('registration.completed');
    
    Route::get('/logout', function () {
        return view('logout');
    })->name('logout');
    Route::get('/events/index', function () {
        return view('events.index');
    });
    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UsersController::class, 'show'])->name('users.show');
});

Route::middleware('admin')->group(function () {
    // adminユーザーのみがアクセス可能なルート
    Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::resource('users', UsersController::class)->only(['edit', 'update', 'destroy']);
    // その他のadmin限定ルート...
});

 





require __DIR__ . '/auth.php';

