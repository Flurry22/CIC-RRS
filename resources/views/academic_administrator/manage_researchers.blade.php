<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Researchers</title>
    
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/admin/manageResearchers.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card.profile-header {
    height: 200px; /* Set the fixed height for the card */
    overflow: hidden; /* Ensure content doesn't overflow */
}
        /* === Profile Image === */
        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid black;
        }

        /* === Card Styling === */
        .card-body {
            text-align: center;
            overflow-y: auto;
        }

        .card-title {
            font-weight: bold;
            color: black;
        }

        .card-text {
            color: black;
        }

        /* === Active Years === */
        .projects-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }

        .projects-row p {
            margin-bottom: 0;
        }

        .projects-label {
            color: #52489f;
        }

        /* === Toggle Styled Like Badge === */
        .badge-toggle .form-check-input {
            width: 3.2rem;
            height: 1.6rem;
            background-color: #adb5bd;
            border-radius: 2rem;
            position: relative;
            appearance: none;
            outline: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .badge-toggle .form-check-input::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 1.2rem;
            height: 1.2rem;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .badge-toggle .form-check-input:checked {
            background-color: #198754;
        }

        .badge-toggle .form-check-input:checked::before {
            transform: translateX(1.6rem);
            box-shadow: none;
            outline: none;
        }
        .badge-toggle .form-check-input:focus {
    box-shadow: none;
    outline: none;
}
.profile-divider {
    width: 60%; /* Set the width of the <hr> to 60% of the container */
    margin: 10px auto; /* Center align it horizontally with some spacing */
    border-top: 2px solid #ccc; /* Add a border to the <hr> */
}
.custom-pagination .pagination {
    gap: 0.5rem;
}

.custom-pagination .page-item .page-link {
    color: #922220;
    border: 1px solid #922220;
    border-radius: 8px;
    padding: 6px 12px;
    transition: background-color 0.3s;
}

.custom-pagination .page-item.active .page-link {
    background-color: #922220;
    color: #fff;
    border-color: #922220;
}

.custom-pagination .page-item.disabled .page-link {
    color: #aaa;
    border-color: #ccc;
}
    </style>
</head>

<body>
<div class="wrapper container-fluid w-auto p-0 m-0">

    <!-- Sidebar & Header -->
    <div class="header container-fluid p-0">
        <button class="menu-btn" id="menuBtn">&#9776;</button>
    </div>

    <div class="sidebar" id="sidebar">
        <button class="close-btn" id="closeBtn">&times;</button>
        <img src="{{ asset('img/cic-logo.png') }}" alt="usep-logo">
        <h3>USeP-College of Information and Computing</h3>
        <h4>Research Repository System</h4>
        <ul>
          <hr style="width: 100%; border: 1px solid white; margin-bottom: -4px">
              
          <li><a href="{{ route('academic_administrator.dashboard') }}">Dashboard</a></li>
          
          <hr style="width: 100%; border: 1px solid white; margin: 2px 0;">
          
          {{-- Accordion --}}
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
              Monitoring
            </button>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                <li><a href="{{ route('academic_administrator.manage_research') }}">Research</a></li>
                <li><a href="{{ route('manage.researchers') }}">Researchers</a></li>
              </div>
            </div>

            <hr style="width: 100%; border: 1px solid white; margin: 2px 0;">
            
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
              Admin Features
            </button>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                <li><a href="{{ route('school_years.viewUpdateschoolyear') }}">School Years</a></li>
              </div>
            </div>
          </div>
          {{-- End of Accordion --}}
          <hr style="width: 100%; border: 1px solid white; margin: 2px 0;">
          
          <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
              
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div class="main-content container-fluid"style="background-color:  #818589;">
    <div class="container mt-4">
        <h1 class="mb-4"style="color: white;">Researchers</h1>
        <div class="alert alert-info">
            <strong>{{ $activeResearchers }} Active Researchers</strong> out of <strong>{{ $totalResearchers }}</strong> total researchers.
        </div>

        <div class="container profile-page">
    <div class="row">
        @foreach($researchers as $researcher)
            <div class="col-xl-6 col-lg-7 col-md-12 mb-4">
                <div class="card profile-header shadow-sm position-relative">
                    <div class="body p-3">
                        <div class="row align-items-center">
                            <!-- Profile Picture -->
                            <div class="col-lg-4 col-md-4 col-12 text-center mb-3 mb-md-0">
                                <div class="profile-image">
                                    <img src="{{ $researcher->profile_picture ? Storage::url($researcher->profile_picture) : asset('img/default-profile.png') }}" 
                                        alt="Profile Picture" 
                                        class="img-fluid rounded-circle border border-dark" 
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                </div>

                                <!-- Position below image -->
                               

                                <!-- Horizontal Rule below the image -->
                                <hr class="profile-divider">
                                <span class="job_post text-muted d-block mt-2">{{ $researcher->position }}</span>
                            </div>

                            <!-- Info and Buttons -->
                            <div class="col-lg-8 col-md-8 col-12">
                                <h4 class="m-0">
                                    <strong>{{ $researcher->name }}</strong>
                                </h4>
                                
                                <!-- Email replaces position -->
                                <span class="text-muted">{{ $researcher->email }}</span>

                                <!-- Status Toggle -->
                                <form method="POST" 
                                    action="{{ route('academic_administrator.manage_researchers.updateStatus', $researcher->id) }}" 
                                    class="position-absolute top-0 end-0 m-2">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-check form-switch badge-toggle"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="left"
                                        data-bs-title="{{ $researcher->researcher_status === 'Active' ? 'Active' : 'Inactive' }}">
                                        <input type="checkbox"
                                            class="form-check-input toggle-input"
                                            id="statusSwitch{{ $researcher->id }}"
                                            name="researcher_status"
                                            value="Active"
                                            {{ $researcher->researcher_status === 'Active' ? 'checked' : '' }}>
                                    </div>
                                </form>

                                <!-- Active Years -->
                                <div class="mt-3">
                                    <p class="fw-bold mb-1">Active Years:</p>
                                    @if($researcher->activeYears->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($researcher->activeYears->sortDesc() as $year)
                                                <span class="badge" style="background-color: #922220;">{{ $year }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted small">None</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>



        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
    <div class="custom-pagination">
        {{ $researchers->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>
    </div>
</div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toggle-input').forEach(function(toggle) {
        toggle.addEventListener('change', function () {
            const form = this.closest('form');

            // If unchecked, send 'Inactive'
            if (!this.checked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'researcher_status';
                hiddenInput.value = 'Inactive';
                form.appendChild(hiddenInput);
            }

            form.submit();
        });
    });

    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        const tooltip = new bootstrap.Tooltip(el);

        const input = el.querySelector('input[type="checkbox"]');
        input.addEventListener('change', function () {
            const newTitle = this.checked ? 'Active' : 'Inactive';
            el.setAttribute('data-bs-original-title', newTitle); // Update tooltip title
            tooltip.setContent({ '.tooltip-inner': newTitle }); // Bootstrap 5.3+ dynamic update
        });
    });
</script>
</body>
</html>
