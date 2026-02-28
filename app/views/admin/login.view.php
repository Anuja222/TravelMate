<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Login</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/loginSignup.css?v=<?= time() ?>">
</head>
<body>
  <!-- Auth Section -->
  <section class="auth-section">
    <div class="auth-container">
      <!-- Left Side - Form -->
      <div class="auth-form-section">
        <div class="auth-form-container">
          <div class="auth-header">
            <h1>Welcome Back!</h1>
            <p>Sign in to your account to continue your journey</p>
          </div>

          <form class="auth-form" id="loginForm">
            <div class="form-group">
              <label for="email" >Email Address</label>
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
              <a href="#" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-auth">Sign In</button>

            <!-- <div class="divider">
              <span>Or continue with</span>
            </div>

            <div class="social-auth">
              <button type="button" class="btn-social google" onclick="socialLogin('google')">
                <svg viewBox="0 0 24 24" width="20" height="20">
                  <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                  <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                  <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                  <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
              </button>
              <button type="button" class="btn-social facebook" onclick="socialLogin('facebook')">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="#1877F2">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Continue with Facebook
              </button>
            </div> -->

            <div class="auth-footer">
              <p>Don't have an account? <a href="../Traveller/signup.view.php">Sign up here</a></p>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Side - Image -->
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

  <!-- Footer -->
  <!-- <footer>
    <div class="footer-content">
      <div class="footer-section company">
        <h4>TravelMate</h4>
        <p>Your trusted partner for exploring Sri Lanka. Create memories that last a lifetime.</p>
      </div>
      <div class="footer-section links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Help Center</a></li>
        </ul>
      </div>
      <div class="footer-section support">
        <h4>Support</h4>
        <ul>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Live Chat</a></li>
        </ul>
      </div>
      <div class="footer-section connect">
        <h4>Contact Info</h4>
        <p>+94 11 434 4340<br>info@travelmate.lk</p>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; 2024 TravelMate Sri Lanka. All rights reserved.</span>
    </div>
  </footer> -->

  <!-- <script src="../public/assets/js/auth.js"></script> -->
</body>
</html>
