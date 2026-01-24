// Transport Booking Payment Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    
    // Format card number input
    const cardNumberInput = document.getElementById('card_number');
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });
    
    // Format expiry date input
    const expiryInput = document.getElementById('expiry_date');
    expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });
    
    // Only allow numbers in CVV
    const cvvInput = document.getElementById('cvv');
    cvvInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Gather form data
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
        
        // Validate required fields
        if (!formData.card_name || !formData.card_number || !formData.expiry_date || 
            !formData.cvv || !formData.billing_address || !formData.billing_city || 
            !formData.billing_country) {
            alert('Please fill in all required fields');
            return;
        }
        
        // Validate card number (should be 16 digits)
        if (formData.card_number.length !== 16) {
            alert('Please enter a valid 16-digit card number');
            return;
        }
        
        // Validate expiry date format (MM/YY)
        const expiryRegex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
        if (!expiryRegex.test(formData.expiry_date)) {
            alert('Please enter expiry date in MM/YY format');
            return;
        }
        
        // Validate CVV (should be 3 digits)
        if (formData.cvv.length !== 3) {
            alert('Please enter a valid 3-digit CVV');
            return;
        }
        
        try {
            // Disable submit button
            const submitBtn = paymentForm.querySelector('.finish-booking-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Save payment details to session (masked)
            const maskedData = {
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
            
            const result = await response.json();
            
            if (result.success) {
                // Redirect to finish page
                window.location.href = '/TravelMate/public/transport-booking-finish';
            } else {
                alert(result.errors?.general || 'Failed to save payment details. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Next: Review & Confirm';
            }
        } catch (error) {
            console.error('Error saving payment details:', error);
            alert('An error occurred. Please try again.');
            const submitBtn = paymentForm.querySelector('.finish-booking-btn');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Next: Review & Confirm';
        }
    });
});
