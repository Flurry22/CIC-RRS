<?php 

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Draft;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorksheetController extends Controller
{
    // Show the worksheet for a specific project
    public function showWorksheet($id)
    {
        $project = Project::with(['tasks', 'user', 'college', 'drafts', 'links'])->findOrFail($id);
        $progressPercentage = $this->calculateProgress($id);
        

        return view('worksheet', [
            'project' => $project,
            'projectId' => $id,
            'progressPercentage' => $progressPercentage,
            'links' => $project->links,
        ]);
    }

    // Add a new task to the project
    public function addTask(Request $request, $id)
    {
        $validatedData = $request->validate([
            'task_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'progress' => 'nullable|integer|min:0|max:100', // Validate progress as an integer
        ]);

        $project = Project::findOrFail($id);
        $project->tasks()->create($validatedData);

        return redirect()->route('worksheet.show', ['id' => $id])->with('success', 'Task added successfully.');
    }

    // Update the task completion status and progress
    public function updateTaskStatus(Request $request, $project_id, $task_id)
    {
        $request->validate([
            'completed' => 'required|boolean',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        // Find the task by its ID
        $task = Task::findOrFail($task_id);

        // Update the task attributes
        $task->update([
            'completed' => $request->input('completed'),
            'progress' => $request->input('progress', $task->progress),
        ]);

        // Recalculate the progress of the entire project
        $this->updateProjectProgress($project_id);

        // Redirect with updated progress
        return redirect()->route('worksheet.show', ['id' => $project_id])
                         ->with('success', 'Task status updated successfully.');
    }

    // Remove a task from the project
    public function removeTask($project_id, $task_id)
    {
        $task = Task::where('id', $task_id)->where('project_id', $project_id)->firstOrFail();
        $task->delete();

        // Update the project's progress after removing the task
        $this->updateProjectProgress($project_id);

        return redirect()->route('worksheet.show', ['id' => $project_id])->with('success', 'Task removed successfully.');
    }

    // Update project progress
    private function updateProjectProgress($project_id)
    {
        $project = Project::findOrFail($project_id);
        $progressPercentage = $this->calculateProgress($project_id);

        // Update progress percentage
        $project->progress_percentage = $progressPercentage;

        // Automatically mark the project as completed if progress hits 100%
        if ($progressPercentage >= 100) {
            $project->completed = true;
            $project->completed_at = now(); // set completed_at to the current timestamp
        } else {
            // Revert the project to not completed if progress is below 100%
            $project->completed = false;
            $project->completed_at = null; // clear the completed_at field
        }

        // Save the project
        $project->save();
    }

    // Add a new draft to the project
    public function addDraft(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,txt',
            'date' => 'nullable|date',
        ]);

        if ($request->hasFile('file_path')) {
            // Store the file with a unique name first
            $originalFileName = $request->file('file_path')->getClientOriginalName();
            $filePath = $request->file('file_path')->storeAs('drafts', uniqid() . '-' . $originalFileName, 'public');
            $validatedData['file_path'] = $filePath;
        }

        $project = Project::findOrFail($id);
        $project->drafts()->create($validatedData);

        return redirect()->route('worksheet.show', ['id' => $id])->with('success', 'Draft added successfully.');
    }

    // Remove a draft from the project
    public function removeDraft($project_id, $draft_id)
    {
        $draft = Draft::where('id', $draft_id)->where('project_id', $project_id)->firstOrFail();

        if ($draft->file_path) {
            Storage::disk('public')->delete($draft->file_path);
        }

        $draft->delete();

        return redirect()->route('worksheet.show', ['id' => $project_id])->with('success', 'Draft removed successfully.');
    }

    // Calculate the progress percentage for the project
    private function calculateProgress($project_id)
    {
        $project = Project::with('tasks')->findOrFail($project_id);
        $totalTasks = $project->tasks->count();
        $completedTasks = $project->tasks->where('completed', true)->count();
        $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
    
        return number_format($progressPercentage, 2);
    }

    public function downloadDraft($project_id, $draft_id)
    {
        $draft = Draft::where('id', $draft_id)->where('project_id', $project_id)->firstOrFail();

        if (!$draft->file_path) {
            return redirect()->back()->with('error', 'No file available for download.');
        }

        // Get the draft title to use as the filename
        $fileName = $draft->title . '.' . pathinfo($draft->file_path, PATHINFO_EXTENSION);

        return response()->download(storage_path('app/public/' . $draft->file_path), $fileName);
    }
}
