<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workplace;
use App\Models\EducationalBackground;
use App\Models\Project; // Import the Project model

class UserController extends Controller
{
    public function showDashboard(User $user)
    {
        // Fetch user's workplaces, educational backgrounds, and skills
        $workplaces = Workplace::where('user_id', $user->id)->get();
        $educationalBackgrounds = EducationalBackground::where('user_id', $user->id)->get();
        $skills = $user->skills ? array_filter(explode(',', $user->skills)) : [];
        
        // Fetch user's featured project
        $featuredProject = Project::where('user_id', $user->id)->where('featured', true)->get();
        
        // Return the data to the view
        return view('user.dashboard', [
            'user' => $user,
            'workplaces' => $workplaces,
            'educationalBackgrounds' => $educationalBackgrounds,
            'skills' => $skills,
            'featuredProject' => $featuredProject, // Pass featured project to the view
        ]);
    }
}
