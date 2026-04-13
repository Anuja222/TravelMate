<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Login</title>
  <link rel="stylesheet" href="assets/css/Traveller/loginSignup.css">
</head>
<body>
  <section class="auth-section">
    <div class="auth-container">
      <div class="auth-form-section">
        <div class="auth-form-container">
          <div class="auth-header">
            <h1>Welcome Back!</h1>
            <p>Sign in to your account to continue your journey</p>
          </div>

          <form class="auth-form" id="loginForm" method="POST">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <div style="position: relative; display: flex; align-items: center;">
                <input type="password" id="password" name="password" placeholder="Enter your password" required style="width: 100%; padding-right: 40px; box-sizing: border-box;">
                <button type="button" id="togglePassword" style="position: absolute; right: 15px; background: none; border: none; cursor: pointer; padding: 0; display: flex; color: #6e6e6e;" aria-label="Toggle password visibility">
                  <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
              </div>
            </div>

            <div class="form-options">
              <label class="checkbox-container">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <span class="checkmark"></span>
                Remember me
              </label>
              <a href="#" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-auth">Sign In</button>

            <div class="auth-footer">
              <p>Don't have an account? <a href="signupmodel">Sign up here</a></p>
            </div>
          </form>
        </div>
      </div>

      <div class="auth-image-section">
        <div class="auth-image">
          <div class="image-overlay">
            <div class="overlay-content">
              <h2>Discover Sri Lanka</h2>
              <p>Join thousands of travelers exploring the pearl of the Indian Ocean</p>
              <div class="stats">
                <div class="stat">
                  <span class="number">1000+</span>
                  <span class="label">Destinations</span>
                </div>
                <div class="stat">
                  <span class="number">50K+</span>
                  <span class="label">Happy Travelers</span>
                </div>
                <div class="stat">
                  <span class="number">24/7</span>
                  <span class="label">Support</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Success Modal -->
  <div id="successModal" class="success-modal">
    <div class="success-modal-content">
      <div class="success-icon">
        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="30" cy="30" r="28" stroke="#10b981" stroke-width="3" fill="#ecfdf5"/>
          <path d="M20 30L26 36L40 22" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <h2>Login Successful!</h2>
      <p>Welcome back!</p>
    </div>
  </div>

  <!-- Error Modal -->
  <div id="errorModal" class="error-modal">
    <div class="error-modal-content">
      <div class="error-icon">
        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="30" cy="30" r="28" stroke="#ef4444" stroke-width="3" fill="#fef2f2"/>
          <path d="M30 20V32" stroke="#ef4444" stroke-width="3" stroke-linecap="round"/>
          <circle cx="30" cy="40" r="2" fill="#ef4444"/>
        </svg>
      </div>
      <h2>Login Failed</h2>
      <p id="errorMessage">Invalid email or password</p>
      <button class="btn-close-modal" onclick="document.getElementById('errorModal').classList.remove('show')">Try Again</button>
    </div>
  </div>

  <script src="../public/assets/js/login.js"></script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        // Eye off SVG
        eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
      } else {
        passwordInput.type = 'password';
        // Eye on SVG
        eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
      }
    });
  </script>
</body>
</html>