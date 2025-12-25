<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit House Rules</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/editHouseRules.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <section class="rules-section">
        <h1>Edit House Rules</h1>
        <form class="rules-form">
            <div class="toggle-row">
                <label>Smoking allowed</label>
                <input type="checkbox" class="toggle-switch">
            </div>
            <div class="toggle-row">
                <label>Parties/events allowed</label>
                <input type="checkbox" class="toggle-switch">
            </div>
            <div class="pets-row">
                <label><strong>Do you allow pets?</strong></label>
                <div class="radio-group">
                    <label><input type="radio" name="pets" value="yes"> Yes</label>
                    <label><input type="radio" name="pets" value="request"> Upon request</label>
                    <label><input type="radio" name="pets" value="no" checked> No</label>
                </div>
            </div>
            <div class="checkin-row">
                <div>
                    <label>Check-in From</label>
                    <select><option>15:00</option><option>16:00</option></select>
                </div>
                <div>
                    <label>Until</label>
                    <select><option>18:00</option><option>19:00</option></select>
                </div>
            </div>
            <div class="checkout-row">
                <div>
                    <label>Check-out From</label>
                    <select><option>08:00</option><option>09:00</option></select>
                </div>
                <div>
                    <label>Until</label>
                    <select><option>11:00</option><option>12:00</option></select>
                </div>
            </div>
            <a href="viewProperty.view.php"><button type="button" class="continue-btn">Save & Continue</button></a>
        </form>
    </section>
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accomodation_provider/editHouseRules.js"></script>
</body>
</html>
