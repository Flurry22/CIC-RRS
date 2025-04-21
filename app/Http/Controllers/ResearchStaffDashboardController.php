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
        $researches = Research::all();
        // Get filters from the request
        $schoolYearFilter = $request->input('school_year');
        $semesterFilter = $request->input('semester');
        
        // Fetch all school years in descending order of 'off_sem_end'
        $schoolYears = SchoolYear::orderByDesc('off_sem_end')->get();
        $schoolYear = null;
        $dateRange = null;
    
        // Initialize research and researcher queries
        $researchQuery = Research::query();
        $researcherQuery = Researcher::query();
    
        // Check if the school year filter is set
        if ($schoolYearFilter) {
            // Try to fetch the school year based on the filter
            $schoolYear = SchoolYear::where('school_year', $schoolYearFilter)->first();
    
            // If no school year is found, log or handle the error (or provide a fallback)
            if (!$schoolYear) {
                dd("No school year found for: {$schoolYearFilter}"); // Debugging line
            }
    
            // If a valid school year is found, apply filtering to the research query
            if ($schoolYear) {
                // Restrict researchers to those with research within the selected school year
                $researcherQuery->whereHas('researches', function ($query) use ($schoolYear) {
                    $query->where(function ($q) use ($schoolYear) {
                        $q->whereBetween('approved_date', [$schoolYear->first_sem_start, $schoolYear->off_sem_end])
                          ->orWhere(function ($q) use ($schoolYear) {
                              // Filter for ongoing research (no completion date)
                              $q->whereNull('date_completed')
                                ->where('approved_date', '<=', $schoolYear->off_sem_end);
                          })
                          ->orWhereBetween('date_completed', [$schoolYear->first_sem_start, $schoolYear->off_sem_end]);
                    });
                });
    
                // Apply filtering based on the semester if provided
                if ($semesterFilter) {
                    $dateRange = match ($semesterFilter) {
                        'First Semester' => [$schoolYear->first_sem_start, $schoolYear->first_sem_end],
                        'Second Semester' => [$schoolYear->second_sem_start, $schoolYear->second_sem_end],
                        'Off Semester' => [$schoolYear->off_sem_start, $schoolYear->off_sem_end],
                        default => null,
                    };
    
                    // Apply the date range filter if a valid range is found
                    if ($dateRange) {
                        $researchQuery->whereBetween('approved_date', $dateRange);
                    }
                }
            }
        }

        // Count ongoing, finished, and overdue researches based on filtered query
        $researchCounts = (clone $researchQuery)
        ->selectRaw("
            COALESCE(SUM(CASE WHEN status = 'On-Going' AND deadline >= NOW() THEN 1 ELSE 0 END), 0) AS ongoing,
            COALESCE(SUM(CASE WHEN status = 'Finished' THEN 1 ELSE 0 END), 0) AS finished,
            COALESCE(SUM(CASE WHEN status != 'Finished' AND deadline < NOW() THEN 1 ELSE 0 END), 0) AS overdue
        ")->first();
    
        $totalResearches = $researchQuery->count();

    $ongoingResearches = $researchQuery->where('status', 'On-Going')
        ->where('deadline', '>=', now())  // Deadline is in the future or today
        ->when($dateRange, function ($query) use ($dateRange) {
            return $query->whereBetween('approved_date', $dateRange);
        })
        ->with(['researchers' => function ($query) {
            $query->wherePivot('role', 'leader'); // Filter to get only researchers with 'leader' role
        }])
        // Sorting by the latest approved_date (if available), otherwise by created_at
        ->orderByRaw('COALESCE(approved_date, created_at) DESC')  // Sort by approved_date or created_at (fallback)
        ->orderBy('title', 'asc')  // Sort alphabetically by title
        ->get();
    
    $overdueResearches = \App\Models\Research::where('status', 'On-Going')
        ->where('deadline', '<', now()) // Ensure the deadline has passed
        ->when($dateRange, function ($query) use ($dateRange) {
            return $query->whereBetween('approved_date', $dateRange);
        })
        ->whereNull('date_completed') // Ensure it's not completed
        ->with(['researchers' => function ($query) {
            $query->wherePivot('role', 'leader'); // Fetch only the 'leader' role
        }])
        // Sorting by the latest approved_date (if available), otherwise by created_at
        ->orderByRaw('COALESCE(approved_date, created_at) DESC')  // Sort by approved_date or created_at (fallback)
        ->orderBy('title', 'asc')  // Sort alphabetically by title
        ->get();
    
    $completedResearches = \App\Models\Research::where('status', 'Finished')
        ->whereNotNull('date_completed')
        ->when($dateRange, function ($query) use ($dateRange) {
            return $query->whereBetween('approved_date', $dateRange);
        })
        ->with(['researchers' => function ($query) {
            $query->wherePivot('role', 'leader'); // Fetch only the 'leader' role
        }])
        // Sorting by the latest approved_date (if available), otherwise by created_at
        ->orderByRaw('COALESCE(approved_date, created_at) DESC')  // Sort by approved_date or created_at (fallback)
        ->orderBy('title', 'asc')  // Sort alphabetically by title
        ->get();

        $ongoingCount = $ongoingResearches->count();
        $finishedCount = $completedResearches->count();
    $overdueCount = $researchCounts->overdue ?? 0;
    $researchStatusChartData = [
        'ongoing' => $ongoingCount,
        'finished' => $finishedCount,
    ];
        
        // Total researches based on filters
       

        // Total researchers restricted by school year
        $totalResearchers = $researcherQuery->count();
        $suffixes = "'Jr.', 'Sr.', 'II', 'III', 'IV', 'V'";
    
        $totalResearchersDetails = $researcherQuery->withCount(['researches' => function ($query) use ($schoolYear) {
            if ($schoolYear) {
                $query->where(function ($q) use ($schoolYear) {
                    // Only count researches within the filtered school year
                    $q->whereBetween('approved_date', [$schoolYear->first_sem_start, $schoolYear->off_sem_end])
                      ->orWhere(function ($q) use ($schoolYear) {
                          $q->whereNull('date_completed')
                            ->where('approved_date', '<=', $schoolYear->off_sem_end);
                      })
                      ->orWhereBetween('date_completed', [$schoolYear->first_sem_start, $schoolYear->off_sem_end]);
                });
            }
        }])
        ->orderByRaw("
                TRIM(
                    CASE 
                        WHEN name REGEXP ' (Jr\\.|Sr\\.|II|III|IV|V)$' 
                        THEN SUBSTRING_INDEX(SUBSTRING_INDEX(name, ' ', -2), ' ', 1)
                        ELSE SUBSTRING_INDEX(name, ' ', -1) 
                    END
                ) ASC
            ")->get();
            $totalResearchers = $researcherQuery->count();

            // Count active/inactive researchers based on the selected school year and research data
            $activeResearchersCount = 0;
            $inactiveResearchersCount = 0;
            
            if ($schoolYear) {
                // Count active researchers within the selected school year based on associated research
                $activeResearchersCount = Researcher::where('researcher_status', 'Active')
                    ->whereHas('researches', function ($query) use ($schoolYear) {
                        $query->whereBetween('approved_date', [$schoolYear->first_sem_start, $schoolYear->off_sem_end])
                              ->orWhere(function ($q) use ($schoolYear) {
                                  $q->whereNull('date_completed')
                                    ->where('approved_date', '<=', $schoolYear->off_sem_end);
                              })
                              ->orWhereBetween('date_completed', [$schoolYear->first_sem_start, $schoolYear->off_sem_end]);
                    })->count();
        
                // Count inactive researchers within the selected school year based on associated research
                $inactiveResearchersCount = Researcher::where('researcher_status', 'Inactive')
                    ->whereHas('researches', function ($query) use ($schoolYear) {
                        $query->whereBetween('approved_date', [$schoolYear->first_sem_start, $schoolYear->off_sem_end])
                              ->orWhere(function ($q) use ($schoolYear) {
                                  $q->whereNull('date_completed')
                                    ->where('approved_date', '<=', $schoolYear->off_sem_end);
                              })
                              ->orWhereBetween('date_completed', [$schoolYear->first_sem_start, $schoolYear->off_sem_end]);
                    })->count();
            } else {
                // If no school year is selected, count all active/inactive researchers globally based on research
                $activeResearchersCount = Researcher::where('researcher_status', 'Active')
                    ->whereHas('researches')
                    ->count();
        
                $inactiveResearchersCount = Researcher::where('researcher_status', 'Inactive')
                    ->whereHas('researches')
                    ->count();
            }
        
            // Ensure the counts do not exceed the total researchers and are non-negative
            $activeResearchersCount = max(0, min($activeResearchersCount, $totalResearchers));
            $inactiveResearchersCount = max(0, min($inactiveResearchersCount, $totalResearchers));
        
            // Prepare chart data (for example)
            $researcherActivityChartData = [
                'active' => $activeResearchersCount,
                'inactive' => $inactiveResearchersCount,
            ];
        
        // Format events for FullCalendar
        $events = $researches->map(function ($research) {
            // Check if the research has a valid deadline
            $startDate = $research->deadline ? Carbon::parse($research->deadline)->toDateString() : null;
        
            return [
                'title' => $research->title,
                'start' => $startDate, // Set the start date for the event
                'leader' => optional($research->leader)->name ?? 'No Leader', // Set the leader's name (or default to 'No Leader')
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
            'totalResearchersDetails',
            'ongoingResearches',
            'overdueResearches',
            'completedResearches',
            'events',
            'researchStatusChartData',
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
