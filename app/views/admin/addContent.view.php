<!DOCTYPE html>
<html>
<head>
  <title>Add Content</title>
  <link rel="stylesheet" href="assets/css/Admin/addContent.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-container">
    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <div class="page-title">
        <div class="page-title-content">
          <div class="page-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>Add New Content</h1>
            <p class="page-subtitle">Create and publish new content</p>
          </div>
        </div>
      </div>

      <div class="form-container">
        <form class="content-form" method="POST">
          <div class="form-section">
            <h3>Content Details</h3>
            
            <div class="form-group">
              <label for="contentType">Content Type</label>
              <select id="contentType" name="contentType" required>
                <option value="">Select Content Type</option>
                <option value="hotel">Hotel Listing</option>
                <option value="vehicle">Vehicle Listing</option>
                <option value="attraction">Tourist Attraction</option>
                <option value="restaurant">Restaurant</option>
                <option value="guide">Travel Guide</option>
              </select>
            </div>

            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" id="title" name="title" placeholder="Enter content title" required>
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea id="description" name="description" rows="4" placeholder="Enter detailed description" required></textarea>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Enter location" required>
              </div>
              <div class="form-group">
                <label for="price">Price (LKR)</label>
                <input type="number" id="price" name="price" placeholder="0.00" step="0.01">
              </div>
            </div>

            <div class="form-group">
              <label for="category">Category</label>
              <select id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="luxury">Luxury</option>
                <option value="budget">Budget</option>
                <option value="mid-range">Mid-range</option>
                <option value="premium">Premium</option>
              </select>
            </div>

            <div class="form-group">
              <label for="images">Upload Images</label>
              <input type="file" id="images" name="images[]" multiple accept="image/*">
              <small>Select multiple images (Max 5 files, 2MB each)</small>
            </div>

            <div class="form-group">
              <label for="features">Features/Amenities</label>
              <textarea id="features" name="features" rows="3" placeholder="List key features (one per line)"></textarea>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="tel" id="contact" name="contact" placeholder="+94 XX XXX XXXX">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="contact@example.com">
              </div>
            </div>

            <div class="form-group checkbox-group">
              <label class="checkbox-label">
                <input type="checkbox" name="featured" value="1">
                <span class="checkmark"></span>
                Mark as Featured Content
              </label>
            </div>

            <div class="form-group checkbox-group">
              <label class="checkbox-label">
                <input type="checkbox" name="active" value="1" checked>
                <span class="checkmark"></span>
                Publish Immediately
              </label>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="btn-secondary" onclick="history.back()">Cancel</button>
            <button type="submit" class="btn-primary">Add Content</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>
</html>