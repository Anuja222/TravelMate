// load booking data on page load
document.addEventListener('DOMContentLoaded', function () {
    loadBookingData();
    initializeChangeSearch();
    updateProgressBar();
});

// load booking data from localStorage
function loadBookingData() {
    const bookingData = JSON.parse(localStorage.getItem('currentBooking'));

    if (!bookingData) {
        // redirect back if no booking data
        window.location.href = 'accommodationdetail.php';
        return;
    }

    // update display with booking data
    updateBookingDisplay(bookingData);
    updateAvailabilityTable(bookingData);
}

// update booking display
function updateBookingDisplay(bookingData) {
    const checkin = new Date(bookingData.checkinDate);
    const checkout = new Date(bookingData.checkoutDate);

    const dateDisplay = `${formatDate(checkin)} — ${formatDate(checkout)}`;
    const guestDisplay = `${bookingData.adults} adults · ${bookingData.children} children · 1 room`;

    // update date and guest displays
    document.querySelector('.date-picker input').value = dateDisplay;
    document.querySelector('.guest-room-picker input').value = guestDisplay;
}

// format date for display
function formatDate(date) {
    const options = { weekday: 'short', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// update availability table with booking data
function updateAvailabilityTable(bookingData) {
    const roomLink = document.querySelector('.room-link');
    const oldPrice = document.querySelector('.old-price');
    const currentPrice = document.querySelector('.current-price');

    roomLink.textContent = bookingData.roomName;

    // calculate prices with potential discount
    const originalPrice = bookingData.totalPrice * 1.3; // show 30% discount
    oldPrice.textContent = `LKR ${Math.round(originalPrice).toLocaleString()}`;
    currentPrice.textContent = `LKR ${Math.round(bookingData.totalPrice).toLocaleString()}`;
}

// initialize change search functionality
function initializeChangeSearch() {
    const changeSearchBtn = document.querySelector('.change-search-btn');

    changeSearchBtn.addEventListener('click', function () {
        showChangeSearchModal();
    });
}

// show change search modal
function showChangeSearchModal() {
    const modal = createSearchModal();
    document.body.appendChild(modal);

    // load current booking data into modal
    const bookingData = JSON.parse(localStorage.getItem('currentBooking'));
    if (bookingData) {
        document.getElementById('modalCheckin').value = bookingData.checkinDate;
        document.getElementById('modalCheckout').value = bookingData.checkoutDate;
        document.getElementById('modalAdults').value = bookingData.adults;
        document.getElementById('modalChildren').value = bookingData.children;
    }
}

// create search modal
function createSearchModal() {
    const modal = document.createElement('div');
    modal.className = 'search-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Change Search</h3>
                <button class="close-modal" onclick="closeSearchModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Check-in Date</label>
                        <input type="date" id="modalCheckin" min="${new Date().toISOString().split('T')[0]}">
                    </div>
                    <div class="form-group">
                        <label>Check-out Date</label>
                        <input type="date" id="modalCheckout" min="${new Date().toISOString().split('T')[0]}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Adults</label>
                        <select id="modalAdults" disabled>
                            <option value="1">1 Adult</option>
                            <option value="2">2 Adults</option>
                            <option value="3">3 Adults</option>
                            <option value="4">4+ Adults</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Children</label>
                        <select id="modalChildren" disabled>
                            <option value="0">0 Children</option>
                            <option value="1">1 Child</option>
                            <option value="2">2 Children</option>
                            <option value="3">3+ Children</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeSearchModal()">Cancel</button>
                <button class="btn-primary" onclick="updateSearch()">Update Search</button>
            </div>
        </div>
    `;
    return modal;
}

// close search modal
function closeSearchModal() {
    const modal = document.querySelector('.search-modal');
    if (modal) {
        modal.remove();
    }
}

// update search with new values
function updateSearch() {
    const checkinDate = document.getElementById('modalCheckin').value;
    const checkoutDate = document.getElementById('modalCheckout').value;
    const adults = document.getElementById('modalAdults').value;
    const children = document.getElementById('modalChildren').value;

    if (!checkinDate || !checkoutDate) {
        alert('Please select dates');
        return;
    }

    const checkin = new Date(checkinDate);
    const checkout = new Date(checkoutDate);
    const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));

    if (nights <= 0) {
        alert('Please select valid dates');
        return;
    }

    // get current booking data and update
    const bookingData = JSON.parse(localStorage.getItem('currentBooking'));

    // recalculate prices
    const basePrice = bookingData.roomPrice * nights;
    const taxes = basePrice * 0.15;
    const totalPrice = basePrice + taxes;

    // update booking data
    bookingData.checkinDate = checkinDate;
    bookingData.checkoutDate = checkoutDate;
    bookingData.nights = nights;
    bookingData.adults = adults;
    bookingData.children = children;
    bookingData.basePrice = basePrice;
    bookingData.taxes = taxes;
    bookingData.totalPrice = totalPrice;

    // save updated data
    localStorage.setItem('currentBooking', JSON.stringify(bookingData));

    // refresh display
    updateBookingDisplay(bookingData);
    updateAvailabilityTable(bookingData);

    // close modal
    closeSearchModal();
}

// update progress bar
function updateProgressBar() {
    const steps = document.querySelectorAll('.booking-progress .step');
    steps[0].classList.add('active');
}

// handle reserve button click
document.addEventListener('DOMContentLoaded', function () {
    const reserveBtn = document.querySelector('.reserve-btn');
    if (reserveBtn) {
        reserveBtn.addEventListener('click', function () {
            // save final booking data
            const bookingData = JSON.parse(localStorage.getItem('currentBooking'));
            bookingData.status = 'confirmed';
            bookingData.bookingDate = new Date().toISOString();

            // generate booking ID
            bookingData.bookingId = 'BK' + Date.now();

            // save to localStorage
            localStorage.setItem('confirmedBooking', JSON.stringify(bookingData));

            // add to booking history
            let bookingHistory = JSON.parse(localStorage.getItem('bookingHistory')) || [];
            bookingHistory.push(bookingData);
            localStorage.setItem('bookingHistory', JSON.stringify(bookingHistory));

            // redirect to next step
            window.location.href = 'booking_details';
        });
    }
});