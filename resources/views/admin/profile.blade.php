<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | Manila LinkUp</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-profile.css') }}">
</head>
<body class="bg-light d-flex">

    @include('admin.sidebar')

    <div class="main-content w-100 p-4">
        <div class="container-fluid">
            <h1 class="fw-bold mb-4" style="color: #1B3E9C;">Admin Settings</h1>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <span class="material-symbols-outlined align-middle me-2">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                        <div class="position-relative d-inline-block mx-auto mb-3">
                            <img src="{{ asset('images/admin_profile_pic.svg') }}" class="rounded-circle border border-4 border-white shadow-sm" width="120" height="120">
                            <button class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 shadow" style="background-color: #1B3E9C;">
                                <span class="material-symbols-outlined fs-6 pt-1">edit</span>
                            </button>
                        </div>
                        <h4 class="fw-bold mb-0">Admin Portal</h4>
                        <p class="text-muted small">System Overseer</p>
                        <hr class="text-muted opacity-25">
                        <div class="text-start small">
                            <p class="mb-2"><strong>Last Login:</strong> <span class="text-muted">April 20, 2026</span></p>
                            <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success-subtle text-success">Super Admin</span></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-4">Account Details</h5>
                        
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Full Name</label>
                                    <input type="text" name="name" class="form-control bg-light border-0" value="Admin Portal" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Email Address</label>
                                    <input type="email" name="email" class="form-control bg-light border-0" value="admin@manilalinkup.ph" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">New Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control bg-light border-0" placeholder="••••••••">
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" style="background-color: #1B3E9C; border-radius: 10px;">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>