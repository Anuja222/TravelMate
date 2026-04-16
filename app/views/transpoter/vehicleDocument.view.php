<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vehicle Documents - TravelMate</title>
  <link rel="stylesheet" href="assets/css/Transpoter/vehicleDocument.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <div class="main-content">
      <div class="progress-container">
        <div class="progress-bar">
          <div class="progress-step">
            <span class="step-number">1</span>
            <span class="step-label">Personal Details</span>
          </div>
          <div class="progress-step active">
            <span class="step-number">2</span>
            <span class="step-label">Vehicle Documents</span>
          </div>
        </div>
      </div>

      <h1 class="page-title">Vehicle Details & Documents</h1>
      <p class="page-subtitle">Please provide your vehicle information and upload required documents</p>

      <form id="vehicle-docs-form">
        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-car"></i>
            Vehicle Information
          </h2>

          <div class="form-row">
            <div class="form-group">
              <label for="vehicle-model">Vehicle Model <span class="required-asterisk">*</span></label>
              <input type="text" id="vehicle-model" name="vehicle_model" placeholder="e.g., Toyota Prius" required>
              <div class="error-message" id="vehicle-model-error"></div>
            </div>

            <div class="form-group">
              <label for="vehicle-year">Manufacturing Year <span class="required-asterisk">*</span></label>
              <input type="number" id="vehicle-year" name="vehicle_year" min="1990" max="2024" placeholder="e.g., 2018"
                required>
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
            <input type="number" id="passenger-count" class="passenger-input" name="passenger_count" min="1" max="50"
              value="2" required>
            <div class="error-message" id="passenger-count-error"></div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-money-bill-wave"></i>
            Pricing
          </h2>

          <div class="form-group">
            <label for="cost-per-km">Cost per 1km (LKR) <span class="required-asterisk">*</span></label>
            <input type="number" id="cost-per-km" name="cost_per_km" min="0.01" step="0.01" placeholder="e.g., 120.00" required>
            <div class="error-message" id="cost-per-km-error"></div>
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
              <input type="radio" id="ac" name="ac-type" value="ac">
              <label for="ac">A/C</label>
            </div>

            <div class="radio-option selected" id="non-ac-option">
              <input type="radio" id="non-ac" name="ac-type" value="non-ac" checked>
              <label for="non-ac">Non-A/C</label>
            </div>
          </div>
          <div class="error-message" id="ac-type-error"></div>
        </div>

        <div class="vehicle-preview">
          <h3 class="preview-title">Vehicle Preview</h3>
          <div class="preview-details">
            <div class="preview-item">
              <h4>Passengers</h4>
              <p id="preview-passengers">2</p>
            </div>
            <div class="preview-item">
              <h4>A/C Type</h4>
              <p id="preview-ac">Non-A/C</p>
            </div>
            <div class="preview-item">
              <h4>Status</h4>
              <p id="preview-status">Incomplete</p>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-file-alt"></i>
            Revenue License
          </h2>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-file-upload"></i>
              <span>Upload Revenue License <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="revenue-license" name="revenue_license" accept=".jpg,.jpeg,.png,.pdf"
              style="display: none;" required>
            <div class="upload-box" id="revenue-license-box"
              onclick="document.getElementById('revenue-license').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG, PNG or PDF (max. 5MB)</p>
            </div>
            <div class="error-message" id="revenue-license-error"></div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-shield-alt"></i>
            Vehicle Insurance
          </h2>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-file-upload"></i>
              <span>Upload Insurance Document <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="insurance" name="insurance" accept=".jpg,.jpeg,.png,.pdf" style="display: none;"
              required>
            <div class="upload-box" id="insurance-box" onclick="document.getElementById('insurance').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG, PNG or PDF (max. 5MB)</p>
            </div>
            <div class="error-message" id="insurance-error"></div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-file-contract"></i>
            Vehicle Registration Document
          </h2>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-file-upload"></i>
              <span>Upload Registration Document <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="registration" name="registration" accept=".jpg,.jpeg,.png,.pdf"
              style="display: none;" required>
            <div class="upload-box" id="registration-box" onclick="document.getElementById('registration').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG, PNG or PDF (max. 5MB)</p>
            </div>
            <div class="error-message" id="registration-error"></div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-images"></i>
            Vehicle Photos
          </h2>
          <p class="section-subtitle">Upload at least 2 photos of your vehicle from different angles</p>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-file-upload"></i>
              <span>Upload Vehicle Photos <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="vehicle-photos" name="vehicle_photos" accept=".jpg,.jpeg,.png" multiple
              style="display: none;" required>
            <div class="upload-box" id="vehicle-photos-box" onclick="document.getElementById('vehicle-photos').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG or PNG (max. 5MB each, min. 2 photos)</p>
            </div>
            <div class="error-message" id="vehicle-photos-error"></div>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="save-btn">Save Draft</button>
          <button type="submit" class="continue-btn">
            Complete Registration <i class="fas fa-check-circle"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <!-- vehicle Registration Success Modal -->
  <div id="vehicleSuccessModal" class="vehicle-success-modal">
    <div class="vehicle-success-content">
      <div class="success-icon">
        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="40" cy="40" r="38" stroke="#10b981" stroke-width="3" fill="#ecfdf5"/>
          <path d="M25 40L35 50L55 30" stroke="#10b981" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <h2>Vehicle Registered Successfully!</h2>
      <p>Your vehicle has been registered and is under review. You will be notified once it's approved.</p>
      <button class="btn-go-dashboard" onclick="goToDashboard()">Go to Dashboard</button>
    </div>
  </div>

  <style>
    /* vehicle Success Modal */
    .vehicle-success-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 9999;
      animation: fadeIn 0.3s ease;
      align-items: center;
      justify-content: center;
    }

    .vehicle-success-modal.show {
      display: flex;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes slideUp {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
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

    .vehicle-success-content {
      background: white;
      border-radius: 20px;
      padding: 48px 40px 40px 40px;
      text-align: center;
      max-width: 480px;
      width: 90%;
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
      animation: slideUp 0.4s ease;
    }

    .vehicle-success-content .success-icon {
      margin-bottom: 28px;
      animation: scaleIn 0.6s ease 0.2s both;
    }

    .vehicle-success-content h2 {
      color: #10b981;
      font-size: 32px;
      margin: 0 0 16px 0;
      font-weight: 700;
    }

    .vehicle-success-content p {
      color: #6b7280;
      font-size: 16px;
      margin: 0 0 32px 0;
      line-height: 1.6;
    }

    .btn-go-dashboard {
      background: #10b981;
      color: white;
      border: none;
      padding: 14px 36px;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 100%;
    }

    .btn-go-dashboard:hover {
      background: #059669;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // form elements
      const form = document.getElementById('vehicle-docs-form');
      const continueBtn = document.querySelector('.continue-btn');
      const saveBtn = document.querySelector('.save-btn');

      // get base URL
      function getBaseUrl() {
        const path = window.location.pathname;
        const parts = path.split('/');
        const publicIndex = parts.indexOf('public');
        if (publicIndex !== -1) {
          return parts.slice(0, publicIndex + 1).join('/');
        }
        return '/TravelMate/public';
      }
      const baseUrl = getBaseUrl();

      // aC type selection
      const acOption = document.getElementById('ac-option');
      const nonAcOption = document.getElementById('non-ac-option');
      const acInput = document.getElementById('ac');
      const nonAcInput = document.getElementById('non-ac');

      if (acOption && nonAcOption) {
        acOption.addEventListener('click', function () {
          acInput.checked = true;
          acOption.classList.add('selected');
          nonAcOption.classList.remove('selected');
          document.getElementById('preview-ac').textContent = 'A/C';
          updatePreviewStatus();
        });

        nonAcOption.addEventListener('click', function () {
          nonAcInput.checked = true;
          nonAcOption.classList.add('selected');
          acOption.classList.remove('selected');
          document.getElementById('preview-ac').textContent = 'Non-A/C';
          updatePreviewStatus();
        });
      }

      // passenger count update
      const passengerCount = document.getElementById('passenger-count');
      if (passengerCount) {
        passengerCount.addEventListener('change', function () {
          const previewEl = document.getElementById('preview-passengers');
          if (previewEl) previewEl.textContent = this.value;
          updatePreviewStatus();
        });
      }

      // file input change handlers
      document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function (e) {
          const file = this.files[0];
          const boxId = this.id + '-box';
          const box = document.getElementById(boxId);
          const errorElement = document.getElementById(this.id + '-error');

          if (file) {
            // validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
              if (errorElement) errorElement.textContent = 'File size must be less than 5MB';
              this.value = '';
              if (box) {
                const uploadText = box.querySelector('.upload-text');
                const uploadIcon = box.querySelector('.upload-icon');
                if (uploadText) uploadText.textContent = 'Click to upload or drag and drop';
                if (uploadIcon) uploadIcon.innerHTML = '<i class="fas fa-cloud-upload-alt"></i>';
              }
              return;
            }

            // validate file type
            let validTypes = [];
            if (this.id === 'vehicle-photos') {
              validTypes = ['image/jpeg', 'image/png'];
            } else {
              validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            }

            if (!validTypes.includes(file.type)) {
              if (errorElement) {
                errorElement.textContent = this.id === 'vehicle-photos'
                  ? 'Only JPG and PNG files are allowed'
                  : 'Only JPG, PNG and PDF files are allowed';
              }
              this.value = '';
              if (box) {
                const uploadText = box.querySelector('.upload-text');
                const uploadIcon = box.querySelector('.upload-icon');
                if (uploadText) uploadText.textContent = 'Click to upload or drag and drop';
                if (uploadIcon) uploadIcon.innerHTML = '<i class="fas fa-cloud-upload-alt"></i>';
              }
              return;
            }

            // for multiple file uploads (vehicle photos)
            if (this.id === 'vehicle-photos' && this.files.length < 2) {
              if (errorElement) errorElement.textContent = 'Please upload at least 2 photos';
              return;
            }

            // clear any previous error
            if (errorElement) errorElement.textContent = '';

            // update UI
            let fileName = file.name;
            if (fileName.length > 25) {
              fileName = fileName.substring(0, 22) + '...';
            }

            if (box) {
              const uploadText = box.querySelector('.upload-text');
              const uploadIcon = box.querySelector('.upload-icon');
              if (uploadText) uploadText.textContent = fileName;
              if (uploadIcon) uploadIcon.innerHTML = '<i class="fas fa-check-circle" style="color: #1abc5b;"></i>';
            }

            // update preview status
            updatePreviewStatus();
          }
        });
      });

      // drag and drop functionality
      document.querySelectorAll('.upload-box').forEach(box => {
        box.addEventListener('dragover', function (e) {
          e.preventDefault();
          this.style.borderColor = '#1abc5b';
          this.style.background = '#e9f7ef';
        });

        box.addEventListener('dragleave', function (e) {
          e.preventDefault();
          this.style.borderColor = '#ccc';
          this.style.background = '#f8f9fa';
        });

        box.addEventListener('drop', function (e) {
          e.preventDefault();
          this.style.borderColor = '#ccc';
          this.style.background = '#f8f9fa';

          const files = e.dataTransfer.files;
          if (files.length) {
            const inputId = this.id.replace('-box', '');
            const input = document.getElementById(inputId);

            if (input) {
              const dataTransfer = new DataTransfer();
              if (input.multiple) {
                for (let i = 0; i < Math.min(files.length, 5); i++) {
                  dataTransfer.items.add(files[i]);
                }
              } else {
                dataTransfer.items.add(files[0]);
              }
              input.files = dataTransfer.files;
              const event = new Event('change');
              input.dispatchEvent(event);
            }
          }
        });
      });

      // input validation
      document.querySelectorAll('input[type="text"], input[type="number"], select').forEach(input => {
        input.addEventListener('blur', function () {
          validateField(this);
          updatePreviewStatus();
        });

        input.addEventListener('input', function () {
          const errorElement = document.getElementById(this.id + '-error');
          if (errorElement) {
            errorElement.textContent = '';
          }
          this.style.borderColor = '#ddd';
          updatePreviewStatus();
        });
      });

      // form submission - THIS IS THE IMPORTANT PART
      if (form) {
        form.addEventListener('submit', function (e) {
          e.preventDefault();

          console.log('Form submitted - starting API call');

          let isValid = true;

          // validate all required fields
          const requiredFields = this.querySelectorAll('[required]');
          requiredFields.forEach(field => {
            if (!validateField(field)) {
              isValid = false;
            }
          });

          // validate vehicle number format
          const vehicleNumber = document.getElementById('vehicle-number');
          if (vehicleNumber && vehicleNumber.value && !validateVehicleNumber(vehicleNumber.value)) {
            const errorEl = document.getElementById('vehicle-number-error');
            if (errorEl) errorEl.textContent = 'Please enter a valid vehicle number (e.g., ABC-1234)';
            vehicleNumber.style.borderColor = '#e74c3c';
            isValid = false;
          }

          // validate at least 2 vehicle photos
          const vehiclePhotos = document.getElementById('vehicle-photos');
          if (vehiclePhotos && vehiclePhotos.files.length < 2) {
            const errorEl = document.getElementById('vehicle-photos-error');
            if (errorEl) errorEl.textContent = 'Please upload at least 2 photos of your vehicle';
            isValid = false;
          }

          if (!isValid) {
            const firstError = document.querySelector('.error-message:not(:empty)');
            if (firstError) {
              firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return; // sTOP HERE IF INVALID
          }

          // show loading state
          if (continueBtn) {
            continueBtn.innerHTML = 'Processing... <i class="fas fa-spinner fa-spin"></i>';
            continueBtn.disabled = true;
          }

          // build FormData with ALL data
          const fd = new FormData();

          // add stored session data
          fd.append('vehicle_type', sessionStorage.getItem('vehicleType') || '');
          fd.append('working_district', sessionStorage.getItem('working_district') || '');
          fd.append('ac_type', sessionStorage.getItem('ac_type') || 'non-ac');

          // add fields from current form
          const inputs = this.querySelectorAll('input, select, textarea');
          inputs.forEach(input => {
            if (input.type === 'file') {
              if (input.files && input.files.length > 0) {
                if (input.multiple) {
                  Array.from(input.files).forEach((file, index) => {
                    fd.append(input.name + '[]', file);
                  });
                } else {
                  fd.append(input.name, input.files[0]);
                }
              }
            } else if (input.type === 'radio') {
              if (input.checked && input.name) {
                fd.append(input.name, input.value);
              }
            } else if (input.name) {
              fd.append(input.name, input.value || '');
            }
          });

          // log what we're sending
          console.log('Sending data to API:', baseUrl + '/api/vehicle/create');
          for (let [key, value] of fd.entries()) {
            if (value instanceof File) {
              console.log(key + ':', value.name, '(' + value.size + ' bytes)');
            } else {
              console.log(key + ':', value);
            }
          }

          // rEPLACE THE ENTIRE fetch() CALL WITH THIS:
          fetch(baseUrl + '/api/vehicle/create', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin'
          })
            .then(response => {
              console.log('Response status:', response.status);
              return response.text();
            })
            .then(text => {
              console.log('Raw response:', text);
              try {
                const data = JSON.parse(text);
                console.log('Parsed response:', data);

                if (data.success) {
                  sessionStorage.clear();
                  localStorage.removeItem('vehicleDetailsDraft');
                  
                  // show success modal
                  showVehicleSuccessModal();
                } else {
                  const errorMsg = data.errors && data.errors.error ? data.errors.error : 'Failed to save vehicle';
                  console.error('API Error:', data.errors);
                  alert(errorMsg);

                  if (continueBtn) {
                    continueBtn.innerHTML = 'Complete Registration <i class="fas fa-check-circle"></i>';
                    continueBtn.disabled = false;
                  }
                }
              } catch (e) {
                console.error('JSON parse error:', e);
                alert('Invalid response from server');
                if (continueBtn) {
                  continueBtn.innerHTML = 'Complete Registration <i class="fas fa-check-circle"></i>';
                  continueBtn.disabled = false;
                }
              }
            })
            .catch(err => {
              console.error('Fetch error:', err);
              alert('Network error. Please try again.');

              if (continueBtn) {
                continueBtn.innerHTML = 'Complete Registration <i class="fas fa-check-circle"></i>';
                continueBtn.disabled = false;
              }
            });
        });
      }

      // save draft functionality
      if (saveBtn) {
        saveBtn.addEventListener('click', function () {
          const formData = new FormData(form);
          const formObject = {};

          for (let [key, value] of formData.entries()) {
            if (!(value instanceof File)) {
              formObject[key] = value;
            }
          }

          localStorage.setItem('vehicleDetailsDraft', JSON.stringify(formObject));

          const originalText = saveBtn.innerHTML;
          saveBtn.innerHTML = '<i class="fas fa-check"></i> Saved';
          saveBtn.style.background = '#1abc5b';

          setTimeout(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.style.background = '#95a5a6';
          }, 2000);
        });
      }

      // load draft if exists
      const draft = localStorage.getItem('vehicleDetailsDraft');
      if (draft) {
        try {
          const formObject = JSON.parse(draft);
          Object.keys(formObject).forEach(key => {
            const element = document.querySelector(`[name="${key}"]`);
            if (element && element.type !== 'file') {
              element.value = formObject[key];
              if (element.id === 'passenger-count') {
                const previewEl = document.getElementById('preview-passengers');
                if (previewEl) previewEl.textContent = element.value;
              }
            }
          });
          updatePreviewStatus();
        } catch (e) {
          console.error('Error loading draft:', e);
        }
      }

      // helper functions
      function validateField(field) {
        if (!field) return true;

        const errorElement = document.getElementById(field.id + '-error');

        if (field.hasAttribute('required') && !field.value) {
          if (errorElement) errorElement.textContent = 'This field is required';
          field.style.borderColor = '#e74c3c';
          return false;
        }

        if (field.id === 'vehicle-number' && field.value && !validateVehicleNumber(field.value)) {
          if (errorElement) errorElement.textContent = 'Please enter a valid vehicle number (e.g., ABC-1234)';
          field.style.borderColor = '#e74c3c';
          return false;
        }

        if (field.id === 'vehicle-year' && field.value) {
          const year = parseInt(field.value);
          const currentYear = new Date().getFullYear();
          if (year < 1990 || year > currentYear) {
            if (errorElement) errorElement.textContent = `Please enter a year between 1990 and ${currentYear}`;
            field.style.borderColor = '#e74c3c';
            return false;
          }
        }

        if (field.id === 'cost-per-km' && field.value) {
          const cost = parseFloat(field.value);
          if (isNaN(cost) || cost <= 0) {
            if (errorElement) errorElement.textContent = 'Please enter a valid amount greater than 0';
            field.style.borderColor = '#e74c3c';
            return false;
          }
        }

        if (errorElement) errorElement.textContent = '';
        field.style.borderColor = '#ddd';
        return true;
      }

      function validateVehicleNumber(number) {
        const pattern = /^[A-Z]{2,3}-[0-9]{4}$/;
        return pattern.test(number);
      }

      function updatePreviewStatus() {
        const requiredFields = [
          document.getElementById('vehicle-model')?.value,
          document.getElementById('vehicle-year')?.value,
          document.getElementById('vehicle-color')?.value,
          document.getElementById('vehicle-number')?.value,
          document.getElementById('cost-per-km')?.value,
          document.getElementById('revenue-license')?.files.length,
          document.getElementById('insurance')?.files.length,
          document.getElementById('registration')?.files.length,
          (document.getElementById('vehicle-photos')?.files.length || 0) >= 2
        ];

        const isComplete = requiredFields.every(field => field);
        const statusEl = document.getElementById('preview-status');
        if (statusEl) {
          statusEl.textContent = isComplete ? 'Complete' : 'Incomplete';
          statusEl.style.color = isComplete ? '#1abc5b' : '#e74c3c';
        }

        const previewBox = document.querySelector('.vehicle-preview');
        if (previewBox) {
          if (isComplete) {
            previewBox.classList.add('updated');
          } else {
            previewBox.classList.remove('updated');
          }
        }
      }

      // initial preview status update
      updatePreviewStatus();

      // responsive adjustments
      function handleResponsive() {
        if (window.innerWidth < 768) {
          document.querySelectorAll('.section-title').forEach(title => {
            title.classList.add('mobile-view');
          });
          document.querySelectorAll('.form-row').forEach(row => {
            row.style.gridTemplateColumns = '1fr';
          });
        } else {
          document.querySelectorAll('.section-title').forEach(title => {
            title.classList.remove('mobile-view');
          });
          document.querySelectorAll('.form-row').forEach(row => {
            row.style.gridTemplateColumns = '1fr 1fr';
          });
        }
      }

      handleResponsive();
      window.addEventListener('resize', handleResponsive);
    });

    // show vehicle registration success modal
    function showVehicleSuccessModal() {
      const modal = document.getElementById('vehicleSuccessModal');
      if (modal) {
        modal.classList.add('show');
      }
    }

    // redirect to dashboard
    function goToDashboard() {
      window.location.href = 'tr_dashboard';
    }
  </script>
</body>

</html>