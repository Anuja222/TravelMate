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

$firstName = $_SESSION['user']['first_name'] ?? '';
$lastName = $_SESSION['user']['last_name'] ?? '';

// Get booking data from session
$bookingData = $_SESSION['transport_booking_temp'] ?? null;
$personalDetails = $_SESSION['transport_personal_details'] ?? null;

if (!$bookingData || !$personalDetails) {
    header('Location: /TravelMate/public/transport');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details - TravelMate Transport</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/booking_payment.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?> 
    
    <div class="booking-details-container">
        <div class="booking-progress">
            <div class="step done"><span>1</span> Your Selection</div>
            <div class="step done"><span>2</span> Your Details</div>
            <div class="step active"><span>3</span> Payment Details</div>
            <div class="step"><span>4</span> Finish Booking</div>
        </div>
        
        <h2>Payment Details</h2>
        
        <div class="details-info-box">
            <span class="info-icon">&#128274;</span> Your payment information is secure and encrypted
        </div>
        
        <form class="details-form" id="paymentForm">
            <div class="form-group">
                <label for="card_name">Cardholder name <span class="required">*</span></label>
                <input type="text" id="card_name" name="card_name" value="<?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="card_number">Card number <span class="required">*</span></label>
                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="expiry_date">Expiry date <span class="required">*</span></label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV <span class="required">*</span></label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="billing_address">Billing address <span class="required">*</span></label>
                <input type="text" id="billing_address" name="billing_address" value="<?php echo htmlspecialchars($personalDetails['address'] ?? ''); ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="billing_city">City <span class="required">*</span></label>
                    <input type="text" id="billing_city" name="billing_city" value="<?php echo htmlspecialchars($personalDetails['city'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="billing_zip">Zip Code <span class="optional">(optional)</span></label>
                    <input type="text" id="billing_zip" name="billing_zip" value="<?php echo htmlspecialchars($personalDetails['zip'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="billing_country">Country/Region <span class="required">*</span></label>
                <select id="billing_country" name="billing_country" required>
                    <option value="LK" <?php echo ($personalDetails['country'] ?? '') === 'LK' ? 'selected' : ''; ?>>Sri Lanka</option>
                    <option value="IN" <?php echo ($personalDetails['country'] ?? '') === 'IN' ? 'selected' : ''; ?>>India</option>
                    <option value="US" <?php echo ($personalDetails['country'] ?? '') === 'US' ? 'selected' : ''; ?>>United States</option>
                    <option value="UK" <?php echo ($personalDetails['country'] ?? '') === 'UK' ? 'selected' : ''; ?>>United Kingdom</option>
                </select>
            </div>
            
            <button type="submit" class="finish-booking-btn">Next: Review & Confirm</button>
        </form>
    </div>

    <script src="/TravelMate/public/assets/js/transport_booking_payment.js"></script>
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
</body>
</html>
