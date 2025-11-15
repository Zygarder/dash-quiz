<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function loginRequest(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password' => 'required|string'
        ]);

        return redirect()->route('user-board');
    }

    public function logout(){
        return view('LoginPage');
    }
}
