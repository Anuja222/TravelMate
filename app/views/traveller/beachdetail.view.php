</html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Destination Details</title>
  <!-- <link rel="stylesheet" href="assets/css/Traveller/usermain.css"> -->
  <link rel="stylesheet" href="assets/css/Traveller/beach.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <main class="main-content">
    <section class="destination-detail">
      <div class="container">
        <div id="destinationInfo">
          <p>Loading destination...</p>
        </div>

        <h2>Places / Beaches</h2>
        <div id="placesContainer" class="beaches-grid">
          <p>Loading places...</p>
        </div>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
  (function(){
    const params = new URLSearchParams(window.location.search);
    const destId = params.get('dest');
    const infoEl = document.getElementById('destinationInfo');
    const placesEl = document.getElementById('placesContainer');

    if (!destId) {
      infoEl.innerHTML = '<p>Destination id missing.</p>';
      placesEl.innerHTML = '';
      return;
    }

    // helper to build absolute URL for uploaded images
    function buildUrl(path) {
      if (!path) return '';
      if (path.startsWith('http')) return path;
      // adjust base if your public is at /TravelMate/public
      const origin = window.location.origin;
      // try to detect '/public' segment in current path
      const p = window.location.pathname;
      const i = p.indexOf('/public');
      const base = i !== -1 ? origin + p.slice(0, i + 7) : origin + '/TravelMate/public';
      return path.startsWith('/') ? base + path : base + '/' + path;
    }

    fetch('../public/api/destination/get?id=' + encodeURIComponent(destId), { credentials: 'same-origin' })
      .then(r => r.json())
      .then(resp => {
        if (!resp.success) {
          infoEl.innerHTML = '<p>Destination not found.</p>';
          placesEl.innerHTML = '';
          return;
        }

        const d = resp.data;
        const imgUrl = buildUrl(d.image || '');
        infoEl.innerHTML = `
          <div class="detail-header" style="display:flex;gap:20px;align-items:flex-start;margin-bottom:20px;">
            ${imgUrl ? `<img src="${imgUrl}" alt="${(d.title||'')}" style="width:360px;height:220px;object-fit:cover;border-radius:8px;">` : ''}
            <div>
              <h1 style="margin:0 0 8px 0;">${escapeHtml(d.title || '')}</h1>
              <p style="margin:0 0 12px 0;">${escapeHtml(d.description || '')}</p>
              <p style="margin:0 0 12px 0;">${escapeHtml(d.slug || '')}</p>
              <a class="see-all-btn" href="favdestination">See all destinations</a>
            </div>
          </div>
        `;

        const places = d.places || [];
        if (!places.length) {
          placesEl.innerHTML = '<p>No places added for this destination yet.</p>';
          return;
        }

        placesEl.innerHTML = places.map(p => {
          const pImg = buildUrl(p.image || '');
          return `
            <div class="card" style="max-width:420px;">
              <div class="card-image">
                ${pImg ? `<img src="${pImg}" alt="${escapeHtml(p.title)}" style="height:180px;object-fit:cover;">` : ''}
                <div class="card-overlay">
                  <a class="explore-btn" href="beachdetail?dest=${destId}&place=${p.id}">View Place</a>
                </div>
              </div>
              <div class="card-content">
                <h3>${escapeHtml(p.title)}</h3>
                <p>${escapeHtml((p.description||'').substring(0,160))}</p>
                <p>${escapeHtml((p.slug||'').substring(0,160))}</p>
              </div>
            </div>
          `;
        }).join('');

      }).catch(err => {
        console.error(err);
        infoEl.innerHTML = '<p>Error loading destination.</p>';
        placesEl.innerHTML = '';
      });

    function escapeHtml(text) {
      if (!text) return '';
      return String(text).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#039;"}[m]));
    }

  })();
  </script>
</body>
</html>