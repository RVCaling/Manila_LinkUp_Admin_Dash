<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manila LinkUp | Seeker Verification</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-verification.css') }}">
    <style>
        .extra-small { font-size: 0.75rem; }
        .notification-item.unread { background-color: #f8f9ff; }
    </style>
</head>
<body class="bg-light d-flex">

    @include('admin.sidebar')

    <div class="main-content w-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">Seeker Verification</h1>
                <p class="text-muted small">Manage and verify job seeker accounts for Manila City.</p>
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
                        <input type="text" 
                            id="seekerSearchInput" 
                            class="form-control border-light bg-light rounded-start-pill px-4" 
                            placeholder="Search by name or code..." 
                            style="min-width: 350px;">
                        <button class="btn btn-primary rounded-end-pill px-4" style="background-color: #1B3E9C; border: none;">Search</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-verification-table" id="seekerTable">
                        <thead>
                            <tr>
                                <th class="text-muted small fw-bold">#</th>
                                <th class="text-muted small fw-bold">Seeker Code</th>
                                <th class="text-muted small fw-bold">Name</th>
                                <th class="text-muted small fw-bold">Email</th>
                                <th class="text-muted small fw-bold">Documents</th>
                                <th class="text-muted small fw-bold">Status</th>
                                <th class="text-muted small fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-muted fw-bold small">1</td>
                                <td class="small fw-bold text-primary">SKR-2026-0127</td>
                                <td class="fw-bold seeker-name">Gojo Satoru</td>
                                <td class="text-muted small">gojo.s@jujutsu.edu</td>
                                <td class="small text-muted">National ID, NBI</td>
                                <td><span class="badge bg-warning-subtle text-warning border border-warning px-3 seeker-status">Pending</span></td>
                                <td class="text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3" onclick="openVerificationModal(1)">
                                        <span class="material-symbols-outlined fs-6 align-middle me-1">description</span>Review Documents
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <p class="text-muted small mb-0" id="user-count-text">Showing 1 seekers in database</p>
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
                        <div class="bg-primary text-white rounded-3 p-2 me-3" style="background-color: #1B3E9C !important;">
                            <span class="material-symbols-outlined align-middle">verified_user</span>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Document Verification</h5>
                            <p class="text-muted small mb-0">
                                <span id="modalSeekerName">---</span> | 
                                <span id="modalSeekerCode" class="fw-bold text-primary">SKR-0000</span></p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-3 border-end">
                            <h6 class="fw-bold small text-muted text-uppercase mb-3">Files to Verify</h6>
                            <div class="list-group list-group-flush" id="documentList">
                                </div>
                        </div>

                        <div class="col-md-9 px-4">
                            <div id="documentPreviewContainer">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="bg-black rounded-4 d-flex align-items-center justify-content-center mb-3" style="min-height: 400px; border: 1px solid #eee; overflow: hidden;">
                                            <img id="docImagePreview" src="" class="img-fluid" alt="Document Preview">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="p-3 bg-light rounded-4 mb-4">
                                            <h6 class="fw-bold small mb-3">AI Comparison Score</h6>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="extra-small text-muted">Face Matching</span>
                                                <span class="badge bg-success">98% Match</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 98%"></div>
                                            </div>
                                        </div>

                                        <div class="extracted-metadata mb-4">
                                            <h6 class="text-muted small fw-bold text-uppercase mb-3">Extracted Data</h6>
                                            <div id="metadataFields">
                                                </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="small text-muted fw-bold mb-2">Reviewer Notes</label>
                                            <textarea id="adminNotesText" class="form-control bg-light border-0 small" rows="3" placeholder="Add observations here..."></textarea>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary rounded-pill py-2" onclick="processDoc('verify')"> Approve Document </button>
                                            <button class="btn btn-outline-danger rounded-pill py-2" onclick="processDoc('reject')"> Reject with Reason </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-danger">Rejection Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Please specify why this document or seeker is being rejected.</p>
                    <select id="rejectReasonSelect" class="form-select mb-3 rounded-3">
                        <option value="Blurry Image">Image is too blurry / unreadable</option>
                        <option value="Expired Document">Document has expired</option>
                        <option value="Mismatch Information">Information doesn't match profile</option>
                        <option value="Tampered Document">Suspected tampering/editing</option>
                        <option value="Other">Other (Write below)</option>
                    </select>
                    <textarea id="rejectReasonCustom" class="form-control rounded-3" rows="3" placeholder="Additional details..."></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger rounded-pill px-4" onclick="confirmRejection()">Submit Rejection</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/seeker-verification.js') }}"></script>
</body>
</html>