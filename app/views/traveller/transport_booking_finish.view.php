<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
if (!$isLoggedIn) {
    header('Location: /TravelMate/public/login');
    exit;
}

$userId = $_SESSION['user']['id'] ?? '';
$bookingId = $_GET['booking_id'] ?? '';

if (!$bookingId) {
    header('Location: /TravelMate/public/mytransportbookings');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finish Booking - TravelMate Transport</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/booking_finish.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
    
    <script>
        window.loggedInUserId = "<?php echo htmlspecialchars((string)$userId, ENT_QUOTES, 'UTF-8'); ?>";
        window.transportBookingId = "<?php echo htmlspecialchars($bookingId, ENT_QUOTES, 'UTF-8'); ?>";
    </script>
</head>
<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?>

    <div class="booking-finish-container">
        <div class="booking-progress">
            <div class="step done"><span>1</span> Personal Details</div>
            <div class="step done"><span>2</span> Payment Details</div>
            <div class="step active"><span>3</span> Finish / Review</div>
            <div class="step"><span>4</span> Complete Booking</div>
        </div>

        <div class="booking-summary-section">
            <div class="summary-box">
                <h3>Your price summary</h3>
                
                <div class="booking-info">
                    <h4>Transport Details</h4>
                    <div class="info-row">
                        <span>Service Type:</span>
                        <span id="summaryServiceType">-</span>
                    </div>
                    <div class="info-row">
                        <span>Pickup:</span>
                        <span id="summaryPickup">-</span>
                    </div>
                    <div class="info-row">
                        <span>Drop-off:</span>
                        <span id="summaryDropoff">-</span>
                    </div>
                    <div class="info-row">
                        <span>Passengers:</span>
                        <span id="summaryPassengers">-</span>
                    </div>
                </div>

                <div class="booking-info" style="margin-top:16px;">
                    <h4>Personal Details</h4>
                    <div class="info-row">
                        <span>Name:</span>
                        <span id="summaryCustomerName">-</span>
                    </div>
                    <div class="info-row">
                        <span>Email:</span>
                        <span id="summaryCustomerEmail">-</span>
                    </div>
                    <div class="info-row">
                        <span>Phone:</span>
                        <span id="summaryCustomerPhone">-</span>
                    </div>
                </div>

                <div class="booking-info" style="margin-top:16px;">
                    <h4>Payment Details</h4>
                    <div class="info-row">
                        <span>Cardholder:</span>
                        <span id="summaryCardholder">-</span>
                    </div>
                    <div class="info-row">
                        <span>Card:</span>
                        <span id="summaryCard">-</span>
                    </div>
                    <div class="info-row">
                        <span>Billing City:</span>
                        <span id="summaryBillingCity">-</span>
                    </div>
                </div>
                
                <div class="summary-row">
                    <span>Base Price</span>
                    <span id="basePrice">LKR 0.00</span>
                </div>
                <div class="summary-row">
                    <span>Service Charge (10%)</span>
                    <span id="serviceCharge">LKR 0.00</span>
                </div>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal">LKR 0.00</span>
                </div>
                <div class="summary-row">
                    <span>Taxes & Fees (12%)</span>
                    <span id="taxAmount">LKR 0.00</span>
                </div>
                <div class="summary-total">
                    <span>Total Amount</span>
                    <span class="total-price" id="totalPrice">LKR 0.00</span>
                </div>
            </div>

            <div class="payment-box">
                <h3>Payment Information</h3>
                <p>Your payment will be securely processed. Booking confirmation will be available immediately after completion.</p>

                <div class="marketing-checkbox">
                    <input type="checkbox" id="marketing" checked>
                    <label for="marketing">
                        I agree to receiving marketing emails from TravelMate, including promotions, personalized
                        recommendations, and updates about TravelMate's products and services.
                    </label>
                </div>

                <div class="payment-note">
                    By signing up, you allow us to tailor offers and content to your interests. Unsubscribe anytime through your account settings or the link in any marketing email. Read our <a href="/TravelMate/public/privacy">privacy policy</a>.
                </div>

                <div class="booking-terms">
                    By completing this booking you agree to the <a href="/TravelMate/public/termsandcondition">terms and conditions</a> and <a href="/TravelMate/public/privacy">privacy policy</a>.
                </div>

                <button class="complete-booking-btn" id="completeBookingBtn">Complete Booking</button>
            </div>
        </div>
    </div>

    <!-- Booking Confirmation Modal -->
    <div id="confirmationModal" class="confirmation-modal">
        <div class="confirmation-modal-content">
            <div class="confirmation-icon">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="38" stroke="#10b981" stroke-width="3" fill="#ecfdf5"/>
                    <path d="M25 40L35 50L55 30" stroke="#10b981" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2>Booking Confirmed!</h2>
            <p class="booking-id-label">Your Booking ID</p>
            <div class="booking-id-display" id="modalBookingId"></div>
            <p class="confirmation-text">Your transport payment has been completed successfully.</p>
            <button class="btn-view-bookings" onclick="window.location.href='/TravelMate/public/mytransportbookings'">View My Bookings</button>
        </div>
    </div>

    <script src="/TravelMate/public/assets/js/transport_booking_finish.js"></script>
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
</body>
</html>
