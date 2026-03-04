// Provider Bookings Page - Fetch and display bookings for accommodation provider

// Get base URL helper
function getBaseUrl() {
    return window.location.origin + '/TravelMate/public';
}

// Format date to readable format
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { weekday: 'short', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// Format property type
function formatPropertyType(type) {
    if (!type) return 'Property';
    return type.charAt(0).toUpperCase() + type.slice(1).replace(/_/g, ' ');
}

// Calculate number of nights
function calculateNights(checkin, checkout) {
    const checkinDate = new Date(checkin);
    const checkoutDate = new Date(checkout);
    const diffTime = Math.abs(checkoutDate - checkinDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
}

// Load bookings from API
async function loadBookings() {
    const currentContainer = document.getElementById('currentBookingsGrid') || document.querySelector('.bookings-grid');
    const expiredContainer = document.getElementById('expiredBookingsGrid');
    
    // Show loading state
    if (currentContainer) {
        currentContainer.innerHTML = '<div class="loading-message"><i class="fas fa-spinner fa-spin"></i><p>Loading bookings...</p></div>';
    }
    if (expiredContainer) {
        expiredContainer.innerHTML = '<div class="loading-message"><i class="fas fa-spinner fa-spin"></i><p>Loading bookings...</p></div>';
    }
    
    try {
        const response = await fetch(`${getBaseUrl()}/api/accommodation/providerBookings`);
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.error('Response is not JSON:', await response.text());
            throw new Error('Server returned invalid response');
        }
        
        const result = await response.json();
        
        if (result.success && result.data.bookings) {
            checkForNewBookings(result.data.bookings);
            displayBookings(result.data.bookings);
        } else {
            const message = result.errors && result.errors.length > 0 ? result.errors[0] : 'No bookings found.';
            if (currentContainer) {
                currentContainer.innerHTML = `<div class="empty-state"><i class="fas fa-calendar-times"></i><p>${message}</p></div>`;
            }
            if (expiredContainer) {
                expiredContainer.innerHTML = '<div class="empty-state"><i class="fas fa-history"></i><p>No expired bookings found.</p></div>';
            }
        }
    } catch (error) {
        console.error('Error loading bookings:', error);
        if (currentContainer) {
            currentContainer.innerHTML = '<div class="error-state"><i class="fas fa-exclamation-circle"></i><p>Error loading bookings. Please make sure you are logged in as an accommodation provider.</p></div>';
        }
        if (expiredContainer) {
            expiredContainer.innerHTML = '';
        }
    }
}

// Store bookings data globally for modal access
let bookingsData = [];

// Check for new bookings and show notification
function checkForNewBookings(bookings) {
    // Get last visit timestamp from localStorage
    const lastVisit = localStorage.getItem('lastBookingsVisit');
    const currentTime = new Date().getTime();
    
    if (lastVisit) {
        // Filter bookings created after last visit
        const newBookings = bookings.filter(booking => {
            const bookingTime = new Date(booking.created_at).getTime();
            return bookingTime > parseInt(lastVisit);
        });
        
        // Show notification if there are new bookings
        if (newBookings.length > 0) {
            showNewBookingNotification(newBookings.length);
        }
    }
    
    // Update last visit timestamp
    localStorage.setItem('lastBookingsVisit', currentTime);
}

// Show notification banner
function showNewBookingNotification(count) {
    const notification = document.getElementById('newBookingNotification');
    const message = document.getElementById('notificationMessage');
    
    if (notification && message) {
        message.textContent = `You have ${count} new booking${count > 1 ? 's' : ''} placed while you were away!`;
        notification.style.display = 'flex';
        
        // Auto-dismiss after 10 seconds
        setTimeout(() => {
            dismissNotification();
        }, 10000);
    }
}

// Dismiss notification
function dismissNotification() {
    const notification = document.getElementById('newBookingNotification');
    if (notification) {
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.style.display = 'none';
            notification.style.opacity = '1';
        }, 300);
    }
}

// Display bookings in the container
function displayBookings(bookings) {
    const currentContainer = document.getElementById('currentBookingsGrid') || document.querySelector('.bookings-grid');
    const expiredContainer = document.getElementById('expiredBookingsGrid');
    
    // Store bookings data
    bookingsData = bookings;

    const currentBookings = [];
    const expiredBookings = [];

    bookings.forEach(booking => {
        if (isExpiredBooking(booking)) {
            expiredBookings.push(booking);
        } else {
            currentBookings.push(booking);
        }
    });
    
    if (!currentContainer) {
        return;
    }

    renderBookingsInto(currentContainer, currentBookings, '<div class="empty-state"><i class="fas fa-calendar-times"></i><p>No current bookings found.</p></div>');

    if (expiredContainer) {
        renderBookingsInto(expiredContainer, expiredBookings, '<div class="empty-state"><i class="fas fa-history"></i><p>No expired bookings found.</p></div>');
    }
}

function isExpiredBooking(booking) {
    if (!booking || !booking.checkout_date) {
        return false;
    }

    const checkoutDate = new Date(booking.checkout_date);
    checkoutDate.setHours(0, 0, 0, 0);

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    return checkoutDate < today;
}

function renderBookingsInto(container, bookings, emptyHtml) {
    if (!container) return;

    if (!Array.isArray(bookings) || bookings.length === 0) {
        container.innerHTML = emptyHtml;
        return;
    }
    
    let bookingsHTML = '';
    
    bookings.forEach(booking => {
        const nights = calculateNights(booking.checkin_date, booking.checkout_date);
        const guests = parseInt(booking.adults || 1) + parseInt(booking.children || 0);
        
        // Use accommodation image if available, otherwise use default
        const imageUrl = booking.accommodation_image 
            ? `${getBaseUrl()}/${booking.accommodation_image}` 
            : `${getBaseUrl()}/assets/images/default-property.jpg`;
        
        const statusClass = booking.booking_status.toLowerCase();
        
        bookingsHTML += `
            <div class="booking-card">
                <div class="booking-image">
                    <img src="${imageUrl}" alt="${booking.accommodation_name}"
                         onerror="this.src='${getBaseUrl()}/assets/images/default-property.jpg'">
                    <div class="booking-badge">${formatPropertyType(booking.property_type)}</div>
                    <div class="booking-status-badge ${statusClass}">${booking.booking_status.toUpperCase()}</div>
                </div>
                <div class="booking-content">
                    <div class="booking-info-row">
                        <h3 class="booking-title">${booking.accommodation_name || 'Accommodation'}</h3>
                        <div class="booking-location">
                            <i class="fas fa-user"></i>
                            <span>${booking.customer_name || 'Guest'}</span>
                        </div>
                        <div class="booking-description">
                            ${formatDate(booking.checkin_date)} - ${formatDate(booking.checkout_date)} • ${nights} night${nights > 1 ? 's' : ''} • ${guests} guest${guests > 1 ? 's' : ''}
                        </div>
                    </div>
                    
                    <div class="booking-footer">
                        <div class="booking-price">
                            <div class="price-amount">
                                <span class="currency">LKR</span>
                                <span>${parseFloat(booking.total_price || 0).toLocaleString()}</span>
                            </div>
                            <span class="price-label">Total Amount</span>
                        </div>
                        <div class="booking-actions">
                            <button class="booking-btn booking-btn-contact" onclick="contactCustomer('${booking.customer_phone || ''}', '${booking.customer_email || ''}', '${booking.customer_name || 'Guest'}')">
                                Contact
                            </button>
                            <button class="booking-btn booking-btn-details" onclick="viewBookingDetails('${booking.booking_id}')">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = bookingsHTML;
}

// View booking details in modal
function viewBookingDetails(bookingId) {
    // Find the booking data
    const booking = bookingsData.find(b => b.booking_id == bookingId);
    
    if (!booking) {
        alert('Booking not found');
        return;
    }
    
    const nights = calculateNights(booking.checkin_date, booking.checkout_date);
    const guests = parseInt(booking.adults || 1) + parseInt(booking.children || 0);
    
    // Build modal content
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <div class="modal-booking-header">
            <div class="modal-booking-id">
                <span class="label">Booking ID:</span>
                <span class="value">#${booking.booking_id}</span>
            </div>
            <div class="modal-booking-status ${booking.booking_status.toLowerCase()}">
                ${booking.booking_status.toUpperCase()}
            </div>
        </div>
        
        <div class="modal-section">
            <h3><i class="fas fa-hotel"></i> Accommodation Details</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Property Name</span>
                    <span class="detail-value">${booking.accommodation_name || 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Property Type</span>
                    <span class="detail-value">${formatPropertyType(booking.property_type)}</span>
                </div>
            </div>
        </div>
        
        <div class="modal-section">
            <h3><i class="fas fa-user"></i> Guest Information</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Guest Name</span>
                    <span class="detail-value">${booking.customer_name || 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">${booking.customer_email || 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">${booking.customer_phone || 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Number of Guests</span>
                    <span class="detail-value">${guests} guest${guests > 1 ? 's' : ''} (${booking.adults || 1} adult${booking.adults > 1 ? 's' : ''}, ${booking.children || 0} child${booking.children > 1 ? 'ren' : ''})</span>
                </div>
            </div>
        </div>
        
        <div class="modal-section">
            <h3><i class="fas fa-calendar-alt"></i> Booking Dates</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Check-in</span>
                    <span class="detail-value">${formatDate(booking.checkin_date)}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Check-out</span>
                    <span class="detail-value">${formatDate(booking.checkout_date)}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value">${nights} night${nights > 1 ? 's' : ''}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Booked On</span>
                    <span class="detail-value">${booking.created_at ? new Date(booking.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</span>
                </div>
            </div>
        </div>
        
        <div class="modal-section">
            <h3><i class="fas fa-money-bill-wave"></i> Payment Details</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Total Amount</span>
                    <span class="detail-value price">LKR ${parseFloat(booking.total_price || 0).toLocaleString()}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Status</span>
                    <span class="detail-value">${booking.payment_status || 'Pending'}</span>
                </div>
            </div>
        </div>
        
        ${booking.special_requests ? `
        <div class="modal-section">
            <h3><i class="fas fa-comment"></i> Special Requests</h3>
            <p class="special-requests">${booking.special_requests}</p>
        </div>
        ` : ''}
        
        <div class="modal-actions">
            <button class="modal-btn modal-btn-contact" onclick="contactCustomer('${booking.customer_phone || ''}', '${booking.customer_email || ''}', '${booking.customer_name || 'Guest'}')">
                <i class="fas fa-phone"></i> Contact Guest
            </button>
            <button class="modal-btn modal-btn-close" onclick="closeModal()">
                Close
            </button>
        </div>
    `;
    
    // Show modal
    document.getElementById('bookingModal').style.display = 'block';
}

// Close modal
function closeModal() {
    document.getElementById('bookingModal').style.display = 'none';
}

// Contact customer - Show contact options in modal
function contactCustomer(phone, email, guestName) {
    const contactModalBody = document.getElementById('contactModalBody');
    
    let contactOptions = `
        <div class="contact-info-section">
            <div class="contact-guest-name">
                <i class="fas fa-user-circle"></i>
                <span>${guestName || 'Guest'}</span>
            </div>
            <p class="contact-description">Guest contact information:</p>
        </div>
        <div class="contact-methods">
    `;
    
    // Add phone option if available
    if (phone && phone !== '') {
        contactOptions += `
            <div class="contact-method-card">
                <div class="contact-method-icon phone">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div class="contact-method-info">
                    <h3>Phone</h3>
                    <p>${phone}</p>
                </div>
            </div>
        `;
        
        // Add WhatsApp option
        contactOptions += `
            <div class="contact-method-card">
                <div class="contact-method-icon whatsapp">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div class="contact-method-info">
                    <h3>WhatsApp</h3>
                    <p>${phone}</p>
                </div>
            </div>
        `;
    }
    
    // Add email option if available
    if (email && email !== '') {
        contactOptions += `
            <div class="contact-method-card">
                <div class="contact-method-icon email">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="contact-method-info">
                    <h3>Email</h3>
                    <p>${email}</p>
                </div>
            </div>
        `;
    }
    
    // If no contact information available
    if ((!phone || phone === '') && (!email || email === '')) {
        contactOptions += `
            <div class="no-contact-info">
                <i class="fas fa-exclamation-circle"></i>
                <p>No contact information available for this guest.</p>
            </div>
        `;
    }
    
    contactOptions += `
        </div>
        <div class="modal-actions">
            <button class="modal-btn modal-btn-close" onclick="closeContactModal()">
                Close
            </button>
        </div>
    `;
    
    contactModalBody.innerHTML = contactOptions;
    
    // Show contact modal
    document.getElementById('contactModal').style.display = 'block';
}

// Close contact modal
function closeContactModal() {
    document.getElementById('contactModal').style.display = 'none';
}

// Initialize page when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadBookings();
    
    // Modal close functionality
    const modal = document.getElementById('bookingModal');
    const contactModal = document.getElementById('contactModal');
    const closeBtn = document.querySelector('.modal-close');
    
    // Close modal when clicking X
    if (closeBtn) {
        closeBtn.onclick = function() {
            closeModal();
        };
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
        if (event.target == contactModal) {
            closeContactModal();
        }
    };
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (modal.style.display === 'block') {
                closeModal();
            }
            if (contactModal.style.display === 'block') {
                closeContactModal();
            }
        }
    });
});
