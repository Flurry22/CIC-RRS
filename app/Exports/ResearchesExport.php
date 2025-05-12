<?php

namespace App\Exports;

use App\Models\Research;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class ResearchesExport implements FromView, WithColumnWidths
{
    public $researches;
    public $request;

    public function __construct($researches, $request)
    {
        $this->researches = $researches;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('research_staff.research_report_excel', [
            'researches' => $this->researches,
            'request' => $this->request
        ]);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5, // No
            'B' => max(array_map('strlen', $this->researches->pluck('title')->toArray())) + 2,// Program/Study Title
            'C' => 20, // Project Duration
            'D' => 30, // Project Team
            'E' => 20, // Funding Source
            'F' => 30, // Collaborating College/Agency
            'G' => 15, // Status
            'H' => 15, // Terminal Reports
            'I' => 15, // Year Completed
        ];
    }
    
    
}
