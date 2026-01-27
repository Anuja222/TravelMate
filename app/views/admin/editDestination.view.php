<!DOCTYPE html>
<html>

<head>
  <title>Edit Destination</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/editDestination.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

  <div class="page-containerr">
    <div class="content">
      <h1>Edit Destination</h1>

      <form id="editDestForm">
        <input type="hidden" name="id" id="id">
        <div>
          <label>Title</label>
          <input name="title" id="title" required>
        </div>
        <div>
          <label>Slug</label>
          <input name="slug" id="slug">
        </div>
        <div>
          <label>Description</label>
          <textarea name="description" id="description"></textarea>
        </div>
        <div>
          <label>Image (upload to replace)</label>
          <input type="file" name="image" id="image">
        </div>
        <div>
          <button type="submit" onclick="window.location.href='ViewListing'" class="btn-primary">Update</button>
          <button type="button" onclick="window.location.href='ViewListing'">Cancel</button>
        </div>
      </form>

      <!-- Places (Beaches) management -->
      <hr />
      <section id="placesSection">
        <h2>Places / Beaches</h2>

        <div id="placesList">
          <p>Loading places...</p>
        </div>

        <h3 id="addPlaceHeading">Add Place</h3>
        <form id="addPlaceForm">
          <input type="hidden" name="destination_id" id="place_destination_id">
          <div>
            <label>Title</label>
            <input name="title" id="place_title" required>
          </div>
          <div>
            <label>Slug (optional)</label>
            <input name="slug" id="place_slug">
          </div>
          <div>
            <label>Description</label>
            <textarea name="description" id="place_description"></textarea>
          </div>
          <div>
            <label>Image</label>
            <input type="file" name="image" id="place_image">
          </div>
          <div>
            <button type="submit" class="btn-primary">Add Place</button>
          </div>
        </form>
      </section>

    </div>
  </div>


  <script>
    (function () {
      const params = new URLSearchParams(window.location.search);
      const id = params.get('id');
      if (!id) { alert('Missing id'); window.location.href = 'destinations'; return; }

      let editingPlaceId = null;
      const addPlaceBtn = document.querySelector('#addPlaceForm button[type="submit"]');

      // Fill destination form and places list
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

      // Update destination
      document.getElementById('editDestForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        fetch('../public/api/destination/update', { method: 'POST', body: fd, credentials: 'same-origin' })
          .then(r => r.json())
          .then(resp => {
            if (resp.success) { alert('Updated'); location.reload(); }
            else alert(JSON.stringify(resp.errors));
          }).catch(() => alert('Network error'));
      });

      // Add or Update place
      document.getElementById('addPlaceForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const fd = new FormData();
        fd.append('destination_id', document.getElementById('place_destination_id').value);
        fd.append('title', document.getElementById('place_title').value);
        fd.append('slug', document.getElementById('place_slug').value);
        fd.append('description', document.getElementById('place_description').value);
        const img = document.getElementById('place_image').files[0];
        if (img) fd.append('image', img);

        if (editingPlaceId) {
          // update
          fd.append('id', editingPlaceId);
          fetch('../public/api/destination/place/update', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(r => r.json()).then(resp => {
              if (resp.success) {
                alert('Place updated');
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
                alert('Place added');
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

      // Render places list and attach delete/edit handlers
      function renderPlaces(places) {
        const container = document.getElementById('placesList');
        if (!places || places.length === 0) {
          container.innerHTML = '<p>No places yet.</p>';
          return;
        }
        container.innerHTML = places.map(p => {
          const baseurl = window.location.origin + '/TravelMate/public/';
          const img = p.image ? ('<img src="' + baseurl + p.image + '" style="max-width:160px;height:90px;object-fit:cover;border-radius:4px;">') : '';
          const title = escapeHtml(p.title);
          const desc = escapeHtml(p.description || '');
          const slug = escapeHtml(p.slug || '');
          const imgAttr = p.image ? p.image : '';
          return `
          <div class="place-item" data-id="${p.id}"
               data-title="${title}"
               data-slug="${slug}"
               data-description="${desc}"
               data-image="${imgAttr}"
               style="display:flex;align-items:center;margin-bottom:12px;">
            <div style="margin-right:12px">${img}</div>
            <div style="flex:1">
              <strong>${title}</strong><br>
              <small>${desc}</small>
            </div>
            <div>
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
                  alert('Deleted');
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
        document.getElementById('place_image').value = '';
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
