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

//LANDING PAGE ROUTES -- doesnt have a forgotpassword feature yet
Route::get('/', [AdminController::class, 'LoginPage'])->name('login_page');
Route::get('/register_account', [AdminController::class, 'RegisterPage'])->name('register_page');

//REQUEST ROUTES
Route::post('login-request', [AdminController::class, 'LoginRequest'])->name('login_request');
Route::post('/register-request', [AdminController::class, 'RegisterRequest'])->name('register_request');
Route::get('/logout', [AdminController::class, 'LogoutRequest'])->name('logout');

// USERS ROUTES
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('/', 'Dashboard')->name('user-board');
    Route::get('/quiz', 'QuizPage')->name('quiz-page');
    Route::get('/record', 'RecordPage')->name('record-page');
    Route::get('/profile', 'ProfilePage')->name('profile-page');
    Route::get('/take-quiz', 'TakingQuiz')->name('take-quiz-page');
});

//fix GET issue after answering one question
Route::get('/quiz-show', [QuizController::class, 'show'])->name('quiz.show');
Route::post('/quiz-submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');

//load the questions for a questionnaire category
Route::post('/quiz-start', [QuizController::class, 'show'])->name('quiz.start');