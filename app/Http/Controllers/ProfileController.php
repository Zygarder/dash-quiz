<?php

namespace App\Http\Controllers;

use App\Models\Dasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    // Update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('dasher')->user(); // get current login user

        $valid = $request->validate([
            'fullName' => 'required|string|max:55',
        ]);


        // Split fullName → first_name + last_name
        $fullName = explode(' ', $valid['fullName'], 2);
        $user->first_name = $fullName[0];
        $user->last_name = $fullName[1] ?? '';
        $user->save(); //save new update to database

        return redirect()->back();
    }

    // Update password
    public function deleteAccount(Request $request)
    {
        // Validate input
        $valid = $request->validate([
            'id' => 'required',
        ]);

        Dasher::findOrFail($valid['id'])->delete();

        return redirect()->route('login');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'myfile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('dasher')->user();

        $filename = time() . '.' . $request->myfile->extension();
        $request->myfile->storeAs('public/images/profiles', $filename);

        $user->profile_photo = $filename;
        $user->save();

        return back()->with('success', 'Profile picture updated!');
    }


}
