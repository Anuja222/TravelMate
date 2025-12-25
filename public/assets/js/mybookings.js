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
async function cancelBooking(bookingId) {
    if (!confirm('Are you sure you want to cancel this booking?')) {
        return;
    }

    try {
        const response = await fetch(`${BASE_PATH}/api/booking/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ bookingId: bookingId })
        });

        const result = await response.json();

        if (result.success) {
            alert('Booking cancelled successfully!');
            loadUserBookings(); // Reload bookings
        } else {
            alert('Failed to cancel booking: ' + (result.errors.general || 'Please try again.'));
        }
    } catch (error) {
        console.error('Error cancelling booking:', error);
        alert('An error occurred while cancelling the booking.');
    }
}

// Delete booking
async function deleteBooking(bookingId) {
    if (!confirm('Are you sure you want to permanently delete this booking? This action cannot be undone.')) {
        return;
    }

    try {
        const response = await fetch(`${BASE_PATH}/api/booking/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ bookingId: bookingId })
        });

        const result = await response.json();

        if (result.success) {
            alert('Booking deleted successfully!');
            loadUserBookings(); // Reload bookings
        } else {
            alert('Failed to delete booking: ' + (result.errors.general || 'Please try again.'));
        }
    } catch (error) {
        console.error('Error deleting booking:', error);
        alert('An error occurred while deleting the booking.');
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