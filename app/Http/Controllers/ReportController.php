<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Method to display all reports to users
    public function showReports()
    {
        $reports = Report::all();
        $user = Auth::user();  // Fetch all reports
        return view('reports', compact('reports', 'user'));
    }

    // Method to handle downloading a report
    public function downloadReport($id)
{
    // Find the report by ID
    $report = Report::findOrFail($id);

    // Get the file path and title
    $filePath = $report->file_path;
    $title = $report->title; // Get the title
    $extension = pathinfo($filePath, PATHINFO_EXTENSION); // Get the file extension

    // Combine title with extension for the download filename
    $downloadFileName = "{$title}.{$extension}";

    // Download the file with the title and extension
    return Storage::download($filePath, $downloadFileName);
}
}
