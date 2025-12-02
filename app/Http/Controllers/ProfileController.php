<?php

namespace App\Http\Controllers;

use App\Models\QuizRecord;
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
    public function updatePassword(Request $request)
    {
        // Validate input
        $valid = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::guard('dasher')->user();

        // Check if old password matches
        if (!Hash::check($valid['current_password'], $user->password)) {
            return redirect()->back()->with('error', 'Your current password is incorrect.');
        }

        // Save new password
        $user->password = Hash::make($valid['new_password']);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
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
