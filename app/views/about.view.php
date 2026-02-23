<?php
// Detect admin session
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
require_once __DIR__ . '/../core/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - About Us</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/about.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/main.css">
  <?php if ($_isAdmin): ?>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <?php endif; ?>
</head>
<body>
  <!-- Header/Navbar -->
  <?php if ($_isAdmin): ?>
    <?php include __DIR__ . '/admin/admin_header.view.php'; ?>
  <?php else: ?>
    <?php include __DIR__ . '/traveller/header.view.php'; ?>
  <?php endif; ?>

  <?php if ($_isAdmin): ?>
  <div class="page-container">
    <?php include __DIR__ . '/admin/sidebar.view.php'; ?>
    <div class="content">
  <?php endif; ?>

  <!-- Hero Section -->
  <section class="hero">
  <img src="assets/images/about.jpg" alt="About Hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <h1>About Us</h1>
    </div>
  </section>

  <!-- Welcome Section -->
  <section class="welcome-section">
    <div class="welcome-container">
      <div class="welcome-content">
        <h2>Welcome to <span class="highlight">TravelMate</span>, your trusted partner for exploring the beauty of Sri Lanka.</h2>
        <p>
          We believe that travel is more than just reaching a destination; it's about creating memories, immersing yourself in new experiences, and enjoying every step of the journey. That's why we've built a complete travel planning platform to make your trip to Sri Lanka seamless, personalized, and unforgettable.
        </p>
        <p>
          From booking cozy beachfront villas to securing reliable transport, from discovering breathtaking destinations to finding exciting activities, we've got everything you need in one place.
        </p>
      </div>
    </div>
  </section>

  <!-- Why Choose Us Section -->
  <section class="why-choose-section">
    <div class="why-choose-container">
      <div class="image-section">
        <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80" alt="Palm trees and tropical beach" class="feature-image">
      </div>
      <div class="content-section">
        <h2>Why Choose Us?</h2>
        <ul class="features-list">
          <li>
            <div class="feature-point">
              <span class="bullet"></span>
              <span>Easy-to-use platform with no hidden fees</span>
            </div>
          </li>
          <li>
            <div class="feature-point">
              <span class="bullet"></span>
              <span>Handpicked, verified partners for quality and safety</span>
            </div>
          </li>
          <li>
            <div class="feature-point">
              <span class="bullet"></span>
              <span>Smart itinerary recommendations based on your preferences</span>
            </div>
          </li>
          <li>
            <div class="feature-point">
              <span class="bullet"></span>
              <span>Local insights to help you travel like a Sri Lankan</span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Journey Begins Section -->
  <section class="journey-section">
    <div class="journey-content">
      <h2>Your journey begins here!</h2>
    </div>
  </section>

  <!-- Footer -->
  <footer>
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
  </footer>

  <script src="../public/assets/js/about.js"></script>

  <?php if ($_isAdmin): ?>
    </div><!-- /.content -->
  </div><!-- /.page-container -->
  <?php endif; ?>
</body>
</html>