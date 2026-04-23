document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // DUMMY DATA (Delete/Replace with Firebase)
    // ==========================================
    const analyticsPalette = {
        indigo: '#4F46E5',
        teal: '#0D9488',
        coral: '#FB7185',
        amber: '#F59E0B',
        slate: '#64748B'
    };

    const districtStats = {
        labels: ['Tondo', 'Binondo', 'Malate', 'Sampaloc', 'Ermita'],
        counts: [1200, 450, 890, 1100, 600],
        colors: [analyticsPalette.indigo, analyticsPalette.teal, analyticsPalette.coral, analyticsPalette.amber, analyticsPalette.slate]
    };

    const growthStats = {
        months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        seekers: [400, 600, 800, 1100, 1300, 1500],
        employers: [100, 150, 300, 450, 500, 650]
    };
    // ==========================================

    // 1. District Doughnut Chart
    const districtCtx = document.getElementById('districtChart');
    if (districtCtx) {
        new Chart(districtCtx, {
            type: 'doughnut',
            data: {
                labels: districtStats.labels,
                datasets: [{
                    data: districtStats.counts,
                    backgroundColor: districtStats.colors,
                    hoverOffset: 10,
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Generate Custom Legend
        const legendBox = document.getElementById('district-legend');
        if (legendBox) {
            legendBox.innerHTML = ''; // Clear previous
            districtStats.labels.forEach((label, i) => {
                legendBox.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:10px; height:10px; border-radius:50%; background:${districtStats.colors[i]}"></div>
                            <span class="small text-muted">${label}</span>
                        </div>
                        <span class="small fw-bold">${districtStats.counts[i]}</span>
                    </div>`;
            });
        }
    }

    // 2. Monthly Growth Chart
    const growthCtx = document.getElementById('growthChart');
    if (growthCtx) {
        new Chart(growthCtx, {
            type: 'bar',
            data: {
                labels: growthStats.months,
                datasets: [
                    {
                        label: 'Seekers',
                        data: growthStats.seekers,
                        backgroundColor: analyticsPalette.indigo,
                        borderRadius: 8,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Employers',
                        data: growthStats.employers,
                        backgroundColor: analyticsPalette.teal,
                        borderRadius: 8,
                        barPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'top',
                        align: 'end',
                        labels: { usePointStyle: true, padding: 20 }
                    } 
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5], color: '#e2e8f0' } 
                    }
                }
            }
        });
    }

    // Call notification renderer if it exists in notifications.js
    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});