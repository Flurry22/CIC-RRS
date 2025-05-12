<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Research Report</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Reduce font size to fit content better */
        }

        .container {
            width: 100%;
            padding: 20px;
            background-color: white;
            color: black;
            border-radius: 8px;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mt-5 {
            margin-top: 20px;
        }

        .border {
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }

        .table th, .table td {
            border: 1px solid black;
            padding: 5px; /* Reduced padding */
            text-align: center;
            word-wrap: break-word; /* Allows content to wrap within cells */
        }

        .table th {
            background-color: #f2f2f2;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
        }

        .signature-line {
            margin-top: 10px;
            border-top: 1px solid black;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Page Breaks for large content */
        .page-break {
            page-break-before: always;
        }

        /* Prevent the table from overflowing the page */
        .table, .table th, .table td {
            table-layout: fixed;
        }

        /* Ensure content fits within the A4 page */
        @page {
            size: A4 landscape;
            margin: 20mm;
        }

        /* Add a page break for long tables */
        .table tr {
            page-break-inside: avoid;
        }
        .footer {
        position: fixed;
        bottom: 20mm;
        width: 100%;
        text-align: center;
        font-size: 10px;
    }

    .footer span {
        margin: 0;
        padding: 0;
    }
    </style>
</head>
<body>
    
    <div class="container">
        <!-- Header -->
        <div class="text-center">
            <p class="mt-2 fw-bold">Research Report</p>
        </div>

        <!-- Report Info -->
        <div class="mt-5 text-center">
            <span>Report Description: {{ $request->description }}</span>
            <span style="margin-left: 50px;">Date Generated: {{ now()->format('F d, Y') }}</span>
        </div>

        <!-- Table -->
        <table>
    <thead>
        <tr>
            <th>No</th>
            <th>Program/Study Title</th>
            <th>Project Duration</th>
            <th>Project Team</th>
            <th>Funding Source</th>
            <th>Collaborating College/Agency</th>
            <th>Status</th>
            <th>Terminal Reports</th>
            <th>Year Completed</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($researches as $index => $research)
            <tr>
                <td style="vertical-align: center;">{{ $index + 1 }}</td>
                <!-- Program/Study Title with vertical alignment -->
                <td style="vertical-align: middle; word-wrap: break-word; max-width: 75ch;">
    {{ $research->title }}
</td>
                <td style="vertical-align: center;">{{ $research->project_duration ?? 'N/A' }}</td>
                <td style="vertical-align: center;">
    @php
        $team = $research->leader ? $research->leader->name . '<br>' : '';
        $team .= $research->members->pluck('name')->implode('<br>');
    @endphp
    {!! $team !!}
</td>
                <td style="vertical-align: center;">{{ $research->fundingType->type ?? 'N/A' }}</td>
                <td style="vertical-align: center;">{{ $research->funded_by ?? 'N/A' }}</td>
                <td style="vertical-align: center;">{{ ucfirst($research->status) }}</td>
                <td style="vertical-align: center;">{{ $research->terminal_file ? 'Yes' : 'No' }}</td>
                <td style="vertical-align: center;">{{ $research->date_completed ? \Carbon\Carbon::parse($research->date_completed)->format('Y') : 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


    

    </div>
    
</body>
</html>
