<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <title>View All Research</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/viewResearchers.css') }}">
    <style>
         .modal-content {
        background-color: #818589; /* Light purple background */
        border-radius: 8px;
        padding: 20px; 
    }

        .card {
            border: 4px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .filter-section {
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .filter-section .form-control {
            width: auto;
            display: inline-block;
        }
        .filter-section .btn {
            margin-left: 10px;
        }
        .card img {
        border: 2px solid #52489f;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
        .card a {
        color: #922220;
        text-decoration: none;
    }
        .modal-dialog {
            max-width: 500px;
            background-color: #f8f9fa; /* Light gray for better readability */
        border: 1px solid #ddd;
        border-radius: 8px;
     }
     

     .btn-primary {
        background-color: #52489f;
        border: none;
    }

      /* Pagination Container */
      .pagination {
        display: flex;
        justify-content: center;
        padding: 10px 0;
    }

    /* Pagination Links */
    .pagination li a {
        color: #52489f; /* Main theme color */
        background-color: #f8f9fa; /* Light background */
        border: 1px solid #ddd;
        margin: 0 5px;
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    /* Active Page */
    .pagination li.active a {
        background-color: #52489f; /* Color for the selected page */
        color: #fff; /* Text color for active page */
        border-color: #52489f; /* Border color matching the background */
        pointer-events: none;
        font-weight: bold; /* Bold text for better emphasis */
    }

    /* Hover Effect */
    .pagination li a:hover {
        background-color: #922220;
        color: #fff;
        border-color: white;
    }

    /* Disabled Links */
    .pagination li.disabled a {
        color: #ccc;
        background-color: #f8f9fa;
        border-color: #ddd;
        pointer-events: none;
    }
        
    </style>
</head>
<body>


    <div class="header">
        <button class="menu-btn" id="menuBtn">&#9776;</button>
    </div>


    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <button class="close-btn" id="closeBtn">&times;</button>
            <img src="{{ asset('img/cic-logo.png') }}" alt="usep-logo">
            <h3>USeP-College of Information and Computing</h3>
            <h4>Research Repository System</h4>
            <hr class="w-100 border-3">
            <ul>
                <li><a href="{{ route('research_staff.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('research.create', ['type' => 'program']) }}">Add New Research</a></li>
                <li><a href="{{ route('research.index') }}">View All Research</a></li>
                <li><a href="{{ route('researchers.create') }}">Add New Researcher</a></li>
                <li><a href="{{ route('researchers.index') }}">View Researchers</a></li>
                <li><a href="/research-files">Research Files</a></li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Main Content -->
        <div class="main-content container-fluid">
            <div class="box p-3 rounded-2 text-white" style="background-color: #818589;">
                <h1 style="color: white; font-weight: bold;" class="fs-2">Researchers</h1>

                <!-- Search Form -->
                <form method="GET" action="{{ route('researchers.index') }}" class="mt-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search researchers..." class="form-control mb-3">
                </form>

                <!-- new -->
                <div class="row mt-4">
                    @foreach ($researchers as $researcher)
                        <div class="col-md-4 mb-4">
                            <div class="card" style="border: black;">
                                <div class="card-body text-center">
                                    <a href="{{ route('researcher.repository', $researcher->id) }}" style="text-decoration: none;">
                                        <img 
                                            src="{{ $researcher->profile_picture ? Storage::url($researcher->profile_picture) : asset('img/default-profile.png') }}" 
                                            alt="{{ $researcher->name }}" 
                                            class="rounded-circle mb-3" 
                                            style="width: 100px; height: 100px; object-fit: cover; border: 1.5px solid black;">
                                        <h5 class="card-title" style="color: black;">{{ $researcher->name }}</h5>
                                    </a>
                                    <p class="card-text">{{ $researcher->position }}</p>
                                    <p class="card-text">{{ $researcher->email }}</p>

                                    <!-- Edit Button -->
                                    <button type="button" class="btn edit-researcher-btn" style="background-color: #7393B3; color: white;"
                                        data-toggle="modal"
                                        data-target="#editModal"
                                        data-id="{{ $researcher->id }}"
                                        data-name="{{ $researcher->name }}" 
                                        data-email="{{ $researcher->email }}"
                                        data-position="{{ $researcher->position }}"
                                        data-programs="{{ json_encode($researcher->programs->pluck('id')->toArray()) }}">
                                        Edit
                                    </button>
                                <!-- end -->
                    
                                    <form action="{{ route('researchers.destroy', $researcher->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this researcher?');">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <!-- Render Pagination Links -->
                    {{ $researchers->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>

                <!-- new -->             
                <!-- Edit Modal -->
                                 <!-- Edit Modal -->
                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Researcher</h5>
                                    
                                </div>
                                <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                                    @csrf
                               
                                    <div class="modal-body">
                                        <div>
                                            <label for="editFirstName"style="color: white;">First Name</label>
                                            <input type="text" name="first_name" id="editFirstName" class="form-control" required>
                                        </div>
                                        <div>
                                            <label for="editLastName"style="color: white;">Last Name</label>
                                            <input type="text" name="last_name" id="editLastName" class="form-control" required>
                                        </div>
                                        <div>
                                            <label for="editPosition"style="color: white;">Position</label>
                                            <input type="text" name="position" id="editPosition" class="form-control" required>
                                        </div>
                                        <div>
                                            <label for="editEmail"style="color: white;">Email</label>
                                            <input type="email" name="email" id="editEmail" class="form-control" required>
                                        </div>
                                        <div>
                                            <label for="editPassword"style="color: white;">Password</label>
                                            <input type="password" name="password" id="editPassword" class="form-control">
                                            <small class="form-text text-muted">Leave blank to keep the current password.</small>
                                        </div>
                                        <div>
                                            <label for="editProfilePicture"style="color: white;">Profile Picture</label>
                                            <input type="file" name="profile_picture" id="editProfilePicture" class="form-control">
                                        </div>

                                        <!-- Checkbox section for selecting programs -->
                                        <h5>Select Programs</h5>
                                        @foreach($programs as $program)
                                            <div class="form-check">
                                                <input type="checkbox" name="program_ids[]" id="{{ 'program_' . $program->id }}" value="{{ $program->id }}" 
                                                    class="form-check-input">
                                                <label for="{{ 'program_' . $program->id }}" class="form-check-label" style="color: white;">{{ $program->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn" style="background-color: #922220; color: white;">Update</button>
                                    </div>
                                </form>
                            </div> <!-- End of modal-content -->
                        </div> <!-- End of modal-dialog -->
                    </div> <!-- End of modal -->
            </div>
        </div>
    </div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menuBtn');
    const closeBtn = document.getElementById('closeBtn')
    // Show sidebar
    menuBtn.addEventListener('click', () => {
        sidebar.classList.add('active');
    })
    // Hide sidebar
    closeBtn.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });

</script>

<script>
    
    $(document).on('click', '.edit-researcher-btn', function () {
    const researcherId = $(this).data('id');
    const researcherName = $(this).data('name');
    const researcherEmail = $(this).data('email');
    const researcherPosition = $(this).data('position');
    const researcherPrograms = $(this).data('programs');

    // Set form action URL dynamically
    $('#editForm').attr('action', `/researchers/${researcherId}`);

    // Pre-fill the modal with existing data
    $('#editFirstName').val(researcherName.split(' ')[0]); // Assuming first and last names are split by space
    $('#editLastName').val(researcherName.split(' ')[1]);
    $('#editEmail').val(researcherEmail);
    $('#editPosition').val(researcherPosition);

    // Uncheck all programs, then check the ones associated with the researcher
    $('input[name="program_ids[]"]').prop('checked', false);
    researcherPrograms.forEach(function (programId) {
        $(`#program_${programId}`).prop('checked', true);
    });
});
</script>
</body>
</html>
