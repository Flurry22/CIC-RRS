<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research;
use App\Models\Researcher;
use App\Models\SchoolYear;
use App\Models\ResearchStaff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResearchStaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filters from the request
        $schoolYearFilter = $request->input('school_year');
        $semesterFilter = $request->input('semester');
        
        // Fetch all school years for filter options
        $schoolYears = SchoolYear::all();
        
        // Initialize the query for Research
        $researchQuery = Research::query();
        
        // Apply school year filter
        if ($schoolYearFilter) {
            $schoolYear = SchoolYear::where('school_year', $schoolYearFilter)->first();
            if ($schoolYear) {
                $researchQuery->where('school_year_id', $schoolYear->id);
                
                // Apply semester date range filter
                if ($semesterFilter) {
                    $dateRange = match ($semesterFilter) {
                        'First Semester' => [$schoolYear->first_sem_start, $schoolYear->first_sem_end],
                        'Second Semester' => [$schoolYear->second_sem_start, $schoolYear->second_sem_end],
                        'Off Semester' => [$schoolYear->off_sem_start, $schoolYear->off_sem_end],
                        default => null,
                    };
                    
                    if ($dateRange) {
                        $researchQuery->whereBetween('approved_date', $dateRange);
                    }
                }
            }
        }

        // Count ongoing, finished, and overdue researches based on filtered query
        $ongoingCount = (clone $researchQuery)->where('status', 'On-Going')->where('deadline', '>=', now())->count();
        $finishedCount = (clone $researchQuery)->where('status', 'Finished')->count();
        $overdueCount = (clone $researchQuery)->where('status', 'On-Going')->where('deadline', '<', now())->count();
        
        // Calculate total researches based on the same filters
        $totalResearches = $researchQuery->count();

        // Total researchers
        $totalResearchers = Researcher::count();
        
        // Count active researchers based on filtered research
        $activeResearchersCount = Researcher::whereHas('researches', function($query) use ($researchQuery) {
            $query->whereIn('researches.id', $researchQuery->pluck('id')); 
        })->count();
        

        // Calculate inactive researchers
        $inactiveResearchersCount = $totalResearchers - $activeResearchersCount;
        
        // Prepare chart data for active and inactive researchers
        $researcherActivityChartData = [
            'active' => $activeResearchersCount,
            'inactive' => $inactiveResearchersCount,
        ];

        // Format events for FullCalendar
        $events = $researchQuery->get()->map(function ($research) {
            return [
                'title' => $research->title,
                'start' => Carbon::parse($research->deadline)->toDateString(),
                'leader' => optional($research->leader)->name ?? 'No Leader',
            ];
        });

        // Return the data to the view
        return view('research_staff.dashboard', compact(
            'schoolYears',
            'schoolYearFilter',
            'semesterFilter',
            'ongoingCount',
            'finishedCount',
            'overdueCount',
            'totalResearchers',
            'totalResearches',
            'researcherActivityChartData',
            'events'
        ));
    }

    public function updateCredentials(Request $request)
    {
        // Ensure the user is logged in using the 'research_staff' guard
        $staff = auth()->guard('research_staff')->user();

        // Validate the incoming data
        $validatedData = $request->validate([
            'email' => 'required|email|unique:research_staff,email,' . $staff->id,
            'password' => 'required|min:8|confirmed',
        ]);

        // Update the staff's email and password
        $staff->email = $validatedData['email'];
        $staff->password = Hash::make($validatedData['password']);
        $staff->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Credentials updated successfully.');
    }
}

