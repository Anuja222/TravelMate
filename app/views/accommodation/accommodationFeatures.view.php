<?php
// Populate property type from session if it exists
$propertyType = $_SESSION['accommodation_features']['property_type'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // You can process form data here if needed
    header('Location: services.view.php');
    exit();
}
?>
<!-- Accommodation Features Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit what can guests use at your place?</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/accommodationFeatures.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">  
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
    <script src="/TravelMate/public/assets/js/accommodation.js" defer></script>
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <!-- Page Content -->
    <form class="features-form" method="POST" action="/TravelMate/public/saveFeatures">
        <fieldset>
            <legend><h2>Property Information</h2></legend>
            <div class="form-group">
                <label for="property_type">Property Type *</label>
                <input type="text" id="property_type" name="property_type" value="<?php echo htmlspecialchars($propertyType); ?>" placeholder="Property Type" readonly style="background-color: #f0f0f0;">
            </div>
            <div class="form-group">
                <label for="title">Accommodation Name *</label>
                <input type="text" id="title" name="title" placeholder="Accommodation Name" required>
            </div>
            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" placeholder="Address" required>
            </div>
            <!-- <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" placeholder="Describe your property" required></textarea>
            </div> -->
        </fieldset>

        <h2>What can guests use at your place?</h2>
        <fieldset>
            <legend>General</legend>
            <label><input type="checkbox" name="feature_air_conditioning" value="1"> Air conditioning</label>
            <label><input type="checkbox" name="feature_heating" value="1"> Heating</label>
            <label><input type="checkbox" name="feature_wifi" value="1"> Free Wifi</label>
            <label><input type="checkbox" name="feature_ev_charging" value="1"> Electric vehicle charging station</label>
            <label><input type="checkbox" name="feature_pool" value="1"> Swimming pool</label>
        </fieldset>
        <fieldset>
            <legend>Cooking and cleaning</legend>
            <label><input type="checkbox" name="feature_kitchen" value="1"> Kitchen</label>
            <label><input type="checkbox" name="feature_kitchenette" value="1"> Kitchenette</label>
            <label><input type="checkbox" name="feature_washing_machine" value="1"> Washing machine</label>
        </fieldset>
        <fieldset>
            <legend>Entertainment</legend>
            <label><input type="checkbox" name="feature_tv" value="1"> Flat-screen TV</label>
            <label><input type="checkbox" name="feature_entertainment_pool" value="1"> Swimming pool</label>
            <label><input type="checkbox" name="feature_hot_tub" value="1"> Hot tub</label>
            <label><input type="checkbox" name="feature_minibar" value="1"> Minibar</label>
            <label><input type="checkbox" name="feature_sauna" value="1"> Sauna</label>
        </fieldset>
        <fieldset>
            <legend>Outside and view</legend>
            <label><input type="checkbox" name="feature_balcony" value="1"> Balcony</label>
            <label><input type="checkbox" name="feature_garden_view" value="1"> Garden view</label>
            <label><input type="checkbox" name="feature_terrace" value="1"> Terrace</label>
            <label><input type="checkbox" name="feature_view" value="1"> View</label>
        </fieldset>
        <fieldset>
            <legend>Safety and Security</legend>
            <label><input type="checkbox" name="feature_cctv" value="1"> CCTV</label>
            <label><input type="checkbox" name="feature_security_guards" value="1"> Security guards</label>
            <label><input type="checkbox" name="feature_first_aid_kit" value="1"> First aid kit</label>
        </fieldset>
        <fieldset>
            <legend>Living Area</legend>
            <label><input type="checkbox" name="feature_living_room" value="1"> Living Room</label>
        </fieldset>
        <button type="submit" class="save-btn">Continue</button>
    </form>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script></script>
</body>
</html>
