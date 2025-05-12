<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Files for Researchers</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('stylesheet/researcher/researcherFiles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="header p-0 m-0">
        <button class="menu-btn" id="menuBtn">&#9776;</button>
    </div>

    <div class="wrapper container-fluid p-0 m-0">
        <!-- Header -->
        

        <div class="sidebar" id="sidebar">
            <button class="close-btn" id="closeBtn">&times;</button>
            <img src="{{ asset('img/cic-logo.png') }}" alt="University Logo">
            <h3>USeP-College of Information and Computing</h3>
            <ul>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: 8px">
                <li><a href="{{ route('researcher.dashboard', ['id' => $researcher->id]) }}">Dashboard</a></li>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: -7px">
                <li><a href="{{ route('researchers.search') }}">Researchers & Researches</a></li>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: -5px">
                <li><a href="{{ route('researcher.settings.edit') }}">Settings</a></li>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: -5px">
                <li><a href="{{ route('researcher.files.index') }}">Research Files</a></li>
                <hr style="width: 100%; border: 1px solid white; margin-bottom: -5px">
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        
        <div class="main-content container-fluid">
            <div class="box rounded-2 text-white p-3" style="background-color: #818589;"">

                <h1 class="mb-4 fs-2">Research Files for Researchers</h1>

                <!-- Uploaded Files List -->
                <div class="card">
                    <div class="card-header">Available Research Files</div>
                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif


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
                                                <a href="{{ route('research-files.download', $file->id) }}" class="btn btn-sm btn-success">
                                                    Download
                                                </a>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menuBtn');
        const closeBtn = document.getElementById('closeBtn');
        menuBtn.addEventListener('click', () => sidebar.classList.add('active'));
        closeBtn.addEventListener('click', () => sidebar.classList.remove('active'));
    </script>
</body>
</html>
