<?php
namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    /**
     * Show the form for creating a new school year.
     */
    public function create()
    {
        return view('academic_administrator.addschoolyear');  // Render the 'addschoolyear' Blade view
    }

    /**
     * Show the list of all school years.
     */
    public function viewUpdateschoolyear()
    {
        // Fetch all school years from the database
        $schoolYears = SchoolYear::all();

        // Return the 'updateschoolyear' view with the list of school years
        return view('academic_administrator.viewupdateschoolyear', compact('schoolYears'));
    }

    /**
     * Store a newly created school year in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $this->validateSchoolYear($request);

        // Create a new SchoolYear record using the validated data
        SchoolYear::create($request->all());

        // Redirect to the updateschoolyear view with success message
        return redirect()->route('school_years.viewUpdateschoolyear')->with('success', 'School year added successfully!');
    }

    /**
     * Update the specified school year in storage.
     */
    public function update(Request $request, SchoolYear $schoolYear)
    {
        // Validate incoming request data for update
        $this->validateSchoolYear($request, $schoolYear);

        // Update the specified SchoolYear record with new data
        $schoolYear->update($request->all());

        // Redirect to the updateschoolyear view with success message
        return redirect()->route('school_years.viewUpdateschoolyear')->with('success', 'School year updated successfully!');
    }

    /**
     * Remove the specified school year from storage.
     */
    public function destroy(SchoolYear $schoolYear)
    {
        // Delete the specified SchoolYear record
        $schoolYear->delete();

        // Redirect to the updateschoolyear view with success message
        return redirect()->route('school_years.viewUpdateschoolyear')->with('success', 'School year deleted successfully!');
    }

    /**
     * Shared validation for store and update methods.
     */
    private function validateSchoolYear(Request $request, SchoolYear $schoolYear = null)
    {
        $uniqueRule = $schoolYear
            ? 'unique:school_years,school_year,' . $schoolYear->id
            : 'unique:school_years';

        // Validate the school year data
        $request->validate([
            'school_year' => ['required', 'string', 'max:255', $uniqueRule],
            'first_sem_start' => 'required|date',
            'first_sem_end' => 'required|date|after:first_sem_start',
            'second_sem_start' => 'required|date|after:first_sem_end',
            'second_sem_end' => 'required|date|after:second_sem_start',
            'off_sem_start' => 'nullable|date|after:second_sem_end',
            'off_sem_end' => 'nullable|date|after:off_sem_start',
        ]);
    }
}
