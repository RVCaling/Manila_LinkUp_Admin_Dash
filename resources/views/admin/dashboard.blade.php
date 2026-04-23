<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manila LinkUp | Dashboard Overview</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light d-flex">

    @include('admin.sidebar')

    <div class="main-content w-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">Dashboard Overview</h1>
                <p class="text-muted small">Welcome back, Admin! Today is {{ now()->format('F d, Y') }}</p>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <div class="notification-wrapper me-4 position-relative" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-symbols-outlined fs-2 text-muted" style="cursor: pointer;">notifications</span>
                        <span id="notif-count" class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-light" style="padding: 5px; font-size: 10px;">0</span>
                    </div>
                    
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-0 mt-2" aria-labelledby="notificationDropdown" style="width: 320px; overflow: hidden;">
                        <li class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">Notifications</h6>
                                <button class="btn btn-sm text-primary p-0 extra-small" onclick="clearNotifications()">Mark all as read</button>
                            </div>
                        </li>
                        <div id="notification-list" style="max-height: 350px; overflow-y: auto;">
                            </div>
                        <li class="p-2 text-center border-top">
                            <a href="#" class="text-muted extra-small text-decoration-none" data-bs-toggle="modal" data-bs-target="#alertsModal">View all alerts</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card p-3 bg-white rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold mb-1">Total Users</p>
                            <h3 class="fw-bold mb-0" id="total-users">5,432</h3>
                            <small class="text-success fw-bold">+12% this week</small>
                        </div>
                        <span class="material-symbols-outlined text-primary bg-light p-2 rounded-3">groups</span>
                    </div>
                    <hr class="my-2 opacity-25">
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Seekers: <b id="stat-seekers">3,412</b></span>
                        <span>Employers: <b id="stat-employers">2,020</b></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-3 bg-white rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold mb-1">Pending Seekers</p>
                            <h3 class="fw-bold mb-0 text-warning" id="pending-seekers">128</h3>
                            <small class="text-warning fw-bold">+8% today</small>
                        </div>
                        <span class="material-symbols-outlined text-warning bg-light p-2 rounded-3">pending_actions</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-3 bg-white rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold mb-1">Verified Users</p>
                            <h3 class="fw-bold mb-0 text-success" id="verified-users">5,304</h3>
                            <small class="text-success fw-bold">+20% active</small>
                        </div>
                        <span class="material-symbols-outlined text-success bg-light p-2 rounded-3">verified_user</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-3 bg-white rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold mb-1">System Alerts</p>
                            <h3 class="fw-bold mb-0 text-danger" id="system-alerts">14</h3>
                            <small class="text-danger fw-bold">-15% from yesterday</small>
                        </div>
                        <span class="material-symbols-outlined text-danger bg-light p-2 rounded-3">report</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">User Activity Trends (Last 30 Days)</h5>
                        <select class="form-select form-select-sm w-auto rounded-pill border-light bg-light">
                            <option>Manila City</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="userTrendsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Recent Activity</h5>
                        <button class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#activityModal">More</button>
                    </div>
                    <div id="activity-feed"></div>
                </div>

                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Verification Queue</h5>
                        <button class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#queueModal">View All</button>
                    </div>
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>Status</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="queue-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alertsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" style="color: #1B3E9C;">System Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-end">
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 extra-small" onclick="clearNotifications()">Mark all as read</button>
                </div>
                <div class="modal-body p-0">
                    <div id="modal-alerts-list">
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="activityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" style="color: #1B3E9C;">All Recent Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="modal-activity-feed"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="queueModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" style="color: #1B3E9C;">Full Verification Queue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Status</th>
                                <th>Name</th>
                                <th>Role Type</th>
                                <th>Date Submitted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="modal-queue-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.adminRoutes = {
            seekers: "{{ route('admin.seekers') }}",
            employers: "{{ route('admin.employers') }}"
        };
    </script>

    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>