/**
 * Charts — Chart.js configurations for Dashboard
 * Expects global variables: borrowActivityData, topEquipmentData, statusDistData
 */
function initDashboardCharts() {

    // Common defaults
    Chart.defaults.font.family = "'Inter', 'Noto Sans Thai', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6b7280';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 16;

    // 1) Borrow Activity Line Chart
    var activityCtx = document.getElementById('borrowActivityChart');
    if (activityCtx && typeof borrowActivityData !== 'undefined') {
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: borrowActivityData.labels,
                datasets: [{
                    label: 'Borrows',
                    data: borrowActivityData.values,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        cornerRadius: 8,
                        padding: 12,
                        displayColors: false,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            stepSize: 1,
                            font: { size: 11 },
                            callback: function(val) { return Number.isInteger(val) ? val : null; }
                        }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    }

    // 2) Top Borrowed Equipment Bar Chart
    var topCtx = document.getElementById('topEquipmentChart');
    if (topCtx && typeof topEquipmentData !== 'undefined') {
        new Chart(topCtx, {
            type: 'bar',
            data: {
                labels: topEquipmentData.labels,
                datasets: [{
                    label: 'Times Borrowed',
                    data: topEquipmentData.values,
                    backgroundColor: [
                        'rgba(37,99,235,0.8)',
                        'rgba(99,102,241,0.8)',
                        'rgba(139,92,246,0.8)',
                        'rgba(168,85,247,0.8)',
                        'rgba(192,132,252,0.8)',
                    ],
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        cornerRadius: 8,
                        padding: 12,
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            stepSize: 1,
                            font: { size: 11 },
                            callback: function(val) { return Number.isInteger(val) ? val : null; }
                        }
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 12, weight: '500' } }
                    }
                }
            }
        });
    }

    // 3) Status Distribution Pie Chart
    var statusCtx = document.getElementById('statusPieChart');
    if (statusCtx && typeof statusDistData !== 'undefined') {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Borrowed', 'Returned', 'Overdue'],
                datasets: [{
                    data: [statusDistData.borrowed, statusDistData.returned, statusDistData.overdue],
                    backgroundColor: ['#f59e0b', '#22c55e', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 12 } }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        cornerRadius: 8,
                        padding: 12,
                    }
                }
            }
        });
    }
}

// Init on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        initDashboardCharts();
    }
});
