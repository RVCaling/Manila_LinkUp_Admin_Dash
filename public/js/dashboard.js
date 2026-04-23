document.addEventListener('DOMContentLoaded', function() {
    // 1. Data Store (Combined with notifications if necessary)
    if (!window.dummyData) window.dummyData = {};
    
    window.dummyData.trends = [2200, 2800, 3100, 2900, 3400, 4200, 3800, 4500, 4100, 5000, 4800, 5432];
    window.dummyData.feed = [
        { tag: '#ManilaJobs', text: 'Seeker posted in Binondo', user: 'Juan D.', time: '2 mins ago' },
        { tag: '#HiringNow', text: 'Employer added 5 slots in Malate', user: 'Logistics Co', time: '1 hour ago' },
        { tag: '#SampalocHiring', text: 'New opening near UST area', user: 'Cafe Manila', time: '3 hours ago' },
        { tag: '#TondoWorks', text: 'Verification pending for new user', user: 'Maria S.', time: '5 hours ago' }
    ];
    window.dummyData.queue = [
        { id: 101, status: 'Pending', name: 'Maria Santos', role: 'Seeker', location: 'Tondo', date: 'Apr 21, 2026' },
        { id: 202, status: 'Pending', name: 'Quiapo Vendor Assoc', role: 'Employer', location: 'Quiapo', date: 'Apr 21, 2026' },
        { id: 103, status: 'Pending', name: 'Ricardo Dalisay', role: 'Seeker', location: 'Binondo', date: 'Apr 21, 2026' },
        { id: 204, status: 'Approved', name: 'Intramuros Tour', role: 'Employer', location: 'Intramuros', date: 'Apr 20, 2026' },
        { id: 205, status: 'Rejected', name: 'Fake Company Inc', role: 'Employer', location: 'Unknown', date: 'Apr 19, 2026' }
    ];

    // 2. Trends Chart
    const ctx = document.getElementById('userTrendsChart');
    if (ctx) {
        new Chart(ctx, {
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
    }

    // 3. Feed & Queue Rendering Functions
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
        
        // Use the routes passed from Blade
        const targetUrl = item.role === 'Employer' ? window.adminRoutes.employers : window.adminRoutes.seekers;

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

    // 4. Init Rendering
    const feedContainer = document.getElementById('activity-feed');
    const modalFeedContainer = document.getElementById('modal-activity-feed');
    const queueBody = document.getElementById('queue-body');
    const modalQueueBody = document.getElementById('modal-queue-body');

    if (feedContainer) dummyData.feed.slice(0, 3).forEach(item => feedContainer.innerHTML += createActivityHTML(item));
    if (modalFeedContainer) dummyData.feed.forEach(item => modalFeedContainer.innerHTML += createActivityHTML(item));
    if (queueBody) dummyData.queue.slice(0, 3).forEach(item => queueBody.innerHTML += createQueueRow(item, false));
    if (modalQueueBody) dummyData.queue.forEach(item => modalQueueBody.innerHTML += createQueueRow(item, true));
    
    // Call the function from notifications.js
    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});