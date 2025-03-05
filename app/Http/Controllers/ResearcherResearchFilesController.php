<?php

namespace App\Http\Controllers;

use App\Models\ResearchFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResearcherResearchFilesController extends Controller
{
    /**
     * Display the list of uploaded research files available for download by the researcher.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $researcher = Auth::guard('researcher')->user();
        // Fetch all research files available for download by the researcher
        $files = ResearchFile::all();
        
        // Return the view with the fetched files
        return view('researcher.researcher_researchfiles', compact('researcher','files'));
    }

    /**
     * Handle the download of a specified research file.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id)
    {
        $file = ResearchFile::findOrFail($id);

        // Check if the file exists in storage
        $filePath = storage_path('app/public/' . $file->research_file);
        
        // Ensure file exists
        if (!Storage::disk('public')->exists($file->research_file)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->download($filePath);
    }
}
