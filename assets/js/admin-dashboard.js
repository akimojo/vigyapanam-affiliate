document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    loadSignupTrends();
    
    // Handle client form submission
    const clientForm = document.querySelector('.client-form');
    if (clientForm) {
        clientForm.addEventListener('submit', handleClientFormSubmit);
    }
    
    // Handle tracking form submission
    const trackingForm = document.querySelector('.tracking-form');
    if (trackingForm) {
        trackingForm.addEventListener('submit', handleTrackingFormSubmit);
    }
    
    // Handle ban form submission
    const banForm = document.querySelector('.ban-form');
    if (banForm) {
        banForm.addEventListener('submit', handleBanFormSubmit);
    }
});

function initializeCharts() {
    // Signup trends chart
    const signupCtx = document.getElementById('signup-trend-chart');
    if (signupCtx) {
        new Chart(signupCtx, {
            type: 'line',
            data: {
                labels: getLastThirtyDays(),
                datasets: [{
                    label: 'New Signups',
                    data: [], // Data will be populated via AJAX
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            maxTicksLimit: 5 // Limit the number of ticks
                        }
                    },
                    x: {
                        ticks: {
                            maxTicksLimit: 10 // Limit the number of x-axis labels
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        right: 10,
                        bottom: 10,
                        left: 10
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

async function loadSignupTrends() {
    try {
        const response = await fetch(`${ajaxurl}?action=get_signup_trends&nonce=${vigyapanamAjax.nonce}`);
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        if (data.success) {
            updateSignupChart(data.data);
        }
    } catch (error) {
        console.error('Error loading signup trends:', error);
    }
}

function updateSignupChart(data) {
    const chart = Chart.getChart('signup-trend-chart');
    if (chart) {
        chart.data.datasets[0].data = data;
        chart.update();
    }
}