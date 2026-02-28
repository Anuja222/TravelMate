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
            <p>Tell the TravelMate community about your amazing travel experience</p>
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
                    <div class="upload-text">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        Click to upload photo
                    </div>
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
                        <option value="5">5 Stars - Excellent</option>
                        <option value="4">4 Stars - Very Good</option>
                        <option value="3">3 Stars - Good</option>
                        <option value="2">2 Stars - Fair</option>
                        <option value="1">1 Star - Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="tags">Tags (Optional)</label>
                <input type="text" id="tags" name="tags" placeholder="Add tags separated by commas (e.g., beach, sunset, romantic)">
            </div>

            <div class="form-actions">
                <a href="feed" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Share Post</button>
            </div>
        </form>
    </main>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <!-- Success Modal -->
    <div id="postSuccessModal" class="post-success-modal">
        <div class="post-success-content">
            <div class="success-icon">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="38" stroke="#10b981" stroke-width="4" fill="#ecfdf5"/>
                    <path d="M25 40L35 50L55 30" stroke="#10b981" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2>Post Shared Successfully!</h2>
            <p>Your travel story has been published to the TravelMate community.</p>
            <div class="modal-actions">
                <button class="btn-view-post" onclick="goToFeed()">View Feed</button>
                <button class="btn-create-another" onclick="closeSuccessModal()">Share Another</button>
            </div>
        </div>
    </div>

    <style>
        /* Success Modal Styles */
        .post-success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .post-success-modal.show {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .post-success-content {
            background: white;
            border-radius: 20px;
            padding: 3em 2.5em;
            text-align: center;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        .success-icon {
            margin-bottom: 1.5em;
            animation: scaleIn 0.5s ease 0.2s both;
        }

        .post-success-content h2 {
            color: #10b981;
            font-size: 28px;
            margin: 0 0 0.5em 0;
            font-weight: 700;
        }

        .post-success-content p {
            color: #6b7280;
            font-size: 16px;
            margin: 0 0 2em 0;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 1em;
            justify-content: center;
        }

        .btn-view-post,
        .btn-create-another {
            border: none;
            padding: 0.9em 2em;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-view-post {
            background: linear-gradient(135deg, #1abc5b 0%, #169d4a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(26, 188, 91, 0.3);
        }

        .btn-view-post:hover {
            background: linear-gradient(135deg, #169d4a 0%, #128a3f 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 188, 91, 0.4);
        }

        .btn-create-another {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-create-another:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }
    </style>

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

        document.getElementById('createPostForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                console.log('Submitting form to blog/store...');
                
                const response = await fetch('blog/store', {
                    method: 'POST',
                    body: formData
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                // Log the actual response for debugging
                const text = await response.text();
                console.log('Raw response:', text);
                
                if (!text || text.trim() === '') {
                    alert('Server returned empty response. Check if posts table exists in database.');
                    return;
                }
                
                let result;
                try {
                    result = JSON.parse(text);
                    console.log('Parsed result:', result);
                } catch (parseError) {
                    console.error('JSON Parse Error:', parseError);
                    console.error('Response text:', text);
                    alert('Server error: Response is not valid JSON.\n\nResponse: ' + text.substring(0, 200));
                    return;
                }
                
                if (result.success) {
                    showSuccessModal();
                } else {
                    console.error('Server error:', result);
                    const errorMsg = result.message || 'Unknown error';
                    const errorDetails = result.errors ? '\n\nDetails: ' + JSON.stringify(result.errors) : '';
                    alert('Error creating post: ' + errorMsg + errorDetails);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                alert('Network error: ' + error.message + '\n\nMake sure Apache and MySQL are running.');
            }
        });

        function showSuccessModal() {
            const modal = document.getElementById('postSuccessModal');
            modal.classList.add('show');
        }

        function closeSuccessModal() {
            const modal = document.getElementById('postSuccessModal');
            modal.classList.remove('show');
            // Reset the form
            document.getElementById('createPostForm').reset();
            document.getElementById('imagePreview').style.display = 'none';
        }

        function goToFeed() {
            window.location.href = 'feed';
        }

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