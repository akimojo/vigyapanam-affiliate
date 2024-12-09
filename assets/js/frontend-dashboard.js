document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    
    // Handle withdrawal form submission
    const withdrawalForm = document.querySelector('#withdrawal-form');
    if (withdrawalForm) {
        withdrawalForm.addEventListener('submit', handleWithdrawalSubmit);
    }
});

function initializeCharts() {
    // Revenue chart
    const revenueCtx = document.getElementById('revenue-chart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: getLastThirtyDays(),
                datasets: [{
                    label: 'Revenue',
                    data: [], // Data will be populated via AJAX
                    borderColor: '#0073aa',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Traffic chart
    const trafficCtx = document.getElementById('traffic-chart');
    if (trafficCtx) {
        new Chart(trafficCtx, {
            type: 'bar',
            data: {
                labels: getLastThirtyDays(),
                datasets: [{
                    label: 'Traffic',
                    data: [], // Data will be populated via AJAX
                    backgroundColor: '#0073aa'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // RPM chart
    const rpmCtx = document.getElementById('rpm-chart');
    if (rpmCtx) {
        new Chart(rpmCtx, {
            type: 'line',
            data: {
                labels: getLastThirtyDays(),
                datasets: [{
                    label: 'RPM',
                    data: [], // Data will be populated via AJAX
                    borderColor: '#28a745',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

function getLastThirtyDays() {
    const dates = [];
    for (let i = 29; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        dates.push(date.toLocaleDateString());
    }
    return dates;
}

async function handleWithdrawalSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    try {
        const response = await fetch(ajaxurl, {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        if (data.success) {
            alert('Withdrawal request submitted successfully!');
            location.reload();
        } else {
            alert(data.data.message || 'Error submitting withdrawal request');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error submitting withdrawal request');
    }
}

// Load chart data
async function loadChartData() {
    try {
        const response = await fetch(`${ajaxurl}?action=get_chart_data`);
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        if (data.success) {
            updateCharts(data.data);
        }
    } catch (error) {
        console.error('Error loading chart data:', error);
    }
}

function updateCharts(data) {
    const charts = {
        revenue: Chart.getChart('revenue-chart'),
        traffic: Chart.getChart('traffic-chart'),
        rpm: Chart.getChart('rpm-chart')
    };
    
    if (charts.revenue) {
        charts.revenue.data.datasets[0].data = data.revenue;
        charts.revenue.update();
    }
    
    if (charts.traffic) {
        charts.traffic.data.datasets[0].data = data.traffic;
        charts.traffic.update();
    }
    
    if (charts.rpm) {
        charts.rpm.data.datasets[0].data = data.rpm;
        charts.rpm.update();
    }
}

// Load initial chart data
loadChartData();