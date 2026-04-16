<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password - TravelMate Admin</title>
  <link rel="stylesheet" href="forgotpassword.css">
  <link rel="stylesheet" href="common.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="forgot-password-container">
    <div class="forgot-password-card">
      <div class="card-header">
        <h2>Reset Your Password</h2>
        <p>Enter your email address and we'll send you a link to reset your password.</p>
      </div>

      <form class="forgot-password-form" method="POST" action="">
        <div class="input-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Enter your email address" required>
        </div>

        <button type="submit" class="reset-btn">Send Reset Link</button>

        <div class="form-links">
          <a href="login.php" class="back-to-login">← Back to Login</a>
        </div>
      </form>

      <div class="success-message" id="successMessage" style="display: none;">
        <div class="message-icon">✓</div>
        <h3>Reset Link Sent!</h3>
        <p>We've sent a password reset link to your email address. Please check your inbox and follow the instructions.</p>
        <small>Didn't receive the email? <a href="#" onclick="resendEmail()">Resend link</a></small>
      </div>

      <div class="error-message" id="errorMessage" style="display: none;">
        <div class="message-icon">⚠</div>
        <p>Email address not found. Please check and try again.</p>
      </div>
    </div>
  </div>

  <script>
    // simple form handling simulation
    document.querySelector('.forgot-password-form').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = document.getElementById('email').value;
      
      if (email) {
        // hide form and show success message
        document.querySelector('.forgot-password-form').style.display = 'none';
        document.getElementById('successMessage').style.display = 'block';
      } else {
        // show error message
        document.getElementById('errorMessage').style.display = 'block';
      }
    });

    function resendEmail() {
      alert('Reset link has been resent to your email address.');
    }
  </script>

</body>
</html>