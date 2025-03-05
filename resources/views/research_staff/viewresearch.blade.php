<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <title>View All Research</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/viewResearch.css') }}">
</head>
<style>
    .card:hover {
        transform: scale(1.006);  /* Slightly lifts the card */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow for depth */
    transition: all 0.2s ease-in-out; /* Smooth transition */
}
.card .card-body strong,
.card .card-title {
    color: #4c2f6f;
}
.pagination li a {
        color: #52489f; /* Main theme color */
        background-color: #f8f9fa; /* Light background */
        border: 1px solid #ddd;
        margin: 0 5px;
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    /* Active Page */
    .pagination li.active a {
        background-color: #52489f; /* Color for the selected page */
        color: #fff; /* Text color for active page */
        border-color: #52489f; /* Border color matching the background */
        pointer-events: none;
        font-weight: bold; /* Bold text for better emphasis */
    }

    /* Hover Effect */
    .pagination li a:hover {
        background-color: #922220;
        color: white;
        border-color: white;
    }

    /* Disabled Links */
    .pagination li.disabled a {
        color: #ccc;
        background-color: #f8f9fa;
        border-color: #ddd;
        pointer-events: none;
    }

    </style>
<body>
   

    <div class="wrapper">
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
            <div class="box p-2 rounded-2 text-white" style="background-color: #818589;">
            <h1 style="color: white; font-weight: bold;" class="fs-2">All Research</h1>

                <!-- Search Section -->
                <!-- Search and Filter Section -->
<form action="{{ route('research.index') }}" method="GET" class="mb-4">
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mt-5">
        <!-- Search Input -->
        <div class="col-12 col-md-12 col-lg-auto">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search for Research" 
                value="{{ request('search') }}">
        </div>

        <!-- Filter Dropdown -->
        <div class="col-12 col-md-12 col-lg-auto">
            <select 
                name="type" 
                class="form-control" 
                onchange="this.form.submit()">
                <option value="" {{ request('type') === '' ? 'selected' : '' }}>All</option>
                <option value="program" {{ request('type') === 'program' ? 'selected' : '' }}>Program</option>
                <option value="project" {{ request('type') === 'project' ? 'selected' : '' }}>Project</option>
                <option value="study" {{ request('type') === 'study' ? 'selected' : '' }}>Study</option>
            </select>
        </div>

        <div class="col-12 col-md-12 col-lg-auto">
            <select 
                name="status" 
                class="form-control" 
                onchange="this.form.submit()">
                <option value="" {{ request('status') === '' ? 'selected' : '' }}>All Statuses</option>
                <option value="On-Going" {{ request('status') === 'On-Going' ? 'selected' : '' }}>Ongoing</option>
                <option value="Finished" {{ request('status') === 'Finished' ? 'selected' : '' }}>Terminated/Finished</option>
            </select>
        </div>

        <!-- Search Button -->
        <div class="col-12 col-md-12 col-lg-auto">
            <button type="submit" class="btn text-white w-100" style="background-color: #922220;">Search</button>
        </div>
    </div>
    <a href="{{ route('research-report.create') }}" class="btn btn-primary float-end mb-2 mt-2 " style="background-color:#922220; border: 1px solid #922220;">
        Generate Report
    </a>
</form>


<hr class="border-3" style="color: white; margin: 0 auto; width: 100%;">
<br>

                <!-- Research Cards -->
                <div class="row g-1">
                    @forelse ($research as $researchItem)
                    <div class="card shadow-sm w-100" style="border: 1.5px solid black; border-radius: 10px; margin-bottom: 20px;">
                           
                                <a href="{{ route('research.show', ['id' => $researchItem->id]) }}" class="text-decoration-none text-dark">
                                    <div class="card-body">
                                        <h3 class="card-title" style="color: black;">{{ $researchItem->title }}</h3>
                                        <div class="d-flex justify-content-between">
                                        <p class="card-text" style="color: gray;"><strong style="color: black;">Leader:</strong> {{ $researchItem->leader->name }}</p>
                                            <p class="card-text" style="color: gray;"><strong style="color: black;">Type:</strong> {{ ucfirst($researchItem->type) }}</p>
                                           
                                        </div>
                                    </div>
                                </a>
                                <div class="card-footer text-right d-flex justify-content-start">
                                <p class="card-text"  style="color: black;"><strong>Status:</strong> {{ $researchItem->status }}</p>
                                </div>
                            
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted">No research found for the applied filters and search query.</p>
                        </div>
                    @endforelse
                </div>
                <div class="pagination-container d-flex justify-content-center mt-4">
    {{ $research->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>
               
            </div>
        </div>
        
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
