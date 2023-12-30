<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UsersController;






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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/user/registration-completed', function () {
    return view('users.registration-completed');
})->name('registration.completed');


require __DIR__.'/auth.php';

Route::get('signup', [RegisteredUserController::class, 'create'])->name('signup.get');
Route::post('signup', [RegisteredUserController::class, 'register'])->name('signup.post');

Route::resource('users', UsersController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
Route::get('/logout', function () {
    return view('logout'); 
})->name('logout');

Route::get('/events/index', function () {
    return view('events.index'); // ここでlogout.blade.phpを指定
});

