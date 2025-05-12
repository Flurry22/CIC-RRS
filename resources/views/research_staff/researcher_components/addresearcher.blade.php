<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <title>Add New Researcher</title>
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/addResearcher.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>  

    <div class="header">
        <button class="menu-btn" id="menuBtn">&#9776;</button>
    </div>

    <div class="wrapper container-fluid w-auto p-0 m-0">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <button class="close-btn" id="closeBtn">&times;</button>
            <img src="{{ asset('img/cic-logo.png') }}" alt="usep-logo">
            <h3>USeP-College of Information and Computing</h3>
            <h4>Research Repository System</h4>

            <ul>
              <hr style="width: 100%; border: 1px solid white; margin-top: -4px">
  
              <li><a href="{{ route('research_staff.dashboard') }}">Dashboard</a></li>
  
              <hr style="width: 100%; border: 1px solid white; margin-top: 5px">
  
              {{-- Accordion --}}
              <div class="accordion accordion-flush" id="accordionFlushExample">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  Research Management
                </button>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <li><a href="{{ route('research.create', ['type' => 'program']) }}">Add New Research</a></li>
                    <li><a href="{{ route('research.index') }}">View All Research</a></li>
                  </div>
                </div>
  
                <hr style="width: 100%; border: 1px solid white; margin-top: 5px">
  
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  Researcher Management
                </button>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <li><a href="{{ route('researchers.create') }}">Add New Researcher</a></li>
                    <li><a href="{{ route('researchers.index') }}">View Researchers</a></li>
                  </div>
                </div>
  
                <hr style="width: 100%; border: 1px solid white; margin-top: 10px;">
                
                
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Monitoring Management
                </button>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <li><a href="/research-files">Research Files</a></li>
                  </div>
                </div>
              </div>
              {{-- End of Accordion --}}
  
              <hr style="width: 100%; border: 1px solid white; margin-top: 10px;">
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#updateCredentialsModal">Update Credentials</a></li>
  
              <hr style="width: 100%; border: 1px solid white; margin-top: 5px;">
              <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
              
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Main Content -->
        <div class="main-content container-fluid"> 
    <div class="box p-4 rounded-2 text-white mt-3" style="background-color: #818589;">
        <h1 style="color: white; font-weight: bold;" class="fs-2">Add New Researcher</h1>
        <hr class="w-100 border-3">
        <form action="{{ route('researchers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- First Name and Last Name in Same Row -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="first_name" style="color: white;">First Name</label>
                    <input class="form-control" type="text" name="first_name" id="first_name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="last_name" style="color: white;">Last Name</label>
                    <input class="form-control" type="text" name="last_name" id="last_name" required>
                </div>
            </div>

            <!-- Position Field (with reduced width) -->
            <div class="mt-4">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label" for="position" style="color: white;">Position</label>
                        <input class="form-control" type="text" name="position" id="position" required>
                    </div>
                </div>
            </div>

            <!-- Email and Password in Same Row -->
            <div class="mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label" for="email" style="color: white;">Email</label>
                        <input class="form-control" type="email" name="email" id="email" required>
                        <small class="form-text" style="font-size: 0.875rem; color: white;">
                          Use University email address.
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password" style="color: white;">Password</label>
                        <input class="form-control" type="password" name="password" id="password" required>
                        <small class="form-text" style="font-size: 0.875rem; color:white;">
                            Password must be at least 8 characters.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Profile Picture -->
            <div class="mt-4">
                <label class="form-label" for="profile_picture" style="color: white;">Profile Picture</label>
                <input class="form-control" type="file" name="profile_picture" id="profile_picture">
            </div>

            <hr class="w-100 border-3">

            <!-- Program Selection in Rows -->
            <div>
                <h3 style="color: white;">Select Programs</h3>
                <div class="row">
                    @foreach($programs as $index => $program)
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="program_ids[]" id="program_{{ $program->id }}" value="{{ $program->id }}">
                                <label class="form-check-label" for="program_{{ $program->id }}" style="color: white;">
                                    {{ $program->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <hr class="w-100 border-3">

            <!-- Submit Button -->
            <button class="btn btn-primary d-block mx-auto mt-4" type="submit" style="width: 30%; background-color: #922220; border: none; font-weight: bold; padding: 10px 20px; border-radius: 8px;">Submit</button>
        </form>
    </div>
</div>


  {{-- Update Credentials --}}
  <div class="modal fade" id="updateCredentialsModal" tabindex="-1" aria-labelledby="updateCredentialsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="updateCredentialsModalLabel" style ="color: #52489f;">Update Research Staff Credentials</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="POST" action="{{ route('staff.updateCredentials') }}">
              @csrf
              <div class="mb-3">
              <label for="email" class="form-label" style ="color: #52489f;">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->guard('research_staff')->user()->email) }}" required>
              </div>
              <div class="mb-3">
              <label for="password" class="form-label" style ="color: #52489f;">New Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="mb-3">
              <label for="password_confirmation" class="form-label" style ="color: #52489f;">Confirm Password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
              </div>
              <button type="submit" class="btn btn-primary">Update Credentials</button>
          </form>
      </div>
      </div>
    </div>
  </div>

<!-- Additional Styles -->
<style>
    /* Thin border for form fields */
    .form-control {
        border: 1px solid #ccc;  /* Light grey border */
        border-radius: 8px;
        padding: 10px;
        transition: border-color 0.3s ease;
    }

    /* Border color change on hover/focus */
    .form-control:focus {
        border-color: #52489f; /* Use the purple color on focus */
        box-shadow: 0 0 5px rgba(82, 72, 159, 0.5);
    }

    /* Optional: Add border for checkboxes too */
    input[type="checkbox"] {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
    }

    input[type="checkbox"]:focus {
        outline: none;
        border-color: #52489f;
    }
</style>


        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menuBtn');
        const closeBtn = document.getElementById('closeBtn');

        // Show sidebar
        menuBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        // Hide sidebar
        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    </script>

</body>
</html>
