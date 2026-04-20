<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manila LinkUp | User Database</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-verification.css') }}">
</head>
<body class="bg-light d-flex">

    @include('admin.sidebar')

    <div class="main-content w-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">User Database</h1>
                <p class="text-muted small">Overview of all registered Seekers and Employers in Manila LinkUp.</p>
            </div>
            <div class="notification-wrapper position-relative">
                <span class="material-symbols-outlined fs-2 text-muted" style="cursor: pointer;">notifications</span>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-light" style="padding: 5px; font-size: 10px;">5</span>
            </div>
        </div>

        <div id="js-success-alert" class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-none" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
            <span id="alert-message">Action processed successfully.</span>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex mb-4">
                    <div class="input-group w-auto">
                        <input type="text" id="userSearchInput" class="form-control border-light bg-light rounded-start-pill px-4" placeholder="Search by name or email..." style="min-width: 350px;">
                        <button class="btn btn-primary rounded-end-pill px-4" style="background-color: #1B3E9C; border: none;">Search</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-verification-table" id="userTable">
                        <thead>
                            <tr>
                                <th class="text-muted small fw-bold">#</th>
                                <th class="text-muted small fw-bold">Name</th>
                                <th class="text-muted small fw-bold">Email</th>
                                <th class="text-muted small fw-bold">Account Type</th>
                                <th class="text-muted small fw-bold">Status</th>
                                <th class="text-muted small fw-bold">Verification Needs</th>
                                <th class="text-muted small fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- DUMMY DATA: SELLING POINT - Easy to replace with @foreach later --}}
                            <tr>
                                <td class="fw-bold">1</td>
                                <td class="fw-bold user-name">Josh Wayne</td>
                                <td class="text-muted">josh.wayne@email.com</td>
                                <td><span class="badge rounded-pill text-info border border-info" style="background-color: #f0faff;">Seeker</span></td>
                                <td><span class="badge bg-warning-subtle text-warning border border-warning px-3">Pending</span></td>
                                <td class="small text-muted italic">Valid ID, NBI Clearance</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3">Manage</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">2</td>
                                <td class="fw-bold user-name">SM Manila (HR)</td>
                                <td class="text-muted">hr@sm-manila.com.ph</td>
                                <td><span class="badge rounded-pill text-primary border border-primary" style="background-color: #eef2ff;">Employer</span></td>
                                <td><span class="badge bg-success-subtle text-success border border-success px-3">Verified</span></td>
                                <td class="small text-success fw-bold">Complete</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3">Manage</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">3</td>
                                <td class="fw-bold user-name">Gojo Satoru</td>
                                <td class="text-muted">gojo.s@jujutsu.edu</td>
                                <td><span class="badge rounded-pill text-info border border-info" style="background-color: #f0faff;">Seeker</span></td>
                                <td><span class="badge bg-success-subtle text-success border border-success px-3">Verified</span></td>
                                <td class="small text-success fw-bold">Complete</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3">Manage</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">4</td>
                                <td class="fw-bold user-name">Juan Dela Cruz</td>
                                <td class="text-muted">juan.dc@gmail.com</td>
                                <td><span class="badge rounded-pill text-info border border-info" style="background-color: #f0faff;">Seeker</span></td>
                                <td><span class="badge bg-danger-subtle text-danger border border-danger px-3">Suspended</span></td>
                                <td class="small text-muted">Re-upload Documents</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3">Manage</button>
                                </td>
                            </tr>
                            {{-- END DUMMY DATA --}}
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <p class="text-muted small mb-0">Showing 4 users in database</p>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#" style="background-color: #1B3E9C; border-color: #1B3E9C;">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Real-time Search Feature
            const searchInput = document.getElementById('userSearchInput');
            const tableBody = document.querySelector('#userTable tbody');
            const rows = tableBody.querySelectorAll('tr');

            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();

                rows.forEach(row => {
                    const userName = row.querySelector('.user-name').innerText.toLowerCase();
                    const userEmail = row.cells[2].innerText.toLowerCase();
                    
                    if (userName.includes(term) || userEmail.includes(term)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>
</html>