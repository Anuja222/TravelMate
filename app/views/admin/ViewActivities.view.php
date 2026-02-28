<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activities - TravelMate Admin</title>
  <link rel="stylesheet" href="assets/css/Admin/ViewActivities.css">
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
          <h1><i class="fas fa-hiking"></i> Activities Management</h1>
          <p class="subtitle">Manage adventure activities and experiences</p>
        </div>
        <button id="btnCreate" class="btn-primary" onclick="window.location.href='createActivity'">
          <i class="fas fa-plus"></i> Create Activity
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
            <i class="fas fa-hiking"></i>
          </div>
          <div class="stat-info">
            <div class="stat-number" id="totalCount">0</div>
            <div class="stat-label">Total Activities</div>
          </div>
        </div>
      </div>

      <!-- Activities Grid -->
      <div id="activityList" class="content-grid">
        <div class="loading-container">
          <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: #1abc5b;"></i>
          <p>Loading activities...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div id="emptyState" class="empty-state" style="display: none;">
        <i class="fas fa-hiking"></i>
        <h3>No Activities Found</h3>
        <p>Start by creating your first activity</p>
        <button class="btn-primary" onclick="window.location.href='createActivity'">
          <i class="fas fa-plus"></i> Create First Activity
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
        <p>Are you sure you want to delete this activity?</p>
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

  <!-- Success Modal -->
  <div id="successModal" class="success-modal" style="display: none;">
    <div class="success-modal-content">
      <div class="success-modal-icon">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <h3 id="successMessage" class="success-modal-message">Success!</h3>
      <button onclick="closeSuccessModal()" class="success-modal-btn">OK</button>
    </div>
  </div>

  <!-- View Activity Modal -->
  <div id="viewModal" class="view-modal" style="display: none;">
    <div class="view-modal-content">
      <div class="view-modal-header">
        <h2 id="viewModalTitle">Activity Details</h2>
        <span class="view-modal-close" onclick="closeViewModal()">&times;</span>
      </div>
      <div class="view-modal-body">
        <div class="view-activity-info">
          <div class="view-activity-image">
            <img id="viewActivityImage" src="" alt="Activity">
          </div>
          <div class="view-activity-details">
            <h3 id="viewActivityTitle"></h3>
            <p id="viewActivityDescription"></p>
          </div>
        </div>
        <div class="view-places-section">
          <h3 class="view-places-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Locations for this Activity
          </h3>
          <div id="viewPlacesList" class="view-places-grid">
            <!-- Places will be loaded here -->
          </div>
        </div>
      </div>
      <div class="view-modal-footer">
        <button class="btn-edit-activity" id="viewModalEditBtn" onclick="editFromModal()">
          <i class="fas fa-edit"></i> Edit Activity
        </button>
        <button class="btn-close-modal" onclick="closeViewModal()">Close</button>
      </div>
    </div>
  </div>

  <script src="../public/assets/js/activities.js"></script>
  
  <script>
    // Success Modal Functions
    function showSuccessModal(message) {
      document.getElementById('successMessage').textContent = message;
      document.getElementById('successModal').style.display = 'flex';
    }

    function closeSuccessModal() {
      document.getElementById('successModal').style.display = 'none';
    }

    // View Modal Functions
    let currentViewActivityId = null;

    function showViewModal(activityId) {
      currentViewActivityId = activityId;
      const modal = document.getElementById('viewModal');
      modal.style.display = 'flex';
      
      // Fetch activity details
      const baseApi = window.location.origin + '/TravelMate/public';
      fetch(baseApi + '/api/activity/get?id=' + activityId, { credentials: 'same-origin' })
        .then(r => r.json())
        .then(resp => {
          if (resp.success) {
            const activity = resp.data;
            const baseUrl = window.location.origin + '/TravelMate/public/';
            
            document.getElementById('viewModalTitle').textContent = activity.title || 'Activity Details';
            document.getElementById('viewActivityTitle').textContent = activity.title || '';
            document.getElementById('viewActivityDescription').textContent = activity.description || 'No description available';
            
            const imgSrc = activity.image ? baseUrl + activity.image : 'assets/images/default-activity.png';
            document.getElementById('viewActivityImage').src = imgSrc;
            
            // Display places
            const placesList = document.getElementById('viewPlacesList');
            if (activity.places && activity.places.length > 0) {
              placesList.innerHTML = activity.places.map(place => {
                const placeImg = place.image ? baseUrl + place.image : 'assets/images/default-place.png';
                return `
                  <div class="view-place-card">
                    <div class="view-place-image">
                      <img src="${placeImg}" alt="${place.title}">
                    </div>
                    <div class="view-place-info">
                      <h4>${place.title}</h4>
                      <p>${place.description ? place.description.substring(0, 100) : 'No description'}${place.description && place.description.length > 100 ? '...' : ''}</p>
                    </div>
                  </div>
                `;
              }).join('');
            } else {
              placesList.innerHTML = `
                <div class="view-no-places">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <p>No locations added yet</p>
                </div>
              `;
            }
          }
        })
        .catch(err => console.error('Error fetching activity:', err));
    }

    function closeViewModal() {
      document.getElementById('viewModal').style.display = 'none';
      currentViewActivityId = null;
    }

    function editFromModal() {
      if (currentViewActivityId) {
        window.location.href = 'editActivity?id=' + currentViewActivityId;
      }
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
      const viewModal = document.getElementById('viewModal');
      if (event.target === viewModal) {
        closeViewModal();
      }
    });

    // Modal handling
    document.addEventListener('DOMContentLoaded', function() {
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
        animateValue(document.getElementById('activeCount'), 0, 8, 1000);
        animateValue(document.getElementById('pendingCount'), 0, 2, 1000);
        animateValue(document.getElementById('inactiveCount'), 0, 1, 1000);
        animateValue(document.getElementById('totalCount'), 0, 11, 1000);
      }, 500);
    });
  </script>
</body>
</html>
