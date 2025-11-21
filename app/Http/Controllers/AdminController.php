<?php

namespace App\Http\Controllers;
use App\Models\Dasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    ####### Below is ang mga code para ma view ang pages #########

    public function RegisterPage()
    {
        return view('RegisterPage');
    }
    public function LoginPage()
    {
        return view('LoginPage');
    }

    //dont forget to add session destroy after logging out
    public function LogoutRequest()
    {
        Auth::guard('dasher')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login_page')->with('success', 'Logged out successfully');
    }


    ################## Below is code para sa mga requests ng user OK?!####################

    public function LoginRequest(Request $request)
    {
        $valid = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email address',
            'password.required' => 'Password is required'
        ]);

        if (Auth::guard('dasher')->attempt($valid)) {
            return redirect()->route('user-board')->with('success', 'Successfully logged in');
        }

        return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
    }


    public function RegisterRequest(Request $request)
    {
        // Validate the incoming request
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:dasher,email',
            'password' => 'required|string|confirmed|min:6',
        ], [
            'email.required' => 'Email required',
            'email.unique' => 'Email already taken',
            'password.required' => 'Password required',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        // Create the user
        $dasher = Dasher::create([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'password' => Hash::make($valid['password']),
        ]);

        Auth::guard('dasher')->loginUsingId($dasher->id);

        // Redirect to user dashboard after registration
        return redirect()->route('user-board')->with('success', 'Account created successfully!');
    }
}
