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

        // Fetch all school years
        $schoolYears = SchoolYear::all();

        // Initialize research query
        $researchQuery = Research::query();

        // Apply school year filter if provided
        if ($schoolYearFilter) {
            $schoolYear = SchoolYear::where('school_year', $schoolYearFilter)->first();

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

        // Clone query for counts to avoid query overwrites
        $ongoingCount = (clone $researchQuery)->where('status', 'On-Going')->where('deadline', '>=', now())->count();
        $finishedCount = (clone $researchQuery)->where('status', 'Finished')->count();
        $overdueCount = (clone $researchQuery)
            ->where('status', '!=', 'Finished')
            ->where('status', 'On-Going')
            ->where('deadline', '<', now())
            ->count();

        // Get total research count
        $totalResearches = $researchQuery->count();

        // Get total researchers
        $totalResearchers = Researcher::count();

        // Fetch researchers with research counts
        $researchersWithResearchCounts = Researcher::withCount('researches')->get();

        // Get top and least active researchers (limit to 3)
        $researchersWithMostResearches = $researchersWithResearchCounts->sortByDesc('researches_count')->take(3);
        $researchersWithLeastResearches = $researchersWithResearchCounts->sortBy('researches_count')->take(3);

        // Fetch program research data
        $programData = Program::leftJoin('program_research', 'programs.id', '=', 'program_research.program_id')
            ->leftJoin('researches', 'program_research.research_id', '=', 'researches.id')
            ->select('programs.name as program_name', 'programs.level', DB::raw('COUNT(researches.id) as total_researches'))
            ->groupBy('programs.id', 'programs.name', 'programs.level')
            ->orderBy('programs.name')
            ->get();

        // Prepare program chart data
        $chartData = [];
        foreach ($programData as $program) {
            $level = ucfirst($program->level);

            if (!isset($chartData[$program->program_name])) {
                $chartData[$program->program_name] = ['Undergraduate' => 0, 'Graduate' => 0];
            }

            $chartData[$program->program_name][$level] += $program->total_researches;
        }

        // Fetch SDG research counts
        $sdgCounts = DB::table('research_sdg')
            ->join('sdgs', 'research_sdg.sdg_id', '=', 'sdgs.id')
            ->select('sdgs.name as sdg_name', DB::raw('COUNT(research_sdg.research_id) as research_count'))
            ->groupBy('sdgs.id', 'sdgs.name')
            ->orderBy('sdgs.name')
            ->get();

        // Prepare SDG chart data
        $sdgNames = $sdgCounts->pluck('sdg_name')->toArray();
        $sdgResearches = $sdgCounts->pluck('research_count')->toArray();

        // Fetch DOST 6P research counts
        $dost6pCounts = DOST6P::withCount('researches')->orderBy('name')->get();
        $dost6pNames = $dost6pCounts->pluck('name')->toArray();
        $dost6pResearches = $dost6pCounts->pluck('researches_count')->toArray();

        // Return view with all data
        return view('academic_administrator.dashboard', compact(
            'totalResearchers',
            'ongoingCount',
            'overdueCount',
            'finishedCount',
            'researchersWithMostResearches',
            'researchersWithLeastResearches',
            'schoolYears',
            'schoolYearFilter',
            'semesterFilter',
            'sdgNames',
            'sdgResearches',
            'dost6pNames',
            'dost6pResearches',
            'chartData',
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
