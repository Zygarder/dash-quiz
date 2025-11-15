<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard(){
        return view('User_Folder.Dashboard');
    }

    public function quiz(){
        return view('User_Folder.QuizPage');
    }

    public function profile(){
        return view('User_Folder.ProfilePage');
    }
    public function record(){
        return view('User_Folder.RecordPage');
    }

    
}
