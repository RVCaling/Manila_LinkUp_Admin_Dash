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
                        <input type="text" id="seekerSearchInput" class="form-control border-light bg-light rounded-start-pill px-4" placeholder="Search seeker..." style="min-width: 300px;">
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
                                <th class="text-muted small fw-bold">Document Type</th>
                                <th class="text-muted small fw-bold">Status</th>
                                <th class="text-muted small fw-bold">Location</th>
                                <th class="text-muted small fw-bold text-center">View</th>
                                <th class="text-muted small fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">1</td>
                                <td><span class="badge bg-light text-dark border">MLU-2026-0127</span></td>
                                <td class="fw-bold seeker-name">Gojo Satoru</td>
                                <td class="text-muted">gojo.s@jujutsu.edu</td>
                                <td><span class="text-primary fw-bold small"><span class="material-symbols-outlined align-middle fs-6">badge</span> National ID</span></td>
                                <td><span class="badge rounded-pill bg-warning text-dark seeker-status">Not Verified</span></td>
                                <td>Malate, Manila</td>
                                <td class="text-center">
                                    <button class="btn btn-dark btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#verificationModal"><span class="material-symbols-outlined fs-6 align-middle">visibility</span> View</button>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-success btn-sm rounded-3 px-3 action-approve-btn">Approve</button>
                                        <button class="btn btn-danger btn-sm rounded-3 px-3 action-reject-btn">Reject</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
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
                    <div id="modal-alerts-list"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 px-4 pt-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark text-white rounded-3 p-2 me-3">
                            <span class="material-symbols-outlined align-middle">assignment_ind</span>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Identity Verification - <span id="modalSeekerName">Gojo Satoru</span></h5>
                            <small class="text-warning fw-bold"><span class="dot bg-warning"></span> Pending Review: #SUB-88219</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="bg-black rounded-4 p-2 d-flex align-items-center justify-content-center mb-3" style="min-height: 320px; border: 1px solid #eee;">
                                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/96/SatoruGojomanga.png/250px-SatoruGojomanga.png" class="img-fluid rounded-3" alt="ID Document Preview">
                            </div>
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold small mb-2">Document Authenticity Check</h6>
                                <div class="row g-2 text-center">
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="extra-small text-muted">Hologram</div>
                                            <div class="small fw-bold text-success">Detected</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="extra-small text-muted">Tampering</div>
                                            <div class="small fw-bold text-success">Clear</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="extra-small text-muted">Edge Detection</div>
                                            <div class="small fw-bold text-success">Valid</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="verification-checks mb-3">
                                <div class="check-item d-flex justify-content-between align-items-center bg-success-subtle p-2 rounded-3 mb-2 px-3">
                                    <span class="small fw-bold text-success"><span class="material-symbols-outlined fs-6 align-middle me-1">check_circle</span> Face Match Score</span>
                                    <span class="small text-success fw-bold">98%</span>
                                </div>
                                <div class="check-item d-flex justify-content-between align-items-center bg-info-subtle p-2 rounded-3 mb-2 px-3">
                                    <span class="small fw-bold text-info"><span class="material-symbols-outlined fs-6 align-middle me-1">info</span> Data Consistency</span>
                                    <span class="small text-info fw-bold">High</span>
                                </div>
                            </div>

                            <div class="extracted-metadata">
                                <h6 class="text-muted small fw-bold text-uppercase mb-3">Extracted Metadata</h6>
                                <div class="d-flex justify-content-between mb-2 pb-1 border-bottom"><span class="small text-muted">FULL NAME</span><span class="small fw-bold text-end">GOJO SATORU</span></div>
                                <div class="d-flex justify-content-between mb-2 pb-1 border-bottom"><span class="small text-muted">ID NUMBER</span><span class="small fw-bold text-end">123-4567-890</span></div>
                                <div class="d-flex justify-content-between mb-2 pb-1 border-bottom"><span class="small text-muted">BIRTHDAY</span><span class="small fw-bold text-end">DEC 07, 1989</span></div>
                                <div class="d-flex justify-content-between mb-2 pb-1 border-bottom"><span class="small text-muted">EXPIRY DATE</span><span class="small fw-bold text-end">DEC 07, 2030</span></div>
                                <div class="d-flex justify-content-between mb-4 pb-1 border-bottom"><span class="small text-muted">BARANGAY</span><span class="small fw-bold text-end">Barangay 688, Malate</span></div>
                                
                                <div class="admin-notes">
                                    <label class="small text-muted fw-bold mb-2">VERIFICATION NOTES</label>
                                    <textarea id="adminNotesText" class="form-control bg-light border-0 small" rows="3" placeholder="Reviewer comments..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 justify-content-end">
                    <button type="button" id="btn-request-clarification" class="btn btn-link text-danger text-decoration-none fw-bold me-3">Request Clarification</button>
                    <button type="button" id="btn-verify-seeker" class="btn btn-primary px-4 py-2" style="background-color: #1B3E9C; border-radius: 8px;">Verify Seeker</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-4 text-center">
                    <div id="confirm-icon-container" class="mb-3"></div>
                    <h5 class="fw-bold" id="confirm-title">Are you sure?</h5>
                    <p class="text-muted small" id="confirm-text">Do you want to process this seeker?</p>
                    <div class="d-grid gap-2">
                        <button type="button" id="btn-confirm-submit" class="btn btn-primary rounded-3">Confirm</button>
                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Notification Logic
            const dummyNotifs = {
                notifications: [
                    { id: 1, title: 'New Seeker Registration', body: 'Maria Santos uploaded ID for verification.', time: '2 mins ago', unread: true },
                    { id: 2, title: 'System Alert', body: 'Server load reached 85% in Manila node.', time: '1 hour ago', unread: true },
                    { id: 3, title: 'New Employer', body: 'Logistics Co registered in Malate.', time: '3 hours ago', unread: true },
                    { id: 4, title: 'Verification Approved', body: 'Intramuros Tour is now active.', time: 'Yesterday', unread: false }
                ]
            };

            window.renderNotifications = function() {
                const list = document.getElementById('notification-list');
                const modalList = document.getElementById('modal-alerts-list');
                const countBadge = document.getElementById('notif-count');
                const unreadCount = dummyNotifs.notifications.filter(n => n.unread).length;
                
                countBadge.innerText = unreadCount;
                countBadge.style.display = unreadCount > 0 ? 'block' : 'none';

                const notificationHTML = dummyNotifs.notifications.map(n => `
                    <div class="notification-item p-3 ${n.unread ? 'unread' : ''}" onclick="markAsRead(${n.id})" style="cursor:pointer; border-bottom: 1px solid #f1f3f9;">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <p class="small fw-bold mb-1" style="color: #1B3E9C;">${n.title}</p>
                                <p class="extra-small text-muted mb-1">${n.body}</p>
                                <span class="extra-small text-secondary">${n.time}</span>
                            </div>
                            ${n.unread ? '<div class="bg-primary rounded-circle" style="width: 8px; height: 8px; margin-top: 5px;"></div>' : ''}
                        </div>
                    </div>
                `).join('');

                list.innerHTML = notificationHTML;
                if(modalList) modalList.innerHTML = notificationHTML;
            };

            window.markAsRead = function(id) {
                const notif = dummyNotifs.notifications.find(n => n.id === id);
                if (notif) notif.unread = false;
                renderNotifications();
            };

            window.clearNotifications = function() {
                dummyNotifs.notifications.forEach(n => n.unread = false);
                renderNotifications();
            };

            // 2. SEARCH Logic
            const searchInput = document.getElementById('seekerSearchInput');
            const tableBody = document.querySelector('#seekerTable tbody');

            function filterTable(query) {
                const rows = tableBody.querySelectorAll('tr:not(.no-result-row)');
                let visibleCount = 0;

                rows.forEach(row => {
                    const name = row.querySelector('.seeker-name').innerText.toLowerCase();
                    if (name.includes(query.toLowerCase())) {
                        row.style.display = "";
                        visibleCount++;
                    } else {
                        row.style.display = "none";
                    }
                });

                const existingNoResult = tableBody.querySelector('.no-result-row');
                if (visibleCount === 0) {
                    if (!existingNoResult) {
                        const noResultRow = document.createElement('tr');
                        noResultRow.className = 'no-result-row';
                        noResultRow.innerHTML = `<td colspan="9" class="text-center py-5 text-muted">
                            <span class="material-symbols-outlined fs-1 d-block mb-2">person_search</span>
                            User not found in database
                        </td>`;
                        tableBody.appendChild(noResultRow);
                    }
                } else if (existingNoResult) {
                    existingNoResult.remove();
                }
            }

            searchInput.addEventListener('input', (e) => filterTable(e.target.value));

            // Modal/Action logic
            let selectedSeeker = "";
            let currentAction = "";
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmActionModal'));
            const verificationModal = new bootstrap.Modal(document.getElementById('verificationModal'));

            function triggerConfirm(name, action) {
                selectedSeeker = name;
                currentAction = action;
                const title = document.getElementById('confirm-title');
                const text = document.getElementById('confirm-text');
                const iconBox = document.getElementById('confirm-icon-container');
                const confirmBtn = document.getElementById('btn-confirm-submit');

                if(action === 'Approve' || action === 'Verify') {
                    title.innerText = "Confirm Verification";
                    text.innerText = `Verify and approve account for ${name}? The seeker will be marked as verified.`;
                    iconBox.innerHTML = '<span class="material-symbols-outlined text-success fs-1">verified_user</span>';
                    confirmBtn.className = "btn btn-success rounded-3";
                } else if(action === 'Reject') {
                    title.innerText = "Confirm Rejection";
                    text.innerText = `Are you sure you want to reject ${name}?`;
                    iconBox.innerHTML = '<span class="material-symbols-outlined text-danger fs-1">cancel</span>';
                    confirmBtn.className = "btn btn-danger rounded-3";
                } else if(action === 'Clarification') {
                    title.innerText = "Request Clarification";
                    text.innerText = `Send clarification request to ${name}?`;
                    iconBox.innerHTML = '<span class="material-symbols-outlined text-warning fs-1">error</span>';
                    confirmBtn.className = "btn btn-warning rounded-3";
                }
                confirmModal.show();
            }

            // Table buttons
            document.querySelectorAll('.action-approve-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const name = e.target.closest('tr').querySelector('.seeker-name').innerText;
                    triggerConfirm(name, 'Approve');
                });
            });

            document.querySelectorAll('.action-reject-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const name = e.target.closest('tr').querySelector('.seeker-name').innerText;
                    triggerConfirm(name, 'Reject');
                });
            });

            // Modal: Verify Seeker Button
            document.getElementById('btn-verify-seeker').addEventListener('click', function() {
                const name = document.getElementById('modalSeekerName').innerText;
                verificationModal.hide();
                triggerConfirm(name, 'Verify');
            });

            // Modal: Request Clarification Button
            document.getElementById('btn-request-clarification').addEventListener('click', function() {
                const notes = document.getElementById('adminNotesText').value.trim();
                const name = document.getElementById('modalSeekerName').innerText;
                
                if (notes === "") {
                    alert("Please provide clarification details in the Verification Notes.");
                    document.getElementById('adminNotesText').focus();
                    return;
                }
                
                verificationModal.hide();
                triggerConfirm(name, 'Clarification');
            });

            // Final Confirmation
            document.getElementById('btn-confirm-submit').addEventListener('click', function() {
                confirmModal.hide();
                const alertBox = document.getElementById('js-success-alert');
                const alertMsg = document.getElementById('alert-message');
                
                alertBox.classList.remove('d-none');
                
                if(currentAction === 'Approve' || currentAction === 'Verify') {
                    alertMsg.innerText = `Seeker ${selectedSeeker} is now verified successfully.`;
                    // Update UI status in table (demo logic)
                    document.querySelectorAll('tr').forEach(row => {
                        const nameCell = row.querySelector('.seeker-name');
                        if(nameCell && nameCell.innerText === selectedSeeker) {
                            const statusBadge = row.querySelector('.seeker-status');
                            statusBadge.innerText = "Verified";
                            statusBadge.className = "badge rounded-pill bg-success text-white seeker-status";
                        }
                    });
                } else if(currentAction === 'Clarification') {
                    alertMsg.innerText = `Clarification request sent to ${selectedSeeker}.`;
                } else {
                    alertMsg.innerText = `Successfully processed ${currentAction} for ${selectedSeeker}.`;
                }
                
                setTimeout(() => { alertBox.classList.add('d-none'); }, 4000);
            });

            renderNotifications();
        });
    </script>
</body>
</html>