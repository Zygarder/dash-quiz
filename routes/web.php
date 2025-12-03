<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// PUBLIC ROUTES (NO AUTH)
Route::get('/', [AdminController::class, 'LoginPage'])->name('login');
Route::get('/register-account', [AdminController::class, 'RegisterPage'])->name('register_page');
Route::get('/forgot-password', [AdminController::class, 'ForgotPage'])->name('forgot_page');

Route::post('/login-request', [AdminController::class, 'LoginRequest'])->name('login_request');
Route::post('/register-request', [AdminController::class, 'RegisterRequest'])->name('register_request');


// =========================================
// USER ROUTES (ALL REQUIRE AUTH)
// =========================================
Route::middleware('auth:dasher')->group(function () {

    // Routes using UserController
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'Dashboard')->name('user-board');
        Route::get('/leaderboard-data', 'leaderboard')->name('get-leaderboard');
        Route::get('/quiz', 'QuizPage')->name('quiz-page');
        Route::get('/record', 'RecordPage')->name('record-page');
        Route::get('/profile', 'ProfilePage')->name('profile-page');
        Route::get('/take-quiz', 'TakingQuiz')->name('take-quiz-page');
        Route::get('/logout-user', 'LogoutUser')->name('logout-user');
    });

    // Profile Update Routes (ProfileController)
    Route::prefix('user')->group(function () {
        Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile-update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile-new-password');
        Route::post('/profile/upload', [ProfileController::class, 'uploadPhoto'])->name('profile.upload');
    }); 

    // Answering quiz requires auth
    Route::post('/quiz-submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');

    // Load questions also requires auth
    Route::get('/quiz-start', [QuizController::class, 'show'])->name('quiz.start');
});


// =========================================
// ADMIN ROUTES (ALL REQUIRE AUTH)
// =========================================
Route::middleware('auth:dasher')->prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/', 'Dashboard')->name('admin-board');
    Route::get('/quizmgmt', 'Quizmgmt')->name('quiz-manage');
    Route::get('/admin-logout', 'LogoutRequest')->name('adminlogout');

    // QUIZ MANAGEMENT
    Route::get('/quiz/create', 'addquiz')->name('quiz-add');
    Route::post('/quiz/store', 'savequiz')->name('quiz-save');
    Route::get('/quiz/{id}/edit', 'editQuiz')->name('quiz-edit');
    Route::put('/quiz/{id}', 'updateQuiz')->name('quiz-update');
    Route::delete('/quiz/del/{id}', 'deletequiz')->name('quizdel');

    // USER MANAGEMENT
    Route::get('/records', 'UserTable')->name('user-table');
    Route::delete('/del/{id}', 'dasherdelete')->name('deleteuser');
    Route::get('/studentrecords', 'srecords')->name('srecords');
});
