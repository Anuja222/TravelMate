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

    document.getElementById('confirmDelete').addEventListener('click', async function () {
      if (!confirm('Are you absolutely sure you want to delete this vehicle?')) return;

      this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> DELETING...';
      this.disabled = true;

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
          alert('Vehicle deleted successfully');
          window.location.href = 'tr_dashboard';
        } else {
          alert('Failed to delete vehicle');
          this.innerHTML = 'YES, DELETE';
          this.disabled = false;
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Network error');
        this.innerHTML = 'YES, DELETE';
        this.disabled = false;
      }
    });

    document.getElementById('cancelDelete').addEventListener('click', function () {
      window.location.href = 'tr_dashboard';
    });

    loadVehicleDetails();
  </script>
</body>

</html>