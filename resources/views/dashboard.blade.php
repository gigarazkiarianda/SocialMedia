<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-custom {
            background-color: #f8f9fa; /* Light gray background */
        }
        .card-header-custom {
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
        }
        .btn-custom {
            background-color: #28a745; /* Bootstrap success color */
            color: white;
        }
        .btn-custom:hover {
            background-color: #218838; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card bg-custom shadow-sm">
                    <div class="card-header card-header-custom">
                        <h1 class="mb-0 text-center">Welcome, {{ Session::get('user')->name }}!</h1>
                    </div>
                    <div class="card-body">
                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}" class="text-center">
                            @csrf
                            <button type="submit" class="btn btn-custom">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
