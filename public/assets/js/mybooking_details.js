const BASE_PATH = window.location.pathname.includes('/TravelMate/')
    ? '/TravelMate/public'
    : '';

// Get booking ID from URL
const urlParams = new URLSearchParams(window.location.search);
const bookingId = urlParams.get('id');

// Load booking details
async function loadBookingDetails() {
    if (!bookingId) {
        showError('No booking ID provided');
        return;
    }

    try {
        const response = await fetch(`${BASE_PATH}/api/booking/${bookingId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        // Debug: Check response type
        const contentType = response.headers.get('content-type');
        console.log('Load Response Content-Type:', contentType);

        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Load Response is not JSON:', text);
            showError('Server error: Expected JSON response');
            return;
        }

        const result = await response.json();

        if (result.success && result.data && result.data.booking) {
            populateForm(result.data.booking);
        } else {
            showError(result.errors?.general || result.errors?.auth || 'Failed to load booking details');
        }
    } catch (error) {
        console.error('Error loading booking details:', error);
        showError('An error occurred while loading booking details');
    }
}

// Store original booking data
let originalBooking = null;

// Populate form with booking details
function populateForm(booking) {
    // Store the original booking data
    originalBooking = booking;
    
    document.getElementById('bookingId').value = booking.booking_id;
    document.getElementById('roomName').value = booking.room_name;
    
    // Handle date formatting - extract just the date part
    const checkinDate = booking.checkin_date.split(' ')[0];
    const checkoutDate = booking.checkout_date.split(' ')[0];
    
    document.getElementById('checkinDate').value = checkinDate;
    document.getElementById('checkoutDate').value = checkoutDate;
    document.getElementById('adults').value = booking.adults;
    document.getElementById('children').value = booking.children || 0;
    document.getElementById('nights').value = booking.nights;
    document.getElementById('totalPrice').value = parseFloat(booking.total_price).toLocaleString();
}

// Calculate nights based on check-in and check-out dates
function calculateNights() {
    const checkinInput = document.getElementById('checkinDate').value;
    const checkoutInput = document.getElementById('checkoutDate').value;
    
    if (!checkinInput || !checkoutInput || !originalBooking) return;
    
    const checkin = new Date(checkinInput);
    const checkout = new Date(checkoutInput);
    
    if (checkout > checkin) {
        const diffTime = Math.abs(checkout - checkin);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        document.getElementById('nights').value = diffDays;
        
        // Recalculate total price based on new nights
        recalculatePrice(diffDays);
    } else if (checkout <= checkin) {
        showError('Check-out date must be after check-in date');
        document.getElementById('nights').value = '';
    }
}

// Recalculate total price when nights change
function recalculatePrice(nights) {
    if (!originalBooking) return;
    
    // Calculate price per night from original booking
    const pricePerNight = parseFloat(originalBooking.room_price);
    const basePrice = pricePerNight * nights;
    const taxes = parseFloat(originalBooking.taxes);
    const totalPrice = basePrice + taxes;
    
    // Update the display
    document.getElementById('totalPrice').value = totalPrice.toLocaleString();
    
    console.log('Price recalculated:', {
        nights: nights,
        pricePerNight: pricePerNight,
        basePrice: basePrice,
        taxes: taxes,
        totalPrice: totalPrice
    });
}

// Form submission
document.getElementById('bookingForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    // Get current total price without formatting
    const totalPriceText = document.getElementById('totalPrice').value.replace(/,/g, '');
    const totalPrice = parseFloat(totalPriceText);

    const data = {
        bookingId: document.getElementById('bookingId').value,
        checkinDate: document.getElementById('checkinDate').value,
        checkoutDate: document.getElementById('checkoutDate').value,
        adults: parseInt(document.getElementById('adults').value),
        children: parseInt(document.getElementById('children').value),
        nights: parseInt(document.getElementById('nights').value),
        // Include pricing information
        roomPrice: parseFloat(originalBooking.room_price),
        basePrice: parseFloat(originalBooking.room_price) * parseInt(document.getElementById('nights').value),
        taxes: parseFloat(originalBooking.taxes),
        totalPrice: totalPrice
    };

    console.log('Submitting booking update:', data);

    // Client-side validation
    if (!data.checkinDate || !data.checkoutDate) {
        showError('Check-in and check-out dates are required');
        return;
    }
    
    const checkin = new Date(data.checkinDate);
    const checkout = new Date(data.checkoutDate);
    if (checkout <= checkin) {
        showError('Check-out date must be after check-in date');
        return;
    }
    
    if (data.adults < 1) {
        showError('At least one adult is required');
        return;
    }
    
    if (data.children < 0) {
        showError('Children count cannot be negative');
        return;
    }
    
    if (data.nights < 1) {
        showError('Number of nights must be at least 1');
        return;
    }

    try {
        const url = `${BASE_PATH}/api/booking/update`;
        console.log('Update URL:', url);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        console.log('Response status:', response.status);
        
        // Debug: Check response content type
        const contentType = response.headers.get('content-type');
        console.log('Update Response Content-Type:', contentType);

        // If response is not JSON, log the actual response
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Update Response is not JSON. Full response:', text);
            showError('Server error: Please check if you are logged in and try again');
            return;
        }

        const result = await response.json();
        console.log('Update result:', result);

        if (result.success) {
            showUpdateSuccessModal();
        } else {
            const errorMsg = result.errors?.general || 
                           result.errors?.auth || 
                           result.errors?.bookingId ||
                           'Failed to update booking';
            showError(errorMsg);
        }
    } catch (error) {
        console.error('Error updating booking:', error);
        showError('An error occurred while updating the booking. Check console for details.');
    }
});

// Update nights when dates change
document.getElementById('checkinDate').addEventListener('change', calculateNights);
document.getElementById('checkoutDate').addEventListener('change', calculateNights);

// Go back to bookings list
function goBack() {
    window.location.href = `${BASE_PATH}/mybookings`;
}

// Show error message
function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    const successDiv = document.getElementById('successMessage');
    
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    successDiv.style.display = 'none';
    
    // Scroll to message
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Show success message
function showSuccess(message) {
    const successDiv = document.getElementById('successMessage');
    const errorDiv = document.getElementById('errorMessage');
    
    successDiv.textContent = message;
    successDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    
    // Scroll to message
    successDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Show update success modal
function showUpdateSuccessModal() {
    const modal = document.getElementById('updateSuccessModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Navigate to my bookings page
function goToMyBookings() {
    window.location.href = `${BASE_PATH}/mybookings`;
}

// Load booking details on page load
document.addEventListener('DOMContentLoaded', loadBookingDetails);