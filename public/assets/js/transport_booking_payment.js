// transport Booking Payment Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const bookingId = String(window.transportPaymentBookingId || new URLSearchParams(window.location.search).get('booking_id') || '').trim();
    const submitBtn = paymentForm ? paymentForm.querySelector('.finish-booking-btn') : null;

    if (!bookingId) {
        alert('Booking reference is missing.');
        window.location.href = '/TravelMate/public/mytransportbookings';
        return;
    }

    if (!paymentForm || !submitBtn) {
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Validating...';

    verifyPaymentStep(bookingId).then((isValid) => {
        if (!isValid) {
            return;
        }

        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Complete Payment';
    });
    
    // format card number input
    const cardNumberInput = document.getElementById('card_number');
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });
    
    // format expiry date input
    const expiryInput = document.getElementById('expiry_date');
    expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });
    
    // only allow numbers in CVV
    const cvvInput = document.getElementById('cvv');
    cvvInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // gather form data
        const formData = {
            card_name: document.getElementById('card_name').value.trim(),
            card_number: document.getElementById('card_number').value.replace(/\s/g, ''),
            expiry_date: document.getElementById('expiry_date').value.trim(),
            cvv: document.getElementById('cvv').value.trim(),
            billing_address: document.getElementById('billing_address').value.trim(),
            billing_city: document.getElementById('billing_city').value.trim(),
            billing_zip: document.getElementById('billing_zip').value.trim(),
            billing_country: document.getElementById('billing_country').value
        };
        
        // validate required fields
        if (!formData.card_name || !formData.card_number || !formData.expiry_date || 
            !formData.cvv || !formData.billing_address || !formData.billing_city || 
            !formData.billing_country) {
            alert('Please fill in all required fields');
            return;
        }
        
        // validate card number (should be 16 digits)
        if (formData.card_number.length !== 16) {
            alert('Please enter a valid 16-digit card number');
            return;
        }
        
        // validate expiry date format (MM/YY)
        const expiryRegex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
        if (!expiryRegex.test(formData.expiry_date)) {
            alert('Please enter expiry date in MM/YY format');
            return;
        }
        
        // validate CVV (should be 3 digits)
        if (formData.cvv.length !== 3) {
            alert('Please enter a valid 3-digit CVV');
            return;
        }
        
        try {
            // disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            const maskedData = {
                booking_id: bookingId,
                card_name: formData.card_name,
                card_last4: formData.card_number.slice(-4),
                billing_address: formData.billing_address,
                billing_city: formData.billing_city,
                billing_zip: formData.billing_zip,
                billing_country: formData.billing_country
            };

            const response = await fetch('/TravelMate/public/api/transport-booking/save-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(maskedData),
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to save payment details');
            }
            
            const result = await response.json();
            
            if (result.success) {
                window.location.href = `/TravelMate/public/transport-booking-finish?booking_id=${encodeURIComponent(bookingId)}`;
            } else {
                alert(getApiErrorMessage(result.errors, 'Failed to save payment details. Please try again.'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Complete Payment';
            }
        } catch (error) {
            console.error('Error saving payment details:', error);
            alert('An error occurred. Please try again.');
            const submitBtn = paymentForm.querySelector('.finish-booking-btn');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Complete Payment';
        }
    });
});

async function verifyPaymentStep(bookingId) {
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

        if (!result.data.has_personal_details) {
            alert('Please complete personal details first.');
            window.location.href = `/TravelMate/public/transport-booking-details?booking_id=${encodeURIComponent(bookingId)}`;
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
