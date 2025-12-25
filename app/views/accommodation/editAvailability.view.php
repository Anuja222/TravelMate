<!-- Edit Availability Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Availability</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/editAvailability.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <div class="edit-availability-container">
        <h1>Edit Availability</h1>
        <span class="max-bedrooms-label">Maximum Bedrooms - 5</span>
        <form id="editBedroomsForm">
            <div class="bedroom-box">
                <div class="bedroom-row">
                    <span class="bedroom-label">Available Bedrooms</span>
                    <div class="bedroom-counter">
                        <button type="button" class="counter-btn" id="decrementBtn">&#8211;</button>
                        <input type="text" class="bedroom-value" id="bedroomValue" name="bedroomValue" value="2" readonly>
                        <button type="button" class="counter-btn" id="incrementBtn">+</button>
                    </div>
                </div>
            </div>
            <button type="submit" class="update-btn">Update</button>
        </form>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script>
        // Counter logic
        const decrementBtn = document.getElementById('decrementBtn');
        const incrementBtn = document.getElementById('incrementBtn');
        const bedroomValue = document.getElementById('bedroomValue');
        const maxBedrooms = 5;
        decrementBtn.addEventListener('click', function() {
            let val = parseInt(bedroomValue.value, 10);
            if (val > 1) bedroomValue.value = val - 1;
        });
        incrementBtn.addEventListener('click', function() {
            let val = parseInt(bedroomValue.value, 10);
            if (val < maxBedrooms) bedroomValue.value = val + 1;
        });
        // Prevent form submit (demo only)
        document.getElementById('editBedroomsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // You can add your update logic here
        });
    </script>
</body>
</html>
