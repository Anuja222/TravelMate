<!-- Accommodation Features Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit what can guests use at your place?</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/editAccommodationFeatures.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <!-- Page Content -->
    <h1>Edit what can guests use at your place?</h1>
    <form class="features-form">
        <fieldset>
            <legend>General</legend>
            <label><input type="checkbox" name="features[]" value="air_conditioning"> Air conditioning</label>
            <label><input type="checkbox" name="features[]" value="heating"> Heating</label>
            <label><input type="checkbox" name="features[]" value="wifi"> Free Wifi</label>
            <label><input type="checkbox" name="features[]" value="ev_charging"> Electric vehicle charging station</label>
        </fieldset>
        <fieldset>
            <legend>Cooking and cleaning</legend>
            <label><input type="checkbox" name="features[]" value="kitchen"> Kitchen</label>
            <label><input type="checkbox" name="features[]" value="kitchenette"> Kitchenette</label>
            <label><input type="checkbox" name="features[]" value="washing_machine"> Washing machine</label>
        </fieldset>
        <fieldset>
            <legend>Entertainment</legend>
            <label><input type="checkbox" name="features[]" value="tv"> Flat-screen TV</label>
            <label><input type="checkbox" name="features[]" value="pool"> Swimming pool</label>
            <label><input type="checkbox" name="features[]" value="hot_tub"> Hot tub</label>
            <label><input type="checkbox" name="features[]" value="minibar"> Minibar</label>
            <label><input type="checkbox" name="features[]" value="sauna"> Sauna</label>
        </fieldset>
        <fieldset>
            <legend>Outside and view</legend>
            <label><input type="checkbox" name="features[]" value="balcony"> Balcony</label>
            <label><input type="checkbox" name="features[]" value="garden_view"> Garden view</label>
            <label><input type="checkbox" name="features[]" value="terrace"> Terrace</label>
            <label><input type="checkbox" name="features[]" value="view"> View</label>
        </fieldset>
    <a href="viewProperty.view.php"><button type="button" class="save-btn">Save & Continue</button></a>
    </form>

    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accomodation_provider/accommodationFeatures.js"></script>
</body>
</html>
