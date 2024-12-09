document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    
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
    // Revenue chart
    const revenueCtx = document.getElementById('revenue-chart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: getLastSevenDays(),
                datasets: [{
                    label: 'Revenue',
                    data: [], // Data will be populated via AJAX
                    borderColor: '#0073aa',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // Traffic chart
    const trafficCtx = document.getElementById('traffic-chart');
    if (trafficCtx) {
        new Chart(trafficCtx, {
            type: 'bar',
            data: {
                labels: getLastSevenDays(),
                datasets: [{
                    label: 'Traffic',
                    data: [], // Data will be populated via AJAX
                    backgroundColor: '#0073aa'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}

function getLastSevenDays() {
    const dates = [];
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        dates.push(date.toLocaleDateString());
    }
    return dates;
}

async function handleClientFormSubmit(event) {
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
            alert('Client added successfully!');
            location.reload();
        } else {
            alert(data.data.message || 'Error adding client');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error adding client');
    }
}

async function handleTrackingFormSubmit(event) {
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
            alert('Tracking added successfully!');
            location.reload();
        } else {
            alert(data.data.message || 'Error adding tracking');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error adding tracking');
    }
}

async function handleBanFormSubmit(event) {
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
            alert('Freelancer banned successfully!');
            location.reload();
        } else {
            alert(data.data.message || 'Error banning freelancer');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error banning freelancer');
    }
}

// Handle edit and delete client buttons
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('edit-client')) {
        const clientId = event.target.dataset.id;
        handleEditClient(clientId);
    } else if (event.target.classList.contains('delete-client')) {
        const clientId = event.target.dataset.id;
        handleDeleteClient(clientId);
    }
});

async function handleEditClient(clientId) {
    try {
        const response = await fetch(`${ajaxurl}?action=get_client&id=${clientId}`);
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        if (data.success) {
            // Populate form with client data
            const form = document.querySelector('.client-form');
            for (const [key, value] of Object.entries(data.data)) {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) input.value = value;
            }
            form.dataset.editId = clientId;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error loading client data');
    }
}

async function handleDeleteClient(clientId) {
    if (!confirm('Are you sure you want to delete this client?')) return;
    
    try {
        const response = await fetch(ajaxurl, {
            method: 'POST',
            body: JSON.stringify({
                action: 'delete_client',
                id: clientId
            })
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        if (data.success) {
            alert('Client deleted successfully!');
            location.reload();
        } else {
            alert(data.data.message || 'Error deleting client');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error deleting client');
    }
}