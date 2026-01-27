const BASE_PATH = window.location.pathname.includes('/TravelMate/') 
    ? '/TravelMate/public' 
    : '';

// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    loadUserBookings();
    setupFilterTabs();
    setupSearchFunctionality();
});

// Load user bookings from database
async function loadUserBookings() {
    try {
        const response = await fetch(`${BASE_PATH}/api/booking/user`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success && result.data) {
            displayBookings(result.data.bookings);
            updateBookingStats(result.data.stats);
        } else {
            showEmptyState();
        }
    } catch (error) {
        console.error('Error loading bookings:', error);
        showErrorMessage('Failed to load bookings. Please try again.');
    }
}

// Display bookings on the page
function displayBookings(bookings) {
    const hotelsSection = document.querySelector('[data-category="hotels"]');
    const transportSection = document.querySelector('[data-category="transport"]');
    
    // Clear existing content
    const existingHotelItems = hotelsSection.querySelectorAll('.booking-item');
    const existingTransportItems = transportSection?.querySelectorAll('.booking-item');
    existingHotelItems.forEach(item => item.remove());
    existingTransportItems?.forEach(item => item.remove());

    if (bookings.length === 0) {
        showEmptyState();
        return;
    }

    // Separate hotel bookings from transport (you can add transport logic later)
    const hotelBookings = bookings;

    // Display hotel bookings
    hotelBookings.forEach(booking => {
        const bookingElement = createBookingElement(booking);
        hotelsSection.appendChild(bookingElement);
    });
}

// Create booking element HTML
function createBookingElement(booking) {
    const bookingDiv = document.createElement('div');
    bookingDiv.className = 'booking-item';
    bookingDiv.setAttribute('data-status', booking.booking_status);
    bookingDiv.setAttribute('data-type', 'hotels');
    bookingDiv.setAttribute('data-booking-id', booking.booking_id);

    const checkinDate = new Date(booking.checkin_date).toLocaleDateString('en-US', { 
        month: 'short', day: 'numeric', year: 'numeric' 
    });
    const checkoutDate = new Date(booking.checkout_date).toLocaleDateString('en-US', { 
        month: 'short', day: 'numeric', year: 'numeric' 
    });

    bookingDiv.innerHTML = `
        <div class="booking-image">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&h=200&fit=crop" alt="${booking.room_name}">
        </div>
        <div class="booking-details">
            <h3 class="booking-title">${booking.room_name}</h3>
            <div class="booking-info">
                <div class="info-item">
                    <span class="info-icon">📅</span>
                    <span>Check-in: ${checkinDate}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">📅</span>
                    <span>Check-out: ${checkoutDate}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">👥</span>
                    <span>${booking.adults} Adult${booking.adults > 1 ? 's' : ''}${booking.children > 0 ? ', ' + booking.children + ' Child' + (booking.children > 1 ? 'ren' : '') : ''}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">🛏️</span>
                    <span>${booking.nights} Night${booking.nights > 1 ? 's' : ''}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">💰</span>
                    <span class="booking-price">LKR ${parseFloat(booking.total_price).toLocaleString()}</span>
                </div>
            </div>
            <div class="booking-status">
                <span class="status-badge status-${booking.booking_status}">${capitalizeFirst(booking.booking_status)}</span>
                <div class="booking-actions">
                    <button class="action-btn btn-primary" onclick="viewBookingDetails('${booking.booking_id}')">View Details</button>
                    ${booking.booking_status !== 'cancelled' ? `<button class="action-btn btn-secondary" onclick="cancelBooking('${booking.booking_id}')">Cancel</button>` : ''}
                    ${booking.booking_status === 'cancelled' ? `<button class="action-btn btn-danger" onclick="deleteBooking('${booking.booking_id}')">Delete</button>` : ''}
                </div>
            </div>
        </div>
    `;

    return bookingDiv;
}

// View booking details
function viewBookingDetails(bookingId) {
    window.location.href = `${BASE_PATH}/mybooking_details?id=${bookingId}`;
}

// Cancel booking
// Store booking ID temporarily for cancel operation
let bookingIdToCancel = null;

// Show cancel confirmation modal
function cancelBooking(bookingId) {
    console.log('=== cancelBooking CALLED ==');
    console.log('Received booking ID:', bookingId);
    console.log('Type:', typeof bookingId);
    console.log('Is null?:', bookingId === null);
    console.log('Is undefined?:', bookingId === undefined);
    console.log('Is empty string?:', bookingId === '');
    
    if (!bookingId || bookingId === 'undefined' || bookingId === 'null' || bookingId === '') {
        alert('Invalid booking ID. Please refresh the page and try again.');
        console.error('Invalid booking ID received');
        return;
    }
    
    // Store the booking ID
    bookingIdToCancel = String(bookingId).trim();
    console.log('Stored booking ID:', bookingIdToCancel);
    
    const modal = document.getElementById('cancelConfirmModal');
    if (modal) {
        modal.classList.add('show');
        console.log('Modal shown');
    } else {
        console.error('Cancel confirmation modal not found!');
    }
}

// Close confirmation modal
function closeConfirmModal() {
    console.log('=== closeConfirmModal CALLED ==');
    const modal = document.getElementById('cancelConfirmModal');
    if (modal) {
        modal.classList.remove('show');
    }
    // Clear booking ID when user cancels
    bookingIdToCancel = null;
    console.log('Booking ID cleared');
}

// Proceed with cancellation
async function proceedWithCancel() {
    console.log('=== proceedWithCancel CALLED ==');
    console.log('Current bookingIdToCancel:', bookingIdToCancel);
    
    if (!bookingIdToCancel || bookingIdToCancel === 'null' || bookingIdToCancel === 'undefined') {
        console.error('No valid booking ID available');
        showErrorModal('Booking ID is missing. Please try again.');
        return;
    }
    
    // Store booking ID BEFORE closing modal or any other operations
    const bookingIdToProcess = String(bookingIdToCancel).trim();
    console.log('Processing booking ID:', bookingIdToProcess);
    console.log('BASE_PATH:', BASE_PATH);
    
    // Close confirmation modal
    const confirmModal = document.getElementById('cancelConfirmModal');
    if (confirmModal) {
        confirmModal.classList.remove('show');
        console.log('Confirmation modal closed');
    }

    try {
        const requestData = { bookingId: bookingIdToProcess };
        const url = `${BASE_PATH}/api/booking/cancel`;
        
        console.log('Request URL:', url);
        console.log('Request data:', requestData);
        console.log('Request JSON:', JSON.stringify(requestData));
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        });

        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        // Get response as text first
        const responseText = await response.text();
        console.log('Raw response:', responseText);
        
        // Try to parse as JSON
        let result;
        try {
            result = JSON.parse(responseText);
            console.log('Parsed result:', result);
        } catch (parseError) {
            console.error('JSON parse error:', parseError);
            console.error('Response was:', responseText);
            showErrorModal('Server returned invalid response. Response: ' + responseText.substring(0, 100));
            bookingIdToCancel = null;
            return;
        }

        if (result.success) {
            console.log('Cancellation successful!');
            showCancelSuccessModal();
            // Reload bookings after a short delay
            setTimeout(() => {
                loadUserBookings();
            }, 500);
        } else {
            console.error('Cancellation failed:', result);
            let errorMsg = 'Failed to cancel booking';
            if (result.errors) {
                if (result.errors.general) {
                    errorMsg = result.errors.general;
                } else if (result.errors.auth) {
                    errorMsg = result.errors.auth;
                } else {
                    errorMsg = JSON.stringify(result.errors);
                }
            }
            showErrorModal(errorMsg);
        }
    } catch (error) {
        console.error('Fetch error:', error);
        console.error('Error details:', error.message, error.stack);
        showErrorModal('Network error: ' + error.message);
    } finally {
        // Clear booking ID after operation
        bookingIdToCancel = null;
        console.log('Booking ID cleared after operation');
    }
}

// Show cancel success modal
function showCancelSuccessModal() {
    const modal = document.getElementById('cancelSuccessModal');
    modal.classList.add('show');
}

// Close cancel success modal
function closeCancelSuccessModal() {
    const modal = document.getElementById('cancelSuccessModal');
    modal.classList.remove('show');
}

// Show error modal
function showErrorModal(message) {
    const modal = document.getElementById('errorModal');
    const messageElement = document.getElementById('errorModalMessage');
    if (messageElement) {
        messageElement.textContent = message;
    }
    if (modal) {
        modal.classList.add('show');
    }
}

// Close error modal
function closeErrorModal() {
    const modal = document.getElementById('errorModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

// Delete booking
// Store booking ID temporarily for delete operation
let bookingIdToDelete = null;

// Show delete confirmation modal
function deleteBooking(bookingId) {
    console.log('=== deleteBooking CALLED ==');
    console.log('Received booking ID:', bookingId);
    
    if (!bookingId || bookingId === 'undefined' || bookingId === 'null' || bookingId === '') {
        alert('Invalid booking ID. Please refresh the page and try again.');
        console.error('Invalid booking ID received');
        return;
    }
    
    // Store the booking ID
    bookingIdToDelete = String(bookingId).trim();
    console.log('Stored booking ID for deletion:', bookingIdToDelete);
    
    const modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.classList.add('show');
        console.log('Delete confirmation modal shown');
    } else {
        console.error('Delete confirmation modal not found!');
    }
}

// Close delete confirmation modal
function closeDeleteConfirmModal() {
    console.log('=== closeDeleteConfirmModal CALLED ==');
    const modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.classList.remove('show');
    }
    // Clear booking ID when user cancels
    bookingIdToDelete = null;
    console.log('Booking ID cleared');
}

// Proceed with deletion
async function proceedWithDelete() {
    console.log('=== proceedWithDelete CALLED ==');
    console.log('Current bookingIdToDelete:', bookingIdToDelete);
    
    if (!bookingIdToDelete || bookingIdToDelete === 'null' || bookingIdToDelete === 'undefined') {
        console.error('No valid booking ID available');
        showErrorModal('Booking ID is missing. Please try again.');
        return;
    }
    
    // Store booking ID BEFORE closing modal
    const bookingIdToProcess = String(bookingIdToDelete).trim();
    console.log('Processing booking ID for deletion:', bookingIdToProcess);
    
    // Close confirmation modal
    const confirmModal = document.getElementById('deleteConfirmModal');
    if (confirmModal) {
        confirmModal.classList.remove('show');
        console.log('Delete confirmation modal closed');
    }

    try {
        const requestData = { bookingId: bookingIdToProcess };
        const url = `${BASE_PATH}/api/booking/delete`;
        
        console.log('Delete request URL:', url);
        console.log('Delete request data:', requestData);
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        });

        console.log('Delete response status:', response.status);
        
        // Get response as text first
        const responseText = await response.text();
        console.log('Delete raw response:', responseText);
        
        // Try to parse as JSON
        let result;
        try {
            result = JSON.parse(responseText);
            console.log('Delete parsed result:', result);
        } catch (parseError) {
            console.error('JSON parse error:', parseError);
            showErrorModal('Server returned invalid response');
            bookingIdToDelete = null;
            return;
        }

        if (result.success) {
            console.log('Deletion successful!');
            showDeleteSuccessModal();
            // Reload bookings after a short delay
            setTimeout(() => {
                loadUserBookings();
            }, 500);
        } else {
            console.error('Deletion failed:', result);
            let errorMsg = 'Failed to delete booking';
            if (result.errors) {
                if (result.errors.general) {
                    errorMsg = result.errors.general;
                } else if (result.errors.auth) {
                    errorMsg = result.errors.auth;
                } else {
                    errorMsg = JSON.stringify(result.errors);
                }
            }
            showErrorModal(errorMsg);
        }
    } catch (error) {
        console.error('Delete fetch error:', error);
        showErrorModal('Network error: ' + error.message);
    } finally {
        // Clear booking ID after operation
        bookingIdToDelete = null;
        console.log('Booking ID cleared after delete operation');
    }
}

// Show delete success modal
function showDeleteSuccessModal() {
    const modal = document.getElementById('deleteSuccessModal');
    if (modal) {
        modal.classList.add('show');
    }
}

// Close delete success modal
function closeDeleteSuccessModal() {
    const modal = document.getElementById('deleteSuccessModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

// Update booking statistics
function updateBookingStats(stats) {
    if (!stats) return;

    const statsContainer = document.querySelector('.booking-section[style*="margin-top"] > div:last-child');
    if (statsContainer) {
        const totalBookings = stats.total_bookings || 0;
        const totalSpent = stats.total_spent || 0;
        const confirmed = stats.confirmed || 0;
        const pending = stats.pending || 0;
        const cancelled = stats.cancelled || 0;

        statsContainer.innerHTML = `
            <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                <div style="font-size: 2em; font-weight: 700; color: #1abc5b;">${totalBookings}</div>
                <div style="color: #666;">Total Bookings</div>
            </div>
            <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                <div style="font-size: 2em; font-weight: 700; color: #1abc5b;">${confirmed}</div>
                <div style="color: #666;">Confirmed</div>
            </div>
            <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                <div style="font-size: 2em; font-weight: 700; color: #f39c12;">${cancelled}</div>
                <div style="color: #666;">Cancelled</div>
            </div>
            <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                <div style="font-size: 2em; font-weight: 700; color: #169d4a;">LKR ${parseFloat(totalSpent).toLocaleString()}</div>
                <div style="color: #666;">Total Spent</div>
            </div>
        `;
    }
}

// Filter bookings by status - FIXED VERSION
function filterBookingsByStatus(status) {
    const bookingItems = document.querySelectorAll('.booking-item');
    
    bookingItems.forEach(item => {
        const itemStatus = item.getAttribute('data-status');
        
        if (status === 'all') {
            item.style.display = 'flex';
        } else {
            item.style.display = itemStatus === status ? 'flex' : 'none';
        }
    });
    
    // Check if any bookings are visible
    const visibleBookings = document.querySelectorAll('.booking-item[style*="display: flex"]');
    const hotelsSection = document.querySelector('[data-category="hotels"]');
    
    // Remove existing empty state
    const existingEmptyState = hotelsSection.querySelector('.empty-state');
    if (existingEmptyState) {
        existingEmptyState.remove();
    }
    
    if (visibleBookings.length === 0) {
        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.innerHTML = `
            <div class="empty-state-icon">📭</div>
            <h3>No ${status === 'all' ? '' : capitalizeFirst(status)} Bookings</h3>
            <p>You don't have any ${status === 'all' ? '' : status} bookings at the moment.</p>
        `;
        hotelsSection.appendChild(emptyState);
    }
}

// Setup filter tabs - FIXED VERSION
function setupFilterTabs() {
    const tabs = document.querySelectorAll('.filter-tab');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Get filter value from data-filter attribute
            const filter = this.getAttribute('data-filter');
            
            // Filter bookings by status
            filterBookingsByStatus(filter);
        });
    });
}

// Setup search functionality
function setupSearchFunctionality() {
    // Add search box if it doesn't exist
    const filterTabs = document.querySelector('.filter-tabs');
    if (filterTabs && !document.querySelector('.search-box')) {
        const controlsSection = document.createElement('div');
        controlsSection.className = 'controls-section';
        controlsSection.innerHTML = `
            <input type="text" class="search-box" placeholder="Search bookings..." id="bookingSearch">
        `;
        filterTabs.parentNode.insertBefore(controlsSection, filterTabs.nextSibling);

        // Add search event listener
        const searchBox = document.getElementById('bookingSearch');
        let searchTimeout;
        searchBox.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchBookings(e.target.value);
            }, 500);
        });
    }
}

// Search bookings
async function searchBookings(searchTerm) {
    if (!searchTerm.trim()) {
        loadUserBookings();
        return;
    }

    try {
        const response = await fetch(`${BASE_PATH}/api/booking/search?search=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success && result.data) {
            displayBookings(result.data.bookings);
        }
    } catch (error) {
        console.error('Error searching bookings:', error);
    }
}

// Show empty state
function showEmptyState() {
    const hotelsSection = document.querySelector('[data-category="hotels"]');
    if (hotelsSection) {
        const existingItems = hotelsSection.querySelectorAll('.booking-item');
        existingItems.forEach(item => item.remove());

        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.innerHTML = `
            <div class="empty-state-icon">📭</div>
            <h3>No Bookings Yet</h3>
            <p>You haven't made any bookings yet. Start exploring amazing accommodations!</p>
            <a href="${BASE_PATH}/accommodations" class="explore-btn">Explore Accommodations</a>
        `;
        hotelsSection.appendChild(emptyState);
    }
}

// Show error message
function showErrorMessage(message) {
    alert(message);
}

// Helper function to capitalize first letter
function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}