<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Vehicle - TravelMate</title>
  <link rel="stylesheet" href="assets/css/Transpoter/DeleteVehicle.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <div class="main-content">
      <div class="confirmation-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h1 class="page-title">Delete Vehicle</h1>
      <p class="confirmation-text">Are you sure you want to delete your vehicle? This action cannot be undone.</p>

      <div class="warning-text">
        <p><strong>Warning:</strong> Deleting this vehicle will remove it from your listings and any future bookings.
        </p>
      </div>

      <!-- Vehicle details for confirmation -->
      <div class="vehicle-details" id="vehicle-details">
        <div class="loading">Loading vehicle details...</div>
      </div>

      <div class="confirmation-actions">
        <button class="btn btn-danger" id="confirmDelete" disabled>YES, DELETE</button>
        <button class="btn btn-secondary" id="cancelDelete">CANCEL</button>
      </div>

      <div class="divider"></div>

      <p class="confirmation-text" style="font-size: 14px;">
        Need help? <a href="contact" style="color: #1abc5b;">Contact support</a>
      </p>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <!-- Delete Confirmation Modal -->
  <div id="deleteConfirmModal" class="delete-modal">
    <div class="delete-modal-content">
      <div class="warning-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
      </div>
      <h2>Confirm Vehicle Deletion</h2>
      <p>Are you absolutely sure you want to delete this vehicle? This action cannot be undone.</p>
      <div class="modal-actions">
        <button onclick="closeDeleteConfirmModal()" class="btn-cancel">Cancel</button>
        <button onclick="proceedWithDelete()" class="btn-delete">Yes, Delete</button>
      </div>
    </div>
  </div>

  <!-- Delete Success Modal -->
  <div id="deleteSuccessModal" class="delete-success-modal">
    <div class="delete-success-content">
      <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <h2>Vehicle Deleted Successfully!</h2>
      <p>The vehicle has been permanently removed from your account.</p>
      <button onclick="goToDashboard()" class="btn-go-dashboard">Go to Dashboard</button>
    </div>
  </div>

  <!-- Delete Error Modal -->
  <div id="deleteErrorModal" class="delete-error-modal">
    <div class="delete-error-content">
      <div class="error-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="15" y1="9" x2="9" y2="15"></line>
          <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
      </div>
      <h2>Cannot Delete Vehicle</h2>
      <p id="errorMessage">An error occurred</p>
      <button onclick="closeDeleteErrorModal()" class="btn-ok-error">OK</button>
    </div>
  </div>

  <style>
    /* Delete Error Modal */
    .delete-error-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 10000;
      animation: fadeIn 0.3s ease-in-out;
    }

    .delete-error-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      max-width: 450px;
      width: 90%;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.4s ease-out;
    }

    .delete-error-content .error-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      background: linear-gradient(135deg, #ef4444, #dc2626);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease-out 0.2s both;
    }

    .delete-error-content .error-icon svg {
      width: 45px;
      height: 45px;
      color: white;
    }

    .delete-error-content h2 {
      font-size: 24px;
      color: #1f2937;
      margin-bottom: 12px;
      font-weight: 600;
    }

    .delete-error-content p {
      font-size: 15px;
      color: #6b7280;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .btn-ok-error {
      background: #ef4444;
      color: white;
      border: none;
      padding: 12px 32px;
      font-size: 16px;
      font-weight: 500;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-ok-error:hover {
      background: #dc2626;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    /* Delete Confirmation Modal */
    .delete-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 10000;
      animation: fadeIn 0.3s ease-in-out;
    }

    .delete-modal-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      max-width: 450px;
      width: 90%;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.4s ease-out;
    }

    .delete-modal .warning-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      background: linear-gradient(135deg, #ef4444, #dc2626);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease-out 0.2s both;
    }

    .delete-modal .warning-icon svg {
      width: 45px;
      height: 45px;
      color: white;
    }

    .delete-modal-content h2 {
      font-size: 24px;
      color: #1f2937;
      margin-bottom: 12px;
      font-weight: 600;
    }

    .delete-modal-content p {
      font-size: 15px;
      color: #6b7280;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .modal-actions {
      display: flex;
      gap: 12px;
      justify-content: center;
    }

    .btn-cancel {
      background: #f3f4f6;
      color: #374151;
      border: none;
      padding: 12px 28px;
      font-size: 16px;
      font-weight: 500;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-cancel:hover {
      background: #e5e7eb;
      transform: translateY(-2px);
    }

    .btn-delete {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      border: none;
      padding: 12px 28px;
      font-size: 16px;
      font-weight: 500;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-delete:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    /* Delete Success Modal */
    .delete-success-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 10000;
      animation: fadeIn 0.3s ease-in-out;
    }

    .delete-success-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      max-width: 450px;
      width: 90%;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      animation: slideUp 0.4s ease-out;
    }

    .delete-success-modal .success-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      background: linear-gradient(135deg, #10b981, #059669);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease-out 0.2s both;
    }

    .delete-success-modal .success-icon svg {
      width: 45px;
      height: 45px;
      color: white;
    }

    .delete-success-content h2 {
      font-size: 24px;
      color: #1f2937;
      margin-bottom: 12px;
      font-weight: 600;
    }

    .delete-success-content p {
      font-size: 15px;
      color: #6b7280;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .btn-go-dashboard {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      border: none;
      padding: 12px 32px;
      font-size: 16px;
      font-weight: 500;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-go-dashboard:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translate(-50%, -40%);
      }
      to {
        opacity: 1;
        transform: translate(-50%, -50%);
      }
    }

    @keyframes scaleIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }
  </style>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const vehicleId = urlParams.get('id');

    if (!vehicleId) {
      alert('No vehicle selected');
      window.location.href = 'tr_dashboard';
    }

    // Fetch vehicle details
    async function loadVehicleDetails() {
      try {
        const response = await fetch(`/TravelMate/public/api/vehicle/get?id=${vehicleId}`, {
          credentials: 'same-origin'
        });

        const result = await response.json();

        if (result.success && result.data) {
          displayVehicleDetails(result.data);
          document.getElementById('confirmDelete').disabled = false;
        } else {
          alert('Vehicle not found');
          window.location.href = 'tr_dashboard';
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Failed to load vehicle details');
      }
    }

    function displayVehicleDetails(vehicle) {
      const container = document.getElementById('vehicle-details');
      container.innerHTML = `
        <h3>${vehicle.vehicle_model || 'Vehicle'}</h3>
        <div class="vehicle-info">
          <div><strong>Type:</strong> ${vehicle.vehicle_type || 'N/A'}</div>
          <div><strong>Year:</strong> ${vehicle.vehicle_year || 'N/A'}</div>
          <div><strong>Color:</strong> ${vehicle.vehicle_color || 'N/A'}</div>
          <div><strong>Number:</strong> ${vehicle.vehicle_number || 'N/A'}</div>
          <div><strong>Location:</strong> ${vehicle.working_district || 'N/A'}</div>
          <div><strong>Passengers:</strong> ${vehicle.passenger_count || 'N/A'}</div>
        </div>
      `;
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
      showDeleteConfirmModal();
    });

    async function proceedWithDelete() {
      closeDeleteConfirmModal();
      
      const deleteBtn = document.getElementById('confirmDelete');
      deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> DELETING...';
      deleteBtn.disabled = true;

      try {
        const formData = new FormData();
        formData.append('id', vehicleId);

        const response = await fetch('/TravelMate/public/api/vehicle/delete', {
          method: 'POST',
          body: formData,
          credentials: 'same-origin'
        });

        const result = await response.json();

        if (result.success) {
          showDeleteSuccessModal();
        } else {
          const errorMsg = result.errors && result.errors[0] ? result.errors[0] : 'Failed to delete vehicle.';
          showDeleteErrorModal(errorMsg);
          deleteBtn.innerHTML = 'YES, DELETE';
          deleteBtn.disabled = false;
        }
      } catch (error) {
        console.error('Error:', error);
        showDeleteErrorModal('Network error. Please try again.');
        deleteBtn.innerHTML = 'YES, DELETE';
        deleteBtn.disabled = false;
      }
    }

    document.getElementById('cancelDelete').addEventListener('click', function () {
      window.location.href = 'tr_dashboard';
    });

    loadVehicleDetails();
    
    // Error Modal functions
    function showDeleteErrorModal(errorMsg) {
      const modal = document.getElementById('deleteErrorModal');
      const message = document.getElementById('errorMessage');
      message.textContent = errorMsg;
      modal.style.display = 'flex';
    }

    function closeDeleteErrorModal() {
      const modal = document.getElementById('deleteErrorModal');
      modal.style.display = 'none';
    }
    
    // Modal functions
    function showDeleteConfirmModal() {
      const modal = document.getElementById('deleteConfirmModal');
      if (modal) {
        modal.style.display = 'block';
      }
    }
    
    function closeDeleteConfirmModal() {
      const modal = document.getElementById('deleteConfirmModal');
      if (modal) {
        modal.style.display = 'none';
      }
    }
    
    function showDeleteSuccessModal() {
      const modal = document.getElementById('deleteSuccessModal');
      if (modal) {
        modal.style.display = 'block';
      }
    }
    
    function goToDashboard() {
      window.location.href = 'tr_dashboard';
    }

    // Close error modal when clicking overlay
    document.getElementById('deleteErrorModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeDeleteErrorModal();
      }
    });
  </script>
</body>

</html>