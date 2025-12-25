
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Ride Booking Finish</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/ride_booking_finish.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?>
    <div class="ride-booking-finish-container">
        <div class="booking-progress">
            <div class="step done"><span class="circle">&#10003;</span><span class="label">Your Selection</span></div>
            <div class="step done"><span class="circle">&#10003;</span><span class="label">Your Details</span></div>
            <div class="step active"><span class="circle">3</span><span class="label">Finish Booking</span></div>
        </div>
        <h2 class="ride-title ride-summary-title">TRIP SUMMARY</h2>
        <div class="ride-summary-box">
            <div class="ride-summary-details">
                <div class="summary-label"><b>Trip Date:</b></div>
                <div class="summary-value">2025-09-09</div>
                <div class="summary-label"><b>Name:</b></div>
                <div class="summary-value">Anuja Wanigasekara</div>
                <div class="summary-label"><b>Email:</b></div>
                <div class="summary-value">anujawanigasekara5@gmail.com</div>
                <div class="summary-label"><b>Contact Number:</b></div>
                <div class="summary-value">0713344100</div>
                <div class="summary-label"><b>Package:</b></div>
                <div class="summary-value">👤 Adults 2</div>
                <div class="summary-label"><b>Pickup Location:</b></div>
                <div class="summary-value">Galle, Sri Lanka</div>
                <div class="summary-label"><b>Dropoff Location:</b></div>
                <div class="summary-value">Kandy, Sri Lanka</div>
                <div class="summary-label"><b>Distance:</b></div>
                <div class="summary-value">222.34 km</div>
                <div class="summary-label"><b>Vehicle Type:</b></div>
                <div class="summary-value">Mini Car</div>
                <div class="summary-label"><b>Vehicle Description:</b></div>
                <div class="summary-value">Mini Car</div>
                <div class="summary-label"><b>Capacity:</b></div>
                <div class="summary-value">2 passengers, 2 bags</div>
            </div>
        </div>
        <button class="ride-complete-btn">Complete Booking</button>
    </div>
    <script src="/TravelMate/public/assets/js/ride_booking_finish.js"></script>
    <?php include __DIR__ . '/../traveller/footer.view.php'; ?>
</body>
</html>
