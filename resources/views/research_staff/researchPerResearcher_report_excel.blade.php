<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Researcher-Based Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
            <thead>
                <tr style="text-align: center;">
                    <th style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: top;">Researcher</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: top;">Program/Study Title</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: top;">Project Duration</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: top;">Funding Source</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: top;">Collaborating College/Agency</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: top;">Status</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: top;">Terminal Reports</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: top;">Year Completed</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($researchers as $researcher)
                    @php $rowspan = $researcher->researches->count(); @endphp
                    @foreach ($researcher->researches as $index => $research)
                        <tr style="text-align: center;">
                            @if ($index === 0)
                                <td rowspan="{{ $rowspan }}" style="border: 1px solid black; padding: 8px; vertical-align: middle; font-weight: bold;">
                                    {{ $researcher->name }}
                                </td>
                            @endif
                            <td style="border: 1px solid black; padding: 8px; vertical-align: top; text-align: center;">{{ $research->title }}</td>
                            <td style="border: 1px solid black; padding: 8px; vertical-align: top; text-align: center;">{{ $research->project_duration ?? 'N/A' }}</td>
                            <td style="border: 1px solid black; padding: 8px; vertical-align: top; text-align: center;">{{ $research->fundingType->type ?? 'N/A' }}</td>
                            <td style="border: 1px solid black; padding: 8px; vertical-align: top; text-align: center;">{{ $research->funded_by ?? 'N/A' }}</td>
                            <td style="border: 1px solid black; padding: 8px; vertical-align: top; text-align: center;">{{ ucfirst($research->status) }}</td>
                            <td style="border: 1px solid black; padding: 8px; vertical-align: top; text-align: center;">{{ $research->terminal_file ? 'Yes' : 'N/A' }}</td>
                            <td style="border: 1px solid black; padding: 8px; vertical-align: top; text-align: center;">{{ $research->date_completed ? \Carbon\Carbon::parse($research->date_completed)->format('Y') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="9" style="border: 1px solid black; padding: 8px; text-align: center; color: #888;">No research data available for the given filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>




</body>
</html>
