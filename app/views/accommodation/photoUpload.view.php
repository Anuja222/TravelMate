<!-- Photo Upload Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What does your place look like?</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/photoUpload.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
    <script src="/TravelMate/public/assets/js/propertyListing.js" defer></script>
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <h1>What does your place look like?</h1>
    <form class="photo-upload-form" action="/TravelMate/public/savePhoto" method="POST" enctype="multipart/form-data">
        <label>Upload photos of your property</label>
        <div class="photo-upload-box">
            <input type="file" id="photoInput" name="images" multiple accept="image/*">
            <label for="photoInput" class="photo-upload-label">
                <span class="photo-upload-icon"></span>
                Upload photos
            </label>
        </div>
        <div class="property-description-section">
            <label for="propertyDescription">Write a description about your property</label>
            <textarea id="propertyDescription" name="propertyDescription" rows="5" maxlength="1000" placeholder="Describe your property, its features, and what makes it special..." required style="width:100%;resize:vertical;margin-top:10px;"></textarea>
        </div>
        <button type="submit" class="save-btn">Continue</button>
    </form>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script></script>
</body>
</html>
