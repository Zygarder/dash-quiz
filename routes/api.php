<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ProfileApiController;

/*
| Public Routes
*/
Route::post('/login', [AdminApiController::class, 'login']);
Route::post('/register', [AdminApiController::class, 'register']);

/*
| Authenticated Routes (Dashers/Users)
*/
// Note: We use auth:sanctum because it handles the session cookie from your Vue app
Route::middleware('auth:sanctum')->group(function () {

    // Matches your fetchUser() call: axios.get("/api/me")
    Route::get('/me', [UserApiController::class, 'profile']);

    // Dashboard & Leaderboard
    // Moved inside middleware so UserApiController can use Auth::id() to highlight "You"
    Route::get('/dashboard/leaderboard', [UserApiController::class, 'leaderboard']);

    // Quizzes
    Route::get('/quizzes', [UserApiController::class, 'quizzes']);
    Route::get('/quiz/{quiz_id}', [QuizApiController::class, 'getQuiz']);
    Route::get('/quiz/progress', [QuizApiController::class, 'getQuizProgress']);
    Route::post('/quiz/answer', [QuizApiController::class, 'submitAnswer']);
    Route::post('/quiz/result', [QuizApiController::class, 'submitQuizResult']);

    // Records
    Route::get('/records', [UserApiController::class, 'records']);

    // Profile
    Route::put('/profile/update', [ProfileApiController::class, 'updateProfile']);

    Route::post('/profile/photo', [ProfileApiController::class, 'uploadPhoto']);
    // Self-Delete
    Route::delete('/profile/delete', [ProfileApiController::class, 'selfDeleteAccount']);
    // Logout
    Route::post('/logout', [AdminApiController::class, 'logout']);
});

/*
| Admin Specific Routes
*/
// Ensure your 'admin' guard is configured in config/auth.php
Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminApiController::class, 'dashboard']);
    Route::get('/admin/quizzes', [AdminApiController::class, 'allQuizzes']);
    Route::get('/admin/quizzes/{id}/edit', [AdminApiController::class, 'editQuiz']);
    Route::put('/admin/quizzes/{id}', [AdminApiController::class, 'updateQuiz']);
    Route::post('/admin/quizzes/create', [AdminApiController::class, 'createQuiz']);
    Route::delete('/admin/quizzes/{id}', [AdminApiController::class, 'deleteQuiz']);
    Route::get('/admin/users', [AdminApiController::class, 'allUsers']);
    Route::delete('/admin/users/{id}', [AdminApiController::class, 'deleteUser']);
    Route::get('/admin/records', [AdminApiController::class, 'studentRecords']);
});