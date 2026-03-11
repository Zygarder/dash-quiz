<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizRecord;
use App\Models\Dasher;
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
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:dasher|max:255',
            'password' => 'nullable|string|confirmed|min:6',
        ]);

        $user->first_name = $valid['first_name'] ?? $user->first_name;
        $user->last_name = $valid['last_name'] ?? $user->last_name;
        $user->email = $valid['email'];
        $user->password = Hash::make($valid['password']) ?? $user->password;
        $user->save();

        return response()->json([
            'status' => 'success',
            'data' => $user
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

    public function selfDeleteAccount()
    {
        $user_id = Auth::guard('dasher')->user()->id;

        $user = Dasher::findOrFail($user_id);

        $user->delete();
    }
}
