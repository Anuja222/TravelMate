// My Transport Bookings JavaScript

const BASE_PATH = window.location.pathname.includes('/TravelMate/')
    ? '/TravelMate/public'
    : '';

let allBookings = [];
let currentFilter = 'all';
let bookingIdToCancel = null;
let bookingIdToDelete = null;

// Load bookings on page load
document.addEventListener('DOMContentLoaded', function() {
    loadBookings();
    setupFilterTabs();
});

// Setup filter tabs
function setupFilterTabs() {
    const tabs = document.querySelectorAll('.filter-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            filterBookings();
        });
    });
}

// Load all bookings
async function loadBookings() {
    const loadingContainer = document.querySelector('.loading-container');
    const transportSection = document.querySelector('[data-category="transport"]');

    if (loadingContainer) loadingContainer.style.display = 'block';

    try {
        const response = await fetch(`${BASE_PATH}/api/transport-booking/all`, {
            method: 'GET',
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Failed to fetch bookings');
        }

        const result = await response.json();
        console.log('Bookings loaded:', result);

        if (result.success) {
            allBookings = result.data.bookings || [];
            
            if (loadingContainer) loadingContainer.style.display = 'none';
            
            displayBookings(allBookings);
            updateBookingStats();
        } else {
            throw new Error(result.errors?.general || 'Failed to load bookings');
        }
    } catch (error) {
        console.error('Error loading bookings:', error);
        if (loadingContainer) loadingContainer.style.display = 'none';
        showEmptyState();
    }
}

// Filter bookings
function filterBookings() {
    let filteredBookings = allBookings;

    if (currentFilter !== 'all') {
        filteredBookings = allBookings.filter(booking => 
            booking.booking_status === currentFilter
        );
    }

    displayBookings(filteredBookings);
}

// Display bookings
function displayBookings(bookings) {
    const transportSection = document.querySelector('[data-category="transport"]');
    
    if (!transportSection) {
        console.error('Transport section not found');
        return;
    }
    
    // Clear existing bookings
    const existingItems = transportSection.querySelectorAll('.booking-item');
    existingItems.forEach(item => item.remove());

    if (bookings.length === 0) {
        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.style.textAlign = 'center';
        emptyState.style.padding = '3em';
        emptyState.innerHTML = `
            <div style="font-size: 4em; margin-bottom: 0.5em;">🚗</div>
            <h3>No Transport Bookings</h3>
            <p>You haven't booked any transportation yet.</p>
        `;
        transportSection.appendChild(emptyState);
        return;
    }

    bookings.forEach(booking => {
        const bookingElement = createBookingElement(booking);
        transportSection.appendChild(bookingElement);
    });
}

// Create booking element HTML
function createBookingElement(booking) {
    const bookingDiv = document.createElement('div');
    bookingDiv.className = 'booking-item';
    bookingDiv.setAttribute('data-status', booking.booking_status);
    bookingDiv.setAttribute('data-type', 'transport');
    bookingDiv.setAttribute('data-booking-id', booking.booking_id);

    const pickupDate = new Date(booking.pickup_date).toLocaleDateString('en-US', { 
        month: 'short', day: 'numeric', year: 'numeric' 
    });
    const returnDate = new Date(booking.return_date).toLocaleDateString('en-US', { 
        month: 'short', day: 'numeric', year: 'numeric' 
    });

    const vehicleName = booking.vehicle_model || 'Transport Vehicle';
    const serviceType = formatServiceType(booking.service_type);

    bookingDiv.innerHTML = `
        <div class="booking-image">
            <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=300&h=200&fit=crop" alt="${vehicleName}">
        </div>
        <div class="booking-details">
            <h3 class="booking-title">${vehicleName}</h3>
            <div class="booking-info">
                <div class="info-item">
                    <span class="info-icon">🚗</span>
                    <span>${serviceType}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">📅</span>
                    <span>Pickup: ${pickupDate}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">📅</span>
                    <span>Return: ${returnDate}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">📍</span>
                    <span>${booking.pickup_location} → ${booking.dropoff_location}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">👥</span>
                    <span>${booking.passengers} Passenger${booking.passengers > 1 ? 's' : ''}</span>
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

// Format service type
function formatServiceType(type) {
    const types = {
        'airport': 'Airport Transfer',
        'daily': 'Daily Rental',
        'tour': 'Tour Package',
        'custom': 'Custom Service'
    };
    return types[type] || type;
}

// Capitalize first letter
function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Show empty state
function showEmptyState() {
    const transportSection = document.querySelector('[data-category="transport"]');
    if (!transportSection) return;

    const existingItems = transportSection.querySelectorAll('.booking-item');
    existingItems.forEach(item => item.remove());

    const emptyState = document.createElement('div');
    emptyState.className = 'empty-state';
    emptyState.style.textAlign = 'center';
    emptyState.style.padding = '3em';
    emptyState.innerHTML = `
        <div style="font-size: 4em; margin-bottom: 0.5em;">🚗</div>
        <h3>No Transport Bookings</h3>
        <p>You haven't booked any transportation yet.</p>
    `;
    transportSection.appendChild(emptyState);
}

// Update booking statistics
function updateBookingStats() {
    const stats = allBookings.reduce((acc, booking) => {
        acc.total++;
        if (booking.booking_status === 'confirmed') acc.confirmed++;
        if (booking.booking_status === 'pending') acc.pending++;
        acc.totalSpent += parseFloat(booking.total_price || 0);
        return acc;
    }, { total: 0, confirmed: 0, pending: 0, totalSpent: 0 });

    const statsContainer = document.getElementById('bookingStats');
    if (statsContainer) {
        statsContainer.innerHTML = `
            <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                <div style="font-size: 2em; font-weight: 700; color: #1abc5b;">${stats.total}</div>
                <div style="color: #666;">Total Bookings</div>
            </div>
            <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                <div style="font-size: 2em; font-weight: 700; color: #1abc5b;">${stats.confirmed}</div>
                <div style="color: #666;">Confirmed</div>
            </div>
            <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                <div style="font-size: 2em; font-weight: 700; color: #f39c12;">${stats.pending}</div>
                <div style="color: #666;">Pending</div>
            </div>
            <div style="text-align: center; padding: 1em; background: #f8f9fa; border-radius: 12px;">
                <div style="font-size: 2em; font-weight: 700; color: #169d4a;">LKR ${Math.round(stats.totalSpent).toLocaleString()}</div>
                <div style="color: #666;">Total Spent</div>
            </div>
        `;
    }
}
// View booking details
function viewBookingDetails(bookingId) {
    const booking = allBookings.find(b => b.booking_id === bookingId);
    if (!booking) return;

    const modal = document.getElementById('bookingDetailsModal');
    const detailsContainer = document.getElementById('modalContent');
    
    const pickupDate = new Date(booking.pickup_date).toLocaleDateString('en-US', { 
        month: 'short', day: 'numeric', year: 'numeric' 
    });
    const returnDate = new Date(booking.return_date).toLocaleDateString('en-US', { 
        month: 'short', day: 'numeric', year: 'numeric' 
    });

    detailsContainer.innerHTML = `
        <div style="margin-bottom: 1.5em;">
            <h3 style="color: #1abc5b; margin-bottom: 0.5em; font-size: 22px;">${booking.vehicle_model || 'Transport Vehicle'}</h3>
            <span class="status-badge status-${booking.booking_status}">${capitalizeFirst(booking.booking_status)}</span>
        </div>
        <div>
            <strong>Booking ID:</strong> <span style="color: #1abc5b; font-weight: 600;">${booking.booking_id}</span>
        </div>
        <div>
            <strong>Service Type:</strong> ${formatServiceType(booking.service_type)}
        </div>
        <div>
            <strong>Pickup Date:</strong> ${pickupDate} at ${booking.pickup_time}
        </div>
        <div>
            <strong>Return Date:</strong> ${returnDate} at ${booking.return_time}
        </div>
        <div>
            <strong>Pickup Location:</strong> ${booking.pickup_location}
        </div>
        <div>
            <strong>Drop-off Location:</strong> ${booking.dropoff_location}
        </div>
        <div>
            <strong>Passengers:</strong> ${booking.passengers}
        </div>
        <div>
            <strong>Luggage:</strong> ${booking.luggage} bag(s)
        </div>
        ${booking.special_requirements ? `
        <div>
            <strong>Special Requirements:</strong> ${booking.special_requirements}
        </div>
        ` : ''}
        <div style="border-top: 2px solid #1abc5b; padding-top: 1em; margin-top: 1.5em;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.8em;">
                <span style="font-weight: 600;">Base Price:</span>
                <span style="font-weight: 600;">LKR ${parseFloat(booking.base_price).toLocaleString()}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.8em;">
                <span style="font-weight: 600;">Service Charge:</span>
                <span style="font-weight: 600;">LKR ${parseFloat(booking.service_charge).toLocaleString()}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 1.3em; font-weight: 700; color: #1abc5b; padding-top: 0.5em;">
                <span>Total Amount:</span>
                <span>LKR ${parseFloat(booking.total_price).toLocaleString()}</span>
            </div>
        </div>
    `;
    
    modal.style.display = 'block';
}

// Cancel booking
function cancelBooking(bookingId) {
    bookingIdToCancel = bookingId;
    const modal = document.getElementById('cancelConfirmModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// Close cancel confirmation modal
function closeConfirmModal() {
    const modal = document.getElementById('cancelConfirmModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
        bookingIdToCancel = null;
    }, 300);
}

// Proceed with cancel
async function proceedWithCancel() {
    closeConfirmModal();

    const localBookingId = bookingIdToCancel;
    if (!localBookingId) return;

    try {
        const response = await fetch(`${BASE_PATH}/api/transport-booking/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ booking_id: localBookingId }),
            credentials: 'same-origin'
        });

        const result = await response.json();

        if (result.success) {
            showCancelSuccessModal();
            setTimeout(() => {
                loadBookings();
            }, 500);
        } else {
            showErrorModal('Failed to cancel booking: ' + (result.errors?.general || 'Unknown error'));
        }
    } catch (error) {
        console.error('Cancel error:', error);
        showErrorModal('An error occurred while cancelling the booking');
    }
}

// Show cancel success modal
function showCancelSuccessModal() {
    const modal = document.getElementById('cancelSuccessModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// Close cancel success modal
function closeCancelSuccessModal() {
    const modal = document.getElementById('cancelSuccessModal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 300);
}

// Delete booking
function deleteBooking(bookingId) {
    bookingIdToDelete = bookingId;
    const modal = document.getElementById('deleteConfirmModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// Close delete confirmation modal
function closeDeleteConfirmModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
        bookingIdToDelete = null;
    }, 300);
}

// Proceed with delete
async function proceedWithDelete() {
    closeDeleteConfirmModal();

    const localBookingId = bookingIdToDelete;
    if (!localBookingId) return;

    try {
        const response = await fetch(`${BASE_PATH}/api/transport-booking/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ booking_id: localBookingId }),
            credentials: 'same-origin'
        });

        const result = await response.json();

        if (result.success) {
            showDeleteSuccessModal();
            setTimeout(() => {
                loadBookings();
            }, 500);
        } else {
            showErrorModal('Failed to delete booking: ' + (result.errors?.general || 'Unknown error'));
        }
    } catch (error) {
        console.error('Delete error:', error);
        showErrorModal('An error occurred while deleting the booking');
    }
}

// Show delete success modal
function showDeleteSuccessModal() {
    const modal = document.getElementById('deleteSuccessModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// Close delete success modal
function closeDeleteSuccessModal() {
    const modal = document.getElementById('deleteSuccessModal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 300);
}

// Show error modal
function showErrorModal(message) {
    const modal = document.getElementById('errorModal');
    const messageEl = document.getElementById('errorModalMessage');
    if (messageEl) messageEl.textContent = message;
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// Close error modal
function closeErrorModal() {
    const modal = document.getElementById('errorModal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 300);
}

// Close modal handlers
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close booking details modal
function closeBookingDetailsModal() {
    const modal = document.getElementById('bookingDetailsModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modals on outside click
window.onclick = function(event) {
    const modal = document.getElementById('bookingDetailsModal');
    if (event.target === modal) {
        closeBookingDetailsModal();
    }
}

// Setup modal close buttons
document.addEventListener('DOMContentLoaded', function() {
    // Close button for booking details modal
    const detailsModal = document.getElementById('bookingDetailsModal');
    if (detailsModal) {
        const closeBtn = detailsModal.querySelector('.close');
        if (closeBtn) {
            closeBtn.onclick = closeBookingDetailsModal;
        }
    }
});