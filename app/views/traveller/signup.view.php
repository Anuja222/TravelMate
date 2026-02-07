<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Sign Up</title>
  <link rel="stylesheet" href="assets/css/Traveller/loginSignup.css">
</head>
<body>
  <!-- Auth Section -->
  <section class="auth-section">
    <div class="auth-container">
      <!-- Left Side - Form -->
      <div class="auth-form-section">
        <div class="auth-form-container">
          <div class="auth-header">
            <h1>Join TravelMate!</h1>
            <p>Create your account and start exploring Sri Lanka</p>
          </div>

          <form class="auth-form" id="signupForm">
            <!-- Profile Photo Upload -->
            <div class="form-group">
              <label>Profile Photo</label>
              <div class="profile-photo-upload">
                <div class="photo-preview" id="photoPreview">
                  <svg class="default-avatar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                </div>
                <div class="photo-upload-controls">
                  <label for="profilePhoto" class="btn-upload">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                      <polyline points="7,10 12,15 17,10"></polyline>
                      <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Upload Photo
                  </label>
                  <button type="button" class="btn-remove" id="removePhoto" style="display: none;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3,6 5,6 21,6"></polyline>
                      <path d="m19,6v14a2,2 0 0,1-2,2H7a2,2 0 0,1-2-2V6m3,0V4a2,2 0 0,1,2-2h4a2,2 0 0,1,2,2v2"></path>
                    </svg>
                    Remove
                  </button>
                </div>
                <p class="upload-hint">JPG, PNG or GIF (max. 5MB)</p>
                <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*" style="display: none;">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
              </div>
              <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>

            <!-- Date of Birth and Gender Row -->
            <div class="form-row">
              <div class="form-group">
                <label for="dateOfBirth">Date of Birth</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" required>
              </div>
              <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                  <!-- <option value="">Select Gender</option> -->
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                  <option value="prefer-not-to-say">Prefer not to say</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <div class="password-input">
                <input type="password" id="password" name="password" placeholder="Create a password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                  <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                </button>
              </div>
              <div class="password-strength" id="passwordStrength">
                <div class="strength-bar">
                  <div class="strength-fill"></div>
                </div>
                <span class="strength-text">Password strength</span>
              </div>
            </div>

            <div class="form-group">
              <label for="confirmPassword">Confirm Password</label>
              <div class="password-input">
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                  <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                </button>
              </div>
            </div>

            <div class="form-options">
              <label class="checkbox-container">
                <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                <span class="checkmark"></span>
                I agree to the <a href="termsandcondition" class="terms-link">Terms of Service</a> and <a href="privacy" class="terms-link">Privacy Policy</a>
              </label>
            </div>

            <div class="form-options">
              <label class="checkbox-container">
                <input type="checkbox" id="newsletter" name="newsletter">
                <span class="checkmark"></span>
                Subscribe to our newsletter for travel tips and offers
              </label>
            </div>

            <button type="submit" class="btn-auth">Create Account</button>

            <div class="auth-footer">
              <p>Already have an account? <a href="login">Sign in here</a></p>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Side - Image -->
      <div class="auth-image-section">
        <div class="auth-image signup-image">
          <div class="image-overlay">
            <div class="overlay-content">
              <h2>Start Your Journey</h2>
              <p>Discover hidden gems, create unforgettable memories, and experience Sri Lanka like never before</p>
              <div class="features">
                <div class="feature-item">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <span>1000+ Destinations</span>
                </div>
                <div class="feature-item">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  </svg>
                  <span>Expert Local Guides</span>
                </div>
                <div class="feature-item">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 11H1v3h8v3l7-4.5L9 8v3z"></path>
                    <path d="M22 12h-3"></path>
                  </svg>
                  <span>Easy Booking Process</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Success Modal -->
  <div id="successModal" class="modal">
    <div class="modal-content">
      <div class="modal-icon success">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <h2>Registration Successful!</h2>
      <p>Your account has been created successfully. Welcome to TravelMate!</p>
      <p class="modal-subtext">You will be redirected in <span id="countdown">3</span> seconds...</p>
      <button class="btn-modal" onclick="redirectToNext('traveller')">Continue Now</button>
    </div>
  </div>

  <script>
    console.log('Inline script loaded');
    console.log('Testing if elements exist:', {
      form: !!document.getElementById('signupForm'),
      modal: !!document.getElementById('successModal'),
      password: !!document.getElementById('password')
    });
    
    // Password toggle functionality
    function togglePassword(fieldId) {
      const field = document.getElementById(fieldId);
      const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
      field.setAttribute('type', type);
    }

    // Photo upload functionality
    document.getElementById('profilePhoto').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        if (file.size > 5 * 1024 * 1024) {
          alert('File size should be less than 5MB');
          return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('photoPreview');
          preview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview">`;
          document.getElementById('removePhoto').style.display = 'inline-flex';
        };
        reader.readAsDataURL(file);
      }
    });

    // Remove photo functionality
    document.getElementById('removePhoto').addEventListener('click', function() {
      document.getElementById('profilePhoto').value = '';
      const preview = document.getElementById('photoPreview');
      preview.innerHTML = `
        <svg class="default-avatar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
      `;
      this.style.display = 'none';
    });

    // Password strength checker
    document.getElementById('password').addEventListener('input', function(e) {
      const password = e.target.value;
      const strengthFill = document.querySelector('.strength-fill');
      const strengthText = document.querySelector('.strength-text');
      
      let strength = 0;
      let strengthLabel = 'Very Weak';
      
      if (password.length >= 8) strength += 20;
      if (/[a-z]/.test(password)) strength += 20;
      if (/[A-Z]/.test(password)) strength += 20;
      if (/[0-9]/.test(password)) strength += 20;
      if (/[^A-Za-z0-9]/.test(password)) strength += 20;
      
      strengthFill.style.width = strength + '%';
      
      if (strength >= 80) {
        strengthLabel = 'Very Strong';
        strengthFill.style.background = '#10b981';
      } else if (strength >= 60) {
        strengthLabel = 'Strong';
        strengthFill.style.background = '#22c55e';
      } else if (strength >= 40) {
        strengthLabel = 'Fair';
        strengthFill.style.background = '#eab308';
      } else if (strength >= 20) {
        strengthLabel = 'Weak';
        strengthFill.style.background = '#f59e0b';
      } else {
        strengthLabel = 'Very Weak';
        strengthFill.style.background = '#ef4444';
      }
      
      strengthText.textContent = strengthLabel;
    });

    // Set max date for date of birth (18 years ago)
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    document.getElementById('dateOfBirth').max = maxDate.toISOString().split('T')[0];

    // Form submission handler
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
      console.log('Form found, attaching submit handler');
      
      signupForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted!');
        
        // Get form values
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const agreeTerms = document.getElementById('agreeTerms').checked;
        
        // Validate passwords match
        if (password !== confirmPassword) {
          alert('Passwords do not match!');
          return;
        }
        
        // Validate terms agreement
        if (!agreeTerms) {
          alert('Please agree to the Terms of Service and Privacy Policy');
          return;
        }
        
        console.log('Validation passed');
        
        // Get user role from localStorage or default to traveller
        let userRole = localStorage.getItem('selectedUserRole') || 'traveller';
        console.log('User role:', userRole);
        
        // Create FormData
        const formData = new FormData(signupForm);
        formData.append('role', userRole);
        
        // Log form data
        console.log('Sending registration request...');
        for (let pair of formData.entries()) {
          if (pair[0] !== 'password' && pair[0] !== 'confirmPassword') {
            console.log(pair[0] + ': ' + pair[1]);
          }
        }
        
        // Send request
        fetch('auth.php?action=register', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          console.log('Response status:', response.status);
          if (!response.ok) {
            throw new Error('HTTP error ' + response.status);
          }
          return response.text();
        })
        .then(text => {
          console.log('Raw response:', text);
          try {
            const data = JSON.parse(text);
            console.log('Parsed data:', data);
            
            if (data.success) {
              console.log('Registration successful!');
              
              // Store user ID
              if (data.user && data.user.userId) {
                localStorage.setItem('userId', data.user.userId);
                console.log('User ID stored:', data.user.userId);
              }
              
              // Show modal and redirect
              showSuccessModal(userRole);
              
              // Remove role from storage
              localStorage.removeItem('selectedUserRole');
            } else {
              console.error('Registration failed:', data.errors);
              let errorMsg = 'Registration failed:\n';
              if (data.errors) {
                for (const key in data.errors) {
                  errorMsg += data.errors[key] + '\n';
                }
              }
              alert(errorMsg);
            }
          } catch (err) {
            console.error('JSON parse error:', err);
            console.error('Response was:', text);
            alert('Invalid response from server. Check console for details.');
          }
        })
        .catch(error => {
          console.error('Fetch error:', error);
          alert('Error: ' + error.message + '\nCheck console for details.');
        });
      });
    } else {
      console.error('Form not found!');
    }
    
    // Modal functions
    window.showSuccessModal = function(userRole) {
      console.log('showSuccessModal called');
      const modal = document.getElementById('successModal');
      
      if (!modal) {
        console.error('Modal not found, redirecting directly...');
        redirectToNext(userRole);
        return;
      }
      
      console.log('Showing modal');
      modal.classList.add('show');
      modal.style.display = 'flex';
      
      let countdown = 3;
      const countdownElement = document.getElementById('countdown');
      
      if (countdownElement) {
        const timer = setInterval(() => {
          countdown--;
          countdownElement.textContent = countdown;
          console.log('Countdown:', countdown);
          
          if (countdown <= 0) {
            clearInterval(timer);
            console.log('Countdown finished, redirecting...');
            redirectToNext(userRole);
          }
        }, 1000);
      } else {
        console.error('Countdown element not found');
        setTimeout(() => redirectToNext(userRole), 3000);
      }
    };
    
    window.redirectToNext = function(userRole) {
      console.log('redirectToNext called with role:', userRole);
      
      if (!userRole) {
        userRole = localStorage.getItem('selectedUserRole') || 'traveller';
      }
      
      let redirectUrl = 'preference'; // Default to preference for traveller
      
      if (userRole === 'transport') {
        redirectUrl = 'login';
      } else if (userRole === 'admin') {
        redirectUrl = 'login';
      } else if (userRole === 'accommodation') {
        redirectUrl = 'login';
      }
      
      console.log('Redirecting to:', redirectUrl);
      window.location.href = redirectUrl;
    };
    
  </script>
  <script src="assets/js/signupmodel.js"></script>
</body>
</html>