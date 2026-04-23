<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manila LinkUp | Analytics & Reports</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-analytics.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light d-flex">

    @include('admin.sidebar')

    <div class="main-content w-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">System Analytics</h1>
                <p class="text-muted small">Visualizing platform growth and engagement metrics.</p>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <div class="notification-wrapper me-2 position-relative" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <div id="notification-list" style="max-height: 350px; overflow-y: auto;"></div>
                        <li class="p-2 text-center border-top">
                            <a href="#" class="text-muted extra-small text-decoration-none" data-bs-toggle="modal" data-bs-target="#alertsModal">View all alerts</a>
                        </li>
                    </ul>
                </div>

                <button class="btn btn-white bg-white border shadow-sm rounded-pill px-3 py-2 small fw-bold">
                    <span class="material-symbols-outlined align-middle fs-5">calendar_month</span> Last 30 Days
                </button>
                <button class="btn btn-primary shadow-sm rounded-pill px-3 py-2 small fw-bold" style="background-color: #1B3E9C;">
                    <span class="material-symbols-outlined align-middle fs-5">file_download</span> Export
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm border-start border-indigo border-4">
                    <p class="text-muted small fw-bold mb-1">User Retention Rate</p>
                    <div class="d-flex align-items-end gap-2">
                        <h2 class="fw-bold mb-0">84.2%</h2>
                        <small class="text-success fw-bold mb-1"><span class="material-symbols-outlined fs-6 align-middle">trending_up</span> +2.4%</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm border-start border-teal border-4">
                    <p class="text-muted small fw-bold mb-1">Avg. Verification Time</p>
                    <div class="d-flex align-items-end gap-2">
                        <h2 class="fw-bold mb-0">4.5h</h2>
                        <small class="text-success fw-bold mb-1"><span class="material-symbols-outlined fs-6 align-middle">bolt</span> -1.2h</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm border-start border-coral border-4">
                    <p class="text-muted small fw-bold mb-1">Job Match Success</p>
                    <div class="d-flex align-items-end gap-2">
                        <h2 class="fw-bold mb-0">1,240</h2>
                        <small class="text-muted small mb-1">Total Hired</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <h5 class="fw-bold mb-4">Users by District</h5>
                    <div class="chart-container" style="position: relative; height:250px;">
                        <canvas id="districtChart"></canvas>
                    </div>
                    <div class="mt-4" id="district-legend"></div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Monthly Registration Growth</h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary active">2026</button>
                            <button class="btn btn-outline-secondary">2025</button>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 350px;">
                        <canvas id="growthChart"></canvas>
                    </div>
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
                    <div id="modal-alerts-list"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/analytics.js') }}"></script>
</body>
</html>