<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Vehicle - TravelMate</title>
  <link rel="stylesheet" href="assets/css/Transpoter/vehicleDocument.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <div class="main-content">
      <h1 class="page-title">Edit Vehicle Details</h1>
      <p class="page-subtitle">Update your vehicle information</p>

      <form id="edit-vehicle-form">
        <input type="hidden" id="vehicle-id" name="id">

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-car"></i>
            Vehicle Information
          </h2>

          <div class="form-row">
            <div class="form-group">
              <label for="vehicle-type">Vehicle Type <span class="required-asterisk">*</span></label>
              <select id="vehicle-type" name="vehicle_type" required>
                <option value="">-- Select Type --</option>
                <option value="car">Car</option>
                <option value="van">Van</option>
                <option value="bus">Bus</option>
                <option value="jeep">Jeep</option>
                <option value="tuk">Tuk</option>
              </select>
              <div class="error-message" id="vehicle-type-error"></div>
            </div>

            <div class="form-group">
              <label for="working-district">Working District <span class="required-asterisk">*</span></label>
              <select id="working-district" name="working_district" required>
                <option value="">-- Select District --</option>
                <option value="colombo">Colombo</option>
                <option value="gampaha">Gampaha</option>
                <option value="kalutara">Kalutara</option>
                <option value="kandy">Kandy</option>
                <option value="matale">Matale</option>
                <option value="nuwara-eliya">Nuwara Eliya</option>
                <option value="galle">Galle</option>
                <option value="matara">Matara</option>
                <option value="hambantota">Hambantota</option>
                <option value="jaffna">Jaffna</option>
                <option value="kilinochchi">Kilinochchi</option>
                <option value="mannar">Mannar</option>
                <option value="vavuniya">Vavuniya</option>
                <option value="mullaitivu">Mullaitivu</option>
                <option value="batticaloa">Batticaloa</option>
                <option value="ampara">Ampara</option>
                <option value="trincomalee">Trincomalee</option>
                <option value="kurunegala">Kurunegala</option>
                <option value="puttalam">Puttalam</option>
                <option value="anuradhapura">Anuradhapura</option>
                <option value="polonnaruwa">Polonnaruwa</option>
                <option value="badulla">Badulla</option>
                <option value="monaragala">Monaragala</option>
                <option value="ratnapura">Ratnapura</option>
                <option value="kegalle">Kegalle</option>
              </select>
              <div class="error-message" id="working-district-error"></div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="vehicle-model">Vehicle Model <span class="required-asterisk">*</span></label>
              <input type="text" id="vehicle-model" name="vehicle_model" placeholder="e.g., Toyota Prius" required>
              <div class="error-message" id="vehicle-model-error"></div>
            </div>

            <div class="form-group">
              <label for="vehicle-year">Manufacturing Year <span class="required-asterisk">*</span></label>
              <input type="number" id="vehicle-year" name="vehicle_year" min="1990" max="2024" placeholder="e.g., 2018" required>
              <div class="error-message" id="vehicle-year-error"></div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="vehicle-color">Vehicle Color <span class="required-asterisk">*</span></label>
              <input type="text" id="vehicle-color" name="vehicle_color" placeholder="e.g., Blue" required>
              <div class="error-message" id="vehicle-color-error"></div>
            </div>

            <div class="form-group">
              <label for="vehicle-number">Vehicle Number <span class="required-asterisk">*</span></label>
              <input type="text" id="vehicle-number" name="vehicle_number" placeholder="e.g., CAB-1234" required>
              <div class="error-message" id="vehicle-number-error"></div>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-users"></i>
            Passenger Capacity
          </h2>

          <div class="form-group">
            <label for="passenger-count">Number of Passengers <span class="required-asterisk">*</span></label>
            <input type="number" id="passenger-count" class="passenger-input" name="passenger_count" min="1" max="50" value="2" required>
            <div class="error-message" id="passenger-count-error"></div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-snowflake"></i>
            Air Conditioning
          </h2>

          <div class="radio-group">
            <div class="radio-option" id="ac-option">
              <input type="radio" id="ac" name="ac_type" value="ac">
              <label for="ac">A/C</label>
            </div>

            <div class="radio-option selected" id="non-ac-option">
              <input type="radio" id="non-ac" name="ac_type" value="non-ac" checked>
              <label for="non-ac">Non-A/C</label>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-cog"></i>
            Status
          </h2>

          <div class="form-group">
            <label for="vehicle-status">Vehicle Status</label>
            <select id="vehicle-status" name="status">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="maintenance">Under Maintenance</option>
            </select>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="save-btn" onclick="window.location.href='tr_dashboard'">Cancel</button>
          <button type="submit" class="continue-btn">
            Update Vehicle <i class="fas fa-check-circle"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('edit-vehicle-form');
      const continueBtn = document.querySelector('.continue-btn');
      
      // Get vehicle ID from URL
      const urlParams = new URLSearchParams(window.location.search);
      const vehicleId = urlParams.get('id');
      
      if (!vehicleId) {
        alert('No vehicle selected');
        window.location.href = 'tr_dashboard';
        return;
      }
      
      // Set vehicle ID in hidden field
      document.getElementById('vehicle-id').value = vehicleId;
      
      // Load vehicle data
      loadVehicleData(vehicleId);
      
      // AC type selection
      const acOption = document.getElementById('ac-option');
      const nonAcOption = document.getElementById('non-ac-option');
      const acInput = document.getElementById('ac');
      const nonAcInput = document.getElementById('non-ac');

      if (acOption && nonAcOption) {
        acOption.addEventListener('click', function () {
          acInput.checked = true;
          acOption.classList.add('selected');
          nonAcOption.classList.remove('selected');
        });

        nonAcOption.addEventListener('click', function () {
          nonAcInput.checked = true;
          nonAcOption.classList.add('selected');
          acOption.classList.remove('selected');
        });
      }
      
      // Form submission
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        
        let isValid = true;
        const requiredFields = this.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
          if (!field.value) {
            const errorEl = document.getElementById(field.id + '-error');
            if (errorEl) errorEl.textContent = 'This field is required';
            field.style.borderColor = '#e74c3c';
            isValid = false;
          }
        });
        
        if (!isValid) return;
        
        // Show loading
        continueBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        continueBtn.disabled = true;
        
        // Build FormData
        const formData = new FormData(form);
        
        // Log what we're sending
        console.log('Updating vehicle with data:');
        for (let [key, value] of formData.entries()) {
          console.log(key + ':', value);
        }
        
        // Send update request
        fetch('/TravelMate/public/api/vehicle/update', {
          method: 'POST',
          body: formData,
          credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Vehicle updated successfully!');
            window.location.href = 'tr_dashboard';
          } else {
            alert('Failed to update vehicle: ' + (data.errors?.error || 'Unknown error'));
            continueBtn.innerHTML = 'Update Vehicle <i class="fas fa-check-circle"></i>';
            continueBtn.disabled = false;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Network error. Please try again.');
          continueBtn.innerHTML = 'Update Vehicle <i class="fas fa-check-circle"></i>';
          continueBtn.disabled = false;
        });
      });
      
      // Load vehicle data function
      async function loadVehicleData(id) {
        try {
          const response = await fetch(`/TravelMate/public/api/vehicle/get?id=${id}`, {
            credentials: 'same-origin'
          });
          
          const result = await response.json();
          
          if (result.success && result.data) {
            populateForm(result.data);
          } else {
            alert('Vehicle not found');
            window.location.href = 'tr_dashboard';
          }
        } catch (error) {
          console.error('Error loading vehicle:', error);
          alert('Failed to load vehicle data');
        }
      }
      
      // Populate form with vehicle data
      function populateForm(vehicle) {
        console.log('Populating form with:', vehicle);
        
        // Set all form fields
        if (vehicle.vehicle_type) document.getElementById('vehicle-type').value = vehicle.vehicle_type;
        if (vehicle.working_district) document.getElementById('working-district').value = vehicle.working_district;
        if (vehicle.vehicle_model) document.getElementById('vehicle-model').value = vehicle.vehicle_model;
        if (vehicle.vehicle_year) document.getElementById('vehicle-year').value = vehicle.vehicle_year;
        if (vehicle.vehicle_color) document.getElementById('vehicle-color').value = vehicle.vehicle_color;
        if (vehicle.vehicle_number) document.getElementById('vehicle-number').value = vehicle.vehicle_number;
        if (vehicle.passenger_count) document.getElementById('passenger-count').value = vehicle.passenger_count;
        if (vehicle.status) document.getElementById('vehicle-status').value = vehicle.status;
        
        // Set AC type
        if (vehicle.ac_type === 'ac') {
          document.getElementById('ac').checked = true;
          acOption.classList.add('selected');
          nonAcOption.classList.remove('selected');
        } else {
          document.getElementById('non-ac').checked = true;
          nonAcOption.classList.add('selected');
          acOption.classList.remove('selected');
        }
      }
    });
  </script>
</body>

</html>