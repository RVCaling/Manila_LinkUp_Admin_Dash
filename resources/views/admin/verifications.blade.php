<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manila LinkUp | Verification Queue</title>

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
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">Verification Queue</h1>
                <p class="text-muted small">Review submitted IDs and clearances for seekers and employers.</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <div class="notification-wrapper me-4 position-relative" id="notificationDropdown"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-symbols-outlined fs-2 text-muted" style="cursor: pointer;">notifications</span>
                        <span id="notif-count" class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-light"
                              style="padding: 5px; font-size: 10px;">0</span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-0 mt-2"
                        aria-labelledby="notificationDropdown" style="width: 320px; overflow: hidden;">
                        <li class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">Notifications</h6>
                                <button class="btn btn-sm text-primary p-0 extra-small" onclick="clearNotifications()">Mark all as read</button>
                            </div>
                        </li>
                        <div id="notification-list" style="max-height: 350px; overflow-y: auto;"></div>
                        <li class="p-2 text-center border-top">
                            <a href="#" class="text-muted extra-small text-decoration-none">View all alerts</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="js-success-alert" class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-none" role="alert">
            <span id="alert-message">Action processed successfully.</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="text-muted small mb-0">
                <span id="queue-count-text">{{ count($pending) }}</span> pending verification{{ count($pending) !== 1 ? 's' : '' }}
            </p>
        </div>

        <div class="row g-3" id="verif-card-grid">
            @forelse($pending as $user)
            <div class="col-md-4 col-lg-3" id="verif-card-{{ $user['uid'] }}">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3 d-flex flex-column">

                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0 small">{{ $user['name'] }}</h6>
                            <span class="badge {{ $user['type'] === 'employer' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }}">
                                {{ ucfirst($user['type']) }}
                            </span>
                        </div>

                        <p class="extra-small text-muted mb-3">
                            <span class="material-symbols-outlined fs-6 align-middle">schedule</span>
                            {{ $user['submittedAt'] ? \Carbon\Carbon::parse($user['submittedAt'])->diffForHumans() : 'Date unknown' }}
                        </p>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <p class="extra-small text-muted fw-bold mb-1 text-uppercase">Valid ID</p>
                                @if($user['validIdUrl'])
                                <a href="{{ $user['validIdUrl'] }}" target="_blank">
                                    <img src="{{ $user['validIdUrl'] }}" class="img-fluid rounded-3"
                                         style="height: 80px; width: 100%; object-fit: cover; cursor: zoom-in;" alt="Valid ID">
                                </a>
                                @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 80px;">
                                    <span class="material-symbols-outlined text-muted">hide_image</span>
                                </div>
                                @endif
                            </div>
                            <div class="col-6">
                                <p class="extra-small text-muted fw-bold mb-1 text-uppercase">Clearance</p>
                                @if($user['clearanceUrl'])
                                <a href="{{ $user['clearanceUrl'] }}" target="_blank">
                                    <img src="{{ $user['clearanceUrl'] }}" class="img-fluid rounded-3"
                                         style="height: 80px; width: 100%; object-fit: cover; cursor: zoom-in;" alt="Clearance">
                                </a>
                                @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 80px;">
                                    <span class="material-symbols-outlined text-muted">hide_image</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-auto d-grid gap-2">
                            <button class="btn btn-sm btn-primary rounded-3 fw-bold"
                                    style="background-color: #1B3E9C; border: none;"
                                    data-uid="{{ $user['uid'] }}"
                                    data-type="{{ $user['type'] }}"
                                    data-name="{{ $user['name'] }}"
                                    onclick="approveUser(this)">
                                <span class="material-symbols-outlined fs-6 align-middle me-1">check_circle</span>
                                Approve
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-3 fw-bold"
                                    data-uid="{{ $user['uid'] }}"
                                    data-type="{{ $user['type'] }}"
                                    data-name="{{ $user['name'] }}"
                                    onclick="openRejectModal(this)">
                                <span class="material-symbols-outlined fs-6 align-middle me-1">cancel</span>
                                Reject
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            @empty
            <div class="col-12" id="empty-state">
                <div class="text-center py-5 text-muted">
                    <span class="material-symbols-outlined fs-1 d-block mb-2">task_alt</span>
                    <p class="fw-bold mb-0">No pending verifications.</p>
                    <p class="small">All users are verified or the queue is clear.</p>
                </div>
            </div>
            @endforelse
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
                    <p class="text-muted small">Please specify why <strong id="reject-target-name"></strong>'s verification was rejected. This will be sent to the user.</p>
                    <select class="form-select mb-3 border-light bg-light" id="reject-reason-select">
                        <option value="Expired Documents">Expired Documents</option>
                        <option value="Blurry/Unreadable Image">Blurry/Unreadable Image</option>
                        <option value="Mismatched Information">Mismatched Information</option>
                        <option value="Suspected Tampering">Suspected Tampering</option>
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

    <script>window.pendingVerifications = @json($pending);</script>
    <script src="{{ asset('js/verifications.js') }}"></script>
</body>
</html>
