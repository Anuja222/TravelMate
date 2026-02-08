<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$role = $isLoggedIn ? ($_SESSION['user']['role'] ?? $_SESSION['role'] ?? '') : '';

// Role-based redirect - this is an accommodation provider page
if (!$isLoggedIn || $role !== 'accommodation') {
    if ($role === 'admin') {
        header('Location: ad_dashboard');
        exit;
    } elseif ($role === 'transport') {
        header('Location: tr_dashboard');
        exit;
    } else {
        header('Location: homet');
        exit;
    }
}

$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TravelMate - Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/dashboard.css">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/property-cards.css">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <!-- Navbar -->
  <!-- <header>
    <nav class="navbar">
      <div class="logo-container">
        <img src="assets/images/logo.jpg" class="logo" alt="TravelMate Logo">
        <h2>TravelMate</h2>
      </div>
      <ul class="nav-links">
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact Us</a></li>
        <li><a href="#">Blog</a></li>
      </ul>
      <div class="nav-actions"> -->
        <!-- <i class="uil uil-bell" style="width:28px;height:28px;margin-right:12px;vertical-align:middle;cursor:pointer;"></i> -->
        <!-- <img src="assets/images/notification.png" class="notification-icon" alt="Notifications" style="width:28px;height:28px;margin-right:12px;vertical-align:middle;cursor:pointer;"> -->
        <!-- <img src="assets/images/profile.jpg" class="user-icon" alt="User Icon">
      </div>
    </nav>
  </header> -->

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <main>
    <aside class="sidebar">
      <ul>
        <li><a href="ac_dashboard">Dashboard</a></li>
        <li><a href="ac_dashboard">Bookings</a></li>
        <li><a href="acc_setting">Settings</a></li>
      </ul>
    </aside>
    <section class="dashboard-content">
      <!-- Cover -->
      <div class="cover">
  <img src="/TravelMate/public/assets/images/cover.jpg" alt="Travel Cover" class="cover-img">
        <span class="cover-text">TRAVEL <span class="cover-sub">more</span></span>
      </div>
      <!-- Profile -->
      <div class="profile-section">
  <img src="/TravelMate/public/assets/images/profile.jpg" alt="User" class="profile-pic">
        <div>
          <h2><?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?></h2>
          <span class="profile-email"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
        </div>
      </div>
      <!-- Favourites -->
     <section class="favourite">
        <div class="section-header">
          <h3>My Properties</h3>
          <button class="btn-list-property" onclick="window.location.href='/TravelMate/public/propertyListingStart';">
            <i class="fas fa-plus"></i> List Your Property
          </button>
        </div>

        <div class="property-cards-grid">
          <!-- Property cards will be loaded here dynamically -->
          <div class="loading-message" style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 10px;"></i>
            <p>Loading your properties...</p>
          </div>
        </div>
      </section>
      <!-- Activity Summary -->
      <div class="activity-summary">
        <h3>Activity Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <span class="stat-num">0</span>
            <span class="stat-label">Listings</span>
          </div>
          <div class="stat">
            <span class="stat-num">0</span>
            <span class="stat-label">Booked</span>
          </div>
          <div class="stat">
            <span class="stat-num">0</span>
            <span class="stat-label">Bookings Received</span>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- Footer -->
   <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
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
  <!-- Dashboard scripts -->
  <script src="/TravelMate/public/assets/js/accommodation.js"></script>
  <script>
    // ensure loader runs once script is available
    (function(){
      function tryRun(){
        if (window.loadUserProperties) {
          try { window.loadUserProperties(); } catch(e){ console.error('loadUserProperties error', e); }
        } else {
          setTimeout(tryRun, 200);
        }
      }
      tryRun();
    })();
  </script>
</body>
</html>