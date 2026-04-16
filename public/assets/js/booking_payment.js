// initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    checkBookingData();
    loadBillingDetails();
    setupFormFormatting();
    setupFormSubmission();
});

// check if booking data exists and user completed step 2
function checkBookingData() {
    const bookingData = JSON.parse(window.localStorage.getItem('currentBooking'));
    
    if (!bookingData || bookingData.bookingStep < 3) {
        alert('Please complete the previous steps first.');
        window.location.href = 'booking_details';
        return;
    }
}

// load billing details from user details
function loadBillingDetails() {
    const userDetails = JSON.parse(window.localStorage.getItem('userDetails'));
    
    if (userDetails) {
        // pre-fill billing address with user address
        if (userDetails.address) document.getElementById('billing_address').value = userDetails.address;
        if (userDetails.city) document.getElementById('billing_city').value = userDetails.city;
        if (userDetails.zip) document.getElementById('billing_zip').value = userDetails.zip;
        if (userDetails.country) document.getElementById('billing_country').value = userDetails.country;
    }
}

// setup form formatting
function setupFormFormatting() {
    // format card number with spaces
    document.getElementById('card_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });
    
    // format expiry date
    document.getElementById('expiry_date').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });
    
    // allow only numbers for CVV
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    
    // allow only numbers for card number
    document.getElementById('card_number').addEventListener('keypress', function(e) {
        if (!/\d/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
            e.preventDefault();
        }
    });
}

// setup form submission
function setupFormSubmission() {
    const form = document.getElementById('paymentForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validatePaymentForm()) {
            savePaymentDetails();
        }
    });
}

// validate payment form
function validatePaymentForm() {
    const cardName = document.getElementById('card_name').value.trim();
    const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
    const expiryDate = document.getElementById('expiry_date').value;
    const cvv = document.getElementById('cvv').value;
    const billingAddress = document.getElementById('billing_address').value.trim();
    const billingCity = document.getElementById('billing_city').value.trim();
    const billingZip = document.getElementById('billing_zip').value.trim();
    
    // validate cardholder name
    if (!cardName || cardName.length < 3) {
        alert('Please enter a valid cardholder name');
        return false;
    }
    
    // validate card number (13-19 digits)
    if (cardNumber.length < 13 || cardNumber.length > 19) {
        alert('Please enter a valid card number');
        return false;
    }
    
    // validate expiry date format
    if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
        alert('Please enter a valid expiry date (MM/YY)');
        return false;
    }
    
    // validate expiry date is not in the past
    const [month, year] = expiryDate.split('/').map(num => parseInt(num));
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear() % 100;
    const currentMonth = currentDate.getMonth() + 1;
    
    if (year < currentYear || (year === currentYear && month < currentMonth)) {
        alert('Card has expired. Please enter a valid expiry date');
        return false;
    }
    
    if (month < 1 || month > 12) {
        alert('Please enter a valid month (01-12)');
        return false;
    }
    
    // validate CVV
    if (cvv.length !== 3) {
        alert('Please enter a valid 3-digit CVV');
        return false;
    }
    
    // validate billing address
    if (!billingAddress) {
        alert('Please enter your billing address');
        return false;
    }
    
    if (!billingCity) {
        alert('Please enter your billing city');
        return false;
    }
    
    if (!billingZip) {
        alert('Please enter your billing zip code');
        return false;
    }
    
    return true;
}

// save payment details to localStorage
function savePaymentDetails() {
    const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
    const maskedCardNumber = '**** **** **** ' + cardNumber.slice(-4);
    
    const paymentDetails = {
        cardholderName: document.getElementById('card_name').value.trim(),
        cardNumberMasked: maskedCardNumber,
        expiryDate: document.getElementById('expiry_date').value,
        billingAddress: document.getElementById('billing_address').value.trim(),
        billingCity: document.getElementById('billing_city').value.trim(),
        billingZip: document.getElementById('billing_zip').value.trim(),
        billingCountry: document.getElementById('billing_country').value,
        paymentMethod: 'Credit Card',
        paymentStatus: 'success',
        paymentCompletedAt: new Date().toISOString()
    };
    
    // save payment details
    window.localStorage.setItem('paymentDetails', JSON.stringify(paymentDetails));
    
    // update booking data
    const bookingData = JSON.parse(window.localStorage.getItem('currentBooking'));
    bookingData.bookingStep = 4;
    bookingData.paymentDetails = {
        cardNumberMasked: maskedCardNumber,
        paymentMethod: 'Credit Card',
        paymentStatus: 'success'
    };
    bookingData.paymentCompletedAt = new Date().toISOString();
    
    window.localStorage.setItem('currentBooking', JSON.stringify(bookingData));
    
    // navigate to finish page
    window.location.href = 'booking_finish';
}