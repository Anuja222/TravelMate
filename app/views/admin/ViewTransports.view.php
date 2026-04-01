<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transports - TravelMate Admin</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/ViewTransports.css">
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
              <path d="M5 17h14v2H5v-2z"></path>
              <path d="M4 12h16v3H4v-3z"></path>
              <path d="M8 6h8l2 4H6l2-4z"></path>
              <circle cx="7" cy="19" r="2"></circle>
              <circle cx="17" cy="19" r="2"></circle>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>Transport Management</h1>
            <p class="page-subtitle">Manage and monitor all vehicle listings</p>
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
            <div class="stat-number" id="rejectedCount">0</div>
            <div class="stat-label">Rejected</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #3498db;">
            <i class="fas fa-car"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number" id="totalCount">0</div>
            <div class="stat-label">Total</div>
          </div>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <input type="text" id="searchInput" placeholder="Search vehicles..." />
        <select id="statusFilter">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="pending">Pending</option>
          <option value="inactive">Rejected</option>
        </select>
        <select id="typeFilter">
          <option value="">All Types</option>
          <option value="car">Car</option>
          <option value="van">Van</option>
          <option value="bus">Bus</option>
          <option value="suv">SUV</option>
          <option value="mini-van">Mini Van</option>
        </select>
        <select id="acFilter">
          <option value="">All AC Types</option>
          <option value="ac">AC</option>
          <option value="non-ac">Non-AC</option>
        </select>
        <button id="btnApplyFilter">
          <i class="fas fa-filter"></i> Apply Filters
        </button>
      </div>

      <!-- Pending Vehicles Section -->
      <div class="section-block">
        <div class="section-title-row">
          <h2>Pending Vehicles</h2>
          <p>Review newly submitted vehicles and approve or reject them.</p>
        </div>

        <div id="pendingTransportsGrid" class="transports-grid">
          <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading pending vehicles...</p>
          </div>
        </div>

        <div id="pendingEmptyState" class="empty-state" style="display: none; margin-bottom: 20px;">
          <div class="empty-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <h3>No Pending Vehicles</h3>
          <p>All submitted vehicles have been reviewed.</p>
        </div>
      </div>

      <!-- Rejected Vehicles Section -->
      <div class="section-block">
        <div class="section-title-row" style="margin-top: 10px;">
          <h2>Rejected Vehicles</h2>
          <p>Vehicles rejected by admin are listed here.</p>
        </div>
        <div id="rejectedTransportsGrid" class="transports-grid">
          <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading rejected vehicles...</p>
          </div>
        </div>

        <div id="rejectedEmptyState" class="empty-state" style="display: none; margin-bottom: 20px;">
          <div class="empty-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <h3>No Rejected Vehicles</h3>
          <p>No vehicles have been rejected yet.</p>
        </div>
      </div>

      <!-- Transports Grid -->
      <div class="section-title-row" style="margin-top: 10px;">
        <h2>Approved Vehicles</h2>
        <p>Browse approved vehicles.</p>
      </div>
      <div id="transportsGrid" class="transports-grid">
        <!-- Loading state -->
        <div class="loading-state">
          <i class="fas fa-spinner fa-spin"></i>
          <p>Loading vehicles...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-icon">
          <i class="fas fa-car"></i>
        </div>
        <h3>No Vehicles Found</h3>
        <p>There are no vehicles matching your criteria.</p>
      </div>

    </div>
  </div>

  <!-- View Modal -->
  <div id="viewModal" class="view-modal" style="display: none;">
    <div class="view-modal-content">
      <div class="view-modal-header">
        <h2 id="modalTitle">Vehicle Details</h2>
        <button class="modal-close" onclick="closeViewModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="view-modal-body">
        <!-- Image Gallery -->
        <div class="modal-image-section">
          <div id="mainImage" class="main-image">
            <img src="" alt="Vehicle">
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
              <span class="detail-label">Vehicle ID:</span>
              <span class="detail-value" id="modalVehicleId">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Vehicle Type:</span>
              <span class="detail-value" id="modalVehicleType">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Model:</span>
              <span class="detail-value" id="modalModel">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Year:</span>
              <span class="detail-value" id="modalYear">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Status:</span>
              <span class="detail-value" id="modalStatus">-</span>
            </div>
          </div>

          <div class="detail-section">
            <h3><i class="fas fa-car"></i> Vehicle Details</h3>
            <div class="detail-row">
              <span class="detail-label">Color:</span>
              <span class="detail-value" id="modalColor">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Vehicle Number:</span>
              <span class="detail-value" id="modalNumber">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Passenger Count:</span>
              <span class="detail-value" id="modalPassengers">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">AC Type:</span>
              <span class="detail-value" id="modalAcType">-</span>
            </div>
          </div>

          <div class="detail-section">
            <h3><i class="fas fa-map-marker-alt"></i> Location & Service</h3>
            <div class="detail-row">
              <span class="detail-label">Working District:</span>
              <span class="detail-value" id="modalDistrict">-</span>
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
            <h3><i class="fas fa-file-alt"></i> Documents</h3>
            <div class="detail-row">
              <span class="detail-label">Total Documents:</span>
              <span class="detail-value" id="modalDocCount">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Has Photos:</span>
              <span class="detail-value" id="modalHasPhotos">-</span>
            </div>
          </div>

          <div class="detail-section full-width">
            <h3><i class="fas fa-list"></i> Document List</h3>
            <div id="modalDocumentList" class="document-list">
              <!-- Documents will be listed here -->
            </div>
          </div>
        </div>
      </div>

      <div class="view-modal-footer">
        <button class="btn-secondary" onclick="closeViewModal()">Close</button>
      </div>
    </div>
  </div>

  <!-- Action Confirm Modal -->
  <div id="actionConfirmModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 450px; text-align: center;">
      <div class="confirm-modal-icon" id="actionConfirmIcon">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="9 12 11 14 15 10"></polyline>
        </svg>
      </div>
      <h2 id="actionConfirmTitle" style="margin: 20px 0 10px 0; color: #1f2937; font-size: 22px;">Are you sure?</h2>
      <p id="actionConfirmMessage" style="margin: 0 0 30px 0; color: #6b7280; font-size: 15px; line-height: 1.6;">Do you want to approve this vehicle?</p>
      <div style="display: flex; gap: 12px; justify-content: center;">
        <button id="actionConfirmCancel" style="padding: 12px 32px; background: #f3f4f6; border: none; border-radius: 8px; font-size: 15px; cursor: pointer; color: #374151; font-weight: 600;">Cancel</button>
        <button id="actionConfirmProceed" style="padding: 12px 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 8px; font-size: 15px; cursor: pointer; color: white; font-weight: 600;">Approve</button>
      </div>
    </div>
  </div>

  <!-- Action Success Modal -->
  <div id="actionSuccessModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
      <div class="success-icon" id="actionSuccessIcon">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="9 12 11 14 15 10"></polyline>
        </svg>
      </div>
      <h2 id="actionSuccessTitle" style="margin: 20px 0 10px 0; color: #1f2937; font-size: 22px;">Approved Successfully</h2>
      <p id="actionSuccessMessage" style="margin: 0 0 30px 0; color: #6b7280; font-size: 15px;">Vehicle has been approved and is now active.</p>
      <button id="actionSuccessOk" style="padding: 12px 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 8px; font-size: 15px; cursor: pointer; color: white; font-weight: 600;">Continue</button>
    </div>
  </div>

  <script src="<?= ROOT ?>/assets/js/viewTransports.js?v=<?php echo time(); ?>"></script>

</body>
</html>
