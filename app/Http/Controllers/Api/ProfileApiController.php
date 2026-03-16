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
    // Get the currently authenticated user's profile
    public function getProfile()
    {
        // Get logged-in user using the "dasher" authentication guard
        $user = Auth::guard('dasher')->user();

        // Count how many quizzes the user has taken
        // This is used for profile statistics
        $quizzes_count = QuizRecord::where('user_id', $user->id)->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,

                // Create a full name field for easier display in frontend
                'full_name' => $user->first_name . ' ' . $user->last_name,

                'email' => $user->email,

                // Return profile image path if it exists
                // otherwise return null so frontend knows there is no image
                'profile_photo' => $user->profile_photo
                    ? '/storage/images/profiles/' . $user->profile_photo
                    : null,

                // Format registration date to a readable format
                'created_at' => $user->created_at->format('F j, Y'),

                // Total quizzes taken by the user
                'quizzes_taken' => $quizzes_count
            ]
        ]);
    }

    // Update user profile information
    public function updateProfile(Request $request)
    {
        // Get the authenticated user
        $user = Auth::guard('dasher')->user();

        // Validate incoming profile data
        $valid = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',

            // Email must be unique in the dasher table
            'email' => 'required|email|unique:dasher|max:255',

            // Password is optional but must be confirmed if provided
            'password' => 'nullable|string|confirmed|min:6',
        ]);

        // Update user fields only if new values are provided
        $user->first_name = $valid['first_name'] ?? $user->first_name;
        $user->last_name = $valid['last_name'] ?? $user->last_name;
        $user->email = $valid['email'];

        // If password is provided, hash it before saving
        // Hashing protects the password in the database
        $user->password = Hash::make($valid['password']) ?? $user->password;

        // Save updated user information
        $user->save();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    // Upload or update the user's profile photo
    public function uploadPhoto(Request $request)
    {
        // Validate uploaded file
        // Only allow image formats and limit file size
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Get the logged-in user
        $user = Auth::guard('dasher')->user();

        // Generate a unique filename to prevent conflicts
        $filename = uniqid() . '.' . $request->photo->extension();

        // Store image inside the public storage directory
        // This allows the image to be accessible via URL
        $request->photo->storeAs('public/images/profiles', $filename);

        // Save the filename to the user's profile
        $user->profile_photo = $filename;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile photo updated successfully',

            // Return the image URL for immediate frontend display
            'photo_url' => asset('storage/public/images/profiles/' . $filename),

            'new_photo' => $filename
        ]);
    }

    // Allow users to delete their own account
    public function selfDeleteAccount()
    {
        // Get logged-in user's ID
        $user_id = Auth::guard('dasher')->user()->id;

        // Find the user record
        $user = Dasher::findOrFail($user_id);

        // Delete the user account permanently
        $user->delete();
    }
}