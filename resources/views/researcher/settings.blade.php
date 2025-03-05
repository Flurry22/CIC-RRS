<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('stylesheet/researcher/researcherSettings.css') }}">
    <link rel="icon" href="{{ asset('img/cic-logo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Settings</title>



</head>
<body>

    
    
    <div class="wrapper container-fluid w-auto p-0 m-0">

        <div class="header">
            <button class="menu-btn" id="menuBtn">&#9776;</button>
        </div>

        <div class="sidebar" id="sidebar">
            <button class="close-btn" id="closeBtn">&times;</button>
            <img src="{{ asset('img/cic-logo.png') }}" alt="University Logo">
            <h3>USeP-College of Information and Computing</h3>
            <h4>Research Repository System</h4>
            <hr class="w-100 border-3">
            <ul>
                <li><a href="{{ route('researcher.dashboard', ['id' => $researcher->id]) }}">Dashboard</a></li>
                <li><a href="{{ route('researchers.search') }}">Researchers & Researches</a></li>
                <li><a href="{{ route('researcher.settings.edit') }}">Settings</a></li>
                <li><a href="{{ route('researcher.files.index') }}">Research Files</a></li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <div class="main-content container-fluid">
            <div class="box text-whit p-3 rounded-2 text-white" style="background-color: #818589;">
                <h1 style="color: white; font-weight: bold;" class="fs-2">Settings</h1>
                <div class="form-container mt-5">
                    <!-- Update Profile Picture Form -->
                    <form action="{{ route('researcher.updateProfilePicture') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="img-container">
                            <img class="profile-picture profile-img mx-auto d-block mt-2 rounded-circle h-25 w-25 border border-black" src="{{ asset('storage/' . $researcher->profile_picture) }}" alt="Profile Picture">
                        </div>
                        <div class="upload d-flex justify-content-center gap-2 mt-2">
                            <label class=" p-3 rounded-2 btn" style="cursor: pointer; background-color: #922220; color: white;" for="file-upload">Upload Image</label>
                            <input  class="d-none" type="file" id="file-upload" name="profile_picture" accept=".jpg, .jpeg, .png, .jfif,">
                            <button type="submit" class="btn" style="background-color: #922220; color: white;" >Update Picture</button>
                        </div>
                    </form>

                    <hr class="w-100 border-3">

                    <!-- Change Password Form -->
                    <form action="{{ route('researcher.changePassword') }}" method="POST">
                        @csrf
                        <div class="password-section">
                            <h2 class="text-center" style="color: white;">Change Password</h2>
                            <div class="info mt-2">
                                <label for="current-password" class="form-label" style="color: white;" style="border:1px, solid #52489f">Current Password</label>
                                <input type="password" id="current-password" name="current_password" class="form-control" required style="border:1px, solid #52489f">
                            </div>
                            <div class="info-2 mt-3">
                                <label for="new-password" class="form-label" style="color: white;">New Password</label>
                                <input type="password" id="new-password" name="new_password" class="form-control" required style="border:1px, solid #52489f">
                            </div>
                            <div class="info-3 mt-3">
                                <label for="new-password-confirm" class="form-label" style="color: white;">Confirm New Password</label>
                                <input type="password" id="new-password-confirm" name="new_password_confirmation" class="form-control" required style="border:1px, solid #52489f">
                            </div>
                            <div class="show-pass mt-3">
                                <input type="checkbox" id="show-password" onclick="togglePasswordVisibility()" >
                                <label for="show-password" style="color: white;">Show Password</label>
                            </div>
                            <div class="save-button mt-3 d-flex justify-content-center">
                                <button type="submit" class="btn" style="background-color :#922220; color: white;">Change Password</button>
                            </div>
                        </div>
                    </form>

                    <hr class="w-100 border-3">

                    <!-- Update Profile Information Form -->
                    <form action="{{ route('researcher.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Basic Information -->
                        <h2 class="text-center" style="color: white;">Basic Information</h2>
                        <div class="info mt-2">
                            <label for="first-name" class="form-label" style="color: white;">Edit First Name</label>
                            <input type="text" id="first-name" name="first_name" class="form-control" value="{{ old('first_name', explode(' ', $researcher->name)[0] ?? '') }}" style="border:1px, solid #52489f">
                        </div>
                        <div class="info-2 mt-3">
                            <label for="last-name" class="form-label" style="color: white;">Edit Last Name</label>
                            <input type="text" id="last-name" name="last_name" class="form-control" value="{{ old('last_name', explode(' ', $researcher->name)[1] ?? '') }}" style="border:1px, solid #52489f">
                        </div>
                        <div class="info-4 mt-3">
                            <label for="email" class="form-label" style="color: white;">Edit Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $researcher->email) }}" style="border:1px, solid #52489f">
                        </div>

                        <!-- Skills -->
                        <div class="skill-info mt-3">
                            <span class="form-label" style="color: white;">Edit Specializations</span>
                        </div>
                        <div class="skills">
                            <div id="skills-container">
                                @foreach($skills as $skill)
                                    <div class="input-group mb-2">
                                        <input type="text" name="skills[]" class="form-control" value="{{ $skill }}" style="border:1px, solid #52489f">
                                        <button type="button" class="btn btn-danger remove-skill">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="button-container mt-4 d-flex justify-content-center">
                                <button type="button" class="btn w-25" id="add-skill" style="background-color : #922220;color: white;">Add Specializations</button=>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="save-button mt-4 d-flex justify-content-center">
                            <button type="submit" class="btn  w-25" style="background-color: #922220;color: white;">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePasswordVisibility() {
            const passwordFields = ['current-password', 'new-password', 'new-password-confirm'];
            passwordFields.forEach(id => {
                const field = document.getElementById(id);
                field.type = field.type === 'password' ? 'text' : 'password';
            });
        }

        // Add skill input dynamically
        document.getElementById('add-skill').addEventListener('click', function () {
            const container = document.getElementById('skills-container');
            const newField = `
                <div class="input-group mb-2">
                    <input type="text" name="skills[]" class="form-control" placeholder="Enter a skill">
                    <button type="button" class="btn btn-danger remove-skill">Remove</button>
                </div>`;
            container.insertAdjacentHTML('beforeend', newField);
        });

        // Remove skill dynamically
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-skill')) {
                e.target.parentElement.remove();
            }
        });


        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menuBtn');
        const closeBtn = document.getElementById('closeBtn');
        menuBtn.addEventListener('click', () => sidebar.classList.add('active'));
        closeBtn.addEventListener('click', () => sidebar.classList.remove('active'));
    </script>
</body>
</html>
