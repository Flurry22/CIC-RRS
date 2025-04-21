<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/cic-logo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>{{ $researcher->name }}'s Profile</title>
    <style>
        body {
            background-color: #f4f4f4;
        }
        .profile-header {
            background-color: #818589;
            color: white;
            border-radius: 10px;
        }
        .profile-header img {
            object-fit: cover;
            height: 150px;
            width: 150px;
        }
        .btn-custom {
            background-color: #922220;
            color: white;
        }
        .card {
            border: none;
            border-radius: 10px;
            margin-top: 20px;
        }
        .research-status-badge {
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .status-ongoing {
            background-color: #922220;
            color: white;
        }
        .status-completed {
            background-color:rgb(46, 154, 39);
            color: white;
        }
       
    </style>
</head>
<body>

    <div class="container mt-5 p-4 text-white rounded-2 profile-header">
        <div class="text-left mb-4">
            <a href="{{ route('researchers.search') }}" class="btn btn-custom">Back to Search</a>
        </div>

        <div class="text-center mb-4">
            <h1>{{ $researcher->name }}'s Profile</h1>
        </div>

        <hr style="border: 2px solid white;">
        
        <!-- Profile Picture and Biography -->
        <div class="row mt-4">
            <div class="col-md-3 text-center">
                <img src="{{ $researcher->profile_picture ? asset('storage/' . $researcher->profile_picture) : asset('img/default-avatar.png') }}" 
                    class="img-fluid rounded-circle border" alt="Profile Picture">
            </div>
            <div class="col-md-9">
                <h2>Biography</h2>
                <p>{{ $researcher->bio ?? 'No biography available.' }}</p>

                <hr style="border: 2px solid white;">
                
                <h2>Specializations</h2>
                <p>{{ implode(', ', $skills) }}</p>

                <hr style="border: 2px solid white;">
                
                <h2>Contact Information</h2>
                <p>Email: <a href="mailto:{{ $researcher->email }}" style="color: white;">{{ $researcher->email }}</a></p>
            </div>
        </div>

        <hr style="border: 2px solid white;">

        <!-- Research Counts -->
        <div class="mt-4 text-center">
            <h2>Research Counts</h2>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card p-3 bg-light text-dark">
                        <p class="fs-4">{{ $researchCount }}</p>
                        <p>Total Researches</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 bg-light text-dark">
                        <p class="fs-4">{{ $finishedResearchCount }}</p>
                        <p>Finished Researches</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 bg-light text-dark">
                        <p class="fs-4">{{ $ongoingResearchCount }}</p>
                        <p>Ongoing Researches</p>
                    </div>
                </div>
            </div>
        </div>

        <hr style="border: 2px solid white;">

        <!-- Research Statuses -->
        <h4 class="mb-3 text-center">Research Statuses</h4>
        <ul class="list-group">
            @foreach ($researcher->researches as $research)
            <li class="list-group-item d-flex align-items-center p-2" style="font-size: 0.875rem;">
            <span class="flex-grow-1"><strong>{{ $research->title }}</strong></span>
                    <span class="mx-3 text-center" style="min-width: 150px;">
            <small>Approved on:</small><br>
            <strong style="color: black;">{{ $research->approved_date }}</strong>
        </span>
                    <span class="research-status-badge 
                        @if($research->status == 'On-Going') status-ongoing 
                        @elseif($research->status == 'Finished') status-completed 
                        @else bg-light @endif">
                        {{ $research->status }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
