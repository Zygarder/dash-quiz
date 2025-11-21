<?php

namespace App\Http\Controllers;

use App\Models\Dasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function LoginRequest(Request $request)
    {
        //dw about it, just an admin login test credentials.
        if ($request->email === 'admin@admin.com' && $request->password === 'admin') {
            return redirect()->route('admin-board');
        }
        #################### REMOVE ABOVE WHEN EVERYTHING IS READY OKAY?!#################
        ################## Below is code para sa mga requests ng user OK?!####################

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
            'email' => 'required|email|unique:dasher,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // Create the user
        Dasher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Redirect to user dashboard after registration
        return redirect()->route('login_page')->with('success', 'Account created successfully!');
    }


    ############################### USER DB ##################################



    //Admin side nav controller
    public function Dashboard()
    {
        return view('Admin_Folder.Dashboard');
    }

    public function Quizmgmt()
    {
        return view('Admin_Folder.ManageQuestions');
    }
    public function UserTable()
    {
        $dasher = DB::select("select * from dasher");
        return view('Admin_Folder.UsersTable', compact('dasher'));
    }
    public function Settings()
    {
        return view('Admin_Folder.Settings');
    }



    //db manager for users (admin side)

    public function dasherdelete($id)
    {
        DB::delete("DELETE from occupants where id=?", [$id]);
        return redirect('user-table')->with('success', 'data deleted');
    }

    //DB FOR QUIZZES
    //currently editing...


}
