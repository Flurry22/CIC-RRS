<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;

class ShowResearchController extends Controller
{
    public function show($id)
    {
        $research = Research::with(['members', 'programs'])->findOrFail($id);
        $allPrograms = Program::all();

        $currentDate = Carbon::now();
        $deadline = Carbon::parse($research->deadline);
        $remainingDays = $deadline->diffInDays($currentDate, false);
        $delayedDays = max(0, -$remainingDays); // Convert negative to positive
        $remainingDays = $deadline->diffInDays($currentDate, false); // Ensure non-negative remaining days

        return view('research_staff.showresearch', compact('research', 'allPrograms', 'delayedDays', 'remainingDays'));
    }

    public function fetchPrograms($id)
    {
        $research = Research::with('programs')->findOrFail($id);
        return response()->json(['programs' => $research->programs ?? []]);
    }

    public function updatePrograms(Request $request, $id)
    {
        $validated = $request->validate([
            'programs' => 'array',
            'programs.*' => 'exists:programs,id',
        ]);

        Research::findOrFail($id)->programs()->sync($validated['programs'] ?? []);

        return redirect()->route('research.show', $id)->with('success', 'Programs updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate(['status' => 'required|in:On-Going,Finished']);
        Research::whereId($id)->update(['status' => $validated['status']]);

        return redirect()->route('research.show', $id)->with('success', 'Research status updated successfully!');
    }

    public function updateProjectDuration(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'project_duration' => 'required|string|max:100',
    ]);

    // Find the research record
    $research = Research::findOrFail($id);

    // Update the project_duration field
    $research->update([
        'project_duration' => $request->input('project_duration'),
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Project duration updated successfully!');
}

    private function handleFileUpload(Request $request, $id, $field, $folder)
    {
        $validated = $request->validate([$field => 'required|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048']);
        $research = Research::findOrFail($id);

        if ($research->$field) {
            Storage::disk('public')->delete($research->$field);
        }

        $filePath = $request->file($field)->store($folder, 'public');
        $research->$field = $filePath;
        $research->save();

        return redirect()->route('research.show', $id)->with('success', ucfirst(str_replace('_', ' ', $field)) . ' uploaded successfully!');
    }

    public function updateCertificate(Request $request, $id)
    {
        return $this->handleFileUpload($request, $id, 'certificate_of_utilization', 'certificates');
    }

    public function updateSpecialOrder(Request $request, $id)
    {
        return $this->handleFileUpload($request, $id, 'special_order', 'special_orders');
    }

    public function updateApprovedFile(Request $request, $id)
    {
        return $this->handleFileUpload($request, $id, 'approved_file', 'approved_files');
    }

    public function updateProposalFile(Request $request, $id)
    {
        return $this->handleFileUpload($request, $id, 'proposal_file', 'proposal_files');
    }

    public function updateTerminalFile(Request $request, $id)
    {
        $response = $this->handleFileUpload($request, $id, 'terminal_file', 'terminal_files');

        Research::whereId($id)->update([
            'status' => 'Finished',
            'date_completed' => now(),
        ]);

        return $response;
    }

    public function updateApprovedDate(Request $request, $id)
    {
        $validated = $request->validate(['approved_date' => 'required|date']);
        Research::whereId($id)->update(['approved_date' => $validated['approved_date']]);

        return redirect()->route('research.show', $id)->with('success', 'Approved date updated successfully!');
    }

    private function downloadFile($id, $field, $name)
    {
        $research = Research::findOrFail($id);
        if (!$research->$field) {
            return redirect()->back()->with('error', ucfirst(str_replace('_', ' ', $field)) . ' not found.');
        }

        return Response::download(storage_path('app/public/' . $research->$field), "{$research->title} - {$name}." . pathinfo($research->$field, PATHINFO_EXTENSION));
    }

    public function downloadProposalFile($id)
    {
        return $this->downloadFile($id, 'proposal_file', 'Proposal File');
    }

    public function downloadCertificate($id)
    {
        return $this->downloadFile($id, 'certificate_of_utilization', 'Certificate of Utilization');
    }

    public function downloadSpecialOrder($id)
    {
        return $this->downloadFile($id, 'special_order', 'Special Order');
    }

    public function downloadApprovedFile($id)
    {
        return $this->downloadFile($id, 'approved_file', 'Approved File');
    }

    public function downloadTerminalFile($id)
    {
        return $this->downloadFile($id, 'terminal_file', 'Terminal File');
    }

    private function viewFile($id, $field)
    {
        $research = Research::findOrFail($id);
        if (!$research->$field) {
            return redirect()->back()->with('error', ucfirst(str_replace('_', ' ', $field)) . ' not found.');
        }

        return Response::file(storage_path('app/public/' . $research->$field));
    }

    public function viewApprovedProposalFile($id)
    {
        return $this->viewFile($id, 'approved_file');
    }

    public function viewCertificate($id)
    {
        return $this->viewFile($id, 'certificate_of_utilization');
    }

    public function viewSpecialOrder($id)
    {
        return $this->viewFile($id, 'special_order');
    }

    public function viewTerminalFile($id)
    {
        return $this->viewFile($id, 'terminal_file');
    }

    public function updateDeadline(Request $request, $id)
{
    // Validate the deadline input
    $validated = $request->validate([
        'deadline' => 'required|date|after_or_equal:today', // Must be a valid date and not in the past
    ]);

    // Find the research and update the deadline
    Research::whereId($id)->update(['deadline' => $validated['deadline']]);

    return redirect()->route('research.show', ['id' => $id])->with('success', 'Deadline updated successfully!');
}

public function updateTitle(Request $request, $id)
{
    // Validate the title input
    $validated = $request->validate([
        'title' => 'required|string|max:255', // Ensures title is required, a string, and max 255 characters
    ]);

    // Find the research and update the title
    Research::whereId($id)->update(['title' => $validated['title']]);

    return redirect()->route('research.show', ['id' => $id])->with('success', 'Title updated successfully!');
}

public function downloadSelectedFiles($id)
{
    $research = Research::findOrFail($id);
    
    // Define files with their desired names
    $files = [
        'certificate_of_utilization' => 'Certificate of Utilization',
        'special_order' => 'Special Order',
        'approved_file' => 'Approved Proposal',
        'proposal_file' => 'Proposal File',
        'terminal_file' => 'Terminal Report',
    ];

    // Storage path for the ZIP file
    $zipFileName = 'Research_Files_' . $research->title . '.zip';
    $zipFilePath = storage_path('app/public/' . $zipFileName);

    $zip = new ZipArchive;
    
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        foreach ($files as $field => $desiredName) {
            if ($research->$field) {
                $filePath = storage_path('app/public/' . $research->$field);
                if (file_exists($filePath)) {
                    // Get file extension
                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                    // Add to ZIP with desired name
                    $zip->addFile($filePath, "{$desiredName}.{$extension}");
                }
            }
        }
        $zip->close();
    } else {
        return redirect()->back()->with('error', 'Failed to create ZIP file.');
    }

    // Download the ZIP file
    return response()->download($zipFilePath)->deleteFileAfterSend(true);
}
public function updateCompletedDate(Request $request, $id)
{
    $validated = $request->validate([
        'date_completed' => 'required|date',
    ]);

    Research::whereId($id)->update([
        'date_completed' => $validated['date_completed'],
    ]);

    return redirect()->route('research.show', $id)->with('success', 'Completed date updated successfully!');
}
}


