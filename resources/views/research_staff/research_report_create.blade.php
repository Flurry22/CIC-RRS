<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Research Report</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .mt-2 { margin-top: 10px; }
        .mt-5 { margin-top: 20px; }
        .mt-3 { margin-top: 15px; }
        .mb-3 { margin-bottom: 15px; }
        .form-group { width: 100%; }
        .btn-block { width: 100%; }


        .container{
          background-color: #818589;
          border-radius: 5px;
          padding: 10px;
          color: white;
          width: 45%;
          
        }


        .container form {
            animation: fadeIn 0.8s ease-out forwards;
            display: flex;
            flex-direction: column;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
                scale: 0.8;
            }
            to {
                opacity: 1;
                transform: translateY(0);
                scale: 1;
            }
        }
    </style>
</head>
<body>

  <a href="{{ route('research.index') }}" style="background-color:#922220; color: white; padding: 10px; text-decoration: none; display:block; width: 230px; text-align:center;
  margin: 2vh 3vw; border-radius:5px;">Back to View All Research</a>

  <div class="container mt-5">
    <h3 class="text-center mt-5">Generate Research Report</h3>
    <hr class="w-100 border-3">
    <!-- Form to filter research data -->
    <form action="{{ route('research-report.preview') }}" method="GET" >
      @csrf
      <div class="row mt-3 p-2">
        
    <div class="mb-3">
        <label for="view_version" class="form-label">Report View</label>
        <select name="view_version" id="view_version" class="form-select" required>
            <option value="title" {{ request('view_version') === 'title' ? 'selected' : '' }}>By Research Title</option>
            <option value="researcher" {{ request('view_version') === 'researcher' ? 'selected' : '' }}>By Researcher</option>
        </select>
    </div>
        <!-- Report Description -->
        <div class="col-md-6">
          <div class="form-group">
            <label for="description">Report Description</label>
            <textarea name="description" id="description" class="form-control h-25" rows="1">{{ old('description') }}</textarea>
          </div>
        </div>
        
          {{-- School Year --}}
        <div class="col-md-6">
          <div class="form-group">
            <label for="school_year">School Year</label>
            <select name="school_year" id="school_year" class="form-control">
              <option value="">Select School Year</option>
              @foreach ($schoolYears as $schoolYear)
                <option value="{{ $schoolYear->school_year }}">{{ $schoolYear->school_year }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row mt-3 p-2">
        <!-- Semester -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="semester">Semester</label>
                <select name="semester" id="semester" class="form-control">
                    <option value="">Select Semester</option>
                    <option value="first_semester">First Semester</option>
                    <option value="second_semester">Second Semester</option>
                    <option value="off_semester">Off Semester</option>
                </select>
            </div>
        </div>
        <!-- Status -->
        <div class="col-md-6">
            <div class="form-group">
            <label for="status">Status</label>
              <select name="status" id="status" class="form-control">
                  <option value="">Select Status</option>
                  <option value="On-Going" @if(old('status') == 'On-Going') selected @endif>On-Going</option>
                  <option value="Finished" @if(old('status') == 'Finished') selected @endif>Finished</option>
              </select>
            </div>
        </div>
      </div>

      <div class="row mt-3 p-2">
        <!-- Researcher -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="researcher_id">Researcher</label>
                <select name="researcher_id" id="researcher_id" class="form-control">
                    <option value="">Select Researcher</option>
                    @foreach ($researchers as $researcher)
                        <option value="{{ $researcher->id }}">{{ $researcher->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Program -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="program_id">Program</label>
                <select name="program_id" id="program_id" class="form-control">
                    <option value="">Select Program</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
      </div>

      <!-- Dynamic Table -->
      <div id="research-data" class="mt-4"></div>

      <!-- Generate PDF Button -->
      <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary btn-block"><i class="bi bi-eye" style="margin-right: 5px; height: 10px;"></i>Preview</button>
      </div>
      
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>
</html>
