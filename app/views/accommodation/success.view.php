<!-- success/Thank You Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/success.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <div class="main-success-wrapper">
        <div class="success-card">
            <div class="success-card-header">
                <div class="success-check">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="white"/>
                        <path d="M7 12.5l3 3 6-6" stroke="#8ad44c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="success-title">SUCCESS</div>
            </div>
            <div class="success-card-body">
                <div class="success-message">Congratulations, your property has been successfully listed.</div>
                <div class="success-page-actions" style="display: flex; justify-content: center; gap: 24px; margin: 32px 0 48px 0;">
                    <a href="propertyListingStart" class="success-btn" style="text-decoration:none;">+ Add Another Property</a>
                    <a href="ac_dashboard" class="success-btn" style="text-decoration:none;">Go to Profile</a>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script></script>
</body>
</html>
