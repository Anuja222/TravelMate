// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    checkBookingData();
    loadUserDetails();
    setupFormSubmission();
});

// Check if booking data exists
function checkBookingData() {
    const bookingData = JSON.parse(window.localStorage.getItem('currentBooking'));
    
    if (!bookingData || bookingData.bookingStep < 2) {
        alert('No booking found. Please start from the availability page.');
        window.location.href = 'booking_availability';
        return;
    }
}

// Load user details if available
function loadUserDetails() {
    const userDetails = JSON.parse(window.localStorage.getItem('userDetails'));
    
    if (userDetails) {
        // Populate form with saved details
        if (userDetails.firstName) document.getElementById('first_name').value = userDetails.firstName;
        if (userDetails.lastName) document.getElementById('last_name').value = userDetails.lastName;
        if (userDetails.email) document.getElementById('email').value = userDetails.email;
        if (userDetails.address) document.getElementById('address').value = userDetails.address;
        if (userDetails.city) document.getElementById('city').value = userDetails.city;
        if (userDetails.zip) document.getElementById('zip').value = userDetails.zip;
        if (userDetails.country) document.getElementById('country').value = userDetails.country;
        if (userDetails.phoneCode) document.getElementById('phone_code').value = userDetails.phoneCode;
        if (userDetails.phone) document.getElementById('phone').value = userDetails.phone;
    }
}

// Setup form submission
function setupFormSubmission() {
    const form = document.getElementById('detailsForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            saveUserDetails();
        }
    });
}

// Validate form
function validateForm() {
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const address = document.getElementById('address').value.trim();
    const city = document.getElementById('city').value.trim();
    const phone = document.getElementById('phone').value.trim();
    
    if (!firstName || !lastName) {
        alert('Please enter your first and last name');
        return false;
    }
    
    if (!email || !isValidEmail(email)) {
        alert('Please enter a valid email address');
        return false;
    }
    
    if (!address) {
        alert('Please enter your address');
        return false;
    }
    
    if (!city) {
        alert('Please enter your city');
        return false;
    }
    
    if (!phone || phone.length < 9) {
        alert('Please enter a valid phone number');
        return false;
    }
    
    return true;
}

// Validate email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Save user details to localStorage
function saveUserDetails() {
    const userDetails = {
        firstName: document.getElementById('first_name').value.trim(),
        lastName: document.getElementById('last_name').value.trim(),
        email: document.getElementById('email').value.trim(),
        address: document.getElementById('address').value.trim(),
        city: document.getElementById('city').value.trim(),
        zip: document.getElementById('zip').value.trim(),
        country: document.getElementById('country').value,
        phoneCode: document.getElementById('phone_code').value,
        phone: document.getElementById('phone').value.trim(),
        completedAt: new Date().toISOString()
    };
    
    // Save user details
    window.localStorage.setItem('userDetails', JSON.stringify(userDetails));
    
    // Update booking data
    const bookingData = JSON.parse(window.localStorage.getItem('currentBooking'));
    bookingData.bookingStep = 3;
    bookingData.userDetails = userDetails;
    bookingData.detailsCompletedAt = new Date().toISOString();
    
    window.localStorage.setItem('currentBooking', JSON.stringify(bookingData));
    
    // Navigate to payment page
    window.location.href = 'booking_payment';
}