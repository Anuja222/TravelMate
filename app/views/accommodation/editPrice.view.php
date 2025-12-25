<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Price</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/editPrice.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <section class="price-section">
        <h1>Edit Price</h1>
        <form class="price-form">
            <h2>Edit Price Per Night</h2>
            <div class="price-box">
                <label><strong>How much do you want to charge per night?</strong></label>
                <input type="text" placeholder="LKR">
                <p class = "desc">Including taxes, commission, and fees</p>
            </div>
            <h2>Edit Price Per Guests</h2>
            <div class="price-box">
                <label><strong>How much do you want to charge per guest?</strong></label>
                <input type="text" placeholder="LKR">
            </div>
            <button type="submit" class="continue-btn">Save & Continue</button>
        </form>
    </section>
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accomodation_provider/editPrice.js"></script>
</body>
</html>
