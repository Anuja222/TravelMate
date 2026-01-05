// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    checkBookingData();
    loadPriceSummary();
    setupCompleteBooking();
});

// Check if booking data exists and payment was completed
function checkBookingData() {
    const bookingData = localStorage.getItem('currentBooking') ? 
        JSON.parse(localStorage.getItem('currentBooking')) : null;
    const paymentDetails = localStorage.getItem('paymentDetails') ? 
        JSON.parse(localStorage.getItem('paymentDetails')) : null;
    
    if (!bookingData || bookingData.bookingStep < 4 || !paymentDetails || paymentDetails.paymentStatus !== 'success') {
        alert('Please complete the payment first.');
        window.location.href = 'booking_payment';
        return;
    }
}

// Load and display price summary
function loadPriceSummary() {
    const bookingData = localStorage.getItem('currentBooking') ? 
        JSON.parse(localStorage.getItem('currentBooking')) : null;
    
    if (!bookingData) return;
    
    // Calculate prices
    const totalPrice = bookingData.totalPrice;
    const discount = bookingData.discount || 0.3;
    const originalPrice = Math.round(totalPrice / (1 - discount));
    const discountAmount = originalPrice - totalPrice;
    const taxes = bookingData.taxes || Math.round(totalPrice * 0.15);
    
    // Convert to USD (approximate rate: 1 USD = 300 LKR)
    const usdAmount = Math.round(totalPrice / 300);
    
    // Update display
    const elements = {
        originalPrice: document.getElementById('originalPrice'),
        discountAmount: document.getElementById('discountAmount'),
        totalPrice: document.getElementById('totalPrice'),
        taxAmount: document.getElementById('taxAmount'),
        usdAmount: document.getElementById('usdAmount')
    };

    if (elements.originalPrice) elements.originalPrice.textContent = `LKR ${originalPrice.toLocaleString()}`;
    if (elements.discountAmount) elements.discountAmount.textContent = `LKR ${discountAmount.toLocaleString()}`;
    if (elements.totalPrice) elements.totalPrice.textContent = `LKR ${Math.round(totalPrice).toLocaleString()}`;
    if (elements.taxAmount) elements.taxAmount.textContent = `LKR ${taxes.toLocaleString()}`;
    if (elements.usdAmount) elements.usdAmount.textContent = `US$${usdAmount}`;
}

// Setup complete booking button
function setupCompleteBooking() {
    const completeBtn = document.getElementById('completeBookingBtn');
    
    if (completeBtn) {
        completeBtn.addEventListener('click', function() {
            completeBooking();
        });
    }
}

// Complete the booking
async function completeBooking() {
    const bookingData = localStorage.getItem('currentBooking') ? 
        JSON.parse(localStorage.getItem('currentBooking')) : null;
    const userDetails = localStorage.getItem('userDetails') ? 
        JSON.parse(localStorage.getItem('userDetails')) : null;
    const paymentDetails = localStorage.getItem('paymentDetails') ? 
        JSON.parse(localStorage.getItem('paymentDetails')) : null;
    const marketingConsent = document.getElementById('marketing') ? 
        document.getElementById('marketing').checked : false;
    
    if (!bookingData || !userDetails || !paymentDetails) {
        alert('Booking information is incomplete. Please try again.');
        return;
    }
    
    // Generate booking confirmation number
    const bookingId = 'BK' + Date.now() + Math.floor(Math.random() * 1000);
    
    // Prepare data for database (only required fields)
    const bookingDataForDB = {
        bookingId: bookingId,
		userId: loggedInUserId,
        roomId: bookingData.roomId,
        roomName: bookingData.roomName,
        checkinDate: bookingData.checkinDate,
        checkoutDate: bookingData.checkoutDate,
        adults: bookingData.adults,
        children: bookingData.children || 0,
        nights: bookingData.nights,
        roomPrice: bookingData.roomPrice,
        basePrice: bookingData.basePrice,
        taxes: bookingData.taxes,
        totalPrice: bookingData.totalPrice,
        bookingStatus: 'confirmed',
        paymentStatus: paymentDetails.paymentStatus,
        bookingDate: new Date().toISOString()
    };
    
    try {
        // Send booking to database
        const response = await fetch('/TravelMate/public/api/booking/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bookingDataForDB)
        });

        const result = await response.json();

        if (result.success && result.data) {
            // Create full booking object for localStorage
            const finalBooking = {
                bookingId: bookingId,
                bookingStatus: 'confirmed',
                bookingDate: new Date().toISOString(),
                
                // Accommodation details
                accommodationId: bookingData.accommodationId,
                accommodationName: bookingData.accommodationName,
                roomId: bookingData.roomId,
                roomName: bookingData.roomName,
                
                // Stay details
                checkinDate: bookingData.checkinDate,
                checkoutDate: bookingData.checkoutDate,
                nights: bookingData.nights,
                adults: bookingData.adults,
                children: bookingData.children,
                
                // Pricing
                roomPrice: bookingData.roomPrice,
                basePrice: bookingData.basePrice,
                taxes: bookingData.taxes,
                totalPrice: bookingData.totalPrice,
                discount: bookingData.discount,
                
                // Guest details
                guestDetails: {
                    firstName: userDetails.firstName,
                    lastName: userDetails.lastName,
                    email: userDetails.email,
                    phone: userDetails.phoneCode + userDetails.phone,
                    address: userDetails.address,
                    city: userDetails.city,
                    zip: userDetails.zip,
                    country: userDetails.country
                },
                
                // Payment details
                paymentInfo: {
                    paymentMethod: paymentDetails.paymentMethod,
                    cardNumberMasked: paymentDetails.cardNumberMasked,
                    paymentStatus: paymentDetails.paymentStatus,
                    paymentDate: paymentDetails.paymentCompletedAt
                },
                
                // Marketing consent
                marketingConsent: marketingConsent,
                
                // Timestamps
                createdAt: bookingData.reservedAt || new Date().toISOString(),
                completedAt: new Date().toISOString()
            };
            
            // Save to confirmed booking in localStorage
            localStorage.setItem('confirmedBooking', JSON.stringify(finalBooking));
            
            // Clear current booking and temporary data
            localStorage.removeItem('currentBooking');
            localStorage.removeItem('userDetails');
            localStorage.removeItem('paymentDetails');
            
            // Show success modal with booking ID
            showConfirmationModal(bookingId, userDetails.email);
            
        } else {
            // Handle error
            let errorMsg = 'Failed to complete booking. ';
            if (result.errors) {
                for (const key in result.errors) {
                    errorMsg += result.errors[key] + ' ';
                }
            }
            alert(errorMsg);
        }
        
    } catch (error) {
        console.error('Error completing booking:', error);
        alert('An error occurred while completing your booking. Please try again.');
    }
}

// Show confirmation modal
function showConfirmationModal(bookingId, email) {
    const modal = document.getElementById('confirmationModal');
    const bookingIdElement = document.getElementById('modalBookingId');
    const emailElement = document.getElementById('confirmationEmail');
    
    if (bookingIdElement) {
        bookingIdElement.textContent = bookingId;
    }
    
    if (emailElement) {
        emailElement.textContent = email;
    }
    
    if (modal) {
        modal.classList.add('show');
    }
}
