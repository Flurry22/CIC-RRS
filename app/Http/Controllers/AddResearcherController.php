<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Researcher;
use App\Models\Program;


class AddResearcherController extends Controller
{
    /**
     * Show the form for creating a new researcher.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Fetch all programs to display in the form
        $programs = Program::all(); // Assuming you have a Program model

        return view('research_staff.researcher_components.addresearcher', compact('programs'));
    }

    /**
     * Store a newly created researcher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:researchers',
            'password' => 'required|string|min:8',
            'profile_picture' => 'nullable|image|max:2048',
            'program_ids' => 'nullable|array', // Validate program_ids as an optional array
            'program_ids.*' => 'exists:programs,id', // Ensure each ID exists in the programs table
        ]);

        // Concatenate first name and last name to form the full name
        $fullName = $request->input('first_name') . ' ' . $request->input('last_name');

        // Handle file upload if provided
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Create the new researcher
        $researcher = new Researcher();
        $researcher->name = $fullName;  // Store the full name in the name field
        $researcher->position = $request->input('position');
        $researcher->email = $request->input('email');
        $researcher->password = Hash::make($request->input('password'));
        $researcher->profile_picture = $profilePicturePath;
        $researcher->save();

        // Attach programs if provided
        if ($request->has('program_ids')) {
            $researcher->programs()->attach($request->input('program_ids'));
        }

        // Redirect with success message
        return redirect()->route('researchers.index')->with('success', 'Researcher added successfully!');
    }
}
