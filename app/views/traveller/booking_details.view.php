<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
$email = $isLoggedIn ? $_SESSION['user']['email'] : '';
$phone = $isLoggedIn ? $_SESSION['user']['phone'] : '';
$maskedPhone = $phone ? substr($phone, 1) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Your Details - TravelMate</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/booking_details.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?> 
    
    <div class="booking-details-container">
        <div class="booking-progress">
            <div class="step done"><span>1</span> Your Selection</div>
            <div class="step active"><span>2</span> Your Details</div>
            <div class="step"><span>3</span> Payment Details</div>
            <div class="step"><span>4</span> Finish Booking</div>
        </div>
        
        <h2>Enter your details</h2>
        
        <div class="details-info-box">
            <span class="info-icon">&#9888;</span> Almost done! Just fill in the <span class="required">*</span> required info
        </div>
        
        <form class="details-form" id="detailsForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First name <span class="required">*</span></label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last name <span class="required">*</span></label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email address <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="form-note">Confirmation email sent to this address</div>
            </div>
            
            <div class="form-group">
                <label for="address">Address <span class="required">*</span></label>
                <input type="text" id="address" name="address" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="city">City <span class="required">*</span></label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="zip">Zip Code <span class="optional">(optional)</span></label>
                    <input type="text" id="zip" name="zip">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="country">Country/Region</label>
                    <select id="country" name="country">
                        <option value="LK">Sri Lanka</option>
                        <option value="IN">India</option>
                        <option value="US">United States</option>
                        <option value="UK">United Kingdom</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone">Phone number <span class="required">*</span></label>
                    <div class="phone-input">
                        <select id="phone_code" name="phone_code">
                            <option value="+94">+94</option>
                            <option value="+91">+91</option>
                            <option value="+1">+1</option>
                            <option value="+44">+44</option>
                        </select>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($maskedPhone); ?>" required>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="finish-booking-btn">Complete Details</button>
        </form>
    </div>

    <script src="/TravelMate/public/assets/js/booking_details.js?v=<?php echo time(); ?>"></script>
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
</body>
</html>