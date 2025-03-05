<?php

namespace App\Http\Controllers;

use App\Models\Researcher;
use App\Models\Research;

class AdminManageResearcherController extends Controller
{
    /**
     * Display the list of researchers along with their total, ongoing projects, active researchers, and inactive researchers.
     */
    public function index()
    {
        // Retrieve all researchers
        $researchers = Researcher::with('researches')->paginate(9);

        // Count the active researchers (those with ongoing projects)
        $activeResearchers = Researcher::with('researches')
        ->whereHas('researches', function ($query) {
            $query->where('status', 'On-Going');
        })->count(); 

        // Count the inactive researchers (those without ongoing projects)
        $inactiveResearchers = $researchers->filter(function ($researcher) {
            return $researcher->researches->where('status', 'On-Going')->isEmpty();
        })->count();

        // Get the total number of researchers
        $totalResearchers = Researcher::count();

        // Pass data to the view
        return view('academic_administrator.manage_researchers', compact('researchers', 'activeResearchers', 'inactiveResearchers', 'totalResearchers'));
    }
}
