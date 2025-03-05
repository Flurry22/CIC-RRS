<?php

namespace App\Http\Controllers;

use App\Models\ResearchFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResearchFilesController extends Controller
{
    /**
     * Display a listing of the uploaded research files.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all research files from the database
        $files = ResearchFile::all();
        return view('research_staff.researchfiles', compact('files')); 
    }

    /**
     * Handle the file upload and store it in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        // Validate the request
        $request->validate([
            'file_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,docx,doc,png,jpg,jpeg|max:2048',
        ]);

        // Store the uploaded file
        $file = $request->file('file');
        $filePath = $file->storeAs('research_files', $file->getClientOriginalName(), 'public');

        // Create a new record in the database
        ResearchFile::create([
            'title' => $request->input('file_name'),
            'research_file' => $filePath,
        ]);

        // Redirect back with success message
        return redirect()->route('research-files.index')->with('success', 'File uploaded successfully.');
    }

    /**
     * Download the specified file.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id)
    {
        $file = ResearchFile::findOrFail($id);

        // Get the file's full path
        $filePath = storage_path('app/public/' . $file->research_file);

        return response()->download($filePath);
    }

    /**
     * Remove the specified file from the database and storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $file = ResearchFile::findOrFail($id);

        // Delete the file from the storage
        if (Storage::disk('public')->exists($file->research_file)) {
            Storage::disk('public')->delete($file->research_file);
        }

        // Delete the file record from the database
        $file->delete();

        // Redirect back with success message
        return redirect()->route('research-files.index')->with('success', 'File deleted successfully.');
    }


}
