<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
// Details page for a single property. Expects ?id={id}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Property Details</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/propertyDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <main class="property-details-page">
        <div id="propertyRoot" class="container">
            <div class="loading">Loading property...</div>
        </div>
    </main>

    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script>
        (function () {
            function getBaseUrl() {
                const path = window.location.pathname;
                const parts = path.split('/');
                const publicIndex = parts.indexOf('public');
                if (publicIndex !== -1) return parts.slice(0, publicIndex + 1).join('/');
                return '/TravelMate/public';
            }

            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            const root = document.getElementById('propertyRoot');

            if (!id) {
                root.innerHTML = '<div class="error">No property id specified.</div>';
                return;
            }

            async function load() {
                try {
                    const res = await fetch(getBaseUrl() + '/api/accommodation/get?id=' + encodeURIComponent(id));
                    const ct = res.headers.get('content-type') || '';
                    if (!ct.includes('application/json')) {
                        const t = await res.text();
                        root.innerHTML = '<pre>' + t + '</pre>';
                        return;
                    }
                    const json = await res.json();
                    if (!json.success) {
                        root.innerHTML = '<div class="error">Failed to load property: ' + (json.errors || 'unknown') + '</div>';
                        return;
                    }
                    renderProperty(json.data);
                } catch (err) {
                    console.error(err);
                    root.innerHTML = '<div class="error">Error loading property. Check console.</div>';
                }
            }

            function renderProperty(p) {
                const images = p.images || [];
                const base = getBaseUrl();
                const main = images.length ? (base + '/' + (p.main_image || images[0].image_path)) : base + '/assets/images/default-property.jpg';
                const price = (p.price_per_night !== undefined && p.price_per_night !== null) ? (parseFloat(p.price_per_night).toFixed(2)) : '';
                const created = p.created_at ? new Date(p.created_at).toLocaleString() : '';

                const html = `
          <!-- Hero Section -->
          <section class="property-hero" style="background-image:url('${main}')">
            <div class="hero-overlay">
              <h1>${escapeHtml(p.title || 'Property Details')}</h1>
              <p class="muted">
                <i class="fa-solid fa-map-marker-alt"></i> ${escapeHtml(p.location || 'Unknown Location')} &nbsp;•&nbsp;
                ${escapeHtml(p.property_type || 'Type Unknown')}
              </p>
            </div>
          </section>

          <!-- Main Property Grid -->
          <section class="property-grid">
            <div class="left">
              
              <!-- Gallery -->
              <section class="gallery">
                ${images.length ? images.map(img => `
                  <img src='${base}/${escapeHtml(img.image_path)}' alt='Property image'>
                `).join('') : `<img src='${base}/assets/images/default-property.jpg' alt='Default property'>`}
              </section>

              <!-- Description -->
              <section class="description">
                <h3><i class="fa-solid fa-align-left"></i> Description</h3>
                <p>${escapeHtml(p.description || 'No description available.')}</p>

                <h4><i class="fa-solid fa-circle-info"></i> Property Details</h4>
                <ul>
                  <li><strong>Rooms:</strong> ${p.rooms || 0}</li>
                  <li><strong>Bathrooms:</strong> ${p.bathrooms || 0}</li>
                  <li><strong>Guests:</strong> ${p.max_guests || 0}</li>
                  <li><strong>Smoking:</strong> ${p.smoking ? 'Allowed' : 'Not allowed'}</li>
                  <li><strong>Parties:</strong> ${p.parties ? 'Allowed' : 'Not allowed'}</li>
                  <li><strong>Pets:</strong> ${escapeHtml(p.pets || 'No')}</li>
                  <li><strong>Check-in:</strong> ${escapeHtml(p.check_in_start || '')}</li>
                  <li><strong>Check-out:</strong> ${escapeHtml(p.check_out_time || '')}</li>
                </ul>

                
              </section>
            </div>

            <!-- Right Sidebar -->
            <aside class="right">
              <div class="meta">
                <div><i class="fa-solid fa-signal"></i> Status: ${escapeHtml(p.status || '')}</div>
                <div><i class="fa-solid fa-calendar-days"></i> Created: ${escapeHtml(created)}</div>
              </div>

              <div class="actions">
                <button id="btnEdit" class="btn"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                <button id="btnDelete" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                <a href="${getBaseUrl()}/ac_dashboard" class="btn btn-link"><i class="fa-solid fa-arrow-left"></i> Back to Listings</a>
              </div>
            </aside>
          </section>
        `;

                root.innerHTML = html;

                // Event Listeners
                document.getElementById('btnEdit').addEventListener('click', function () {
                    window.location.href = getBaseUrl() + '/updateProperty?id=' + encodeURIComponent(p.id);
                });
                document.getElementById('btnDelete').addEventListener('click', async function () {
                    if (!confirm('Delete this property?')) return;
                    try {
                        const res = await fetch(getBaseUrl() + '/api/accommodation/delete', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(p.id)
                        });
                        const ct = res.headers.get('content-type') || '';
                        if (ct.includes('application/json')) {
                            const j = await res.json();
                            if (j.success) {
                                alert('Property deleted successfully.');
                                window.location.href = getBaseUrl() + '/ac_dashboard';
                                return;
                            }
                            alert('Delete failed.');
                        } else {
                            alert('Delete failed. See console.');
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Delete failed.');
                    }
                });
            }

            function escapeHtml(s) {
                return String(s).replace(/[&<>"']/g, function (c) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
                });
            }

            load();
        })();
    </script>
</body>

</html>
