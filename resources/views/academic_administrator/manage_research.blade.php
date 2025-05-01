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
                                <option value="overdue" {{ old('status', $statusFilter) === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                
                            </select>
                        </form>

                        {{-- <button id="show-calendar-btn" class="btn" style="background-color: #922220; color: white;">Show Deadlines Calendar</button> --}}
                    </div>
                    <!-- Row for Analytics and Calendar -->
                    <div class="row mt-4 pb-3">
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
                        <div class="container mt-3 p-4 " style="background-color: white; border-radius: 10px; max-width: 98%; padding: 10px; margin: auto;">
                            <div class="col-12 col-md-10 col-lg-12 mt-3 rounded p-2 rrl" style="width: 98.5%; height: auto;">
                                <!-- Toggle Buttons -->
                                <div class="d-flex gap-4 align-items-center mt-3 mb-2">
                                <h5 id="tabLed" class="tab-header active-tab" style="cursor: pointer; margin-bottom: 0;">Undergraduate Research Projects</h5>
                                <h5 id="tabParticipated" class="tab-header" style="cursor: pointer; margin-bottom: 0;">Graduate Research Projects</h5>
                            </div>
                            
                            <div id="researchLedSection" class="mt-3">
                                <!-- Table for Led Researches -->
                                <table id="ledResearchesTable" class="table table-bordered table-striped table-hover">
                                    <thead style="background-color: #e9ecef;">
                                        <tr>
                                            <th>Title</th>
                                            <th>Leader</th>
                                            <th>Status</th>
                                            <th>Program</th>
                                            <th>Deadline</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($undergraduateResearches as $key => $research)
                                            <tr>
                                                <td><strong> {{ $research->title }}</strong></td>
                                                <td>{{ $research->leader->name ?? 'N/A' }}</td>
                                                <td>
  <span class="badge text-white" style="background-color: {{ $research->status === 'On-Going' ? '#922220' : '#118B50' }}">
    {{ $research->status }}
  </span>
</td>
                                                <td>
																									@if($research->programs->isNotEmpty())
                                                    @foreach($research->programs as $program)
                                                        {{ $program->name }}@if(!$loop->last), @endif
                                                    @endforeach
																									@else
																											N/A
																									@endif
																								</td>
                                                <td>{{ \Carbon\Carbon::parse($research->deadline)->format('d-m-Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        
                                <!-- Button to toggle view -->
                                <div class="text-center mt-3">
                                    <button id="toggleLedBtn" class="btn" style="background-color:#922220; color:white;">Show All</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Graduate Research Projects -->
												<div id="researchParticipatedSection" class="mt-3" style="display: none;">
													<!-- Table for Participated Researches -->
													<table id="participatedResearchesTable" class="table table-bordered table-striped table-hover">
															<thead style="background-color: #e9ecef;">
																	<tr>
																		<th>Title</th>
																		<th>Leader</th>
																		<th>Status</th>
																		<th>Program</th>
																		<th>Deadline</th>
																	</tr>
															</thead>
															<tbody>
																	@foreach ($graduateResearches as $key => $research)
																			<tr>
																					<td><strong>{{ $research->title }}</strong></td>
																					<td>{{ $research->leader->name ?? 'N/A' }}</td>
																					<td>
  <span class="badge text-white" style="background-color: {{ $research->status === 'On-Going' ? '#922220' : '#118B50' }}">
    {{ $research->status }}
  </span>
</td>
																					<td>
																						@if($research->programs->isNotEmpty())
																						@foreach($research->programs as $program)
																								{{ $program->name }}@if(!$loop->last), @endif
																						@endforeach
																						@else
																								N/A
																						@endif
																					</td>
																					<td>{{ \Carbon\Carbon::parse($research->deadline)->format('F d, Y') }}</td>
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
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleBtn = document.getElementById("toggleBtn");
            const researchItems = document.querySelectorAll(".research-item");
            const itemsToShow = 3;
            let expanded = false;
    
            function updateVisibility() {
                researchItems.forEach((item, index) => {
                    if (expanded || index  < itemsToShow) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });
    
                toggleBtn.textContent = expanded ? "Show Less" : "Show All";
            }
    
            toggleBtn.addEventListener("click", function () {
                expanded = !expanded;
                updateVisibility();
            });
    
            updateVisibility(); // Ensure only 3 items are shown at first
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleBtn = document.getElementById("showBtn");
            const researchItems = document.querySelectorAll(".research-grad");
            const itemsToShow = 3;
            let expanded = false;

            function updateVisibility() {
                researchItems.forEach((item, index) => {
                    if (expanded || index  < itemsToShow) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });

                toggleBtn.textContent = expanded ? "Show Less" : "Show All";
            }

            toggleBtn.addEventListener("click", function () {
                expanded = !expanded;
                updateVisibility();
            });

            updateVisibility(); // Ensure only 3 items are shown at first
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Chart 1: Research Distribution
            const ctx1 = document.getElementById('researchDistributionChart').getContext('2d');
            const data1 = {
                labels: ['Undergraduate Programs', 'Graduate Programs'],
                datasets: [{
                    label: 'Number of Researches',
                    data: [{{ $undergraduateResearches->count() }}, {{ $graduateResearches->count() }}],
                    backgroundColor: ['#818589', '#922220'],
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
                    backgroundColor: [' #64C4FF', '#818589', '#922220'],
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
