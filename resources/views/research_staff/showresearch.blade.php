<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/showResearch.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <title>Research Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<style>
.btn-download {
    background-color:  #c266a7;
    border-color:  #c266a7;
    color: white;
}
</style>

    <div class="header">
        <button class="menu-btn" id="menuBtn">&#9776;</button>
    </div>

    <div class="wrapper container-fluid w-auto p-0 m-0">
        
        <div class="sidebar" id="sidebar">
            <button class="close-btn" id="closeBtn">&times;</button>
            <img src="{{ asset('img/cic-logo.png') }}" alt="usep-logo">
            <h3> USeP- College of Information and Computing</h3>
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

        <div class="main-content container-fluid">
            <div class="box p-2 rounded-2 text-white" style="background-color: #818589;">

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card-header text-white">
                    <h2 class="mb-0">{{ $research->title }}</h2>
                </div>
                <button type="button" class="btn btn-primary mt-2 mb-2 float-end" style="background-color:#922220; border: 1px solid #922220;" data-bs-toggle="modal" data-bs-target="#updateTitleModal">
    Update Title
</button>

<div class="modal fade" id="updateTitleModal" tabindex="-1" aria-labelledby="updateTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTitleModalLabel"style="color: black;">Update Research Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('research.updateTitle', $research->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="title" class="form-label">New Title:</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $research->title }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

                <hr class="w-100  border-3">

                <div class="card-body mt-3 text-black p-3 rounded-2" style="background-color: #F2F9FF;">
                <div class="card-body mt-3 text-black p-3 rounded-2" style="background-color: #F2F9FF;">
    <div class="row">
        <!-- Research Details Section -->
        <div class="col-md-6 mb-3">
            <p style="color: 	#3b3b3b;"><strong style="color: black;">Leader:</strong> {{ $research->leader->name }}</p>
            <p style="color: 	#3b3b3b;"><strong style="color: black;">Status:</strong> {{ $research->status }}</p>
            <p style="color: 	#3b3b3b;"><strong style="color: black;">Description:</strong> {{ $research->description }}</p>
            <p style="color: 	#3b3b3b;"><strong style="color: black;">Program:</strong> @if($research->programs->isNotEmpty())
    <ul>
        @foreach($research->programs as $program)
            <li>{{ $program->name }}</li>
        @endforeach
    </ul>
@else
    <p>No programs assigned yet.</p>
@endif</p> <button type="button" class="btn btn-primary" style="background-color:#922220; border: 1px solid #922220;" data-bs-toggle="modal" data-bs-target="#updateProgramsModal">
    Update Programs
</button>
            <p  class="mt-3" style="color: 	#3b3b3b;">
    <strong style="color: black;">Members:</strong>
    @if($research->members->isNotEmpty())
        @foreach($research->members as $index => $member)
            {{ $member->name }}@if(!$loop->last), @endif
        @endforeach
    @else
        No members assigned.
    @endif
</p> 
        </div>

        <!-- Program Modal -->
         
<div class="modal fade" id="updateProgramsModal" tabindex="-1" aria-labelledby="updateProgramsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProgramsModalLabel">Update Research Programs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateProgramsForm" action="{{ route('research.updatePrograms', $research->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @foreach($allPrograms as $program)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="programs[]" value="{{ $program->id }}"
                                {{ in_array($program->id, $research->programs->pluck('id')->toArray()) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $program->name }}</label>
                        </div>
                    @endforeach

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        <!-- Research Leader and Funding Section -->
        <div class="col-md-6 mb-3">
        <p style="color: 	#3b3b3b;"><strong style="color: black;">Budget:</strong> ₱{{ number_format($research->budget, 2) }}</p>
            <p style="color: 	#3b3b3b;"><strong style="color: black;">Funding Type:</strong> {{ $research->fundingType->type }}</p>
            <p style="color:	#3b3b3b;"><strong style="color: black;">Approved Date:</strong>
                @if ($research->approved_date)
                    {{ \Carbon\Carbon::parse($research->approved_date)->format('F d, Y') }}
                @else
                    Not yet approved.
                @endif
            </p>
        </div>
    </div>

    <hr class="w-100 border-1">

   
    <div class="row">
        <!-- Deadline and Remaining Time Section -->
        <div class="col-md-6 mb-3">
            <p style="color: 	#3b3b3b;"><strong style="color: black;">Deadline:</strong> {{ \Carbon\Carbon::parse($research->deadline)->format('F d, Y') }}</p>
            @if($research->status !== 'Finished')
            @if($remainingDays > 0)
                <p style="color:rgb(221, 53, 53);"><strong style="color: black;">Delayed:</strong> Delayed by {{ $remainingDays }} days</p>
            @elseif($remainingDays == 0)
                <p style="color: #FFD372;"><strong style="color: black;">Remaining Days:</strong> Today is the deadline!</p>
            @elseif($remainingDays < 0)
                <p><strong style="color: black;">Remaining Days:</strong> {{ abs($remainingDays) }} days</p>
            @endif
        @endif
        <button type="button" class="btn btn-primary" style="background-color:#922220; border: 1px solid #922220;" data-bs-toggle="modal" data-bs-target="#updateDeadlineModal">
    Update Deadline
</button>
<form action="{{ route('research.updateDuration', $research->id) }}" method="POST">
    @csrf
    <div class="form-group mt-2">
        <label for="project_duration">Update Project Duration</label>
        <input type="text" name="project_duration" id="project_duration" class="form-control" value="{{ $research->project_duration }}" required>
    </div>
    <button type="submit" class="btn btn-primary mt-2" style="background-color:#922220; border: 1px solid #922220;">Update Duration</button>
</form>

<div class="modal fade" id="updateDeadlineModal" tabindex="-1" aria-labelledby="updateDeadlineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateDeadlineModalLabel">Update Research Deadline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('research.updateDeadline', $research->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="deadline" class="form-label">New Deadline:</label>
                        <input type="date" name="deadline" id="deadline" class="form-control" value="{{ $research->deadline }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        </div>

        <!-- Completion Date Section -->
        <div class="col-md-6 mb-3">
            <p style="color: 	#3b3b3b;"><strong style="color: black;">Completion Date:</strong>
                @if ($research->date_completed)
                    {{ \Carbon\Carbon::parse($research->date_completed)->format('F d, Y') }}
                @else
                    Not completed yet.
                @endif
            </p>
        </div>
    </div>


    <hr class="w-100 border-1">

    <div class="row">
    <!-- Document Section -->
    <form action="{{ route('research.download.zip', $research->id) }}" method="POST">
    @csrf
    <div class="col-md-6 mb-3">
    <p style="color: #3b3b3b;">Check for Multiple Download</p>
        <p style="color: #3b3b3b;">
            <strong style="color: black;">Approved Proposal File:</strong>
            @if($research->approved_file)
                <input type="checkbox" name="files[]" value="approved_file"  class="ms-1"> 
                <a href="{{ route('research.download_approved_file', $research->id) }}" target="_blank">Download </a> | 
                <a href="{{ route('research.viewApprovedProposalFile', ['id' => $research->id]) }}" target="_blank">View</a>
            @else
                No approved file uploaded.
            @endif
        </p>

        <p style="color: #3b3b3b;">
            <strong style="color: black;">Certificate of Utilization:</strong>
            @if($research->certificate_of_utilization)
                <input type="checkbox" name="files[]" value="certificate_of_utilization"  class="ms-3"> 
                <a href="{{ route('research.download_certificate', $research->id) }}" target="_blank">Download </a> | 
                <a href="{{ route('research.viewCertificate', ['id' => $research->id]) }}" target="_blank">View</a>
            @else
                No certificate of utilization uploaded.
            @endif
        </p>
    </div>

    <div class="col-md-6 mb-3">
        <p style="color: #3b3b3b;">
            <strong style="color: black;">Special Order File:</strong>
            @if($research->special_order)
                <input type="checkbox" name="files[]" value="special_order"  class="ms-5"> 
                <a href="{{ route('research.download_special_order', $research->id) }}" target="_blank">Download </a> | 
                <a href="{{ route('research.viewSpecialOrder', ['id' => $research->id]) }}" target="_blank">View</a>
            @else
                No special order uploaded.
            @endif
        </p>

        <p style="color: #3b3b3b;">
            <strong style="color: black;">Terminal Report :</strong>
            @if($research->terminal_file)
                <input type="checkbox" name="files[]" value="terminal_file"  class="ms-5"> 
                <a href="{{ route('research.download_terminal_file', $research->id) }}" target="_blank">Download </a> | 
                <a href="{{ route('research.viewTerminalFile', ['id' => $research->id]) }}" target="_blank">View</a>
            @else
                No terminal file uploaded.
            @endif
        </p>
    </div>

    <button type="submit" class="btn btn-primary" style="background-color:#922220; border: 1px solid #922220;">Download Selected as ZIP</button>
</form>
</div>
</div>
                    
                    <hr class="w-100  border-3">
                    <!-- Forms Section -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <form action="{{ route('research.update_status', ['id' => $research->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="status" class="form-label" style="color: black;">Update Status:</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="On-Going" {{ $research->status === 'On-Going' ? 'selected' : '' }}>On-Going</option>
                                    <option value="Finished" {{ $research->status === 'Finished' ? 'selected' : '' }}>Finished</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="btn mt-3" style="color:white ;background-color: #922220; border-color: #c266a7;">Update </button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-4">
                            <form action="{{ route('research.update_special_order', ['id' => $research->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <label for="special_order" class="form-label" style="color: black;">Update Special Order File:</label>
                                <input type="file" name="special_order" id="special_order" class="form-control" required accept="application/pdf">
                                @error('special_order')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="btn mt-3" style="color:white ;background-color: #922220; border-color: #c266a7;">Update</button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-4">
                            <form action="{{ route('research.update_certificate', ['id' => $research->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <label for="certificate_of_utilization" class="form-label" style="color: black;">Upload Certificate of Utilization:</label>
                                <input type="file" name="certificate_of_utilization" id="certificate_of_utilization" class="form-control" required accept="application/pdf">
                                @error('certificate_of_utilization')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="btn mt-3" style="color:white ;background-color: #922220; border-color:  #c266a7;">Upload</button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-4">
                            <form action="{{ route('research.update_approved_date', ['id' => $research->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="approved_date" class="form-label" style="color: black;">Update Approved Date:</label>
                                <input type="date" name="approved_date" id="approved_date" class="form-control" value="{{ old('approved_date', $research->approved_date) }}">
                                @error('approved_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="btn mt-3" style="color:white ;background-color: #922220; border-color: #c266a7;">Update</button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-4">
                            <form action="{{ route('research.update_approved_file', ['id' => $research->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <label for="approved_file" class="form-label" style="color: black;">Update Approved Proposal File:</label>
                                <input type="file" name="approved_file" id="approved_file" class="form-control" accept="application/pdf">
                                @error('approved_file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="btn mt-3" style="color:white ;background-color: #922220; border-color:  #c266a7;">Update</button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-4">
                            <form action="{{ route('research.update_terminal_file', ['id' => $research->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <label for="terminal_file" class="form-label" style="color: black;">Upload Terminal File:</label>
                                <input type="file" name="terminal_file" id="terminal_file" class="form-control" accept="application/pdf">
                                @error('terminal_file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="btn mt-3" style="color:white ;background-color: #922220; border-color: white;">Upload</button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-4">
                        <form action="{{ route('research.updateCompletedDate', $research->id) }}" method="POST" class="mb-3">
    @csrf
    <div class="form-group">
        <label for="date_completed">Update Completed Date</label>
        <input type="date" name="date_completed" id="date_completed" class="form-control" value="{{ old('date_completed', $research->date_completed ? \Carbon\Carbon::parse($research->date_completed)->format('Y-m-d') : '') }}" required>
    </div>
    <button type="submit" class="btn mt-3" style="color:white ;background-color: #922220; border-color: white;">Update</button>
</form>

                        </div>
                    </div>
                </div>
                <hr class="w-100 border-3">
                <div class="card-footer d-flex justify-content-center align-items-center mt-4">
                    <a href="{{ route('research.index') }}" class="btn" style="background-color: #922220; color: white">Back to All Research</a>
                </div>
            </div>
        </div>
        
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
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
</body>
</html>
