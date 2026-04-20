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
                <div class="notification-wrapper me-4 position-relative">
                    <span class="material-symbols-outlined fs-2 text-muted" style="cursor: pointer;">notifications</span>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-light" style="padding: 5px; font-size: 10px;">7</span>
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
                    <div class="chart-container" style="height: 400px;">
                        <canvas id="userTrendsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Recent Activity</h5>
                        <button class="btn btn-sm btn-light rounded-pill px-3">More</button>
                    </div>
                    <div id="activity-feed">
                        </div>
                </div>

                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Verification Queue</h5>
                        <button class="btn btn-sm btn-light rounded-pill px-3">View All</button>
                    </div>
                    <table class="table table-hover align-middle custom-table mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>Status</th>
                                <th>Name</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody id="queue-body">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dummyData = {
                trends: [2200, 2800, 3100, 2900, 3400, 4200, 3800, 4500, 4100, 5000, 4800, 5432],
                feed: [
                    { tag: '#ManilaJobs', text: 'Seeker posted in Binondo', user: 'Juan D.' },
                    { tag: '#HiringNow', text: 'Employer added 5 slots in Malate', user: 'Logistics Co' },
                    { tag: '#SampalocHiring', text: 'New opening near UST area', user: 'Cafe Manila' }
                ],
                queue: [
                    { status: 'Approved', name: 'Intramuros Tour', role: 'Employer' },
                    { status: 'Pending', name: 'Quiapo Vendor Assoc', role: 'Employer' },
                    { status: 'Pending', name: 'Maria Santos', role: 'Seeker (Tondo)' }
                ]
            };

            // Activity Trends Line Chart
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

            // Populate Feed
            const feedContainer = document.getElementById('activity-feed');
            dummyData.feed.forEach(item => {
                feedContainer.innerHTML += `
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">
                        <div class="bg-primary-subtle rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined text-primary fs-5">location_on</span>
                        </div>
                        <div>
                            <span class="fw-bold small" style="color: #1B3E9C;">${item.tag}</span>
                            <p class="mb-0 small text-muted">${item.text}</p>
                        </div>
                    </div>`;
            });

            // Populate Table
            const queueBody = document.getElementById('queue-body');
            dummyData.queue.forEach(item => {
                queueBody.innerHTML += `
                    <tr>
                        <td><span class="badge ${item.status === 'Approved' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'} rounded-pill">${item.status}</span></td>
                        <td class="small fw-bold">${item.name}</td>
                        <td class="small text-muted">${item.role}</td>
                    </tr>`;
            });
        });
    </script>
</body>
</html>