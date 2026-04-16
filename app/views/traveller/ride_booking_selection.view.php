
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Ride Booking Selection</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/ride_booking_selection.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?>
    <div class="ride-booking-selection-container">
        <div class="booking-progress">
            <div class="step active"><span class="circle">1</span><span class="label">Your Selection</span></div>
            <div class="step"><span class="circle">2</span><span class="label">Your Details</span></div>
            <div class="step"><span class="circle">3</span><span class="label">Finish Booking</span></div>
        </div>
        <h2 class="ride-title">Book Your Ride</h2>
        <div class="ride-summary-section">
            <div class="ride-summary-box">
                <div class="ride-summary-row">
                    <div class="summary-label">TOTAL DISTANCE</div>
                    <div class="summary-value">20.3 km</div>
                </div>
                <div class="ride-summary-row">
                    <div class="summary-label">TOTAL TIME</div>
                    <div class="summary-value">36 mins</div>
                </div>
                <div class="ride-summary-row">
                    <input type="text" value="Galle, Sri Lanka" readonly />
                </div>
                <div class="ride-summary-row">
                    <input type="text" value="Hikkaduwa, Sri Lanka" readonly />
                </div>
                <div class="ride-summary-row">
                    <input type="text" value="2025-09-09" readonly />
                </div>
                <div class="ride-summary-row">
                    <input type="text" value="5:00 PM" readonly />
                </div>
                <div class="ride-summary-row">
                    <input type="number" value="2" min="1" class="summary-adult" />
                    <input type="number" value="0" min="0" class="summary-children" />
                    <input type="number" value="0" min="0" class="summary-baggages" />
                </div>
            </div>
            <div class="ride-map-box">
                <!-- google Map iframe or map placeholder -->
                <div class="map-placeholder">Map</div>
            </div>
        </div>
        <div class="ride-vehicle-options">
            <div class="vehicle-card">
                <div class="vehicle-type">Mini Car</div>
                <div class="vehicle-price">Per Km 110.00 LKR (0.37 USD)</div>
                <div class="vehicle-capacity">2 <span class="icon">👤</span> 2 <span class="icon">🧳</span></div>
                <button class="vehicle-select">Select</button>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-type">Sedan Car</div>
                <div class="vehicle-price">Per Km 125.00 LKR (0.42 USD)</div>
                <div class="vehicle-capacity">3 <span class="icon">👤</span> 3 <span class="icon">🧳</span></div>
                <button class="vehicle-select">Select</button>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-type">SUV Car</div>
                <div class="vehicle-price">Per Km 135.00 LKR (0.45 USD)</div>
                <div class="vehicle-capacity">3 <span class="icon">👤</span> 3 <span class="icon">🧳</span></div>
                <button class="vehicle-select">Select</button>
            </div>
        </div>
        <button class="ride-next-btn">Next</button>
    </div>
    <script src="/TravelMate/public/assets/js/ride_booking_selection.js"></script>
    <?php include __DIR__ . '/../traveller/footer.view.php'; ?>
</body>
</html>
