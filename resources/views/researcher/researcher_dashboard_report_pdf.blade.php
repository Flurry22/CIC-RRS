<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Researcher Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        /* Header Styling */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            margin-bottom: 10px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        /* Info Section Styling */
        .info {
            text-align: center;
            margin-bottom: 20px;
        }

        .info-line {
            font-size: 14px;
            margin: 5px 0;
        }

        /* Profile Picture and Bio Styling */
        .profile-container {
            margin-top: 30px;
        }
        .profile-picture-container {
            float: left;
            width: 30%;
            text-align: center;
        }

        .rounded-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }

        .bio-container {
            float: left;
            width: 65%;
            margin-left: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
            font-size: 16px;
        }

        .bio, .skills, .contact {
            font-size: 14px;
            margin-top: 5px;
        }

        /* Clearfix */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Cards Styling */
        .card-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: separate;
            border-spacing: 15px;
        }

        .card {
            background-color: #e9ecef;
            color: black;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 24px;
            font-weight: bold;
        }

        /* Research Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-size: 12px;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* All Researches Section - Page Break */
        .all-researches-section {
            page-break-before: always;
            margin-top: 40px;
        }

        /* Small Notes Styling */
        .small-note {
            margin-top: 5px;
            font-size: 10px;
            color: #ccc;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('img/cic-logo.png') }}" alt="Logo">
        <p>College of Information and Computing</p>
        <p>Research Report</p>
    </div>

    <div class="info">
        <div class="info-line">Report Description: {{ $request->description }}</div>
        <div class="info-line">Date Generated: {{ now()->format('F d, Y') }}</div>
    </div>

    <div class="profile-container clearfix">
        <!-- Profile Picture Container -->
        <div class="profile-picture-container">
            <img src="{{ $researcher->profile_picture ? public_path('storage/' . $researcher->profile_picture) : public_path('img/default-avatar.png') }}" 
                 class="rounded-img" 
                 alt="Profile Picture">
            <div class="position" style="margin-top: 10px; font-weight: bold; font-size: 14px;">
                {{ $researcher->position ?? 'No position available.' }}
            </div>
        </div>

        <!-- Bio Container -->
        <div class="bio-container">
            <div class="section-title">Biography</div>
            <div class="bio">{{ $researcher->bio ?? 'No biography available.' }}</div>

            <div class="section-title">Specializations</div>
            <div class="skills">{{ implode(', ', $skills) }}</div>

            <div class="section-title">Contact Information</div>
            <div class="contact">Email: {{ $researcher->email }}</div>
        </div>
    </div>

    <table class="card-table">
        <tr>
            <td class="card">
                <div class="card-title">Ongoing Research</div>
                <div class="card-value">{{ $ongoingCount }}</div>
            </td>
            <td class="card">
                <div class="card-title">Finished Research</div>
                <div class="card-value">{{ $finishedCount }}</div>
            </td>
            <td class="card">
                <div class="card-title">Total Research</div>
                <div class="card-value">{{ $totalCount }}</div>
            </td>
        </tr>
    </table>

    <div class="all-researches-section">
        <div class="section-title">All Researches</div>
        @if(count($researches) > 0)
            <table>
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
                            <td>{{ ucwords($research->type) }}</td>
                            <td>{{ $research->status }}</td>
                            <td>{{  ucwords($research->pivot->role) ?? '—' }}</td>
                            <td>{{ $research->approved_date ? \Carbon\Carbon::parse($research->approved_date)->format('F d, Y') : '—' }}</td>
                            <td>{{ $research->date_completed ? \Carbon\Carbon::parse($research->date_completed)->format('Y') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="margin-top: 10px;">No research records found.</p>
        @endif
    </div>
    <div class="signature-section" style="margin-top: 60px;">
    <table style="width: 100%; text-align: center; border: none; margin-top: 30px;">
        <tr>
            <td>
                _________________________________<br>
                <strong>Verified by:</strong><br>
                Research Center Manager
            </td>
            <td>
                _________________________________<br>
                <strong>Verified by:</strong><br>
                Research Center Staff
            </td>
        </tr>
    </table>
</div>


</body>
</html>
