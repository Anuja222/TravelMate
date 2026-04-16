const BASE_PATH = window.location.pathname.includes('/TravelMate/') 
    ? '/TravelMate/public' 
    : '';

// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    loadUserBookings();
    setupFilterTabs();
    setupSearchFunctionality();
});

const selectedRatings = {};

function buildAccommodationImageUrl(imagePath) {
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

function getAccommodationFallbackImage() {
    return `${BASE_PATH}/assets/images/no-image.jpg`;
}

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
    const historySection = document.querySelector('[data-category="history"]');
    const transportSection = document.querySelector('[data-category="transport"]');
    
    // Clear existing content
    const existingHotelItems = hotelsSection.querySelectorAll('.booking-item, .booking-cards-grid');
    const existingHistoryItems = historySection ? historySection.querySelectorAll('.booking-item, .booking-cards-grid') : [];
    const existingTransportItems = transportSection?.querySelectorAll('.booking-item, .booking-cards-grid');
    existingHotelItems.forEach(item => item.remove());
    existingHistoryItems?.forEach(item => item.remove());
    existingTransportItems?.forEach(item => item.remove());

    const previousEmpty = document.querySelectorAll('.history-empty-state, .current-empty-state');
    previousEmpty.forEach(node => node.remove());

    if (bookings.length === 0) {
        showEmptyState();
        return;
    }

    const activeBookings = [];
    const historyBookings = [];

    bookings.forEach(booking => {
        if (isExpiredBooking(booking)) {
            historyBookings.push(booking);
        } else {
            activeBookings.push(booking);
        }
    });

    renderSectionBookings(hotelsSection, activeBookings, false);

    if (activeBookings.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'empty-state current-empty-state';
        empty.innerHTML = `
            <div class="empty-state-icon"><i class="fas fa-calendar-day" aria-hidden="true"></i></div>
            <h3>No Current Bookings</h3>
            <p>You have no active bookings right now.</p>
        `;
        hotelsSection.appendChild(empty);
    }

    if (historySection) {
        renderSectionBookings(historySection, historyBookings, true);

        if (historyBookings.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'empty-state history-empty-state';
            empty.innerHTML = `
                <div class="empty-state-icon"><i class="fas fa-clock-rotate-left" aria-hidden="true"></i></div>
                <h3>No Booking History Yet</h3>
                <p>Expired bookings will appear here once your travel dates pass.</p>
            `;
            historySection.appendChild(empty);
        }
    }
}

function isExpiredBooking(booking) {
    if (!booking || !booking.checkout_date) return false;
    const checkoutDate = new Date(booking.checkout_date);
    const today = new Date();
    checkoutDate.setHours(0, 0, 0, 0);
    today.setHours(0, 0, 0, 0);
    return checkoutDate < today;
}

function renderSectionBookings(sectionElement, bookings, isHistory = false) {
    if (!sectionElement || !bookings || bookings.length === 0) {
        return;
    }

    const cardsGrid = document.createElement('div');
    cardsGrid.className = 'booking-cards-grid';

    bookings.forEach(booking => {
        const bookingElement = createBookingElement(booking, isHistory);
        cardsGrid.appendChild(bookingElement);
    });

    sectionElement.appendChild(cardsGrid);
}

// Create booking element HTML
function createBookingElement(booking, isHistory = false) {
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

    const existingRating = parseInt(booking.user_rating || 0, 10);
    const ratingBookingId = String(booking.booking_id || '');
    if (existingRating > 0 && !selectedRatings[ratingBookingId]) {
        selectedRatings[ratingBookingId] = existingRating;
    }

    const editableStars = [1, 2, 3, 4, 5].map(value => `
        <button type="button" class="star-btn ${(selectedRatings[ratingBookingId] || existingRating) >= value ? 'active' : ''}" onclick="setSelectedRating('${ratingBookingId}', ${value})">★</button>
    `).join('');

    const readonlyStars = [1, 2, 3, 4, 5].map(value => `
        <span class="star-btn ${(existingRating) >= value ? 'active' : ''}" style="cursor: default;">★</span>
    `).join('');

    let ratingSection = '';
    if (isHistory && booking.booking_status !== 'cancelled') {
        if (existingRating > 0) {
            ratingSection = `
                <div class="booking-rating-box">
                    <div class="booking-rating-title">Your Rating</div>
                    <div class="star-row">${readonlyStars}</div>
                    <div class="rating-meta">Rated ${existingRating}/5</div>
                    ${booking.user_review ? `<div class="rating-meta" style="margin-top:8px;">${booking.user_review}</div>` : ''}
                </div>
            `;
        } else {
            ratingSection = `
                <div class="booking-rating-box">
                    <div class="booking-rating-title">Rate this stay</div>
                    <div class="star-row" id="stars-${ratingBookingId}">${editableStars}</div>
                    <textarea id="review-${ratingBookingId}" class="rating-input" placeholder="Share your experience (optional)"></textarea>
                    <button class="rating-save" onclick="submitBookingRating('${ratingBookingId}')">Submit Rating</button>
                </div>
            `;
        }
    }

    const actionButtons = isHistory
        ? `<button class="action-btn btn-primary" onclick="viewBookingDetails('${booking.booking_id}')">View Details</button>
           ${booking.booking_status === 'cancelled' ? `<button class="action-btn btn-danger" onclick="deleteBooking('${booking.booking_id}')">Delete</button>` : ''}`
        : `<button class="action-btn btn-primary" onclick="viewBookingDetails('${booking.booking_id}')">View Details</button>
           ${booking.booking_status !== 'cancelled' ? `<button class="action-btn btn-secondary" onclick="cancelBooking('${booking.booking_id}')">Cancel</button>` : ''}
           ${booking.booking_status === 'cancelled' ? `<button class="action-btn btn-danger" onclick="deleteBooking('${booking.booking_id}')">Delete</button>` : ''}`;

    const accommodationImage = buildAccommodationImageUrl(booking.accommodation_photo) || getAccommodationFallbackImage();
    const imageFallback = getAccommodationFallbackImage();

    bookingDiv.innerHTML = `
        <div class="booking-image">
            <img src="${accommodationImage}" alt="${booking.room_name}" onerror="this.onerror=null;this.src='${imageFallback}';">
        </div>
        <div class="booking-details">
            <h3 class="booking-title">${booking.room_name}</h3>
            <div class="booking-info">
                <div class="info-item">
                    <i class="fas fa-calendar-days info-icon" aria-hidden="true"></i>
                    <span>Check-in: ${checkinDate}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar-check info-icon" aria-hidden="true"></i>
                    <span>Check-out: ${checkoutDate}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-users info-icon" aria-hidden="true"></i>
                    <span>${booking.adults} Adult${booking.adults > 1 ? 's' : ''}${booking.children > 0 ? ', ' + booking.children + ' Child' + (booking.children > 1 ? 'ren' : '') : ''}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-bed info-icon" aria-hidden="true"></i>
                    <span>${booking.nights} Night${booking.nights > 1 ? 's' : ''}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-wallet info-icon" aria-hidden="true"></i>
                    <span class="booking-price">LKR ${parseFloat(booking.total_price).toLocaleString()}</span>
                </div>
            </div>
            <div class="booking-status">
                <span class="status-badge status-${booking.booking_status}">${capitalizeFirst(booking.booking_status)}</span>
            </div>
            <div class="booking-actions">
                <div class="action-row">
                    ${actionButtons}
                </div>
            </div>
            ${ratingSection}
        </div>
    `;

    return bookingDiv;
}

function setSelectedRating(bookingId, rating) {
    selectedRatings[bookingId] = rating;
    const starsWrap = document.getElementById(`stars-${bookingId}`);
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

async function submitBookingRating(bookingId) {
    const rating = parseInt(selectedRatings[bookingId] || 0, 10);
    const reviewEl = document.getElementById(`review-${bookingId}`);
    const review = reviewEl ? reviewEl.value.trim() : '';

    if (rating < 1 || rating > 5) {
        showErrorModal('Please select a rating between 1 and 5');
        return;
    }

    try {
        const response = await fetch(`${BASE_PATH}/api/booking/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                bookingId,
                rating,
                review
            })
        });

        const result = await response.json();
        if (result.success) {
            loadUserBookings();
            return;
        }

        const errorMsg = result.errors?.general || 'Failed to save rating';
        showErrorModal(errorMsg);
    } catch (error) {
        console.error('submitBookingRating error:', error);
        showErrorModal('Failed to save rating. Please try again.');
    }
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
    const sections = [
        document.querySelector('[data-category="hotels"]'),
        document.querySelector('[data-category="history"]')
    ].filter(Boolean);

    sections.forEach(section => {
        const oldFiltered = section.querySelector('.filtered-empty-state');
        if (oldFiltered) oldFiltered.remove();

        const items = section.querySelectorAll('.booking-item');
        let visibleCount = 0;

        items.forEach(item => {
            const itemStatus = item.getAttribute('data-status');
            const visible = status === 'all' || itemStatus === status;
            item.style.display = visible ? '' : 'none';
            if (visible) visibleCount += 1;
        });

        if (items.length > 0 && visibleCount === 0) {
            const emptyState = document.createElement('div');
            emptyState.className = 'empty-state filtered-empty-state';
            emptyState.innerHTML = `
                <div class="empty-state-icon"><i class="fas fa-inbox" aria-hidden="true"></i></div>
                <h3>No ${status === 'all' ? '' : capitalizeFirst(status)} Bookings</h3>
                <p>You don't have any ${status === 'all' ? '' : status} bookings in this section.</p>
            `;
            section.appendChild(emptyState);
        }
    });
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
        const existingItems = hotelsSection.querySelectorAll('.booking-item, .booking-cards-grid');
        existingItems.forEach(item => item.remove());

        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.innerHTML = `
            <div class="empty-state-icon"><i class="fas fa-inbox" aria-hidden="true"></i></div>
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