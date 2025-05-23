<?php
namespace App\Http\Controllers;

use App\Models\Researcher; // Import the Researcher model
use Illuminate\Http\Request;

class ResearcherProfileController extends Controller
{
    public function show($id)
    {
        // Find the researcher by their ID with their researches (Eager Loading)
        $researcher = Researcher::with(['researches' => function ($query) {
            $query->orderBy('approved_date', 'desc')  // Sort by most recent approved date
                  ->orderBy('title', 'asc')            // Sort alphabetically by title
                  ->orderBy('created_at', 'desc');     // Finally, sort by creation date
        }])->findOrFail($id);

        // Parse skills as an array (if applicable)
        $skills = explode(',', $researcher->skills);

        $researchCount = $researcher->researches->count(); 

       
        $finishedResearchCount = $researcher->researches->where('status', 'Finished')->count(); // Finished researches
        $ongoingResearchCount = $researcher->researches->where('status', 'On-Going')->count(); // Ongoing researches

        // Return the profile view with the necessary data
        return view('researcher.researcher_profile', compact(
            'researcher', 
            'skills', 
            'researchCount', 
            'finishedResearchCount', 
            'ongoingResearchCount'
        ));
    }
}
