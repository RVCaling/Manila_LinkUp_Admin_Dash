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

            <form id="loginForm" action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="id_token" id="id_token">
                <input type="hidden" name="refresh_token" id="refresh_token">

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <span class="material-symbols-outlined fs-5 text-muted">mail</span>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control bg-light border-start-0" placeholder="admin@manilalinkup.ph" required autofocus>
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
                        <input type="password" name="password" id="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                    </div>
                </div>

                <div id="login-error" class="alert alert-danger py-2 small text-center mb-3 border-0 shadow-sm d-none" role="alert">
                    <span class="material-symbols-outlined fs-6 align-middle">error</span>
                    <span id="login-error-msg"></span>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Keep me logged in</label>
                </div>

                <button type="submit" id="loginBtn" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3" style="background-color: #1B3E9C;">
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

    <script type="module">
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/11.7.1/firebase-app.js';
        import { getAuth, signInWithEmailAndPassword } from 'https://www.gstatic.com/firebasejs/11.7.1/firebase-auth.js';

        const app = initializeApp({
            apiKey:     '{{ env("FIREBASE_WEB_API_KEY") }}',
            authDomain: 'manilalinkup.firebaseapp.com',
            projectId:  'manilalinkup',
        });

        const auth = getAuth(app);

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn      = document.getElementById('loginBtn');
            const errorBox = document.getElementById('login-error');
            const errorMsg = document.getElementById('login-error-msg');
            const email    = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            btn.disabled    = true;
            btn.textContent = 'Signing in…';
            errorBox.classList.add('d-none');

            try {
                const credential = await signInWithEmailAndPassword(auth, email, password);
                const idToken    = await credential.user.getIdToken();

                document.getElementById('id_token').value      = idToken;
                document.getElementById('refresh_token').value = credential.user.stsTokenManager.refreshToken;

                e.target.submit();
            } catch (err) {
                btn.disabled    = false;
                btn.textContent = 'Sign In';
                errorBox.classList.remove('d-none');

                const msgs = {
                    'auth/invalid-credential':    'Invalid email or password.',
                    'auth/user-disabled':          'This account has been disabled.',
                    'auth/too-many-requests':      'Too many attempts. Try again later.',
                };
                errorMsg.textContent = msgs[err.code] ?? 'Sign in failed. Please try again.';
            }
        });
    </script>
</body>
</html>