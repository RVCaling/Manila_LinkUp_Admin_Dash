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
                        <input type="text" id="userSearchInput" class="form-control border-light bg-light rounded-start-pill px-4" placeholder="Search by name or email..." style="min-width: 350px;" autocomplete="off">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('userSearchInput');
            searchInput.value = '';

            // 1. Data Store
            const usersData = {
                1: { id: 1, name: 'Josh Wayne', email: 'josh.wayne@email.com', phone: '+63 912 345 6789', joined: 'March 10, 2026', type: 'Seeker', status: 'Pending', skills: 'Construction, Carpentry', history: 'Formerly worked as a site helper in QC. Looking for temporary labor roles.', img: 'https://i.pravatar.cc/150?u=josh', suspensionReason: '', needs: 'Valid ID, NBI Clearance' },
                2: { id: 2, name: 'SM Manila (HR)', email: 'hr@sm-manila.com.ph', phone: '+63 2 8523 7044', joined: 'January 05, 2026', type: 'Employer', status: 'Verified', skills: 'Retail, Customer Service', history: 'Official HR account for SM City Manila. Recruiting for seasonal sales associates.', img: 'https://via.placeholder.com/150', suspensionReason: '', needs: 'Complete' },
                3: { id: 3, name: 'Mark Rivera', email: 'm.rivera@email.com', phone: '+63 998 765 4321', joined: 'February 20, 2026', type: 'Seeker', status: 'Suspended', skills: 'Delivery, Logistics', history: 'Experienced motorcycle rider. Account suspended due to frequent cancellations.', img: 'https://i.pravatar.cc/150?u=mark', suspensionReason: 'Policy Violation: Excessive job cancellations (3 occurrences in 24 hours).', needs: 'Reason: Policy Violation' }
            };

            const dummyNotifs = {
                notifications: [
                    { id: 1, title: 'New Seeker Registration', body: 'Maria Santos uploaded ID for verification.', time: '2 mins ago', unread: true },
                    { id: 2, title: 'System Alert', body: 'Server load reached 85% in Manila node.', time: '1 hour ago', unread: true },
                    { id: 3, title: 'New Employer', body: 'Logistics Co registered in Malate.', time: '3 hours ago', unread: true },
                    { id: 4, title: 'Verification Approved', body: 'Intramuros Tour is now active.', time: 'Yesterday', unread: false }
                ]
            };

            let activeAction = null; 
            let activeUserId = null;

            // 2. Render Main Table (Fully Reflective)
            window.renderUserTable = function(filterTerm = '') {
                const tableBody = document.getElementById('user-table-body');
                tableBody.innerHTML = '';
                let count = 0;

                Object.values(usersData).forEach(user => {
                    if (user.name.toLowerCase().includes(filterTerm) || user.email.toLowerCase().includes(filterTerm)) {
                        count++;
                        let statusBadge = '';
                        let needsClass = 'small text-muted italic';
                        
                        if(user.status === 'Verified') {
                            statusBadge = '<span class="badge bg-success-subtle text-success border border-success px-3">Verified</span>';
                            needsClass = 'small text-success fw-bold';
                        } else if(user.status === 'Suspended') {
                            statusBadge = '<span class="badge bg-danger-subtle text-danger border border-danger px-3">Suspended</span>';
                        } else {
                            statusBadge = '<span class="badge bg-warning-subtle text-warning border border-warning px-3">Pending</span>';
                        }

                        const row = `
                            <tr>
                                <td class="fw-bold">${user.id}</td>
                                <td class="fw-bold user-name">${user.name}</td>
                                <td class="text-muted">${user.email}</td>
                                <td><span class="badge rounded-pill ${user.type === 'Seeker' ? 'text-info border-info' : 'text-primary border-primary'}" style="background-color: ${user.type === 'Seeker' ? '#f0faff' : '#eef2ff'}; border: 1px solid;">${user.type}</span></td>
                                <td class="status-cell">${statusBadge}</td>
                                <td class="${needsClass}">${user.needs}</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3" onclick="viewUserDetails(${user.id})">View Details</button>
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    }
                });
                document.getElementById('user-count-text').innerText = `Showing ${count} users in database`;
            };

            // 3. View User Details (Updated with Unsuspend Logic)
            window.viewUserDetails = function(id) {
                const user = usersData[id];
                activeUserId = id;
                
                document.getElementById('modal-user-name').innerText = user.name;
                document.getElementById('modal-user-email').innerText = user.email;
                document.getElementById('modal-user-phone').innerText = user.phone;
                document.getElementById('modal-user-joined').innerText = user.joined;
                document.getElementById('modal-user-type').innerText = user.type;
                document.getElementById('modal-user-skills').innerText = user.skills;
                document.getElementById('modal-user-history').innerText = user.history;
                document.getElementById('modal-user-img').src = user.img;

                const statusEl = document.getElementById('modal-user-status');
                const reasonBox = document.getElementById('suspension-reason-box');
                const reasonText = document.getElementById('modal-suspension-text');
                const btnContainer = document.getElementById('modal-action-buttons');

                // Clear and render buttons
                btnContainer.innerHTML = '';
                
                if(user.status === 'Suspended') {
                    statusEl.innerHTML = '<span class="badge bg-danger-subtle text-danger border border-danger px-3">Suspended</span>';
                    reasonBox.classList.remove('d-none');
                    reasonText.innerText = user.suspensionReason || 'No reason specified.';
                    
                    // Add Unsuspend instead of Suspend
                    btnContainer.innerHTML = `
                        <button class="btn btn-success btn-sm px-4 rounded-pill fw-bold" onclick="openActionModal('unsuspend')">Unsuspend Account</button>
                        <button class="btn btn-danger btn-sm px-4 rounded-pill fw-bold" onclick="openActionModal('delete')">Delete User Data</button>
                    `;
                } else {
                    if(user.status === 'Verified') {
                        statusEl.innerHTML = '<span class="badge bg-success-subtle text-success border border-success px-3">Verified</span>';
                    } else {
                        statusEl.innerHTML = '<span class="badge bg-warning-subtle text-warning border border-warning px-3">Pending</span>';
                    }
                    reasonBox.classList.add('d-none');
                    
                    btnContainer.innerHTML = `
                        <button class="btn btn-warning btn-sm px-4 rounded-pill fw-bold text-white" onclick="openActionModal('suspend')">Suspend Account</button>
                        <button class="btn btn-danger btn-sm px-4 rounded-pill fw-bold" onclick="openActionModal('delete')">Delete User Data</button>
                    `;
                }

                const detailsModal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                detailsModal.show();
            };

            // 4. Admin Action Trigger
            window.openActionModal = function(type) {
                activeAction = type;
                const title = document.getElementById('actionModalTitle');
                const suspendFields = document.getElementById('suspend-fields');
                const warningBox = document.getElementById('action-warning-box');
                const confirmBtn = document.getElementById('confirm-action-btn');
                
                suspendFields.classList.add('d-none');
                warningBox.className = "alert alert-danger border-0 small mb-4";
                confirmBtn.className = "btn btn-danger rounded-pill px-4";

                if(type === 'suspend') {
                    title.innerText = "Suspend User Account";
                    title.className = "modal-title fw-bold text-warning";
                    suspendFields.classList.remove('d-none');
                    confirmBtn.className = "btn btn-warning rounded-pill px-4 text-white";
                } else if (type === 'unsuspend') {
                    title.innerText = "Lift Account Suspension";
                    title.className = "modal-title fw-bold text-success";
                    warningBox.className = "alert alert-success border-0 small mb-4";
                    warningBox.innerHTML = "<strong>Notice:</strong> This will restore full access to the user immediately.";
                    confirmBtn.className = "btn btn-success rounded-pill px-4";
                } else {
                    title.innerText = "Delete User Permanently";
                    title.className = "modal-title fw-bold text-danger";
                    warningBox.innerHTML = "<strong>Warning:</strong> This action is permanent. All user data will be purged.";
                }

                const actionModal = new bootstrap.Modal(document.getElementById('adminActionModal'));
                actionModal.show();
            };

            // 5. Execute Action
            window.executeAdminAction = function() {
                const password = document.getElementById('admin-pass-verify').value;
                if(password === "") {
                    alert("Please enter admin password to verify identity.");
                    return;
                }

                const alertBox = document.getElementById('js-success-alert');
                const message = document.getElementById('alert-message');
                const user = usersData[activeUserId];
                
                if(activeAction === 'delete') {
                    message.innerText = `User ID ${activeUserId} (${user.name}) has been purged from the database.`;
                    delete usersData[activeUserId];
                } else if(activeAction === 'unsuspend') {
                    user.status = 'Verified'; // Or restore to previous; usually verified in this flow
                    user.suspensionReason = '';
                    user.needs = 'Complete';
                    message.innerText = `Suspension lifted for ${user.name}. Account is now Verified.`;
                } else {
                    const days = document.getElementById('suspend-days').value;
                    const reasonInput = document.getElementById('suspend-reason').value;
                    user.status = 'Suspended';
                    user.suspensionReason = reasonInput;
                    user.needs = `Reason: Policy Violation (${days === 'permanent' ? 'Indefinite' : days + ' days'})`;
                    message.innerText = `Account suspended for ${days} days. Reason logged.`;
                }

                renderUserTable(); // Update the main UI
                alertBox.classList.remove('d-none');
                setTimeout(() => alertBox.classList.add('d-none'), 3000);

                bootstrap.Modal.getInstance(document.getElementById('adminActionModal')).hide();
                bootstrap.Modal.getInstance(document.getElementById('userDetailsModal')).hide();
                document.getElementById('admin-pass-verify').value = "";
            };

            // 6. Notification Logic
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

            // 7. Search Listener
            searchInput.addEventListener('input', function(e) {
                renderUserTable(e.target.value.toLowerCase());
            });

            // Initial Render
            renderUserTable();
            renderNotifications();
        });
    </script>
</body>
</html>