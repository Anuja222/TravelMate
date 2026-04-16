<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Admin Login</title>
  <link rel="stylesheet" href="assets/css/Admin/adminLogin.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
</head>
<body>
  <!-- auth Section -->
  <section class="auth-section">
    <div class="auth-container">
      <!-- left Side - Form -->
      <div class="auth-form-section">
        <div class="auth-form-container">
          <div class="auth-header">
            <h1>Welcome Back!</h1>
            <p>Sign in to your admin account to manage the platform</p>
          </div>

          <form class="auth-form" id="loginForm">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <div class="password-input">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                  <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                </button>
              </div>
            </div>

            <div class="form-options">
              <label class="checkbox-container">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <span class="checkmark"></span>
                Remember me
              </label>
              <a href="forgotPassword.php" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-auth">Sign In</button>

            <div class="auth-footer">
              <p>Not an admin? <a href="login.php">Back to User Login</a></p>
            </div>
          </form>
        </div>
      </div>

      <!-- right Side - Image -->
      <div class="auth-image-section">
        <div class="auth-image">
          <div class="image-overlay">
            <div class="overlay-content">
              <h2>Manage TravelMate</h2>
              <p>Full control over your platform and all its features</p>
              <div class="stats">
                <div class="stat">
                  <span class="number">2,500+</span>
                  <span class="label">Users</span>
                </div>
                <div class="stat">
                  <span class="number">500+</span>
                  <span class="label">Listings</span>
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

  <script>
    function togglePassword(fieldId) {
      const field = document.getElementById(fieldId);
      const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
      field.setAttribute('type', type);
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const rememberMe = document.getElementById('rememberMe').checked;
      
      if (!email || !password) {
        alert('Please fill in all fields');
        return;
      }
      
      console.log('Admin login attempt:', {
        email: email,
        password: '***',
        rememberMe: rememberMe
      });
      
      alert('Login successful! Redirecting to dashboard...');
      // window.location.href = 'dashboard.php';
    });
  </script>
</body>
</html>