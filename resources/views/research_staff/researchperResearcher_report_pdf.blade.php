<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Research Report PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            height: auto;
        }

        .header p {
            margin: 5px 0;
        }

        .header .fw-bold {
            font-weight: bold;
            font-size: 14px;
        }

        .mt-4 {
            margin-top: 25px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
            font-size: 11px;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .no-page-break {
            page-break-inside: avoid;
        }

        .page-break {
            page-break-before: always;
        }

        .researcher-name {
            font-weight: bold;
            font-size: 13px;
            text-align: center;
            margin: 10px 0;
        }

        .table thead tr {
            background-color: #f9f9f9;
        }

        .table tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        .table tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .empty-data {
            text-align: center;
            color: #888;
        }

        .page-break-header {
            display: block;
            page-break-before: always;
        }

        .project-team {
            width: 20%;
            word-wrap: break-word;
            white-space: normal;
        }
        

    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('img/cic-logo.png') }}" alt="CIC Logo" class="logo">
    <p class="fw-bold">College of Information and Computing</p>
    <p class="fw-bold">Research Report</p>
</div>

<div class="mt-4">
    <p><strong>Report Description:</strong> {{ $request->description }}</p>
    <p><strong>Date Generated:</strong> {{ now()->format('F d, Y') }}</p>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Researcher</th>
            <th>Program/Study Title</th>
            <th>Project Duration</th>
            <th>Funding Source</th>
            <th>Collaborating College/Agency</th>
            <th>Status</th>
            <th>Terminal Reports</th>
            <th>Year Completed</th>
        </tr>
    </thead>
   <tbody>
@forelse ($researchers as $index => $researcher)
    @php $researches = $researcher->researches; @endphp
    @if ($researches->isNotEmpty())
        @foreach ($researches as $i => $research)
            <tr @if($index > 0 && $i === 0) class="page-break" @endif>
                {{-- Show researcher name only on the first research row --}}
                @if ($i === 0)
                    <td class="researcher-name">{{ $researcher->name }}</td>
                @else
                    <td></td>
                @endif

                <td>{{ $research->title }}</td>
                <td>{{ $research->project_duration ?? 'N/A' }}</td>
                <td>{{ $research->fundingType->type ?? 'N/A' }}</td>
                <td>{{ $research->funded_by ?? 'N/A' }}</td>
                <td>{{ ucfirst($research->status) }}</td>
                <td>{{ $research->terminal_file ? 'Yes' : 'N/A' }}</td>
                <td>{{ $research->date_completed ? \Carbon\Carbon::parse($research->date_completed)->format('Y') : 'N/A' }}</td>
            </tr>
        @endforeach
    @endif
@empty
    <tr>
        <td colspan="9" class="empty-data">No research data available for the given filters.</td>
    </tr>
@endforelse
</tbody>



</table>

</body>
</html>
