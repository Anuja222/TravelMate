<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Blog</title>
    <link rel="stylesheet" href="assets/css/Traveller/blog.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <!-- Create Post Content -->
    <main class="create-post-container">
        <div class="page-header">
            <h1>Share Your Adventure</h1>
            <p>Tell the Travel Mate community about your amazing travel experience</p>
        </div>

        <form class="post-form" id="createPostForm">
            <div class="form-group">
                <label for="postTitle">Post Title</label>
                <input type="text" id="postTitle" name="postTitle" placeholder="Give your post an engaging title..." required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" placeholder="Where was this photo taken?" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <option value="destination">Destination</option>
                        <option value="adventure">Adventure</option>
                        <option value="food">Food & Culture</option>
                        <option value="tips">Travel Tips</option>
                        <option value="accommodation">Accommodation</option>
                        <option value="transportation">Transportation</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Upload Photo</label>
                <div class="image-upload" onclick="document.getElementById('imageInput').click()">
                    <input type="file" id="imageInput" name="image" accept="image/*" onchange="previewImage(this)">
                    <div class="upload-text">📷 Click to upload photo</div>
                    <div class="upload-subtext">Support JPG, PNG files up to 5MB</div>
                </div>
                <div class="image-preview" id="imagePreview" style="display: none;">
                    <img id="previewImg" src="" alt="Preview">
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Share your experience, tips, or thoughts about this place..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="travelDate">Travel Date</label>
                    <input type="date" id="travelDate" name="travelDate">
                </div>
                <div class="form-group">
                    <label for="rating">Overall Rating</label>
                    <select id="rating" name="rating">
                        <option value="">Rate your experience</option>
                        <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                        <option value="4">⭐⭐⭐⭐ Very Good</option>
                        <option value="3">⭐⭐⭐ Good</option>
                        <option value="2">⭐⭐ Fair</option>
                        <option value="1">⭐ Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="tags">Tags (Optional)</label>
                <input type="text" id="tags" name="tags" placeholder="Add tags separated by commas (e.g., beach, sunset, romantic)">
            </div>

            <div class="form-actions">
                <a href="feed.html" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Share Post</button>
            </div>
        </form>
    </main>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('createPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically send the form data to your server
            alert('Post created successfully! Redirecting to feed...');
            window.location.href = 'feed.html';
        });

        // Tab functionality for filter
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>