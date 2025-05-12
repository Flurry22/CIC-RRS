<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Research Report Preview</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    
        <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; padding: 20px; background-color: white; color: black; border-radius: 8px; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .mt-2 { margin-top: 10px; }
        .mt-5 { margin-top: 20px; }
        .border { border: 1px solid black; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid black; padding: 8px; text-align: center; }
        .table th { background-color: #f2f2f2; }
        .signature { margin-top: 40px; text-align: center; }
        .signature-line { margin-top: 10px; border-top: 1px solid black; width: 200px; margin-left: auto; margin-right: auto; }
        .container-fluid  a {
            text-decoration: none;
            color: white;
            display: block;
            height: 40px;
            background-color: #922220;
            width: fit-content;
            padding: 10px;
            border-radius: 5px;
            text-align: left;
            margin-bottom: 10px;
            font-size: 14px;
            
        }
    </style>

</head>
<body>

<div class="container-fluid">

  <!-- Buttons -->
  <div class="text-center mt-5" style="display: flex; justify-content: space-between; align-items:center;">
    <a href="{{ route('research-report.create') }}" style>Back to View All Research</a>
    <form action="{{ route('research-report.generate-pdf') }}" method="POST">
      @csrf
      <input type="hidden" name="school_year" value="{{ $request->school_year }}">
      <input type="hidden" name="semester" value="{{ $request->semester }}">
      <input type="hidden" name="status" value="{{ $request->status }}">
      <input type="hidden" name="researcher_id" value="{{ $request->researcher_id }}">
      <input type="hidden" name="program_id" value="{{ $request->program_id }}">
      <input type="hidden" name="description" value="{{ $request->description }}">
      <button type="submit" class="btn btn-primary">Generate PDF</button>
    </form>
  </div>
    <!-- Header -->
    <div class="text-center">
    <img src="{{ asset('img/cic-logo.png') }}" alt="CIC Logo" style="width: 100px; height: 100px;">
        <p class="mt-2 fw-bold">College of Information and Computing</p>
        <p class="mt-2 fw-bold">Research Report Preview</p>
    </div>

    <!-- Report Info -->
    <div class="mt-5 text-center">
        <span>Report Description: {{ $request->description }}</span>
        <span style="margin-left: 50px;">Date Generated: {{ now()->format('F d, Y') }}</span>
    </div>

    <!-- Table -->
    <div class="container-fluid">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
              <tr>
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
                  <td>{{ $research->title }}</td>
                  <td>{{ $research->project_duration ?? 'N/A' }}</td>
          
                  <td>
                    @if ($research->leader) 
                        {{ $research->leader->name }}<br>
                    @endif
  
                    @foreach ($research->members as $member)
                        {{ $member->name }}<br>
                    @endforeach
                  </td>
                  <td>{{ $research->fundingType->type }}</td>
                  <td>{{ $research->funded_by ?? 'N/A' }}</td>
                  <td>{{ ucfirst($research->status) }}</td>
                  <td>{{ $research->terminal_file ? 'Yes' : 'N/A' }}</td>
                  <td>{{ $research->date_completed ? \Carbon\Carbon::parse($research->date_completed)->format('Y') : 'N/A' }}</td>
                  
              </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
