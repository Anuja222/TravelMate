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
  <link rel="stylesheet" href="assets/css/accommodation/dashboard.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- Navbar -->
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <main>
    <aside class="sidebar">
      <ul>
        <li><a href="#">My Listings</a></li>
        <li><a href="#">Bookings</a></li>
        <li><a href="#">Profile Settings</a></li>
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
          <h2 class="profile-name">Chamuditha Lakmal</h2>
          <span class="profile-email">Lakmal123@gmail.com</span>
        </div>
      </div>
      <!-- Favourites -->
      <div class="favourites">
        <h3>My Listings</h3>
        <div class="fav-cards">
          <a href="/TravelMate/Accomodation_provider/viewProperty" class="fav-card" style="text-decoration:none;color:inherit;">
            <img src="assets/egypt.jpg" alt="Egypt" class="fav-img">
            <div class="fav-info">
              <h3>ABC Villa</h3>
              <!-- <div class="fav-rating">
                <span>★★★★★</span>
                <span>(13 reviews)</span>
              </div>
              <div class="fav-details">
                <span>6 Nights, 6 days</span>
                <span>From: $1500</span>
              </div> -->
            </div>
          </a>
          <a href="/TravelMate/Accomodation_provider/viewProperty" class="fav-card" style="text-decoration:none;color:inherit;">
            <img src="assets/usa.jpg" alt="USA" class="fav-img">
            <div class="fav-info">
              <h3>XYZ Hotel</h3>
              <!-- <div class="fav-rating">
                <span>★★★★★</span>
                <span>(8 reviews)</span>
              </div>
              <div class="fav-details">
                <span>7 Nights, 8 days</span>
                <span>From: $2200</span>
              </div> -->
            </div>
          </a>
          <a href="/TravelMate/Accomodation_provider/viewProperty" class="fav-card" style="text-decoration:none;color:inherit;">
            <img src="assets/spain.jpg" alt="Spain" class="fav-img">
            <div class="fav-info">
              <h3>LMN Guest House</h3>
              <div class="fav-rating">
                <!-- <span>★★★★☆</span>
                <span>(9 reviews)</span>
              </div>
              <div class="fav-details">
                <span>6 Nights, 6 days</span>
                <span>From: $900</span>
              </div> -->
            </div>
          </a>
        </div>
      </div>
      <!-- Activity Summary -->
      <div class="activity-summary">
        <h3>Activity Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <span class="stat-num">4</span>
            <span class="stat-label">Listings</span>
          </div>
          <div class="stat">
            <span class="stat-num">3</span>
            <span class="stat-label">Booked</span>
          </div>
          <div class="stat">
            <span class="stat-num">6</span>
            <span class="stat-label">Bookings Received</span>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- Footer -->
  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
  <script src="dashboard.js"></script>
</body>
</html>