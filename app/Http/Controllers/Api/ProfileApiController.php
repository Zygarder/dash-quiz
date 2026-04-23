<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
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
    public function uploadPhoto(Request $request)
    {
        // 1. Validate file
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Auth user
        $user = Auth::guard('dasher')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated user.'
            ], 401);
        }

        // 3. Generate filename
        $filename = Str::uuid() . '.' . $request->photo->getClientOriginalExtension();

        // 4. Store file
        $request->photo->storeAs(
            'images/profiles',
            $filename,
            'public'
        );

        // 5. Delete old photo safely
        if ($user->profile_photo) {
            \Storage::disk('public')->delete('images/profiles/' . $user->profile_photo);
        }

        // 6. Save ONLY filename in DB (clean architecture)
        $user->profile_photo = $filename;
        $user->save();

        // 7. Build consistent URL
        $photoUrl = asset('storage/images/profiles/' . $filename);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile photo updated successfully',
            'new_photo' => $filename,
            'photo_url' => $photoUrl
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('dasher')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:dasher,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ], [
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        // Only hash & update password if it was provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'first_name' => $user->fresh()->first_name,
                'last_name' => $user->fresh()->last_name,
                'email' => $user->fresh()->email,
            ],
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