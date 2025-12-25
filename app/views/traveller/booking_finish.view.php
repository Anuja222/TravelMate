<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$id = $isLoggedIn ? $_SESSION['user']['id'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finish Booking - TravelMate</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/booking_finish.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">

    <script>
        const loggedInUserId = "<?php echo $id; ?>";
    </script>
</head>

<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?>

    <div class="booking-finish-container">
        <div class="booking-progress">
            <div class="step done"><span>1</span> Your Selection</div>
            <div class="step done"><span>2</span> Your Details</div>
            <div class="step done"><span>3</span> Payment Details</div>
            <div class="step active"><span>4</span> Finish Booking</div>
        </div>

        <div class="booking-summary-section">
            <div class="summary-box">
                <h3>Your price summary</h3>
                <div class="summary-row">
                    <span>Original price</span>
                    <span class="old-price" id="originalPrice">LKR 52,840</span>
                </div>
                <div class="summary-row">
                    <span>Limited-time Deal</span>
                    <span class="discount-price" id="discountAmount">LKR 21,136</span>
                </div>
                <div class="summary-note">
                    Limited-time discount because—for a limited time only—this property is offering reduced rates on
                    some room types. Much cheaper now!
                </div>
                <div class="summary-total">
                    <span>Price</span>
                    <span class="total-price" id="totalPrice">LKR 31,704</span>
                </div>
                <div class="summary-currency">
                    + <span id="taxAmount">LKR 5,418</span> taxes and fees<br>
                    In property currency: <span id="usdAmount">US$105</span>
                </div>
            </div>

            <div class="payment-box">
                <h3>No payment details required</h3>
                <p>No payment will be handled by Grand Serendib Hotel, so you don't need to enter any payment details
                    for this booking.</p>

                <div class="marketing-checkbox">
                    <input type="checkbox" id="marketing" checked>
                    <label for="marketing">
                        I agree to receiving marketing emails from Booking.com, including promotions, personalized
                        recommendations, rewards, travel experiences, and updates about Booking.com's products and
                        services.
                    </label>
                </div>

                <div class="payment-note">
                    By signing up, you allow us to tailor offers and content to your interests by monitoring how you use
                    Booking.com through tracking technologies. Unsubscribe anytime through your account settings or the
                    link in any marketing email. Read our <a href="#">privacy policy</a>.
                </div>

                <div class="booking-terms">
                    Your booking is directly with Grand Serendib Hotel and by completing this booking you agree to the
                    <a href="#">booking conditions</a>, <a href="#">general terms</a>, and <a href="#">privacy
                        policy</a>.
                </div>

                <button class="complete-booking-btn" id="completeBookingBtn">Complete booking</button>
            </div>
        </div>
    </div>

    <script src="/TravelMate/public/assets/js/booking_finish.js"></script>
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
</body>

</html>