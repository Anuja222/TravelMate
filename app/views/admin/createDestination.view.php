<!DOCTYPE html>
<html>
<head>
  <title>Create Destination</title>
  <link rel="stylesheet" href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/css/Admin/createDestination.css">
  <link rel="stylesheet" href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/css/Admin/common.css">
</head>
<body>
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<<<<<<< HEAD
  <div class="page-container">
    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <div class="page-title">
        <div class="page-title-content">
          <div class="page-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>Create New Destination</h1>
            <p class="page-subtitle">Add a new destination to your listing</p>
          </div>
        </div>
      </div>

      <div class="form-card">
        <h2 class="section-title">Destination Information</h2>
        <form id="createDestForm">
          <div class="form-group">
            <label class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-input" required>
          </div>
          
          <div class="form-group">
            <label class="form-label">Slug (optional)</label>
            <input type="text" name="slug" id="slug" class="form-input">
          </div>
          
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" id="description" class="form-textarea" rows="5"></textarea>
          </div>
          
          <div class="form-group">
            <label class="form-label">Image</label>
            <div class="file-upload-wrapper">
              <input type="file" name="image" id="image" class="file-input" accept="image/*">
              <label for="image" class="file-upload-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="17 8 12 3 7 8"></polyline>
                  <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Choose Image
              </label>
              <span class="file-name" id="image-name">No file chosen</span>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              Create Destination
            </button>
            <button type="button" class="btn-cancel" onclick="window.location.href='ViewListing'">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
              Cancel
            </button>
          </div>
        </form>
      </div>
=======
  <div class="page-containerr">
    <div class="content">
      <h1>Create Destination</h1>
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874

      <form id="createDestForm">
        <div>
          <label>Title</label>
          <input name="title" id="title" required>
        </div>
        <div>
          <label>Slug (optional)</label>
          <input name="slug" id="slug">
        </div>
        <div>
          <label>Description</label>
          <textarea name="description" id="description"></textarea>
        </div>
        <div>
          <label>Image</label>
          <input type="file" name="image" id="image">
        </div>
        <div>
          <button type="submit" class="btn-primary">Create</button>
          <button type="button" onclick="window.location.href='ViewListing'">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  document.getElementById('createDestForm').addEventListener('submit', function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fetch('../public/api/destination/create', { method:'POST', body: fd, credentials: 'same-origin' })
      .then(r => r.json())
      .then(resp => {
        if (resp.success) { alert('Created'); window.location.href='ViewListing'; }
        else alert(JSON.stringify(resp.errors));
      })
      .catch(err => { alert('Network error'); console.error(err); });
  });
  </script>
</body>
</html>