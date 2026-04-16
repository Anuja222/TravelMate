<?php
// start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userData = isset($user) ? $user : [];
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$firstName = $userData['first_name'] ?? ($isLoggedIn ? $_SESSION['user']['first_name'] : '');
$lastName = $userData['last_name'] ?? ($isLoggedIn ? $_SESSION['user']['last_name'] : '');
$email = $userData['email'] ?? ($isLoggedIn ? $_SESSION['user']['email'] : '');
$phone = $userData['phone'] ?? ($isLoggedIn ? ($_SESSION['user']['phone'] ?? '') : '');
$dateOfBirth = $userData['date_of_birth'] ?? ($isLoggedIn ? ($_SESSION['user']['dateOfBirth'] ?? '') : '');
$gender = $userData['gender'] ?? ($isLoggedIn ? ($_SESSION['user']['gender'] ?? '') : '');
$bio = $userData['bio'] ?? ($isLoggedIn ? ($_SESSION['user']['bio'] ?? '') : '');
$country = $userData['country'] ?? ($isLoggedIn ? ($_SESSION['user']['country'] ?? '') : '');
$city = $userData['city'] ?? ($isLoggedIn ? ($_SESSION['user']['city'] ?? '') : '');

$rootUrl = defined('ROOT') ? ROOT : '/TravelMate/public';
$currentProfileImage = (!empty($userData['profile_image'])) ? $rootUrl . '/' . $userData['profile_image'] : (!empty($_SESSION['user']['profile_image']) ? $rootUrl . '/' . $_SESSION['user']['profile_image'] : 'assets/images/profile.jpg');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Settings</title>
  <link rel="stylesheet" href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/css/Accommodation/setting.css">
  <link rel="stylesheet" href="<?= defined('ROOT') ? ROOT : '/TravelMate/public' ?>/assets/css/Accommodation/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <!-- toast notification -->
  <div class="toast" id="toast"></div>
  
  <!-- mAIN CONTENT -->
  <main>
    <!-- sIDEBAR -->
    <?php 
    $active_page = 'settings';
    include __DIR__ . '/sidebar.view.php'; 
    ?>

    <div class="content">
        <div class="page-title">
          <h1><i class="fas fa-cog"></i> Account Settings</h1>
          <p>Manage your account preferences and settings</p>
        </div>

    <div class="settings-grid">

      <section class="settings-section">
        <div class="section-header">
          <i class="fas fa-user"></i>
          <h2>Profile Settings</h2>
        </div>
            <form class="auth-form" id="profileForm">
            <!-- profile Photo Upload -->
            <div class="form-group">
              <label>Profile Photo</label>
              <div class="profile-photo-upload">
                <div class="photo-preview" id="photoPreview">
                  <img src="<?php echo htmlspecialchars($currentProfileImage); ?>" class="current-photo" id="profileImageDisplay" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;" onerror="this.src='assets/images/profile.jpg'">
                </div>
                <div class="photo-upload-controls">
                  <label for="profilePhoto" class="btn-upload">
                    <i class="fas fa-upload"></i> Change photo
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

            <div class="form-row">
              <div class="form-group" id="firstNameGroup">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" placeholder="Enter your first name" required>
                <div class="error-message" id="firstNameError"></div>
              </div>
              <div class="form-group" id="lastNameGroup">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" placeholder="Enter your last name" required>
                <div class="error-message" id="lastNameError"></div>
              </div>
            </div>

            <div class="form-group" id="emailGroup">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your email" required>
              <div class="error-message" id="emailError"></div>
            </div>

            <div class="form-group" id="phoneGroup">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="e.g. 0705697391" required>
              <div class="error-message" id="phoneError"></div>
            </div>

            <!-- date of Birth and Gender Row -->
            <div class="form-row">
              <div class="form-group" id="dobGroup">
                <label for="dateOfBirth">Date of Birth</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($dateOfBirth); ?>" required>
                <div class="error-message" id="dobError"></div>
              </div>
              <div class="form-group" id="genderGroup">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                  <option value="">Select Gender</option>
                  <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                  <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                  <option value="other" <?php echo ($gender === 'other') ? 'selected' : ''; ?>>Other</option>
                  <option value="prefer-not-to-say" <?php echo ($gender === 'prefer-not-to-say') ? 'selected' : ''; ?>>Prefer not to say</option>
                </select>
                <div class="error-message" id="genderError"></div>
              </div>
            </div>

            <!-- bio, Country, and City -->
            <div class="form-group" id="bioGroup">
              <label for="bio">Provider Bio / Description</label>
              <textarea id="bio" name="bio" placeholder="Tell us about yourself or your property management business" rows="4"><?php echo htmlspecialchars($bio); ?></textarea>
              <div class="error-message" id="bioError"></div>
            </div>

            <div class="form-row">
              <div class="form-group" id="countryGroup">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>" placeholder="Country" required>
                <div class="error-message" id="countryError"></div>
              </div>
              <div class="form-group" id="cityGroup">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" placeholder="City" required>
                <div class="error-message" id="cityError"></div>
              </div>
            </div>

            
            <button type="submit" class="save-btn" id="profileSaveBtn">
              <span class="spinner" id="profileSpinner" style="display: none;"></span>
              <i class="fas fa-save"></i> Save Changes
            </button>

          </form>
      </section>

      <!-- password and Security-->
      <section class="settings-section">
        <div class="section-header">
          <i class="fas fa-lock"></i>
          <h2>Password & Security</h2>
        </div>
        <form class="settings-form" id="securityForm">
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
            
            <div class="form-buttons">
                <button type="submit" class="save-btn" id="securitySaveBtn">
                  <span class="spinner" id="securitySpinner" style="display: none;"></span>
                  <i class="fas fa-key"></i> Update Password
                </button>
                <button type="reset" class="cancel-btn"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </form>
      </section>

    </div>
  </div>
</main>

<?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

<script>
// dOM Elements
const profileForm = document.getElementById('profileForm');
const securityForm = document.getElementById('securityForm');
const toast = document.getElementById('toast');
const profilePhoto = document.getElementById('profilePhoto');
const photoPreview = document.getElementById('photoPreview');
const removePhotoBtn = document.getElementById('removePhoto');
const newPasswordInput = document.getElementById('new_password');
const passwordStrength = document.getElementById('passwordStrength');
const passwordStrengthText = document.getElementById('passwordStrengthText');

// event Listeners
document.addEventListener('DOMContentLoaded', initApp);
profileForm.addEventListener('submit', handleProfileSubmit);
securityForm.addEventListener('submit', handleSecuritySubmit);
profilePhoto.addEventListener('change', handleProfilePhotoUpload);
removePhotoBtn.addEventListener('click', removeProfilePhoto);
newPasswordInput.addEventListener('input', checkPasswordStrength);

// initialize the application
function initApp() {
  // we no longer rely on localStorage dummy data, the page natively populates from PHP.
}

// handle profile form submission
function handleProfileSubmit(e) {
  e.preventDefault();
  
  if (validateProfileForm()) {
    const submitBtn = document.getElementById('profileSaveBtn');
    const spinner = document.getElementById('profileSpinner');
    
    // show loading state
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    
    const formData = new FormData(document.getElementById('profileForm'));

    fetch('/TravelMate/public/acc_setting/update', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      submitBtn.disabled = false;
      spinner.style.display = 'none';

      if(data.success) {
        showToast(data.message, 'success');
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast(data.message, 'error');
      }
    })
    .catch(error => {
      submitBtn.disabled = false;
      spinner.style.display = 'none';
      showToast('An error occurred. Please try again.', 'error');
      console.error('Error:', error);
    });
  }
}

// validate profile form
function validateProfileForm() {
  let isValid = true;
  
  // reset error states
  resetErrors('profileForm');

  const firstName = document.getElementById('firstName').value.trim();
  if (!firstName) {
    showError('firstNameGroup', 'First name is required');
    isValid = false;
  }
  
  // validate last name
  const lastName = document.getElementById('lastName').value.trim();
  if (!lastName) {
    showError('lastNameGroup', 'Last name is required');
    isValid = false;
  }
  
  // validate email
  const email = document.getElementById('email').value.trim();
  if (!email) {
    showError('emailGroup', 'Email is required');
    isValid = false;
  } else if (!isValidEmail(email)) {
    showError('emailGroup', 'Please enter a valid email address');
    isValid = false;
  }
  
  // validate phone
  const phone = document.getElementById('phone').value.trim();
  if (!phone) {
    showError('phoneGroup', 'Phone number is required');
    isValid = false;
  } else if (!isValidPhone(phone)) {
    showError('phoneGroup', 'Please enter a valid 10-digit phone number starting with 0');
    isValid = false;
  }
  
  // validate date of birth
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
  
  // validate gender
  const gender = document.getElementById('gender').value;
  if (!gender) {
    showError('genderGroup', 'Please select your gender');
    isValid = false;
  }

  // validate country and city
  const country = document.getElementById('country').value.trim();
  if (!country) {
    showError('countryGroup', 'Country is required');
    isValid = false;
  }
  
  const city = document.getElementById('city').value.trim();
  if (!city) {
    showError('cityGroup', 'City is required');
    isValid = false;
  }
  
  return isValid;
}

// handle security form submission
function handleSecuritySubmit(e) {
  e.preventDefault();
  
  if (validateSecurityForm()) {
    const submitBtn = document.getElementById('securitySaveBtn');
    const spinner = document.getElementById('securitySpinner');
    
    // show loading state
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    
    const formData = new FormData(document.getElementById('securityForm'));

    fetch('/TravelMate/public/acc_setting/updatePassword', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      submitBtn.disabled = false;
      spinner.style.display = 'none';

      if(data.success) {
        showToast(data.message, 'success');
        securityForm.reset();
        passwordStrength.className = 'password-strength';
        passwordStrengthText.textContent = '';
      } else {
        showToast(data.message, 'error');
      }
    })
    .catch(error => {
      submitBtn.disabled = false;
      spinner.style.display = 'none';
      showToast('An error occurred. Please try again.', 'error');
      console.error('Error:', error);
    });
  }
}

// validate security form
function validateSecurityForm() {
  let isValid = true;
  
  // reset error states
  resetErrors('securityForm');
  
  // validate current password
  const currentPassword = document.getElementById('current_password').value;
  if (!currentPassword) {
    showError('currentPasswordGroup', 'Current password is required');
    isValid = false;
  }
  
  // validate new password
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
  
  // validate confirm password
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

// handle profile photo upload
function handleProfilePhotoUpload(e) {
  const file = e.target.files[0];
  if (!file) return;
  
  // validate file type
  if (!file.type.match('image.*')) {
    showError('photoPreview', 'Please select an image file');
    return;
  }
  
  // validate file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    showError('photoPreview', 'File size must be less than 5MB');
    return;
  }
  
  const reader = new FileReader();
  reader.onload = function(e) {
    photoPreview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
    removePhotoBtn.style.display = 'block';
    
    // save to localStorage
    localStorage.setItem('profilePhoto', e.target.result);
  };
  reader.readAsDataURL(file);
}

// remove profile photo
function removeProfilePhoto() {
  photoPreview.innerHTML = `
    <svg class="default-avatar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
      <circle cx="12" cy="7" r="4"></circle>
    </svg>
  `;
  removePhotoBtn.style.display = 'none';
  
  // remove from localStorage
  localStorage.removeItem('profilePhoto');
}

// check password strength
function checkPasswordStrength() {
  const password = newPasswordInput.value;
  const strengthBar = passwordStrength;
  const strengthText = passwordStrengthText;
  
  if (!password) {
    strengthBar.className = 'password-strength';
    strengthText.textContent = '';
    return;
  }
  
  // calculate strength
  let strength = 0;
  
  // length check
  if (password.length >= 8) strength += 1;
  
  // contains lowercase
  if (/[a-z]/.test(password)) strength += 1;
  
  // contains uppercase
  if (/[A-Z]/.test(password)) strength += 1;
  
  // contains numbers
  if (/\d/.test(password)) strength += 1;
  
  // contains special characters
  if (/[^A-Za-z0-9]/.test(password)) strength += 1;
  
  // update UI
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

// show error message
function showError(elementId, message) {
  const element = document.getElementById(elementId);
  const errorElement = element.querySelector('.error-message') || document.getElementById(elementId + 'Error');
  
  element.classList.add('error');
  if (errorElement) {
    errorElement.textContent = message;
    errorElement.style.display = 'block';
  }
}

// reset all errors in a form
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

// show toast notification
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
  
  // hide after 3 seconds
  setTimeout(() => {
    toast.classList.remove('show');
  }, 3000);
}

// utility functions
function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function isValidPhone(phone) {
  const re = /^0\d{9}$/;
  return re.test(phone.replace(/[\s\-\(\)]/g, ''));
}
</script>

</body>
</html>
