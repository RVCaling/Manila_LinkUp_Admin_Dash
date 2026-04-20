<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Manila LinkUp</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
</head>
<body class="login-body">

    <div class="login-container">
        <div class="login-card shadow-lg p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="logo-wrapper mb-3">
                    <img src="{{ asset('images/logo.svg') }}" alt="Manila LinkUp Logo" class="login-logo">
                </div>
                <h2 class="fw-bold mb-1" style="color: #1B3E9C;">Manila LinkUp</h2>
                <p class="text-muted small">Administrator Console</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger py-2 small text-center mb-4 border-0 shadow-sm" role="alert">
                    <span class="material-symbols-outlined fs-6 align-middle">error</span>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <span class="material-symbols-outlined fs-5 text-muted">mail</span>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-light border-start-0" placeholder="admin@manilalinkup.ph" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <label class="form-label small fw-bold text-muted">Password</label>
                        <a href="#" class="small text-decoration-none" style="color: #1B3E9C;">Forgot?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <span class="material-symbols-outlined fs-5 text-muted">lock</span>
                        </span>
                        <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Keep me logged in</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3" style="background-color: #1B3E9C;">
                    Sign In
                </button>

                <div class="text-center">
                    <a href="/" class="text-muted small text-decoration-none d-flex align-items-center justify-content-center">
                        <span class="material-symbols-outlined fs-6 me-1">arrow_back</span>
                        Back to Homepage
                    </a>
                </div>
            </form>
        </div>
        
        <p class="text-center mt-4 small text-muted">
            &copy; {{ date('Y') }} Manila LinkUp. System Version 1.0.4-Beta
        </p>
    </div>

</body>
</html>