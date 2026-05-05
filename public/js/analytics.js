document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. DATA REPOSITORY
    // ==========================================
    const analyticsPalette = {
        indigo: '#4F46E5',
        teal: '#0D9488',
        coral: '#FB7185',
        amber: '#F59E0B',
        slate: '#64748B',
        sky: '#0EA5E9'
    };

    const filterDays = {
        'Last 30 Days': 30,
        'Last 7 Days': 7,
        'Last 24 Hours': 1,
    };

    const tagData   = window.trendingTags || [];
    const jobLabels = tagData.map(t => t.label);
    const jobCounts = tagData.map(t => t.jobCount);

    const yearlyGrowth = window.monthlyGrowth || {};

    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    const currentYear = String(new Date().getFullYear());
    const defaultYear = yearlyGrowth[currentYear] ? currentYear : Object.keys(yearlyGrowth)[0] || currentYear;

    let jobChart, growthChart;

    // ==========================================
    // 2. INITIALIZE CHARTS
    // ==========================================
    function initCharts() {
        const jobCtx = document.getElementById('districtChart');
        if (jobCtx) {
            jobChart = new Chart(jobCtx, {
                type: 'bar',
                data: {
                    labels: jobLabels,
                    datasets: [{
                        label: 'Postings',
                        data: jobCounts,
                        backgroundColor: Object.values(analyticsPalette),
                        borderRadius: 6,
                        barThickness: 15
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true }, y: { grid: { display: false } } }
                }
            });
        }

        const growthCtx = document.getElementById('growthChart');
        if (growthCtx) {
            const initData = yearlyGrowth[defaultYear] || { seekers: Array(12).fill(0), employers: Array(12).fill(0) };
            growthChart = new Chart(growthCtx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        { label: 'Seekers', data: initData.seekers, backgroundColor: analyticsPalette.indigo, borderRadius: 8 },
                        { label: 'Employers', data: initData.employers, backgroundColor: analyticsPalette.teal, borderRadius: 8 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
        updateLegend();
    }

    // ==========================================
    // 3. CORE UPDATE LOGIC
    // ==========================================
    window.updateDashboard = async function(filter) {
        document.getElementById('filterText').innerText = filter;

        const days = filterDays[filter] || 30;

        let overview = {}, users = {};
        try {
            const res  = await fetch(`/admin/analytics/filter?days=${days}`);
            const json = await res.json();
            overview = json.overview || {};
            users    = json.users    || {};
        } catch (e) {
            console.error('Failed to load filtered analytics:', e);
            return;
        }

        const newUsers = (users.newSeekers || 0) + (users.newEmployers || 0);

        document.getElementById('stat-indigo-label').innerText = `New Users (${filter})`;
        document.getElementById('stat-indigo-value').innerText = newUsers;
        document.getElementById('stat-indigo-sub').innerText =
            `${users.newSeekers || 0} seekers · ${users.newEmployers || 0} employers`;

        document.getElementById('stat-teal-label').innerText = `Jobs Posted (${filter})`;
        document.getElementById('stat-teal-value').innerText = overview.totalJobs || 0;
        document.getElementById('stat-teal-sub').innerText =
            `${overview.totalApplications || 0} applications`;

        document.getElementById('stat-coral-label').innerText = `Hires Made (${filter})`;
        document.getElementById('stat-coral-value').innerText = overview.totalHires || 0;
        document.getElementById('stat-coral-sub').innerText =
            `from ${overview.totalApplications || 0} applications`;
    };

    function updateLegend() {
        const legendBox = document.getElementById('district-legend');
        if (legendBox) {
            legendBox.innerHTML = '';
            jobLabels.forEach((label, i) => {
                legendBox.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:8px; height:8px; border-radius:2px; background:${Object.values(analyticsPalette)[i]}"></div>
                            <span class="small text-muted" style="font-size: 0.75rem;">${label}</span>
                        </div>
                        <span class="small fw-bold" style="font-size: 0.75rem;">${jobCounts[i]}</span>
                    </div>`;
            });
        }
    }

    // ==========================================
    // 4. EVENT LISTENERS (Date, Year, Export)
    // ==========================================

    // Date Filter Toggle Logic
    const filters = ['Last 30 Days', 'Last 7 Days', 'Last 24 Hours'];
    let currentFilterIndex = 0;

    window.toggleDateFilter = function() {
        currentFilterIndex = (currentFilterIndex + 1) % filters.length;
        const newFilter = filters[currentFilterIndex];
        updateDashboard(newFilter);
    };

    // Year Toggle Logic
    document.querySelectorAll('.btn-group .btn-outline-secondary').forEach(btn => {
        btn.addEventListener('click', function() {
            const year = this.innerText;
            
            // UI Toggle
            document.querySelectorAll('.btn-group .btn-outline-secondary').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Update Chart
            const yearData = yearlyGrowth[year] || { seekers: Array(12).fill(0), employers: Array(12).fill(0) };
            growthChart.data.datasets[0].data = yearData.seekers;
            growthChart.data.datasets[1].data = yearData.employers;
            growthChart.update();
        });
    });

    window.exportData = function() {
        const now      = new Date().toISOString().slice(0, 19).replace('T', ' ');
        const overview = window.overviewStats || {};
        const users    = window.usersStats    || {};
        const tags     = window.trendingTags  || [];
        const growth   = window.monthlyGrowth || {};

        const totalUsers = (users.totalSeekers || 0) + (users.totalEmployers || 0);
        const hireRate   = overview.totalApplications
            ? ((overview.totalHires / overview.totalApplications) * 100).toFixed(1) + '%'
            : 'N/A';

        const rows = [
            ['Manila LinkUp — Analytics Report'],
            ['Generated At', now],
            [],
            ['USER SUMMARY'],
            ['Total Users',      totalUsers],
            ['Total Seekers',    users.totalSeekers   ?? 0],
            ['Total Employers',  users.totalEmployers ?? 0],
            [],
            ['JOBS SUMMARY'],
            ['Active Jobs',         overview.activeJobs        ?? 0],
            ['Total Jobs',          overview.totalJobs         ?? 0],
            ['Total Applications',  overview.totalApplications ?? 0],
            ['Total Hires',         overview.totalHires        ?? 0],
            ['Hire Rate',           hireRate],
            [],
            ['TRENDING JOB TAGS (Top 6)'],
            ['Tag', 'Job Postings'],
            ...tags.map(t => [t.label, t.jobCount]),
            [],
            ['MONTHLY REGISTRATION GROWTH'],
            ['Year', 'Month', 'Seekers', 'Employers'],
        ];

        const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        Object.entries(growth).sort().forEach(([year, data]) => {
            monthNames.forEach((month, i) => {
                const s = (data.seekers   || [])[i] || 0;
                const e = (data.employers || [])[i] || 0;
                if (s || e) rows.push([year, month, s, e]);
            });
        });

        const csv  = rows.map(r => r.map(v => `"${String(v).replace(/"/g, '""')}"`).join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url  = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href  = url;
        link.download = `manila_linkup_report_${now.slice(0, 10)}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    };

    initCharts();

    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});
