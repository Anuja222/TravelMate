<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listings Management - TravelMate Admin</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/ViewListing.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

  <div class="page-container">

    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <!-- Page Header -->
      <div class="page-title">
        <div class="title-section">
          <h1><i class="fas fa-list-alt"></i> Listings Management</h1>
          <p class="subtitle">Manage destinations, accommodations, and vehicles</p>
        </div>
        <button id="btnCreate" class="btn-primary" onclick="window.location.href='createDestination'">
          <i class="fas fa-plus"></i> Create Destination
        </button>
      </div>

      <!-- Tabs for listing types -->
      <div class="listing-tabs">
        <button class="tab-btn active" data-tab="destinations">
          <i class="fas fa-map-marked-alt"></i> Destinations (<?php echo $stats['destinations'] ?? 0; ?>)
        </button>
        <button class="tab-btn" data-tab="accommodations">
          <i class="fas fa-hotel"></i> Accommodations (<?php echo $stats['accommodations'] ?? 0; ?>)
        </button>
        <button class="tab-btn" data-tab="vehicles">
          <i class="fas fa-car"></i> Vehicles (<?php echo $stats['vehicles'] ?? 0; ?>)
        </button>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <input type="text" id="searchInput" placeholder="Search listings..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" />
        <select id="statusFilter">
          <option value="">All Status</option>
          <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
          <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
          <option value="suspended" <?php echo ($_GET['status'] ?? '') === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
        </select>
        <button id="btnApplyFilter">
          <i class="fas fa-filter"></i> Apply Filters
        </button>
      </div>

      <!-- Statistics Summary -->
      <div class="stats-summary">
        <div class="stat-card">
          <div class="stat-icon" style="background: #3498db;">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number"><?php echo $stats['destinations'] ?? 0; ?></div>
            <div class="stat-label">Destinations</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #1abc5b;">
            <i class="fas fa-hotel"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number"><?php echo $stats['accommodations'] ?? 0; ?></div>
            <div class="stat-label">Accommodations</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #f39c12;">
            <i class="fas fa-car"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number"><?php echo $stats['vehicles'] ?? 0; ?></div>
            <div class="stat-label">Vehicles</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #9b59b6;">
            <i class="fas fa-layer-group"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
            <div class="stat-label">Total Listings</div>
          </div>
        </div>
      </div>

      <!-- Destinations Tab Content -->
      <div id="tab-destinations" class="tab-content active">
        <div class="content-grid">
          <?php if (isset($destinations) && count($destinations) > 0): ?>
            <?php foreach ($destinations as $dest): ?>
              <div class="content-card" data-id="<?php echo $dest->id; ?>" data-type="destination">
                <div class="card-image">
                  <img src="<?php echo $dest->image ? 'uploads/destinations/' . $dest->image : 'assets/images/placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($dest->title); ?>">
                  <span class="card-badge active">Active</span>
                </div>
                <div class="card-body">
                  <h3><?php echo htmlspecialchars($dest->title); ?></h3>
                  <p><?php echo htmlspecialchars(substr($dest->description ?? '', 0, 100)); ?>...</p>
                  <div class="card-meta">
                    <span><i class="fas fa-map-pin"></i> <?php echo $dest->places_count ?? 0; ?> places</span>
                    <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($dest->created_at)); ?></span>
                  </div>
                </div>
                <div class="card-actions">
                  <button class="btn-view" onclick="window.location.href='editDestination?id=<?php echo $dest->id; ?>'">
                    <i class="fas fa-edit"></i> Edit
                  </button>
                  <button class="btn-delete" onclick="deleteListing('destination', <?php echo $dest->id; ?>, '<?php echo addslashes($dest->title); ?>')">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">
              <i class="fas fa-map-marked-alt"></i>
              <h3>No Destinations Found</h3>
              <p>Start by creating your first destination</p>
              <button class="btn-primary" onclick="window.location.href='createDestination'">
                <i class="fas fa-plus"></i> Create Destination
              </button>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Accommodations Tab Content -->
      <div id="tab-accommodations" class="tab-content" style="display: none;">
        <div class="content-grid">
          <?php if (isset($accommodations) && count($accommodations) > 0): ?>
            <?php foreach ($accommodations as $acc): ?>
              <div class="content-card" data-id="<?php echo $acc->id; ?>" data-type="accommodation">
                <div class="card-image">
                  <img src="<?= ROOT ?>/assets/images/placeholder.jpg" alt="<?php echo htmlspecialchars($acc->title); ?>">
                  <span class="card-badge <?php echo $acc->status ?? 'active'; ?>"><?php echo ucfirst($acc->status ?? 'Active'); ?></span>
                </div>
                <div class="card-body">
                  <h3><?php echo htmlspecialchars($acc->title); ?></h3>
                  <p class="owner-info">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars(($acc->first_name ?? '') . ' ' . ($acc->last_name ?? '')); ?>
                  </p>
                  <p><?php echo htmlspecialchars(substr($acc->description ?? '', 0, 80)); ?>...</p>
                  <div class="card-meta">
                    <span><i class="fas fa-bed"></i> <?php echo $acc->rooms ?? 0; ?> rooms</span>
                    <span><i class="fas fa-users"></i> <?php echo $acc->max_guests ?? 0; ?> guests</span>
                    <span><i class="fas fa-bookmark"></i> <?php echo $acc->bookings_count ?? 0; ?> bookings</span>
                  </div>
                </div>
                <div class="card-actions">
                  <button class="btn-view" onclick="window.location.href='viewHotel?id=<?php echo $acc->id; ?>'">
                    <i class="fas fa-eye"></i> View
                  </button>
                  <?php if (($acc->status ?? 'active') === 'active'): ?>
                    <button class="btn-suspend" onclick="suspendListing('accommodation', <?php echo $acc->id; ?>)">
                      <i class="fas fa-pause"></i> Suspend
                    </button>
                  <?php else: ?>
                    <button class="btn-activate" onclick="approveListing('accommodation', <?php echo $acc->id; ?>)">
                      <i class="fas fa-check"></i> Activate
                    </button>
                  <?php endif; ?>
                  <button class="btn-delete" onclick="deleteListing('accommodation', <?php echo $acc->id; ?>, '<?php echo addslashes($acc->title); ?>')">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">
              <i class="fas fa-hotel"></i>
              <h3>No Accommodations Found</h3>
              <p>Accommodations will appear here when providers create them</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Vehicles Tab Content -->
      <div id="tab-vehicles" class="tab-content" style="display: none;">
        <div class="content-grid">
          <?php if (isset($vehicles) && count($vehicles) > 0): ?>
            <?php foreach ($vehicles as $vehicle): ?>
              <div class="content-card" data-id="<?php echo $vehicle->id; ?>" data-type="vehicle">
                <div class="card-image">
                  <img src="<?= ROOT ?>/assets/images/placeholder.jpg" alt="<?php echo htmlspecialchars($vehicle->model ?? ''); ?>">
                  <span class="card-badge <?php echo $vehicle->status ?? 'active'; ?>"><?php echo ucfirst($vehicle->status ?? 'Active'); ?></span>
                </div>
                <div class="card-body">
                  <h3><?php echo htmlspecialchars($vehicle->vehicle_type ?? 'Vehicle'); ?> - <?php echo htmlspecialchars($vehicle->model ?? ''); ?></h3>
                  <p class="owner-info">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars(($vehicle->first_name ?? '') . ' ' . ($vehicle->last_name ?? '')); ?>
                  </p>
                  <div class="card-meta">
                    <span><i class="fas fa-car"></i> <?php echo htmlspecialchars($vehicle->vehicle_type ?? 'N/A'); ?></span>
                    <span><i class="fas fa-users"></i> <?php echo $vehicle->seats ?? 0; ?> seats</span>
                    <span><i class="fas fa-bookmark"></i> <?php echo $vehicle->bookings_count ?? 0; ?> bookings</span>
                  </div>
                </div>
                <div class="card-actions">
                  <button class="btn-view" onclick="window.location.href='viewVehicle?id=<?php echo $vehicle->id; ?>'">
                    <i class="fas fa-eye"></i> View
                  </button>
                  <?php if (($vehicle->status ?? 'active') === 'active'): ?>
                    <button class="btn-suspend" onclick="suspendListing('vehicle', <?php echo $vehicle->id; ?>)">
                      <i class="fas fa-pause"></i> Suspend
                    </button>
                  <?php else: ?>
                    <button class="btn-activate" onclick="approveListing('vehicle', <?php echo $vehicle->id; ?>)">
                      <i class="fas fa-check"></i> Activate
                    </button>
                  <?php endif; ?>
                  <button class="btn-delete" onclick="deleteListing('vehicle', <?php echo $vehicle->id; ?>, '<?php echo addslashes($vehicle->model ?? ''); ?>')">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">
              <i class="fas fa-car"></i>
              <h3>No Vehicles Found</h3>
              <p>Vehicles will appear here when transporters register them</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
        <span class="close">&times;</span>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete "<span id="deleteItemName"></span>"?</p>
        <p class="warning"><i class="fas fa-info-circle"></i> This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" id="btnCancelDelete">Cancel</button>
        <button class="btn-danger" id="btnConfirmDelete">
          <i class="fas fa-trash"></i> Delete
        </button>
      </div>
    </div>
  </div>

  <style>
    .listing-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      border-bottom: 2px solid #e0e0e0;
      padding-bottom: 0;
    }
    .tab-btn {
      padding: 12px 24px;
      border: none;
      background: none;
      cursor: pointer;
      font-size: 14px;
      color: #666;
      border-bottom: 3px solid transparent;
      margin-bottom: -2px;
      transition: all 0.3s;
    }
    .tab-btn:hover {
      color: #1abc5b;
    }
    .tab-btn.active {
      color: #1abc5b;
      border-bottom-color: #1abc5b;
      font-weight: 600;
    }
    .tab-btn i {
      margin-right: 8px;
    }
    .owner-info {
      color: #666;
      font-size: 13px;
      margin-bottom: 8px;
    }
    .btn-suspend {
      background: #f39c12;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn-activate {
      background: #1abc5b;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>

  <script>
    let deleteType = null;
    let deleteId = null;

    // Tab functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        // Remove active from all tabs and contents
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        
        // Activate clicked tab
        this.classList.add('active');
        const tabId = 'tab-' + this.dataset.tab;
        document.getElementById(tabId).style.display = 'block';
      });
    });

    // Delete functionality
    function deleteListing(type, id, name) {
      deleteType = type;
      deleteId = id;
      document.getElementById('deleteItemName').textContent = name;
      document.getElementById('deleteModal').style.display = 'block';
    }

    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
      if (deleteType && deleteId) {
        fetch('/api/admin/listing/delete', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({type: deleteType, id: deleteId})
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Listing deleted successfully');
            location.reload();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to delete listing');
        });
      }
      document.getElementById('deleteModal').style.display = 'none';
    });

    // Suspend functionality
    function suspendListing(type, id) {
      if (confirm('Suspend this listing?')) {
        fetch('/api/admin/listing/suspend', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({type: type, id: id})
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Listing suspended successfully');
            location.reload();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to suspend listing');
        });
      }
    }

    // Approve functionality
    function approveListing(type, id) {
      if (confirm('Activate this listing?')) {
        fetch('/api/admin/listing/approve', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({type: type, id: id})
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Listing activated successfully');
            location.reload();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to activate listing');
        });
      }
    }

    // Modal close handlers
    document.querySelector('.close').onclick = function() {
      document.getElementById('deleteModal').style.display = 'none';
    }
    document.getElementById('btnCancelDelete').onclick = function() {
      document.getElementById('deleteModal').style.display = 'none';
    }
    window.onclick = function(event) {
      if (event.target == document.getElementById('deleteModal')) {
        document.getElementById('deleteModal').style.display = 'none';
      }
    }

    // Filter functionality
    document.getElementById('btnApplyFilter').addEventListener('click', function() {
      const search = document.getElementById('searchInput').value;
      const status = document.getElementById('statusFilter').value;
      window.location.href = 'ViewListing?search=' + encodeURIComponent(search) + '&status=' + status;
    });
  </script>
</body>
</html>
