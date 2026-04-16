// Transport Booking Finish Handler
initializeFinishPage();

function initializeFinishPage() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupFinishPage, { once: true });
        return;
    }

    setupFinishPage();
}

function setupFinishPage() {
    loadBookingSummary();
    const completeBookingBtn = document.getElementById('completeBookingBtn');

    if (!completeBookingBtn) {
        return;
    }
    
    completeBookingBtn.addEventListener('click', async function() {
        try {
            // Disable button
            completeBookingBtn.disabled = true;
            completeBookingBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing Payment...';

            const bookingId = getCurrentBookingId();
            if (!bookingId) {
                alert('Booking reference is missing.');
                window.location.href = '/TravelMate/public/mytransportbookings';
                return;
            }
            
            const response = await fetch('/TravelMate/public/api/transport-booking/pay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ booking_id: bookingId }),
                credentials: 'same-origin'
            });
            
            const result = await response.json();
            console.log('Booking result:', result);
            
            if (result.success) {
                // Show confirmation modal
                document.getElementById('modalBookingId').textContent = bookingId;
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
}

function getCurrentBookingId() {
    const fromWindow = typeof window.transportBookingId === 'string' ? window.transportBookingId : '';
    const fromConst = typeof transportBookingId !== 'undefined' ? transportBookingId : '';
    const fromQuery = new URLSearchParams(window.location.search).get('booking_id') || '';
    return String(fromWindow || fromConst || fromQuery || '').trim();
}

async function loadBookingSummary() {
    const bookingId = getCurrentBookingId();
    if (!bookingId) {
        window.location.href = '/TravelMate/public/mytransportbookings';
        return;
    }

    try {
        const response = await fetch(`/TravelMate/public/api/transport-booking/review?id=${encodeURIComponent(bookingId)}`, {
            method: 'GET',
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Failed to load booking review data');
        }

        const result = await response.json();
        if (!result.success || !result.data?.booking) {
            throw new Error(result.errors?.general || 'Unable to load booking summary');
        }

        const booking = result.data.booking;
        const personal = result.data.personal_details || {};
        const payment = result.data.payment_details || {};

        if (String(booking.booking_status || '').toLowerCase() !== 'confirmed') {
            alert('This booking is not approved for payment yet.');
            window.location.href = '/TravelMate/public/mytransportbookings';
            return;
        }

        if (String(booking.payment_status || '').toLowerCase() === 'paid') {
            alert('This booking is already paid.');
            window.location.href = '/TravelMate/public/mytransportbookings';
            return;
        }

        if (!result.data.has_personal_details) {
            alert('Please complete personal details first.');
            window.location.href = `/TravelMate/public/transport-booking-details?booking_id=${encodeURIComponent(bookingId)}`;
            return;
        }

        if (!result.data.has_payment_details) {
            alert('Please complete payment details first.');
            window.location.href = `/TravelMate/public/transport-booking-payment?booking_id=${encodeURIComponent(bookingId)}`;
            return;
        }
        const taxAmount = Math.round((parseFloat(booking.total_price || 0) || 0) * 0.12 * 100) / 100;
        const grandTotal = (parseFloat(booking.total_price || 0) || 0) + taxAmount;

        const formatMoney = (value) => `LKR ${Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        const formatDateTime = (date, time) => {
            if (!date) return '-';
            const d = new Date(date);
            const dateLabel = Number.isNaN(d.getTime()) ? date : d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            return `${dateLabel} at ${time || '-'}`;
        };

        document.getElementById('summaryServiceType').textContent = String(booking.service_type || '-').replace(/^./, ch => ch.toUpperCase());
        document.getElementById('summaryPickup').textContent = `${booking.pickup_location || '-'} on ${formatDateTime(booking.pickup_date, booking.pickup_time)}`;
        document.getElementById('summaryDropoff').textContent = `${booking.dropoff_location || '-'} on ${formatDateTime(booking.return_date, booking.return_time)}`;
        document.getElementById('summaryPassengers').textContent = booking.passengers || '0';

        document.getElementById('summaryCustomerName').textContent = `${personal.first_name || ''} ${personal.last_name || ''}`.trim() || '-';
        document.getElementById('summaryCustomerEmail').textContent = personal.email || '-';
        document.getElementById('summaryCustomerPhone').textContent = `${personal.phone_code || ''} ${personal.phone || ''}`.trim() || '-';

        document.getElementById('summaryCardholder').textContent = payment.card_name || '-';
        document.getElementById('summaryCard').textContent = payment.card_last4 ? `**** **** **** ${payment.card_last4}` : '-';
        document.getElementById('summaryBillingCity').textContent = payment.billing_city || '-';

        document.getElementById('basePrice').textContent = formatMoney(booking.base_price);
        document.getElementById('serviceCharge').textContent = formatMoney(booking.service_charge);
        document.getElementById('subtotal').textContent = formatMoney(booking.total_price);
        document.getElementById('taxAmount').textContent = formatMoney(taxAmount);
        document.getElementById('totalPrice').textContent = formatMoney(grandTotal);
    } catch (error) {
        console.error(error);
        alert('Failed to load booking summary.');
        window.location.href = '/TravelMate/public/mytransportbookings';
    }
}
