<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\QuizRecord;
use App\Models\Dasher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserApiController extends Controller
{
    // Get top users based on quiz scores
    public function leaderboard()
    {
        // Fetch quiz records with related user and quiz
        // whereHas('user') ensures the record still has a valid user
        $leaders = QuizRecord::with(['user', 'quiz'])
            ->whereHas('user')
            ->orderByDesc('score') // highest scores first
            ->limit(10) // show only top 10 users
            ->get()
            ->map(function ($record) {

                return [
                    // Combine first and last name for easier display
                    'name' => "{$record->user->first_name} {$record->user->last_name}",

                    // If user has no photo, return default image
                    'profile_photo' => $record->user->profile_photo,

                    // Score achieved in the quiz
                    'score' => $record->score,

                    // Title of the quiz the score belongs to
                    'quiz_title' => $record->quiz->title,

                    // User ID can be used by frontend for profile links
                    'user_id' => $record->user_id
                ];
            });

        return response()->json([
            'status' => 'success',
            'results' => $leaders
        ]);
    }

    // Get list of available quizzes
    public function quizzes()
    {
        // Retrieve quiz list with only necessary fields
        // Reduces response size and improves performance
        $quizzes = Quiz::all(['id', 'title', 'description']);

        return response()->json([
            'status' => 'success',
            'results' => $quizzes
        ]);
    }

    // Get logged-in user's profile information
    public function profile()
    {
        // Get authenticated user using the dasher guard
        $user = Auth::guard('dasher')->user();

        return response()->json([
            'status' => 'success',
            'results' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,

                // Full name for easier frontend display
                'full_name' => "{$user->first_name} {$user->last_name}",

                'email' => $user->email,

                // If no profile photo exists, return default image
                'profile_photo' => $user->profile_photo ?? 'default.png',

                // Count number of quizzes the user has taken
                'quizzes_taken' => QuizRecord::where('user_id', $user->id)->count(),

                // Format account creation date
                'created_at' => $user->created_at->format('F j, Y')
            ]
        ]);
    }

    // Get quiz history of the logged-in user
    public function records()
    {
        // Get authenticated user's ID
        $userId = Auth::guard('dasher')->id();

        // Fetch quiz records for this user
        $records = QuizRecord::where('user_id', $userId)
            ->with(['quiz']) // load quiz information
            ->orderByDesc('created_at') // newest records first
            ->get()
            ->map(function ($record) {

                return [
                    'quiz_id' => $record->quiz_id,
                    'score' => $record->score,

                    // Include quiz details for frontend display
                    'quiz_title' => $record->quiz->title,
                    'quiz_description' => $record->quiz->description,

                    // Format record date
                    'created_at' => $record->created_at->format('Y-m-d')
                ];
            });

        return response()->json([
            'results' => $records
        ]);
    }
}