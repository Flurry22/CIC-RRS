<?php
namespace App\Exports;

use App\Models\Researcher;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResearchesPerResearcherExport implements FromView
{
    protected $request;

    public function __construct($researchers, $request)
{
    $this->researchers = $researchers;
    $this->request = $request;
}

    public function view(): View
    {
        $query = Researcher::with(['researches' => function ($q) {
            $q->with(['leader', 'members', 'fundingType', 'programs', 'schoolYear']);
        }]);

        if ($this->request->researcher_id) {
            $query->where('id', $this->request->researcher_id);
        }

        $researchers = $query->get();

        return view('research_staff.researchPerResearcher_report_excel', [
            'researchers' => $researchers,
            'request' => $this->request
        ]);
    }
}
