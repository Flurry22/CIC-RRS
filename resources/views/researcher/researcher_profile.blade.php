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
            background-color: #eaeaea;;
        }
    </style>
</head>
<body>



    <div class="container mt-5 p-3 text-white rounded-2" style="background-color:  #818589;">
    <div class="text-left mt-4">
            <a href="{{ route('researchers.search') }}" class="btn" style="background-color: #922220; color: white;">Back to Search</a>
        </div>
        <!-- Researcher Profile Header -->
        <div class="text-center mb-4">
            <h1 style="color: white;">{{ $researcher->name }}'s Profile</h1>
        </div>

        <hr style="width: 100%; border: 2px solid white;">
        <!-- Profile Details -->
        <div class="row mt-5">
            <div class="col-md-12 d-flex justify-content-center align-items-center">
                <!-- Profile Picture -->
                <img src="{{ $researcher->profile_picture ? asset('storage/' . $researcher->profile_picture) : asset('img/default-avatar.png') }}" 
                    class="img-fluid rounded-circle border border-black" alt="Profile Picture" style="object-fit: cover; height: 150px;">
            </div>
            <div class="col-md-12 d-flex justify-content-center align-items-center flex-column mt-3">
                <h2 style="color: white;">Biography</h2>
                <p style="color: white;" >{{ $researcher->bio ?? 'No biography available.' }}</p>
                
                <hr style="width: 100%; border: 2px solid  white;">
                <!-- Skills -->
                <h2 style="color: white;">Specializations</h2>
                <p style="color: white;">{{ implode(', ', $skills) }}</p>

                <hr class="w-50 border-3">
                <!-- Contact Information -->
                <h2 style="color: white;">Contact Information</h2>
                <p style="color: white;">Email: <a style="color: white;" href="mailto:{{ $researcher->email }}">{{ $researcher->email }}</a></p>
            </div>
        </div>

        <hr style="width: 100%; border: 2px solid white;">

        <div class="mt-4 d-flex flex-column align-items-center">
    <h2 class="mb-4" style="color: white;">Research Counts</h2>
    <div class="row w-100 justify-content-center text-center">
        <!-- Total Researches -->
        <div class="col-md-4">
            <p class="fs-4" style="color: white">{{ $researchCount }}</p>
            <p class="fs-6" style="color: white;">Total Researches</p>
        </div>
        <!-- Finished Researches -->
        <div class="col-md-4">
            <p class="fs-4" style="color: white;">{{ $finishedResearchCount }}</p>
            <p class="fs-6" style="color: white;">Finished Researches</p>
        </div>
        <!-- Ongoing Researches -->
        <div class="col-md-4">
            <p class="fs-4" style="color: white;">{{ $ongoingResearchCount }}</p>
            <p class="fs-6" style="color: white;">Ongoing Researches</p>
        </div>
    </div>
</div>

        <hr style="width: 100%; border: 2px solid white;">

        <div class="mt-2">
    <!-- Research Statuses -->
    <h4 class="mb-3 text-center" style="color: white; font-size: 1.25rem;">Research Statuses:</h4>
    <ul class="list-group mt-2" style="padding-left: 0;">
        @foreach ($researcher->researches as $research)
        <li class="list-group-item d-flex align-items-center p-2" style="font-size: 0.875rem;">
            <!-- Research Title (flex-grow-1 to take up available space) -->
            <span class="flex-grow-1"><strong style="color: black;">{{ $research->title }}</strong></span>

            <!-- Approved Date (Centered) -->
            <span class="mx-3 text-center" style="min-width: 700px;">
                <small>Approved on:</small><br>
                <strong style="color: black;">{{ $research->approved_date }}</strong>
            </span>

            <!-- Status Badge -->
            <span class="badge 
                        @if($research->status == 'Ongoing')  
                        @elseif($research->status == 'Completed')
                        @elseif($research->status == 'Pending')  
                        @else bg-light  @endif" 
                        style="font-size: 0.75rem; color: black">
                {{ $research->status }}
            </span>
        </li>
        @endforeach
    </ul>
</div>

        <hr class="w-100 border-3">
        <!-- Back Button -->
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
