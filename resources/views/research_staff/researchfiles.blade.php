<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/researchFile.css') }}">
    <title>Research Files Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <button class="menu-btn" id="menuBtn">&#9776;</button>
    </div>

    <div class="wrapper container-fluid w-auto p-0 m-0">
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

        <div class="main-content container-fluid">
            <div class="box p-2 rounded-2 text-white mt-3" style="background-color: #818589;">
                <h1 class="mb-4 fs-2" style="font-weight: bold;">Upload and Manage Research Files</h1>
        
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- File Upload Form -->
                <div class="card mb-4">
                    <div class="card-header">Upload or Replace a File</div>
                    <div class="card-body">
                        <form action="{{ route('research-files.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file_name" class="form-label">File Title</label>
                                <input type="text" class="form-control" id="file_name" name="file_name" placeholder="Enter file title" required>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Choose File</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-primary" style="background-color: #922220; border-color: white;">Upload or Replace File</button>
                            </div>
                        </form>
                    </div>
                </div>
                

                <!-- Uploaded Files List -->
                <div class="card">
                    <div class="card-header">Uploaded Files</div>
                    <div class="card-body">
                        @if ($files->isEmpty())
                            <p class="text-muted">No files uploaded yet.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">File Title</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($files as $file)
                                        <tr>
                                            <td>{{ $file->title }}</td>
                                            <td>
                                                <!-- Download Button -->
                                                <a href="{{ route('research-files.download', $file->id) }}" class="btn btn-sm" style="background-color: #52489f; color: #fff; border-color: #52489f;">
                                                    Download
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('research-files.delete', $file->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm" 
                                                            style="background-color: #922220; color: black; border-color: white;"
                                                            onclick="return confirm('Are you sure you want to delete this file?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
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


    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
