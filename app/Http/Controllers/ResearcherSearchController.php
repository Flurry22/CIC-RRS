<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Researcher;
use Illuminate\Support\Facades\Auth;

class ResearcherSearchController extends Controller
{
    public function index(Request $request)
    {
        $researcher = Auth::guard('researcher')->user();
        $search = $request->input('search');
        $searchType = $request->input('search_type', 'researchers'); // Default to 'researchers'

        // Fetch results based on search type
        if ($searchType === 'researchers') {
            // Search researchers
            $researchers = Researcher::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->paginate(9);

            return view('researcher.search_researcher', compact('researcher', 'researchers', 'search', 'searchType'));
        } elseif ($searchType === 'researches') {
            // Search researches (Assuming you have a relationship defined)
            $researches = $researcher->researches()->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })->paginate(9);

            return view('researcher.search_researcher', compact('researcher', 'researches', 'search', 'searchType'));
        }

        // Default case: search researchers
        return redirect()->route('researchers.search');
    }



}
