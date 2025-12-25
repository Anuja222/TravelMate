<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Ride Booking Details</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/ride_booking_details.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?>
    <div class="ride-booking-details-container">
        <div class="booking-progress">
            <div class="step done"><span class="circle">&#10003;</span><span class="label">Your Selection</span></div>
            <div class="step active"><span class="circle">2</span><span class="label">Your Details</span></div>
            <div class="step"><span class="circle">3</span><span class="label">Finish Booking</span></div>
        </div>
        <h2 class="ride-title">CONTACT DETAILS</h2>
    <form class="ride-details-form">
            <div class="ride-details-row">
                <div class="ride-details-col">
                    <input type="text" value="Anuja" readonly />
                    <input type="email" value="anujawanigasekara5@gmail.com" readonly />
                </div>
                <div class="ride-details-col">
                    <input type="text" value="Wanigasekara" readonly />
                    <input type="text" value="0713344100" readonly />
                </div>
            </div>
            <textarea placeholder="Comments"></textarea>
            <hr>
            <button type="submit" class="ride-finish-btn">Finish Booking</button>
        </form>
    </div>
    <script src="/TravelMate/public/assets/js/ride_booking_details.js"></script>
    <?php include __DIR__ . '/../traveller/footer.view.php'; ?>
</body>
</html>