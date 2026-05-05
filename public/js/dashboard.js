document.addEventListener('DOMContentLoaded', function () {
    var data   = window.dashboardData || {};
    var growth = data.growth || {};

    // ── Trends Chart ────────────────────────────────────────────────────────
    var ctx = document.getElementById('userTrendsChart');
    if (ctx) {
        var monthLabels  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var currentYear  = new Date().getFullYear().toString();
        var currentMonth = new Date().getMonth(); // 0-indexed

        // Use current year if available, otherwise the most recent year in data
        var yearKeys = Object.keys(growth).sort().reverse();
        var yearData = growth[currentYear] || (yearKeys.length ? growth[yearKeys[0]] : {});

        var seekers   = yearData.seekers   || new Array(12).fill(0);
        var employers = yearData.employers || new Array(12).fill(0);

        // Only plot up to the current month
        var labels = monthLabels.slice(0, currentMonth + 1);
        var totals = seekers.slice(0, currentMonth + 1).map(function (s, i) {
            return s + (employers[i] || 0);
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: totals,
                    borderColor: '#1B3E9C',
                    backgroundColor: 'rgba(27, 62, 156, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f5f5f5' }, beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ── Fetch pending verifications for feed + queue ─────────────────────────
    fetch('/admin/notifications', {
        method: 'GET',
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(function (pending) {
        renderFeed(Array.isArray(pending) ? pending : []);
        renderQueue(Array.isArray(pending) ? pending : []);
    })
    .catch(function () {
        renderFeed([]);
        renderQueue([]);
    });

    // ── Helpers ──────────────────────────────────────────────────────────────
    function relativeTime(iso) {
        if (!iso) return '';
        var diff  = new Date() - new Date(iso);
        if (isNaN(diff) || diff < 0) return 'Just now';
        var mins  = Math.floor(diff / 60000);
        var hours = Math.floor(mins  / 60);
        var days  = Math.floor(hours / 24);
        if (mins  < 1)  return 'Just now';
        if (mins  < 60) return mins  + (mins  === 1 ? ' min'  : ' mins')  + ' ago';
        if (hours < 24) return hours + (hours === 1 ? ' hour' : ' hours') + ' ago';
        if (days  < 7)  return days  + (days  === 1 ? ' day'  : ' days')  + ' ago';
        return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' });
    }

    // ── Activity Feed ────────────────────────────────────────────────────────
    function feedItemHtml(item) {
        var isEmployer = item.userType === 'employer';
        var tag        = isEmployer ? '#NewEmployer' : '#NewSeeker';
        var label      = isEmployer ? 'Employer' : 'Seeker';
        return (
            '<div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">' +
                '<div class="bg-primary-subtle rounded-circle p-2 me-3" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">' +
                    '<span class="material-symbols-outlined text-primary fs-5">person_check</span>' +
                '</div>' +
                '<div class="flex-grow-1">' +
                    '<div class="d-flex justify-content-between">' +
                        '<span class="fw-bold small" style="color:#1B3E9C;">' + tag + '</span>' +
                        '<span class="extra-small text-muted">' + relativeTime(item.updatedAt) + '</span>' +
                    '</div>' +
                    '<p class="mb-0 small text-muted">' + item.name + ' submitted ID &amp; clearance</p>' +
                    '<small class="extra-small fw-bold text-secondary">Type: ' + label + '</small>' +
                '</div>' +
            '</div>'
        );
    }

    function renderFeed(items) {
        var feed      = document.getElementById('activity-feed');
        var modalFeed = document.getElementById('modal-activity-feed');
        if (!feed) return;

        if (items.length === 0) {
            var empty = '<p class="text-muted small text-center py-3">No pending verifications.</p>';
            feed.innerHTML = empty;
            if (modalFeed) modalFeed.innerHTML = empty;
            return;
        }

        feed.innerHTML      = items.slice(0, 3).map(feedItemHtml).join('');
        if (modalFeed) modalFeed.innerHTML = items.map(feedItemHtml).join('');
    }

    // ── Verification Queue ───────────────────────────────────────────────────
    function queueRowHtml(item, isModal) {
        var isEmployer = item.userType === 'employer';
        var targetUrl  = isEmployer
            ? (window.adminRoutes ? window.adminRoutes.employers : '#')
            : (window.adminRoutes ? window.adminRoutes.seekers   : '#');

        if (!isModal) {
            return (
                '<tr>' +
                    '<td><span class="badge bg-warning-subtle text-warning rounded-pill extra-small">Pending</span></td>' +
                    '<td class="small fw-bold">' + item.name + '</td>' +
                    '<td><a href="' + targetUrl + '" class="btn btn-sm btn-primary rounded-pill extra-small">Review</a></td>' +
                '</tr>'
            );
        }

        var label = isEmployer ? 'Employer' : 'Seeker';
        var date  = item.updatedAt
            ? new Date(item.updatedAt).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' })
            : '—';
        return (
            '<tr>' +
                '<td><span class="badge bg-warning-subtle text-warning rounded-pill">Pending</span></td>' +
                '<td class="fw-bold small">' + item.name + '</td>' +
                '<td class="small text-muted">' + label + '</td>' +
                '<td class="small text-muted">' + date + '</td>' +
                '<td><a href="' + targetUrl + '" class="btn btn-sm btn-outline-primary rounded-pill extra-small px-3">Full Review</a></td>' +
            '</tr>'
        );
    }

    function renderQueue(items) {
        var queueBody  = document.getElementById('queue-body');
        var modalQueue = document.getElementById('modal-queue-body');
        if (!queueBody) return;

        if (items.length === 0) {
            queueBody.innerHTML  = '<tr><td colspan="3" class="text-center text-muted small py-3">No pending verifications.</td></tr>';
            if (modalQueue) modalQueue.innerHTML = '<tr><td colspan="5" class="text-center text-muted small py-3">No pending verifications.</td></tr>';
            return;
        }

        queueBody.innerHTML = items.slice(0, 3).map(function (i) { return queueRowHtml(i, false); }).join('');
        if (modalQueue) modalQueue.innerHTML = items.map(function (i) { return queueRowHtml(i, true); }).join('');
    }

    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});
