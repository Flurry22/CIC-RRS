<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Researchers</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('stylesheet/admin/manageResearchers.css')}}">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for the profile picture */
        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #52489f; /* Rounded border frame */
        }

        /* Centering text below the profile picture */
        .card-body {
            text-align: center;
        }

        .card-title,
        .card-text {
            color: #4c2f6f;
        }

        .card-title {
            font-weight: bold;
        }

        /* Styling the total and ongoing counts in the same row */
        .projects-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }

        .projects-row p {
            margin-bottom: 0;
        }

        .projects-label {
            color: #52489f;
        }

        .badge-active {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #28a745;  /* Green color */
            color: white;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: bold;
        }
        .badge-inactive {
    position: absolute;  /* Positioning to match the active badge */
    top: 10px;           /* Align vertically with the active badge */
    right: 10px;         /* Align horizontally with the active badge */
    background-color: #dc3545;  /* Red color for inactive */
    color: white;
    padding: 5px 10px;
    border-radius: 12px; /* Same border radius for consistency */
    font-size: 14px;     /* Same font size for consistency */
    font-weight: bold;   /* Same font weight for consistency */
}
    </style>
</head>

<body>

    <div class="wrapper container-fluid w-auto p-0 m-0">

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
                    <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
        </div>

        <div class="main-content container-fluid">
            <div class="box p-2 rounded-2 text-white" style="background-color: #818589;">
                <h1 class=" fs-2" style="color: white; font-weight: bold;">Manage Researchers</h1>

                <div class="alert alert-info  mt-4" style="background-color: #f5f3f9; color: black; border: 0.5px solid black;">
                    <strong>{{ $activeResearchers }} Active Researchers</strong> out of <strong>{{ $totalResearchers }}</strong> total researchers.
                </div>

            
                <div class="row">
                    @foreach($researchers as $researcher)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-2" style="border: 0.5px solid black;">
                            <img class="card-img-top profile-img mx-auto d-block mt-2 " style="border: 2px solid black;" src="{{ $researcher->profile_picture ? Storage::url($researcher->profile_picture) : asset('img/default-profile.png') }}" alt="Profile Picture">
                            <div class="card-body">
                            
        @if($researcher->researches->where('status', 'On-Going')->isNotEmpty())
            <span class="badge badge-active">Active</span>
        @else
            <span class="badge badge-inactive" style="background-color: #922220;">Inactive</span>
        @endif
                                <h5 class="card-title" style="color: black;">{{ $researcher->name }}</h5>
                                <p class="card-text" style="color: gray;">{{ $researcher->position }}</p>

                                <!-- Total Projects and Ongoing Projects in the same row -->
                                <div class="projects-row">
                                    <p><span class="projects-label" style="color: black;">Total Projects:</span> {{ $researcher->researches->count() }}</p>
                                    <p><span class="projects-label" style="color: black;">Ongoing Projects:</span> 
                                        {{ $researcher->researches->filter(function($research) {
                                            return $research->status === 'Ongoing';
                                        })->count() }}
                                    </p>
                                </div>

                                <p class="card-text" style="color: gray;">
                                    @if(is_array($researcher->skills))
                                        {{ implode(', ', $researcher->skills) }}
                                    @elseif($researcher->skills)
                                        {{ $researcher->skills }}
                                    @else
                                        No skills available
                                    @endif
                                </p>
                                <p class="card-text" style="color: #888;">
                                    <small>{{ $researcher->email }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <!-- Render Pagination Links -->
                    {{ $researchers->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
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
