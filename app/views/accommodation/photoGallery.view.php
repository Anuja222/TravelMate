<!-- photo Gallery Management Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Villa - Photo Gallery</title>
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accomodation_provider/photoGallery.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/main.css">
</head>
<body>
    <!-- header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <h1>ABC Villa</h1>
    <div class="photo-gallery">
        <div class="gallery-photos">
            <!-- repeat for each photo -->
            <div class="gallery-photo">
                <img src="/assets/images/cover.jpg" alt="Property Photo">
                <button class="delete-photo-btn">Delete</button>
            </div>
            <div class="gallery-photo">
                <img src="/assets/images/cover.jpg" alt="Property Photo">
                <button class="delete-photo-btn">Delete</button>
            </div>
            <div class="gallery-photo">
                <img src="/assets/images/cover.jpg" alt="Property Photo">
                <button class="delete-photo-btn">Delete</button>
            </div>
            <!-- ...more photos... -->
        </div>
        <div class="addMore">
            <h2>Add more photos</h2>
        </div>
        <div class="AddPhotoForm">
            <form class="add-photo-form">
                <div class="photo-upload-box">
                    <input type="file" id="addPhotoInput" multiple accept="image/*">
                    <label for="addPhotoInput" class="photo-upload-label">
                        <span class="photo-upload-icon"></span>
                        <h4>Upload photos</h4>
                    </label>
                </div>
                <a href="viewProperty.view.php"><button type="submit" class="save-btn">Save & Continue</button></a>
            </form>            
        </div>
    </div>
    <!-- footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script src="/TravelMate/public/assets/js/Accomodation_provider/photoGallery.js"></script>
</body>
</html>
