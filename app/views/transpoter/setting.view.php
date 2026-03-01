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
          <button class="tab-btn" data-tab="account-status">
            <i class="fas fa-toggle-on"></i>
            <span>Account Status</span>
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

          <!-- Account Status Tab -->
          <div class="tab-pane" id="account-status-tab">
              <section class="settings-section">
                  <div class="section-header">
                      <i class="fas fa-toggle-on" style="color: var(--primary);"></i>
                      <h2>Account Status</h2>
                  </div>
                  
                  <div class="status-container">
                      <!-- Current Status Card -->
                      <div class="status-gradient-card">
                          <div class="status-flex">
                              <div class="status-user-info">
                                  <div class="status-icon-circle">
                                      <i class="fas fa-user-circle"></i>
                                  </div>
                                  <div>
                                      <h3 class="status-title">Current Account Status</h3>
                                      <div class="status-badges-container">
                                          <span id="currentStatusBadge" class="status-badge-active">
                                              <i class="fas fa-check-circle"></i> Active
                                          </span>
                                          <span id="vehicleVisibilityBadge" class="visibility-badge-active">
                                              <i class="fas fa-eye"></i> Vehicles visible to travellers
                                          </span>
                                      </div>
                                  </div>
                              </div>
                              <div class="status-toggle-wrapper">
                                  <div class="status-toggle-label">
                                      <span>Account Status</span>
                                      <span id="statusText" class="status-toggle-value">Active</span>
                                  </div>
                                  <label class="toggle-switch status-toggle-switch-lg">
                                      <input type="checkbox" id="accountStatusToggle" checked>
                                      <span class="toggle-slider"></span>
                                  </label>
                              </div>
                          </div>
                      </div>

                      <!-- Guidelines Section - Dynamic Content -->
                      <div id="deactivationGuidelines" class="guidelines-container">
                          <!-- Content will be loaded dynamically -->
                      </div>

                      <!-- Info Cards Grid -->
                      <div class="info-grid">
                          <!-- Active Mode Card -->
                          <div class="info-card-active">
                              <div class="info-header">
                                  <div class="info-icon-active">
                                      <i class="fas fa-check-circle"></i>
                                  </div>
                                  <h4 class="info-title">Active Mode</h4>
                              </div>
                              <ul class="info-list">
                                  <li class="info-list-item">
                                      <i class="fas fa-car active-icon"></i>
                                      <span>All vehicles visible in search results</span>
                                  </li>
                                  <li class="info-list-item">
                                      <i class="fas fa-calendar-check active-icon"></i>
                                      <span>Receive new booking requests</span>
                                  </li>
                                  <li class="info-list-item">
                                      <i class="fas fa-bell active-icon"></i>
                                      <span>Full notification access</span>
                                  </li>
                              </ul>
                          </div>

                          <!-- Deactivated Mode Card -->
                          <div class="info-card-inactive">
                              <div class="info-header">
                                  <div class="info-icon-inactive">
                                      <i class="fas fa-pause-circle"></i>
                                  </div>
                                  <h4 class="info-title">Deactivated Mode</h4>
                              </div>
                              <ul class="info-list">
                                  <li class="info-list-item">
                                      <i class="fas fa-eye-slash inactive-icon"></i>
                                      <span>Vehicles hidden from travellers</span>
                                  </li>
                                  <li class="info-list-item">
                                      <i class="fas fa-clock inactive-icon"></i>
                                      <span>No new booking requests</span>
                                  </li>
                                  <li class="info-list-item">
                                      <i class="fas fa-save inactive-icon"></i>
                                      <span>All data & vehicles preserved</span>
                                  </li>
                              </ul>
                          </div>
                      </div>

                      <!-- Deactivation Confirmation Box -->
                      <div id="deactivationConfirmBox" class="deactivation-box" style="display: none;">
                          <div class="deactivation-heading">
                              <i class="fas fa-exclamation-triangle"></i>
                              <h4>Confirm Account Deactivation</h4>
                          </div>
                          <p class="deactivation-message">Please confirm that you want to deactivate your account.</p>
                          
                          <div class="form-group">
                              <label for="deactivationReason">Reason for deactivation (optional):</label>
                              <select id="deactivationReason" class="deactivation-select">
                                  <option value="">Select a reason</option>
                                  <option value="temporary_break">Taking a temporary break</option>
                                  <option value="maintenance">Vehicle maintenance/updates</option>
                                  <option value="holiday">Going on holiday</option>
                                  <option value="business_pause">Business paused</option>
                                  <option value="other">Other reason</option>
                              </select>
                          </div>
                          
                          <div id="otherReasonGroup" style="display: none;">
                              <label for="otherReason">Please specify:</label>
                              <textarea id="otherReason" rows="2" class="deactivation-textarea" placeholder="Tell us more..."></textarea>
                          </div>
                          
                          <div class="deactivation-checkbox">
                              <label>
                                  <input type="checkbox" id="confirmDeactivationCheckbox">
                                  <span>I understand that deactivating my account will hide my vehicles from travellers, but I am still responsible for fulfilling all existing bookings.</span>
                              </label>
                          </div>
                          
                          <div class="deactivation-actions">
                              <button class="delete-btn" id="confirmDeactivationBtn" disabled>
                                  <i class="fas fa-pause-circle"></i> Confirm Deactivation
                              </button>
                              <button class="cancel-btn" id="cancelDeactivationBtn">
                                  <i class="fas fa-times"></i> Cancel
                              </button>
                          </div>
                      </div>

                      <!-- Reactivation Confirmation Box -->
                      <div id="reactivationConfirmBox" class="reactivation-box" style="display: none;">
                          <div class="reactivation-heading">
                              <i class="fas fa-check-circle"></i>
                              <h4>Reactivate Account</h4>
                          </div>
                          <p class="reactivation-message">Your account is currently deactivated. Reactivate to make your vehicles visible to travellers again.</p>
                          
                          <div class="reactivation-actions">
                              <button class="save-btn" id="confirmReactivationBtn">
                                  <i class="fas fa-play-circle"></i> Reactivate Account
                              </button>
                              <button class="cancel-btn" id="cancelReactivationBtn">
                                  <i class="fas fa-times"></i> Cancel
                              </button>
                          </div>
                      </div>
                  </div>
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

  <script src="assets/js/Transporter/settings.js"></script>
</body>
</html>