<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function Dashboard()
    {
        return view('User_Folder.Dashboard');
    }

    public function QuizPage()
    {
        return view('User_Folder.QuizPage');
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
