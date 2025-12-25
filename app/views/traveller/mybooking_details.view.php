<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link rel="stylesheet" href="assets/css/Traveller/mybooking_details.css">
</head>
<body>
    <div class="booking-details-container">
        <h2>Booking Details</h2>

        <form class="details-form" id="bookingForm">
            <!-- Row 1: Booking ID & Room Name -->
            <div class="form-row">
                <div class="form-group">
                    <label for="bookingId">Booking ID</label>
                    <input type="text" id="bookingId" readonly>
                </div>
                <div class="form-group">
                    <label for="roomName">Room Name</label>
                    <input type="text" id="roomName" readonly>
                </div>
            </div>

            <!-- Row 2: Check-in & Check-out Dates -->
            <div class="form-row">
                <div class="form-group">
                    <label for="checkinDate">Check-in Date</label>
                    <input type="date" id="checkinDate">
                </div>
                <div class="form-group">
                    <label for="checkoutDate">Check-out Date</label>
                    <input type="date" id="checkoutDate">
                </div>
            </div>

            <!-- Row 3: Adults & Children -->
            <div class="form-row">
                <div class="form-group">
                    <label for="adults">Adults</label>
                    <input type="number" id="adults" min="1">
                </div>
                <div class="form-group">
                    <label for="children">Children</label>
                    <input type="number" id="children" min="0" value="0">
                </div>
            </div>

            <!-- Row 4: Nights & Total Price -->
            <div class="form-row">
                <div class="form-group">
                    <label for="nights">Nights</label>
                    <input type="number" id="nights" readonly>
                </div>
                <div class="form-group">
                    <label for="totalPrice">Total Price (LKR)</label>
                    <input type="text" id="totalPrice" readonly>
                </div>
            </div>

            <!-- Row 5: Booking Status -->
            <!-- <div class="form-row">
                <div class="form-group">
                    <label for="bookingStatus">Booking Status</label>
                    <select id="bookingStatus">
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    
                </div>
            </div> -->

            <!-- Messages -->
            <div class="error-message" id="errorMessage"></div>
            <div class="success-message" id="successMessage"></div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Booking</button>
            </div>
        </form>
    </div>

    <script src="assets/js/mybooking_details.js"></script>
</body>
</html>
