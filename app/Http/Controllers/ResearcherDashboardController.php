<?php

namespace App\Http\Controllers;

use App\Models\Researcher;
use App\Models\Research;
use App\Models\ResearchFile;

class ResearcherDashboardController extends Controller
{
    /**
     * Display the researcher's dashboard.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
{
 
   
    $researcher = Researcher::with('researches','programs')->findOrFail($id);


    $skills = explode(',', $researcher->skills);


    $leadingResearches = $researcher->researches()
    ->wherePivot('role', 'leader')
    ->latest() // Order by created_at descending
    ->take(3)  // Limit to 3 records
    ->get();


    $participatingResearches = $researcher->researches()
    ->wherePivot('role', 'member')
    ->latest() // Order by created_at descending
    ->take(3)  // Limit to 3 records
    ->get();

  
    $allResearches = $leadingResearches->merge($participatingResearches);

    // Calculate statistics
    $researchCount = $leadingResearches->count() + $participatingResearches->count(); // Total researches as leader + participant
    $participatingCount = $participatingResearches->count(); // Total researches as member
    $upcomingDeadlines = $allResearches->filter(function ($research) {
        return strtotime($research->deadline) > time(); // Filter for upcoming deadlines
    });

    $finishedResearchCount = $allResearches->where('status', 'Finished')->count();
    $ongoingResearchCount = $allResearches->where('status', 'On-Going')->count();
  

    // Data for chart (Research status distribution)
    $chartData = [
        'labels' => ['Finished', 'Ongoing',],
        'counts' => [$finishedResearchCount, $ongoingResearchCount,],
    ];

    // Calculate the count of researches where the researcher is a leader
    $asLeaderCount = $leadingResearches->count(); // Count of researches where the researcher is a leader

    // Calculate the count of researches where the researcher is a member
    $asMemberCount = $participatingResearches->count();
    $programs = $researcher->programs;

    // Return the data to the view
    return view('researcher.dashboard', compact(
        'researcher',
        'skills',
        'leadingResearches',
        'participatingResearches',
        'researchCount',
        'participatingCount',
        'upcomingDeadlines',
        'finishedResearchCount',
        'ongoingResearchCount',
        'chartData',
        'asLeaderCount',  // Passed to the view
        'asMemberCount',
        'programs',   // Passed to the view
    ));
}





        
}
