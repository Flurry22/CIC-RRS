<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Years Management</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/admin/schoolYear.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
        <style>
    /* Updated color scheme */
    .custom-table thead th {
        background-color: #818589; /* blue-gray */
        color: #fff;
        text-align: center;
    }

    .custom-table tbody tr:hover {
        background-color: #eef2f5;
    }

    .table-actions .btn-primary {
        background-color: #3F729B;
        border-color: #3F729B;
    }
    .btn-primary {
        background-color: #3F729B;
        border-color: #3F729B;
    }
    .btn-primary:hover {
        background-color: #335d7c;
        border-color: #2e5471;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .main-content .box {
        background-color: #ffffff;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .table th, .table td {
        text-align: center;
    }

    .btn-primary,
    .btn-success {
        border-radius: 0.375rem;
    }

    .modal-header {
        background-color: #3F729B;
        color: white;
    }

    .modal-footer .btn-success {
        background-color: #3F729B;
        border-color: #3F729B;
    }

    .modal-footer .btn-success:hover {
        background-color: #335d7c;
    }

    .btn-outline-primary {
        border-color: #3F729B;
        color: #3F729B;
    }

    .btn-outline-primary:hover {
        background-color: #3F729B;
        color: white;
    }
</style>

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
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content" style="background-color:  #818589;">
        <div class="box mt-5 p-4 rounded-3 bg-light">
            <h1 class="mb-4 text-dark fw-bold fs-3">School Years</h1>

            <div class="table-responsive">
                <table class="table table-hover table-bordered custom-table">
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
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $year->id }}">Update</button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $year->id }}">Delete</button>
                                </td>
                            </tr>

                            <!-- Update Modal -->
                            <div class="modal fade" id="updateModal{{ $year->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update School Year</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('school_years.update', $year) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <label class="form-label">School Year</label>
                                                    <input type="text" name="school_year" class="form-control" value="{{ old('school_year', $year->school_year) }}" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">1st Sem Start</label>
                                                        <input type="date" name="first_sem_start" class="form-control" value="{{ old('first_sem_start', $year->first_sem_start) }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">1st Sem End</label>
                                                        <input type="date" name="first_sem_end" class="form-control" value="{{ old('first_sem_end', $year->first_sem_end) }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">2nd Sem Start</label>
                                                        <input type="date" name="second_sem_start" class="form-control" value="{{ old('second_sem_start', $year->second_sem_start) }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">2nd Sem End</label>
                                                        <input type="date" name="second_sem_end" class="form-control" value="{{ old('second_sem_end', $year->second_sem_end) }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">Off Sem Start</label>
                                                        <input type="date" name="off_sem_start" class="form-control" value="{{ old('off_sem_start', $year->off_sem_start) }}">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">Off Sem End</label>
                                                        <input type="date" name="off_sem_end" class="form-control" value="{{ old('off_sem_end', $year->off_sem_end) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $year->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete <strong>{{ $year->school_year }}</strong>?</p>
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
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('school_years.create') }}" class="btn btn-primary px-4">Add New School Year</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menuBtn');
    const closeBtn = document.getElementById('closeBtn');

    menuBtn.addEventListener('click', () => sidebar.classList.add('active'));
    closeBtn.addEventListener('click', () => sidebar.classList.remove('active'));
</script>

</body>
</html>
