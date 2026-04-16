<!DOCTYPE html>
<html>
<head>
  <title>Edit Activity</title>
  <link rel="stylesheet" href="assets/css/Admin/editActivity.css">
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
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>Edit Activity</h1>
            <p class="page-subtitle">Update activity details and manage locations</p>
          </div>
        </div>
      </div>

      <div class="form-card">
        <h2 class="section-title">Activity Information</h2>
        <form id="editActivityForm">
          <input type="hidden" name="id" id="id">
          
          <div class="form-group">
            <label class="form-label">Activity Name</label>
            <input type="text" name="title" id="title" class="form-input" required>
          </div>
          
          <div class="form-group">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-input">
          </div>
          
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" id="description" class="form-textarea" rows="5"></textarea>
          </div>
          
          <div class="form-group">
            <label class="form-label">Image (upload to replace)</label>
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
            <button type="submit" class="btn-update">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
              </svg>
              Update Activity
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

      <!-- Places (Locations) management -->
      <div class="form-card" style="margin-top: 32px;">
        <h2 class="section-title">Locations / Destinations for this Activity</h2>
        
        <div id="placesList" class="places-grid">
          <p style="color: #666;">Loading locations...</p>
        </div>

        <div class="divider"></div>

        <h3 class="subsection-title" id="addPlaceHeading">Add Location</h3>
        <form id="addPlaceForm">
          <input type="hidden" name="activity_id" id="place_activity_id">
          
          <div class="form-group">
            <label class="form-label">Location Name</label>
            <input type="text" name="title" id="place_title" class="form-input" required>
          </div>
          
          <div class="form-group">
            <label class="form-label">Slug (optional)</label>
            <input type="text" name="slug" id="place_slug" class="form-input">
          </div>
          
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" id="place_description" class="form-textarea" rows="4"></textarea>
          </div>

          <div class="form-group">
            <label class="form-label">Location</label>
            <input type="text" name="location" id="place_location" class="form-input">
          </div>
          
          <div class="form-group">
            <label class="form-label">Best Time to Visit</label>
            <input type="text" name="best_time" id="place_best_time" class="form-input">
          </div>
          
          <div class="form-group">
            <label class="form-label">Image</label>
            <div class="file-upload-wrapper">
              <input type="file" name="image" id="place_image" class="file-input" accept="image/*">
              <label for="place_image" class="file-upload-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="17 8 12 3 7 8"></polyline>
                  <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Choose Image
              </label>
              <span class="file-name" id="place_image-name">No file chosen</span>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              Add Location
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

  <!-- Delete Confirm Modal -->
  <div id="deleteConfirmModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
      <div class="modal-icon-warning" style="margin-bottom:15px; color:#ef4444; display:flex; justify-content:center;">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
      </div>
      <h3 class="modal-message">Delete Location</h3>
      <p style="color:#6b7280; font-size:14px; margin-bottom:20px; text-align:center;">Are you sure you want to delete this location? This action cannot be undone.</p>
      <div style="display: flex; gap: 12px; justify-content: center;">
        <button onclick="closeDeleteConfirmModal()" class="btn-cancel" style="padding: 10px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight:600;">Cancel</button>
        <button id="confirmDeleteBtn" style="padding: 10px 24px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight:600;">Delete</button>
      </div>
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
    }

    // Delete Confirm Modal Functions
    let placeToDeleteId = null;
    let placeToDeleteElement = null;

    function showDeleteConfirmModal(id, element) {
      placeToDeleteId = id;
      placeToDeleteElement = element;
      document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    function closeDeleteConfirmModal() {
      placeToDeleteId = null;
      placeToDeleteElement = null;
      document.getElementById('deleteConfirmModal').style.display = 'none';
    }

    // Attach listener immediately since elements exist here
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
      if (!placeToDeleteId) return;

      const fd = new FormData();
      fd.append('id', placeToDeleteId);

      // Disable button while processing
      this.disabled = true;
      const originalText = this.textContent;
      this.textContent = 'Deleting...';

      fetch('../public/api/activity/place/delete', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(resp => {
          this.disabled = false;
          this.textContent = originalText;
          closeDeleteConfirmModal();
          
          if (resp.success) {
            showSuccessModal('Location deleted successfully!');
            if (placeToDeleteElement) {
              placeToDeleteElement.remove();
            }
            const container = document.getElementById('placesList');
            if (container && container.children.length === 0) {
              container.innerHTML = '<div class="empty-places">No locations added yet. Use the form below to add your first location.</div>';
            }
          } else {
            alert('Delete failed: ' + (resp.errors ? JSON.stringify(resp.errors) : 'Unknown error'));
          }
        })
        .catch(err => {
          this.disabled = false;
          this.textContent = originalText;
          closeDeleteConfirmModal();
          alert('Network error while deleting.');
          console.error(err);
        });
    });

    // File input handlers
    document.getElementById('image').addEventListener('change', function() {
      const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
      document.getElementById('image-name').textContent = fileName;
    });

    document.getElementById('place_image').addEventListener('change', function() {
      const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
      document.getElementById('place_image-name').textContent = fileName;
    });

    (function () {
      const params = new URLSearchParams(window.location.search);
      const id = params.get('id');
      if (!id) { alert('Missing id'); window.location.href='ViewActivities'; return; }

      let editingPlaceId = null;
      const addPlaceBtn = document.querySelector('#addPlaceForm button[type="submit"]');

      // Fill activity form and places list
      fetch('../public/api/activity/get?id=' + encodeURIComponent(id), { credentials: 'same-origin' })
        .then(r => r.json()).then(resp => {
          if (!resp.success) { alert('Not found'); window.location.href='ViewActivities'; return; }
          const a = resp.data;
          document.getElementById('id').value = a.id;
          document.getElementById('title').value = a.title || '';
          document.getElementById('slug').value = a.slug || '';
          document.getElementById('description').value = a.description || '';
          document.getElementById('place_activity_id').value = a.id;

          renderPlaces(a.places || []);
        }).catch(err => { console.error(err); alert('Load error'); });

      // Update activity
      document.getElementById('editActivityForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        fetch('../public/api/activity/update', { method: 'POST', body: fd, credentials: 'same-origin' })
          .then(r => r.json())
          .then(resp => {
            if (resp.success) { 
              showSuccessModal('Activity updated successfully!');
              setTimeout(() => window.location.href='ViewActivities', 1500);
            }
            else alert(JSON.stringify(resp.errors));
          }).catch(() => alert('Network error'));
      });

      // Add or Update place
      document.getElementById('addPlaceForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const fd = new FormData();
        fd.append('activity_id', document.getElementById('place_activity_id').value);
        fd.append('title', document.getElementById('place_title').value);
        fd.append('slug', document.getElementById('place_slug').value);
        fd.append('description', document.getElementById('place_description').value);
        fd.append('location', document.getElementById('place_location').value);
        fd.append('best_time', document.getElementById('place_best_time').value);
        const img = document.getElementById('place_image').files[0];
        if (img) fd.append('image', img);

        if (editingPlaceId) {
          // update
          fd.append('id', editingPlaceId);
          fetch('../public/api/activity/place/update', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(r => r.json()).then(resp => {
              if (resp.success) {
                showSuccessModal('Location updated successfully!');
                resetPlaceForm();
                return fetch('../public/api/activity/get?id=' + encodeURIComponent(document.getElementById('place_activity_id').value), { credentials: 'same-origin' });
              } else {
                throw new Error(JSON.stringify(resp.errors));
              }
            }).then(r => r.json()).then(resp => {
              if (resp.success) renderPlaces(resp.data.places || []);
            }).catch(err => {
              console.error(err);
              alert('Failed to update location');
            });
        } else {
          // create
          fetch('../public/api/activity/place/create', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(r => r.json()).then(resp => {
              if (resp.success) {
                showSuccessModal('Location added successfully!');
                resetPlaceForm();
                // refresh places list
                return fetch('../public/api/activity/get?id=' + encodeURIComponent(document.getElementById('place_activity_id').value), { credentials: 'same-origin' });
              } else {
                throw new Error(JSON.stringify(resp.errors));
              }
            }).then(r => r.json()).then(resp => {
              if (resp.success) renderPlaces(resp.data.places || []);
            }).catch(err => {
              console.error(err);
              alert('Failed to add location');
            });
        }
      });

      // Render places list and attach delete/edit handlers
      function renderPlaces(places) {
        const container = document.getElementById('placesList');
        if (!places || places.length === 0) {
          container.innerHTML = '<div class="empty-places">No locations added yet. Use the form below to add your first location.</div>';
          return;
        }
        container.innerHTML = places.map(p => {
          const baseurl = window.location.origin + '/TravelMate/public/';
          const imgHtml = p.image 
            ? `<div class="place-image"><img src="${baseurl}${p.image}" alt="${escapeHtml(p.title)}"></div>` 
            : '<div class="place-image" style="display:flex;align-items:center;justify-content:center;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>';
          const title = escapeHtml(p.title);
          const desc = escapeHtml(p.description || '');
          const slug = escapeHtml(p.slug || '');
          const location = escapeHtml(p.location || '');
          const bestTime = escapeHtml(p.best_time || '');
          const imgAttr = p.image ? p.image : '';
          return `
          <div class="place-item" data-id="${p.id}"
               data-title="${title}"
               data-slug="${slug}"
               data-description="${desc}"
               data-location="${location}"
               data-best-time="${bestTime}"
               data-image="${imgAttr}">
            ${imgHtml}
            <div class="place-info">
              <h4 class="place-title">${title}</h4>
              <p class="place-desc">${desc || 'No description provided'}</p>
            </div>
            <div class="place-actions">
              <button class="btn-edit-place" data-id="${p.id}">Edit</button>
              <button class="btn-delete-place" data-id="${p.id}">Delete</button>
            </div>
          </div>
        `;
        }).join('');

        // attach delete handlers
        document.querySelectorAll('.btn-delete-place').forEach(btn => {
          btn.addEventListener('click', function () {
            showDeleteConfirmModal(this.dataset.id, this.closest('.place-item'));
          });
        });

        // attach edit handlers
        document.querySelectorAll('.btn-edit-place').forEach(btn => {
          btn.addEventListener('click', function () {
            const item = this.closest('.place-item');
            if (!item) return;
            editingPlaceId = item.dataset.id;
            document.getElementById('place_title').value = item.dataset.title || '';
            document.getElementById('place_slug').value = item.dataset.slug || '';
            document.getElementById('place_description').value = item.dataset.description || '';
            document.getElementById('place_location').value = item.dataset.location || '';
            document.getElementById('place_best_time').value = item.dataset.bestTime || '';
            addPlaceBtn.textContent = 'Update Location';
            const heading = document.getElementById('addPlaceHeading');
            if (heading) heading.textContent = 'Update Location';
            // add a cancel button if not present
            if (!document.getElementById('cancelEditPlace')) {
              const btn = document.createElement('button');
              btn.type = 'button';
              btn.id = 'cancelEditPlace';
              btn.textContent = 'Cancel';
              btn.className = 'btn-secondary';
              btn.style.marginLeft = '8px';
              btn.addEventListener('click', resetPlaceForm);
              document.querySelector('#addPlaceForm div:last-child').appendChild(btn);
            }
            window.scrollTo({ top: document.getElementById('addPlaceForm').offsetTop - 20, behavior: 'smooth' });
          });
        });
      }

      function resetPlaceForm() {
        editingPlaceId = null;
        document.getElementById('place_title').value = '';
        document.getElementById('place_slug').value = '';
        document.getElementById('place_description').value = '';
        document.getElementById('place_location').value = '';
        document.getElementById('place_best_time').value = '';
        document.getElementById('place_image').value = '';
        document.getElementById('place_image-name').textContent = 'No file chosen';
        addPlaceBtn.textContent = 'Add Location';
        const heading = document.getElementById('addPlaceHeading');
        if (heading) heading.textContent = 'Add Location';
        const cancelBtn = document.getElementById('cancelEditPlace');
        if (cancelBtn) cancelBtn.remove();
      }

      function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": "&#039;" }[m]));
      }

    })();
  </script>
</body>
</html>
