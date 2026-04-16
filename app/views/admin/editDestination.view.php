<!DOCTYPE html>
<html>

<head>
  <title>Edit Destination</title>
  <link rel="stylesheet" href="assets/css/Admin/editDestination.css">
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
            <h1>Edit Destination</h1>
            <p class="page-subtitle">Update destination details and manage places</p>
          </div>
        </div>
      </div>

      <div class="form-card">
        <h2 class="section-title">Destination Information</h2>
        <form id="editDestForm">
          <input type="hidden" name="id" id="id">
          
          <div class="form-group">
            <label class="form-label">Title</label>
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
              Update Destination
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

      <!-- places (Beaches) management -->
      <div class="form-card" style="margin-top: 32px;">
        <h2 class="section-title">Places / Beaches</h2>
        
        <div id="placesList" class="places-grid">
          <p style="color: #666;">Loading places...</p>
        </div>

        <div class="divider"></div>

        <h3 class="subsection-title" id="addPlaceHeading">Add Place</h3>
        <form id="addPlaceForm">
          <input type="hidden" name="destination_id" id="place_destination_id">
          
          <div class="form-group">
            <label class="form-label">Title</label>
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
              Add Place
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <!-- success Modal -->
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
    // success Modal Functions
    function showSuccessModal(message) {
      document.getElementById('successMessage').textContent = message;
      document.getElementById('successModal').style.display = 'flex';
    }

    function closeSuccessModal() {
      document.getElementById('successModal').style.display = 'none';
    }

    // file input handlers - show filename when selected
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
      if (!id) { alert('Missing id'); window.location.href = 'destinations'; return; }

      let editingPlaceId = null;
      const addPlaceBtn = document.querySelector('#addPlaceForm button[type="submit"]');

      // fill destination form and places list
      fetch('../public/api/destination/get?id=' + encodeURIComponent(id), { credentials: 'same-origin' })
        .then(r => r.json()).then(resp => {
          if (!resp.success) { alert('Not found'); window.location.href = 'destinations'; return; }
          const d = resp.data;
          document.getElementById('id').value = d.id;
          document.getElementById('title').value = d.title || '';
          document.getElementById('slug').value = d.slug || '';
          document.getElementById('description').value = d.description || '';
          document.getElementById('place_destination_id').value = d.id;

          renderPlaces(d.places || []);
        }).catch(err => { console.error(err); alert('Load error'); });

      // update destination
      document.getElementById('editDestForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        fetch('../public/api/destination/update', { method: 'POST', body: fd, credentials: 'same-origin' })
          .then(r => r.json())
          .then(resp => {
            if (resp.success) { 
              showSuccessModal('Destination updated successfully!');
              setTimeout(() => window.location.href = 'ViewListing', 1500);
            }
            else alert(JSON.stringify(resp.errors));
          }).catch(() => alert('Network error'));
      });

      // add or Update place
      document.getElementById('addPlaceForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const fd = new FormData();
        fd.append('destination_id', document.getElementById('place_destination_id').value);
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
          fetch('../public/api/destination/place/update', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(r => r.json()).then(resp => {
              if (resp.success) {
                showSuccessModal('Place updated successfully!');
                resetPlaceForm();
                return fetch('../public/api/destination/get?id=' + encodeURIComponent(document.getElementById('place_destination_id').value), { credentials: 'same-origin' });
              } else {
                throw new Error(JSON.stringify(resp.errors));
              }
            }).then(r => r.json()).then(resp => {
              if (resp.success) renderPlaces(resp.data.places || []);
            }).catch(err => {
              console.error(err);
              alert('Failed to update place');
            });
        } else {
          // create
          fetch('../public/api/destination/place/create', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(r => r.json()).then(resp => {
              if (resp.success) {
                showSuccessModal('Place added successfully!');
                resetPlaceForm();
                // refresh places list
                return fetch('../public/api/destination/get?id=' + encodeURIComponent(document.getElementById('place_destination_id').value), { credentials: 'same-origin' });
              } else {
                throw new Error(JSON.stringify(resp.errors));
              }
            }).then(r => r.json()).then(resp => {
              if (resp.success) renderPlaces(resp.data.places || []);
            }).catch(err => {
              console.error(err);
              alert('Failed to add place');
            });
        }
      });

      // render places list and attach delete/edit handlers
      function renderPlaces(places) {
        const container = document.getElementById('placesList');
        if (!places || places.length === 0) {
          container.innerHTML = '<div class="empty-places">No places added yet. Use the form below to add your first place.</div>';
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
            if (!confirm('Delete this place?')) return;
            const fd = new FormData();
            fd.append('id', this.dataset.id);
            fetch('../public/api/destination/place/delete', { method: 'POST', body: fd, credentials: 'same-origin' })
              .then(r => r.json()).then(resp => {
                if (resp.success) {
                  showSuccessModal('Place deleted successfully!');
                  // remove from DOM
                  this.closest('.place-item').remove();
                } else alert('Delete failed');
              }).catch(() => alert('Network error'));
          });
        });

        // attach edit handlers (inline edit)
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
            // image cannot be prefilled into file input; show preview or leave as-is
            addPlaceBtn.textContent = 'Update Place';
            const heading = document.getElementById('addPlaceHeading');
            if (heading) heading.textContent = 'Update Place';
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
        addPlaceBtn.textContent = 'Add Place';
        const heading = document.getElementById('addPlaceHeading');
        if (heading) heading.textContent = 'Add Place';
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