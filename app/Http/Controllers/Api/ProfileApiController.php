<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Dasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileApiController extends Controller
{

    // Update user profile information
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $filename = Str::uuid() . '.' . $request->photo->getClientOriginalExtension();

        $request->photo->storeAs('images/profiles', $filename, 'public');

        if ($user->profile_photo) {
            Storage::disk('public')->delete('images/profiles/' . $user->profile_photo);
        }

        $user->profile_photo = $filename;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile photo updated successfully.',
            'new_photo' => $filename,
            'photo_url' => asset('storage/images/profiles/' . $filename),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

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
    public function selfDeleteAccount(Request $request)
    {
        // Get logged-in user's ID
        $user_id = $request->user()->id;

        // Find the user record
        $user = Dasher::findOrFail($user_id);

        // Delete the user account permanently
        $user->delete();
    }
}