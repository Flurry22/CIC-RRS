<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/researcher/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>



    <!-- Header -->
    
    

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
            <hr class="w-100 border-3">
            <ul>
                <li><a href="{{ route('researcher.dashboard', ['id' => $researcher->id]) }}">Dashboard</a></li>
                <li><a href="{{ route('researchers.search') }}">Researchers & Researches</a></li>
                <li><a href="{{ route('researcher.settings.edit') }}">Settings</a></li>
                <li><a href="{{ route('researcher.files.index') }}">Research Files</a></li>
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
                                <h5 class="text-black">Specializations</h5>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($skills as $skill)
                                        @if (!empty($skill))
                                            <span class="badge bg-danger">{{ $skill }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <hr class="my-3 border-danger" style="width: 100%;">
                            <div>
                                <h5 class="text-black">Programs</h5>
                                <ul class="list-unstyled text-black">
                                    @forelse ($programs as $program)
                                        <li>{{ $program->name }}</li>
                                    @empty
                                        <li>No programs found.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-10 col-lg-12 mt-3 rounded p-2 rrl" style="background-color: #F2F9FF; width: 98.5%; height: auto;">
                        <h5 class="text-black"> Researches Led</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($leadingResearches as $research)
                                <div class="research-item text-black">
                                   - {{ $research->title }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-md-10 col-lg-12 mt-5 rounded p-3 rrp" style="background-color: #F2F9FF; width: 98.5%; height: auto;">
                        <h5 class="text-black"> Researches Participated</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($participatingResearches as $research)
                                <div class="research-item bg-white text-black rounded-1 w-100 mt-2" style="height: 5vh; text-indent: 10px; border: 1px solid black;">
                                  -  {{ $research->title }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-md-10 col-lg-12 mt-5 rounded-1 p-2 text-black insights" style="background-color: #F2F9FF; width: 98.5%; height: auto;">
                        <h5 class="text-black">Research Insights</h5>
                        <div class="row g-3 ">
                            <div class="col-md-6 text-center">
                                <h6>Status Breakdown</h6>
                                <div class="chart-container">
                                    <canvas id="statusChart"></canvas>
                                </div>
                                <p><strong>Finished:</strong> {{ $finishedResearchCount }}</p>
                                <p><strong>Ongoing:</strong> {{ $ongoingResearchCount }}</p>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    backgroundColor: ['#90EE91', '#FFB668'],
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
                    backgroundColor: ['#ff8282', '#64C4FF'],
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
</body>
</html>
