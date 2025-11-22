<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizRecord;
use App\Models\Dasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function Dashboard()
    {
        $leaders = QuizRecord::with(['user', 'quiz']) // eager load user and quiz
        ->orderByDesc('score')                     // sort by score descending
        ->limit(10)                                // top 10 scores
        ->get();

    return view('User_Folder.Dashboard', compact('leaders'));
    }

    public function QuizPage()
    {
        session()->forget(['quiz_questions', 'quiz_index', 'score']); // reset session
        $quizzes = DB::table('quizzes')->get();
        return view('User_Folder.QuizPage', compact('quizzes')); //added for optional scalability
    }

    public function ProfilePage()
    {
        $dasher = Auth::guard('dasher')->user();
        $fullname = $dasher->first_name . ' ' . $dasher->last_name;
        $countUsers = QuizRecord::where('quiz_id', $dasher->id)
            ->distinct('user_id')
            ->count('user_id');

        return view('User_Folder.ProfilePage', compact('dasher', 'fullname', 'countUsers'));
    }

    public function LogoutUser()
    {
        return redirect()->route('login_page');
    }

    public function RecordPage()
    {
        // Get the logged-in user's quiz records
        $userId = Auth::guard('dasher')->id();// Assuming you use Laravel auth
        $records = QuizRecord::where('user_id', $userId)
            ->orderBy('completed_at', 'desc')
            ->get();


        return view('User_Folder.RecordPage', compact('records'));
    }

    public function TakingQuiz()
    {
        return view('User_Folder.TakeQuizPage');
    }

}
