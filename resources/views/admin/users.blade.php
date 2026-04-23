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
    <style>
        .extra-small { font-size: 0.75rem; }
        .notification-item.unread { background-color: #f8f9ff; }
        .italic { font-style: italic; }
    </style>
</head>
<body class="bg-light d-flex">

    @include('admin.sidebar')

    <div class="main-content w-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">User Database</h1>
                <p class="text-muted small">Overview of all registered Seekers and Employers in Manila LinkUp.</p>
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
                        <div id="notification-list" style="max-height: 350px; overflow-y: auto;"></div>
                        <li class="p-2 text-center border-top">
                            <a href="#" class="text-muted extra-small text-decoration-none" data-bs-toggle="modal" data-bs-target="#alertsModal">View all alerts</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="js-success-alert" class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-none" role="alert">
            <span id="alert-message">Action processed successfully.</span>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex mb-4">
                    <div class="input-group w-auto">
                        <input type="text" name="fake_username" style="position:absolute; top:-9999px; left:-9999px;">
                       <input type="text" 
                        id="dbSearchInput" 
                        name="prevent_autofill_val_123"
                        class="form-control border-light bg-light rounded-start-pill px-4" 
                        placeholder="Search by name or email..." 
                        style="min-width: 350px;" 
                        autocomplete="new-password"
                        role="presentation">
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
                        <tbody id="user-table-body">
                            </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <p class="text-muted small mb-0" id="user-count-text">Showing 0 users in database</p>
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

    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" style="color: #1B3E9C;">User Profile Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <img id="modal-user-img" src="https://via.placeholder.com/150" class="rounded-circle mb-3 border p-1" style="width: 120px; height: 120px; object-fit: cover;">
                            <h5 id="modal-user-name" class="fw-bold mb-0">Name</h5>
                            <p id="modal-user-type" class="text-muted small mb-2">Account Type</p>
                            <div id="modal-user-status" class="mb-3"></div>
                            
                            <div id="suspension-reason-box" class="mt-3 p-2 bg-danger-subtle rounded-3 d-none">
                                <p class="extra-small fw-bold text-danger mb-1 text-uppercase">Reason for Suspension:</p>
                                <p id="modal-suspension-text" class="small text-danger mb-0 italic"></p>
                            </div>
                        </div>
                        <div class="col-md-8 px-4">
                            <h6 class="fw-bold text-uppercase small text-muted mb-3">General Information</h6>
                            <div class="row mb-3">
                                <div class="col-6 mb-3">
                                    <p class="small text-muted mb-0">Email Address</p>
                                    <p id="modal-user-email" class="fw-bold">email@example.com</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <p class="small text-muted mb-0">Phone Number</p>
                                    <p id="modal-user-phone" class="fw-bold">+63 9XX XXX XXXX</p>
                                </div>
                                <div class="col-6">
                                    <p class="small text-muted mb-0">Date Joined</p>
                                    <p id="modal-user-joined" class="fw-bold">January 15, 2026</p>
                                </div>
                                <div class="col-6">
                                    <p class="small text-muted mb-0">Skills / Specialization</p>
                                    <p id="modal-user-skills" class="fw-bold">N/A</p>
                                </div>
                            </div>
                            <h6 class="fw-bold text-uppercase small text-muted mb-3">Work History / About</h6>
                            <p id="modal-user-history" class="small text-secondary">No work history provided.</p>
                            
                            <hr class="my-4">
                            
                            <div class="d-flex gap-2" id="modal-action-buttons">
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="adminActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-danger" id="actionModalTitle">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="action-warning-box" class="alert alert-danger border-0 small mb-4">
                        <strong>Warning:</strong> This action is permanent or restricts user access. Please provide administrative credentials to proceed.
                    </div>
                    
                    <div id="suspend-fields" class="d-none">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Suspension Duration (Days)</label>
                            <select id="suspend-days" class="form-select form-select-sm rounded-3">
                                <option value="3">3 Days</option>
                                <option value="7">7 Days</option>
                                <option value="30">30 Days</option>
                                <option value="permanent">Indefinite</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Reason for Suspension</label>
                            <textarea id="suspend-reason" class="form-control form-control-sm rounded-3" rows="2" placeholder="Explain why the account is being restricted..."></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Admin Password</label>
                        <input type="password" id="admin-pass-verify" class="form-control rounded-3" placeholder="Enter your admin password">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirm-action-btn" class="btn btn-danger rounded-pill px-4" onclick="executeAdminAction()">Confirm & Execute</button>
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
    <script src="{{ asset('js/user-database.js') }}"></script>
</body>
</html>