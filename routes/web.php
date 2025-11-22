<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecordsController;
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

//Front Page Request Routes
Route::post('login-request', [AdminController::class, 'LoginRequest'])->name('login_request');
Route::post('/register-request', [AdminController::class, 'RegisterRequest'])->name('register_request');

// USERS ROUTES
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('/', 'Dashboard')->name('user-board');
    Route::get('/quiz', 'QuizPage')->name('quiz-page');
    Route::get('/record', 'RecordPage')->name('record-page');
    Route::get('/profile', 'ProfilePage')->name('profile-page');
    Route::get('/take-quiz', 'TakingQuiz')->name('take-quiz-page');
    Route::get('/logout-user', 'LogoutUser')->name('logout-user');
});

//fix GET issue after answering one question
Route::post('/quiz-submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');

//load the questions for a questionnaire category
Route::get('/quiz-start', [QuizController::class, 'show'])->name('quiz.start');


//admin side routes
Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/', 'Dashboard')->name('admin-board');
    Route::get('/quizmgmt', 'Quizmgmt')->name('quiz-manage');
    Route::get('/admin-logout', 'LogoutRequest')->name('admin-logout');

    // QUIZ MANAGEMENT
    Route::get('/quiz/create', 'addquiz')->name('quiz-add');
    Route::post('/quiz/store', 'savequiz')->name('quiz-save');
<<<<<<< HEAD

=======
    Route::get('/quiz/{id}/edit', 'editQuiz')->name('quiz-edit');
    Route::put('/quiz/{id}', 'updateQuiz')->name('quiz-update');
    Route::post('/quiz/del/{id}','deletequiz')->name('quizdel');

    
>>>>>>> refs/remotes/origin/main
    //USER MANAGEMENT
    Route::get('/records', 'UserTable')->name('user-table');
    Route::post('/del/{id}', 'dasherdelete')->name('deleteuser');
    Route::get('/studentrecords', 'srecords')->name('srecords');
});

