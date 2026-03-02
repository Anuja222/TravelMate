<!DOCTYPE html>
<html>
<head>
  <title>Create Activity</title>
  <link rel="stylesheet" href="assets/css/Admin/createActivity.css">
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
            <h1>Create New Activity</h1>
            <p class="page-subtitle">Add a new adventure activity to your listing</p>
          </div>
        </div>
      </div>

      <div class="form-card">
        <h2 class="section-title">Activity Information</h2>
        <form id="createActivityForm">
          <div class="form-group">
            <label class="form-label">Activity Name</label>
            <input type="text" name="title" id="title" class="form-input" placeholder="e.g., Water Rafting, Surfing, Bird Watching" required>
          </div>
          
          <div class="form-group">
            <label class="form-label">Slug (optional)</label>
            <input type="text" name="slug" id="slug" class="form-input" placeholder="URL-friendly name">
          </div>
          
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" id="description" class="form-textarea" rows="5" placeholder="Describe the activity, what makes it special, and what visitors can expect..."></textarea>
          </div>
          
          <div class="form-group">
            <label class="form-label">Activity Image</label>
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
              Create Activity
            </button>
            <button type="button" class="btn-cancel" onclick="window.location.href='ViewActivities'">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
              Cancel
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <!-- Success Modal -->
  <div id="successModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
      <div class="modal-icon-success">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <h3 id="successMessage" class="modal-message">Success!</h3>
      <button onclick="closeSuccessModal()" class="modal-btn-ok">OK</button>
    </div>
  </div>

  <script>
    // Success Modal Functions
    function showSuccessModal(message) {
      document.getElementById('successMessage').textContent = message;
      document.getElementById('successModal').style.display = 'flex';
    }

    function closeSuccessModal() {
      document.getElementById('successModal').style.display = 'none';
      window.location.href = 'ViewActivities';
    }

    // File input handler
    document.getElementById('image').addEventListener('change', function() {
      const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
      document.getElementById('image-name').textContent = fileName;
    });

    // Form submission
    document.getElementById('createActivityForm').addEventListener('submit', function(e){
      e.preventDefault();
      const fd = new FormData(this);
      fetch('../public/api/activity/create', { method:'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(resp => {
          if (resp.success) { 
            showSuccessModal('Activity created successfully!');
          }
          else alert(JSON.stringify(resp.errors));
        })
        .catch(err => { alert('Network error'); console.error(err); });
    });
  </script>
</body>
</html>
