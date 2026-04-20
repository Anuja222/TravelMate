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

      <form id="edit-vehicle-form" enctype="multipart/form-data">
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

          <div class="form-row">
            <div class="form-group">
              <label for="cost-per-km">Cost per 1Km (Rs) <span class="required-asterisk">*</span></label>
              <input type="number" id="cost-per-km" name="cost_per_km" placeholder="e.g., 150" min="1" step="0.01" required>
              <div class="error-message" id="cost-per-km-error"></div>
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

  <div id="vehicleUpdateSuccessModal" class="vehicle-update-modal">
    <div class="vehicle-update-content">
      <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <h2>Vehicle Updated Successfully!</h2>
      <p>Your vehicle information has been updated and saved to your account.</p>
      <button onclick="goToDashboard()" class="btn-go-dashboard">Go to Dashboard</button>
    </div>
  </div>

  <style>
    .vehicle-update-modal {
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

    .vehicle-update-content {
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

    .vehicle-update-modal .success-icon {
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

    .vehicle-update-modal .success-icon svg {
      width: 45px;
      height: 45px;
      color: white;
    }

    .vehicle-update-content h2 {
      font-size: 24px;
      color: #1f2937;
      margin-bottom: 12px;
      font-weight: 600;
    }

    .vehicle-update-content p {
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
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('edit-vehicle-form');
      const continueBtn = document.querySelector('.continue-btn');
      
      // get vehicle ID from URL
      const urlParams = new URLSearchParams(window.location.search);
      const vehicleId = urlParams.get('id');
      
      if (!vehicleId) {
        alert('No vehicle selected');
        window.location.href = 'tr_dashboard';
        return;
      }
      
      document.getElementById('vehicle-id').value = vehicleId;
      
      loadVehicleData(vehicleId);
      
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
      
      // form submission
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
        
        continueBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        continueBtn.disabled = true;
        
        // build formData
        const formData = new FormData(form);
        
        console.log('Updating vehicle with data:');
        for (let [key, value] of formData.entries()) {
          console.log(key + ':', value);
        }
        
        // send update request
        fetch('/TravelMate/public/api/vehicle/update', {
          method: 'POST',
          body: formData,
          credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showVehicleUpdateSuccessModal();
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
      
      // load vehicle data function
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
      
      function populateForm(vehicle) {
        console.log('Populating form with:', vehicle);
        
        // set all form fields
        if (vehicle.vehicle_type) document.getElementById('vehicle-type').value = vehicle.vehicle_type;
        if (vehicle.working_district) document.getElementById('working-district').value = vehicle.working_district;
        if (vehicle.vehicle_model) document.getElementById('vehicle-model').value = vehicle.vehicle_model;
        if (vehicle.vehicle_year) document.getElementById('vehicle-year').value = vehicle.vehicle_year;
        if (vehicle.vehicle_color) document.getElementById('vehicle-color').value = vehicle.vehicle_color;
        if (vehicle.vehicle_number) document.getElementById('vehicle-number').value = vehicle.vehicle_number;
        if (vehicle.cost_per_km) document.getElementById('cost-per-km').value = vehicle.cost_per_km;
        if (vehicle.passenger_count) document.getElementById('passenger-count').value = vehicle.passenger_count;
        if (vehicle.status) document.getElementById('vehicle-status').value = vehicle.status;
        
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
    
    // modal functions
    function showVehicleUpdateSuccessModal() {
      const modal = document.getElementById('vehicleUpdateSuccessModal');
      if (modal) {
        modal.style.display = 'block';
      }
    }
    
    function goToDashboard() {
      window.location.href = 'tr_dashboard';
    }
  </script>
</body>

</html>