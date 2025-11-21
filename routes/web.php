<?php

use App\Http\Controllers\QuizController;
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

//LANDING PAGE ROUTES -- doesnt have a forgot password feature yet
Route::get('/', [AdminController::class, 'LoginPage'])->name('login_page');
Route::get('/register_account', [AdminController::class, 'RegisterPage'])->name('register_page');

//REQUEST ROUTES
Route::post('login-request', [AdminController::class, 'LoginRequest'])->name('login_request');
Route::post('/register-request', [AdminController::class, 'RegisterRequest'])->name('register_request');
Route::get('/logout', [AdminController::class, 'Logout'])->name('logout');

// USERS ROUTES
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('/', 'Dashboard')->name('user-board');
    Route::get('/quiz', 'QuizPage')->name('quiz-page');
    Route::get('/record', 'RecordPage')->name('record-page');
    Route::get('/profile', 'ProfilePage')->name('profile-page');
    Route::get('/take-quiz', 'TakingQuiz')->name('take-quiz-page');
});

//fix GET issue after answering one question
Route::post('/quiz-submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');

//load the questions for a questionnaire category
Route::get('/quiz-start', [QuizController::class, 'show'])->name('quiz.start');


// back to basics //
use App\Http\Controllers\BasicController;
Route::get('basic', function(){
    return view('basic');
});
Route::get('login-request',[BasicController::class, 'login'])->name('basic-login');

//admin side routes
Route::prefix('admin')->controller(AdminController::class)->group(function(){
    Route::get('/', 'Dashboard')->name('admin-board');
    Route::get('/quizmgmt', 'Quizmgmt')->name('quiz-manage');
    Route::get('/records', 'UserTable')->name('user-table');
    Route::get('/del{id}', 'dasherdelete')->name('deleteuser');
    Route::get('/settings', 'Settings')->name('settings');
});