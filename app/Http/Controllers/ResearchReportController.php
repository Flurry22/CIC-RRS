<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Research;
use App\Models\Researcher;
use App\Models\Program;
use App\Models\SchoolYear;

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

public function preview(Request $request)
{
    // Fetch filtered data based on the selected filters
    $query = Research::with(['members', 'programs', 'schoolYear']);
    

    if ($request->school_year) {
        $query->whereHas('schoolYear', function ($q) use ($request) {
            $q->where('school_year', $request->school_year);
        });
    }

    if ($request->semester) {
        $query->where('semester', $request->semester);
    }

    if ($request->status) {
        $query->where('status', $request->status); // Check if the status filter is applied correctly
    }


    if ($request->researcher_id) {
        $query->whereHas('members', function ($q) use ($request) {
            $q->where('researcher_id', $request->researcher_id);
        });
    }

    if ($request->program_id) {
        $query->whereHas('programs', function ($q) use ($request) {
            $q->where('program_id', $request->program_id);
        });
    }

    $researches = $query->get();

    return view('research_staff.research_report_preview', compact('researches', 'request'));
}

public function generatePdf(Request $request)
{
    $query = Research::with(['members', 'programs', 'schoolYear']);

    if ($request->school_year) {
        $query->whereHas('schoolYear', function ($q) use ($request) {
            $q->where('school_year', $request->school_year);
        });
    }

    if ($request->semester) {
        $query->where('semester', $request->semester);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->researcher_id) {
        $query->whereHas('members', function ($q) use ($request) {
            $q->where('researcher_id', $request->researcher_id);
        });
    }

    if ($request->program_id) {
        $query->whereHas('programs', function ($q) use ($request) {
            $q->where('program_id', $request->program_id);
        });
    }

    $researches = $query->get();

    $description = str_replace(' ', '_', $request->description); // Optionally, replace spaces with underscores
    $fileName = 'ResearchReport-' . $description . '.pdf';

    // Generate and download the PDF
    $pdf = Pdf::loadView('research_staff.research_report_pdf', compact('researches', 'request'))
              ->setPaper('a4', 'landscape');

    return $pdf->download($fileName); 
}
}
