<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/cic-logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('stylesheet/researcher/searchResearcher.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Search</title>
    <style>
        .pagination .page-item .page-link {
            background-color: #7393B3;
            color: white;
            border-color: #7393B3;
        }

        .pagination .page-item.active .page-link {
            background-color: blue;
            color: white;
            border-color: blue;
        }

        .pagination .page-item.active .page-link:hover {
            background-color: blue;
            color: white;
            border-color: blue;
        }

        .pagination .page-item .page-link:hover {
            background-color: #922220;
            color: white;
            border-color: #922220;
        }

    </style>
</head>
<body>

    <div class="wrapper container-fluid w-auto p-0 m-0">
        <!-- Header -->
        <div class="header">
            <button class="menu-btn" id="menuBtn">&#9776;</button>
        </div>

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
        <div class="main-content container-fluid";>
            <div class="box text-white p-3 border rounded" style="background-color: #818589;">
                <div class="text-center mb-4">
                    <h1 class="fs-2" style="color: white;">Search</h1>
                </div>
                <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <form action="{{ route('researchers.search') }}" method="GET" class="mb-4">
                         <!-- Dropdown to select search type -->
                         <label for="search_type" class="visually-hidden">Select Search Type</label>
                        <select name="search_type" id="search_type" class="form-select mb-2" aria-label="Search type">
                            <option value="researchers" {{ $searchType === 'researchers' ? 'selected' : '' }}>Researchers</option>
                            <option value="researches" {{ $searchType === 'researches' ? 'selected' : '' }}>Researches</option>
                        </select>
                        <label for="search" class="visually-hidden">Search by name or title</label>
                        <input 
                            id="search"
                            type="text" 
                            name="search" 
                            class="form-control mb-2" 
                            placeholder="Search..." 
                            value="{{ old('search', $search ?? '') }}"
                            aria-label="Search">

                        <!-- Dropdown to select search type -->
                       

                        <button type="submit" class="btn" style="background-color: #922220; color: white;">Search</button>
                    </form>

                    <!-- Display form validation errors -->
                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

                <!-- Display Search Results -->
                <div>
                    @if(isset($researchers))
                        <h2 class="text-center mb-4 fs-2" style="color: white;">All Researchers</h2>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 researcher-cards">
                            @foreach($researchers as $researcher)
                                <div class="col ">
                                    <a href="{{ route('researcher.profile', $researcher->id) }}" class="text-decoration-none">
                                        <div class="card h-100 shadow-sm" style="border: 1.5px solid black;">
                                            <img src="{{ $researcher->profile_picture ? asset('storage/' . $researcher->profile_picture) : asset('img/default-avatar.png') }}" 
                                                class="card-img-top profile-img mx-auto d-block mt-2 rounded-circle h-50 w-25" style="border: 1px solid black;" alt="Profile Picture">
                                            <div class="card-body">
                                                <h5 class="card-title text-center" style="color: black;">{{ $researcher->name }}</h5>
                                                <p class="card-text text-center" style="color: gray;">
                                                    {{ Str::limit($researcher->bio ?? 'No bio available.', 100) }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $researchers->links('pagination::bootstrap-4') }}
                        </div>
                    @elseif(isset($researches))
                    <div class="row g-1">
    @foreach($researches as $research)
        <!-- Each card will take full width of the row (col-12) -->
        <div class="col-12">
            <div class="card shadow-sm w-100" style="border: 2px solid black; border-radius: 10px; margin-bottom: 20px;">
                <!-- Card Content -->
                <div class="card-body" style="padding: 15px; display: flex; flex-direction: column;">
    <!-- Title and Status in the Same Row -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h5 class="card-title" style="color: black; font-size: 1.25rem; margin-bottom: 0;">
            {{ $research->title }}
        </h5>
        <p class="card-text" style="color: #922820; font-size: 1rem; margin-bottom: 0;">
            {{ $research->status }}
        </p>
    </div>

    <!-- Files Section in the Same Row -->
    <div class="mt-3" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
        <h6 style="color: black; width: 100%; margin-bottom: 5px;">Related Files:</h6>
        <ul class="list-unstyled d-flex flex-wrap" style="padding-left: 0; margin-bottom: 0;">
            @if($research->certificate_of_utilization)
                <li class="mx-2">
                    <a href="{{ asset('storage/' . $research->certificate_of_utilization) }}" target="_blank" class="text-decoration-none" style="color: black;">
                        Certificate of Utilization
                    </a>
                </li>
            @endif

            @if($research->approved_file)
                <li class="mx-2">
                    <a href="{{ asset('storage/' . $research->approved_file) }}" target="_blank" class="text-decoration-none" style="color: black;">
                        Approved Proposal File
                    </a>
                </li>
            @endif

            @if($research->special_order)
                <li class="mx-2">
                    <a href="{{ asset('storage/' . $research->special_order) }}" target="_blank" class="text-decoration-none" style="color: black;">
                        Special Order
                    </a>
                </li>
            @endif

            @if($research->terminal_file)
                <li class="mx-2">
                    <a href="{{ asset('storage/' . $research->terminal_file) }}" target="_blank" class="text-decoration-none" style="color: black; text-decoration:underline;">
                        Terminal File
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
            </div>
        </div>
    @endforeach
</div>
                        <div class="d-flex justify-content-center mt-4 bg-alert">
                            {{ $researches->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional for Interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menuBtn');
        const closeBtn = document.getElementById('closeBtn');
        menuBtn.addEventListener('click', () => sidebar.classList.add('active'));
        closeBtn.addEventListener('click', () => sidebar.classList.remove('active'));

    </script>
</body>
</html>
