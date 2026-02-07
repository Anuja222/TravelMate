<!-- Bed Room Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Room</title>
    <link rel="stylesheet" href="assets/css/Accommodation/bedRoom.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <?php
    $bedNumber = 1;
    if (isset($_GET['bed']) && is_numeric($_GET['bed'])) {
        $bedNumber = (int) $_GET['bed'];
        if ($bedNumber < 1) $bedNumber = 1;
    }
    ?>
    <h1>Bed Room <?php echo $bedNumber; ?></h1>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $existingType = '';
    $existingCount = 1;
    if (!empty($_SESSION['property_details']['bedrooms'][$bedNumber - 1])) {
        $existingType = $_SESSION['property_details']['bedrooms'][$bedNumber - 1]['type'] ?? '';
        $existingCount = (int)($_SESSION['property_details']['bedrooms'][$bedNumber - 1]['count'] ?? 1);
        if ($existingCount < 1) $existingCount = 1;
    }
    ?>
    <form class="bedroom-form" method="POST" action="/TravelMate/public/bedRoom">
        <label>Which beds are available in this room?</label>
        <div class="bed-type-row">
            <div class="bed">
                <select name="bed_type">
                    <option <?= $existingType === 'King bed' ? 'selected' : '' ?>>King bed</option>
                    <option <?= $existingType === 'Queen bed' ? 'selected' : '' ?>>Queen bed</option>
                    <option <?= $existingType === 'Double bed' ? 'selected' : '' ?>>Double bed</option>
                    <option <?= $existingType === 'Twin bed' ? 'selected' : '' ?>>Twin bed</option>
                    <option <?= $existingType === 'Full bed' ? 'selected' : '' ?>>Full bed</option>
                    <option <?= $existingType === 'Single bed' ? 'selected' : '' ?>>Single bed</option>
                </select>
            </div>
            <div class="counter">
                <input type="number" name="bed_count" min="1" max="10" value="<?= $existingCount ?>">
            </div>
        </div>
        <input type="hidden" name="bed_index" value="<?= (int) $bedNumber ?>">
        <button type="submit" class="save-btn">Continue</button>
    </form>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
</body>
</html>
