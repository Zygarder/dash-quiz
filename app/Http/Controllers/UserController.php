<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\QuizRecord;
use App\Models\Quiz;
class UserController extends Controller
{
    public function Dashboard()
    {
        $leaders = QuizRecord::with(['user', 'quiz']) // get user and quiz table
            ->orderByDesc('score')                     // sort by score descending
            ->limit(10)                                // top 10 scores
            ->get();

        return view('User_Folder.Dashboard', compact('leaders'));
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
        $countUsers = QuizRecord::where('quiz_id', $dasher->id)
            ->distinct('user_id')
            ->count('user_id');

        return view('User_Folder.ProfilePage', compact('dasher', 'fullname', 'countUsers'));
    }

    public function LogoutUser()
    {
        Auth::guard('dasher')->logout();
        session()->flush();
        session()->regenerate(true);
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
