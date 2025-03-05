<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="{{ asset('img/cic-logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('stylesheet/login.css') }}">

</head>
<body>

    @if ($errors->any())
        <div class="error-msg" id="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div> 
    @endif

    <div class="container">
        <img src="{{ asset('img/cic-logo.png') }}" alt="usep logo" class="img-fluid">
        <p class="usep">College of Information and Computing</p>
        <p class="rms">Research Repository System</p>

        <!-- Login form -->
        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf
            <!-- Role selection -->
            <div class="role">
                <label for="role" class="form-label">Login as:</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="academic_administrator">Academic Administrator</option>
                    <option value="researcher">Researcher</option>
                    <option value="research_staff">Research Staff</option>
                </select>
            </div>

            <!-- Email input -->
            <div class="user">
                <label for="email" class="form-label">USeP Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <!-- Password input -->
            <div class="pass">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <!-- Login button -->
            <div class="log">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        // Hide error message when user interacts with input fields
        const error = document.getElementById('error');
        const userInput = document.getElementById('email');
        const userPass = document.getElementById('password');
        const role = document.getElementById('role');


        document.addEventListener('click', (e) => {
            if (e.target == userInput || e.target == userPass || e.target == role) {
                error.style.display = 'none';
            }
        });
    </script>
</body>
</html>
