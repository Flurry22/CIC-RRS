<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ ucfirst($type) }} Research Form</title>
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/program.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS (or your preferred CSS framework) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        .budget-input {
    max-width: 350px; /* You can change this value to reduce or increase the width */
}
        .researcher-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 columns */
            gap: 10px; /* Spacing between items */
            margin-top: 10px;
        }

        .form-check {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .form-check-input {
            margin-right: 8px; /* Space between checkbox and label */
        }
        .form-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.form-row .form-group {
    flex: 1;
    margin-right: 10px;
}

.form-row .form-group:last-child {
    margin-right: 0;
}
.narrow-select {
    width: auto; /* Automatically adjusts width */
    max-width: 300px; /* Set maximum width to prevent it from stretching too far */
    min-width: 150px;
     /* Optional: Set minimum width for readability */
}
.narrow-select option:checked {
    text-align: center; /* Center the selected option text */
}
input[type="checkbox"]:checked {
    background-color: #922220; /* Set background color when checked */
    border-color: #922220; /* Set border color when checked */
    box-shadow: none;
    outline: none; 
}


/* Optional: To change the color of the checkmark inside the checkbox */
input[type="checkbox"]:checked::before {
    color: white; /* Change the checkmark color */
}
    </style>
</head>

<body>


    <div class="wrapper container-fluid w-auto p-0 m-0">
        
        <div class="header">
            <button class="menu-btn" id="menuBtn">&#9776;</button>
        </div>

            <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <button class="close-btn" id="closeBtn">&times;</button>
            <img src="{{ asset('img/cic-logo.png') }}" alt="usep-logo">
            <h3>USeP-College of Information and Computing</h3>
            <h4>Research Repository System</h4>
            <hr class="w-100 border-3">
            <ul>
                <li><a href="{{ route('research_staff.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('research.create', ['type' => 'program']) }}">Add New Research</a></li>
                <li><a href="{{ route('research.index') }}">View All Research</a></li>
                <li><a href="{{ route('researchers.create') }}">Add New Researcher</a></li>
                <li><a href="{{ route('researchers.index') }}">View Researchers</a></li>
                <li><a href="/research-files">Research Files</a></li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Main Content -->
        <div class="main-content container-fluid">
            <div class="box p-3 rounded-2 text-white " style= "background-color:  #818589;">
                <div class="form-group">
                    <label for="research-type-select" style="color: white;"><strong>Select Research Type:</strong></label>
                    <select id="research-type-select" class="form-control narrow-select" onchange="updateFormAction()">
                        <option value="{{ route('research.create', ['type' => 'program']) }}" selected>Program</option>
                        <option value="{{ route('research.create', ['type' => 'project']) }}">Project</option>
                        <option value="{{ route('research.create', ['type' => 'study']) }}">Study</option>
                    </select>
                </div>
                <form action="{{ route('research.store', ['type' => $type]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h3 class="mt-4 text-center"style="color: white;">{{ ucfirst($type) }} Research Form</h3>
                   
<br>
                    <div class="form-row">
    <!-- Research Title -->
    <div class="form-group">
        <label for="title" style="color: white;"><strong>Program Title:</strong></label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        @error('title')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Research Leader -->
    <div class="form-group">
        <label for="leader_id" style="color: white;"><strong>Research Leader:</strong></label>
        <select name="leader_id" id="leader_id" class="form-control" required>
            <option value="" disabled selected>Select {{ ucfirst($type) }} Leader</option>
            @foreach ($researchers as $researcher)
                <option value="{{ $researcher->id }}" {{ old('leader_id') == $researcher->id ? 'selected' : '' }}>
                    {{ $researcher->name }}
                </option>
            @endforeach
        </select>
        @error('leader_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

                    <!-- Description -->
                    <div class="form-group mt-3">
                        <label for="description"style="color: white;"><strong>Description:</strong></label>
                        <textarea id="description" name="description" class="form-control" rows="3" >{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <br>
                    <!-- School Year -->
                    <div class="form-row">
    <!-- School Year -->
    <div class="form-group">
        <label for="school_year_id" class="form-label" style="color: white;"><strong>Select School Year:</strong></label>
        <select name="school_year_id" id="school_year_id" class="form-select" required>
            <option value="">Select School Year</option>
            @foreach($schoolYears as $schoolYear)
                <option value="{{ $schoolYear->id }}" {{ old('school_year_id') == $schoolYear->id ? 'selected' : '' }}>
                    {{ $schoolYear->school_year }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Semester -->
    <div class="form-group">
        <label for="semester" class="form-label" style="color: white;"><strong>Select Semester:</strong></label>
        <select name="semester" id="semester" class="form-select" required>
            <option value="">Select Semester</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester }}" {{ old('semester') == $semester ? 'selected' : '' }}>
                    {{ $semester }}
                </option>
            @endforeach
        </select>
    </div>
</div>
                    <!-- Budget -->
                    <div class="form-group mt-3">
    <label for="budget" style="color: white;"><strong>Budget:</strong></label>
    <input type="number" id="budget" name="budget" class="form-control budget-input" value="{{ old('budget') }}" required>
    @error('budget')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>


                    <hr class="w-100 border-3">

                    <hr class="w-100 border-3" style="border-color: #52489f;">
                   <!-- Researcher Members -->
<div class="form-group mt-3">
    <label for="researchers"style="color: white;"><strong>Select Members (Optional):</strong></label>

    <!-- Select All Checkbox -->
    <div class="form-check mt-3">
        <input type="checkbox" id="select-all" class="form-check-input" onclick="toggleSelectAll()">
        <label for="select-all" class="form-check-label"style="color: white;">Select All</label>
    </div>

    <div class="researcher-grid">
        @foreach ($researchers as $researcher)
            <div class="form-check">
                <input type="checkbox" name="members[]" id="researcher-{{ $researcher->id }}" value="{{ $researcher->id }}" class="form-check-input" {{ is_array(old('members')) && in_array($researcher->id, old('members')) ? 'checked' : '' }}>
                <label for="researcher-{{ $researcher->id }}" class="form-check-label"style="color: white;">{{ $researcher->name }}</label>
            </div>
        @endforeach
    </div>
    @error('members')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>


                    <hr class="w-100 border-3">

                    <div class="form-group">
                        <label for="sdgs"style="color white;"><strong>Select SDGs:</strong></label>
                        <div class="checkbox-group" >
                        <label class="mt-2" style="color: white;">
    <input class="form-check-input" type="checkbox" id="select-all-sdgs" onclick="toggleSelectAllSDGs()"> Select All
</label><br>
                            <div class="d-flex flex-wrap gap-3 justify-content-start mt-3">
                                @foreach ($sdgs as $sdg)
                                    <label class="w-25"style="color: white;">
                                        <input class="form-check-input" type="checkbox" name="sdgs[]" value="{{ $sdg->id }}"> {{ $sdg->name }}
                                    </label><br>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <hr class="w-100 border-3">

                    <div class="form-group mt-4">
    <label for="dost_6ps" style="color: white;"><strong>Select DOST 6ps:</strong></label>
    <div class="checkbox-group">
    <label class="mt-2" style="color: white;">
    <input class="form-check-input" type="checkbox" id="select-all-dost6ps" onclick="toggleSelectAllDOST6Ps()"> Select All
</label><br>
        <div class="d-flex flex-wrap gap-3 mt-3">
            @foreach ($dost6ps as $dost6p)
                <label class="form-check-label w-auto" style="color: white;">
                    <input class="form-check-input" type="checkbox" name="dost_6ps[]" value="{{ $dost6p->id }}"> {{ $dost6p->name }}
                </label>
            @endforeach
        </div>
    </div>
</div>

<hr class="w-100 border-3" style="border-color: white;">


                    <div class="form-row">
                        <!-- Funding Source -->
                        <div class="form-group">
                            <label for="funding_type_id" style="color: white;"><strong>Funding Source:</strong></label>
                            <select name="funding_type_id" id="funding_type_id" class="form-control" required onchange="toggleFundedByField()">
                                @foreach ($fundingTypes as $fundingType)
                                    <option value="{{ $fundingType->id }}" {{ old('funding_type_id') == $fundingType->id ? 'selected' : '' }}>
                                        {{ $fundingType->type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('funding_type_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Funded by (conditionally shown) -->
                        <div class="form-group" id="funded_by_group" style="display: none;">
                            <label for="funded_by" style="color: white;"><strong>Funded by:</strong></label>
                            <input type="text" name="funded_by" id="funded_by" class="form-control" value="{{ old('funded_by') }}">
                            @error('funded_by')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

    <!-- Deadline -->
                        <div class="form-group">
                            <label for="deadline" style="color: white;"><strong>Deadline:</strong></label>
                            <input type="date" id="deadline" name="deadline" class="form-control" value="{{ old('deadline') }}" required>
                            @error('deadline')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                        <label for="project_duration">Project Duration</label>
                        <input type="text" name="project_duration" id="project_duration" class="form-control" placeholder="e.g., 6 months, 1 year" required>
                    </div>

    <!-- Program Selection -->
    </div>
        <div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
            <label for="program_id" style="color: white;"><strong>Select Programs:</strong></label>
            <div>
                    @foreach ($programs as $program)
                        <div class="form-check">
                            <input type="checkbox" name="program_id[]" value="{{ $program->id }}" 
                                class="form-check-input" id="program_{{ $program->id }}"
                                {{ is_array(old('program_id')) && in_array($program->id, old('program_id')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="program_{{ $program->id }}">
                                {{ $program->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('program_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        <div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
            <label for="approved_file" style='color: white;'><strong>Upload Approved Proposal File:</strong></label>
            <input type='file' name='approved_file' id='approved_file' accept=".pdf,.doc,.docx" class='form-control'>
            @error('approved_file')
                <span class='text-danger'>{{ $message }}</span>
            @enderror
    </div>

    <div class="form-group">
    <label for="approved_date">Approved Date:</label>
    <input type="date" name="approved_date" id="approved_date" class="form-control" >
</div>

    <div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
        <label for="special_order" class="form-label" style="color: white;"><strong>Upload Special Order File:</strong></label>
        <input type="file" name="special_order" id="special_order" class="form-control" required accept=".pdf,.doc,.docx">
        @error('special_order')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
                    <!-- Submit Button -->
                    <div class="form-group mt-4 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary" style="background-color: #922220; border-color: white;">Submit {{ ucfirst($type) }} Research</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script for Form Action Update -->
    <script>
        function updateFormAction() {
            const selectedValue = document.getElementById('research-type-select').value;
            window.location.href = selectedValue; // Redirect to the selected research type
        }
    </script>

<script>
    function toggleFundedByField() {
        const fundingTypeSelect = document.getElementById('funding_type_id');
        const fundedByGroup = document.getElementById('funded_by_group');
        
        // Check if the selected funding type is external (you can adjust the condition)
        const selectedFundingType = fundingTypeSelect.options[fundingTypeSelect.selectedIndex].text.toLowerCase();
        
        // If the selected funding type is 'external' or matches your specific type, show the 'Funded by' field
        if (selectedFundingType.includes('external')) {
            fundedByGroup.style.display = 'block';
        } else {
            fundedByGroup.style.display = 'none';
        }
    }

    // Initial check in case the page is loaded with a pre-selected value
    window.onload = toggleFundedByField;
</script>

<script>
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('select-all');
        const memberCheckboxes = document.querySelectorAll('input[name="members[]"]');
        
        // Loop through all the member checkboxes and set their checked status
        memberCheckboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    // Toggle "Select All" for SDGs
function toggleSelectAllSDGs() {
    const selectAllSDGsCheckbox = document.getElementById('select-all-sdgs');
    const sdgCheckboxes = document.querySelectorAll('input[name="sdgs[]"]');
    
    // Loop through all the SDG checkboxes and set their checked status
    sdgCheckboxes.forEach(function(checkbox) {
        checkbox.checked = selectAllSDGsCheckbox.checked;
    });
}

// Toggle "Select All" for DOST 6Ps
function toggleSelectAllDOST6Ps() {
    const selectAllDOST6PsCheckbox = document.getElementById('select-all-dost6ps');
    const dost6pCheckboxes = document.querySelectorAll('input[name="dost_6ps[]"]');
    
    // Loop through all the DOST 6Ps checkboxes and set their checked status
    dost6pCheckboxes.forEach(function(checkbox) {
        checkbox.checked = selectAllDOST6PsCheckbox.checked;
    });
}


        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menuBtn');
        const closeBtn = document.getElementById('closeBtn');

        // Show sidebar
        menuBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        // Hide sidebar
        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const approvedDateInput = document.getElementById('approved_date');
        const deadlineInput = document.getElementById('deadline');
        const projectDurationInput = document.getElementById('project_duration');

        function calculateDuration() {
            const startDate = new Date(approvedDateInput.value);
            const endDate = new Date(deadlineInput.value);

            if (!isNaN(startDate) && !isNaN(endDate) && startDate <= endDate) {
                const durationInMs = endDate - startDate;
                const durationInDays = Math.ceil(durationInMs / (1000 * 60 * 60 * 24));
                const durationInMonths = Math.round(durationInDays / 30.44);
                const durationInYears = Math.round(durationInDays / 365.25);

                let durationText = '';
                
                if (durationInYears >= 1) {
                    durationText = `${durationInYears} year${durationInYears > 1 ? 's' : ''}`;
                } else {
                    durationText = `${durationInMonths} month${durationInMonths > 1 ? 's' : ''}`;
                }

                if (!projectDurationInput.matches(':focus')) { 
                    projectDurationInput.value = durationText;
                }
            }
        }

        approvedDateInput.addEventListener('change', calculateDuration);
        deadlineInput.addEventListener('change', calculateDuration);
    });
</script>


</body>

</html>
