<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
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
    return view('LoginPage');
});

Route::get('/register_account', function () {
    return view('RegisterPage');
});


//REQUEST
Route::post('login-request', [AdminController::class, 'loginRequest'])->name('login_request');

Route::get('/logout', [AdminController::class,'logout'])->name('logout');





// USERS
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('/', 'dashboard')->name('user-board');
    Route::get('/quiz', 'quiz')->name('quiz-page');
    Route::get('/record', 'record')->name('record-page');
    Route::get('/profile', 'profile')->name('profile-page');
    
});

