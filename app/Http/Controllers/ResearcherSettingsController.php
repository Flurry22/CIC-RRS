<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResearcherSettingsController extends Controller
{
    /**
     * Display the settings form.
     */
    public function edit()
    {
        $researcher = Auth::guard('researcher')->user();

        $skills = $researcher->skills ? explode(',', $researcher->skills) : []; // Convert skills string to array

        return view('researcher.settings', compact('researcher', 'skills'));
    }

    /**
     * Update researcher profile details.
     */
    public function update(Request $request)
    {
        $researcher = Auth::guard('researcher')->user();
    
        // Validate incoming data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:researchers,email,' . $researcher->id,
            'skills' => 'nullable|array',
        ]);
    
        // Combine first and last name into a single name field
        $fullName = $validated['first_name'] . ' ' . $validated['last_name'];
    
        // Update researcher details with the combined name
        $researcher->update([
            'name' => $fullName,  // Store combined name in the 'name' column
            'email' => $validated['email'],
        ]);
    
        // Update or add skills
        if (isset($validated['skills'])) {
            $researcher->skills = implode(',', $validated['skills']);
            $researcher->save();
        }
    
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update researcher profile picture.
     */
    public function updateProfilePicture(Request $request)
    {
        $researcher = Auth::guard('researcher')->user();

        // Validate uploaded profile picture
        $validated = $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,jfif|max:2048',
        ]);

        // Store the profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        // Update the researcher's profile picture
        $researcher->profile_picture = $path;
        $researcher->save();

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    /**
     * Change researcher password.
     */
    public function changePassword(Request $request)
    {
        $researcher = Auth::guard('researcher')->user();

        // Validate password input
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password matches
        if (!Hash::check($validated['current_password'], $researcher->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $researcher->password = Hash::make($validated['new_password']);
        $researcher->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
