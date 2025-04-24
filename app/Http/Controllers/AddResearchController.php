<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\Researcher;
use App\Models\Program;
use App\Models\FundingType;
use App\Models\SchoolYear;
use App\Models\SDG;  // Import SDG model
use App\Models\DOST6P;  // Import DOST6P model
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AddResearchController extends Controller
{
    // Show the form for creating a new research based on the type (program, project, or study)
    public function create($type)
    {
        $researchers = Researcher::all();
        $programs = Program::all();
        $fundingTypes = FundingType::all();
        $schoolYears = SchoolYear::all(); // Fetch available school years
        $semesters = ['First Semester', 'Second Semester', 'Off Semester']; // Define semester options

        // Fetch all SDGs and DOST 6Ps
        $sdgs = SDG::all(); // Fetch all SDGs
        $dost6ps = DOST6P::all(); // Fetch all DOST 6Ps

        return view(
            'research_staff.add_research_components.forms.' . $type,
            compact('type', 'programs', 'fundingTypes', 'researchers', 'schoolYears', 'semesters', 'sdgs', 'dost6ps')
        );
    }

    public function store(Request $request, $type)
{
    // Define validation rules
    $rules = [
        'program_id' => 'required|array',
        'program_id.*' => 'exists:programs,id',
        'description' => 'required|string|max:255',
        'budget' => 'required|numeric|min:0',
        'deadline' => 'required|date',
        'funding_type_id' => 'required|exists:funding_types,id',
        'leader_id' => 'required|exists:researchers,id',
        'members' => 'nullable|array',
        'members.*' => 'exists:researchers,id',
        'title' => 'required|string|max:355',
        'funded_by' => 'nullable|string|max:255',
        'school_year_id' => 'required|exists:school_years,id',
        'semester' => 'required|string|in:First Semester,Second Semester,Off Semester',
        'approved_file' => 'nullable|file|mimes:pdf,docx,doc|max:2048',
        'approved_date' => 'nullable|date|before_or_equal:today',
        'special_order' => 'nullable|file|mimes:pdf,docx,doc|max:2048',
        'sdgs' => 'nullable|array',
        'dost_6ps' => 'nullable|array',
        'project_duration' => 'required|string|max:100', // Added validation for project duration
    ];

    // Validate request data
    $validated = $request->validate($rules);

    // Remove the leader from the members array to prevent duplication
    $members = isset($validated['members']) ? array_diff($validated['members'], [$validated['leader_id']]) : [];

    // Handle file upload if provided
    $approvedFilePath = null;
    if ($request->hasFile('approved_file')) {
        $approvedFilePath = $request->file('approved_file')->store('approved_files', 'public');
    }

    $specialOrderFilePath = null;
    if ($request->hasFile('special_order')) {
        $specialOrderFilePath = $request->file('special_order')->store('special_orders', 'public');
    }
 
    // Create the research record
    $research = Research::create([
        'type' => $type,
        'description' => $validated['description'],
        'budget' => $validated['budget'],
        'deadline' => $validated['deadline'],
        'funding_type_id' => $validated['funding_type_id'],
        'leader_id' => $validated['leader_id'],
        'title' => $validated['title'],
        'funded_by' => $validated['funded_by'] ?? null,
        'school_year_id' => $validated['school_year_id'],
        'semester' => $validated['semester'],
        'approved_file' => $approvedFilePath,
        'approved_date' => $validated['approved_date'] ?? null,
        'special_order' => $specialOrderFilePath,
        'project_duration' => $validated['project_duration'], // Save project duration
    ]);

    // Attach the leader with a 'leader' role
    $research->researchers()->attach($validated['leader_id'], ['role' => 'leader']);
    $research->programs()->sync($validated['program_id']);

    // Attach other members with a 'member' role
    if (!empty($members)) {
        $research->researchers()->attach($members, ['role' => 'member']);
    }

    // Attach SDGs (if provided)
    if (isset($validated['sdgs'])) {
        $research->sdgs()->attach($validated['sdgs']);
    }

    // Attach DOST 6Ps (if provided)
    if (isset($validated['dost_6ps'])) {
        $research->dost6ps()->attach($validated['dost_6ps']);
    }

    // Redirect back with a success message
    return redirect()->route('research.index')->with('success', 'Research added successfully!');
}


public function index(Request $request)
{
    // Start with the base query including relationships
    $query = Research::with(['leader'])
    ->orderByRaw('COALESCE(approved_date, date_completed, created_at) DESC') // Sort by dates
    ->orderBy('title', 'asc');  // Sort by approved_date, fallback to created_at

    // Search by title or leader's name
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhereHas('leader', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
        });
    }

    
    // Filter by type
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('status')) {
        if ($request->status === 'overdue') {
            $query->where('status', '!=', 'Finished')
                  ->where('deadline', '<', now());
        } else {
            $query->where('status', $request->status);
        }
    }
    

    // Paginate results
    $research = $query->paginate(15)->withQueryString();

    // Pass current search and filter values to the view
    return view('research_staff.viewresearch', [
        'research' => $research,
        'search' => $request->input('search'),
        'type' => $request->input('type'),
        'status' => $request->input('status'),
    ]);
}

    public function destroy($id)
    {
        $research = Research::find($id);

        if (!$research) {
            return redirect()->route('research.index')->with('error', 'Research record not found!');
        }

        $research->researchers()->detach();
        $research->delete();

        return redirect()->route('research.index')->with('success', 'Research record deleted successfully!');
    }
}
