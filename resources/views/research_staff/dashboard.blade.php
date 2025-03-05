<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/staffDashboard.css') }}">
    <title>Research Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>
</head>

    <style>
    .small-chart {
        width: 150px;   /* Adjust the width */
        height: 150px;  /* Adjust the height */
    }
    #researcherActivityChart {
    max-width: 250px;  /* Adjust max width */
    max-height: 250px; /* Adjust max height */
    width: 100%;       /* Responsive to the container */
    height: auto;      /* Maintain aspect ratio */
}

.research-chart {
    max-width: 250px;  /* Adjust max width */
    max-height: 250px; /* Adjust max height */
    width: 100%;       /* Responsive to the container */
    height: auto;      /* Maintain aspect ratio */
}
</style>


<body>

    <div class="header">
        <button class="menu-btn" id="menuBtn">&#9776;</button>
    </div>

    <div class="wrapper container-fluid w-auto p-0 m-0">
        

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
                <li><a href="#" id="show-calendar-btn">Show Deadlines Calendar</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#updateCredentialsModal">Update Credentials</a></li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

            <!-- Main Content -->
            <!-- Main Content -->
            <div class="main-content container-fluid">
                <div class="box p-2 rounded-2 text-white" style="background-color: #818589;">
                    <h1 class="mb-4 fs-2" style="color: white; font-weight: bold;">Dashboard</h1>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('research_staff.dashboard') }}" class="row mb-4">
                        <div class="col-md-4">
                            <label for="school_year" class="form-label text-white" ><strong>School Year</strong></label>
                            <select name="school_year" id="school_year" class="form-select">
                                <option value="">All School Years</option>
                                @foreach($schoolYears as $schoolYear)
                                    <option value="{{ $schoolYear->school_year }}" {{ $schoolYearFilter == $schoolYear->school_year ? 'selected' : '' }}>{{ $schoolYear->school_year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="semester" class="form-label text-white" ><strong>Semester</strong></label>
                            <select name="semester" id="semester" class="form-select">
                                <option value="">All Semesters</option>
                                @foreach(['First Semester', 'Second Semester', 'Off Semester'] as $semester)
                                    <option value="{{ $semester }}" {{ $semesterFilter == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end mt-3">
                            <button type="submit" class="btn w-30 text-white w-100" style="background-color: #922220;">Filter</button>
                        </div>
                    </form>
                    


                    <div class="modal fade" id="updateCredentialsModal" tabindex="-1" aria-labelledby="updateCredentialsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateCredentialsModalLabel" style ="color: #52489f;">Update Research Staff Credentials</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('staff.updateCredentials') }}">
                                @csrf
                                <div class="mb-3">
                                <label for="email" class="form-label" style ="color: #52489f;">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->guard('research_staff')->user()->email) }}" required>
                                </div>
                                <div class="mb-3">
                                <label for="password" class="form-label" style ="color: #52489f;">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                <label for="password_confirmation" class="form-label" style ="color: #52489f;">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Credentials</button>
                            </form>
                        </div>
                        </div>
                    </div>
                    </div>

                <!-- Statistics Section --> 
                <div class="row mb-6 mt-4 g-2">
                    <!-- Total Researchers Card -->
                    <div class="col-md-6">
                    <div class="card">
                            <div class="card-body d-flex">
                                <div class="col-6 d-flex justify-content-center align-items-center"">
                                    <div>
                                        <h4 class="card-title" style="color: gray;">{{ $totalResearchers }}</h4>
                                        <p class="card-text" style="color: gray;"> Total Researchers</p>
                                    </div>
                                </div>
                                <!-- Doughnut Chart Canvas -->
                                <div class="col-6"><canvas id="researcherActivityChart"></canvas></div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Researches Card -->
                    <div class="col-md-6">
                    <div class="card">
                            <div class="card-body d-flex">
                                <!-- Left side for counts and text -->
                                <div class="col-6 d-flex justify-content-center align-items-center" style="color: gray;">
                                    <div>
                                        <h4 class="card-title">{{ $totalResearches }}</h4>
                                        <p class="card-text">Total Researches</p>
                                    </div>
                                </div>
                                <!-- Right side for status chart -->
                                <div class="col-6"><canvas id="statusChart" class='research-chart'></canvas></div>
                            </div>
                        </div>
                    </div>

                    <!-- Ongoing, Overdue, Finished Projects Cards -->
                    @foreach([['Ongoing Projects', $ongoingCount, '#64C4FF;'], ['Overdue Projects', $overdueCount, '#ff8282'], ['Finished Researches', $finishedCount, '#90EE91']] as [$title, $count, $color])
                    <div class='col-md-4 mt-4 mb-1'>
                        <div class='card text-white' style="background-color: {{ $color }}; ">
                            <div class='card-body'>
                                <h4 class='card-title' style="color: #3b444b;">{{ $count }}</h4><p class='card-text' style="color: #3b444b;">{{ $title }}</p>
                            </div> 
                        </div> 
                    </div> 
                    @endforeach
                </div>
                   
                    <!-- Calendar Section -->

                </div>
                
                <div id="calendar-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="calendarModalLabel">Research Deadlines Calendar</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="calendar"></div> <!-- Calendar will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

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

        // Initialize the calendar
    
    $(document).ready(function() {
        // Initialize the calendar inside the modal
        $('#calendar').fullCalendar({
            events: @json($events),  // Pass events from the server to FullCalendar
            eventRender: function(event, element) {
                // Optionally, add extra details to events
                element.find('.fc-title').append('<br>' + event.leader);
            }
        });

        // Show modal on button click
        $('#show-calendar-btn').click(function() {
            $('#calendar-modal').modal('show');
        });
    });

    </script>
    
    <script type="text/javascript">
    // Retrieve active and inactive researchers from the controller
    var activeResearchers = {{ $researcherActivityChartData['active'] }};
    var inactiveResearchers = {{ $researcherActivityChartData['inactive'] }};
    
    // Doughnut Chart Initialization
    var ctx = document.getElementById('researcherActivityChart').getContext('2d');
    var researcherActivityChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Active Researchers', 'Inactive Researchers'],
            datasets: [{
                label: 'Researcher Activity',
                data: [activeResearchers, inactiveResearchers],
                backgroundColor: ['#90EE91', '#ff8282'], // Green for active, red for inactive
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });


    var statusChartCtx = document.getElementById('statusChart').getContext('2d');
var statusChart = new Chart(statusChartCtx, {
    type: 'doughnut',
    data: {
        labels: ['On-Going Researches', 'Finished Researches'],
        datasets: [{
            label: 'Research Status',
            data: [{{ $ongoingCount }}, {{ $finishedCount }}], // On-Going and Finished counts
            backgroundColor: ['#FFB668', '#90EE91'], // Yellow for On-Going, Green for Finished
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw;
                    }
                }
            }
        }
    }
});
</script>




</body>
</html>
