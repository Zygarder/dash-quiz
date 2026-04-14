<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizRecord;
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
                    'name' => "{$record->user->first_name} {$record->user->last_name}",
                    'profile_photo' => $record->user->profile_photo,
                    'score' => $record->score,
                    'quiz_title' => $record->quiz->title,
                    'user_id' => $record->user_id
                ];
            });
        return response()->json([
            'status' => 'success',
            'data' => $leaders
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
            'data' => $quizzes
        ]);
    }
    public function profile()
    {
        if (Auth::guard('dasher')->check()) {
            $user = Auth::guard('dasher')->user();

            // Update online status
            $user->update([
                'active_status' => 1,
                'last_activity' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'results' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'role' => $user->role,
                    'full_name' => "{$user->first_name} {$user->last_name}",
                    'email' => $user->email,
                    'profile_photo' => $user->profile_photo ?? 'default.png',
                    'created_at' => $user->created_at->format('F j, Y'),
                    'quizzes_taken' => QuizRecord::where('user_id', $user->id)->count(),
                    'active_status' => $user->active_status, // optional for frontend
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthenticated.'
        ], 401);
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