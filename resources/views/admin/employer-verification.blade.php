<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manila LinkUp | Employer Verification</title>
    
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
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">Employer Verification</h1>
                <p class="text-muted small">Review business permits and verify company accounts.</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <div class="notification-wrapper position-relative" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <input type="text" id="employerSearchInput" class="form-control border-light bg-light rounded-start-pill px-4" placeholder="Search company name..." style="min-width: 300px;">
                        <button class="btn btn-primary rounded-end-pill px-4" style="background-color: #1B3E9C; border: none;">Search</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-verification-table" id="employerTable">
                        <thead>
                            <tr>
                                <th class="text-muted small fw-bold">#</th>
                                <th class="text-muted small fw-bold">Company Code</th>
                                <th class="text-muted small fw-bold">Company Name</th>
                                <th class="text-muted small fw-bold">Document Type</th>
                                <th class="text-muted small fw-bold">Contact Person</th>
                                <th class="text-muted small fw-bold">Industry</th>
                                <th class="text-muted small fw-bold">Location</th>
                                <th class="text-muted small fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-muted fw-bold small">1</td> 
                                <td class="small fw-bold text-primary">EMP-2026-001</td>
                                <td class="fw-bold entity-name">Manila Tech Solutions</td>
                                <td><span class="text-dark small">Business Permit, NBI</span></td>
                                <td class="text-muted">Kento Nanami</td>
                                <td><span class="badge rounded-pill text-primary" style="background-color: #e7f0ff;">IT Services</span></td>
                                <td>Intramuros, Manila</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3 view-docs-btn" 
                                    data-bs-toggle="modal" data-bs-target="#verificationModal">
                                        <span class="material-symbols-outlined fs-6 align-middle me-1">description</span> 
                                        Review Documents
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-bold small">2</td>
                                <td class="small fw-bold text-primary">EMP-2026-042</td>
                                <td class="fw-bold entity-name">Binondo Logistics Corp</td>
                                <td><span class="text-dark small">National ID, DTI</span></td>
                                <td class="text-muted">Enrile Ponce</td>
                                <td><span class="badge rounded-pill text-primary" style="background-color: #e7f0ff;">Logistics</span></td>
                                <td>Binondo, Manila</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3 view-docs-btn" 
                                    data-bs-toggle="modal" data-bs-target="#verificationModal">
                                        <span class="material-symbols-outlined fs-6 align-middle me-1">description</span> 
                                        Review Documents
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <p class="text-muted small mb-0" id="user-count-text">Showing 2 employers in database</p>
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

    <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 px-4 pt-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark text-white rounded-3 p-2 me-3">
                            <span class="material-symbols-outlined align-middle">domain_verification</span>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Reviewing: <span id="modalEntityName">Manila Tech Solutions</span></h5>
                            <small class="text-muted">Verification Status: <span class="badge bg-warning-subtle text-warning">Pending Review</span></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3 border-end">
                            <h6 class="fw-bold mb-3 small text-uppercase">Submitted Documents</h6>
                            <div class="list-group list-group-flush" id="documentList">
                                <button type="button" class="list-group-item list-group-item-action active rounded-3 mb-2 border-0" 
                                data-doc-src="https://static0.cbrimages.com/wordpress/wp-content/uploads/2023/12/nanami-kento-jujutsu-kaisen.jpg?w=1200&h=675&fit=crop">
                                    <div class="d-flex align-items-center">
                                        <span class="material-symbols-outlined me-2 fs-5">article</span>
                                        <div>
                                            <div class="small fw-bold">Business Permit</div>
                                            <div class="extra-small text-muted">Uploaded: 04/20/26</div>
                                        </div>
                                    </div>
                                </button>
                                <button type="button" class="list-group-item list-group-item-action rounded-3 mb-2 border-0" data-doc-src="https://via.placeholder.com/600x800?text=DTI+Registration+Form">
                                    <div class="d-flex align-items-center">
                                        <span class="material-symbols-outlined me-2 fs-5">assignment_ind</span>
                                        <div>
                                            <div class="small fw-bold">DTI Registration</div>
                                            <div class="extra-small text-muted">Uploaded: 04/20/26</div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="bg-black rounded-4 p-2 d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 500px; max-height: 600px;">
                                <img src="https://via.placeholder.com/600x800?text=Business+Permit+2026" id="docImagePreview" class="img-fluid rounded-3" alt="Document">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="extracted-metadata mb-4">
                                <h6 class="text-muted small fw-bold text-uppercase mb-3">Company Information</h6>
                                <div class="mb-3">
                                    <label class="extra-small text-muted fw-bold">TIN NUMBER</label>
                                    <div class="fw-bold small">123-456-789-000</div>
                                </div>
                                <div class="mb-3">
                                    <label class="extra-small text-muted fw-bold">BUSINESS TYPE</label>
                                    <div class="fw-bold small">Corporation</div>
                                </div>
                                <hr>
                                <div class="admin-notes">
                                    <label class="small text-muted fw-bold mb-2">Internal Remarks</label>
                                    <textarea id="admin-remarks" class="form-control bg-light border-0 small" rows="4" placeholder="Add notes here..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-danger px-4 rounded-3 fw-bold me-auto" id="btn-show-reject-reason">
                        Reject Application
                    </button>
                    <button type="button" class="btn btn-light px-4 rounded-3 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btn-verify-entity" class="btn btn-primary px-4 rounded-3 fw-bold" style="background-color: #1B3E9C;">
                        Approve & Verify
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-danger">Rejection Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Please specify why this employer's verification was rejected. This will be sent to the employer.</p>
                    <select class="form-select mb-3 border-light bg-light" id="reject-reason-select">
                        <option value="Expired Documents">Expired Documents</option>
                        <option value="Blurry/Unreadable Image">Blurry/Unreadable Image</option>
                        <option value="Mismatched Business Name">Mismatched Business Name</option>
                        <option value="Invalid License Number">Invalid License Number</option>
                        <option value="other">Other Reason...</option>
                    </select>
                    <textarea class="form-control bg-light border-0 d-none" id="other-reason-text" rows="3" placeholder="Describe the issue..."></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-3 px-4" id="btn-confirm-reject">Confirm Rejection</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/employer-verification.js') }}"></script>
</body>
</html>