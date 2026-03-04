<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

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
    <title>Property Details</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/dashboard.css">
    <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/propertyDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      .details-layout main {
        padding-top: 6em;
        max-width: 1300px;
      }

      .details-layout .dashboard-content {
        flex: 1;
        margin-top: 1em;
        margin-bottom: 90px;
      }

      .property-shell {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #edf1f5;
        box-shadow: 0 8px 26px rgba(44, 62, 80, 0.08);
        overflow: hidden;
      }

      .hero-banner {
        position: relative;
        height: 280px;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: flex-end;
      }

      .hero-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.2) 15%, rgba(0, 0, 0, 0.7) 100%);
      }

      .hero-content {
        position: relative;
        z-index: 1;
        width: 100%;
        padding: 24px 28px;
        color: #fff;
      }

      .hero-content h1 {
        margin: 0 0 8px 0;
        font-size: 32px;
        line-height: 1.2;
      }

      .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 14px;
        opacity: .95;
      }

      .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .2px;
        text-transform: uppercase;
      }

      .status-pill.active {
        color: #065f46;
        background: #d1fae5;
      }

      .status-pill.pending {
        color: #92400e;
        background: #fef3c7;
      }

      .status-pill.inactive {
        color: #7f1d1d;
        background: #fee2e2;
      }

      .details-body {
        padding: 24px;
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 22px;
        background: #f8fafc;
      }

      .panel {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 14px;
        padding: 18px;
      }

      .panel h3 {
        margin: 0 0 14px 0;
        font-size: 18px;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .panel h3 i {
        color: #1abc5b;
      }

      .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
      }

      .gallery-grid img {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e8edf2;
      }

      .desc-text {
        margin: 0;
        color: #4b5563;
        line-height: 1.7;
        font-size: 14px;
      }

      .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
      }

      .detail-item {
        border: 1px solid #e9edf2;
        background: #fafcfd;
        border-radius: 10px;
        padding: 10px 12px;
      }

      .detail-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 4px;
      }

      .detail-value {
        color: #1f2937;
        font-weight: 600;
        font-size: 14px;
        word-break: break-word;
      }

      .amenities-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
      }

      .amenity-pill {
        background: #ecfdf3;
        border: 1px solid #c9f2da;
        color: #166534;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 600;
      }

      .action-stack {
        display: flex;
        flex-direction: column;
        gap: 10px;
      }

      .action-stack .btn,
      .action-stack .btn-link {
        width: 100%;
        justify-content: center;
        text-decoration: none;
      }

      .action-stack .btn {
        border-radius: 10px;
        border: none;
        font-weight: 700;
        transition: all .25s ease;
      }

      .action-stack #btnEdit {
        background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(26, 188, 91, 0.22);
      }

      .action-stack #btnEdit:hover {
        background: linear-gradient(135deg, #16a085 0%, #1abc5b 100%);
        box-shadow: 0 6px 16px rgba(26, 188, 91, 0.32);
        transform: translateY(-2px);
      }

      .action-stack #btnDelete {
        background: #fff;
        color: #e74c3c;
        border: 2px solid #e74c3c;
      }

      .action-stack #btnDelete:hover {
        background: #e74c3c;
        color: #fff;
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
        transform: translateY(-2px);
      }

      .action-stack .btn-link {
        background: #fff;
        border: 1px solid #dce4ea;
        color: #374151;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
      }

      .action-stack .btn-link:hover {
        border-color: #1abc5b;
        color: #1abc5b;
        background: #f0fbf5;
      }

      .loading,
      .error {
        background: #fff;
        border: 1px solid #e7edf2;
        border-radius: 12px;
        padding: 18px;
        color: #475569;
      }

      @media (max-width: 1024px) {
        .details-body {
          grid-template-columns: 1fr;
        }
      }

      @media (max-width: 768px) {
        .details-layout .sidebar {
          display: none;
        }

        .details-layout main {
          padding-top: 5em;
        }

        .hero-content h1 {
          font-size: 26px;
        }

        .detail-grid {
          grid-template-columns: 1fr;
        }
      }
    </style>
</head>

  <body class="details-layout">
    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

    <main>
      <aside class="sidebar">
        <ul>
          <li><a href="ac_dashboard">Dashboard</a></li>
          <li><a href="ac_bookings">Bookings</a></li>
          <li><a href="acc_setting">Settings</a></li>
        </ul>
      </aside>

      <section class="dashboard-content">
        <div id="propertyRoot" class="container">
          <div class="loading">Loading property...</div>
        </div>
      </section>
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

            function toYesNo(value) {
                if (value === null || value === undefined) return 'No';
                if (String(value) === '1' || String(value).toLowerCase() === 'yes' || value === true) return 'Yes';
                return 'No';
            }

            function parseAmenities(rawAmenities) {
                if (Array.isArray(rawAmenities)) {
                    return rawAmenities;
                }

                if (typeof rawAmenities === 'string' && rawAmenities.trim() !== '') {
                    try {
                        const parsed = JSON.parse(rawAmenities);
                        if (Array.isArray(parsed)) {
                            return parsed;
                        }
                    } catch (error) {
                        return rawAmenities.split(',').map(item => item.trim()).filter(Boolean);
                    }
                }

                return [];
            }

            function formatAmenityLabel(value) {
                return String(value || '').replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
            }

            function formatDate(value) {
                if (!value) return 'N/A';
                const d = new Date(value);
                return Number.isNaN(d.getTime()) ? escapeHtml(String(value)) : d.toLocaleString();
            }

            function renderProperty(p) {
                const images = p.images || [];
                const base = getBaseUrl();
                const main = images.length ? (base + '/' + (p.main_image || images[0].image_path)) : base + '/assets/images/default-property.jpg';
                const priceNight = (p.price_per_night !== undefined && p.price_per_night !== null) ? parseFloat(p.price_per_night).toLocaleString('en-US') : '0';
                const priceGuest = (p.price_per_guest !== undefined && p.price_per_guest !== null) ? parseFloat(p.price_per_guest).toLocaleString('en-US') : '0';
                const amenities = parseAmenities(p.amenities);
                const statusClass = String(p.status || '').toLowerCase();

                const html = `
          <section class="property-shell">
            <div class="hero-banner" style="background-image:url('${main}')">
              <div class="hero-content">
                <h1>${escapeHtml(p.title || 'Property Details')}</h1>
                <div class="hero-meta">
                  <span><i class="fa-solid fa-location-dot"></i> ${escapeHtml(p.location || 'Unknown Location')}</span>
                  <span><i class="fa-solid fa-layer-group"></i> ${escapeHtml(p.property_type || 'Type Unknown')}</span>
                  <span class="status-pill ${statusClass}"><i class="fa-solid fa-circle"></i> ${escapeHtml(p.status || 'unknown')}</span>
                </div>
              </div>
            </div>

            <div class="details-body">
              <div>
                <div class="panel">
                  <h3><i class="fa-regular fa-image"></i> Property Images</h3>
                  <div class="gallery-grid">
                    ${images.length ? images.map(img => `
                      <img src='${base}/${escapeHtml(img.image_path)}' alt='Property image'>
                    `).join('') : `<img src='${base}/assets/images/default-property.jpg' alt='Default property'>`}
                  </div>
                </div>

                <div class="panel">
                  <h3><i class="fa-solid fa-align-left"></i> Description</h3>
                  <p class="desc-text">${escapeHtml(p.description || 'No description available.')}</p>
                </div>

                <div class="panel">
                  <h3><i class="fa-solid fa-circle-info"></i> Full Property Details</h3>
                  <div class="detail-grid">
                    <div class="detail-item"><div class="detail-label">Title</div><div class="detail-value">${escapeHtml(p.title || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Property Type</div><div class="detail-value">${escapeHtml(p.property_type || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Location</div><div class="detail-value">${escapeHtml(p.location || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Address</div><div class="detail-value">${escapeHtml(p.address || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Rooms</div><div class="detail-value">${escapeHtml(p.rooms ?? '0')}</div></div>
                    <div class="detail-item"><div class="detail-label">Bathrooms</div><div class="detail-value">${escapeHtml(p.bathrooms ?? '0')}</div></div>
                    <div class="detail-item"><div class="detail-label">Max Guests</div><div class="detail-value">${escapeHtml(p.max_guests ?? '0')}</div></div>
                    <div class="detail-item"><div class="detail-label">Children Allowed</div><div class="detail-value">${toYesNo(p.children_allowed)}</div></div>
                    <div class="detail-item"><div class="detail-label">Smoking</div><div class="detail-value">${toYesNo(p.smoking)}</div></div>
                    <div class="detail-item"><div class="detail-label">Parties</div><div class="detail-value">${toYesNo(p.parties)}</div></div>
                    <div class="detail-item"><div class="detail-label">Pets</div><div class="detail-value">${escapeHtml(p.pets || 'No')}</div></div>
                    <div class="detail-item"><div class="detail-label">Breakfast</div><div class="detail-value">${toYesNo(p.breakfast)}</div></div>
                    <div class="detail-item"><div class="detail-label">Parking</div><div class="detail-value">${toYesNo(p.parking)}</div></div>
                    <div class="detail-item"><div class="detail-label">Check-in Start</div><div class="detail-value">${escapeHtml(p.check_in_start || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Check-in End</div><div class="detail-value">${escapeHtml(p.check_in_end || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Check-out Time</div><div class="detail-value">${escapeHtml(p.check_out_time || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Price per Night</div><div class="detail-value">LKR ${priceNight}</div></div>
                    <div class="detail-item"><div class="detail-label">Price per Guest</div><div class="detail-value">LKR ${priceGuest}</div></div>
                    <div class="detail-item"><div class="detail-label">Contact Name</div><div class="detail-value">${escapeHtml(p.contact_name || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Contact Phone</div><div class="detail-value">${escapeHtml(p.contact_phone || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Contact Email</div><div class="detail-value">${escapeHtml(p.contact_email || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Status</div><div class="detail-value">${escapeHtml(p.status || 'N/A')}</div></div>
                    <div class="detail-item"><div class="detail-label">Created At</div><div class="detail-value">${formatDate(p.created_at)}</div></div>
                    <div class="detail-item"><div class="detail-label">Updated At</div><div class="detail-value">${formatDate(p.updated_at)}</div></div>
                  </div>
                </div>
              </div>

              <div>
                <div class="panel">
                  <h3><i class="fa-solid fa-list-check"></i> Amenities</h3>
                  <div class="amenities-wrap">
                    ${amenities.length ? amenities.map(item => `<span class="amenity-pill">${escapeHtml(formatAmenityLabel(item))}</span>`).join('') : '<span class="detail-value">No amenities listed</span>'}
                  </div>
                </div>

                <div class="panel">
                  <h3><i class="fa-solid fa-gears"></i> Actions</h3>
                  <div class="action-stack">
                    <button id="btnEdit" class="btn"><i class="fa-solid fa-pen-to-square"></i> Edit Property</button>
                    <button id="btnDelete" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete Property</button>
                  </div>
                </div>
              </div>
            </div>
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
