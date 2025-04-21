<?php 
namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\Researcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ResearcherRepositoryController extends Controller
{
    public function show($researcherId, Request $request)
    {
        $researcher = Researcher::findOrFail($researcherId);
    
        // Get the search term and file category from the request
        $searchTerm = $request->input('search');
        $fileCategory = $request->input('file_category', 'all'); // Default to 'all'
    
        // Get researches associated with this researcher and apply filters
        $query = Research::whereHas('researchers', function ($query) use ($researcherId) {
            $query->where('researcher_id', $researcherId);
        });
    
        // Apply search if provided
        if ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%');
        }
    
        // Apply file category filter if provided, excluding 'all'
        if ($fileCategory !== 'all' && $fileCategory) {
            $query->whereNotNull($fileCategory);
        }
    
        // Get researches based on the applied filters
        $researches = $query->orderBy('approved_date', 'desc')
        ->orderBy('title', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
        // Count total researches
        $totalResearches = $researches->count();
    
        // File categories for counting and grouping
        $fileCategories = [
            'certificate_of_utilization',
            'special_order',
            'approved_file',
            'terminal_file',
            'proposal_file' // Add proposal_file to the list
        ];
    
        // Initialize counts for each file category
        $counts = [];
        foreach ($fileCategories as $category) {
            $counts[$category] = $researches->filter(function ($research) use ($category) {
                return $research->{$category};
            })->count();
        }
    
        // Group files by category
        $researchFiles = [];
        foreach ($researches as $research) {
            foreach ($fileCategories as $category) {
                if ($research->{$category}) {
                    $researchFiles[$category][] = $research;
                }
            }
        }
    
        return view('research_staff.researcher_components.repository', compact(
            'researcher', 
            'researches', 
            'researchFiles', 
            'searchTerm', 
            'fileCategory', 
            'totalResearches', 
            'counts'
        ));
    }

    /**
     * Handle file viewing (not downloading).
     */
    private function handleFileView($filePath, $title, $suffix)
    {
        $fullPath = storage_path('app/public/' . $filePath);

        // Check if the file exists
        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', "{$suffix} not found.");
        }

        // Return the file for viewing
        return response()->file($fullPath);
    }

    public function viewCertificate($researchId)
    {
        $research = Research::findOrFail($researchId);

        return $this->handleFileView(
            $research->certificate_of_utilization,
            $research->title,
            'Certificate of Utilization'
        );
    }

    public function viewSpecialOrder($researchId)
    {
        $research = Research::findOrFail($id);

    if (!$research->special_order) {
        return redirect()->back()->with('error', 'Special Order file not found.');
    }

    $filePath = storage_path('app/public/' . $research->special_order);
    return Response::file($filePath);
    }

    public function viewApprovedFile($researchId)
    {
        $research = Research::findOrFail($id);

    if (!$research->approved_file) {
        return redirect()->back()->with('error', 'Proposal file not found.');
    }

    $filePath = storage_path('app/public/' . $research->approved_file);
    return Response::file($filePath);
    }

    public function viewTerminalFile($researchId)
    {
        $research = Research::findOrFail($researchId);

        return $this->handleFileView(
            $research->terminal_file,
            $research->title,
            'Terminal Report'
        );
    }

    // New method to view the proposal file
    public function viewProposalFile($researchId)
    {
        $research = Research::findOrFail($researchId);

        return $this->handleFileView(
            $research->proposal_file,
            $research->title,
            'Proposal File'
        );
    }
}
