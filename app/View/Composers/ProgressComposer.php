<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Project;

class ProgressComposer
{
    public function compose(View $view)
    {
        // Ensure 'projectId' is passed to the view
        $projectId = $view->projectId;

        // Fetch the project with its tasks
        $project = Project::with('tasks')->find($projectId);

        if ($project) {
            $totalTasks = $project->tasks->count();
            $completedTasks = $project->tasks->where('completed', true)->count();
            $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
        } else {
            $progressPercentage = 0;
        }

        // Pass the progress percentage to the view
        $view->with('progressPercentage', $progressPercentage);
    }
}
