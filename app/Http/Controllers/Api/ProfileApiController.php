<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileApiController extends Controller
{
    // Get current user profile
    public function getProfile()
    {
        $user = Auth::guard('dasher')->user();
        $quizzes_count = QuizRecord::where('user_id', $user->id)->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'profile_photo' => $user->profile_photo ? '/storage/images/profiles/' . $user->profile_photo : null,
                'created_at' => $user->created_at->format('F j, Y'),
                'quizzes_taken' => $quizzes_count
            ]
        ]);
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('dasher')->user();

        $valid = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:dasher|max:255',
            'password' => 'nullable|string|confirmed|min:6',
        ]);

        $user->first_name = $valid['first_name'];
        $user->last_name = $valid['last_name'] ?? '';
        $user->email = $valid['email'];
        $user->password = Hash::make($valid['password']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $valid = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::guard('dasher')->user();

        if (!Hash::check($valid['current_password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your current password is incorrect'
            ], 422);
        }

        $user->password = Hash::make($valid['new_password']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }

    // Upload profile photo
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('dasher')->user();

        $filename = uniqid() . '.' . $request->photo->extension();
        $request->photo->storeAs('public/images/profiles', $filename);

        $user->profile_photo = $filename;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile photo updated successfully',
            'photo_url' => asset('storage/public/images/profiles/' . $filename),
            'new_photo' => $filename
        ]);
    }
}
