<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ProfileApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AdminApiController::class, 'login']);
Route::post('/register', [AdminApiController::class, 'register']);


// =========================
// ADMIN ROUTES
// =========================
Route::middleware('auth:admin')->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminApiController::class, 'dashboard']);

    Route::get('/quizzes', [AdminApiController::class, 'allQuizzes']);
    Route::post('/quizzes/create', [AdminApiController::class, 'createQuiz']);
    Route::delete('/quizzes/{id}', [AdminApiController::class, 'deleteQuiz']);

    Route::get('/users', [AdminApiController::class, 'allUsers']);
    Route::delete('/users/{id}', [AdminApiController::class, 'deleteUser']);

    Route::get('/records', [AdminApiController::class, 'studentRecords']);

});


// =========================
// USER ROUTES
// =========================
Route::middleware('auth:dasher')->group(function () {

    // Dashboard
    Route::get('/dashboard/leaderboard', [UserApiController::class, 'leaderboard']);

    // Quizzes
    Route::get('/quizzes', [UserApiController::class, 'quizzes']);

    // Records
    Route::get('/records', [UserApiController::class, 'records']);

    // Profile
    Route::get('/profile', [ProfileApiController::class, 'getProfile']);
    Route::put('/profile/update', [ProfileApiController::class, 'updateProfile']);
    Route::put('/profile/password', [ProfileApiController::class, 'updatePassword']);
    Route::post('/profile/photo', [ProfileApiController::class, 'uploadPhoto']);

});


// =========================
// QUIZ ROUTES
// =========================
Route::get('/quiz/{id}', [QuizApiController::class, 'getQuiz']);
Route::post('/quiz/answer', [QuizApiController::class, 'submitAnswer']);
Route::post('/quiz/result', [QuizApiController::class, 'submitQuizResult']);