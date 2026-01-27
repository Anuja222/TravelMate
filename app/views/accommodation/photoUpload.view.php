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
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
    <h1>What does your place look like?</h1>
    <form class="photo-upload-form" action="/TravelMate/public/savePhoto" method="POST" enctype="multipart/form-data">
        <label>Upload photos of your property</label>
        <div class="photo-upload-box">
            <input type="file" id="photoInput" name="images[]" multiple accept="image/*">
            <label for="photoInput" class="photo-upload-label">
                <span class="photo-upload-icon"></span>
                Upload photos
            </label>
        </div>
        <div id="previewContainer" class="image-previews" aria-live="polite"></div>
        <div class="property-description-section">
            <label for="propertyDescription">Write a description about your property</label>
            <textarea id="propertyDescription" name="propertyDescription" rows="5" maxlength="1000" placeholder="Describe your property, its features, and what makes it special..." required style="width:100%;resize:vertical;margin-top:10px;"></textarea>
        </div>
        <button type="submit" class="save-btn">Continue</button>
    </form>
    <!-- Footer -->
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
    <script>
    (() => {
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('previewContainer');

        const renderPreviews = files => {
            preview.innerHTML = '';
            if (!files || !files.length) {
                return;
            }

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) {
                    return;
                }

                const wrapper = document.createElement('div');
                wrapper.className = 'image-preview';

                const img = document.createElement('img');
                img.alt = file.name;
                img.src = URL.createObjectURL(file);
                img.onload = () => URL.revokeObjectURL(img.src);

                wrapper.appendChild(img);
                preview.appendChild(wrapper);
            });
        };

        input.addEventListener('change', event => {
            renderPreviews(event.target.files);
        });
    })();
    </script>
</body>
</html>
