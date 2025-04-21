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
        $researchers = Researcher::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->paginate(9)->appends([
            'search' => $search,
            'search_type' => $searchType
        ]);

        return view('researcher.search_researcher', compact('researcher', 'researchers', 'search', 'searchType'));
    } elseif ($searchType === 'researches') {
        $researches = $researcher->researches()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderByRaw("COALESCE(researches.approved_date, researches.created_at) DESC") // Specify table name
            ->orderBy('researches.title', 'asc') // Sort alphabetically by title
            ->paginate(9)
            ->appends([
                'search' => $search,
                'search_type' => $searchType
            ]);

        return view('researcher.search_researcher', compact('researcher', 'researches', 'search', 'searchType'));
    }

    return redirect()->route('researchers.search');
}


}
