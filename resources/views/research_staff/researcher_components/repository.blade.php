<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <title>Researcher Repository</title>
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/repository.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
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
        .card-title {
    text-decoration: none !important; /* Removes underline */
    font-size: 1.25rem; 
    color: #52489f !important;
}
.hover-link:hover {
        color: #922220 !important;
        text-decoration: underline;
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
                    <div class="row mb-4 text-center">
    @foreach ([
        'Total Researches' => ['icon' => 'bi-journal-text', 'count' => $totalResearches],
        'Researches with Certificate of Utilization' => ['icon' => 'bi-award', 'count' => $counts['certificate_of_utilization'] ?? 0],
        'Researches with Special Order' => ['icon' => 'bi-file-earmark-lock', 'count' => $counts['special_order'] ?? 0],
        'Researches with Terminal File' => ['icon' => 'bi-file-earmark-check', 'count' => $counts['terminal_file'] ?? 0],
        'Researches with Approved Proposal File' => ['icon' => 'bi-patch-check', 'count' => $counts['approved_file'] ?? 0]
    ] as $label => $data)
        <div class="col-md-4 mb-3">
            <div class="p-3 bg-light text-dark rounded shadow-sm h-100">
                <div class="mb-2">
                <i class="bi {{ $data['icon'] }} fs-4" style="color: #922220;"></i>
                </div>
                <h6 class="mb-1 fw-semibold">{{ $label }}</h6>
                <h5 class="fw-bold">{{ $data['count'] }}</h5>
            </div>
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
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <div class="table table-borderless mb-0">
                <!-- Header Row -->
                <div class="d-none d-md-flex fw-bold border-bottom py-2 px-3 text-dark" style="background-color: #f8f9fa;">
                    <div class="col-md-5">Title</div>
                    <div class="col-md-3">Approved Date</div>
                    <div class="col-md-3">Completed Date</div>
                </div>

                <!-- Research Rows -->
                @foreach ($researches as $research)
                    <div class="row align-items-center border-bottom py-2 px-3" style="background-color: #ffffff;">
                        <!-- Title -->
                        <div class="col-md-5">
                            <a href="#"
                               class="text-dark fw-medium hover-link"
                               style="font-size: 0.95rem;"
                               data-bs-toggle="popover"
                               data-bs-html="true"
                               data-bs-placement="right"
                               data-bs-content="
                                    <ul style='padding-left: 1rem;'>
                                        @if ($research->certificate_of_utilization)
                                            <li><a href='{{ route('research.viewCertificate', $research->id) }}' target='_blank' class='btn btn-link p-0'>Certificate</a></li>
                                        @endif
                                        @if ($research->special_order)
                                            <li><a href='{{ route('research.viewSpecialOrder', $research->id) }}' target='_blank' class='btn btn-link p-0'>Special Order</a></li>
                                        @endif
                                        @if ($research->approved_file)
                                            <li><a href='{{ route('research.viewApprovedProposalFile', $research->id) }}' class='btn btn-link p-0' target='_blank'>Approved File</a></li>
                                        @endif
                                        @if ($research->terminal_file)
                                            <li><a href='{{ route('research.viewTerminalFile', $research->id) }}' target='_blank' class='btn btn-link p-0'>Terminal File</a></li>
                                        @endif
                                        @if ($research->proposal_file)
                                            <li><a href='{{ route('research.viewProposalFile', $research->id) }}' target='_blank' class='btn btn-link p-0'>Proposal</a></li>
                                        @endif
                                    </ul>
                               ">
                                {{ $research->title }}
                            </a>
                        </div>

                        <!-- Approved Date -->
                        <div class="col-md-3 text-muted small">
                            {{ $research->approved_date 
                                ? \Carbon\Carbon::parse($research->approved_date)->format('M d, Y') 
                                : 'Not Approved' }}
                        </div>

                        <!-- Completed Date -->
                        <div class="col-md-3 text-muted small">
                            {{ $research->date_completed 
                                ? \Carbon\Carbon::parse($research->date_completed)->format('M d, Y') 
                                : 'Not Completed' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
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
        document.addEventListener("DOMContentLoaded", function () {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => 
            new bootstrap.Popover(popoverTriggerEl, {
                html: true,
                sanitize: false,  // Allow HTML content inside popovers
                container: 'body',
                trigger: 'focus'
            })
        );
        
        // Close popovers when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.popover') && !e.target.closest('[data-bs-toggle="popover"]')) {
                popoverList.forEach(pop => pop.hide());
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
