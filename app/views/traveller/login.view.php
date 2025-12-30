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
              <input type="password" id="password" name="password" placeholder="Enter your password" required>
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

  <script src="../public/assets/js/login.js"></script>
</body>
</html>