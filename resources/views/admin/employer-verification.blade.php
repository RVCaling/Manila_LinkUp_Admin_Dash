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
                                <th class="text-muted small fw-bold">Contact Person</th>
                                <th class="text-muted small fw-bold">Industry</th>
                                <th class="text-muted small fw-bold">Location</th>
                                <th class="text-muted small fw-bold text-center">View</th>
                                <th class="text-muted small fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">1</td>
                                <td><span class="badge bg-light text-dark border">EMP-2026-001</span></td>
                                <td class="fw-bold entity-name">Manila Tech Solutions</td>
                                <td class="text-muted">Kento Nanami</td>
                                <td><span class="badge rounded-pill text-primary" style="background-color: #e7f0ff;">IT Services</span></td>
                                <td>Intramuros, Manila</td>
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
                            <span class="material-symbols-outlined align-middle">domain_verification</span>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Business Verification - <span id="modalEntityName">Manila Tech Solutions</span></h5>
                            <small class="text-warning fw-bold">Pending Review: Business Permit #2026-9901</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="bg-black rounded-4 p-2 d-flex align-items-center justify-content-center" style="min-height: 300px;">
                                <img src="https://via.placeholder.com/400x550?text=Business+Permit+Preview" class="img-fluid rounded-3" alt="Document">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="verification-checks mb-4">
                                <div class="check-item d-flex justify-content-between align-items-center bg-success-subtle p-2 rounded-3 mb-2 px-3">
                                    <span class="small fw-bold text-success"><span class="material-symbols-outlined fs-6 align-middle me-1">check_circle</span> SEC/DTI Registered</span>
                                    <span class="small text-success fw-bold">Verified</span>
                                </div>
                            </div>
                            <div class="extracted-metadata">
                                <h6 class="text-muted small fw-bold text-uppercase mb-3">Company Metadata</h6>
                                <div class="d-flex justify-content-between mb-2"><span class="small text-muted">TIN NUMBER</span><span class="small fw-bold">123-456-789-000</span></div>
                                <div class="d-flex justify-content-between mb-2"><span class="small text-muted">FOUNDED</span><span class="small fw-bold">2015</span></div>
                                <div class="admin-notes mt-4">
                                    <label class="small text-muted fw-bold mb-2">ADMIN NOTES</label>
                                    <textarea class="form-control bg-light border-0 small" rows="3" placeholder="Check validity of business license..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" id="btn-request-clarification" class="btn btn-link text-danger text-decoration-none fw-bold me-3">Request Clarification</button>
                    <button type="button" id="btn-verify-entity" class="btn btn-primary px-4 py-2" style="background-color: #1B3E9C; border-radius: 8px;">Verify Employer</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-4 text-center">
                    <div id="confirm-icon-container" class="mb-3"></div>
                    <h5 class="fw-bold" id="confirm-title">Confirm Action</h5>
                    <p class="text-muted small" id="confirm-text"></p>
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
            // 1. Notification System Logic (Same as User Database)
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

            // 2. SEARCH & FILTER Logic with "Not Found" message
            const searchInput = document.getElementById('employerSearchInput');
            const tableBody = document.querySelector('#employerTable tbody');
            
            function filterTable(query) {
                const rows = tableBody.querySelectorAll('tr:not(.no-result-row)');
                let visibleCount = 0;

                rows.forEach(row => {
                    const name = row.querySelector('.entity-name').innerText.toLowerCase();
                    if (name.includes(query.toLowerCase())) {
                        row.style.display = "";
                        visibleCount++;
                    } else {
                        row.style.display = "none";
                    }
                });

                // Check if any results were found
                const existingNoResult = tableBody.querySelector('.no-result-row');
                if (visibleCount === 0) {
                    if (!existingNoResult) {
                        const noResultRow = document.createElement('tr');
                        noResultRow.className = 'no-result-row';
                        noResultRow.innerHTML = `<td colspan="8" class="text-center py-5 text-muted">
                            <span class="material-symbols-outlined fs-1 d-block mb-2">search_off</span>
                            Employer not found in database
                        </td>`;
                        tableBody.appendChild(noResultRow);
                    }
                } else if (existingNoResult) {
                    existingNoResult.remove();
                }
            }

            searchInput.addEventListener('input', (e) => filterTable(e.target.value));

            // Existing logic
            let selectedName = "";
            let currentAction = "";
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmActionModal'));
            const viewModal = new bootstrap.Modal(document.getElementById('verificationModal'));

            function triggerConfirm(name, action) {
                selectedName = name;
                currentAction = action;
                document.getElementById('confirm-title').innerText = `Confirm ${action}`;
                document.getElementById('confirm-text').innerText = `Apply ${action} to ${name}?`;
                const iconBox = document.getElementById('confirm-icon-container');
                iconBox.innerHTML = action === 'Approve' ? 
                    '<span class="material-symbols-outlined text-success fs-1">verified_user</span>' : 
                    '<span class="material-symbols-outlined text-danger fs-1">block</span>';
                confirmModal.show();
            }

            document.querySelectorAll('.action-approve-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const name = e.target.closest('tr').querySelector('.entity-name').innerText;
                    triggerConfirm(name, 'Approve');
                });
            });

            document.querySelectorAll('.action-reject-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const name = e.target.closest('tr').querySelector('.entity-name').innerText;
                    triggerConfirm(name, 'Reject');
                });
            });

            document.getElementById('btn-verify-entity').addEventListener('click', () => {
                const name = document.getElementById('modalEntityName').innerText;
                viewModal.hide();
                triggerConfirm(name, 'Approve');
            });

            document.getElementById('btn-confirm-submit').addEventListener('click', () => {
                confirmModal.hide();
                const alert = document.getElementById('js-success-alert');
                alert.classList.remove('d-none');
                document.getElementById('alert-message').innerText = `Employer ${selectedName} has been ${currentAction.toLowerCase()}d.`;
                setTimeout(() => { alert.classList.add('d-none'); }, 4000);
            });

            // Initial Renders
            renderNotifications();
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('search')) {
                searchInput.value = urlParams.get('search');
                filterTable(urlParams.get('search'));
            }
        });
    </script>
</body>
</html>