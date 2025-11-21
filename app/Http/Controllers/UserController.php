<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function Dashboard()
    {
        return view('User_Folder.Dashboard');
    }

    public function QuizPage()
    {
    $quizzes = DB::table('quizzes')->get();
        return view('User_Folder.QuizPage',compact('quizzes')); //added for optional scalability
    }

    public function ProfilePage()
    {
        return view('User_Folder.ProfilePage');
    }
    public function RecordPage()
    {
        return view('User_Folder.RecordPage');
    }

    public function TakingQuiz()
    {
        return view('User_Folder.TakeQuizPage');
    }

}
