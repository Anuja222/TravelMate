<!-- Price Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Per Night & Price Per Guests</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/price.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <section class="price-section">
        <h1>Add Price</h1>
        <form class="price-form">
            <h2>Price Per Night</h2>
            <div class="price-box">
                <label><strong>How much do you want to charge per night?</strong></label>
                <input type="text" placeholder="LKR">
                <p class = "desc">Including taxes, commission, and fees</p>
            </div>
            <h2>Price Per Guests</h2>
            <div class="price-box">
                <label><strong>How much do you want to charge per guest?</strong></label>
                <input type="text" placeholder="LKR">
            </div>
            <button type="submit" class="continue-btn">Continue</button>
        </form>
    </section>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accomodation_provider/price.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          const bell = document.getElementById('notificationBell');
          const popup = document.getElementById('notificationPopup');
          bell.addEventListener('click', function(e) {
            popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
            e.stopPropagation();
          });
          document.addEventListener('click', function() {
            popup.style.display = 'none';
          });
          popup.addEventListener('click', function(e) {
            e.stopPropagation();
          });
        });
    </script>
</body>
</html>
