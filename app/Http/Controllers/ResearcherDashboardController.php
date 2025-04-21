<?php

namespace App\Http\Controllers;

use App\Models\Researcher;
use App\Models\Research;
use App\Models\ResearchFile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
    ->orderBy('created_at', 'desc') // Sort by latest creation date first
    ->orderBy('title', 'asc') // If dates are the same, sort alphabetically by title
    ->get();

$participatingResearches = $researcher->researches()
    ->wherePivot('role', 'member')
    ->orderBy('created_at', 'desc') // Sort by latest creation date first
    ->orderBy('title', 'asc') // If dates are the same, sort alphabetically by title
    ->get(); // Fetch all without limit

$allResearches = $researcher->researches()->latest()->get();

    // Calculate statistics
    $researchCount = $allResearches->count(); // Total researches
    $participatingCount = $allResearches->where('pivot.role', 'member')->count(); // Total researches as member
    
    // Get upcoming deadlines
    $upcomingDeadlines = $allResearches->filter(function ($research) {
        return strtotime($research->deadline) > time(); // Filter for upcoming deadlines
    });

    $finishedResearchCount = $allResearches->where('status', 'Finished')->count();
    $ongoingResearchCount = $allResearches->where('status', 'On-Going')->count();
    $finishedResearches = $researcher->researches()
    ->where('status', 'Finished')
    ->orderBy('date_completed', 'desc') // Sort by latest completion date first
    ->orderBy('title', 'asc') // If dates are the same, sort alphabetically by title
    ->get();
    $ongoingResearches = $researcher->researches()
    ->where('status', 'On-Going')
    ->orderBy('title', 'asc') // Sort alphabetically
    ->get();

  

    // Data for chart (Research status distribution)
    $chartData = [
        'labels' => ['Finished', 'Ongoing',],
        'counts' => [$finishedResearchCount, $ongoingResearchCount,],
    ];

    // Calculate the count of researches where the researcher is a leader
    $asLeaderCount = $researcher->researches()
    ->wherePivot('role', 'leader')
    ->count();

// Count researches where the researcher is a member
$asMemberCount = $researcher->researches()
    ->wherePivot('role', 'member')
    ->count();
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
        'finishedResearches',
        'ongoingResearches',
        'chartData',
        'asLeaderCount',  // Passed to the view
        'asMemberCount',
        'programs',   // Passed to the view
    ));
}
public function preview($id, Request $request)
{
    // Find the researcher by ID, including their researches
    $researcher = Researcher::with('researches')->findOrFail($id);

    // Get filters from the request
    $status = $request->input('status'); // Get the status filter from the request
    $role = $request->input('role'); // Get the role filter from the request
    $position = $researcher->position; // Assuming position exists in researcher model

    // Skills from the researcher
    $skills = explode(',', $researcher->skills);
    $years = $researcher->researches()
    ->distinct()
    ->pluck(\DB::raw('YEAR(approved_date) as year'))
    ->sortDesc(); 

    // Fetch filtered researches with status and role filters
    $researches = $researcher->researches()
        ->when($status && $status != 'all', function ($query) use ($status) {
            return $query->where('status', $status); // Filter by status
        })
        ->when($role && $role != 'all', function ($query) use ($role) {
            return $query->whereHas('researchers', function ($query) use ($role) {
                $query->where('role', $role); // Correct filter based on the 'role' in the pivot table
            });
        })
        ->when($request->has('year') && $request->year != '', function ($query) use ($request) {
            return $query->whereYear('approved_date', $request->year); // Filter by year
        })
        ->orderByDesc('approved_date') // Sort by approved_date descending
        ->orderBy('title') // Sort by title alphabetically
        ->orderByDesc('created_at') // Sort by created_at descending
        ->orderByDesc('date_completed') // Sort by completed_date descending (if needed)
        ->get(); // Fetch the filtered researches

    // Calculate research counts for the preview
    $ongoingCount = $researches->where('status', 'On-Going')->count();
    $finishedCount = $researches->where('status', 'Finished')->count();
    $totalCount = $researches->count();

    // Return the view with the necessary data
    return view('researcher.researcher_dashboard_report_preview', [
        'researcher' => $researcher,
        'ongoingCount' => $ongoingCount,
        'finishedCount' => $finishedCount,
        'totalCount' => $totalCount,
        'skills' => $skills,
        'researches' => $researches,
        'request' => $request,
        'position' => $position,
        'years' => $years,
    ]);
}
public function reportPdf($id, Request $request)
    {
        // Find the researcher by ID, including their researches
        $researcher = Researcher::with('researches')->findOrFail($id);

        // Get filters from the request
        $status = $request->input('status');
        $role = $request->input('role');
        $position = $researcher->position;

        // Skills from the researcher
        $skills = explode(',', $researcher->skills);
        $years = $researcher->researches()
            ->distinct()
            ->pluck(\DB::raw('YEAR(approved_date) as year'))
            ->sortDesc(); 

        // Fetch filtered researches with status and role filters
        $researches = $researcher->researches()
            ->when($status && $status != 'all', function ($query) use ($status) {
                return $query->where('status', $status); // Filter by status
            })
            ->when($role && $role != 'all', function ($query) use ($role) {
                return $query->whereHas('researchers', function ($query) use ($role) {
                    $query->where('role', $role); // Correct filter based on the 'role' in the pivot table
                });
            })
            ->when($request->has('year') && $request->year != '', function ($query) use ($request) {
                return $query->whereYear('approved_date', $request->year); // Filter by year
            })
            ->orderByDesc('approved_date')
            ->orderBy('title')
            ->orderByDesc('created_at')
            ->orderByDesc('date_completed')
            ->get();

        // Calculate research counts for the report
        $ongoingCount = $researches->where('status', 'On-Going')->count();
        $finishedCount = $researches->where('status', 'Finished')->count();
        $totalCount = $researches->count();

        // Load the view to generate the PDF (use your existing PDF-specific view)
        $pdf = Pdf::loadView('researcher.researcher_dashboard_report_pdf', [
            'researcher' => $researcher,
            'ongoingCount' => $ongoingCount,
            'finishedCount' => $finishedCount,
            'totalCount' => $totalCount,
            'skills' => $skills,
            'researches' => $researches,
            'request' => $request,
            'position' => $position,
            'years' => $years,
        ]) ->setPaper('a4', 'landscape'); 

        // Download the generated PDF
        return $pdf->download('Researcher Report - ' . $researcher->name . '.pdf');
    }

        
}
