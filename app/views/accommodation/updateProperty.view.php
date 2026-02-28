<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Property</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/propertyForm.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  <main class="update-property-page container">
    <h1>Update Property</h1>
    <div id="formRoot">Loading...</div>
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
    if (!id) { root.innerHTML = '<div class="error">No id provided</div>'; return; }

    async function load(){
      try {
        const res = await fetch(getBaseUrl() + '/api/accommodation/get?id=' + encodeURIComponent(id));
        const ct = res.headers.get('content-type') || '';
        if (!ct.includes('application/json')) { root.innerHTML = '<pre>' + await res.text() + '</pre>'; return; }
        const j = await res.json();
        if (!j.success) { root.innerHTML = '<div class="error">Failed to load</div>'; return; }
        renderForm(j.data);
      } catch (e) { console.error(e); root.innerHTML = '<div class="error">Error</div>'; }
    }

    function renderForm(p){
      root.innerHTML = `
  <form id="updateForm" enctype="multipart/form-data">
          <input type="hidden" name="id" value="${p.id}">
          <div class="field"><label>Title</label><input name="title" value="${escapeHtml(p.title||'')}"></div>
          <div class="field"><label>Property Type</label><input name="property_type" value="${escapeHtml(p.property_type||'')}"></div>
          <div class="field"><label>Description</label><textarea name="description">${escapeHtml(p.description||'')}</textarea></div>
          
          <div class="field"><label>Rooms</label><input name="rooms" type="number" value="${p.rooms||0}"></div>
          <div class="field"><label>Bathrooms</label><input name="bathrooms" type="number" value="${p.bathrooms||0}"></div>
          <div class="field"><label>Max Guests</label><input name="max_guests" type="number" value="${p.max_guests||0}"></div>
          
          <div class="field"><label>Smoking</label>
            <input type="hidden" name="smoking" value="0">
            <input name="smoking" type="checkbox" value="1" ${p.smoking ? 'checked' : ''}>
          </div>
          <div class="field"><label>Parties</label>
            <input type="hidden" name="parties" value="0">
            <input name="parties" type="checkbox" value="1" ${p.parties ? 'checked' : ''}>
          </div>
          <div class="field"><label>Pets</label>
            <select name="pets">
              <option value="no" ${p.pets==='no' ? 'selected':''}>No</option>
              <option value="yes" ${p.pets==='yes' ? 'selected':''}>Yes</option>
            </select>
          </div>
          <div class="field"><label>Check-in start</label><input name="check_in_start" type="time" value="${p.check_in_start || ''}"></div>
          <div class="field"><label>Check-in end</label><input name="check_in_end" type="time" value="${p.check_in_end || ''}"></div>
          <div class="field"><label>Check-out time</label><input name="check_out_time" type="time" value="${p.check_out_time || ''}"></div>
          <div class="field"><label>Status</label>
            <select name="status">
              <option value="active" ${p.status==='active' ? 'selected':''}>Active</option>
              <option value="inactive" ${p.status==='inactive' ? 'selected':''}>Inactive</option>
            </select>
          </div>

          <fieldset>
            <legend>Images</legend>
            <div id="existingImages">
              ${ (p.images && p.images.length) ? p.images.map(img => `
                <div class="existing-image" style="display:flex;align-items:center;margin-bottom:8px;">
                  <img src="${getBaseUrl()}/${escapeHtml(img.image_path)}" style="width:100px;height:70px;object-fit:cover;border-radius:6px;margin-right:8px;">
                  <div>
                    <label><input type="radio" name="main_image_id" value="${img.id}" ${img.is_main == 1 ? 'checked' : ''}> Main</label>
                    <br>
                    <label><input type="checkbox" name="delete_images[]" value="${img.id}"> Delete</label>
                  </div>
                </div>
              `).join('') : '<div>No images</div>' }
            </div>
            <div class="field"><label>Upload new images</label><input type="file" name="images[]" multiple accept="image/*"></div>
            <div class="field"><label><input type="checkbox" name="make_new_main" value="1"> Make first uploaded new image the main image</label></div>
          </fieldset>
          <div class="field">
            <button type="submit" class="btn">Update</button>
            <a class="btn btn-link" href="${getBaseUrl()}/detailsProperty?id=${p.id}">Cancel</a>
          </div>
        </form>
      `;

      document.getElementById('updateForm').addEventListener('submit', async function(e){
        e.preventDefault();
        const fd = new FormData(this);
        try {
          const res = await fetch(getBaseUrl() + '/api/accommodation/update', { method: 'POST', body: fd });
          const ct = res.headers.get('content-type') || '';
          if (ct.includes('application/json')){
            const j = await res.json();
            if (j.success) { alert('Updated'); window.location.href = getBaseUrl() + '/detailsProperty?id=' + encodeURIComponent(p.id); return; }
            alert('Update failed: ' + (j.errors || 'unknown'));
          } else { const t = await res.text(); console.error('Non-json update:', t); alert('Update failed'); }
        } catch (err) { console.error(err); alert('Update failed'); }
      });
    }

    function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }

    load();
  })();
  </script>
</body>
</html>
