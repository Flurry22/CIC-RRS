<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\College;

class ProfileSetupController extends Controller
{
    // Display the profile setup form
    public function showProfileSetupForm()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $colleges = College::all(); // Get all colleges

        return view('profile_setup', compact('user', 'colleges')); // Pass user and colleges to the view
    }

    // Update user profile (including first-time profile picture upload)
    public function update(Request $request)
    {
        $this->validateProfileData($request);

        $user = Auth::user();

        // Handle profile picture upload (first-time setup)
        if ($request->hasFile('profile_picture')) {
            $this->handleProfilePictureUpload($request, $user);
        }

        // Update user profile fields
        $this->updateUserProfile($user, $request);

        // Redirect back to dashboard with success message
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }

    // Validate profile data
    protected function validateProfileData(Request $request)
    {
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,jfif|max:2048',
            'bio' => 'nullable|string',
            'college' => 'nullable|exists:colleges,id',
            'role' => 'nullable|string',
        ]);
    }

    // Handle profile picture upload
    protected function handleProfilePictureUpload(Request $request, $user)
    {
        // Delete the old profile picture if it exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store the new profile picture
        $profileImagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $profileImagePath;
        $user->save(); // Save the updated profile picture path
    }

    // Update user profile fields
    protected function updateUserProfile($user, Request $request)
    {
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->bio = $request->input('bio');
        $user->college_id = $request->input('college');
        $user->role = $request->input('role');
        $user->save();
    }
}
