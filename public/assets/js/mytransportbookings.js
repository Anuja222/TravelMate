// my Transport Bookings JavaScript

const BASE_PATH = window.location.pathname.includes('/TravelMate/')
    ? '/TravelMate/public'
    : '';

let allBookings = [];
let currentFilter = 'all';
let bookingIdToCancel = null;
let bookingIdToDelete = null;
const selectedTransportRatings = {};

function normalizeValue(value) {
    return String(value || '').trim().toLowerCase();
}

function buildVehicleImageUrl(imagePath) {
    if (!imagePath) {
        return '';
    }

    const value = String(imagePath).trim();
    if (!value) {
        return '';
    }

    if (/^https?:\/\//i.test(value)) {
        return value;
    }

    if (value.startsWith('/')) {
        return `${BASE_PATH}${value}`;
    }

    return `${BASE_PATH}/${value.replace(/^\/+/, '')}`;
}

function getVehicleImageFallbackByType(vehicleType) {
    const type = normalizeValue(vehicleType);
    const fallbackMap = {
        car: `${BASE_PATH}/assets/trimages/car.png`,
        van: `${BASE_PATH}/assets/trimages/van.jpg`,
        bus: `${BASE_PATH}/assets/trimages/Bus.jpeg`,
        jeep: `${BASE_PATH}/assets/trimages/jeepicon.webp`,
        tuk: `${BASE_PATH}/assets/trimages/tukicon.jpg`
    };

    return fallbackMap[type] || `${BASE_PATH}/assets/trimages/car.png`;
}

// load bookings on page load
document.addEventListener('DOMContentLoaded', function() {
    showQueryNotifications();
    loadBookings();
    setupFilterTabs();
});

function showQueryNotifications() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('booking') === 'requested') {
        alert('Your transport booking request is pending transporter approval.');
    }
    if (params.get('payment') === 'success') {
        alert('Payment completed. Your booking is now active in Transport Bookings.');
    }
}

// setup filter tabs
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

// load all bookings
async function loadBookings() {
    const loadingContainer = document.querySelector('.loading-container');

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

// filter bookings
function filterBookings() {
    let filteredBookings = allBookings;

    if (currentFilter !== 'all') {
        filteredBookings = allBookings.filter(booking => {
            const status = normalizeValue(booking.booking_status);
            const paymentStatus = normalizeValue(booking.payment_status);

            if (currentFilter === 'pending') {
                return status === 'pending' || (status === 'confirmed' && paymentStatus !== 'paid');
            }

            if (currentFilter === 'confirmed') {
                return status === 'confirmed' && paymentStatus === 'paid';
            }

            return status === currentFilter;
        });
    }

    displayBookings(filteredBookings);
}

// display bookings
function displayBookings(bookings) {
    const pendingSection = document.querySelector('[data-category="pending"]');
    const transportSection = document.querySelector('[data-category="transport"]');
    const historySection = document.querySelector('[data-category="history"]');

    if (!pendingSection || !transportSection || !historySection) {
        console.error('One or more booking sections not found');
        return;
    }

    clearSectionBookings(pendingSection);
    clearSectionBookings(transportSection);
    clearSectionBookings(historySection);

    const categorizedBookings = {
        pending: [],
        transport: [],
        history: []
    };

    bookings.forEach(booking => {
        const category = getBookingCategory(booking);
        categorizedBookings[category].push(booking);
    });

    renderSectionBookings(pendingSection, categorizedBookings.pending, {
        iconClass: 'fas fa-hourglass-half',
        title: 'No Pending Bookings',
        message: 'You have no pending transport bookings right now.'
    });

    renderSectionBookings(transportSection, categorizedBookings.transport, {
        iconClass: 'fas fa-car-side',
        title: 'No Active Transport Bookings',
        message: 'You have no active transport bookings at the moment.'
    });

    renderSectionBookings(historySection, categorizedBookings.history, {
        iconClass: 'fas fa-clock-rotate-left',
        title: 'No Booking History',
        message: 'Completed and cancelled transport bookings will appear here.'
    });
}

function clearSectionBookings(sectionElement) {
    const existingItems = sectionElement.querySelectorAll('.booking-item, .empty-state');
    existingItems.forEach(item => item.remove());
}

function getBookingCategory(booking) {
    const status = normalizeValue(booking.booking_status);
    const paymentStatus = normalizeValue(booking.payment_status);

    if (status === 'pending' || (status === 'confirmed' && paymentStatus !== 'paid')) {
        return 'pending';
    }

    if (status === 'cancelled' || status === 'completed' || status === 'rejected') {
        return 'history';
    }

    if (status === 'confirmed' && paymentStatus === 'paid') {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const returnDate = booking.return_date ? new Date(booking.return_date) : null;
        if (returnDate && !Number.isNaN(returnDate.getTime())) {
            returnDate.setHours(0, 0, 0, 0);
            if (returnDate < today) {
                return 'history';
            }
        }

        return 'transport';
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const returnDate = booking.return_date ? new Date(booking.return_date) : null;
    if (returnDate && !Number.isNaN(returnDate.getTime())) {
        returnDate.setHours(0, 0, 0, 0);
        if (returnDate < today) {
            return 'history';
        }
    }

    return 'transport';
}

function renderSectionBookings(sectionElement, bookings, emptyConfig) {
    if (!bookings || bookings.length === 0) {
        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.style.textAlign = 'center';
        emptyState.style.padding = '3em';
        emptyState.innerHTML = `
            <div style="font-size: 4em; margin-bottom: 0.5em;"><i class="${emptyConfig.iconClass}" aria-hidden="true"></i></div>
            <h3>${emptyConfig.title}</h3>
            <p>${emptyConfig.message}</p>
        `;
        sectionElement.appendChild(emptyState);
        return;
    }

    const cardsGrid = document.createElement('div');
    cardsGrid.className = 'booking-cards-grid';

    bookings.forEach(booking => {
        const bookingElement = createBookingElement(booking);
        cardsGrid.appendChild(bookingElement);
    });

    sectionElement.appendChild(cardsGrid);
}

// create booking element HTML
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
    const status = normalizeValue(booking.booking_status);
    const paymentStatus = normalizeValue(booking.payment_status);
    const existingRating = parseInt(booking.user_rating || 0, 10);
    const ratingBookingId = String(booking.booking_id || '');

    if (existingRating > 0 && !selectedTransportRatings[ratingBookingId]) {
        selectedTransportRatings[ratingBookingId] = existingRating;
    }

    const canPayNow = status === 'confirmed' && paymentStatus !== 'paid';
    const showRejectNotice = status === 'rejected';
    const statusLabel = canPayNow ? 'Approved - Awaiting Payment' : capitalizeFirst(status);
    const statusClass = canPayNow ? 'status-awaiting-payment' : `status-${booking.booking_status}`;
    const manageButton = booking.booking_status !== 'cancelled'
        ? `<button class="action-btn btn-secondary" onclick="cancelBooking('${booking.booking_id}')">Cancel</button>`
        : `<button class="action-btn btn-danger" onclick="deleteBooking('${booking.booking_id}')">Delete</button>`;

    const vehicleImage = buildVehicleImageUrl(booking.vehicle_photo || booking.main_image) || getVehicleImageFallbackByType(booking.vehicle_type);
    const imageFallback = getVehicleImageFallbackByType(booking.vehicle_type);

    const canRateTrip =
        getBookingCategory(booking) === 'history' &&
        !['cancelled', 'rejected'].includes(status) &&
        paymentStatus === 'paid';

    const editableStars = [1, 2, 3, 4, 5].map(value => `
        <button type="button" class="star-btn ${(selectedTransportRatings[ratingBookingId] || existingRating) >= value ? 'active' : ''}" onclick="setSelectedTransportRating('${ratingBookingId}', ${value})">★</button>
    `).join('');

    const readonlyStars = [1, 2, 3, 4, 5].map(value => `
        <span class="star-btn ${(existingRating) >= value ? 'active' : ''}" style="cursor: default;">★</span>
    `).join('');

    let ratingSection = '';
    if (canRateTrip) {
        if (existingRating > 0) {
            ratingSection = `
                <div class="booking-rating-box">
                    <div class="booking-rating-title">Your Transport Rating</div>
                    <div class="star-row">${readonlyStars}</div>
                    <div class="rating-meta">Rated ${existingRating}/5</div>
                    ${booking.user_review ? `<div class="rating-meta" style="margin-top:8px;">${booking.user_review}</div>` : ''}
                </div>
            `;
        } else {
            ratingSection = `
                <div class="booking-rating-box">
                    <div class="booking-rating-title">Rate this trip</div>
                    <div class="star-row" id="transport-stars-${ratingBookingId}">${editableStars}</div>
                    <textarea id="transport-review-${ratingBookingId}" class="rating-input" placeholder="Share your transport experience (optional)"></textarea>
                    <button class="rating-save" onclick="submitTransportBookingRating('${ratingBookingId}')">Submit Rating</button>
                </div>
            `;
        }
    }

    bookingDiv.innerHTML = `
        <div class="booking-image">
            <img src="${vehicleImage}" alt="${vehicleName}" onerror="this.onerror=null;this.src='${imageFallback}';">
        </div>
        <div class="booking-details">
            <h3 class="booking-title">${vehicleName}</h3>
            <div class="info-item"><i class="fas fa-car-side info-icon" aria-hidden="true"></i><span>${serviceType}</span></div>
            <div class="info-item"><i class="fas fa-location-dot info-icon" aria-hidden="true"></i><span>${booking.pickup_location} → ${booking.dropoff_location}</span></div>
            <div class="booking-info">
                <div class="info-item"><i class="fas fa-calendar-days info-icon" aria-hidden="true"></i><span>${pickupDate}</span></div>
                <div class="info-item"><i class="fas fa-calendar-days info-icon" aria-hidden="true"></i><span>${returnDate}</span></div>
                <div class="info-item"><i class="fas fa-users info-icon" aria-hidden="true"></i><span>${booking.passengers} Passenger${booking.passengers > 1 ? 's' : ''}</span></div>
                <div class="info-item"><i class="fas fa-sack-dollar info-icon" aria-hidden="true"></i><span class="booking-price">LKR ${parseFloat(booking.total_price).toLocaleString()}</span></div>
            </div>
            <div class="booking-status">
                <span class="status-badge ${statusClass}">${statusLabel}</span>
                ${showRejectNotice ? '<div class="booking-alert">This booking was rejected by the transporter.</div>' : ''}
            </div>
            <div class="booking-actions">
                <div class="action-row">
                    <button class="action-btn btn-primary" onclick="viewBookingDetails('${booking.booking_id}')">View Details</button>
                    ${manageButton}
                </div>
                ${canPayNow ? `<div class="action-row single"><button class="action-btn btn-success" onclick="proceedToPayment('${booking.booking_id}')">Complete Payment</button></div>` : ''}
            </div>
            ${ratingSection}
        </div>
    `;

    return bookingDiv;
}

function setSelectedTransportRating(bookingId, rating) {
    selectedTransportRatings[bookingId] = rating;
    const starsWrap = document.getElementById(`transport-stars-${bookingId}`);
    if (!starsWrap) return;

    const stars = starsWrap.querySelectorAll('.star-btn');
    stars.forEach((starBtn, index) => {
        if (index < rating) {
            starBtn.classList.add('active');
        } else {
            starBtn.classList.remove('active');
        }
    });
}

async function submitTransportBookingRating(bookingId) {
    const rating = parseInt(selectedTransportRatings[bookingId] || 0, 10);
    const reviewEl = document.getElementById(`transport-review-${bookingId}`);
    const review = reviewEl ? reviewEl.value.trim() : '';

    if (rating < 1 || rating > 5) {
        showErrorModal('Please select a rating between 1 and 5');
        return;
    }

    try {
        const response = await fetch(`${BASE_PATH}/api/transport-booking/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                bookingId,
                rating,
                review
            }),
            credentials: 'same-origin'
        });

        const result = await response.json();
        if (result.success) {
            loadBookings();
            return;
        }

        const errorMsg = result.errors?.general || 'Failed to save rating';
        showErrorModal(errorMsg);
    } catch (error) {
        console.error('submitTransportBookingRating error:', error);
        showErrorModal('Failed to save rating. Please try again.');
    }
}

// format service type
function formatServiceType(type) {
    const types = {
        'airport': 'Airport Transfer',
        'daily': 'Daily Rental',
        'tour': 'Tour Package',
        'custom': 'Custom Service'
    };
    return types[type] || type;
}

// capitalize first letter
function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// show empty state
function showEmptyState() {
    displayBookings([]);
}

// update booking statistics
function updateBookingStats() {
    const stats = allBookings.reduce((acc, booking) => {
        acc.total++;
        if (booking.booking_status === 'confirmed' && String(booking.payment_status || '').toLowerCase() === 'paid') acc.confirmed++;
        if (booking.booking_status === 'pending') acc.pending++;
        if (String(booking.payment_status || '').toLowerCase() === 'paid') {
            acc.totalSpent += parseFloat(booking.total_price || 0);
        }
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

function proceedToPayment(bookingId) {
    window.location.href = `${BASE_PATH}/transport-booking-details?booking_id=${encodeURIComponent(bookingId)}`;
}
// view booking details
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
            <strong>Alternative Contact:</strong> ${booking.alternative_contact || 'None'}
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

// cancel booking
function cancelBooking(bookingId) {
    bookingIdToCancel = bookingId;
    const modal = document.getElementById('cancelConfirmModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// close cancel confirmation modal
function closeConfirmModal() {
    const modal = document.getElementById('cancelConfirmModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
        bookingIdToCancel = null;
    }, 300);
}

// proceed with cancel
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

// show cancel success modal
function showCancelSuccessModal() {
    const modal = document.getElementById('cancelSuccessModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// close cancel success modal
function closeCancelSuccessModal() {
    const modal = document.getElementById('cancelSuccessModal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 300);
}

// delete booking
function deleteBooking(bookingId) {
    bookingIdToDelete = bookingId;
    const modal = document.getElementById('deleteConfirmModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// close delete confirmation modal
function closeDeleteConfirmModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
        bookingIdToDelete = null;
    }, 300);
}

// proceed with delete
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

// show delete success modal
function showDeleteSuccessModal() {
    const modal = document.getElementById('deleteSuccessModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// close delete success modal
function closeDeleteSuccessModal() {
    const modal = document.getElementById('deleteSuccessModal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 300);
}

// show error modal
function showErrorModal(message) {
    const modal = document.getElementById('errorModal');
    const messageEl = document.getElementById('errorModalMessage');
    if (messageEl) messageEl.textContent = message;
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

// close error modal
function closeErrorModal() {
    const modal = document.getElementById('errorModal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 300);
}

// close modal handlers
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// close booking details modal
function closeBookingDetailsModal() {
    const modal = document.getElementById('bookingDetailsModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// close modals on outside click
window.onclick = function(event) {
    const modal = document.getElementById('bookingDetailsModal');
    if (event.target === modal) {
        closeBookingDetailsModal();
    }
}

// setup modal close buttons
document.addEventListener('DOMContentLoaded', function() {
    // close button for booking details modal
    const detailsModal = document.getElementById('bookingDetailsModal');
    if (detailsModal) {
        const closeBtn = detailsModal.querySelector('.close');
        if (closeBtn) {
            closeBtn.onclick = closeBookingDetailsModal;
        }
    }
});