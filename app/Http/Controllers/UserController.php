<?php

namespace App\Http\Controllers;

use App\Models\Dasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\QuizRecord;
use App\Models\Quiz;
class UserController extends Controller
{

    public function Dashboard()
    {
        Dasher::where('id', Auth::guard('dasher')
            ->user()->id)
            ->update(['active_status' => true]);
        return view('User_Folder.Dashboard');
    }

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
                    'user_id' => $record->user->id,
                    'name' => $record->user->first_name . ' ' . $record->user->last_name,
                    'profile_photo' => $record->user->profile_photo
                        ? asset('storage/images/profiles/' . $record->user->profile_photo)
                        : asset('images/profiles/person.jpg'),
                    'score' => $record->score,
                    'quiz_title' => $record->quiz->title
                ];
            });

        return response()->json([
            'status' => 'success',
            'leaders' => $leaders
        ]);
    }

    public function QuizPage()
    {
        session()->forget(['quiz_questions', 'quiz_index', 'score']); // reset session
        $quizzes = Quiz::all();
        return view('User_Folder.QuizPage', compact('quizzes')); //added for optional scalability
    }

    public function ProfilePage()
    {
        $dasher = Auth::guard('dasher')->user();
        $fullname = $dasher->first_name . ' ' . $dasher->last_name;
        $quizzesCount = QuizRecord::where('user_id', $dasher->id)->count('id');

        return view('User_Folder.ProfilePage', compact('dasher', 'fullname', 'quizzesCount'));
    }

    public function LogoutUser(Request $request)
    {
        Dasher::where('id', Auth::guard('dasher')
            ->user()->id)
            ->update(['active_status' => false]);
        Auth::guard('dasher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function RecordPage()
    {
        // Get the logged-in user's quiz records
        $userId = Auth::guard('dasher')->id();// Assuming you use Laravel auth
        $records = QuizRecord::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('User_Folder.RecordPage', compact('records'));
    }

    public function TakingQuiz()
    {
        return view('User_Folder.TakeQuizPage');
    }

}
