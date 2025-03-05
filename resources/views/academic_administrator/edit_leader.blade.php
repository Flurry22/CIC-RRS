<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Research Leader</title>
    <link rel="shortcut icon" href="{{ asset('img/cic-logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex">
     

        <!-- Main Content -->
        <div class="main-content" style="margin-left: 260px; padding: 20px;">
            <div class="container mt-5">
                <h1>Manage Leader for Research: {{ $research->title }}</h1>

                <form action="{{ route('academic_administrator.update_leader', $research->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="leader_id" class="form-label">Select Leader</label>
                        <select name="leader_id" id="leader_id" class="form-control" required>
                            <option value="">Select a Leader</option>
                            @foreach($researchers as $researcher)
                            <option value="{{ $researcher->id }}" {{ $research->leader_id == $researcher->id ? 'selected' : '' }}>
                                {{ $researcher->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Leader</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
