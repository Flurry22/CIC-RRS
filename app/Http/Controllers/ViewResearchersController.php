<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Researcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Program;

class ViewResearchersController extends Controller
{
    // Display the list of researchers
    public function index(Request $request)
{
    $programs = Program::all(); // All programs for the modal checkboxes
    $search = $request->input('search'); // Search query

    // Paginate researchers, optionally filtering by search query
    $researchers = Researcher::query()
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        })
        ->orderBy('id')
        ->paginate(9);

    return view('research_staff.researcher_components.viewresearchers', compact('researchers', 'programs'));
} 

public function edit($id)
{
    $researcher = Researcher::with('programs')->findOrFail($id);
    $programs = Program::all(); 
    // Fetch all programs

    return response()->json([
        'first_name' => explode(' ', $researcher->name)[0], // Get first name
        'last_name' => explode(' ', $researcher->name)[1] ?? '', // Get last name
        'position' => $researcher->position,
        'email' => $researcher->email,
        'programs' => $researcher->programs->pluck('id')->toArray(), // Get selected program IDs
    ]);
}

    // Show the form for editing a specific researcher
    public function update(Request $request, $id)
{
    // Validate the incoming request data
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:researchers,email,' . $id, // Ensure unique email except for current researcher
        'password' => 'nullable|string|min:8',
        'profile_picture' => 'nullable|image|max:2048',
        'program_ids' => 'nullable|array', // Validate program_ids as an optional array
        'program_ids.*' => 'exists:programs,id', // Ensure each ID exists in the programs table
    ]);

    // Find the researcher by ID
    $researcher = Researcher::findOrFail($id);

    // Concatenate first name and last name to form the full name
    $fullName = $request->input('first_name') . ' ' . $request->input('last_name');

    // Handle file upload if provided
    if ($request->hasFile('profile_picture')) {
        // Store the new profile picture
        $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');

        // Optionally delete the old profile picture if it exists
        if ($researcher->profile_picture) {
            Storage::disk('public')->delete($researcher->profile_picture);
        }

        // Update the researcher's profile picture path
        $researcher->profile_picture = $profilePicturePath;
    }

    // Update the researcher's details
    $researcher->name = $fullName;  // Store the full name in the name field
    $researcher->position = $request->input('position');
    $researcher->email = $request->input('email');
    
    // Update password only if provided
    if ($request->filled('password')) {
        $researcher->password = Hash::make($request->input('password'));
    }

    // Save updated researcher details
    $researcher->save();

    // Sync selected programs with the researcher
    if ($request->has('program_ids')) {
        $researcher->programs()->sync($request->input('program_ids')); // Use sync to update associations
    } else {
        // If no programs are selected, detach all (optional)
        $researcher->programs()->detach();
    }

    return redirect()->route('researchers.index')->with('success', 'Researcher updated successfully!');
}


    // Delete a researcher
    public function destroy($id)
    {
        $researcher = Researcher::findOrFail($id);
        
        // Optionally detach associated programs before deleting
        if ($researcher->programs()->count()) {
            $researcher->programs()->detach();
        }
        
        $researcher->delete();

        return redirect()->route('researchers.index')->with('success', 'Researcher deleted successfully!');
    }
}
