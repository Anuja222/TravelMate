// Transport Booking Details Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const detailsForm = document.getElementById('detailsForm');
    
    detailsForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Gather form data
        const formData = {
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
        
        // Validate required fields
        if (!formData.first_name || !formData.last_name || !formData.email || 
            !formData.address || !formData.city || !formData.phone) {
            alert('Please fill in all required fields');
            return;
        }
        
        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            alert('Please enter a valid email address');
            return;
        }
        
        // Validate phone
        if (formData.phone.length < 9) {
            alert('Please enter a valid phone number');
            return;
        }
        
        try {
            // Disable submit button
            const submitBtn = detailsForm.querySelector('.finish-booking-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Save details to session
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
                // Redirect to payment page
                window.location.href = '/TravelMate/public/transport-booking-payment';
            } else {
                alert(result.errors?.general || 'Failed to save details. Please try again.');
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
