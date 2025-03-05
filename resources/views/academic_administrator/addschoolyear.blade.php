<!-- resources/views/academic_administrator/addschoolyear.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Add New School Year</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <style>
        body {
            background-color:#eaeaea;
        }

        .container-fluid ul li {
            width: fit-content;
            padding: 52489fpx;
        }

        .container-fluid ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 3px;
            border-radius: 5px;
            cursor: pointer;
            
        }
        
        .wrapper {
            animation: fadeIn 0.8s ease-out forwards; 
        }



        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
                scale: 0.8;
            }
            to {
                opacity: 1;
                transform: translateY(0);
                scale: 1;
            }
        }
    </style>
</head>
<body>

    <header class="container-fluid p-1" style="background-color: #858888;">
        <ul>
            <li class="list-group-item btn btn-secondary mt-2 rounded-2 fs-5"><a  href="{{ route('school_years.viewUpdateschoolyear') }}">Back to School Years</a></li>
        </ul>
    </header>

    <div class=" mt-4  mb-3 p-3 rounded-2 text-white w-50 mx-auto h-auto wrapper"  style="background-color: #818589;">
        <h1 class="mb-4 text-center">Add New School Year</h1>
        @if ($errors->any())
            <div class="alert alert-danger text-center">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('school_years.store') }}" method="POST" class="mt-5">
            @csrf
            <!-- Form Fields for Creating School Year -->
            <div class="mb-4">
                <label for="school_year" class="form-label"style >School Year</label>
                <input type="text" name="school_year" id="school_year" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="first_sem_start" class="form-label">1st Semester Start</label>
                <input type="date" name="first_sem_start" id="first_sem_start" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="first_sem_end" class="form-label">1st Semester End</label>
                <input type="date" name="first_sem_end" id="first_sem_end" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="second_sem_start" class="form-label">2nd Semester Start</label>
                <input type="date" name="second_sem_start" id="second_sem_start" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="second_sem_end" class="form-label">2nd Semester End</label>
                <input type="date" name="second_sem_end" id="second_sem_end" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="off_sem_start" class="form-label">Off Semester Start</label>
                <input type="date" name="off_sem_start" id="off_sem_start" class="form-control">
            </div>
            <div class="mb-4">
                <label for="off_sem_end" class="form-label">Off Semester End</label>
                <input type="date" name="off_sem_end" id="off_sem_end" class="form-control">
            </div>
            <button type="submit" class="btn d-block w-25 mx-auto mt-4" style="background-color: #64C4FF; color: white;">Create School Year</button>
            
        </form>
    </div>
    


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
