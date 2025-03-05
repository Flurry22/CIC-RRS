<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/admin/schoolYear.css') }}">
    <title>School Years Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="wrapper container-fluid w-auto p-0 m-0">
        <div class="header container-fluid p-0">
            <button class="menu-btn" id="menuBtn">&#9776;</button>
        </div>



        <div class="sidebar" id="sidebar">
            <button class="close-btn" id="closeBtn">&times;</button>
            <img src="{{ asset('img/cic-logo.png') }}" alt="usep-logo">
            <h3>USeP-College of Information and Computing</h3>
            <h4>Research Repository System</h4>
            <hr class="w-100 border-3">
            <ul>
                <li><a href="{{ route('academic_administrator.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('academic_administrator.manage_research') }}">Manage Research</a></li>
                <li><a href="{{ route('manage.researchers') }}">Manage Researchers</a></li>
                <li><a href="{{ route('school_years.viewUpdateschoolyear') }}">School Years</a></li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>


        <div class="main-content">
            <div class="box mt-5 p-3 rounded-2 text-white" style="background-color: #818589;">
                <h1 class="mb-4 fs-2" style="color: white; font-weight: bold;">School Years</h1>

                <!-- List of School Years -->
                <table class="table table-bordered text-white">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>School Year</th>
                            <th>1st Sem Start</th>
                            <th>1st Sem End</th>
                            <th>2nd Sem Start</th>
                            <th>2nd Sem End</th>
                            <th>Off Sem Start</th>
                            <th>Off Sem End</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schoolYears as $index => $year)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $year->school_year }}</td>
                                <td>{{ $year->first_sem_start }}</td>
                                <td>{{ $year->first_sem_end }}</td>
                                <td>{{ $year->second_sem_start }}</td>
                                <td>{{ $year->second_sem_end }}</td>
                                <td>{{ $year->off_sem_start }}</td>
                                <td>{{ $year->off_sem_end }}</td>
                                <td>
                                    <!-- Update Button -->
                                    <button type="button" class="btn btn-sm" style="background-color: #7393B3; color: white;"data-bs-toggle="modal" data-bs-target="#updateModal{{ $year->id }}">
                                        Update
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" class="btn  btn-sm" style="background-color: #922220; color: white;" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $year->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                            <!-- Update Modal -->
                            <div class="modal fade" id="updateModal{{ $year->id }}" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateModalLabel" style="color: black;">Update School Year</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('school_years.update', $year) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="school_year" class="form-label" style="color:  black;">School Year</label>
                                                    <input type="text" name="school_year" id="school_year" class="form-control" value="{{ old('school_year', $year->school_year) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="first_sem_start" class="form-label" style="color:  black;">1st Semester Start</label>
                                                    <input type="date" name="first_sem_start" id="first_sem_start" class="form-control" value="{{ old('first_sem_start', $year->first_sem_start) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="first_sem_end" class="form-label" style="color:  black;">1st Semester End</label>
                                                    <input type="date" name="first_sem_end" id="first_sem_end" class="form-control" value="{{ old('first_sem_end', $year->first_sem_end) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="second_sem_start" class="form-label" style="color:  black;">2nd Semester Start</label>
                                                    <input type="date" name="second_sem_start" id="second_sem_start" class="form-control" value="{{ old('second_sem_start', $year->second_sem_start) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="second_sem_end" class="form-label" style="color:  black;">2nd Semester End</label>
                                                    <input type="date" name="second_sem_end" id="second_sem_end" class="form-control" value="{{ old('second_sem_end', $year->second_sem_end) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="off_sem_start" class="form-label" style="color:  black;">Off Semester Start</label>
                                                    <input type="date" name="off_sem_start" id="off_sem_start" class="form-control" value="{{ old('off_sem_start', $year->off_sem_start) }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="off_sem_end" class="form-label" style="color:  black;">Off Semester End</label>
                                                    <input type="date" name="off_sem_end" id="off_sem_end" class="form-control" value="{{ old('off_sem_end', $year->off_sem_end) }}">
                                                </div>

                                                <button type="submit" class="btn" style="background-color: #7393B3;">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $year->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the school year <strong>{{ $year->school_year }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('school_years.destroy', $year) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                <a href="{{ route('school_years.create') }}" class="btn d-block w-25 mx-auto mt-4" style="background-color: #7393B3; color:rgb(255, 255, 255);;">Add New School Year</a>
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
