<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$role = $isLoggedIn ? ($_SESSION['user']['role'] ?? $_SESSION['role'] ?? '') : '';

if (!$isLoggedIn || $role !== 'accommodation') {
  if ($role === 'admin') {
    header('Location: ad_dashboard');
    exit;
  } elseif ($role === 'transport') {
    header('Location: tr_dashboard');
    exit;
  } else {
    header('Location: homet');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Property</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/dashboard.css">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/propertyForm.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .update-layout main {
      padding-top: 6em;
    }

    .update-layout .dashboard-content {
      width: 100%;
    }

    .update-layout .update-property-page {
      max-width: 980px;
    }

    .update-layout #formRoot {
      border-radius: 16px;
    }

    .update-layout .form-section {
      background: linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
      border: 1px solid #edf1f4;
      border-radius: 14px;
      padding: 20px;
      margin-bottom: 18px;
    }

    .update-layout .form-section-title {
      margin-bottom: 18px;
    }

    .update-layout .field-row {
      gap: 16px;
    }

    .update-layout .field {
      margin-bottom: 14px;
    }

    .update-header-row {
      display: flex;
      align-items: center;
      margin-bottom: 12px;
    }

    .existing-image {
      display: grid;
      grid-template-columns: 120px 1fr;
      align-items: center;
      gap: 14px;
      padding: 12px;
      border: 1px solid #e7edf2;
      border-radius: 12px;
      margin-bottom: 12px;
      background: #ffffff;
      box-shadow: 0 2px 10px rgba(44, 62, 80, 0.04);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .existing-image:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(44, 62, 80, 0.08);
    }

    .existing-image img {
      width: 120px;
      height: 84px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #e8ecef;
      flex-shrink: 0;
    }

    .existing-image-options {
      display: flex;
      flex-direction: column;
      gap: 10px;
      font-size: 14px;
      color: #2c3e50;
    }

    .existing-image-options label {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      width: fit-content;
      padding: 6px 10px;
      border: 1px solid #e4e9ee;
      border-radius: 8px;
      background: #f8fafb;
      transition: all 0.2s ease;
    }

    .existing-image-options label:hover {
      border-color: #bfe7cf;
      background: #f2fbf6;
    }

    .existing-image-options input[type="radio"],
    .existing-image-options input[type="checkbox"] {
      accent-color: #1abc5b;
      transform: scale(1.05);
    }

    .existing-image-empty {
      padding: 10px;
      border: 1px dashed #d4dbe0;
      border-radius: 10px;
      color: #7b8794;
      text-align: center;
    }

    .amenities-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 10px;
      margin-top: 6px;
    }

    .amenity-item {
      display: flex;
      align-items: center;
      gap: 8px;
      border: 1px solid #e8ecef;
      border-radius: 8px;
      padding: 8px 10px;
      background: #fafcfd;
      font-size: 13px;
      color: #34495e;
    }

    .amenity-item input {
      accent-color: #1abc5b;
    }

    .update-layout .field input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="hidden"]),
    .update-layout .field textarea,
    .update-layout .field select {
      width: 100%;
      padding: 14px 16px;
      border-radius: 12px;
      border: 1px solid #d9dde3;
      background: #f3f5f7;
      font-size: 16px;
      line-height: 1.2;
      color: #2c3e50;
      box-sizing: border-box;
      transition: all 0.25s ease;
    }

    .update-layout .field input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="hidden"]):focus,
    .update-layout .field textarea:focus,
    .update-layout .field select:focus {
      outline: none;
      border-color: #2ecc71;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.12);
    }

    .update-layout .field input[type="file"] {
      width: 100%;
      border: 1px dashed #cfd8df;
      border-radius: 10px;
      padding: 10px;
      background: #f8fafb;
      color: #4b5563;
      box-sizing: border-box;
    }

    .update-layout .field input[type="file"]::file-selector-button {
      border: none;
      border-radius: 8px;
      background: #1abc5b;
      color: #fff;
      padding: 8px 12px;
      margin-right: 10px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.2s ease;
    }

    .update-layout .field input[type="file"]::file-selector-button:hover {
      background: #16a085;
    }

    .update-layout .field.field-checkbox {
      min-height: 48px;
      border: 1px solid #e6ebef;
      border-radius: 10px;
      padding: 10px 12px;
      background: #f9fbfc;
    }

    .update-layout .field.field-checkbox label {
      font-weight: 600;
      margin: 0;
      color: #34495e;
    }

    .update-layout .field.field-checkbox input[type="checkbox"] {
      accent-color: #1abc5b;
    }

    @media (max-width: 768px) {
      .update-layout main {
        padding-top: 5em;
      }

      .update-layout .sidebar {
        display: none;
      }

      .update-layout .update-property-page {
        margin-top: 0;
      }
    }
  </style>
</head>
<body class="update-layout">
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <main>
    <aside class="sidebar">
      <ul>
        <li><a href="ac_dashboard">Dashboard</a></li>
        <li><a href="ac_bookings">Bookings</a></li>
        <li><a href="acc_setting">Settings</a></li>
      </ul>
    </aside>

    <section class="dashboard-content update-property-page container">
      <div class="update-header-row">
        <h1>Update Property</h1>
      </div>
      <p class="subtitle">Edit your property details, images, and listing preferences.</p>
      <div id="formRoot" class="loading">Loading...</div>
    </section>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
  (function(){
    const AMENITY_OPTIONS = [
      'wifi', 'ac', 'tv', 'kitchen', 'parking', 'pool',
      'gym', 'breakfast', 'hot_water', 'washing_machine', 'workspace', 'pet_friendly'
    ];

    function getBaseUrl(){
      const path = window.location.pathname;
      const parts = path.split('/');
      const publicIndex = parts.indexOf('public');
      if (publicIndex !== -1) return parts.slice(0, publicIndex + 1).join('/');
      return '/TravelMate/public';
    }

    function normalizeAmenities(rawAmenities) {
      if (Array.isArray(rawAmenities)) {
        return rawAmenities.map(item => String(item).trim()).filter(Boolean);
      }
      if (typeof rawAmenities === 'string' && rawAmenities.trim() !== '') {
        try {
          const parsed = JSON.parse(rawAmenities);
          if (Array.isArray(parsed)) {
            return parsed.map(item => String(item).trim()).filter(Boolean);
          }
        } catch (err) {
          return rawAmenities.split(',').map(item => item.trim()).filter(Boolean);
        }
      }
      return [];
    }

    function formatAmenityLabel(value) {
      return value.replace(/_/g, ' ').replace(/\b\w/g, m => m.toUpperCase());
    }

    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    const root = document.getElementById('formRoot');
    if (!id) { root.innerHTML = '<div class="error">No property id provided.</div>'; return; }

    async function load(){
      try {
        const res = await fetch(getBaseUrl() + '/api/accommodation/get?id=' + encodeURIComponent(id));
        const ct = res.headers.get('content-type') || '';
        if (!ct.includes('application/json')) { root.innerHTML = '<div class="error">Unexpected server response.</div>'; return; }
        const j = await res.json();
        if (!j.success) { root.innerHTML = '<div class="error">Failed to load property details.</div>'; return; }
        renderForm(j.data);
      } catch (e) { console.error(e); root.innerHTML = '<div class="error">Error loading property details.</div>'; }
    }

    function renderForm(p){
      const selectedAmenities = normalizeAmenities(p.amenities);

      root.innerHTML = `
  <form id="updateForm" enctype="multipart/form-data">
          <input type="hidden" name="id" value="${p.id}">
          <section class="form-section">
            <h3 class="form-section-title"><i class="fa-solid fa-house"></i> Basic Information</h3>
            <div class="field-row">
              <div class="field"><label><i class="fa-solid fa-heading"></i>Title</label><input name="title" value="${escapeHtml(p.title||'')}"></div>
              <div class="field"><label><i class="fa-solid fa-layer-group"></i>Property Type</label><input name="property_type" value="${escapeHtml(p.property_type||'')}"></div>
            </div>
            <div class="field-row">
              <div class="field"><label><i class="fa-solid fa-location-dot"></i>Location</label><input name="location" value="${escapeHtml(p.location||'')}"></div>
              <div class="field"><label><i class="fa-solid fa-map"></i>Address</label><input name="address" value="${escapeHtml(p.address||'')}"></div>
            </div>
            <div class="field"><label><i class="fa-solid fa-align-left"></i>Description</label><textarea name="description">${escapeHtml(p.description||'')}</textarea></div>
          </section>
          
          <section class="form-section">
            <h3 class="form-section-title"><i class="fa-solid fa-bed"></i> Capacity & House Rules</h3>
            <div class="field-row">
              <div class="field"><label><i class="fa-solid fa-door-open"></i>Rooms</label><input name="rooms" type="number" min="0" value="${p.rooms||0}"></div>
              <div class="field"><label><i class="fa-solid fa-bath"></i>Bathrooms</label><input name="bathrooms" type="number" min="0" value="${p.bathrooms||0}"></div>
              <div class="field"><label><i class="fa-solid fa-users"></i>Max Guests</label><input name="max_guests" type="number" min="1" value="${p.max_guests||0}"></div>
            </div>

            <div class="field-row">
              <div class="field"><label><i class="fa-solid fa-child"></i>Children Allowed</label><select name="children_allowed"><option value="1" ${String((p.children_allowed ?? 1)) === '1' ? 'selected' : ''}>Yes</option><option value="0" ${String((p.children_allowed ?? 1)) === '0' ? 'selected' : ''}>No</option></select></div>
              <div class="field"><label><i class="fa-solid fa-utensils"></i>Breakfast</label><select name="breakfast"><option value="yes" ${p.breakfast==='yes' ? 'selected':''}>Yes</option><option value="no" ${p.breakfast==='no' ? 'selected':''}>No</option></select></div>
              <div class="field"><label><i class="fa-solid fa-square-parking"></i>Parking</label><select name="parking"><option value="yes" ${p.parking==='yes' ? 'selected':''}>Yes</option><option value="no" ${p.parking==='no' ? 'selected':''}>No</option></select></div>
            </div>
          
            <div class="field-row">
              <div class="field field-checkbox"><input type="hidden" name="smoking" value="0"><input id="smoking" name="smoking" type="checkbox" value="1" ${p.smoking ? 'checked' : ''}><label for="smoking">Smoking allowed</label></div>
              <div class="field field-checkbox"><input type="hidden" name="parties" value="0"><input id="parties" name="parties" type="checkbox" value="1" ${p.parties ? 'checked' : ''}><label for="parties">Parties allowed</label></div>
              <div class="field"><label><i class="fa-solid fa-paw"></i>Pets</label><select name="pets"><option value="no" ${p.pets==='no' ? 'selected':''}>No</option><option value="yes" ${p.pets==='yes' ? 'selected':''}>Yes</option></select></div>
            </div>

            <div class="field-row">
              <div class="field"><label><i class="fa-regular fa-clock"></i>Check-in start</label><input name="check_in_start" type="time" value="${p.check_in_start || ''}"></div>
              <div class="field"><label><i class="fa-regular fa-clock"></i>Check-in end</label><input name="check_in_end" type="time" value="${p.check_in_end || ''}"></div>
              <div class="field"><label><i class="fa-regular fa-clock"></i>Check-out time</label><input name="check_out_time" type="time" value="${p.check_out_time || ''}"></div>
            </div>

            <div class="field"><label><i class="fa-solid fa-signal"></i>Status</label><select name="status"><option value="active" ${p.status==='active' ? 'selected':''}>Active</option><option value="inactive" ${p.status==='inactive' ? 'selected':''}>Inactive</option></select></div>
          </section>

          <section class="form-section">
            <h3 class="form-section-title"><i class="fa-solid fa-receipt"></i> Pricing & Contact</h3>
            <div class="field-row">
              <div class="field"><label><i class="fa-solid fa-money-bill-wave"></i>Price per night (LKR)</label><input name="price_per_night" type="number" min="0" step="0.01" value="${p.price_per_night || 0}"></div>
              <div class="field"><label><i class="fa-solid fa-user-tag"></i>Price per guest (LKR)</label><input name="price_per_guest" type="number" min="0" step="0.01" value="${p.price_per_guest || 0}"></div>
            </div>
            <div class="field-row">
              <div class="field"><label><i class="fa-solid fa-user"></i>Contact name</label><input name="contact_name" value="${escapeHtml(p.contact_name||'')}"></div>
              <div class="field"><label><i class="fa-solid fa-phone"></i>Contact phone</label><input name="contact_phone" value="${escapeHtml(p.contact_phone||'')}"></div>
              <div class="field"><label><i class="fa-solid fa-envelope"></i>Contact email</label><input name="contact_email" type="text" value="${escapeHtml(p.contact_email||'')}"></div>
            </div>
          </section>

          <section class="form-section">
            <h3 class="form-section-title"><i class="fa-solid fa-list-check"></i> Amenities</h3>
            <div class="amenities-grid">
              ${AMENITY_OPTIONS.map(amenity => `
                <label class="amenity-item">
                  <input type="checkbox" name="amenities[]" value="${amenity}" ${selectedAmenities.includes(amenity) ? 'checked' : ''}>
                  <span>${formatAmenityLabel(amenity)}</span>
                </label>
              `).join('')}
            </div>
          </section>

          <section class="form-section">
            <h3 class="form-section-title"><i class="fa-regular fa-image"></i> Images</h3>
            <div id="existingImages">
              ${ (p.images && p.images.length) ? p.images.map(img => `
                <div class="existing-image">
                  <img src="${getBaseUrl()}/${escapeHtml(img.image_path)}" alt="Property image">
                  <div class="existing-image-options">
                    <label><input type="radio" name="main_image_id" value="${img.id}" ${img.is_main == 1 ? 'checked' : ''}> Main</label>
                    <label><input type="checkbox" name="delete_images[]" value="${img.id}"> Delete</label>
                  </div>
                </div>
              `).join('') : '<div class="existing-image-empty">No images uploaded yet.</div>' }
            </div>
            <div class="field"><label><i class="fa-solid fa-upload"></i>Upload new images</label><input type="file" name="images[]" multiple accept="image/*"></div>
            <div class="field field-checkbox"><input id="makeMain" type="checkbox" name="make_new_main" value="1"><label for="makeMain">Make first uploaded image the main image</label></div>
          </section>

          <div class="field button-group">
            <button type="submit" class="btn"><i class="fa-solid fa-floppy-disk"></i> Update Property</button>
            <a class="btn btn-link" href="${getBaseUrl()}/detailsProperty?id=${p.id}"><i class="fa-solid fa-xmark"></i> Cancel</a>
          </div>
        </form>
      `;

      document.getElementById('updateForm').addEventListener('submit', async function(e){
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalSubmit = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
        }
        const fd = new FormData(this);
        try {
          const res = await fetch(getBaseUrl() + '/api/accommodation/update', { method: 'POST', body: fd });
          const ct = res.headers.get('content-type') || '';
          if (ct.includes('application/json')){
            const j = await res.json();
            if (j.success) { window.location.href = getBaseUrl() + '/detailsProperty?id=' + encodeURIComponent(p.id); return; }
            alert('Update failed: ' + (j.errors || 'unknown'));
          } else { const t = await res.text(); console.error('Non-json update:', t); alert('Update failed'); }
        } catch (err) { console.error(err); alert('Update failed'); }
        finally {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalSubmit;
          }
        }
      });
    }

    function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }

    load();
  })();
  </script>
</body>
</html>
