<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personal Documents - TravelMate</title>
  <link rel="stylesheet" href="assets/css/Transpoter/personalDetails.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <div class="main-content">
      <div class="progress-container">
        <div class="progress-bar">
          <div class="progress-step active">
            <span class="step-number">1</span>
            <span class="step-label">Personal Details</span>
          </div>
          <div class="progress-step">
            <span class="step-number">2</span>
            <span class="step-label">Vehicle Documents</span>
          </div>
        </div>
      </div>

      <h1 class="page-title">Personal Details & Documents</h1>
      <p class="page-subtitle">Please provide your personal information and upload required documents</p>

      <form id="personal-docs-form">
        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-briefcase"></i>
            Working District
          </h2>

          <div class="form-group">
            <label for="working-district">Select your working district <span class="required-asterisk">*</span></label>
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
            <div class="error-message" id="district-error"></div>
          </div>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-user-circle"></i>
              <span>Profile Photo</span>
            </div>
            <input type="file" id="profile-photo" name="profile_photo" accept="image/jpeg,image/png"
              style="display: none;">
            <div class="upload-box" id="profile-photo-box" onclick="document.getElementById('profile-photo').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG or PNG (max. 5MB)</p>
            </div>
            <div class="error-message" id="profile-photo-error"></div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-id-card"></i>
            Driving License
          </h2>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-id-card"></i>
              <span>License - Front <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="license-front" name="license_front" accept="image/jpeg,image/png"
              style="display: none;">
            <div class="upload-box" id="license-front-box" onclick="document.getElementById('license-front').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG or PNG (max. 5MB)</p>
            </div>
            <div class="error-message" id="license-front-error"></div>
          </div>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-id-card"></i>
              <span>License - Rear <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="license-rear" name="license_rear" accept="image/jpeg,image/png"
              style="display: none;">
            <div class="upload-box" id="license-rear-box" onclick="document.getElementById('license-rear').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG or PNG (max. 5MB)</p>
            </div>
            <div class="error-message" id="license-rear-error"></div>
          </div>

          <div class="form-group">
            <label for="license-number">Driving License Number <span class="required-asterisk">*</span></label>
            <input type="text" id="license-number" name="license_number" placeholder="Enter your driving license number"
              required>
            <div class="error-message" id="license-number-error"></div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-address-card"></i>
            National Identity Card - NIC
          </h2>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-address-card"></i>
              <span>NIC - Front <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="nic-front" name="nic_front" accept="image/jpeg,image/png" style="display: none;">
            <div class="upload-box" id="nic-front-box" onclick="document.getElementById('nic-front').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG or PNG (max. 5MB)</p>
            </div>
            <div class="error-message" id="nic-front-error"></div>
          </div>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-address-card"></i>
              <span>NIC - Rear <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="nic-rear" name="nic_rear" accept="image/jpeg,image/png" style="display: none;">
            <div class="upload-box" id="nic-rear-box" onclick="document.getElementById('nic-rear').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG or PNG (max. 5MB)</p>
            </div>
            <div class="error-message" id="nic-rear-error"></div>
          </div>

          <div class="form-group">
            <label for="nic-number">NIC Number <span class="required-asterisk">*</span></label>
            <input type="text" id="nic-number" name="nic_number" placeholder="Enter your NIC number" required>
            <div class="error-message" id="nic-number-error"></div>
          </div>
        </div>

        <h1 class="page-title">
          <i class="fas fa-car"></i>
          Vehicle Owner's Document Upload
        </h1>

        <div class="section">
          <h2 class="section-title">
            <i class="fas fa-address-card"></i>
            Vehicle Owner's NIC
          </h2>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-address-card"></i>
              <span>NIC - Front <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="owner-nic-front" name="owner_nic_front" accept="image/jpeg,image/png"
              style="display: none;">
            <div class="upload-box" id="owner-nic-front-box"
              onclick="document.getElementById('owner-nic-front').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG or PNG (max. 5MB)</p>
            </div>
            <div class="error-message" id="owner-nic-front-error"></div>
          </div>

          <div class="upload-group">
            <div class="upload-title">
              <i class="fas fa-address-card"></i>
              <span>NIC - Rear <span class="required-asterisk">*</span></span>
            </div>
            <input type="file" id="owner-nic-rear" name="owner_nic_rear" accept="image/jpeg,image/png"
              style="display: none;">
            <div class="upload-box" id="owner-nic-rear-box" onclick="document.getElementById('owner-nic-rear').click()">
              <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <p class="upload-text">Click to upload or drag and drop</p>
              <p class="upload-subtext">JPG or PNG (max. 5MB)</p>
            </div>
            <div class="error-message" id="owner-nic-rear-error"></div>
          </div>

          <div class="form-group">
            <label for="owner-nic-number">NIC Number <span class="required-asterisk">*</span></label>
            <input type="text" id="owner-nic-number" name="owner_nic_number"
              placeholder="Enter vehicle owner's NIC number" required>
            <div class="error-message" id="owner-nic-number-error"></div>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="save-btn">Save Draft</button>
          <button type="submit" class="continue-btn">
            Continue <i class="fas fa-arrow-right"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // form elements
      const form = document.getElementById('personal-docs-form');
      const continueBtn = document.querySelector('.continue-btn');
      const saveBtn = document.querySelector('.save-btn');

      document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function (e) {
          const file = this.files[0];
          const boxId = this.id + '-box';
          const box = document.getElementById(boxId);
          const errorElement = document.getElementById(this.id + '-error');

          if (file) {
            // validate file size
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

            const validTypes = ['image/jpeg', 'image/png'];
            if (!validTypes.includes(file.type)) {
              if (errorElement) errorElement.textContent = 'Only JPG and PNG files are allowed';
              this.value = '';
              if (box) {
                const uploadText = box.querySelector('.upload-text');
                const uploadIcon = box.querySelector('.upload-icon');
                if (uploadText) uploadText.textContent = 'Click to upload or drag and drop';
                if (uploadIcon) uploadIcon.innerHTML = '<i class="fas fa-cloud-upload-alt"></i>';
              }
              return;
            }

            if (errorElement) errorElement.textContent = '';

    
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

            previewImage(file, box);
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
              dataTransfer.items.add(files[0]);
              input.files = dataTransfer.files;

              // trigger change event
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
        });

        input.addEventListener('input', function () {
        
          const errorElement = document.getElementById(this.id + '-error');
          if (errorElement) {
            errorElement.textContent = '';
          }
          this.style.borderColor = '#ddd';
        });
      });

      // form submission
      if (form) {
        form.addEventListener('submit', function (e) {
          e.preventDefault();

          let isValid = true;

          // validate all text/number/select required fields
          const requiredFields = this.querySelectorAll('input[type="text"][required], input[type="number"][required], select[required]');
          requiredFields.forEach(field => {
            if (!validateField(field)) {
              isValid = false;
            }
          });

          const fileChecks = [
            { id: 'license-front', errId: 'license-front-error', label: 'License (front)' },
            { id: 'license-rear', errId: 'license-rear-error', label: 'License (rear)' },
            { id: 'nic-front', errId: 'nic-front-error', label: 'NIC (front)' },
            { id: 'nic-rear', errId: 'nic-rear-error', label: 'NIC (rear)' },
            { id: 'owner-nic-front', errId: 'owner-nic-front-error', label: "Owner's NIC (front)" },
            { id: 'owner-nic-rear', errId: 'owner-nic-rear-error', label: "Owner's NIC (rear)" }
          ];

          fileChecks.forEach(f => {
            const input = document.getElementById(f.id);
            const errEl = document.getElementById(f.errId);
            if (input && errEl) {
              if (!input.files || input.files.length === 0) {
                errEl.textContent = f.label + ' is required';
                isValid = false;
              } else {
                errEl.textContent = '';
              }
            }
          });

          if (isValid) {
      
            if (continueBtn) {
              continueBtn.innerHTML = 'Processing... <i class="fas fa-spinner fa-spin"></i>';
              continueBtn.disabled = true;
            }

            // store form data in sessionStorage
            const formData = new FormData(form);
            sessionStorage.setItem('working_district', formData.get('working_district') || '');
            sessionStorage.setItem('license_number', formData.get('license_number') || '');
            sessionStorage.setItem('nic_number', formData.get('nic_number') || '');
            sessionStorage.setItem('owner_nic_number', formData.get('owner_nic_number') || '');

            setTimeout(() => {
              window.location.href = 'vehicleDocument';
            }, 500);
          } else {
        
            const firstError = document.querySelector('.error-message:not(:empty)');
            if (firstError) {
              firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
          }
        });
      }


      if (saveBtn) {
        saveBtn.addEventListener('click', function () {
          // save form data to local storage
          const formData = new FormData(form);
          const formObject = {};

        
          for (let [key, value] of formData.entries()) {
            if (value instanceof File) continue;
            formObject[key] = value;
          }

          localStorage.setItem('personalDetailsDraft', JSON.stringify(formObject));

          // show confirmation
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
      const draft = localStorage.getItem('personalDetailsDraft');
      if (draft) {
        try {
          const formObject = JSON.parse(draft);
          Object.keys(formObject).forEach(key => {
            const element = document.querySelector(`[name="${key}"]`);
            if (element && element.type !== 'file') {
              element.value = formObject[key];
            }
          });
        } catch (e) {
          console.error('Error loading draft:', e);
        }
      }

      // helper functions
      function validateField(field) {
        if (!field) return true;

        const errorElement = document.getElementById(field.id + '-error');

        if (field.hasAttribute('required') && !field.value.trim()) {
          if (errorElement) errorElement.textContent = 'This field is required';
          field.style.borderColor = '#e74c3c';
          return false;
        }

        // field specific validations
        if (field.id === 'nic-number' && field.value && !validateNIC(field.value)) {
          if (errorElement) errorElement.textContent = 'Please enter a valid NIC number';
          field.style.borderColor = '#e74c3c';
          return false;
        }

        if (field.id === 'owner-nic-number' && field.value && !validateNIC(field.value)) {
          if (errorElement) errorElement.textContent = 'Please enter a valid NIC number';
          field.style.borderColor = '#e74c3c';
          return false;
        }

        if (field.id === 'license-number' && field.value && !validateLicenseNumber(field.value)) {
          if (errorElement) errorElement.textContent = 'Please enter a valid license number';
          field.style.borderColor = '#e74c3c';
          return false;
        }

        if (errorElement) errorElement.textContent = '';
        field.style.borderColor = '#ddd';
        return true;
      }

      function validateNIC(nic) {
       
        const oldPattern = /^[0-9]{9}[vVxX]$/;
        const newPattern = /^[0-9]{12}$/;
        return oldPattern.test(nic) || newPattern.test(nic);
      }

      function validateLicenseNumber(license) {
        
        return license.length >= 5;
      }

      function previewImage(file, box) {
        if (!box) return;

        
        if (file.type.match('image.*')) {
          const reader = new FileReader();

          reader.onload = function (e) {
            
            let previewImg = box.querySelector('.preview-img');
            if (!previewImg) {
              previewImg = document.createElement('img');
              previewImg.className = 'preview-img';
              previewImg.style.cssText = 'max-width: 100%; max-height: 150px; margin-top: 10px; border-radius: 4px;';
              box.appendChild(previewImg);
            }

            previewImg.src = e.target.result;
          };

          reader.readAsDataURL(file);
        }
      }

      // responsive adjustments
      function handleResponsive() {
        if (window.innerWidth < 768) {
          document.querySelectorAll('.section-title').forEach(title => {
            title.classList.add('mobile-view');
          });
        } else {
          document.querySelectorAll('.section-title').forEach(title => {
            title.classList.remove('mobile-view');
          });
        }
      }

      // initial call and event listener for responsiveness
      handleResponsive();
      window.addEventListener('resize', handleResponsive);
    });
  </script>
</body>

</html>