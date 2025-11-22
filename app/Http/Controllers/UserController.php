<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function Dashboard()
    {
        return view('User_Folder.Dashboard');
    }

    public function QuizPage()
    {
        session()->forget(['quiz_questions', 'quiz_index', 'score']); // reset session
        $quizzes = DB::table('quizzes')->get();
        return view('User_Folder.QuizPage', compact('quizzes')); //added for optional scalability
    }

    public function ProfilePage()
    {
        return view('User_Folder.ProfilePage');
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
