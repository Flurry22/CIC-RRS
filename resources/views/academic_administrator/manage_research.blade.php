<!DOCTYPE html> 
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Research</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('stylesheet/admin/manageResearch.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card {
            margin-bottom: 20px;
        }

        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: auto;
            margin-bottom: 50px;
        }

        .section-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    
    <div class="wrapper container-fluid w-auto p-0 m-0">

            <div class="header container-fluid p-0">
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
                    <li><a href="{{ route('academic_administrator.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('academic_administrator.manage_research') }}">Manage Research</a></li>
                    <li><a href="{{ route('manage.researchers') }}">Manage Researchers</a></li>
                    <li><a href="{{ route('school_years.viewUpdateschoolyear') }}">School Years</a></li>
                    <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

            <!-- Main Content -->
            <div class="main-content container-fluid">
                <div class="box  p-2 rounded-2 text-white" style="background-color:  #818589;">
                    <h1 class="fs-2" style="color: white; font-weight: bold;">Manage Research</h1>

                    <div class="mb-3 d-flex justify-content-between align-items-center mt-5">
                        <form method="GET" action="{{ route('academic_administrator.manage_research') }}" class="d-flex align-items-center">
                            <label for="statusFilter" class="form-label me-2">Filter by Status:</label>
                            <select 
                                id="statusFilter" 
                                name="status" 
                                class="form-select" 
                                onchange="this.form.submit()"
                                style="width: 150px;"  
                            >
                                <option value="" {{ old('status', $statusFilter) === '' ? 'selected' : '' }}>All</option>
                                <option value="on-going" {{ old('status', $statusFilter) === 'on-going' ? 'selected' : '' }}>On-Going</option>
                                <option value="finished" {{ old('status', $statusFilter) === 'finished' ? 'selected' : '' }}>Finished</option>
                            </select>
                        </form>

                        {{-- <button id="show-calendar-btn" class="btn" style="background-color: #922220; color: white;">Show Deadlines Calendar</button> --}}
                    </div>
                    <!-- Row for Analytics and Calendar -->
                    <div class="row mt-4">
                        <!-- Analytics Section -->
                        <div class="col-md-12">
                            <div class="analytics-div p-4" style="background-color: white; border-radius: 10px; margin-bottom: 20px;">
                                <div class="row ">
                                    <h3 class="text" style="color: gray;">Analytics</h3>
                                    <div class="col-md-6">
                                        <div class="chart-container mb-4 d-flex justify-content-center flex-column align-items-center" style="max-width: 400px; margin: auto;">
                                            <h5 class="text" style="color:  gray;">Research Distribution of Faculty</h5>
                                            <canvas id="researchDistributionChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="chart-container mb-4 d-flex justify-content-center flex-column align-items-center" style="max-width: 400px; margin: auto;">
                                            <h5 class="text" style="color:  gray;">Research Types Distribution</h5>
                                            <canvas id="researchTypesChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                 

                    <!-- Undergraduate Research Projects -->
                    <div class="container mt-3 p-4" style="background-color: white; border-radius: 10px; max-width: 98%; padding: 10px; margin: auto;">
    <h3 class="mb-4" style="font-weight: bold; font-size: 20px; color: black;">Undergraduate Research Projects</h3>
    <div class="row">
        @forelse($undergraduateResearches as $research)
            <div class="col-md-4 mb-3"> <!-- Added col-md-4 for responsive layout and mb-3 for spacing between cards -->
                <div class="card shadow-sm h-100"> <!-- Added h-100 to make cards the same height -->
                    <div class="card-body d-flex flex-column"> <!-- Added d-flex and flex-column for vertical alignment -->
                        <h4 class="card-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: black;">
                            {{ $research->title }}
                        </h4>
                        <hr>
                        <div class="leader-section mb-2" style="color: black;"> <!-- Reduced margin-bottom to mb-2 -->
                            <strong>Leader:</strong>
                            <span class="leader-name" style="font-weight: bold; color: gray;">
                                {{ $research->leader->name ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" style="color: black;">
                            <div>
                                <strong>Status:</strong>
                                <span class="badge" style="font-size: 12px; background-color: {{ $research->status === 'On-Going' ? '#922220' : '#118B50' }};">
                                    {{ $research->status }}
                                </span>
                            </div>
                            <div>
                                <strong>Program:</strong> {{ $research->program->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-3" style="color: black;">
                            <div>
                                <strong>Deadline:</strong> {{ \Carbon\Carbon::parse($research->deadline)->format('d-m-Y') }}
                            </div>
                        </div>
                        <div class="mt-auto">
                            <!-- Additional spacing if needed -->
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center col-12" style="font-size: 14px; color: #888;">No undergraduate research projects available.</p>
        @endforelse
    </div>
</div>


                    <!-- Graduate Research Projects -->
                    <div class="container mt-3" style="background-color: white; border-radius: 10px; max-width: 98%; padding: 10px; margin: auto;">
    <h3 class="mb-4" style="font-weight: bold; font-size: 20px; color: black;">Graduate Research Projects</h3>
    <div class="row">
        @forelse($graduateResearches as $research)
            <div class="col-md-4 mb-3"> <!-- Added col-md-4 for responsive layout and mb-3 for spacing between cards -->
                <div class="card shadow-sm h-100"> <!-- Added h-100 to make cards the same height -->
                    <div class="card-body d-flex flex-column"> <!-- Added d-flex and flex-column for vertical alignment -->
                        <h4 class="card-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: black;"> <!-- Reduced font-size -->
                            {{ $research->title }}
                        </h4>
                        <hr>
                        <div class="leader-section mb-2" style="color: black;"> <!-- Reduced margin-bottom to mb-2 -->
                            <strong>Leader:</strong>
                            <span class="leader-name" style="font-weight: bold; color: gray;">
                                {{ $research->leader->name ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" style="color: black;">
                            <div>
                                <strong>Status:</strong>
                                <span class="badge" style="font-size: 12px; background-color: {{ $research->status === 'On-Going' ? '#922220' : '#118B50' }};">
                                    {{ $research->status }}
                                </span>
                            </div>
                            <div>
                                <strong>Program:</strong> {{ $research->program->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-3" style="color: black;">
                            <div>
                                <strong>Deadline:</strong> {{ \Carbon\Carbon::parse($research->deadline)->format('d-m-Y') }}
                            </div>
                        </div>
                        <div class="mt-auto">
                            <!-- Additional spacing if needed -->
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center col-12" style="font-size: 14px; color: #888;">No graduate research projects available.</p>
        @endforelse
    </div>
</div>

                </div>
            </div>
        </div>



                <!-- Calendar Modal -->
<div id="calendar-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarModalLabel">Research Deadlines Calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="calendar"></div> <!-- Calendar will be inserted here -->
            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Chart 1: Research Distribution
            const ctx1 = document.getElementById('researchDistributionChart').getContext('2d');
            const data1 = {
                labels: ['Undergraduate Programs', 'Graduate Programs'],
                datasets: [{
                    label: 'Number of Researches',
                    data: [{{ $undergraduateResearches->count() }}, {{ $graduateResearches->count() }}],
                    backgroundColor: ['#FFB668', '#ff8282'],
                    hoverOffset: 4
                }]
            };
            new Chart(ctx1, { type: 'doughnut', data: data1 });

            // Chart 2: Research Types
            const ctx2 = document.getElementById('researchTypesChart').getContext('2d');
            const data2 = {
                labels: ['Program', 'Project', 'Study'],
                datasets: [{
                    label: 'Research Types',
                    data: [
                        {{ $researchTypeCounts['Program'] }},
                        {{ $researchTypeCounts['Project'] }},
                        {{ $researchTypeCounts['Study'] }}
                    ],
                    backgroundColor: [' #64C4FF', '#ff8282', '#FFB668'],
                    hoverOffset: 4
                }]
            };
            new Chart(ctx2, { type: 'doughnut', data: data2 });


            
        });
    </script>
    <script>
        // Set up the modal action URL dynamically
        leaderModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // The button that triggered the modal
    var researchId = button.getAttribute('data-research-id'); // Get the research ID from the button's data attribute
    
    // Update the form action with the correct researchId for PUT request
    var form = leaderModal.querySelector('form');
    if (researchId) {
        form.action = '/update-leader/' + researchId;  // Set dynamic URL with research ID
    }
});
    </script>
     <script type="text/javascript">
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
$(document).ready(function() {
    // Initialize FullCalendar
    $('#calendar').fullCalendar({
        events: @json($events), // Ensure this variable is populated correctly
        eventRender: function(event, element) {
            element.find('.fc-title').append('<br>' + event.leader);
        }
    });

    // Show calendar modal on button click
    $('#show-calendar-btn').click(function() {
        $('#calendar-modal').modal('show'); // Ensure this matches your modal ID
    });
});
</script>
</body>

</html>
