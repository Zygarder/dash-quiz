<?php

namespace App\Http\Controllers;
use App\Models\Dasher;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    ####### Below is ang mga code para ma view ang pages #########

    //go to register page
    public function RegisterPage()
    {
        return view('RegisterPage');
    }
    public function LoginPage()
    {
        return view('LoginPage');
    }

    //dont forget to add session destroy after logging out
    public function Logout()
    {
        return view('LoginPage');
    }

    ################## Below is code para sa mga requests ng user OK?!####################

    public function LoginRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        return redirect()->route('user-board');
    }

    public function RegisterRequest(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // Create the user
        Dasher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Redirect to user dashboard after registration
        return redirect()->route('user-board')->with('success', 'Account created successfully!');
    }


}
