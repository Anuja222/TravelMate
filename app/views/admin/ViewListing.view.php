<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Popular Destinations - TravelMate Admin</title>
  <link rel="stylesheet" href="assets/css/Admin/ViewListing.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-container">

    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <!-- Page Header -->
      <div class="page-title">
        <div class="title-section">
          <h1><i class="fas fa-map-marked-alt"></i> Popular Destinations</h1>
          <p class="subtitle">Manage and showcase top travel destinations</p>
        </div>
        <button id="btnCreate" class="btn-primary" onclick="window.location.href='createDestination'">
          <i class="fas fa-plus"></i> Create Destination
        </button>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <input type="text" id="searchInput" placeholder="Search destinations..." />
        <select id="statusFilter">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="pending">Pending</option>
          <option value="inactive">Inactive</option>
        </select>
        <select id="regionFilter">
          <option value="">All Regions</option>
          <option value="western">Western Province</option>
          <option value="central">Central Province</option>
          <option value="southern">Southern Province</option>
          <option value="northern">Northern Province</option>
          <option value="eastern">Eastern Province</option>
        </select>
        <button id="btnApplyFilter">
          <i class="fas fa-filter"></i> Apply Filters
        </button>
      </div>

      <!-- Statistics Summary -->
      <div class="stats-summary">
        <div class="stat-card">
          <div class="stat-icon" style="background: #1abc5b;">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number" id="activeCount">0</div>
            <div class="stat-label">Active</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #f39c12;">
            <i class="fas fa-clock"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number" id="pendingCount">0</div>
            <div class="stat-label">Pending</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #e74c3c;">
            <i class="fas fa-ban"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number" id="inactiveCount">0</div>
            <div class="stat-label">Inactive</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #3498db;">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number" id="totalCount">0</div>
            <div class="stat-label">Total Destinations</div>
          </div>
        </div>
      </div>

      <!-- Destinations Grid -->
      <div id="destList" class="content-grid">
        <div class="loading-container">
          <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: #1abc5b;"></i>
          <p>Loading destinations...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div id="emptyState" class="empty-state" style="display: none;">
        <i class="fas fa-map-marked-alt"></i>
        <h3>No Destinations Found</h3>
        <p>Start by creating your first destination</p>
        <button class="btn-primary" onclick="window.location.href='createDestination'">
          <i class="fas fa-plus"></i> Create First Destination
        </button>
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
        <p>Are you sure you want to delete this destination?</p>
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

  <script src="../public/assets/js/destinations.js"></script>
  
  <script>
    // Enhanced functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Modal handling
      const modal = document.getElementById('deleteModal');
      const closeBtn = document.querySelector('.close');
      const cancelBtn = document.getElementById('btnCancelDelete');
      
      if (closeBtn) {
        closeBtn.onclick = function() {
          modal.style.display = 'none';
        }
      }
      
      if (cancelBtn) {
        cancelBtn.onclick = function() {
          modal.style.display = 'none';
        }
      }
      
      window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = 'none';
        }
      }
      
      // Filter functionality
      const applyFilterBtn = document.getElementById('btnApplyFilter');
      if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
          const searchValue = document.getElementById('searchInput').value;
          const statusValue = document.getElementById('statusFilter').value;
          const regionValue = document.getElementById('regionFilter').value;
          
          // Call filter function from destinations.js
          if (typeof filterDestinations === 'function') {
            filterDestinations(searchValue, statusValue, regionValue);
          }
        });
      }
      
      // Search on enter key
      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            applyFilterBtn.click();
          }
        });
      }
      
      // Update stats animation
      function animateValue(element, start, end, duration) {
        if (!element) return;
        
        let startTimestamp = null;
        const step = (timestamp) => {
          if (!startTimestamp) startTimestamp = timestamp;
          const progress = Math.min((timestamp - startTimestamp) / duration, 1);
          element.textContent = Math.floor(progress * (end - start) + start);
          if (progress < 1) {
            window.requestAnimationFrame(step);
          }
        };
        window.requestAnimationFrame(step);
      }
      
      // Example: Update stats (replace with actual data)
      setTimeout(() => {
        animateValue(document.getElementById('activeCount'), 0, 12, 1000);
        animateValue(document.getElementById('pendingCount'), 0, 3, 1000);
        animateValue(document.getElementById('inactiveCount'), 0, 2, 1000);
        animateValue(document.getElementById('totalCount'), 0, 17, 1000);
      }, 500);
    });
  </script>
</body>
</html>