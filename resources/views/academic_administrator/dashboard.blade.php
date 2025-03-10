<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Admin Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/admin/adminDash.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
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
                <li><a href="{{ route('academic_administrator.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('academic_administrator.manage_research') }}">Manage Research</a></li>
                <li><a href="{{ route('manage.researchers') }}">Manage Researchers</a></li>
                <li><a href="{{ route('school_years.viewUpdateschoolyear') }}">School Years</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#updateCredentialsModal"> Update Credentials </a></li> 
                <li class="nav-item">
    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#createStaffModal">
        Add New Staff
    </a>
</li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
            
        <!-- Main Content -->
        <div class="main-content container-fluid">
            <div class="box p-2 rounded-2 text-white h-auto" style="background-color: #818589;">
                <h1 class="fs-2" style="color: white; font-weight: bold;" >Academic Admin Dashboard</h1>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('academic_administrator.dashboard') }}" class="row mt-5 mb-4 p-2 g-2">
                    <div class="col-md-4">
                        <label for="school_year" class="form-label">School Year</label>
                        <select name="school_year" id="school_year" class="form-select">
                            <option value="">All School Years</option>
                            @foreach($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->school_year }}" 
                                    {{ $schoolYearFilter == $schoolYear->school_year ? 'selected' : '' }}>{{ $schoolYear->school_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" id="semester" class="form-select">
                            <option value="">All Semesters</option>
                            <option value="First Semester" {{ $semesterFilter == 'First Semester' ? 'selected' : '' }}>First Semester</option>
                            <option value="Second Semester" {{ $semesterFilter == 'Second Semester' ? 'selected' : '' }}>Second Semester</option>
                            <option value="Off Semester" {{ $semesterFilter == 'Off Semester' ? 'selected' : '' }}>Off Semester</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn w-100" style="background-color: #922220; color: white; border: none;" onmouseover="this.style.backgroundColor='#922220'" >Filter</button>
                    </div>
                    
                </form>


                {{-- <button type="button" class="btn " style="background-color: #922220; color: white; border: none;" data-bs-toggle="modal" data-bs-target="#updateCredentialsModal">
                    Update Credentials
                </button> --}}



                <div class="modal fade" id="updateCredentialsModal" tabindex="-1" aria-labelledby="updateCredentialsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateCredentialsModalLabel" style ="color:black;">Update Admin Credentials</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.updateCredentials') }}">
                                @csrf
                                <div class="mb-3">
                                <label for="email" class="form-label" style ="color:black;">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->guard('academic_administrator')->user()->email) }}" required>
                                </div>
                                <div class="mb-3">
                                <label for="password" class="form-label" style ="color:black;">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                <label for="password_confirmation" class="form-label" style ="color:black;">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Credentials</button>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="row g-2 mt-5 border-3 border-bottom pb-3 stats">
                    <div class="col-md-3">
                        <div class="info-2 rounded-2 p-2" style="background-color: #64C4FF;">
                            <div class="info-body-1 p-2">
                                <h4 style="font-size: 1.5rem; color: #3b444b;">{{ $totalResearchers }}</h4>
                                <p style="color: #3b444b;">Total Researchers</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="info-2 rounded-2 p-2" style="background-color: #FFB668;">
                            <div class="info-body-2 p-2">
                                <h4 style="font-size: 1.5rem; color: #3b444b;">{{ $ongoingCount }}</h4>
                                <p style="color: #3b444b;">Ongoing Projects</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-2 rounded-2 p-2" style="background-color: #ff8282;">
                            <div class="info-body-3 p-2">
                                <h4 style="font-size: 1.5rem; color: #3b444b;">{{ $overdueCount }}</h4>
                                <p style="color: #3b444b;">Overdue Projects</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="info-2 rounded-2 p-2" style="background-color: #90EE91;">
                            <div class="info-body-3 p-2">
                                <h4 style="font-size: 1.5rem; color: #3b444b;">{{ $finishedCount }}</h4>
                                <p style="color: #3b444b;">Finished Researches</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="row mt-4 border-3 border-bottom pb-3 charts ">
                    <div class="chart-section p-3">
                        <!-- Researches per Program Chart -->
                        <div class="chart-container ">
                            <h4 class="text-center" style="color: gray; font-weight: bold;">Researches Per Program</h4>
                            <div style="position: relative; width: 100%; height: auto;">
                                <canvas id="researchesPerProgramChart"></canvas>
                            </div>
                        </div>

                        <!-- Researches per SDGs Chart -->
                        <div class="chart-container">
                            <h4 class="text-center" style="color: gray; font-weight: bold;">Researches Per SDGs</h4>
                            <div style="position: relative; width: 100%; height: auto;">
                                <canvas id="researchesPerSDGsChart"></canvas>
                            </div>
                        </div>

                        <!-- Researches per DOST 6Ps Chart -->
                        <div class="chart-container">
                            <h4 class="text-center" style="color: gray; font-weight: bold;">Researches Per DOST 6Ps</h4>
                            <div style="position: relative; width: 100%; height: auto;">
                                <canvas id="researchesPer6PsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                
                
                <!-- Most and Least Researchers Section -->
                <!-- Most and Least Researchers Section -->
                <div class=" mt-4 h-auto researchers">
                    <h4 class="section-title text-white text-center">Most and Least Active Researchers</h4>
                    <div class="researchers-section d-flex justify-content-center gap-1">
                        
                        <!-- Most Researchers -->
                        <div class="card w-100 h-auto p-2 most-researchers">
                            <div class="card-body">
                                <h5 class="card-title text-center" style="color: gray;">Researchers with Most Researches</h5>
                                <div class="researcher-list text-center">
                                    @foreach($researchersWithMostResearches as $researcher)
                                        <div class="researcher-item  w-100">
                                            <img src="{{ asset('storage/' . $researcher->profile_picture) }}" alt="Profile" class="profile-img border border-black">
                                            <div class="researcher-name">{{ $researcher->name }}</div>
                                            <div class="researcher-research-count">{{ $researcher->researches_count }} researches</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Least Researchers -->
                        <div class="card w-100 h-auto p-2 least-researchers">
                            <div class="card-body">
                                <h5 class="card-title text-center" style="color: gray;">Researchers with Least Researches</h5>
                                <div class="researcher-list text-center ">
                                    @foreach($researchersWithLeastResearches as $researcher)
                                        <div class="researcher-item w-100">
                                            <img src="{{ asset('storage/' . $researcher->profile_picture) }}" alt="Profile" class="profile-img border border-black">
                                            <div class="researcher-name">{{ $researcher->name }}</div>
                                            <div class="researcher-research-count">{{ $researcher->researches_count }} researches</div>
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

    <!-- Staff Creation Modal -->
<div class="modal fade" id="createStaffModal" tabindex="-1" aria-labelledby="createStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStaffModalLabel">Create New Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="createStaffForm" action="{{ route('staff.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Flash message for success -->
@if(session('success'))
<div class="alert alert-success mt-3">
    {{ session('success') }}
</div>
@endif

<!-- Flash message for errors -->
@if ($errors->any())
<div class="alert alert-danger mt-3">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

   <!-- Script for Research per Program --> 
   <script>
  // Pass the PHP $chartData array to JavaScript by encoding it as JSON
  const chartData = {!! json_encode($chartData) !!};

  // Extract data for the chart
  const { labels, undergraduateValues, graduateValues } = {
    labels: Object.keys(chartData), // Program names as labels
    undergraduateValues: Object.values(chartData).map(item => item.Undergraduate), // Counts of undergraduate researches
    graduateValues: Object.values(chartData).map(item => item.Graduate) // Counts of graduate researches
  };

  const maxResearches = Math.max(
    Math.max(...undergraduateValues),
    Math.max(...graduateValues)
  );
  const maxValueWithAllowance = maxResearches + 2.5;

  const datasets = [
    {
      label: 'Undergraduate Researches',
      data: undergraduateValues,
      backgroundColor: '#6a5acd',
      borderColor: '#6a5acd',
      borderWidth: 1
    },
    {
      label: 'Graduate Researches',
      data: graduateValues,
      backgroundColor: '#ff8282',
      borderColor: '#FF6347',
      borderWidth: 1
    }
  ];

  const ctx1 = document.getElementById('researchesPerProgramChart').getContext('2d');
  
  new Chart(ctx1, {
    type: 'bar',
    data: {
      labels,
      datasets
    },
    options: {
      responsive: true,
      indexAxis: 'y',  // Horizontal bar chart
      scales: {
        x: {
          beginAtZero: true,
          max: maxValueWithAllowance
        },
        y: {}
      },
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: (tooltipItem) => `${tooltipItem.raw} researches`
          }
        }
      }
    }
  });
</script>
<!--Script for SDGs -->
<script>
const sdgsLabels = {!! json_encode($sdgNames) !!}; // SDG names
const sdgsData = {!! json_encode($sdgResearches) !!}; // Research counts for each SDG

const ctx2 = document.getElementById('researchesPerSDGsChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: sdgsLabels,
        datasets: [{
            label: 'Number of Researches per SDG',
            data: sdgsData,
            backgroundColor: '#64C4FF',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y', // Horizontal bars
        scales: {
            x: {
                beginAtZero: true,
                max: Math.max(...sdgsData) + 2.5 // Set max value with allowance
            },
            y: {}
        },
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.raw + ' researches'; // Show number of researches in the tooltip
                    }
                }
            }
        }
    }
});
</script>

 <!-- Script for 6ps --> 
  <script>
    const ctx3 = document.getElementById('researchesPer6PsChart').getContext('2d');

// Get the DOST 6P data from the backend
const dost6pLabels = {!! json_encode($dost6pNames) !!};
const dost6pData = {!! json_encode($dost6pResearches) !!};

// Calculate the max value for the x-axis, with a max allowance of 2.5
const maxResearches6Ps = Math.max(...dost6pData);
const maxValueWithAllowance6Ps = maxResearches6Ps + 2.5;

// Create the DOST 6P Chart
new Chart(ctx3, {
    type: 'bar',  // Bar chart
    data: {
        labels: dost6pLabels,  // DOST 6P names
        datasets: [{
            label: 'Number of Researches per DOST 6P',
            data: dost6pData,  // Research counts for each DOST 6P
            backgroundColor: '#ff8282',  // Light color for bars
            borderColor: '#ff8282',  // Border color
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',  // Horizontal bars
        scales: {
            x: {
                beginAtZero: true,  // Start the x-axis at zero
                max: maxValueWithAllowance6Ps  // Set max value with allowance
            },
            y: {
                // Automatically adjust the y-axis labels
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.raw + ' researches';  // Show number of researches in the tooltip
                    }
                }
            }
        }
    }
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
