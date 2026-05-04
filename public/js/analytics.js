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

    // Simulated data based on time filters
    const dashboardData = {
        'Last 30 Days': {
            retention: "84.2%",
            retentionTrend: "+2.4%",
            verifyTime: "4.5h",
            verifyTrend: "-1.2h",
            matches: "1,240",
        },
        'Last 7 Days': {
            retention: "81.5%",
            retentionTrend: "+0.8%",
            verifyTime: "3.2h",
            verifyTrend: "-0.5h",
            matches: "312",
        },
        'Last 24 Hours': {
            retention: "88.1%",
            retentionTrend: "+5.2%",
            verifyTime: "1.5h",
            verifyTrend: "-0.2h",
            matches: "45",
        }
    };

    const tagData   = window.trendingTags || [];
    const jobLabels = tagData.map(t => t.label);
    const jobCounts = tagData.map(t => t.jobCount);

    const yearlyGrowth = {
        '2026': {
            seekers: [400, 600, 800, 1100, 1300, 1500],
            employers: [100, 150, 300, 450, 500, 650]
        },
        '2025': {
            seekers: [200, 350, 410, 500, 580, 700],
            employers: [50, 80, 120, 200, 240, 310]
        }
    };

    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

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
            growthChart = new Chart(growthCtx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        { label: 'Seekers', data: yearlyGrowth['2026'].seekers, backgroundColor: analyticsPalette.indigo, borderRadius: 8 },
                        { label: 'Employers', data: yearlyGrowth['2026'].employers, backgroundColor: analyticsPalette.teal, borderRadius: 8 }
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
    window.updateDashboard = function(filter) {
        const data = dashboardData[filter];
        
        // Update Text Metrics
        document.querySelector('.border-indigo h2').innerText = data.retention;
        document.querySelector('.border-indigo small').innerHTML = `<span class="material-symbols-outlined fs-6 align-middle">trending_up</span> ${data.retentionTrend}`;
        
        document.querySelector('.border-teal h2').innerText = data.verifyTime;
        document.querySelector('.border-teal small').innerHTML = `<span class="material-symbols-outlined fs-6 align-middle">bolt</span> ${data.verifyTrend}`;
        
        document.querySelector('.border-coral h2').innerText = data.matches;

        // Update Button UI
        document.getElementById('filterText').innerText = filter;
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
            growthChart.data.datasets[0].data = yearlyGrowth[year].seekers;
            growthChart.data.datasets[1].data = yearlyGrowth[year].employers;
            growthChart.update();
        });
    });

    // Export Logic (Simple CSV download)
    window.exportData = function() {
        let csvContent = "data:text/csv;charset=utf-8,Category,Value\n";
        csvContent += `Retention Rate,${document.querySelector('.border-indigo h2').innerText}\n`;
        csvContent += `Avg Verification,${document.querySelector('.border-teal h2').innerText}\n`;
        csvContent += `Job Matches,${document.querySelector('.border-coral h2').innerText}\n`;
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "manila_linkup_report.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    initCharts();

    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});
