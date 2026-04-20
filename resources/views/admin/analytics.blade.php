<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manila LinkUp | Analytics & Reports</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-analytics.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light d-flex">

    @include('admin.sidebar')

    <div class="main-content w-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">System Analytics</h1>
                <p class="text-muted small">Visualizing platform growth and engagement metrics.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-white bg-white border shadow-sm rounded-pill px-3 py-2 small fw-bold">
                    <span class="material-symbols-outlined align-middle fs-5">calendar_month</span> Last 30 Days
                </button>
                <button class="btn btn-primary shadow-sm rounded-pill px-3 py-2 small fw-bold" style="background-color: #1B3E9C;">
                    <span class="material-symbols-outlined align-middle fs-5">file_download</span> Export
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm border-start border-indigo border-4">
                    <p class="text-muted small fw-bold mb-1">User Retention Rate</p>
                    <div class="d-flex align-items-end gap-2">
                        <h2 class="fw-bold mb-0">84.2%</h2>
                        <small class="text-success fw-bold mb-1"><span class="material-symbols-outlined fs-6 align-middle">trending_up</span> +2.4%</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm border-start border-teal border-4">
                    <p class="text-muted small fw-bold mb-1">Avg. Verification Time</p>
                    <div class="d-flex align-items-end gap-2">
                        <h2 class="fw-bold mb-0">4.5h</h2>
                        <small class="text-success fw-bold mb-1"><span class="material-symbols-outlined fs-6 align-middle">bolt</span> -1.2h</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm border-start border-coral border-4">
                    <p class="text-muted small fw-bold mb-1">Job Match Success</p>
                    <div class="d-flex align-items-end gap-2">
                        <h2 class="fw-bold mb-0">1,240</h2>
                        <small class="text-muted small mb-1">Total Hired</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <h5 class="fw-bold mb-4">Users by District</h5>
                    <div class="chart-container" style="position: relative; height:250px;">
                        <canvas id="districtChart"></canvas>
                    </div>
                    <div class="mt-4" id="district-legend"></div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Monthly Registration Growth</h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary active">2026</button>
                            <button class="btn btn-outline-secondary">2025</button>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 350px;">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Updated Palette: Indigo, Teal, Coral, Gold, Slate
            const palette = {
                indigo: '#4F46E5',
                teal: '#0D9488',
                coral: '#FB7185',
                amber: '#F59E0B',
                slate: '#64748B',
                lightTeal: 'rgba(13, 148, 136, 0.2)'
            };

            const districtData = {
                labels: ['Tondo', 'Binondo', 'Malate', 'Sampaloc', 'Ermita'],
                counts: [1200, 450, 890, 1100, 600],
                colors: [palette.indigo, palette.teal, palette.coral, palette.amber, palette.slate]
            };

            const growthData = {
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                seekers: [400, 600, 800, 1100, 1300, 1500],
                employers: [100, 150, 300, 450, 500, 650]
            };

            // 1. District Doughnut Chart
            new Chart(document.getElementById('districtChart'), {
                type: 'doughnut',
                data: {
                    labels: districtData.labels,
                    datasets: [{
                        data: districtData.counts,
                        backgroundColor: districtData.colors,
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

            // Legend Generation
            const legendBox = document.getElementById('district-legend');
            districtData.labels.forEach((label, i) => {
                legendBox.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:10px; height:10px; border-radius:50%; background:${districtData.colors[i]}"></div>
                            <span class="small text-muted">${label}</span>
                        </div>
                        <span class="small fw-bold">${districtData.counts[i]}</span>
                    </div>`;
            });

            // 2. Growth Bar Chart
            new Chart(document.getElementById('growthChart'), {
                type: 'bar',
                data: {
                    labels: growthData.months,
                    datasets: [
                        {
                            label: 'Seekers',
                            data: growthData.seekers,
                            backgroundColor: palette.indigo,
                            borderRadius: 8,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Employers',
                            data: growthData.employers,
                            backgroundColor: palette.teal,
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
        });
    </script>
</body>
</html>