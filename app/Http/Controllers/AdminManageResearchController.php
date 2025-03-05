<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\Researcher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminManageResearchController extends Controller
{
    /**
     * Display the list of research projects with related data, categorized by program type.
     */
    public function index(Request $request)
    {
        $researchers = Researcher::all();
    
        // Fetch status filter from the request
        $statusFilter = $request->input('status');
    
        // Fetch researches with their related leader, programs (many-to-many), and funding type
        $researches = Research::with(['leader', 'programs', 'fundingType']);
    
        // Apply status filtering logic
        if ($statusFilter) {
            switch ($statusFilter) {
                case 'on-going':
                    $researches = $researches->where('status', 'On-Going')
                        ->where('deadline', '>=', now()); // Ongoing and not past the deadline
                    break;
    
                case 'finished':
                    $researches = $researches->where('status', 'Finished'); // Only finished projects
                    break;
    
                case 'overdue':
                    $researches = $researches->where('status', '!=', 'Finished')
                        ->where(function ($query) {
                            $query->where('status', 'On-Going')->where('deadline', '<', now())
                                ->orWhere('status', '!=', 'On-Going');
                        });
                    break;
            }
        }
    
        // Get the filtered results
        $researches = $researches->get();
    
        // Categorize researches based on program level
        $undergraduateResearches = $researches->filter(function ($research) {
            return $research->programs->contains('level', 'undergraduate'); // ✅ Uses many-to-many correctly
        });
    
        $graduateResearches = $researches->filter(function ($research) {
            return $research->programs->contains('level', 'graduate'); // ✅ Uses many-to-many correctly
        });
    
        // Count research types for analytics
        $researchTypeCounts = [
            'Program' => $researches->where('type', 'program')->count(),
            'Project' => $researches->where('type', 'project')->count(),
            'Study' => $researches->where('type', 'study')->count(),
        ];
    
        // Prepare research events for the calendar
        $events = $researches->map(function ($research) {
            $leaderName = $research->leader ? $research->leader->name : 'No Leader';
        
            return [
                'title' => $research->title,
                'start' => Carbon::parse($research->deadline)->toDateString(),
                'leader' => $leaderName,
            ];
        });
    
        // Return the view with updated data
        return view('academic_administrator.manage_research', compact(
            'undergraduateResearches',
            'graduateResearches',
            'researchTypeCounts',
            'researchers',
            'events',
            'statusFilter',
        ));
    }
    

    /**
     * Update the leader of a research project.
     */
    public function updateLeader(Request $request, $researchId)
    {
        // Validate the leader selection
        $request->validate([
            'leader_id' => 'required|exists:researchers,id',  // Ensure the leader exists
        ]);
    
        // Find the research project by ID
        $research = Research::findOrFail($researchId);
    
        // Update the leader of the research project
        $research->leader_id = $request->leader_id;
    
        // Check if save was successful
        if ($research->save()) {
            return redirect()->route('academic_administrator.manage_research')->with('success', 'Leader updated successfully!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to update leader.']);
        }
    }
    
}
