<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Property</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/updateProperty.css">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  <main class="update-property-page">
    <h1><i class="fas fa-edit"></i> Update Property</h1>
    <div id="formRoot">
      <div class="loading-state">
        <i class="fas fa-spinner fa-spin"></i>
        <p>Loading property details...</p>
      </div>
    </div>
  </main>
  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
  (function(){
    function getBaseUrl(){
      const path = window.location.pathname;
      const parts = path.split('/');
      const publicIndex = parts.indexOf('public');
      if (publicIndex !== -1) return parts.slice(0, publicIndex + 1).join('/');
      return '/TravelMate/public';
    }

    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    const root = document.getElementById('formRoot');
    if (!id) { 
      root.innerHTML = '<div class="error-state"><i class="fas fa-exclamation-triangle"></i><p>No property ID provided</p></div>'; 
      return; 
    }

    async function load(){
      try {
        const res = await fetch(getBaseUrl() + '/api/accommodation/get?id=' + encodeURIComponent(id));
        const ct = res.headers.get('content-type') || '';
        if (!ct.includes('application/json')) { 
          root.innerHTML = '<div class="error-state"><i class="fas fa-exclamation-triangle"></i><p>Invalid response from server</p></div>'; 
          return; 
        }
        const j = await res.json();
        if (!j.success) { 
          root.innerHTML = '<div class="error-state"><i class="fas fa-exclamation-triangle"></i><p>Failed to load property</p></div>'; 
          return; 
        }
        renderForm(j.data);
      } catch (e) { 
        console.error(e); 
        root.innerHTML = '<div class="error-state"><i class="fas fa-exclamation-triangle"></i><p>Error loading property</p></div>'; 
      }
    }

    function renderForm(p){
      root.innerHTML = `
        <form id="updateForm" class="property-form-container" enctype="multipart/form-data">
          <input type="hidden" name="id" value="${p.id}">
          
          <!-- Basic Information -->
          <div class="form-section">
            <div class="form-section-header">
              <div class="form-section-icon"><i class="fas fa-info-circle"></i></div>
              <h2>Basic Information</h2>
            </div>
            <div class="form-grid">
              <div class="form-field full-width">
                <label><i class="fas fa-heading"></i> Property Title</label>
                <input type="text" name="title" value="${escapeHtml(p.title||'')}" required placeholder="e.g., Cozy Beach Villa with Ocean View">
              </div>
              <div class="form-field">
                <label><i class="fas fa-home"></i> Property Type</label>
                <select name="property_type" required>
                  <option value="">Select Type</option>
                  <option value="villa" ${p.property_type==='villa'?'selected':''}>Villa</option>
                  <option value="apartment" ${p.property_type==='apartment'?'selected':''}>Apartment</option>
                  <option value="house" ${p.property_type==='house'?'selected':''}>House</option>
                  <option value="hotel" ${p.property_type==='hotel'?'selected':''}>Hotel</option>
                  <option value="resort" ${p.property_type==='resort'?'selected':''}>Resort</option>
                  <option value="guesthouse" ${p.property_type==='guesthouse'?'selected':''}>Guesthouse</option>
                  <option value="bungalow" ${p.property_type==='bungalow'?'selected':''}>Bungalow</option>
                  <option value="cottage" ${p.property_type==='cottage'?'selected':''}>Cottage</option>
                </select>
              </div>
              <div class="form-field">
                <label><i class="fas fa-map-marker-alt"></i> Location</label>
                <input type="text" name="location" value="${escapeHtml(p.location||'')}" placeholder="e.g., Colombo, Sri Lanka">
              </div>
              <div class="form-field full-width">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" required placeholder="Describe your property, amenities, and what makes it special...">${escapeHtml(p.description||'')}</textarea>
              </div>
            </div>
          </div>

          <!-- Property Details -->
          <div class="form-section">
            <div class="form-section-header">
              <div class="form-section-icon"><i class="fas fa-bed"></i></div>
              <h2>Property Details</h2>
            </div>
            <div class="form-grid">
              <div class="form-field">
                <label><i class="fas fa-door-closed"></i> Number of Rooms</label>
                <input type="number" name="rooms" value="${p.rooms||0}" min="0" required>
              </div>
              <div class="form-field">
                <label><i class="fas fa-bath"></i> Number of Bathrooms</label>
                <input type="number" name="bathrooms" value="${p.bathrooms||0}" min="0" required>
              </div>
              <div class="form-field">
                <label><i class="fas fa-users"></i> Maximum Guests</label>
                <input type="number" name="max_guests" value="${p.max_guests||0}" min="1" required>
              </div>
              <div class="form-field">
                <label><i class="fas fa-dollar-sign"></i> Price Per Night (LKR)</label>
                <input type="number" name="price_per_night" value="${p.price_per_night||0}" min="0" step="0.01" placeholder="e.g., 15000">
              </div>
            </div>
          </div>

          <!-- House Rules -->
          <div class="form-section">
            <div class="form-section-header">
              <div class="form-section-icon"><i class="fas fa-list-ul"></i></div>
              <h2>House Rules</h2>
            </div>
            <div class="form-grid">
              <div class="form-field">
                <div class="checkbox-field">
                  <input type="hidden" name="smoking" value="0">
                  <input type="checkbox" name="smoking" id="smoking" value="1" ${p.smoking ? 'checked' : ''}>
                  <label for="smoking"><i class="fas fa-smoking"></i> Smoking Allowed</label>
                </div>
              </div>
              <div class="form-field">
                <div class="checkbox-field">
                  <input type="hidden" name="parties" value="0">
                  <input type="checkbox" name="parties" id="parties" value="1" ${p.parties ? 'checked' : ''}>
                  <label for="parties"><i class="fas fa-glass-cheers"></i> Parties/Events Allowed</label>
                </div>
              </div>
              <div class="form-field full-width">
                <label><i class="fas fa-paw"></i> Pets Policy</label>
                <div class="radio-group">
                  <div class="radio-option">
                    <input type="radio" name="pets" id="pets-no" value="no" ${p.pets==='no' || !p.pets ? 'checked' : ''}>
                    <label for="pets-no">No Pets</label>
                  </div>
                  <div class="radio-option">
                    <input type="radio" name="pets" id="pets-yes" value="yes" ${p.pets==='yes' ? 'checked' : ''}>
                    <label for="pets-yes">Pets Allowed</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Check-in/Check-out Times -->
          <div class="form-section">
            <div class="form-section-header">
              <div class="form-section-icon"><i class="fas fa-clock"></i></div>
              <h2>Check-in & Check-out Times</h2>
            </div>
            <div class="form-grid">
              <div class="form-field">
                <label><i class="fas fa-sign-in-alt"></i> Check-in Start Time</label>
                <input type="time" name="check_in_start" value="${p.check_in_start || ''}">
              </div>
              <div class="form-field">
                <label><i class="fas fa-clock"></i> Check-in End Time</label>
                <input type="time" name="check_in_end" value="${p.check_in_end || ''}">
              </div>
              <div class="form-field">
                <label><i class="fas fa-sign-out-alt"></i> Check-out Time</label>
                <input type="time" name="check_out_time" value="${p.check_out_time || ''}">
              </div>
              <div class="form-field">
                <label><i class="fas fa-toggle-on"></i> Status</label>
                <select name="status">
                  <option value="active" ${p.status==='active' ? 'selected':''}>Active</option>
                  <option value="inactive" ${p.status==='inactive' ? 'selected':''}>Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Images -->
          <div class="form-section">
            <div class="form-section-header">
              <div class="form-section-icon"><i class="fas fa-images"></i></div>
              <h2>Property Images</h2>
            </div>
            <div class="images-section">
              ${(p.images && p.images.length) ? `
                <div class="existing-images-grid">
                  ${p.images.map(img => `
                    <div class="image-card">
                      <img src="${getBaseUrl()}/${escapeHtml(img.image_path)}" alt="Property image">
                      <div class="image-card-controls">
                        ${img.is_main == 1 ? '<div class="main-image-badge"><i class="fas fa-star"></i> Main Image</div>' : ''}
                        <div class="image-control-row">
                          <label class="radio-label">
                            <input type="radio" name="main_image_id" value="${img.id}" ${img.is_main == 1 ? 'checked' : ''}>
                            <span>Set as Main</span>
                          </label>
                          <label class="checkbox-label">
                            <input type="checkbox" name="delete_images[]" value="${img.id}">
                            <span style="color: #e74c3c;"><i class="fas fa-trash"></i> Delete</span>
                          </label>
                        </div>
                      </div>
                    </div>
                  `).join('')}
                </div>
              ` : '<p style="text-align: center; color: #999; padding: 20px;"><i class="fas fa-image" style="font-size: 48px; display: block; margin-bottom: 12px;"></i>No images yet</p>'}
              
              <div class="file-upload-area" onclick="document.getElementById('imageUpload').click()">
                <label class="upload-label">
                  <i class="fas fa-cloud-upload-alt"></i>
                  <p><strong>Click to upload new images</strong></p>
                  <p>or drag and drop</p>
                  <p style="font-size: 12px; color: #999;">PNG, JPG, or JPEG (Max 5MB each)</p>
                  <input type="file" id="imageUpload" name="images[]" multiple accept="image/*">
                </label>
              </div>
              
              <div class="checkbox-field" style="margin-top: 16px;">
                <input type="checkbox" name="make_new_main" id="make_new_main" value="1">
                <label for="make_new_main"><i class="fas fa-star"></i> Make first uploaded image the main image</label>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Update Property
            </button>
            <a href="${getBaseUrl()}/index.php?url=Accomodation_provider/newerDashboard" class="btn btn-secondary">
              <i class="fas fa-times"></i> Cancel
            </a>
          </div>
        </form>
      `;

      // File upload preview
      const fileInput = document.getElementById('imageUpload');
      const uploadArea = document.querySelector('.file-upload-area');
      
      fileInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
          uploadArea.style.borderColor = '#1abc5b';
          uploadArea.style.background = '#e8f5e9';
          uploadArea.querySelector('p strong').textContent = `${files.length} file(s) selected`;
        }
      });

      // Prevent default drag behaviors
      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
      });

      function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
      }

      // Highlight drop area when dragging over it
      ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
          uploadArea.style.borderColor = '#16a085';
          uploadArea.style.background = '#d4f4dd';
        }, false);
      });

      ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
          uploadArea.style.borderColor = '#1abc5b';
          uploadArea.style.background = '#fff';
        }, false);
      });

      // Handle dropped files
      uploadArea.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        
        if (files.length > 0) {
          uploadArea.querySelector('p strong').textContent = `${files.length} file(s) selected`;
        }
      }, false);

      // Form submission
      document.getElementById('updateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
        
        const fd = new FormData(this);
        try {
          const res = await fetch(getBaseUrl() + '/api/accommodation/update', { method: 'POST', body: fd });
          const ct = res.headers.get('content-type') || '';
          if (ct.includes('application/json')){
            const j = await res.json();
            if (j.success) { 
              alert('✅ Property updated successfully!'); 
              window.location.href = getBaseUrl() + '/index.php?url=Accomodation_provider/newerDashboard';
              return; 
            }
            alert('❌ Update failed: ' + (j.errors || 'unknown'));
          } else { 
            const t = await res.text(); 
            console.error('Non-json update:', t); 
            alert('❌ Update failed. Please try again.'); 
          }
        } catch (err) { 
          console.error(err); 
          alert('❌ Update failed. Please check your connection and try again.'); 
        } finally {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        }
      });
    }

    function escapeHtml(s){ 
      return String(s).replace(/[&<>"']/g, function(c){ 
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; 
      }); 
    }

    load();
  })();
  </script>
</body>
</html>
