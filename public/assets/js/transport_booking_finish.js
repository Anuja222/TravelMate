// Transport Booking Finish Handler
document.addEventListener('DOMContentLoaded', function() {
    const completeBookingBtn = document.getElementById('completeBookingBtn');
    
    completeBookingBtn.addEventListener('click', async function() {
        try {
            // Disable button
            completeBookingBtn.disabled = true;
            completeBookingBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing Payment...';
            
            // Create the booking
            const response = await fetch('/TravelMate/public/api/transport-booking/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            const result = await response.json();
            console.log('Booking result:', result);
            
            if (result.success) {
                // Show confirmation modal
                document.getElementById('modalBookingId').textContent = result.data.booking_id;
                const modal = document.getElementById('confirmationModal');
                modal.classList.add('show');
                modal.style.display = 'flex';
            } else {
                const errorMsg = result.errors?.general || 
                               result.errors?.auth || 
                               result.errors?.availability ||
                               result.errors?.date ||
                               'Failed to complete booking';
                alert('Error: ' + errorMsg);
                completeBookingBtn.disabled = false;
                completeBookingBtn.innerHTML = 'Complete Booking';
            }
        } catch (error) {
            console.error('Booking error:', error);
            alert('An error occurred while processing your booking. Please try again.');
            completeBookingBtn.disabled = false;
            completeBookingBtn.innerHTML = 'Complete Booking';
        }
    });
});
