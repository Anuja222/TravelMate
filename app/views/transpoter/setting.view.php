<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Settings</title>
  <link rel="stylesheet" href="assets/css/Transpoter/setting.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <!-- Toast notification -->
  <div class="toast" id="toast"></div>
  
  <!-- Delete Account Modal -->
  <div class="modal" id="deleteAccountModal">
    <div class="modal-content">
      <h3><i class="fas fa-exclamation-triangle"></i> Delete Account</h3>
      <p>Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently lost.</p>
      <div class="modal-buttons">
        <button class="delete-btn" id="confirmDeleteBtn">
          <i class="fas fa-trash-alt"></i> Yes, Delete My Account
        </button>
        <button class="save-btn modal-cancel" id="cancelDeleteBtn">
          <i class="fas fa-times"></i> Cancel
        </button>
      </div>
    </div>
  </div>
  
  <!-- MAIN CONTENT -->
  <main>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-inner">
          <div class="sidebar-menu">
            <a href="/TravelMate/public/tr_dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="/TravelMate/public/bookingnew"><i class="fas fa-calendar-alt"></i> Bookings</a>
            <a href="/TravelMate/public/payment-history"><i class="fas fa-credit-card"></i> Payment History</a>
            <a href="/TravelMate/public/statistics"><i class="fas fa-chart-line"></i> Statistics</a>
            <a href="/TravelMate/public/setting"><i class="fas fa-cog"></i> Settings</a>
          </div>
        </div>
    </aside>

    <div class="content">
        <div class="page-title">
          <h1><i class="fas fa-cog"></i> Account Settings</h1>
          <p>Manage your account preferences and settings</p>
        </div>

        <!-- Tab Navigation -->
        <div class="settings-tabs">
          <button class="tab-btn active" data-tab="profile">
            <i class="fas fa-user"></i>
            <span>Profile</span>
          </button>
          <button class="tab-btn" data-tab="security">
            <i class="fas fa-lock"></i>
            <span>Security</span>
          </button>
          <button class="tab-btn" data-tab="notifications">
            <i class="fas fa-bell"></i>
            <span>Notifications</span>
          </button>
          <button class="tab-btn danger-tab" data-tab="delete">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Delete Account</span>
          </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
          <!-- Profile Tab -->
          <div class="tab-pane active" id="profile-tab">
            <section class="settings-section">
              <div class="section-header">
                <i class="fas fa-user"></i>
                <h2>Profile Information</h2>
              </div>
              
              <div class="profile-layout">
                <!-- Left Column - Profile Photo -->
                <div class="profile-photo-column">
                  <div class="profile-photo-container">
                    <div class="photo-preview" id="photoPreview">
                      <svg class="default-avatar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                      </svg>
                    </div>
                    <div class="photo-upload-controls">
                      <label for="profilePhoto" class="btn-upload">
                        <i class="fas fa-upload"></i> Change
                      </label>
                      <button type="button" class="btn-remove" id="removePhoto" style="display: none;">
                        <i class="fas fa-trash"></i> Remove
                      </button>
                    </div>
                    <p class="upload-hint">JPG, PNG or GIF (max. 5MB)</p>
                    <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*" style="display: none;">
                    <div class="error-message" id="photoError"></div>
                  </div>
                </div>

                <!-- Right Column - Profile Details -->
                <div class="profile-details-column">
                  <form class="profile-form" id="profileForm">
                    <div class="form-row">
                      <div class="form-group" id="firstNameGroup">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
                        <div class="error-message" id="firstNameError"></div>
                      </div>
                      <div class="form-group" id="lastNameGroup">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
                        <div class="error-message" id="lastNameError"></div>
                      </div>
                    </div>

                    <div class="form-group" id="emailGroup">
                      <label for="email">Email Address</label>
                      <input type="email" id="email" name="email" placeholder="Enter your email" required>
                      <div class="error-message" id="emailError"></div>
                    </div>

                    <div class="form-group" id="phoneGroup">
                      <label for="phone">Phone Number</label>
                      <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                      <div class="error-message" id="phoneError"></div>
                    </div>

                    <div class="form-row">
                      <div class="form-group" id="dobGroup">
                        <label for="dateOfBirth">Date of Birth</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth" required>
                        <div class="error-message" id="dobError"></div>
                      </div>
                      <div class="form-group" id="genderGroup">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                          <option value="">Select Gender</option>
                          <option value="male">Male</option>
                          <option value="female">Female</option>
                          <option value="other">Other</option>
                          <option value="prefer-not-to-say">Prefer not to say</option>
                        </select>
                        <div class="error-message" id="genderError"></div>
                      </div>
                    </div>
                    
                    <div class="form-actions">
                      <button type="submit" class="save-btn" id="profileSaveBtn">
                        <span class="spinner" id="profileSpinner" style="display: none;"></span>
                        <i class="fas fa-save"></i> Save Changes
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </section>
          </div>

          <!-- Security Tab -->
          <div class="tab-pane" id="security-tab">
            <section class="settings-section">
              <div class="section-header">
                <i class="fas fa-lock"></i>
                <h2>Password & Security</h2>
              </div>
              <form class="auth-form" id="securityForm">
                <div class="form-group" id="currentPasswordGroup">
                  <label for="current_password">Current Password</label>
                  <input type="password" id="current_password" name="current_password" required>
                  <div class="error-message" id="currentPasswordError"></div>
                </div>

                <div class="form-group" id="newPasswordGroup">
                  <label for="new_password">New Password</label>
                  <input type="password" id="new_password" name="new_password" required>
                  <div class="error-message" id="newPasswordError"></div>
                  <div class="password-strength" id="passwordStrength"></div>
                  <div class="password-strength-text" id="passwordStrengthText"></div>
                </div>

                <div class="form-group" id="confirmPasswordGroup">
                  <label for="confirm_password">Confirm New Password</label>
                  <input type="password" id="confirm_password" name="confirm_password" required>
                  <div class="error-message" id="confirmPasswordError"></div>
                </div>
                
                <div class="form-actions">
                  <button type="submit" class="save-btn" id="securitySaveBtn">
                    <span class="spinner" id="securitySpinner" style="display: none;"></span>
                    <i class="fas fa-key"></i> Update Password
                  </button>
                  <button type="reset" class="cancel-btn"><i class="fas fa-times"></i> Cancel</button>
                </div>
              </form>
            </section>
          </div>

         <!-- Notifications Tab -->
          <div class="tab-pane" id="notifications-tab">
            <section class="settings-section">
              <div class="section-header">
                <i class="fas fa-bell"></i>
                <h2>Notification Preferences</h2>
              </div>
              <form class="auth-form" id="notificationForm">
                
                <div class="notification-category">
                  <h4><i class="fas fa-envelope"></i> Email Notifications</h4>
                  
                  <div class="toggle-item">
                    <div class="toggle-info">
                      <span class="toggle-title">Booking confirmations & updates</span>
                      <span class="toggle-description">Receive email updates about your bookings</span>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" name="email_booking" checked>
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                  
                  <div class="toggle-item">
                    <div class="toggle-info">
                      <span class="toggle-title">Promotions and special offers</span>
                      <span class="toggle-description">Get exclusive deals and discounts</span>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" name="email_promotions" checked>
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                  
                  <div class="toggle-item">
                    <div class="toggle-info">
                      <span class="toggle-title">Travel newsletter</span>
                      <span class="toggle-description">Weekly travel tips and inspiration</span>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" name="email_newsletter">
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                </div>
                
                <div class="notification-category">
                  <h4><i class="fas fa-mobile-alt"></i> Push Notifications</h4>
                  
                  <div class="toggle-item">
                    <div class="toggle-info">
                      <span class="toggle-title">Booking reminders</span>
                      <span class="toggle-description">Get notified before your trips</span>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" name="push_booking" checked>
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                  
                  <div class="toggle-item">
                    <div class="toggle-info">
                      <span class="toggle-title">Exclusive deals</span>
                      <span class="toggle-description">Flash sales and limited time offers</span>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" name="push_deals">
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                  
                  <div class="toggle-item">
                    <div class="toggle-info">
                      <span class="toggle-title">Security alerts</span>
                      <span class="toggle-description">Important account security updates</span>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" name="push_security" checked>
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                </div>
                
                <div class="notification-category">
                  <h4><i class="fas fa-comment-alt"></i> SMS Notifications</h4>
                  
                  <div class="toggle-item">
                    <div class="toggle-info">
                      <span class="toggle-title">Booking confirmations</span>
                      <span class="toggle-description">Instant SMS for new bookings</span>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" name="sms_booking">
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                  
                  <div class="toggle-item">
                    <div class="toggle-info">
                      <span class="toggle-title">Travel reminders</span>
                      <span class="toggle-description">24-hour reminders before departure</span>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" name="sms_reminders">
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                </div>
                
                <div class="form-actions">
                  <button type="submit" class="save-btn" id="notificationSaveBtn">
                    <span class="spinner" id="notificationSpinner" style="display: none;"></span>
                    <i class="fas fa-save"></i> Save Preferences
                  </button>
                  <button type="reset" class="cancel-btn"><i class="fas fa-times"></i> Reset</button>
                </div>
              </form>
            </section>
          </div>

          <!-- Delete Account Tab -->
          <div class="tab-pane" id="delete-tab">
            <section class="settings-section delete-account-section">
              <div class="section-header">
                <i class="fas fa-exclamation-triangle" style="color: #b91c1c;"></i>
                <h2 style="color: #b91c1c;">Delete Account</h2>
              </div>
              
              <div class="delete-warning">
                <h4><i class="fas fa-exclamation-circle"></i> Warning</h4>
                <p>Once you delete your account, there is no going back. This will permanently delete:</p>
                <ul class="delete-list">
                  <li><i class="fas fa-times-circle"></i> Your profile information</li>
                  <li><i class="fas fa-times-circle"></i> All booking history</li>
                  <li><i class="fas fa-times-circle"></i> Payment methods and preferences</li>
                </ul>
              </div>
              
              <form id="deleteAccountForm">
                <div class="delete-reason">
                  <label>Please tell us why you're leaving:</label>
                  <div class="reason-radios">
                    <div class="reason-radio">
                      <input type="radio" id="reason1" name="delete_reason" value="privacy">
                      <label for="reason1">Privacy concerns</label>
                    </div>
                    <div class="reason-radio">
                      <input type="radio" id="reason2" name="delete_reason" value="service">
                      <label for="reason2">Dissatisfied with service</label>
                    </div>
                    <div class="reason-radio">
                      <input type="radio" id="reason3" name="delete_reason" value="usage">
                      <label for="reason3">I don't use this account anymore</label>
                    </div>
                    <div class="reason-radio">
                      <input type="radio" id="reason4" name="delete_reason" value="other">
                      <label for="reason4">Other reason</label>
                    </div>
                  </div>
                </div>
                
                <div class="feedback-input form-group" id="feedbackGroup" style="display: none;">
                  <label for="feedback">Please specify:</label>
                  <textarea id="feedback" name="feedback" rows="3" placeholder="Tell us how we can improve..."></textarea>
                  <div class="error-message" id="feedbackError"></div>
                </div>
                
                <div class="confirm-delete">
                  <p class="confirm-text">To confirm deletion, type "DELETE" in the box below:</p>
                  <div class="form-group" id="confirmDeleteGroup">
                    <input type="text" id="confirmDelete" name="confirmDelete" placeholder="Type DELETE here">
                    <div class="error-message" id="confirmDeleteError"></div>
                  </div>
                </div>
                
                <button type="button" class="delete-btn" id="deleteAccountBtn">
                  <i class="fas fa-trash-alt"></i> Delete My Account
                </button>
              </form>
            </section>
          </div>
        </div>
    </div>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
  // DOM Elements
  const profileForm = document.getElementById('profileForm');
  const securityForm = document.getElementById('securityForm');
  const notificationForm = document.getElementById('notificationForm');
  const deleteAccountForm = document.getElementById('deleteAccountForm');
  const toast = document.getElementById('toast');
  const deleteAccountModal = document.getElementById('deleteAccountModal');
  const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
  const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
  const deleteAccountBtn = document.getElementById('deleteAccountBtn');
  const profilePhoto = document.getElementById('profilePhoto');
  const photoPreview = document.getElementById('photoPreview');
  const removePhotoBtn = document.getElementById('removePhoto');
  const newPasswordInput = document.getElementById('new_password');
  const passwordStrength = document.getElementById('passwordStrength');
  const passwordStrengthText = document.getElementById('passwordStrengthText');
  const reasonRadios = document.querySelectorAll('input[name="delete_reason"]');
  const feedbackGroup = document.getElementById('feedbackGroup');

  // Tab functionality
  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabPanes = document.querySelectorAll('.tab-pane');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      // Remove active class from all tabs and panes
      tabBtns.forEach(b => b.classList.remove('active'));
      tabPanes.forEach(p => p.classList.remove('active'));

      // Add active class to clicked tab
      btn.classList.add('active');

      // Show corresponding pane
      const tabId = btn.getAttribute('data-tab');
      document.getElementById(`${tabId}-tab`).classList.add('active');
    });
  });

  // Event Listeners
  document.addEventListener('DOMContentLoaded', initApp);
  profileForm.addEventListener('submit', handleProfileSubmit);
  securityForm.addEventListener('submit', handleSecuritySubmit);
  notificationForm.addEventListener('submit', handleNotificationSubmit);
  deleteAccountBtn.addEventListener('click', showDeleteModal);
  confirmDeleteBtn.addEventListener('click', handleAccountDeletion);
  cancelDeleteBtn.addEventListener('click', hideDeleteModal);
  profilePhoto.addEventListener('change', handleProfilePhotoUpload);
  removePhotoBtn.addEventListener('click', removeProfilePhoto);
  newPasswordInput.addEventListener('input', checkPasswordStrength);
  reasonRadios.forEach(radio => radio.addEventListener('change', toggleFeedbackField));

  // Initialize the application
  function initApp() {
    loadSavedSettings();
    
    window.addEventListener('click', (e) => {
      if (e.target === deleteAccountModal) {
        hideDeleteModal();
      }
    });
  }

  // Load saved settings from localStorage
  function loadSavedSettings() {
    const savedProfile = JSON.parse(localStorage.getItem('profileSettings')) || {};
    if (savedProfile.firstName) document.getElementById('firstName').value = savedProfile.firstName;
    if (savedProfile.lastName) document.getElementById('lastName').value = savedProfile.lastName;
    if (savedProfile.email) document.getElementById('email').value = savedProfile.email;
    if (savedProfile.phone) document.getElementById('phone').value = savedProfile.phone;
    if (savedProfile.dateOfBirth) document.getElementById('dateOfBirth').value = savedProfile.dateOfBirth;
    if (savedProfile.gender) document.getElementById('gender').value = savedProfile.gender;
    
    const savedNotifications = JSON.parse(localStorage.getItem('notificationSettings')) || {};
    Object.keys(savedNotifications).forEach(key => {
      const checkbox = document.querySelector(`[name="${key}"]`);
      if (checkbox) checkbox.checked = savedNotifications[key];
    });
    
    const savedPhoto = localStorage.getItem('profilePhoto');
    if (savedPhoto) {
      photoPreview.innerHTML = `<img src="${savedPhoto}" alt="Profile Photo">`;
      removePhotoBtn.style.display = 'block';
    }
  }

  // Handle profile form submission
  function handleProfileSubmit(e) {
    e.preventDefault();
    
    if (validateProfileForm()) {
      const submitBtn = document.getElementById('profileSaveBtn');
      const spinner = document.getElementById('profileSpinner');
      
      submitBtn.disabled = true;
      spinner.style.display = 'inline-block';
      
      setTimeout(() => {
        const formData = {
          firstName: document.getElementById('firstName').value,
          lastName: document.getElementById('lastName').value,
          email: document.getElementById('email').value,
          phone: document.getElementById('phone').value,
          dateOfBirth: document.getElementById('dateOfBirth').value,
          gender: document.getElementById('gender').value
        };
        
        localStorage.setItem('profileSettings', JSON.stringify(formData));
        
        submitBtn.disabled = false;
        spinner.style.display = 'none';
        
        showToast('Profile updated successfully!', 'success');
      }, 1500);
    }
  }

  // Validate profile form
  function validateProfileForm() {
    let isValid = true;
    
    resetErrors('profileForm');
    
    const firstName = document.getElementById('firstName').value.trim();
    if (!firstName) {
      showError('firstNameGroup', 'First name is required');
      isValid = false;
    }
    
    const lastName = document.getElementById('lastName').value.trim();
    if (!lastName) {
      showError('lastNameGroup', 'Last name is required');
      isValid = false;
    }
    
    const email = document.getElementById('email').value.trim();
    if (!email) {
      showError('emailGroup', 'Email is required');
      isValid = false;
    } else if (!isValidEmail(email)) {
      showError('emailGroup', 'Please enter a valid email address');
      isValid = false;
    }
    
    const phone = document.getElementById('phone').value.trim();
    if (!phone) {
      showError('phoneGroup', 'Phone number is required');
      isValid = false;
    } else if (!isValidPhone(phone)) {
      showError('phoneGroup', 'Please enter a valid phone number');
      isValid = false;
    }
    
    const dob = document.getElementById('dateOfBirth').value;
    if (!dob) {
      showError('dobGroup', 'Date of birth is required');
      isValid = false;
    } else {
      const birthDate = new Date(dob);
      const today = new Date();
      const age = today.getFullYear() - birthDate.getFullYear();
      
      if (age < 13) {
        showError('dobGroup', 'You must be at least 13 years old');
        isValid = false;
      }
    }
    
    const gender = document.getElementById('gender').value;
    if (!gender) {
      showError('genderGroup', 'Please select your gender');
      isValid = false;
    }
    
    return isValid;
  }

  // Handle security form submission
  function handleSecuritySubmit(e) {
    e.preventDefault();
    
    if (validateSecurityForm()) {
      const submitBtn = document.getElementById('securitySaveBtn');
      const spinner = document.getElementById('securitySpinner');
      
      submitBtn.disabled = true;
      spinner.style.display = 'inline-block';
      
      setTimeout(() => {
        submitBtn.disabled = false;
        spinner.style.display = 'none';
        
        showToast('Password updated successfully!', 'success');
        
        securityForm.reset();
        passwordStrength.className = 'password-strength';
        passwordStrengthText.textContent = '';
      }, 1500);
    }
  }

  // Validate security form
  function validateSecurityForm() {
    let isValid = true;
    
    resetErrors('securityForm');
    
    const currentPassword = document.getElementById('current_password').value;
    if (!currentPassword) {
      showError('currentPasswordGroup', 'Current password is required');
      isValid = false;
    }
    
    const newPassword = document.getElementById('new_password').value;
    if (!newPassword) {
      showError('newPasswordGroup', 'New password is required');
      isValid = false;
    } else if (newPassword.length < 8) {
      showError('newPasswordGroup', 'Password must be at least 8 characters');
      isValid = false;
    } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(newPassword)) {
      showError('newPasswordGroup', 'Password must contain uppercase, lowercase, and numbers');
      isValid = false;
    }
    
    const confirmPassword = document.getElementById('confirm_password').value;
    if (!confirmPassword) {
      showError('confirmPasswordGroup', 'Please confirm your password');
      isValid = false;
    } else if (newPassword !== confirmPassword) {
      showError('confirmPasswordGroup', 'Passwords do not match');
      isValid = false;
    }
    
    return isValid;
  }

  // Handle notification form submission
  function handleNotificationSubmit(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('notificationSaveBtn');
    const spinner = document.getElementById('notificationSpinner');
    
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    
    const notificationSettings = {};
    const checkboxes = notificationForm.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      notificationSettings[checkbox.name] = checkbox.checked;
    });
    
    setTimeout(() => {
      localStorage.setItem('notificationSettings', JSON.stringify(notificationSettings));
      
      submitBtn.disabled = false;
      spinner.style.display = 'none';
      
      showToast('Notification preferences saved!', 'success');
    }, 1000);
  }

  // Show delete account confirmation modal
  function showDeleteModal() {
    if (validateDeleteForm()) {
      deleteAccountModal.style.display = 'flex';
    }
  }

  // Hide delete account confirmation modal
  function hideDeleteModal() {
    deleteAccountModal.style.display = 'none';
  }

  // Handle account deletion
  function handleAccountDeletion() {
    confirmDeleteBtn.innerHTML = '<span class="spinner"></span> Deleting account...';
    confirmDeleteBtn.disabled = true;
    
    setTimeout(() => {
      localStorage.removeItem('profileSettings');
      localStorage.removeItem('notificationSettings');
      localStorage.removeItem('profilePhoto');
      
      showToast('Your account has been deleted successfully', 'success');
      
      setTimeout(() => {
        window.location.href = 'index.php';
      }, 2000);
    }, 2000);
  }

  // Validate delete account form
  function validateDeleteForm() {
    let isValid = true;
    
    resetErrors('deleteAccountForm');
    
    const selectedReason = document.querySelector('input[name="delete_reason"]:checked');
    if (!selectedReason) {
      showError('feedbackGroup', 'Please select a reason for deleting your account');
      isValid = false;
    }
    
    if (selectedReason && selectedReason.value === 'other') {
      const feedback = document.getElementById('feedback').value.trim();
      if (!feedback) {
        showError('feedbackGroup', 'Please provide feedback');
        isValid = false;
      }
    }
    
    const confirmText = document.getElementById('confirmDelete').value;
    if (confirmText !== 'DELETE') {
      showError('confirmDeleteGroup', 'Please type DELETE to confirm');
      isValid = false;
    }
    
    return isValid;
  }

  // Handle profile photo upload
  function handleProfilePhotoUpload(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    if (!file.type.match('image.*')) {
      showError('photoPreview', 'Please select an image file');
      return;
    }
    
    if (file.size > 5 * 1024 * 1024) {
      showError('photoPreview', 'File size must be less than 5MB');
      return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
      photoPreview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
      removePhotoBtn.style.display = 'block';
      
      localStorage.setItem('profilePhoto', e.target.result);
      
      showToast('Profile photo updated successfully!', 'success');
    };
    reader.readAsDataURL(file);
  }

  // Remove profile photo
  function removeProfilePhoto() {
    photoPreview.innerHTML = `
      <svg class="default-avatar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
        <circle cx="12" cy="7" r="4"></circle>
      </svg>
    `;
    removePhotoBtn.style.display = 'none';
    
    localStorage.removeItem('profilePhoto');
    
    showToast('Profile photo removed', 'success');
  }

  // Check password strength
  function checkPasswordStrength() {
    const password = newPasswordInput.value;
    const strengthBar = passwordStrength;
    const strengthText = passwordStrengthText;
    
    if (!password) {
      strengthBar.className = 'password-strength';
      strengthText.textContent = '';
      return;
    }
    
    let strength = 0;
    
    if (password.length >= 8) strength += 1;
    if (/[a-z]/.test(password)) strength += 1;
    if (/[A-Z]/.test(password)) strength += 1;
    if (/\d/.test(password)) strength += 1;
    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
    
    if (strength <= 2) {
      strengthBar.className = 'password-strength strength-weak';
      strengthText.textContent = 'Weak password';
      strengthText.style.color = '#ff4d4f';
    } else if (strength <= 4) {
      strengthBar.className = 'password-strength strength-medium';
      strengthText.textContent = 'Medium strength';
      strengthText.style.color = '#faad14';
    } else {
      strengthBar.className = 'password-strength strength-strong';
      strengthText.textContent = 'Strong password';
      strengthText.style.color = '#52c41a';
    }
  }

  // Toggle feedback field based on selected reason
  function toggleFeedbackField() {
    const selectedReason = document.querySelector('input[name="delete_reason"]:checked');
    if (selectedReason && selectedReason.value === 'other') {
      feedbackGroup.style.display = 'block';
    } else {
      feedbackGroup.style.display = 'none';
    }
  }

  // Show error message
  function showError(elementId, message) {
    const element = document.getElementById(elementId);
    const errorElement = element.querySelector('.error-message') || document.getElementById(elementId + 'Error');
    
    element.classList.add('error');
    if (errorElement) {
      errorElement.textContent = message;
      errorElement.style.display = 'block';
    }
  }

  // Reset all errors in a form
  function resetErrors(formId) {
    const form = document.getElementById(formId);
    const errorElements = form.querySelectorAll('.error-message');
    const errorGroups = form.querySelectorAll('.form-group.error');
    
    errorElements.forEach(el => {
      el.textContent = '';
      el.style.display = 'none';
    });
    
    errorGroups.forEach(group => {
      group.classList.remove('error');
    });
  }

  // Show toast notification
  function showToast(message, type = 'success') {
    toast.textContent = message;
    toast.className = 'toast';
    
    if (type === 'error') {
      toast.classList.add('error');
      toast.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    } else {
      toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    }
    
    toast.classList.add('show');
    
    setTimeout(() => {
      toast.classList.remove('show');
    }, 3000);
  }

  // Utility functions
  function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  function isValidPhone(phone) {
    const re = /^[\+]?[1-9][\d]{0,15}$/;
    return re.test(phone.replace(/[\s\-\(\)]/g, ''));
  }
  </script>
</body>
</html>