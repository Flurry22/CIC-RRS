<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Research;
use App\Models\Researcher;
use App\Models\Program;
use App\Models\SchoolYear;
use App\Exports\ResearchesExport;
use App\Exports\ResearchesPerResearcherExport;
use Maatwebsite\Excel\Facades\Excel;

class ResearchReportController extends Controller
{
    /**
     * Show research report creation page
     */
    public function create()
    {
        $researchers = Researcher::all();
        $programs = Program::all();
        $schoolYears = SchoolYear::all();

        return view('research_staff.research_report_create', compact('researchers', 'programs', 'schoolYears'));
    }

    /**
     * Show the research report preview page
     */
    public function preview(Request $request)
{
    $viewVersion = $request->input('view_version');

    if ($viewVersion === 'researcher') {
        $researchers = $this->filteredResearcherQuery($request)->get();
        return view('research_staff.researchperResearcher_report_preview', compact('researchers', 'request', 'viewVersion'));
    }

    $researches = $this->filteredResearchQuery($request)->get();
    return view('research_staff.research_report_preview', compact('researches', 'request', 'viewVersion'));
}

    /**
     * Export the research report in PDF or Excel format
     */
    public function export(Request $request)
    {
  
        // Prepare the description and format values
        $description = str_replace(' ', '_', $request->description);
        $format = $request->input('format');
        $viewVersion = $request->input('view_version');  // Check if it's 'researcher' or default

        // If the 'researcher' view is selected
        if ($viewVersion === 'researcher') {
            // Get filtered researcher data
            $researchers = $this->filteredResearcherQuery($request)->get();

            // Check the format and export accordingly
            if ($format === 'excel') {
                return Excel::download(new ResearchesPerResearcherExport($researchers, $request), "ResearcherBasedReport-{$description}.xlsx");
            }

            // For PDF export of researcher-based report
            $pdf = Pdf::loadView('research_staff.researchperResearcher_report_pdf', compact('researchers', 'request'))
                      ->setPaper('a4', 'landscape');

            return $pdf->download("ResearcherBasedReport-{$description}.pdf");
        }

        // Default to research-title-based report if 'researcher' view is not selected
        $researches = $this->filteredResearchQuery($request)->get();

        // Check the format and export accordingly
        if ($format === 'excel') {
            return Excel::download(new ResearchesExport($researches, $request), "ResearchReport-{$description}.xlsx");
        }

        // For PDF export of research-title-based report
        $pdf = Pdf::loadView('research_staff.research_report_pdf', compact('researches', 'request'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("ResearchReport-{$description}.pdf");
    }

    /**
     * Applies filters to the Research model.
     */
    public function filteredResearchQuery(Request $request)
    {
        $query = Research::with(['members', 'programs', 'schoolYear'])
            ->orderByRaw('COALESCE(approved_date, researches.created_at) DESC') // Qualify created_at
            ->orderBy('title', 'asc');

        // Your filters here
        if ($request->school_year) {
            $query->whereHas('schoolYear', fn($q) => $q->where('school_year', $request->school_year));
        }

        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->researcher_id) {
            $query->whereHas('members', fn($q) => $q->where('researcher_id', $request->researcher_id));
        }

        if ($request->program_id) {
            $query->whereHas('programs', fn($q) => $q->where('program_id', $request->program_id));
        }

        return $query;
    }

    /**
     * Applies filters to the Researcher model along with nested researches.
     */
    public function filteredResearcherQuery(Request $request)
    {
        $researchersQuery = Researcher::with(['researches' => function ($q) use ($request) {
            $q->with(['members', 'programs', 'schoolYear', 'leader', 'fundingType'])
                ->orderByRaw('COALESCE(approved_date, researches.created_at) DESC') // Qualify created_at
                ->orderBy('title', 'asc');

            // Your filters here
            if ($request->school_year) {
                $q->whereHas('schoolYear', fn($q2) => $q2->where('school_year', $request->school_year));
            }

            if ($request->semester) {
                $q->where('semester', $request->semester);
            }

            if ($request->status) {
                $q->where('status', $request->status);
            }

            if ($request->program_id) {
                $q->whereHas('programs', fn($q2) => $q2->where('program_id', $request->program_id));
            }
        }]);

        if ($request->researcher_id) {
            $researchersQuery->where('id', $request->researcher_id);
        }

        return $researchersQuery;
    }
}
