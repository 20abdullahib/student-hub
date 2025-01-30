<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropbox Account Setup</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Dropbox Account Setup</h2>
        <form action="{{ route('dropbox.setup') }}" method="POST">
            @csrf
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Dropbox account email" required>
            </div>
            <!-- Client ID -->
            <div class="form-group">
                <label for="client_id">Client ID</label>
                <input type="text" class="form-control" id="client_id" name="client_id" placeholder="Enter Dropbox Client ID" required>
            </div>
            <!-- Client Secret -->
            <div class="form-group">
                <label for="client_secret">Client Secret</label>
                <input type="text" class="form-control" id="client_secret" name="client_secret" placeholder="Enter Dropbox Client Secret" required>
            </div>
            <!-- Refresh Token -->
            <div class="form-group">
                <label for="refresh_token">Refresh Token</label>
                <input type="text" class="form-control" id="refresh_token" name="refresh_token" placeholder="Enter Dropbox Refresh Token" required>
            </div>
            <!-- Department -->
            <div class="form-group">
                <label for="department_id">Assign to Department</label>
                <select class="form-control" id="department_id" name="department_id" required>
                    <option value="" disabled selected>Select a department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Save and Refresh Access Token</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
