<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accommodations - TravelMate Admin</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/ViewAccommodations.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-container">

    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <!-- Page Header -->
      <div class="page-title">
        <div class="page-title-content">
          <div class="page-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>Accommodations</h1>
            <p class="page-subtitle">Manage and monitor all property listings</p>
          </div>
        </div>
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
            <i class="fas fa-times-circle"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number" id="inactiveCount">0</div>
            <div class="stat-label">Inactive</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #3498db;">
            <i class="fas fa-home"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number" id="totalCount">0</div>
            <div class="stat-label">Total</div>
          </div>
        </div>
      </div>

      <!-- Pending Accommodations Section -->
      <div class="section-block">
        <div class="section-title-row">
          <h2>Pending Accommodations</h2>
          <p>Review newly submitted properties and approve or reject them.</p>
        </div>

        <div id="pendingAccommodationsGrid" class="accommodations-grid">
          <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading pending accommodations...</p>
          </div>
        </div>

        <div id="pendingEmptyState" class="empty-state" style="display: none; margin-bottom: 20px;">
          <div class="empty-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <h3>No Pending Accommodations</h3>
          <p>All submitted properties have been reviewed.</p>
        </div>
      </div>

      <!-- Accommodations Grid -->
      <div class="section-title-row" style="margin-top: 10px;">
        <h2>All Other Accommodations</h2>
        <p>Browse approved and inactive properties.</p>
      </div>
      <div id="accommodationsGrid" class="accommodations-grid">
        <!-- Loading state -->
        <div class="loading-state">
          <i class="fas fa-spinner fa-spin"></i>
          <p>Loading accommodations...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-icon">
          <i class="fas fa-home"></i>
        </div>
        <h3>No Accommodations Found</h3>
        <p>There are no accommodations matching your criteria.</p>
      </div>

    </div>
  </div>

  <!-- View Modal -->
  <div id="viewModal" class="view-modal" style="display: none;">
    <div class="view-modal-content">
      <div class="view-modal-header">
        <h2 id="modalTitle">Accommodation Details</h2>
        <button class="modal-close" onclick="closeViewModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="view-modal-body">
        <!-- Image Gallery -->
        <div class="modal-image-section">
          <div id="mainImage" class="main-image">
            <img src="" alt="Accommodation">
          </div>
          <div id="imageGallery" class="image-gallery">
            <!-- Thumbnails will be inserted here -->
          </div>
        </div>

        <!-- Details Grid -->
        <div class="modal-details-grid">
          <div class="detail-section">
            <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
            <div class="detail-row">
              <span class="detail-label">Property ID:</span>
              <span class="detail-value" id="modalPropertyId">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Property Type:</span>
              <span class="detail-value" id="modalPropertyType">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Title:</span>
              <span class="detail-value" id="modalAccomTitle">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Location:</span>
              <span class="detail-value" id="modalLocation">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Status:</span>
              <span class="detail-value" id="modalStatus">-</span>
            </div>
          </div>

          <div class="detail-section">
            <h3><i class="fas fa-bed"></i> Property Details</h3>
            <div class="detail-row">
              <span class="detail-label">Rooms:</span>
              <span class="detail-value" id="modalRooms">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Bathrooms:</span>
              <span class="detail-value" id="modalBathrooms">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Max Guests:</span>
              <span class="detail-value" id="modalMaxGuests">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Price per Night:</span>
              <span class="detail-value" id="modalPrice">-</span>
            </div>
          </div>

          <div class="detail-section">
            <h3><i class="fas fa-clock"></i> Check-in/out Times</h3>
            <div class="detail-row">
              <span class="detail-label">Check-in Start:</span>
              <span class="detail-value" id="modalCheckInStart">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Check-in End:</span>
              <span class="detail-value" id="modalCheckInEnd">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Check-out Time:</span>
              <span class="detail-value" id="modalCheckOut">-</span>
            </div>
          </div>

          <div class="detail-section">
            <h3><i class="fas fa-list-check"></i> House Rules</h3>
            <div class="detail-row">
              <span class="detail-label">Smoking:</span>
              <span class="detail-value" id="modalSmoking">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Parties:</span>
              <span class="detail-value" id="modalParties">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Pets:</span>
              <span class="detail-value" id="modalPets">-</span>
            </div>
          </div>

          <div class="detail-section">
            <h3><i class="fas fa-user"></i> Provider Information</h3>
            <div class="detail-row">
              <span class="detail-label">Provider ID:</span>
              <span class="detail-value" id="modalUserId">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Listed On:</span>
              <span class="detail-value" id="modalCreatedAt">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Last Updated:</span>
              <span class="detail-value" id="modalUpdatedAt">-</span>
            </div>
          </div>

          <div class="detail-section">
            <h3><i class="fas fa-images"></i> Media Information</h3>
            <div class="detail-row">
              <span class="detail-label">Total Images:</span>
              <span class="detail-value" id="modalImageCount">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Has Main Image:</span>
              <span class="detail-value" id="modalHasMainImage">-</span>
            </div>
          </div>

          <div class="detail-section full-width">
            <h3><i class="fas fa-align-left"></i> Description</h3>
            <p id="modalDescription" class="description-text">-</p>
          </div>
        </div>
      </div>

      <div class="view-modal-footer">
        <button class="btn-secondary" onclick="closeViewModal()">Close</button>
      </div>
    </div>
  </div>

  <script src="<?= ROOT ?>/assets/js/viewAccommodations.js?v=<?php echo time(); ?>"></script>

</body>
</html>
