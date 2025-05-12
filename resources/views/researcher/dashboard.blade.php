<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/researcher/dashboard.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<style>
.custom-preview-btn {
    background-color: #7393B3;
    color: white;
    border: none;
    transition: background-color 0.3s ease;
}

.custom-preview-btn:hover {
    background-color: #5f7da3;
}
.tab-header {
        padding: 6px 12px;
        border-radius: 8px 8px 0 0;
        background-color: #e9ecef;
        color: #555;
    }

    .active-tab {
        background-color: #922220;
        color: white;
        font-weight: bold;
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
            <img src="{{ asset('img/cic-logo.png') }}" alt="University Logo">
            <h3>USeP-College of Information and Computing</h3>
            <h4>Research Repository System</h4>
            <ul>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: 8px">
                <li><a href="{{ route('researcher.dashboard', ['id' => $researcher->id]) }}">Dashboard</a></li>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: -7px">
                <li><a href="{{ route('researchers.search') }}">Researchers & Researches</a></li>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: -5px">
                <li><a href="{{ route('researcher.settings.edit') }}">Settings</a></li>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: -5px">
                <li><a href="{{ route('researcher.files.index') }}">Research Files</a></li>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: -5px">
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Main Content -->
        <div class="container-fluid px-3 py-3">
            <div class="box p-3 rounded-2 text-white" style="background-color: #818589;">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-12">
                        <div class="profile p-3 rounded" style="background-color: #F2F9FF;">
                        <button type="button" class="btn btn-sm position-absolute" 
    style="top: 5px; right: 5px; background-color: #7393B3; color: white; transition: background-color 0.3s ease;" 
    onmouseover="this.style.backgroundColor='#5f7f9d';" onmouseout="this.style.backgroundColor=' #7393B3';"
    data-bs-toggle="modal" data-bs-target="#generateReportModal"
    data-researcher-id="{{ $researcher->id }}">
    Generate Report
</button>


                            <div class="d-flex flex-column align-items-center text-center">
                                <div class="profile-image mb-2">
                                    <img src="{{ asset('storage/' . $researcher->profile_picture) }}" 
                                        alt="Profile Picture" class="img-thumbnail rounded-circle">
                                </div>
                                <h1 class="text-black">{{ $researcher->name }}</h1>
                                <p class="text-muted">{{ $researcher->bio ?? 'No biography available.' }}</p>
                            </div>
                            <hr class="my-3 border-danger" style="width: 100%;">
                            <div>
                                <h5 class="text-black"> <i class="bi bi-lightbulb me-2"></i>Specializations</h5>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($skills as $skill)
                                        @if (!empty($skill))
                                        <span class="badge"style="background-color:#922220; color:white; font-size: 1rem;">{{ $skill }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <hr class="my-3 border-danger" style="width: 100%;">
                            <div>
                                <h5 class="text-black"><i class="bi bi-stack me-2"></i>Programs</h5>
                                <div class="d-flex flex-wrap gap-2">
    @forelse ($programs as $program)
        <span class="badge"style="background-color:#922220; color:white; font-size: 1rem;">{{ $program->name }}</span>
    @empty
      <p> No programs found.<p>
    @endforelse
</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-11 col-md-10 col-lg-12 mt-3 rounded-1 p-2 text-black insights" style="background-color: #F2F9FF; width: 98.5%; height: auto;">
                        <h5 class="text-black">Research Insights</h5>
                        <div class="row g-3 ">
                        <div class="col-md-6 text-center">
                                    <h6>Status Breakdown</h6>
                                    <div class="chart-container">
                                        <canvas id="statusChart"></canvas>
                                    </div>
                                    <p>
                                        <strong>Finished:</strong> 
                                        <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#researchModal" 
                                        data-status="finished" id="showFinishedResearches">{{ $finishedResearchCount }}</a>
                                    </p>
                                    <p>
                                        <strong>Ongoing:</strong> 
                                        <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#researchModal" 
                                        data-status="ongoing" id="showOngoingResearches">{{ $ongoingResearchCount }}</a>
                                    </p>
                                </div>
                            <div class="col-md-6 text-center">
                                <h6>Role Breakdown</h6>
                                <div class="chart-container">
                                    <canvas id="rolesChart"></canvas>
                                </div>
                                <p><strong>As Leader:</strong> {{ $asLeaderCount }}</p>
                                <p><strong>As Member:</strong> {{ $asMemberCount }}</p>
                            </div>
                        </div>
                    </div>


    <!-- Container for both sections -->
<div class="col-12 col-md-10 col-lg-12 mt-3 rounded p-2 rrl" style="background-color: #F2F9FF; width: 98.5%; height: auto;">
    <!-- Toggle Buttons -->
    <div class="d-flex gap-4 align-items-center mt-3 mb-2">
    <h5 id="tabLed" class="tab-header active-tab" style="cursor: pointer; margin-bottom: 0;">Researches Led</h5>
    <h5 id="tabParticipated" class="tab-header" style="cursor: pointer; margin-bottom: 0;">Researches Participated</h5>
</div>

    <!-- Researches Led Section (Initially Visible) -->
    <div id="researchLedSection" class="mt-3">
        <!-- Table for Led Researches -->
        <table id="ledResearchesTable" class="table table-bordered table-striped table-hover">
            <thead style="background-color: #e9ecef;">
                <tr>
                    <th>Title</th>
                    <th>Approved Date</th>
                    <th>Completed Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leadingResearches as $research)
                    <tr>
                        <td><strong>{{ $research->title }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($research->approved_date)->format('M d, Y') }}</td>
                        <td>
                            @if($research->date_completed)
                                {{ \Carbon\Carbon::parse($research->date_completed)->format('M d, Y') }}
                            @else
                                <span class="text-muted">Not Completed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Button to toggle view -->
        <div class="text-center mt-3">
            <button id="toggleLedBtn" class="btn" style="background-color:#922220; color:white;">Show All</button>
        </div>
    </div>

    <!-- Researches Participated Section (Initially Hidden) -->
    <div id="researchParticipatedSection" class="mt-3" style="display: none;">
        <!-- Table for Participated Researches -->
        <table id="participatedResearchesTable" class="table table-bordered table-striped table-hover">
            <thead style="background-color: #e9ecef;">
                <tr>
                    <th>Title</th>
                    <th>Approved Date</th>
                    <th>Completed Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participatingResearches as $research)
                    <tr>
                        <td><strong>{{ $research->title }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($research->approved_date)->format('M d, Y') }}</td>
                        <td>
                            @if($research->date_completed)
                                {{ \Carbon\Carbon::parse($research->date_completed)->format('M d, Y') }}
                            @else
                                <span class="text-muted">Not Completed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Button to toggle view -->
        <div class="text-center mt-3">
            <button id="toggleParticipatedBtn" class="btn" style="background-color:#922220; color:white;">Show All</button>
        </div>
    </div>
</div>





                  

                </div>
            </div>
        </div>
    </div>


    <!-- Research Titles Modal -->
<div class="modal fade" id="researchModal" tabindex="-1" aria-labelledby="researchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="researchModalLabel">Research List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="researchList" class="list-group">
                    <!-- Research titles will be dynamically inserted here -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <form method="GET" action="{{ route('researcher.dashboard.report.preview', ['id' => $researcher->id]) }}" target="_blank" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateReportModalLabel">Generate Research Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" name="researcher_id" value="{{ $researcher->id }}">

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="all">All</option>
                        <option value="Finished">Finished</option>
                        <option value="On-Going">On-going</option>
                    </select>
                </div>

                <div class="mb-3">
    <label for="year" class="form-label">Year (Optional)</label>
    <input type="number" class="form-control" name="year" id="year" placeholder="Leave blank to include all">
</div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" name="role" id="role">
                        <option value="all">All</option>
                        <option value="leader">Led</option>
                        <option value="member">Participated</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
    <button id="previewReportBtn" data-status="active" class="btn custom-preview-btn w-100 d-flex align-items-center justify-content-center gap-2">
        <i class="bi bi-eye"></i>
        Preview Report
    </button>
</div>
        </form>
    </div>
</div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("previewReportBtn").addEventListener("click", function () {
        const status = document.getElementById("status").value;
        const year = document.getElementById("year").value;
        const role = document.getElementById("role").value;
        const researcherId = {{ $researcher->id }};

        const query = new URLSearchParams({
            status: status,
            year: year,
            role: role
        });

        const url = `{{ url('/researcher') }}/${researcherId}/report/preview?` + query.toString();
        window.location.href = url; // Open in same tab
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Get modal elements
    const researchModal = document.getElementById("researchModal");
    const researchList = document.getElementById("researchList");
    const modalTitle = document.getElementById("researchModalLabel");

    // Click event for Finished and Ongoing links
    document.querySelectorAll("[data-bs-toggle='modal']").forEach(item => {
        item.addEventListener("click", function () {
            const status = this.getAttribute("data-status"); // Get the status (finished/ongoing)
            modalTitle.textContent = (status === "finished") ? "Finished Researches" : "Ongoing Researches";
            researchList.innerHTML = "<li class='list-group-item text-muted'>Loading...</li>";

            // Fetch research data from Laravel Blade variables
            let researches = status === "finished" ? @json($finishedResearches) : @json($ongoingResearches);
            
            // Populate the modal with research titles
            researchList.innerHTML = researches.length > 0 
                ? researches.map(research => `<li class="list-group-item">${research.title}</li>`).join("")
                : "<li class='list-group-item text-muted'>No research found.</li>";
        });
    });
});
</script>
    <script>
        // Sidebar Toggle Script
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menuBtn');
        const closeBtn = document.getElementById('closeBtn');
        menuBtn.addEventListener('click', () => sidebar.classList.add('active'));
        closeBtn.addEventListener('click', () => sidebar.classList.remove('active'));

        // Chart.js Implementation
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Finished', 'Ongoing'],
                datasets: [{
                    label: 'Research Status',
                    data: [{{ $finishedResearchCount }}, {{ $ongoingResearchCount }}],
                    backgroundColor: ['#922220', '#818589'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Status Breakdown' }
                }
            }
        });

        const rolesCtx = document.getElementById('rolesChart').getContext('2d');
        new Chart(rolesCtx, {
            type: 'doughnut',
            data: {
                labels: ['As Leader', 'As Member'],
                datasets: [{
                    label: 'Research Roles',
                    data: [{{ $asLeaderCount }}, {{ $asMemberCount }}],
                    backgroundColor: ['#922220', '#818589'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Role Breakdown' }
                }
            }
        });
    </script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Setup toggle for Led/Participated using header tabs
    const tabLed = document.getElementById("tabLed");
    const tabParticipated = document.getElementById("tabParticipated");
    const researchLedSection = document.getElementById("researchLedSection");
    const researchParticipatedSection = document.getElementById("researchParticipatedSection");

    function switchToLed() {
        researchLedSection.style.display = "block";
        researchParticipatedSection.style.display = "none";
        tabLed.classList.add("active-tab");
        tabParticipated.classList.remove("active-tab");
    }

    function switchToParticipated() {
        researchLedSection.style.display = "none";
        researchParticipatedSection.style.display = "block";
        tabLed.classList.remove("active-tab");
        tabParticipated.classList.add("active-tab");
    }

    tabLed.addEventListener("click", switchToLed);
    tabParticipated.addEventListener("click", switchToParticipated);

    // Set initial view
    switchToLed(); // or switchToParticipated() if you want to default to that

    // Function to toggle the visibility of "Show All" and "Show Less" rows in each table
    function setupToggleTable(tableId, buttonId) {
        const toggleBtn = document.getElementById(buttonId);
        const tableRows = document.querySelectorAll(`#${tableId} tbody tr`);
        const itemsToShow = 10; // Show only the first 3 items initially
        let expanded = false;

        function updateVisibility() {
            tableRows.forEach((row, index) => {
                row.style.display = (expanded || index < itemsToShow) ? "table-row" : "none";
            });

            toggleBtn.textContent = expanded ? "Show Less" : "Show All";
        }

        toggleBtn.addEventListener("click", function () {
            expanded = !expanded;
            updateVisibility();
        });

        updateVisibility();
    }

    // Setup "Show All / Show Less" for both tables
    setupToggleTable("ledResearchesTable", "toggleLedBtn");
    setupToggleTable("participatedResearchesTable", "toggleParticipatedBtn");
});
</script>






</body>
</html>
