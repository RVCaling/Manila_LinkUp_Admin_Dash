<div class="sidebar shadow d-flex flex-column" style="height: 100vh;">
    
    <div class="sidebar-header">
        <div class="admin-logo-container">
            <img src="{{ asset('images/logo.svg') }}" alt="Admin" class="admin-logo">
        </div>
        <h3 class="fw-bold text-white mt-3 mb-0">ADMIN</h3>
        <p class="sidebar-subtitle">MANAGEMENT CONSOLE</p>
    </div>

    <nav class="sidebar-nav flex-grow-1">
        <a href="{{ route('admin.dashboard') }}" class="nav-link-custom {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">home</span> Dashboard
        </a>

        <a href="{{ route('admin.users') }}" class="nav-link-custom {{ Request::is('admin/users*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">group</span> User Database
        </a>
        
        <a href="{{ route('admin.seekers') }}" class="nav-link-custom {{ Request::is('admin/seekers*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">person_search</span> Seeker Verification
        </a>

        <a href="{{ route('admin.employers') }}" class="nav-link-custom {{ Request::is('admin/employers*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">business_center</span> Employer Verification
        </a>

        <a href="{{ route('admin.analytics') }}" class="nav-link-custom {{ Request::is('admin/analytics*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">bar_chart</span> Analytics
        </a>

        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <a href="#" class="nav-link-custom text-danger logout-btn" onclick="confirmLogout(event)">
            <span class="material-symbols-outlined">logout</span> Logout
        </a>
    </nav>

    <div class="sidebar-footer-section">
        <a href="{{ route('admin.profile') }}" class="text-decoration-none">
            <div class="admin-pill-card {{ Request::is('admin/profile*') ? 'pill-active' : '' }}">
                <img src="{{ asset('images/admin_profile_pic.svg') }}" alt="Admin" class="pill-avatar">
                <div class="pill-text">
                    <span class="pill-name">Admin Portal</span>
                    <span class="pill-role">System Overseer</span>
                </div>
                <span class="material-symbols-outlined ms-auto text-muted fs-5">settings</span>
            </div>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmLogout(event) {
        event.preventDefault(); 
        
        Swal.fire({
            title: 'Sign Out?',
            text: "Are you sure you want to end your session?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1B3E9C', 
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>