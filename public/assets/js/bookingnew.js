// bookingnew.js - Transport Bookings Management

const BASE_PATH = window.location.pathname.includes('/TravelMate/') 
    ? '/TravelMate/public' 
    : '';

let allBookings = [];
let filteredBookings = [];

// Load bookings on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTransporterBookings();
    
    // Refresh button
    document.getElementById('refreshBookings').addEventListener('click', function() {
        loadTransporterBookings();
    });
    
    // Search and filter
    document.getElementById('applyFilter').addEventListener('click', function() {
        filterBookings();
    });
    
    // Enter key in search box
    document.getElementById('searchBox').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            filterBookings();
        }
    });
});

// Load transporter bookings
async function loadTransporterBookings() {
    const bookingList = document.querySelector('.booking-list');
    
    try {
        bookingList.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading bookings...</div>';
        
        const response = await fetch(`${BASE_PATH}/api/transport-booking/transporter-bookings`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Bookings loaded:', result);
        
        if (result.success) {
            allBookings = result.data || [];
            updateStats(result.stats || { pending: 0, confirmed: 0, total: 0 });
            displayBookings(allBookings);
        } else {
            showAlert(result.errors?.general || 'Failed to load bookings', 'error');
            bookingList.innerHTML = '<div class="empty-state">No bookings found</div>';
        }
    } catch (error) {
        console.error('Error loading bookings:', error);
        showAlert('Error loading bookings: ' + error.message, 'error');
        bookingList.innerHTML = '<div class="empty-state">Failed to load bookings. Please try again.</div>';
    }
}

// Update statistics
function updateStats(stats) {
    const statsElements = document.querySelectorAll('.stat-num');
    if (statsElements.length >= 3) {
        statsElements[0].textContent = stats.pending || 0;
        statsElements[1].textContent = stats.confirmed || 0;
        statsElements[2].textContent = stats.total || 0;
    }
}

// Display bookings
function displayBookings(bookings) {
    const bookingList = document.querySelector('.booking-list');
    
    if (!bookings || bookings.length === 0) {
        bookingList.innerHTML = '<div class="empty-state">No booking requests found</div>';
        return;
    }
    
    let html = '';
    bookings.forEach(booking => {
        html += createBookingCard(booking);
    });
    
    bookingList.innerHTML = html;
    
    // Add click handlers to view buttons
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const bookingId = this.getAttribute('data-booking-id');
            viewBookingDetails(bookingId);
        });
    });
}

// Create booking card HTML
function createBookingCard(booking) {
    const pickupDate = new Date(booking.pickup_date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
    });
    
    const returnDate = new Date(booking.return_date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
    });
    
    const statusClass = booking.booking_status || 'pending';
    const customerName = `${booking.first_name || ''} ${booking.last_name || ''}`.trim() || 'Customer';
    
    return `
        <div class="booking-card" data-booking-id="${booking.booking_id}">
            <div class="booking-card-header">
                <div class="customer-info">
                    <div class="customer-avatar">
                        ${getInitials(customerName)}
                    </div>
                    <div class="customer-details">
                        <h4>${customerName}</h4>
                        <p>${booking.vehicle_model || 'Vehicle'} • ${booking.vehicle_type || 'Transport'}</p>
                    </div>
                </div>
                <span class="status-badge ${statusClass}">${statusClass}</span>
            </div>
            
            <div class="booking-card-body">
                <div class="booking-route">
                    <div class="route-point">
                        <span class="point-icon pickup">🚩</span>
                        <div class="point-details">
                            <span class="point-label">Pickup</span>
                            <span class="point-value">${booking.pickup_location}</span>
                            <span class="point-date">${pickupDate} at ${booking.pickup_time}</span>
                        </div>
                    </div>
                    <div class="route-point">
                        <span class="point-icon dropoff">🏁</span>
                        <div class="point-details">
                            <span class="point-label">Dropoff</span>
                            <span class="point-value">${booking.dropoff_location}</span>
                            <span class="point-date">${returnDate} at ${booking.return_time}</span>
                        </div>
                    </div>
                </div>
                
                <div class="booking-meta">
                    <div class="meta-item">
                        <span class="meta-icon">👥</span>
                        <span>${booking.passengers} passenger${booking.passengers > 1 ? 's' : ''}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-icon">💰</span>
                        <span>LKR ${parseFloat(booking.total_price || 0).toLocaleString()}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-icon">📅</span>
                        <span>${pickupDate}</span>
                    </div>
                </div>
            </div>
            
            <div class="booking-card-footer">
                <button class="view-details-btn" data-booking-id="${booking.booking_id}">
                    <i class="fas fa-eye"></i> View Details
                </button>
            </div>
        </div>
    `;
}

// Get initials from name
function getInitials(name) {
    return name.split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
}

// Filter bookings
function filterBookings() {
    const searchTerm = document.getElementById('searchBox').value.toLowerCase();
    const statusFilter = document.getElementById('categoryFilter').value;
    
    filteredBookings = allBookings.filter(booking => {
        // Status filter
        if (statusFilter !== 'all' && booking.booking_status !== statusFilter) {
            return false;
        }
        
        // Search filter
        if (searchTerm) {
            const customerName = `${booking.first_name || ''} ${booking.last_name || ''}`.toLowerCase();
            const vehicleModel = (booking.vehicle_model || '').toLowerCase();
            const pickupLocation = (booking.pickup_location || '').toLowerCase();
            
            return customerName.includes(searchTerm) || 
                   vehicleModel.includes(searchTerm) || 
                   pickupLocation.includes(searchTerm);
        }
        
        return true;
    });
    
    displayBookings(filteredBookings);
}

// View booking details
function viewBookingDetails(bookingId) {
    window.location.href = `${BASE_PATH}/transport-booking-details/${bookingId}`;
}

// Show alert message
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    alertDiv.style.backgroundColor = type === 'success' ? '#10b981' : '#ef4444';
    alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
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