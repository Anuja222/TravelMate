<?php
// start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Availability</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/booking_availability.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>

<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?>
    <div class="booking-availability-container">
        <div class="booking-progress">
            <div class="step active"><span>1</span> Your Selection</div>
            <div class="step"><span>2</span> Your Details</div>
            <div class="step"><span>3</span> Payment Details</div>
            <div class="step"><span>4</span> Finish Booking</div>
        </div>
        <h2>Availability</h2>
        <div class="booking-search-bar">
            <div class="date-picker">
                <input type="text" value="Mon, Sep 8 — Tue, Sep 9" readonly />
            </div>
            <div class="guest-room-picker">
                <input type="text" value="2 adults · 0 children · 1 room" readonly />
            </div>
            <button class="change-search-btn">Change search</button>
        </div>
        <div class="availability-table">
            <div class="availability-header">
                <div class="room-type-header">Room type</div>
                <div class="price-header">Today's Price</div>
                <div class="reserve-header"></div>
            </div>
            <div class="availability-row">
                <div class="room-type">
                    <a href="#" class="room-link">Family Suite</a>
                    <div class="room-warning"> <span class="dot"></span> Only 1 room left on our site</div>
                </div>
                <div class="price">
                    <span class="old-price">LKR 52,040</span><br>
                    <span class="current-price">LKR 31,704</span>
                </div>
                <div class="reserve-btn-cell">
                    <button class="reserve-btn" onclick="window.location.href='booking_details'">
                        I'll reserve
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="/TravelMate/public/assets/js/booking_availability.js"></script>
    <?php include __DIR__ . '/../traveller/footer.view.php'; ?>
</body>

</html>