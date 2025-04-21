<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Researcher Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: white;
            color: black;
            padding: 20px;
        }
        hr {
            border: 2px solid black;
        }
        .card {
            background-color: #f8f9fa;
            color: black;
            border: 1px solid #ddd;
        }
        .card h5 {
            font-size: 1rem;
            font-weight: 600;
        }
        .card .display-6 {
            font-weight: bold;
        }
        .table thead {
            background-color: #f0f0f0;
            color: black;
        }
        .table tbody tr {
            background-color: white;
            color: black;
        }
        .table a {
            color: #0d6efd;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 10px;
            
        }
        .buttons a {
            width: 20%;
            font-size: 14px;
            padding: 8px;
        }
        /* A4 paper border */
        .container {
            border: 2px solid black;
            padding: 20px;
            max-width: 40cm;  /* A4 width */
            min-height: 29.7cm; /* A4 height */
            margin: 0 auto;
            background-color: white;
        }
    </style>
</head>
<body>
<div class="buttons">
<a href="{{ route('researcher.dashboard', ['id' => $researcher->id]) }}" class="btn btn-secondary">Back to Dashboard</a>
<a href="{{ route('researcher.dashboard.report.pdf', ['id' => $researcher->id, 'status' => request('status'), 'role' => request('role'), 'year' => request('year')]) }}" class="btn btn-primary" style="background-color: #922220; color:white;">Download PDF</a>


    </div>
<div class="container">
    <div class="text-center">
        <img src="{{ asset('img/cic-logo.png') }}" alt="CIC Logo" style="width: 100px; height: 100px;">
        <p class="mt-2 fw-bold">College of Information and Computing</p>
        <p class="mt-2 fw-bold">Research Report Preview</p>
    </div>

    <!-- Report Info -->
    <div class="mt-5 text-center">
        <span style="margin-left: 50px;">Date Generated: {{ now()->format('F d, Y') }}</span>
    </div>

  
   

    <!-- Profile Info -->
    <div class="row mt-4">
        <div class="col-md-3 text-center">
            <img src="{{ $researcher->profile_picture ? asset('storage/' . $researcher->profile_picture) : asset('img/default-avatar.png') }}" 
                class="img-fluid rounded-circle border" alt="Profile Picture">
            <div class="position" style="margin-top: 10px; font-weight: bold; font-size: 14px;">
                {{ $researcher->position ?? 'No position available.' }}
            </div>
        </div>
        <div class="col-md-9">
            <h2>Biography</h2>
            <p>{{ $researcher->bio ?? 'No biography available.' }}</p>

            <hr>

            <h2>Specializations</h2>
            <p>{{ implode(', ', $skills) }}</p>

            <hr>

            <h2>Contact Information</h2>
            <p>Email: <a href="mailto:{{ $researcher->email }}" style="color: black;">{{ $researcher->email }}</a></p>
        </div>
    </div>

    <div class="row text-center mb-4 mt-5">
        <div class="col-md-4 mb-3">
            <div class="card p-3 shadow-sm rounded">
                <h5>Ongoing Research</h5>
                <div class="display-6">{{ $ongoingCount }}</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-3 shadow-sm rounded">
                <h5>Finished Research</h5>
                <div class="display-6">{{ $finishedCount }}</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-3 shadow-sm rounded">
                <h5>Total Research</h5>
                <div class="display-6">{{ $totalCount }}</div>
            </div>
        </div>
    </div>

    <!-- All Researches -->
    <div class="row mt-5">
        <div class="col-12">
            <h2>All Researches</h2>
            @if(count($researches) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th>Approved Date</th>
                                <th>Completed Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($researches as $research)
                                <tr>
                                    <td>{{ $research->title }}</td>
                                    <td>{{ $research->type }}</td>
                                    <td>{{ $research->status }}</td>
                                    <td>{{  ucwords($research->pivot->role) ?? '—' }}</td>
                                    <td>{{ $research->approved_date ? \Carbon\Carbon::parse($research->approved_date)->format('F d, Y') : '—' }}</td>
                                    <td>{{ $research->date_completed ? \Carbon\Carbon::parse($research->date_completed)->format('F d, Y') : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-3">No research records found.</p>
            @endif
        </div>
    </div>

</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
