<!-- house Rules Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Rules</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/houseRules.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
    <script src="/TravelMate/public/assets/js/propertyListing.js" defer></script>
</head>
<body>
    <!-- header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <h1>House Rules</h1>
    <form class="house-rules-form" action="success" method="get">
        <div class="toggle-row">
            <label>Smoking allowed</label>
            <input type="checkbox" name="smoking" class="toggle-switch">
        </div>
        <div class="toggle-row">
            <label>Parties/events allowed</label>
            <input type="checkbox" name="parties" class="toggle-switch">
        </div>
        <fieldset>
            <legend>Do you allow pets?</legend>
                <label><input type="radio" name="pets" value="yes"> Yes</label>
                <label><input type="radio" name="pets" value="request"> Upon request</label>
                <label><input type="radio" name="pets" value="no"> No</label>
        </fieldset>
        <div class="checkin-row">
            <label>Check-in</label>
            <select name="check_in_start">
                <option>08:00</option>
                <option>09:00</option>
                <option>10:00</option>
                <option>11:00</option>
            </select>
            <span>Until</span>
            <select name="check_in_end">
                <option>14:00</option>
                <option>15:00</option>
                <option>16:00</option>
                <option>17:00</option>
            </select>
        </div>
        <div class="checkout-row">
            <label>Check-out</label>
            <select name="check_out_start">
                <option>08:00</option>
                <option>09:00</option>
                <option>10:00</option>
                <option>11:00</option>
            </select>
            <span>Until</span>
            <select name="check_out_end">
                <option>14:00</option>
                <option>15:00</option>
                <option>16:00</option>
                <option>17:00</option>
            </select>
        </div>
        <button type="submit" class="save-btn">Continue</button>
    </form>
    <!-- footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script></script>
    <script>
    // minimal required-fields check: show a simple message if not completed
    (function(){
        const form = document.querySelector('.house-rules-form');
        if (!form) return;
        form.addEventListener('submit', function(e){
            // simple checks: pets must be selected, and check-in/out sensible
            const petSel = form.querySelector('input[name="pets"]:checked');
            const checkin = form.querySelectorAll('.checkin-row select');
            const checkout = form.querySelectorAll('.checkout-row select');
            let invalid = false;
            if (!petSel) invalid = true;
            if (checkin.length === 2) {
                const a = checkin[0].value, b = checkin[1].value;
                if (!a || !b || a > b) invalid = true;
            }
            if (checkout.length === 2) {
                const a = checkout[0].value, b = checkout[1].value;
                if (!a || !b || a > b) invalid = true;
            }
            if (invalid) {
                e.preventDefault();
                alert('Please complete all required fields before continuing.');
                return false;
            }
            // otherwise allow submit
        });
    })();
    </script>
</body>
</html>
