<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // You can process form data here if needed
    header('Location: propertyDetails.view.php');
    exit();
}
?>
<!-- Services Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services at your property</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/services.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <h1>Services at your property</h1>
    <form class="services-form" method="POST">
        <fieldset>
            <legend>Breakfast</legend>
            <label>Do you serve guests breakfast?</label>
            <label><input type="radio" name="breakfast" value="yes"> Yes</label>
            <label><input type="radio" name="breakfast" value="no"> No</label>
        </fieldset>
        <fieldset>
            <legend>Parking</legend>
            <label>Is parking available to guests?</label>
            <label><input type="radio" name="parking" value="free"> Yes, free</label>
            <label><input type="radio" name="parking" value="paid"> Yes, paid</label>
            <label><input type="radio" name="parking" value="no"> No</label>
        </fieldset>
        <button type="submit" class="save-btn">Continue</button>
    </form>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accomodation_provider/services.js"></script>
</body>
</html>
