<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <title>View All Research</title>
    <!-- Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('stylesheet/researchStaff/viewResearch.css') }}">
</head>
@php
    use Illuminate\Support\Str;
@endphp
<style>
    .card:hover {
        transform: scale(1.006);  /* Slightly lifts the card */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow for depth */
    transition: all 0.2s ease-in-out; /* Smooth transition */
}
.card .card-body strong,
.card .card-title {
    color: #4c2f6f;
}
.pagination-container .page-link {
        color: #922220; /* default link color */
        border: 1px solid #dee2e6;
        padding: 8px 12px;
        margin: 0 2px;
        border-radius: 6px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .pagination-container .page-link:hover {
        background-color: #922220;
        color: #ffffff;
    }

    .pagination-container .page-item.active .page-link {
        background-color: #922220;
        border-color: #922220;
        color: #ffffff;
    }

    .pagination-container .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    .research-title-link {
        font-size: 0.95rem;
        font-weight: 500;
        color: #212529; /* Bootstrap text-dark */
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .research-title-link:hover {
        color: #922220;
        text-decoration: underline;
    }

    </style>
<body>


    <div class="wrapper">
        <div class="header">
            <button class="menu-btn" id="menuBtn">&#9776;</button>
        </div>
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
                    <li><a href="#" id="show-calendar-btn">Show Deadlines Calendar</a></li>
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
            <div class="box p-2 rounded-2 text-white" style="background-color: #818589;">
            <h1 style="color: white; font-weight: bold;" class="fs-2">All Research</h1>

                <!-- Search Section -->
                <!-- Search and Filter Section -->
<form action="{{ route('research.index') }}" method="GET" class="mb-4">
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mt-5">
        <!-- Search Input -->
        <div class="col-12 col-md-12 col-lg-auto">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search for Research" 
                value="{{ request('search') }}">
        </div>

        <!-- Filter Dropdown -->
        <div class="col-12 col-md-12 col-lg-auto">
            <select 
                name="type" 
                class="form-control" 
                onchange="this.form.submit()">
                <option value="" {{ request('type') === '' ? 'selected' : '' }}>All</option>
                <option value="program" {{ request('type') === 'program' ? 'selected' : '' }}>Program</option>
                <option value="project" {{ request('type') === 'project' ? 'selected' : '' }}>Project</option>
                <option value="study" {{ request('type') === 'study' ? 'selected' : '' }}>Study</option>
            </select>
        </div>

        <div class="col-12 col-md-12 col-lg-auto">
            <select 
                name="status" 
                class="form-control" 
                onchange="this.form.submit()">
                <option value="" {{ request('status') === '' ? 'selected' : '' }}>All Statuses</option>
                <option value="On-Going" {{ request('status') === 'On-Going' ? 'selected' : '' }}>Ongoing</option>
                <option value="Finished" {{ request('status') === 'Finished' ? 'selected' : '' }}>Finished</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
        </div>

        <!-- Search Button -->
        <div class="col-12 col-md-12 col-lg-auto">
            <button type="submit" class="btn text-white w-100" style="background-color: #922220;">Search</button>
        </div>
    </div>
    <a href="{{ route('research-report.create') }}" class="btn btn-primary float-end mb-2 mt-2 " style="background-color:#922220; border: 1px solid #922220;">
        Generate Report
    </a>
</form>


<hr class="border-3" style="color: white; margin: 0 auto; width: 100%;">
<br>

<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-borderless mb-0">
                <!-- Header Row -->
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th class="col-md-5 fw-bold text-dark py-2 px-3">Title</th>
                        <th class="col-md-3 fw-bold text-dark py-2 px-3">Leader</th>
                        <th class="col-md-3 fw-bold text-dark py-2 px-3">Type</th>
                        <th class="col-md-1 fw-bold text-dark py-2 px-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($research as $researchItem)
                        <tr class="border-bottom">
                            <!-- Title -->
                            <td class="col-md-5 py-2 px-3">
                            <a href="{{ route('research.show', ['id' => $researchItem->id, 'page' => request()->get('page', 1)]) }}" class="research-title-link">
    {{ Str::limit($researchItem->title, 100, '...') }}
</a>
                            </td>

                            <!-- Leader -->
                            <td class="col-md-3 text-muted small py-2 px-3">
                                {{ $researchItem->leader->name }}
                            </td>

                            <!-- Type -->
                            <td class="col-md-3 text-muted small py-2 px-3">
                                {{ ucfirst($researchItem->type) }}
                            </td>

                            <!-- Status -->
                            <td class="col-md-1 py-2 px-3">
    <span class="badge px-3 py-2 rounded-pill text-white"
        style="
            background-color: 
            @if($researchItem->status == 'On-Going') #922220
            @elseif($researchItem->status == 'Finished')rgb(59, 192, 106)
            @else #6c757d
            @endif;
        ">
        {{ $researchItem->status }}
    </span>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-2">No research found for the applied filters and search query.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="pagination-container d-flex justify-content-center mt-4">
    {{ $research->onEachSide(1)->links('pagination::bootstrap-4') }}
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          const sidebar = document.getElementById('sidebar');
          const menuBtn = document.getElementById('menuBtn');
          const closeBtn = document.getElementById('closeBtn');

          menuBtn.addEventListener('click', () => {
              sidebar.classList.add('active');
          });

          closeBtn.addEventListener('click', () => {
              sidebar.classList.remove('active');
          });
      });
    </script>
</body>
</html>
