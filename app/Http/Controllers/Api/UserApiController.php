<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizRecord;
use Illuminate\Support\Facades\Auth;

class UserApiController extends Controller
{
    // Leaderboard
    public function leaderboard()
    {
        $leaders = QuizRecord::with(['user'])
            ->whereHas('user')
            ->orderByDesc('score')
            ->limit(10)
            ->get()
            ->map(function($record) {
                return [
                    'user_id' => $record->user->id,
                    'name' => $record->user->first_name . ' ' . $record->user->last_name,
                    'profile_photo' => $record->user->profile_photo ? asset('storage/images/profiles/' . $record->user->profile_photo) : null,
                    'score' => $record->score,
                    'quiz_id' => $record->quiz_id
                ];
            });

        return response()->json([
            'status' => 'success',
            'leaders' => $leaders
        ]);
    }

    // List of quizzes
    public function quizzes()
    {
        $quizzes = Quiz::all(['id', 'title', 'description']);

        return response()->json([
            'status' => 'success',
            'quizzes' => $quizzes
        ]);
    }

    // Logged-in user profile
    public function profile()
    {
        $user = Auth::guard('dasher')->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'profile_photo' => $user->profile_photo ? asset('storage/images/profiles/' . $user->profile_photo) : null,
                'quizzes_taken' => QuizRecord::where('user_id', $user->id)->count()
            ]
        ]);
    }

    // User quiz records
    public function records()
    {
        $userId = Auth::guard('dasher')->id();
        $records = QuizRecord::where('user_id', $userId)
            ->orderByDesc('completed_at')
            ->get()
            ->map(function ($record) {
                return [
                    'quiz_id' => $record->quiz_id,
                    'score' => $record->score,
                    'completed_at' => $record->completed_at->format('Y-m-d')
                ];
            });

        return response()->json([
            'status' => 'success',
            'records' => $records
        ]);
    }
}
