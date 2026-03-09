<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\QuizRecord;
use App\Models\Dasher;
use Illuminate\Support\Facades\Auth;

class UserApiController extends Controller
{
    // Leaderboard
    public function leaderboard()
    {
        $leaders = QuizRecord::with(['user', 'quiz'])
            ->whereHas('user')
            ->orderByDesc('score')
            ->limit(10)
            ->get()
            ->map(function ($record) {
                return [
                    'name' => "{$record->user->first_name} {$record->user->last_name}",
                    'profile_photo' => $record->user->profile_photo
                        ? $record->user->profile_photo
                        : 'default.png',
                    'score' => $record->score,
                    'quiz_title' => $record->quiz->title,
                    'user_id' => $record->user_id
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
                'full_name' => "{$user->first_name} {$user->last_name}",
                'email' => $user->email,
                'profile_photo' => $user->profile_photo ?? 'default.png',
                'quizzes_taken' => QuizRecord::where('user_id', $user->id)->count(),
                'created_at' => $user->created_at->format('F j, Y')
            ]
        ]);
    }

    // User quiz records
    public function records()
    {
        $userId = Auth::guard('dasher')->id();

        $records = QuizRecord::where('user_id', $userId)->with(['quiz'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($record) {
                return [
                    'quiz_id' => $record->quiz_id,
                    'score' => $record->score,
                    'quiz_title' => $record->quiz->title,
                    'created_at' => $record->created_at->format('Y-m-d')
                ];
            });

        return response()->json([
            'status' => 'success',
            'records' => $records
        ]);
    }

    public function logout(Request $request)
    {
        // explicitly log out the dasher guard (API users are dashers)
        if (Auth::guard('dasher')->check()) {
            $id = Auth::guard('dasher')->user()->id;
            Dasher::where('id', $id)->update(['active_status' => 0]);
            Auth::guard('dasher')->logout();
        }

        // invalidate session/csrf token regardless
        $request->session()->invalidate();
        $request->session()->regenerateToken();

    }
}