// transport Booking Details Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const detailsForm = document.getElementById('detailsForm');
    const bookingId = window.transportPaymentBookingId || new URLSearchParams(window.location.search).get('booking_id');
    const submitBtn = detailsForm ? detailsForm.querySelector('.finish-booking-btn') : null;

    if (!bookingId) {
        alert('Booking reference is missing.');
        window.location.href = '/TravelMate/public/mytransportbookings';
        return;
    }

    if (!detailsForm || !submitBtn) {
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Validating...';

    verifyDetailsStep(bookingId).then((isValid) => {
        if (!isValid) {
            return;
        }

        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Next: Payment Details';
    });
    
    detailsForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // gather form data
        const formData = {
            booking_id: bookingId,
            first_name: document.getElementById('first_name').value.trim(),
            last_name: document.getElementById('last_name').value.trim(),
            email: document.getElementById('email').value.trim(),
            address: document.getElementById('address').value.trim(),
            city: document.getElementById('city').value.trim(),
            zip: document.getElementById('zip').value.trim(),
            country: document.getElementById('country').value,
            phone_code: document.getElementById('phone_code').value,
            phone: document.getElementById('phone').value.trim(),
            special_requests: document.getElementById('special_requests').value.trim()
        };
        
        // validate required fields
        if (!formData.first_name || !formData.last_name || !formData.email || 
            !formData.address || !formData.city || !formData.phone) {
            alert('Please fill in all required fields');
            return;
        }
        
        // validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            alert('Please enter a valid email address');
            return;
        }
        
        // validate phone
        if (formData.phone.length < 9) {
            alert('Please enter a valid phone number');
            return;
        }
        
        try {
            // disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // save details to session
            const response = await fetch('/TravelMate/public/api/transport-booking/save-details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData),
                credentials: 'same-origin'
            });
            
            const result = await response.json();
            
            if (result.success) {
                // redirect to payment page
                window.location.href = `/TravelMate/public/transport-booking-payment?booking_id=${encodeURIComponent(bookingId)}`;
            } else {
                alert(getApiErrorMessage(result.errors, 'Failed to save details. Please try again.'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Next: Payment Details';
            }
        } catch (error) {
            console.error('Error saving details:', error);
            alert('An error occurred. Please try again.');
            const submitBtn = detailsForm.querySelector('.finish-booking-btn');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Next: Payment Details';
        }
    });
});

async function verifyDetailsStep(bookingId) {
    try {
        const response = await fetch(`/TravelMate/public/api/transport-booking/review?id=${encodeURIComponent(bookingId)}`, {
            method: 'GET',
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Failed to verify booking step');
        }

        const result = await response.json();

        if (!result.success || !result.data?.booking) {
            alert(getApiErrorMessage(result.errors, 'Invalid booking'));
            window.location.href = '/TravelMate/public/mytransportbookings';
            return false;
        }

        const booking = result.data.booking;
        if (String(booking.booking_status || '').toLowerCase() !== 'confirmed') {
            alert('This booking is not approved for payment yet.');
            window.location.href = '/TravelMate/public/mytransportbookings';
            return false;
        }

        if (String(booking.payment_status || '').toLowerCase() === 'paid') {
            alert('This booking is already paid.');
            window.location.href = '/TravelMate/public/mytransportbookings';
            return false;
        }

        return true;
    } catch (error) {
        console.error(error);
        alert('Unable to validate booking step.');
        window.location.href = '/TravelMate/public/mytransportbookings';
        return false;
    }
}

function getApiErrorMessage(errors, fallbackMessage) {
    if (!errors || typeof errors !== 'object') {
        return fallbackMessage;
    }

    if (errors.general) {
        return errors.general;
    }

    const firstError = Object.values(errors).find((value) => typeof value === 'string' && value.trim() !== '');
    return firstError || fallbackMessage;
}
