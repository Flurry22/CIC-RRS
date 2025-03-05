<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <title>Researcher Repository</title>
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/repository.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        
        .file-section {
            display: none; /* Files will be hidden by default */
        }
        .main-content {
             /* Ensuring the content doesn't overlap with the sidebar */
            padding: 10px; /* Adding some space around the content */
        }
        
        .profile-picture img {
            width: 175px;
            height: 175px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid black;
        }
        .file-info {
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="wrapper container-fluid w-auto h-auto p-0 m-0">

        <div class="header container-fluid p-0">
            <button class="menu-btn" id="menuBtn">&#9776;</button>
        </div>

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


            <div class="main-content">
                <div class="box p-4 rounded-2 text-white" style="background-color: #818589;">
                    <!-- Move the Back to Researchers List Button to the left -->
                    <div class="btn-container d-flex justify-content-start mt-1">
                        <a href="{{ route('researchers.index') }}" class="btn" style="background-color: #922220; color: white; ">Back to Researchers List</a>
                    </div>

                    <hr class="w-100 border-3">
    
                    <div class="profile-picture text-center mb-4">
                        <img src="{{ $researcher->profile_picture ? Storage::url($researcher->profile_picture) : asset('img/default-profile.png') }}" 
                        alt="Profile Picture of {{ $researcher->name }}">
                    </div>

                    <!-- Researcher Name and Email -->
                    <div class="text-center mb-4">
                        <h1 class="mb-0"style="color: white;">{{ $researcher->name }}</h1>
                        <p style="color: white;">Email: {{ $researcher->email }}</p>
                    </div>

                    <!-- Display Counts -->
                    <div class="row mb-4">
                        @foreach (['Total Researches' => $totalResearches, 'Researches with Certificate of Utilization' => $counts['certificate_of_utilization'] ?? 0, 'Researches with Special Order' => $counts['special_order'] ?? 0, 'Researches with Terminal File' => $counts['terminal_file'] ?? 0, 'Researches with Approved Proposal File' => $counts['approved_file'] ?? 0] as $label => $count)
                            <div class="col-md-4 mb-3">
                                <h5 style="color: white;">{{ $label }}: {{ $count }}</h5>
                            </div>
                        @endforeach
                    </div>

                    <hr class="w-100 border-3">

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('researcher.repository', $researcher->id) }}" class="mb-4">
                        <div class="form-row d-flex justify-content-center align-items-center flex-wrap gap-2">
                            <div class="col-12 col-md-4 mb-2">
                                <input type="text" name="search" class="form-control" placeholder="Search by title" value="{{ old('search', $searchTerm) }}">
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <select name="file_category" class="form-control">
                                    <option value="">Filter by File Category</option>
                                    <option value="all" {{ old('file_category', $fileCategory) == 'all' ? 'selected' : '' }}>All Files</option>
                                    <option value="certificate_of_utilization" {{ old('file_category', $fileCategory) == 'certificate_of_utilization' ? 'selected' : '' }}>Certificate of Utilization</option>
                                    <option value="special_order" {{ old('file_category', $fileCategory) == 'special_order' ? 'selected' : '' }}>Special Order</option>
                                    <option value="approved_file" {{ old('file_category', $fileCategory) == 'approved_file' ? 'selected' : '' }}>Approved File</option>
                                    <option value="terminal_file" {{ old('file_category', $fileCategory) == 'terminal_file' ? 'selected' : '' }}>Terminal File</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 mb-2">
                            <button type="submit" class="btn btn-primary btn-block" style="background-color: #922220; border-color: #922220;">Search & Filter</button>
                            </div>
                        </div>
                    </form>
                    <hr class="border-3" style="color: white; margin: 0 auto; width: 75%;">
                    <div class="mt-4">
                        <h3 style="color: white;">All Researches</h3>
                        <br>
                        <div class="row">
                            @foreach ($researches as $research)
                                <div class="">
                                    <div class="card mb-3 border" style="border-radius: 10px; border: 1px solid #ddd;">
                                        <div class="card-body p-2">
                                            <div class="row no-gutters">
                                                <!-- Title Section with border and HR -->
                                                <div class="col-12">
                                                    <a href="#" 
                                                    class="card-title mb-1 text-dark font-weight-bold" 
                                                    style="font-size: 1.25rem; color: #52489f !important;"
                                                    data-toggle="popover" 
                                                    title="{{ $research->title }}" 
                                                    data-content="
                                                        <ul>
                                                            @if ($research->certificate_of_utilization)
                                                                <li><a href='{{ route('research.viewCertificate', $research->id) }}' target='_blank' class='btn btn-link'>View Certificate of Utilization</a></li>
                                                            @endif
                                                            @if ($research->special_order)
                                                                <li><a href='{{ route('research.viewSpecialOrder', $research->id) }}' target='_blank' class='btn btn-link'>View Special Order</a></li>
                                                            @endif
                                                            @if ($research->approved_file)
                                                                <li><a href='{{ route('research.viewApprovedProposalFile', $research->id) }}' class='btn btn-link' target='_blank' >View Approved File</a></li>
                                                            @endif
                                                            @if ($research->terminal_file)
                                                                <li><a href='{{ route('research.viewTerminalFile', $research->id) }}' target='_blank'class='btn btn-link'>View Terminal File</a></li>
                                                            @endif
                                                            @if ($research->proposal_file)
                                                                <li><a href='{{ route('research.viewProposalFile', $research->id) }}' target='_blank' class='btn btn-link'>View Proposal File</a></li>
                                                            @endif
                                                        </ul>"
                                                    >
                                                        {{ $research->title }}
                                                    </a>
                                                    <hr style="border-top: 2px solid white;">
                                                    
                                                    <!-- Flexbox to align Approved and Completed on the same row -->
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <p style="color: black;"><strong style="color: black;">Approved:</strong> 
                                                            {{ $research->approved_date ? \Carbon\Carbon::parse($research->approved_date)->format('F d, Y') : 'Not Approved Yet' }}
                                                        </p>
                                                        <p style="color: black;"><strong style="color: black;">Completed:</strong> 
                                                            {{ $research->date_completed ? \Carbon\Carbon::parse($research->date_completed)->format('F d, Y') : 'Not Completed Yet' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Full jQuery version -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script> <!-- Popper.js for Bootstrap 4 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Enable Bootstrap popovers on elements with data-toggle="popover"
            $('[data-toggle="popover"]').popover({
                trigger: 'focus', // Make popover appear on focus (click)
                html: true, // Allow HTML content in the popover
                placement: 'right', // Position the popover on the right side
                container: 'body' // Ensure the popover doesn't get cut off by a container
            });

            // Close popovers when clicking outside of the card
            $(document).click(function(e) {
                if (!$(e.target).closest('.popover, .card').length) {
                    $('[data-toggle="popover"]').popover('hide');
                }
            });
        });

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
