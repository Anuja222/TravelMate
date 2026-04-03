// bookingnew.js - Transport Bookings Management

const BASE_PATH = window.location.pathname.includes('/TravelMate/') 
    ? '/TravelMate/public' 
    : '';

let allBookings = [];
let filteredBookings = [];
let currentBookingId = null;

// Load bookings on page load
document.addEventListener('DOMContentLoaded', function() {
    // Remove any stray status badges that might be in the page
    removeStrayStatusBadges();
    
    loadTransporterBookings();
    
    // Refresh button
    const refreshBtn = document.getElementById('refreshBookings');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            loadTransporterBookings();
        });
    }
    
    // Search and filter
    const applyFilterBtn = document.getElementById('applyFilter');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            filterBookings();
        });
    }
    
    // Enter key in search box
    const searchBox = document.getElementById('searchBox');
    if (searchBox) {
        searchBox.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                filterBookings();
            }
        });
    }
    
    // Category filter change
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            filterBookings();
        });
    }
    
    // Close modal when clicking on X or outside
    const modal = document.getElementById('bookingDetailsModal');
    const closeBtn = document.querySelector('.close-modal');
    const cancelBtn = document.getElementById('cancelModal');
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // Handle status update
    const updateStatusBtn = document.getElementById('updateStatusBtn');
    if (updateStatusBtn) {
        updateStatusBtn.addEventListener('click', updateBookingStatus);
    }
});

// Function to remove any stray status badges from the page
function removeStrayStatusBadges() {
    // Select all elements with class 'status-badge'
    const allBadges = document.querySelectorAll('.status-badge');
    
    allBadges.forEach(badge => {
        // Check if the badge is inside a booking card (these are valid)
        const isInBookingCard = badge.closest('.booking-card');
        
        // Check if the badge is inside the modal (these are valid)
        const isInModal = badge.closest('.modal');
        
        // Check if the badge is in the summary stats (these are stat-num, not badges)
        const isInSummary = badge.closest('.summary-stats');
        
        // Check if the badge is a stat label (PENDING, COMPLETED, TOTAL BOOKINGS)
        const isStatLabel = badge.classList.contains('stat-label') || 
                           (badge.parentElement && badge.parentElement.classList.contains('stat'));
        
        // Check if the badge is inside the page title area (these are the stray ones)
        const isInPageTitle = badge.closest('.page-title');
        
        // Check if the badge is directly after h1 or in the content area but not in booking card
        const isInContentArea = badge.closest('.content') && !isInBookingCard && !isInModal;
        
        // If the badge is not in a valid location, remove it
        if (!isInBookingCard && !isInModal && !isInSummary && !isStatLabel) {
            console.log('Removing stray status badge:', badge);
            badge.remove();
        }
        
        // Specifically remove any badge that is a direct child of .content or .page-title
        if (isInPageTitle || (isInContentArea && !isInBookingCard)) {
            console.log('Removing stray status badge from header area:', badge);
            badge.remove();
        }
    });
    
    // Also look for any spans with text "COMPLETED", "PENDING", etc. that might be styled as badges
    const allSpans = document.querySelectorAll('span');
    allSpans.forEach(span => {
        const text = span.textContent.trim();
        // Check if it's a status text but not in the right place
        if ((text === 'COMPLETED' || text === 'PENDING' || text === 'confirmed' || text === 'cancelled') && 
            !span.classList.contains('status-badge') && 
            !span.closest('.summary-stats') && 
            !span.closest('.stat') &&
            !span.closest('.booking-card') && 
            !span.closest('.modal')) {
            
            // Check if it's in the header area
            if (span.closest('.page-title') || span.closest('.content')) {
                console.log('Removing stray status text:', span);
                span.remove();
            }
        }
    });
}

// Load transporter bookings
async function loadTransporterBookings() {
    const bookingList = document.querySelector('.booking-list');
    
    try {
        if (bookingList) {
            bookingList.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading bookings...</div>';
        }
        
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
            updateStats(result.errors?.stats || { pending: 0, completed: 0, total: 0 });
            displayBookings(allBookings);
            
            // Remove any stray badges again after loading new content
            setTimeout(removeStrayStatusBadges, 100);
        } else {
            showAlert(result.errors?.general || 'Failed to load bookings', 'error');
            if (bookingList) {
                bookingList.innerHTML = '<div class="empty-state">No bookings found</div>';
            }
        }
    } catch (error) {
        console.error('Error loading bookings:', error);
        showAlert('Error loading bookings: ' + error.message, 'error');
        const bookingList = document.querySelector('.booking-list');
        if (bookingList) {
            bookingList.innerHTML = '<div class="empty-state">Failed to load bookings. Please try again.</div>';
        }
    }
}

// Update statistics - Only Pending, Completed, and Total
function updateStats(stats) {
    const statsElements = document.querySelectorAll('.stat-num');
    if (statsElements.length >= 3) {
        statsElements[0].textContent = stats.pending || 0;
        statsElements[1].textContent = stats.completed || 0;
        statsElements[2].textContent = stats.total || 0;
    }
}

// Display bookings
function displayBookings(bookings) {
    const bookingList = document.querySelector('.booking-list');
    
    if (!bookingList) return;
    
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
            if (bookingId) {
                viewBookingDetails(bookingId);
            }
        });
    });
    
    // Remove any stray badges after displaying bookings
    setTimeout(removeStrayStatusBadges, 50);
}

// Create booking card HTML - Only Pending and Completed status badges
function createBookingCard(booking) {
    try {
        const pickupDate = booking.pickup_date ? new Date(booking.pickup_date).toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric',
            year: 'numeric'
        }) : 'N/A';
        
        const returnDate = booking.return_date && booking.return_date !== '0000-00-00' 
            ? new Date(booking.return_date).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                year: 'numeric'
            }) : 'N/A';
        
        // Only show pending or completed status, default to pending for others
        let statusClass = 'pending';
        let statusText = 'Pending';
        
        if (booking.booking_status === 'completed') {
            statusClass = 'completed';
            statusText = 'Completed';
        } else {
            statusClass = 'pending';
            statusText = 'Pending';
        }
        
        const customerName = `${booking.first_name || ''} ${booking.last_name || ''}`.trim() || 'Customer';
        const totalPrice = booking.total_price ? parseFloat(booking.total_price).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) : '0.00';
        
        return `
            <div class="booking-card" data-booking-id="${booking.booking_id || ''}">
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
                    <span class="status-badge ${statusClass}">${statusText}</span>
                </div>
                
                <div class="booking-card-body">
                    <div class="booking-route">
                        <div class="route-point">
                            <span class="point-icon pickup">🚩</span>
                            <div class="point-details">
                                <span class="point-label">Pickup</span>
                                <span class="point-value">${booking.pickup_location || 'N/A'}</span>
                                <span class="point-date">${pickupDate} at ${booking.pickup_time || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="route-point">
                            <span class="point-icon dropoff">🏁</span>
                            <div class="point-details">
                                <span class="point-label">Dropoff</span>
                                <span class="point-value">${booking.dropoff_location || 'N/A'}</span>
                                <span class="point-date">${returnDate} at ${booking.return_time || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="booking-meta">
                        <div class="meta-item">
                            <span class="meta-icon">👥</span>
                            <span>${booking.passengers || 0} passenger${booking.passengers > 1 ? 's' : ''}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">💰</span>
                            <span>LKR ${totalPrice}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">📅</span>
                            <span>${pickupDate}</span>
                        </div>
                    </div>
                </div>
                
                <div class="booking-card-footer">
                    <button class="view-details-btn" data-booking-id="${booking.booking_id || ''}">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                </div>
            </div>
        `;
    } catch (error) {
        console.error('Error creating booking card:', error);
        return '';
    }
}

// Get initials from name
function getInitials(name) {
    if (!name || name === 'Customer') return 'CU';
    return name.split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
}

// Filter bookings - Updated to handle only pending/completed
function filterBookings() {
    const searchBox = document.getElementById('searchBox');
    const categoryFilter = document.getElementById('categoryFilter');
    
    const searchTerm = searchBox ? searchBox.value.toLowerCase() : '';
    const statusFilter = categoryFilter ? categoryFilter.value : 'all';
    
    filteredBookings = allBookings.filter(booking => {
        // Convert confirmed/cancelled to pending for display
        let displayStatus = 'pending';
        if (booking.booking_status === 'completed') {
            displayStatus = 'completed';
        }
        
        // Status filter - only filter by pending or completed
        if (statusFilter !== 'all') {
            if (statusFilter === 'pending' && displayStatus !== 'pending') return false;
            if (statusFilter === 'completed' && displayStatus !== 'completed') return false;
        }
        
        // Search filter
        if (searchTerm) {
            const customerName = `${booking.first_name || ''} ${booking.last_name || ''}`.toLowerCase();
            const vehicleModel = (booking.vehicle_model || '').toLowerCase();
            const pickupLocation = (booking.pickup_location || '').toLowerCase();
            const bookingId = (booking.booking_id || '').toLowerCase();
            
            return customerName.includes(searchTerm) || 
                   vehicleModel.includes(searchTerm) || 
                   pickupLocation.includes(searchTerm) ||
                   bookingId.includes(searchTerm);
        }
        
        return true;
    });
    
    displayBookings(filteredBookings);
}

// View booking details in modal
async function viewBookingDetails(bookingId) {
    if (!bookingId) return;
    
    currentBookingId = bookingId;
    
    // Show modal with loading state
    const modal = document.getElementById('bookingDetailsModal');
    const modalContent = document.getElementById('modalContent');
    
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    modal.style.display = 'flex';
    modalContent.innerHTML = `
        <div class="modal-loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading booking details...</p>
        </div>
    `;
    
    try {
        const response = await fetch(`${BASE_PATH}/api/transport-booking/transporter-details/${bookingId}`, {
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
        console.log('Booking details loaded:', result);
        
        if (result.success) {
            displayModalContent(result.data);
        } else {
            modalContent.innerHTML = `
                <div class="modal-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>${result.errors?.general || 'Failed to load booking details'}</p>
                    <button onclick="closeModal()" class="btn-primary">Close</button>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading booking details:', error);
        modalContent.innerHTML = `
            <div class="modal-error">
                <i class="fas fa-exclamation-circle"></i>
                <p>Error loading booking details: ${error.message}</p>
                <button onclick="closeModal()" class="btn-primary">Close</button>
            </div>
        `;
    }
}

// Display modal content with booking details - Updated to show only Pending/Completed
function displayModalContent(booking) {
    const modalContent = document.getElementById('modalContent');
    
    // Format dates
    const pickupDate = booking.pickup_date ? new Date(booking.pickup_date).toLocaleDateString('en-US', { 
        weekday: 'long',
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    }) : 'N/A';
    
    const returnDate = booking.return_date && booking.return_date !== '0000-00-00' 
        ? new Date(booking.return_date).toLocaleDateString('en-US', { 
            weekday: 'long',
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        }) : 'Not specified';
    
    const bookingDate = booking.booking_date ? new Date(booking.booking_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }) : 'N/A';
    
    // Convert status to only Pending or Completed
    let statusClass = 'pending';
    let statusText = 'Pending';
    
    if (booking.booking_status === 'completed') {
        statusClass = 'completed';
        statusText = 'Completed';
    }
    
    const paymentStatusClass = booking.payment_status || 'pending';
    const tripType = booking.trip_type === 'round_trip' ? 'Round Trip' : 'One Way';
    
    // Format currency
    const formatCurrency = (amount) => {
        return parseFloat(amount || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };
    
    modalContent.innerHTML = `
        <div class="modal-header">
            <h2>Trip Details</h2>
            <span class="booking-id">#${booking.booking_id}</span>
            <span class="status-badge ${statusClass}">${statusText}</span>
            <span class="close-modal">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="detail-section">
                <h3><i class="fas fa-user"></i> Customer Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value">${booking.customer_name || booking.first_name + ' ' + booking.last_name}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">${booking.email || 'N/A'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">${booking.phone || 'N/A'}</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3><i class="fas fa-map-marker-alt"></i> Trip Information</h3>
                <div class="trip-route">
                    <div class="route-detail">
                        <div class="route-icon pickup">🚩</div>
                        <div class="route-info">
                            <span class="route-label">Pickup Location</span>
                            <span class="route-value">${booking.pickup_location || 'N/A'}</span>
                            <span class="route-time">${pickupDate} at ${booking.pickup_time || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="route-arrow"><i class="fas fa-arrow-down"></i></div>
                    <div class="route-detail">
                        <div class="route-icon dropoff">🏁</div>
                        <div class="route-info">
                            <span class="route-label">Dropoff Location</span>
                            <span class="route-value">${booking.dropoff_location || 'N/A'}</span>
                            <span class="route-time">${returnDate} at ${booking.return_time || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                <div class="trip-type-badge">
                    <span class="badge ${booking.trip_type}">${tripType}</span>
                    <span class="duration">Duration: ${booking.duration || 1} day(s)</span>
                </div>
            </div>
            
            <div class="detail-section">
                <h3><i class="fas fa-car"></i> Vehicle Details</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Vehicle:</span>
                        <span class="detail-value">${booking.vehicle_model || 'N/A'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Type:</span>
                        <span class="detail-value">${booking.vehicle_type || 'N/A'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Vehicle Number:</span>
                        <span class="detail-value">${booking.vehicle_number || 'N/A'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">AC Type:</span>
                        <span class="detail-value">${booking.ac_type || 'N/A'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Passenger Capacity:</span>
                        <span class="detail-value">${booking.passenger_capacity || booking.passenger_count || 0}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Working District:</span>
                        <span class="detail-value">${booking.working_district || 'N/A'}</span>
                    </div>
                    ${booking.vehicle_color ? `
                    <div class="detail-item">
                        <span class="detail-label">Color:</span>
                        <span class="detail-value">${booking.vehicle_color}</span>
                    </div>
                    ` : ''}
                    ${booking.vehicle_year ? `
                    <div class="detail-item">
                        <span class="detail-label">Year:</span>
                        <span class="detail-value">${booking.vehicle_year}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
            
            <div class="detail-section">
                <h3><i class="fas fa-users"></i> Trip Requirements</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Passengers:</span>
                        <span class="detail-value">${booking.passengers || 0}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Luggage:</span>
                        <span class="detail-value">${booking.luggage || 0} pieces</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Service Type:</span>
                        <span class="detail-value">${booking.service_type || 'Transport'}</span>
                    </div>
                    ${booking.special_requirements ? `
                    <div class="detail-item full-width">
                        <span class="detail-label">Special Requirements:</span>
                        <span class="detail-value">${booking.special_requirements}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
            
            <div class="detail-section">
                <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                <div class="payment-details">
                    <div class="payment-row">
                        <span>Base Price:</span>
                        <span>LKR ${formatCurrency(booking.base_price)}</span>
                    </div>
                    <div class="payment-row">
                        <span>Service Charge:</span>
                        <span>LKR ${formatCurrency(booking.service_charge)}</span>
                    </div>
                    <div class="payment-row total">
                        <span>Total Amount:</span>
                        <span>LKR ${formatCurrency(booking.total_price)}</span>
                    </div>
                    <div class="payment-row">
                        <span>Payment Status:</span>
                        <span class="payment-status ${booking.payment_status}">${booking.payment_status}</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3><i class="fas fa-calendar-alt"></i> Booking Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Booking Date:</span>
                        <span class="detail-value">${bookingDate}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Booking Reference:</span>
                        <span class="detail-value">${booking.booking_reference || booking.booking_id}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <div class="status-update">
                <select id="statusSelect" class="status-select">
                    <option value="pending" ${booking.booking_status !== 'completed' ? 'selected' : ''}>Pending</option>
                    <option value="completed" ${booking.booking_status === 'completed' ? 'selected' : ''}>Completed</option>
                </select>
                <button id="updateStatusBtn" class="btn-update-status">
                    <i class="fas fa-sync-alt"></i> Update Status
                </button>
            </div>
            <button id="cancelModal" class="btn-secondary">Close</button>
        </div>
    `;
    
    // Re-attach event listeners
    document.getElementById('updateStatusBtn').addEventListener('click', updateBookingStatus);
    document.getElementById('cancelModal').addEventListener('click', closeModal);
    document.querySelector('.close-modal').addEventListener('click', closeModal);
}

// Update booking status - Updated to handle only pending/completed
async function updateBookingStatus() {
    if (!currentBookingId) return;
    
    const statusSelect = document.getElementById('statusSelect');
    const newStatus = statusSelect.value;
    const updateBtn = document.getElementById('updateStatusBtn');
    const originalText = updateBtn.innerHTML;
    
    // Show loading state
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    updateBtn.disabled = true;
    
    try {
        const response = await fetch(`${BASE_PATH}/api/transport-booking/update-status`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                booking_id: currentBookingId,
                status: newStatus
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Booking status updated successfully', 'success');
            
            // Update the status badge in modal
            const statusBadge = document.querySelector('.modal-header .status-badge');
            if (statusBadge) {
                const displayText = newStatus === 'completed' ? 'Completed' : 'Pending';
                statusBadge.className = `status-badge ${newStatus}`;
                statusBadge.textContent = displayText;
            }
            
            // Reload bookings to reflect changes
            loadTransporterBookings();
        } else {
            showAlert(result.errors?.general || 'Failed to update status', 'error');
        }
    } catch (error) {
        console.error('Error updating status:', error);
        showAlert('Error updating status: ' + error.message, 'error');
    } finally {
        // Restore button state
        updateBtn.innerHTML = originalText;
        updateBtn.disabled = false;
    }
}

// Close modal
function closeModal() {
    const modal = document.getElementById('bookingDetailsModal');
    if (modal) {
        modal.style.display = 'none';
        currentBookingId = null;
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
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
    
    .trip-type-badge {
        margin-top: 15px;
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .trip-type-badge .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .badge.round_trip {
        background-color: #e3f2fd;
        color: #1fc725;
    }
    
    .badge.one_way {
        background-color: #f3e5f5;
        color: #17cc2d;
    }
    
    .duration {
        color: #666;
        font-size: 14px;
    }
`;
document.head.appendChild(style);