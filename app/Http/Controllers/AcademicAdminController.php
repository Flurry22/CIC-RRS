<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Researcher;
use App\Models\Research;
use App\Models\ResearchStaff;
use App\Models\SchoolYear;
use App\Models\Program;
use App\Models\SDG;
use App\Models\DOST6P;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcademicAdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Retrieve filters
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

        
        // Fetch the counts
        $researchCounts = (clone $researchQuery)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN status = 'On-Going' AND deadline >= NOW() THEN 1 ELSE 0 END), 0) AS ongoing,
                COALESCE(SUM(CASE WHEN status = 'Finished' THEN 1 ELSE 0 END), 0) AS finished,
                COALESCE(SUM(CASE WHEN status != 'Finished' AND deadline < NOW() THEN 1 ELSE 0 END), 0) AS overdue
            ")->first();
        
        $ongoingCount = $researchCounts->ongoing ?? 0;
        $finishedCount = $researchCounts->finished ?? 0;
        $overdueCount = $researchCounts->overdue ?? 0;
        
        // Fetch the detailed data for ongoing, overdue, and completed researches
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
    
       
        
        
        // Get total researchers (default to 0)
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
        $researchersWithResearchCounts = Researcher::withCount('researches')->get();

        
        $researchersWithMostResearches = $researchersWithResearchCounts
            ->sortByDesc('researches_count')
            ->take(3);
    
        $researchersWithLeastResearches = $researchersWithResearchCounts
            ->sortBy('researches_count')
            ->take(3);
        
        // Fetch SDG research counts (ensure all SDGs appear)
        $sdgCounts = DB::table('sdgs')
            ->leftJoin('research_sdg', 'sdgs.id', '=', 'research_sdg.sdg_id')
            ->select('sdgs.name as sdg_name', DB::raw('COUNT(research_sdg.research_id) as research_count'))
            ->groupBy('sdgs.id', 'sdgs.name')
            ->orderBy('sdgs.name')
            ->get();
    
        $sdgNames = $sdgCounts->pluck('sdg_name')->toArray();
        $sdgResearches = $sdgCounts->pluck('research_count')->toArray();
        
        // Fetch DOST 6P research counts (ensure all appear)
        $dost6pCounts = DOST6P::withCount('researches')->orderBy('name')->get();
        $dost6pNames = $dost6pCounts->pluck('name')->toArray();
        $dost6pResearches = $dost6pCounts->pluck('researches_count')->toArray();
        
        // Fetch program research data (ensure all programs are counted)
        $programData = Program::leftJoin('program_research', 'programs.id', '=', 'program_research.program_id')
            ->leftJoin('researches', 'program_research.research_id', '=', 'researches.id')
            ->select('programs.name as program_name', 'programs.level', DB::raw('COUNT(researches.id) as total_researches'))
            ->groupBy('programs.id', 'programs.name', 'programs.level')
            ->orderBy('programs.name')
            ->get();
        
        // Ensure all programs are included
        $chartData = [];
        foreach ($programData as $program) {
            $level = ucfirst($program->level);
            if (!isset($chartData[$program->program_name])) {
                $chartData[$program->program_name] = ['Undergraduate' => 0, 'Graduate' => 0];
            }
            $chartData[$program->program_name][$level] += $program->total_researches;
        }
        
        return view('academic_administrator.dashboard', compact(
            'totalResearchers',
            'ongoingCount',
            'overdueCount',
            'finishedCount',
            'schoolYears',
            'schoolYearFilter',
            'semesterFilter',
            'researchersWithMostResearches',
            'researchersWithLeastResearches',
            'ongoingResearches',
            'overdueResearches',
            'completedResearches',
            'sdgNames',
            'sdgResearches',
            'dost6pNames',
            'dost6pResearches',
            'chartData',
            'totalResearchersDetails',
            'schoolYear' ,
        ));
    }
    

    public function updateCredentials(Request $request)
    {
        // Get the logged-in academic administrator
        $admin = auth()->guard('academic_administrator')->user();

        // Validate request data
        $validatedData = $request->validate([
            'email' => "required|email|unique:academic_administrators,email,{$admin->id}",
            'password' => 'required|min:8|confirmed',
        ]);

        // Update and save credentials
        $admin->update([
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->back()->with('success', 'Credentials updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:research_staff,email',
            'password' => 'required|min:8',
        ]);

        ResearchStaff::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('academic_administrator.dashboard')->with('success', 'Staff account created successfully!');
    }
}
