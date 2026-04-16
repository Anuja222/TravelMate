// Transport Booking Details Handler
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PAGE LOADED ===');
    console.log('bookingId variable:', typeof bookingId !== 'undefined' ? bookingId : 'NOT DEFINED');
    
    if (typeof bookingId === 'undefined' || !bookingId) {
        showAlert('ERROR: bookingId not found in page', 'error');
        return;
    }
    
    loadBookingDetails();
});

async function loadBookingDetails() {
    try {
        console.log('=== LOADING DETAILS FOR: ' + bookingId + ' ===');
        
        // Build API URL
        const apiUrl = '/TravelMate/public/api/transport-booking/transporter-details/' + bookingId;
        console.log('API URL:', apiUrl);
        
        // Make request
        const response = await fetch(apiUrl);
        console.log('Response received. Status:', response.status);
        
        if (!response.ok) {
            const text = await response.text();
            console.error('ERROR:', response.status, text);
            showAlert('Server error ' + response.status, 'error');
            return;
        }
        
        // Parse JSON
        const result = await response.json();
        console.log('API Response:', result);
        
        if (!result.success) {
            showAlert('Error: ' + (result.errors?.general || 'Failed to load'), 'error');
            return;
        }
        
        if (!result.data) {
            showAlert('Error: No data in response', 'error');
            return;
        }
        
        console.log('=== POPULATING DETAILS ===');
        populateBookingDetails(result.data);
        console.log('=== DONE ===');
        
    } catch (error) {
        console.error('=== EXCEPTION ===', error);
        showAlert('Error: ' + error.message, 'error');
    }
}

function populateBookingDetails(data) {
    console.log('Populating details with:', data);
    
    try {
        // Booking ID
        setElement('bookingIdDisplay', data.booking_id || 'N/A');
        setElement('summaryBookingId', data.booking_id || 'N/A');
        
        // Customer
        const fullName = ((data.first_name || '') + ' ' + (data.last_name || '')).trim() || 'N/A';
        setElement('customerName', fullName);
        setElement('customerEmail', data.email || 'N/A');
        setElement('customerPhone', data.phone || 'N/A');
        
        // Journey
        setElement('pickupLocation', data.pickup_location || 'N/A');
        setElement('dropoffLocation', data.dropoff_location || 'N/A');
        
        const pickupDateTime = (data.pickup_date && data.pickup_time) ? 
            formatDateTime(data.pickup_date, data.pickup_time) : 'N/A';
        setElement('pickupDateTime', pickupDateTime);
        
        const returnDateTime = (data.return_date && data.return_time) ? 
            formatDateTime(data.return_date, data.return_time) : 'N/A';
        setElement('returnDateTime', returnDateTime);
        
        // Details
        setElement('duration', data.duration ? data.duration + ' days' : 'N/A');
        setElement('passengers', data.passengers || 'N/A');
        setElement('luggage', data.luggage || 'N/A');
        
        // Vehicle
        setElement('vehicleModel', data.vehicle_model || 'N/A');
        setElement('vehicleType', data.vehicle_type || 'N/A');
        setElement('vehicleNumber', data.vehicle_number || 'N/A');
        setElement('acType', data.ac_type || 'N/A');
        setElement('passengerCount', data.passenger_count || 'N/A');
        setElement('workingDistrict', data.working_district || 'N/A');
        
        // Special Requirements
        setElement('specialRequirements', data.special_requirements || 'No special requirements');
        
        // Status
        setElement('bookingStatus', capitalizeFirst(data.booking_status || 'pending'));
        setElement('paymentStatus', capitalizeFirst(data.payment_status || 'pending'));
        setElement('bookingDate', formatDate(data.booking_date) || 'N/A');
        
        // Pricing
        setElement('basePrice', data.base_price ? 'LKR ' + data.base_price : 'LKR 0.00');
        setElement('serviceCharge', data.service_charge ? 'LKR ' + data.service_charge : 'LKR 0.00');
        setElement('taxAmount', data.tax_amount ? 'LKR ' + data.tax_amount : 'LKR 0.00');
        setElement('totalPrice', data.total_price ? 'LKR ' + data.total_price : 'LKR 0.00');
        
        // Status header
        const header = document.getElementById('bookingStatusHeader');
        if (header) {
            const statusSpan = header.querySelector('span');
            if (statusSpan) {
                statusSpan.textContent = capitalizeFirst(data.booking_status || 'pending');
            }
            header.className = 'booking-status ' + (data.booking_status || 'pending');
        }
        
        // Action buttons
        setupActions(data.booking_status || 'pending');
        
        console.log('Population complete');
    } catch (error) {
        console.error('Error populating:', error);
    }
}

// Helper function to format date and time
function formatDateTime(date, time) {
    if (!date || !time) return 'N/A';
    try {
        const dateObj = new Date(date + ' ' + time);
        return dateObj.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return date + ' ' + time;
    }
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return dateString;
    }
}

function setElement(id, value) {
    const el = document.getElementById(id);
    if (el) {
        el.textContent = value || 'N/A';
    } else {
        console.warn('Element not found:', id);
    }
}

function capitalizeFirst(str) {
    if (!str) return 'N/A';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Setup action buttons based on status
function setupActions(status) {
    const section = document.getElementById('actionsSection');
    if (!section) {
        console.warn('Actions section not found');
        return;
    }
    
    const statusLower = (status || 'pending').toLowerCase();
    
    switch(statusLower) {
        case 'pending':
            section.innerHTML = `
                <button class="btn-accept" onclick="updateStatus('confirmed')">
                    <i class="fas fa-check"></i> Accept Booking
                </button>
                <button class="btn-reject" onclick="updateStatus('cancelled')">
                    <i class="fas fa-times"></i> Reject Booking
                </button>
            `;
            break;
        case 'confirmed':
            section.innerHTML = `
                <button class="btn-complete" onclick="updateStatus('completed')">
                    <i class="fas fa-check-circle"></i> Mark as Completed
                </button>
                <button class="btn-cancel" onclick="updateStatus('cancelled')">
                    <i class="fas fa-ban"></i> Cancel Booking
                </button>
            `;
            break;
        case 'completed':
            section.innerHTML = '<div class="completed-info"><i class="fas fa-check-circle" style="color: #10b981;"></i> <span>This booking has been completed</span></div>';
            break;
        case 'cancelled':
            section.innerHTML = '<div class="cancelled-info"><i class="fas fa-times-circle" style="color: #ef4444;"></i> <span>This booking has been cancelled</span></div>';
            break;
        default:
            section.innerHTML = '';
    }
}

// Update booking status
async function updateStatus(newStatus) {
    if (!confirm(`Are you sure you want to mark this booking as ${newStatus}?`)) {
        return;
    }
    
    try {
        showAlert('Updating booking status...', 'info');
        
        const response = await fetch('/TravelMate/public/api/transport-booking/update-status', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                booking_id: bookingId, 
                status: newStatus 
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(`Booking ${newStatus}!`, 'success');
            setTimeout(() => loadBookingDetails(), 1500);
        } else {
            showAlert('Failed: ' + (result.errors?.general || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error updating status:', error);
        showAlert('Error: ' + error.message, 'error');
    }
}

// Show alert message
function showAlert(message, type) {
    // Check if alert box exists
    let alertBox = document.getElementById('alertBox');
    
    if (!alertBox) {
        // Create alert box if it doesn't exist
        alertBox = document.createElement('div');
        alertBox.id = 'alertBox';
        alertBox.className = 'alert';
        alertBox.setAttribute('role', 'alert');
        alertBox.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: none;
        `;
        document.body.appendChild(alertBox);
    }
    
    // Set alert style based on type
    alertBox.style.backgroundColor = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6');
    alertBox.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i> ${message}`;
    alertBox.style.display = 'block';
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        alertBox.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (alertBox.parentNode) {
                alertBox.style.display = 'none';
                alertBox.style.animation = 'slideIn 0.3s ease-out'; // Reset animation
            }
        }, 300);
    }, 3000);
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(420px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(420px); opacity: 0; }
    }
`;
document.head.appendChild(style);