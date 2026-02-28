<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['property_details'])) {
    $_SESSION['property_details'] = [
        'bedrooms' => [null],
        'max_guests' => 2,
        'bathrooms' => 1,
        'children' => null
    ];
}

$details = $_SESSION['property_details'];
$bedrooms = $details['bedrooms'] ?? [null];
$bedroomCount = count($bedrooms);
?>
<!-- Edit Your Property Details Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Property Details</title>
    <link rel="stylesheet" href="assets/css/Accommodation/detailsProperty.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <h1>Your Property Details</h1>
    <form class="property-details-form" action="/TravelMate/public/propertyDetails" method="POST">
        <div class="property-rooms">
            <fieldset>
                <legend> Bedrooms </legend>
                <label>Where can people sleep?</label>
                <div id="bedroom-list">
                    <?php foreach ($bedrooms as $index => $bedroom): ?>
                        <div class="bedroom-slot">
                            <a href="bedRoom?bed=<?= $index + 1 ?>" class="bedroom-info">
                                <div class="bedroom-label">Bedroom <?= $index + 1 ?></div>
                                <div class="bedroom-subtitle">
                                    <?php
                                        if (is_array($bedroom)) {
                                            $type = $bedroom['type'] ?? '';
                                            $count = $bedroom['count'] ?? '';
                                            if (!empty($type)) {
                                                echo htmlspecialchars($type);
                                            }
                                            if (!empty($count)) {
                                                echo ' (' . htmlspecialchars($count) . ')';
                                            }
                                        }
                                    ?>
                                </div>
                            </a>
                            <button type="submit" name="action" value="remove_bedroom:<?= $index ?>" class="remove-bed" title="Remove">
                                <img src="/TravelMate/public/assets/images/trashBin.png" alt="Remove" width="18" height="18">
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" name="action" value="add_bedroom" class="add-bedroom-btn">+ Add bedroom</button>
            </fieldset>
        </div>
        <div class="property-guests">
            <label>How many guests can stay? (Maximum 2 people can stay in a one room.)</label>
            <input type="number" name="max_guests" min="1" max="100" value="<?= (int)($details['max_guests'] ?? 2) ?>">
        </div>
        <div class="property-bathrooms">
            <label>How many bathrooms are there? (Minimum 1 bathroom should be there for a property.)</label>
            <input type="number" name="bathrooms" min="1" max="100" value="<?= (int)($details['bathrooms'] ?? 1) ?>">
        </div>
        </fieldset>
        <div class="property-children">
            <label>Do you allow childrens?</label>
            <input type="radio" name="children" value="yes" <?= ($details['children'] ?? '') === 'yes' ? 'checked' : '' ?>> Yes
            <input type="radio" name="children" value="no" <?= ($details['children'] ?? '') === 'no' ? 'checked' : '' ?>> No
        </div>
        <input type="hidden" name="rooms" value="<?= $bedroomCount ?>">
        <button type="submit" formaction="/TravelMate/public/saveDetails" class="save-btn">Continue</button>
    </form>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script></script>
</body>
</html>
