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
        .hover-border {
        transition: box-shadow 0.3s ease-in-out, transform 0.2s ease-in-out;
        border: 2px solid transparent; /* Maintain size to prevent shifting */
    }

    .hover-border:hover {
        border-color:rgb(206, 207, 208); /* Border color on hover */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Soft shadow effect */
        transform: translateY(-3px); /* Slight lift effect */
    }
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
          <ul>
            <hr style="width: 100%; border: 1px solid white; margin-top: -4px">

            <li><a href="{{ route('research_staff.dashboard') }}">Dashboard</a></li>

            <hr style="width: 100%; border: 1px solid white; margin-top: 5px">

            {{-- Accordion --}}
            <div class="accordion accordion-flush" id="accordionFlushExample">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Research Management
              </button>
              <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <li><a href="{{ route('research.create', ['type' => 'program']) }}">Add New Research</a></li>
                  <li><a href="{{ route('research.index') }}">View All Research</a></li>
                </div>
              </div>

              <hr style="width: 100%; border: 1px solid white; margin-top: 5px">

              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                Researcher Management
              </button>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <li><a href="{{ route('researchers.create') }}">Add New Researcher</a></li>
                  <li><a href="{{ route('researchers.index') }}">View Researchers</a></li>
                </div>
              </div>

              <hr style="width: 100%; border: 1px solid white; margin-top: 10px;">
              
              
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Monitoring Management
              </button>
              <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <li><a href="/research-files">Research Files</a></li>
                  <li><a href="#" id="show-calendar-btn">Show Deadlines Calendar</a></li>
                </div>
              </div>
            </div>
            {{-- End of Accordion --}}

            <hr style="width: 100%; border: 1px solid white; margin-top: 10px;">
            <li><a href="#" data-bs-toggle="modal" data-bs-target="#updateCredentialsModal">Update Credentials</a></li>

            <hr style="width: 100%; border: 1px solid white; margin-top: 5px;">
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
                    <div class="col-md-4">
        <div class="info-2 rounded-2 p-2  hover-border" style="background-color: #FFFFAD;" data-bs-toggle="modal" data-bs-target="#ongoingProjectsModal">
            <div class="info-body-2 p-2">
                <h4 style="font-size: 1.5rem; color: #3b444b;">{{ $ongoingCount }}</h4>
                <p style="color: #3b444b;">Ongoing Projects</p>
            </div>
        </div>
    </div>

    <!-- Overdue Projects Card -->
    <div class="col-md-4">
        <div class="info-2 rounded-2 p-2  hover-border" style="background-color: #87CEFA;" data-bs-toggle="modal" data-bs-target="#overdueResearchesModal">
            <div class="info-body-3 p-2">
                <h4 style="font-size: 1.5rem; color: #3b444b;">{{ $overdueCount }}</h4>
                <p style="color: #3b444b;">Overdue Projects</p>
            </div>
        </div>
    </div>

    <!-- Finished Researches Card -->
    <div class="col-md-4">
        <div class="info-2 rounded-2 p-2  hover-border" style="background-color: #DCB2B9;" data-bs-toggle="modal" data-bs-target="#finishedResearchesModal">
            <div class="info-body-3 p-2">
                <h4 style="font-size: 1.5rem; color: #3b444b;">{{ $finishedCount }}</h4>
                <p style="color: #3b444b;">Finished Researches</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Total Researchers -->
<div class="modal fade" id="totalResearchersModal" tabindex="-1" aria-labelledby="totalResearchersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="totalResearchersModalLabel"  style="color: #3b444b;;">Active Researchers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                   
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                        <thead class="table-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Researcher Name</th>
                                    <th>Research Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($totalResearchersDetails as $index => $researcher)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <i class="fas fa-user text-primary me-2"></i> 
                                            {{ $researcher->name }}
                                        </td>
                                        <td class="text-center">
                                            <span >
                                                {{ $researcher->researches_count }} 
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ongoingProjectsModal" tabindex="-1" aria-labelledby="ongoingProjectsModalLabel" aria-hidden="true">
<div class="modal-dialog modal-xl"> 
        <div class="modal-content">
            <div class="modal-header">
            <h5 style="color: #3b444b;">Ongoing Research Projects</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    @if($ongoingResearches->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            <i class="fas fa-exclamation-circle"></i> No ongoing projects found for the selected filters.
        </div>
    @else
        <div class="container">
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Research Title</th>
                            <th>Research Leader</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ongoingResearches as $index => $research)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $research->title }}</td>
                                <td>
                                    
                                    @if($research->researchers->isEmpty())
                                        <span class="text-muted">No leader assigned</span>
                                    @else
                                     
                                        {{ $research->researchers->first()->name }}
                                    @endif
                                 
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
        </div>
    </div>
</div>
<div class="modal fade" id="overdueResearchesModal" tabindex="-1" aria-labelledby="overdueResearchesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="overdueResearchesModalLabel" style="color: #3b444b;">Overdue Researches</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    @if($overdueResearches->isEmpty())
        <p class="text-muted">No overdue research found.</p>
    @else
        <!-- Table to display overdue research -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Research Title</th>
                        <th>Leader</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overdueResearches as $index => $research)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $research->title }}</td>
                            <td>
                                @if($research->researchers && $research->researchers->isNotEmpty())
                                    <!-- Show first researcher's name (leader) -->
                                    <i class="fas fa-user-tie text-primary"></i> {{ $research->researchers->first()->name }}
                                @else
                                    <span class="text-warning">No leader assigned</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($research->deadline)->format('M d, Y') }}</td>
                            <td class="text-center">
                                <!-- Display the status as text without a badge -->
                                <span class="text-danger">{{ $research->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
        </div>
    </div>
</div>
<div class="modal fade" id="finishedResearchesModal" tabindex="-1" aria-labelledby="finishedResearchesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="finishedResearchesModalLabel" style="color:black;">Finished Researches</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    @if($completedResearches->isEmpty())
        <p class="text-muted">No finished researches found.</p>
    @else
        <!-- Table to display completed research -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Research Title</th>
                        <th> Leader</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completedResearches as $index => $research)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $research->title }}</td>
                            <td>
                                @if($research->researchers && $research->researchers->isNotEmpty())
                                    <!-- Display all leaders -->
                                    @foreach ($research->researchers as $researcher)
                                        <div>{{ $researcher->name }}</div>
                                    @endforeach
                                @else
                                    <span class="text-warning">No leader assigned</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
        </div>
    </div>
</div>
                </div>
                   
                    <!-- Calendar Section -->

                </div>
                
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
                backgroundColor: ['#922220', '#818589'], // Green for active, red for inactive
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
            backgroundColor: ['#818589', '#922220'], // Yellow for On-Going, Green for Finished
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
