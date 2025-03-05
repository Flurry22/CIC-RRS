<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\College;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Barryvdh\DomPDF\Facade as PDF;


class ProjectController extends Controller
{
    // Show all projects
    public function index()
{
    // Fetch only the projects that belong to the authenticated user
    $projects = Project::with('college', 'user', 'tasks')
        ->where('user_id', Auth::id()) // Filter by authenticated user
        ->get();

    // Calculate progress for each project
    foreach ($projects as $project) {
        $progressPercentage = $this->calculateProgress($project->id);
        $project->progress_percentage = $progressPercentage;

        // Mark project as completed or incomplete based on progress
        if ($progressPercentage >= 100 && !$project->completed) {
            $project->completed = true;
            $project->completed_at = now(); // Set the completed timestamp
        } elseif ($progressPercentage < 100 && $project->completed) {
            $project->completed = false;
            $project->completed_at = null;
        }

        // Save project with updated progress and completion status
        $project->save();
    }

    return view('viewallproject', ['data' => $projects]);
}
   
    // Show the form to create a new project
    public function create()
    {
        $colleges = College::all();
        $defaultBanners = ['banner1.jfif', 'banner2.jfif', 'banner3.jpg','banner4.jfif', 'banner5.jfif', 'banner6.jpg', 'banner7.jpg', 'banner8.jpg']; 
        return view('addnewproject', compact('colleges', 'defaultBanners'));
    }

    // Store a new project
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'college_id' => 'required|exists:colleges,id', // Validate college_id
            'members' => 'required|string|max:255',
            'description' => 'required|string',
            'published_at' => 'nullable|date',
            'banner_image' => 'required|string' // Validate published_at as a date
        ]);

        // Add the authenticated user's ID to the data
        $validatedData['user_id'] = Auth::id();

        // Create a new project record in the database
        Project::create($validatedData);

        // Redirect to the projects index with a success message
        return redirect()->route('projects.index')->with('success', 'Project added successfully.');
    }

    // Show the worksheet page for a specific project
    public function showWorksheet($id)
    {
        // Find the project and include related tasks, user, college, and drafts
        $project = Project::with(['tasks', 'user', 'college', 'drafts'])->findOrFail($id);

        // Calculate the progress percentage
        $progressPercentage = $this->calculateProgress($id);

        // Pass the formatted progress percentage to the view
        return view('worksheet', [
            'project' => $project,
            'projectId' => $id,
            'progressPercentage' => number_format($progressPercentage, 2), // Format to 2 decimal places
        ]);
    }

    private function calculateProgress($project_id)
    {
        $project = Project::with('tasks')->findOrFail($project_id);
        $totalTasks = $project->tasks->count();
        $completedTasks = $project->tasks->where('completed', true)->count();
        $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

        return $progressPercentage; // Return raw value for formatting in the view
    }

    public function feature(Request $request, $id)
{
    // Validate the input for abstract and file path
    $request->validate([
        'abstract' => 'required|string|max:255',
        'file_path' => 'required|file|mimes:pdf|max:2048',
    ]);

    // Retrieve the project by ID
    $project = Project::findOrFail($id);

    if ($request->hasFile('file_path')) {
        // Get the original extension of the file
        $extension = $request->file('file_path')->getClientOriginalExtension();

        // Use the project title as the filename (sanitize the title to avoid issues with special characters)
        $filename = Str::slug($project->title) . '.' . $extension;

        // Store the file in the 'uploads' directory
        $filePath = $request->file('file_path')->storeAs('uploads', $filename);

        // Update the project with the new file path
        $project->update([
            'featured' => true,
            'abstract' => $request->input('abstract'),
            'file_path' => $filePath,
        ]);
    } else {
        // If no file is uploaded, just update abstract and featured fields
        $project->update([
            'featured' => true,
            'abstract' => $request->input('abstract'),
        ]);
    }

    return redirect()->back()->with('success', 'Project has been added to Featured.');
}

public function removeFeature($id)
{
    $project = Project::findOrFail($id);
    $project->featured = false;
    $project->save();

    return redirect()->back()->with('success', 'Project removed from featured list successfully.');
}
public function downloadFile($id)
{
    // Find the project by ID
    $project = Project::findOrFail($id);

    // Define the path to the file
    $filePath = storage_path('app/' . $project->file_path);

    // Check if the file exists
    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'File not found.');
    }

    // Define the path for the generated PDF
    $pdfFilePath = storage_path('app/uploads/' . Str::slug($project->title) . '.pdf');

    // Check the file extension
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    if ($extension === 'docx') {
        // Load the DOCX file
        $phpWord = IOFactory::load($filePath);

        // Prepare to write to HTML format
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

        // Use output buffering to capture the generated HTML content
        ob_start();
        $htmlWriter->save('php://output'); // Save to output buffer
        $html = ob_get_clean(); // Get HTML content from buffer

        // Debug: Check if HTML is generated
        if (empty($html)) {
            return redirect()->back()->with('error', 'Failed to generate HTML from DOCX.');
        }

        // Load HTML into Dompdf
        $pdf = new \Dompdf\Dompdf();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait'); // Set paper size and orientation
        $pdf->render();

        // Save the PDF file
        file_put_contents($pdfFilePath, $pdf->output());
    } elseif ($extension === 'pdf') {
        // If the file is already a PDF, set the PDF file path to the original
        $pdfFilePath = $filePath;
    } else {
        return redirect()->back()->with('error', 'Invalid file format.'); // Return error for unsupported formats
    }

    // Check if the PDF was created successfully
    if (!file_exists($pdfFilePath)) {
        return redirect()->back()->with('error', 'Failed to generate PDF.');
    }

    // Return the PDF file as a response with the project title as the filename
    return response()->file($pdfFilePath, [
        'Content-Type' => 'application/pdf', // Set content type to PDF
        'Content-Disposition' => 'attachment; filename="' . Str::slug($project->title) . '.pdf"', // Set the filename to download
    ]);
}


public function destroy($id)
{
    $project = Project::findOrFail($id);
    
    // Optionally, you might want to delete the associated file
    if ($project->file_path) {
        Storage::delete($project->file_path);
    }

    $project->delete();

    return redirect()->route('projects.index')->with('success', 'Project removed successfully.');
}

}
