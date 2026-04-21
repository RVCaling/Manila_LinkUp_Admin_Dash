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
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Data Store
            const dummyData = {
                trends: [2200, 2800, 3100, 2900, 3400, 4200, 3800, 4500, 4100, 5000, 4800, 5432],
                feed: [
                    { tag: '#ManilaJobs', text: 'Seeker posted in Binondo', user: 'Juan D.', time: '2 mins ago' },
                    { tag: '#HiringNow', text: 'Employer added 5 slots in Malate', user: 'Logistics Co', time: '1 hour ago' },
                    { tag: '#SampalocHiring', text: 'New opening near UST area', user: 'Cafe Manila', time: '3 hours ago' },
                    { tag: '#TondoWorks', text: 'Verification pending for new user', user: 'Maria S.', time: '5 hours ago' }
                ],
                queue: [
                    { id: 101, status: 'Pending', name: 'Maria Santos', role: 'Seeker', location: 'Tondo', date: 'Apr 21, 2026' },
                    { id: 202, status: 'Pending', name: 'Quiapo Vendor Assoc', role: 'Employer', location: 'Quiapo', date: 'Apr 21, 2026' },
                    { id: 103, status: 'Pending', name: 'Ricardo Dalisay', role: 'Seeker', location: 'Binondo', date: 'Apr 21, 2026' },
                    { id: 204, status: 'Approved', name: 'Intramuros Tour', role: 'Employer', location: 'Intramuros', date: 'Apr 20, 2026' },
                    { id: 205, status: 'Rejected', name: 'Fake Company Inc', role: 'Employer', location: 'Unknown', date: 'Apr 19, 2026' }
                ],
                notifications: [
                    { id: 1, title: 'New Seeker Registration', body: 'Maria Santos uploaded ID for verification.', time: '2 mins ago', unread: true },
                    { id: 2, title: 'System Alert', body: 'Server load reached 85% in Manila node.', time: '1 hour ago', unread: true },
                    { id: 3, title: 'New Employer', body: 'Logistics Co registered in Malate.', time: '3 hours ago', unread: true },
                    { id: 4, title: 'Verification Approved', body: 'Intramuros Tour is now active.', time: 'Yesterday', unread: false }
                ]
            };

            // 2. Notification System Logic
            window.renderNotifications = function() {
                const list = document.getElementById('notification-list');
                const modalList = document.getElementById('modal-alerts-list');
                const countBadge = document.getElementById('notif-count');
                const unreadCount = dummyData.notifications.filter(n => n.unread).length;
                
                countBadge.innerText = unreadCount;
                countBadge.style.display = unreadCount > 0 ? 'block' : 'none';

                const notificationHTML = dummyData.notifications.map(n => `
                    <li class="notification-item p-3 ${n.unread ? 'unread' : ''}" onclick="markAsRead(${n.id})">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <p class="small fw-bold mb-1" style="color: #1B3E9C;">${n.title}</p>
                                <p class="extra-small text-muted mb-1">${n.body}</p>
                                <span class="extra-small text-secondary">${n.time}</span>
                            </div>
                            ${n.unread ? '<div class="bg-primary rounded-circle" style="width: 8px; height: 8px; margin-top: 5px;"></div>' : ''}
                        </div>
                    </li>
                `).join('');

                list.innerHTML = notificationHTML;
                modalList.innerHTML = notificationHTML; // Populate the Modal list as well
            };

            window.markAsRead = function(id) {
                const notif = dummyData.notifications.find(n => n.id === id);
                if (notif) notif.unread = false;
                renderNotifications();
            };

            window.clearNotifications = function() {
                dummyData.notifications.forEach(n => n.unread = false);
                renderNotifications();
            };

            // 3. Trends Chart
            new Chart(document.getElementById('userTrendsChart'), {
                type: 'line',
                data: {
                    labels: ['0', '2', '4', '6', '8', '10', '12', '14', '16', '18', '20', '30'],
                    datasets: [{
                        data: dummyData.trends,
                        borderColor: '#1B3E9C',
                        backgroundColor: 'rgba(27, 62, 156, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { grid: { color: '#f5f5f5' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 4. Feed & Queue Rendering
            function createActivityHTML(item) {
                return `
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">
                        <div class="bg-primary-subtle rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined text-primary fs-5">location_on</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold small" style="color: #1B3E9C;">${item.tag}</span>
                                <span class="extra-small text-muted">${item.time}</span>
                            </div>
                            <p class="mb-0 small text-muted">${item.text}</p>
                            <small class="extra-small fw-bold text-secondary">By ${item.user}</small>
                        </div>
                    </div>`;
            }

            function createQueueRow(item, isFullModal = false) {
                const badgeClass = item.status === 'Approved' ? 'bg-success-subtle text-success' : 
                                   (item.status === 'Pending' ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger');
                const seekerBase = "{{ route('admin.seekers') }}";
                const employerBase = "{{ route('admin.employers') }}";
                const targetUrl = item.role === 'Employer' ? employerBase : seekerBase;

                if (!isFullModal) {
                    return `<tr>
                        <td><span class="badge ${badgeClass} rounded-pill extra-small">${item.status}</span></td>
                        <td class="small fw-bold">${item.name}</td>
                        <td><a href="${targetUrl}" class="btn btn-sm btn-primary rounded-pill extra-small">Review</a></td>
                    </tr>`;
                }
                return `<tr>
                    <td><span class="badge ${badgeClass} rounded-pill">${item.status}</span></td>
                    <td><div class="fw-bold small">${item.name}</div><div class="extra-small text-muted">${item.location}</div></td>
                    <td class="small text-muted">${item.role}</td>
                    <td class="small text-muted">${item.date}</td>
                    <td><a href="${targetUrl}" class="btn btn-sm btn-outline-primary rounded-pill extra-small px-3">Full Review</a></td>
                </tr>`;
            }

            // Init Rendering
            const feedContainer = document.getElementById('activity-feed');
            const modalFeedContainer = document.getElementById('modal-activity-feed');
            const queueBody = document.getElementById('queue-body');
            const modalQueueBody = document.getElementById('modal-queue-body');

            dummyData.feed.slice(0, 3).forEach(item => feedContainer.innerHTML += createActivityHTML(item));
            dummyData.feed.forEach(item => modalFeedContainer.innerHTML += createActivityHTML(item));
            dummyData.queue.slice(0, 3).forEach(item => queueBody.innerHTML += createQueueRow(item, false));
            dummyData.queue.forEach(item => modalQueueBody.innerHTML += createQueueRow(item, true));
            
            renderNotifications();
        });
    </script>
</body>
</html>