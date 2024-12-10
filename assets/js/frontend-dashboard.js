document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts if they exist
    const revenueChart = document.getElementById('revenue-chart');
    const trafficChart = document.getElementById('traffic-chart');
    const locationChart = document.getElementById('location-chart');

    if (revenueChart) initializeRevenueChart(revenueChart);
    if (trafficChart) initializeTrafficChart(trafficChart);
    if (locationChart) initializeLocationChart(locationChart);
    
    // Handle withdrawal form submission
    const withdrawalForm = document.getElementById('withdrawal-form');
    if (withdrawalForm) {
        withdrawalForm.addEventListener('submit', handleWithdrawalSubmit);
    }
});

function initializeRevenueChart(canvas) {
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Revenue',
                data: [],
                borderColor: '#0073aa',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
}

function initializeTrafficChart(canvas) {
    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Traffic',
                data: [],
                backgroundColor: '#0073aa'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function initializeLocationChart(canvas) {
    new Chart(canvas, {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
}

async function loadChartData() {
    try {
        const response = await fetch(vigyapanamAjax.ajaxurl + '?action=get_chart_data&nonce=' + vigyapanamAjax.nonce);
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
    // Update Revenue Chart
    const revenueChart = Chart.getChart('revenue-chart');
    if (revenueChart && data.revenue) {
        revenueChart.data.labels = data.revenue.labels;
        revenueChart.data.datasets[0].data = data.revenue.data;
        revenueChart.update();
    }

    // Update Traffic Chart
    const trafficChart = Chart.getChart('traffic-chart');
    if (trafficChart && data.traffic) {
        trafficChart.data.labels = data.traffic.labels;
        trafficChart.data.datasets[0].data = data.traffic.data;
        trafficChart.update();
    }

    // Update Location Chart
    const locationChart = Chart.getChart('location-chart');
    if (locationChart && data.locations) {
        locationChart.data.labels = data.locations.labels;
        locationChart.data.datasets[0].data = data.locations.data;
        locationChart.update();
    }
}

async function handleWithdrawalSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;

    try {
        const formData = new FormData(form);
        formData.append('action', 'request_withdrawal');
        formData.append('nonce', vigyapanamAjax.nonce);

        const response = await fetch(vigyapanamAjax.ajaxurl, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) throw new Error('Network response was not ok');

        const data = await response.json();
        if (data.success) {
            alert('Withdrawal request submitted successfully!');
            form.reset();
        } else {
            alert(data.data.message || 'Error submitting withdrawal request');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error submitting withdrawal request');
    } finally {
        submitButton.disabled = false;
    }
}

// Load initial chart data
loadChartData();

// Refresh chart data every 5 minutes
setInterval(loadChartData, 300000);