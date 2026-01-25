<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TravelMate - Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/Traveller/dashboard.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
  <!-- Navbar -->
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <main>
    <aside class="sidebar">
      <ul class="sidebar-menu">
        <li class="sidebar-item">
          <a href="mybookings" class="sidebar-link">
            <!-- <span class="sidebar-icon">🏨</span> -->
            <span class="sidebar-text">My Accommodation Bookings</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="mytransportbookings" class="sidebar-link">
            <!-- <span class="sidebar-icon">🚗</span> -->
            <span class="sidebar-text">My Transport Bookings</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link">
            <!-- <span class="sidebar-icon">📹</span> -->
            <span class="sidebar-text">My Vlogs</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="profile_setting" class="sidebar-link">
            <!-- <span class="sidebar-icon">⚙️</span> -->
            <span class="sidebar-text">Profile Settings</span>
          </a>
        </li>
        <li class="sidebar-item sidebar-logout">
          <a href="logout.php" class="sidebar-link logout-link">
            <!-- <span class="sidebar-icon">🚪</span> -->
            <span class="sidebar-text">Log Out</span>
          </a>
        </li>
      </ul>
    </aside>
    <section class="dashboard-content">
      <!-- Cover -->
      <div class="cover">
        <img src="assets/images/cover.jpg" alt="Travel Cover" class="cover-img">
        <span class="cover-text">TRAVEL <span class="cover-sub">more</span></span>
      </div>
      <!-- Profile -->
      <div class="profile-section">
        <img src="assets/images/profile.jpg" alt="User" class="profile-pic">
        <div>
          <h2 class="profile-name"><?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?></h2>
          <span class="profile-email"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
        </div>
      </div>
      <!-- Favourites -->
      <div class="favourites">
        <h3>Favourite Destinations</h3>
        <div class="fav-cards">
          <div class="fav-card">
            <img src="assets/egypt.jpg" alt="Egypt" class="fav-img">
            <div class="fav-info">
              <h3>Galle</h3>
              <!-- <div class="fav-rating">
                <span>★★★★★</span>
                <span>(13 reviews)</span>
              </div>
              <div class="fav-details">
                <span>6 Nights, 6 days</span>
                <span>From: $1500</span>
              </div> -->
            </div>
          </div>
          <div class="fav-card">
            <img src="assets/usa.jpg" alt="USA" class="fav-img">
            <div class="fav-info">
              <h3>Anuradhapura</h3>
              <!-- <div class="fav-rating">
                <span>★★★★★</span>
                <span>(8 reviews)</span>
              </div>
              <div class="fav-details">
                <span>7 Nights, 8 days</span>
                <span>From: $2200</span>
              </div> -->
            </div>
          </div>
          <div class="fav-card">
            <img src="assets/spain.jpg" alt="Spain" class="fav-img">
            <div class="fav-info">
              <h3>Nuwaraeliya</h3>
              <div class="fav-rating">
                <!-- <span>★★★★☆</span>
                <span>(9 reviews)</span>
              </div>
              <div class="fav-details">
                <span>6 Nights, 6 days</span>
                <span>From: $900</span>
              </div> -->
            </div>
          </div>
        </div>
      </div>
      <!-- Activity Summary -->
      <div class="activity-summary">
        <h3>Activity Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <span class="stat-num">4</span>
            <span class="stat-label">Number of bookings made</span>
          </div>
          <div class="stat">
            <span class="stat-num">3</span>
            <span class="stat-label">Trips Planned</span>
          </div>
          <div class="stat">
            <span class="stat-num">6</span>
            <span class="stat-label">Vlogs Posted</span>
          </div>
        </div>
      </div>
    </section>
  </main>
  
  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script src="dashboard.js"></script>
</body>
</html>