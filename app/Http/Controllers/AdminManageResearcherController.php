<?php

namespace App\Http\Controllers;

use App\Models\Researcher;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class AdminManageResearcherController extends Controller
{
    /**
     * Display the list of researchers along with their total, active, and inactive researchers.
     */
    public function index()
    {
        $suffixes = "'Jr.', 'Sr.', 'II', 'III', 'IV', 'V'";
        // Retrieve all researchers with their associated school years and researches
        $researchers = Researcher::with(['schoolYears', 'researches']) ->orderByRaw("
        TRIM(
            CASE 
                WHEN name REGEXP ' (Jr\\.|Sr\\.|II|III|IV|V)$' 
                THEN SUBSTRING_INDEX(SUBSTRING_INDEX(name, ' ', -2), ' ', 1)
                ELSE SUBSTRING_INDEX(name, ' ', -1) 
            END
        ) ASC
    ")->paginate(8);
        $schoolYears = SchoolYear::all();
        
    
        // Count total, active, and inactive researchers
        $totalResearchers = Researcher::count();
        $activeResearchers = Researcher::where('researcher_status', 'Active')->count();
        $inactiveResearchers = Researcher::where('researcher_status', 'Inactive')->count();
    
        // Get active years for each researcher
        foreach ($researchers as $researcher) {
            $researcher->activeYears = $researcher->researches->map(function ($research) {
                return date('Y', strtotime($research->approved_date)); // Extract year
            })->unique()->sort()->values();
        }
    
        // Pass data to the view
        return view('academic_administrator.manage_researchers', compact(
            'researchers', 'schoolYears', 'activeResearchers', 'inactiveResearchers', 'totalResearchers'
        ));
    }

    /**
     * Update the researcher's active/inactive school years.
     */
    public function updateStatus(Request $request, $id)
    {
        $researcher = Researcher::findOrFail($id); // Retrieve the researcher
    
        $researcher->update([
            'researcher_status' => $request->input('researcher_status') === 'Active' ? 'Active' : 'Inactive',
        ]);
    
        return redirect()->route('manage.researchers')->with('success', 'Researcher status updated successfully!');
    }
}
