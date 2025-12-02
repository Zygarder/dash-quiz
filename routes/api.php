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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AdminApiController::class, 'login']);
Route::post('/register', [AdminApiController::class, 'register']);

// Admin Side
Route::middleware('auth:dasher')->group(function () {
    Route::get('/admin/dashboard', [AdminApiController::class, 'dashboard']);
    Route::get('/admin/quizzes', [AdminApiController::class, 'allQuizzes']);
    Route::post('/admin/quizzes/create', [AdminApiController::class, 'createQuiz']);
    Route::delete('/admin/quizzes/{id}', [AdminApiController::class, 'deleteQuiz']);
    Route::get('/admin/users', [AdminApiController::class, 'allUsers']);
    Route::delete('/admin/users/{id}', [AdminApiController::class, 'deleteUser']);
    Route::get('/admin/records', [AdminApiController::class, 'studentRecords']);
});

Route::middleware('auth:dasher')->group(function() {
    Route::get('/dashboard/leaderboard', [UserApiController::class, 'leaderboard']);
    Route::get('/quizzes', [UserApiController::class, 'quizzes']);
    Route::get('/profile', [UserApiController::class, 'profile']);
    Route::get('/records', [UserApiController::class, 'records']);
});

//Profile Page
Route::middleware('auth:dasher')->group(function () {
    Route::get('/profile', [ProfileApiController::class, 'getProfile']);
    Route::put('/profile/update', [ProfileApiController::class, 'updateProfile']);
    Route::put('/profile/password', [ProfileApiController::class, 'updatePassword']);
    Route::post('/profile/photo', [ProfileApiController::class, 'uploadPhoto']);
});


//Quiz Page
Route::middleware('auth:dasher')->group(function () {
    Route::get('/quiz', [QuizApiController::class, 'getQuiz']);
    Route::post('/quiz/answer', [QuizApiController::class, 'submitAnswer']);
    Route::post('/quiz/result', [QuizApiController::class, 'submitQuizResult']);
});


