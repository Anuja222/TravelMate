<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/Admin/common.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="assets/css/Admin/dashboard.css?v=<?php echo time(); ?>">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<div class="page-container">
  <?php include 'sidebar.view.php'; ?>

  <div class="content">
    <div class="page-title">
      <h1>Dashboard</h1>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-header">
          <div>
            <div class="stat-value">1,200</div>
            <div class="stat-label">Total Users</div>
          </div>
          <div class="stat-icon">👥</div>
        </div>
        <div class="stat-change">
          <span>↑ 12%</span>
          <span>from last month</span>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-header">
          <div>
            <div class="stat-value">350</div>
            <div class="stat-label">Hotel Listings</div>
          </div>
          <div class="stat-icon">🏨</div>
        </div>
        <div class="stat-change">
          <span>↑ 8%</span>
          <span>from last month</span>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-header">
          <div>
            <div class="stat-value">75</div>
            <div class="stat-label">Vehicle Listings</div>
          </div>
          <div class="stat-icon">🚗</div>
        </div>
        <div class="stat-change">
          <span>↑ 5%</span>
          <span>from last month</span>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-header">
          <div>
            <div class="stat-value">55</div>
            <div class="stat-label">Total Bookings</div>
          </div>
          <div class="stat-icon">📅</div>
        </div>
        <div class="stat-change">
          <span>↓ 3%</span>
          <span>from last month</span>
        </div>
      </div>
    </div>

    <!-- Additional Stats Section -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-number">24</div>
        <div class="stat-label">Active Destinations 🌍</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-number">4.7</div>
        <div class="stat-label">Average Rating ⭐</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-number">$45,320</div>
        <div class="stat-label">Revenue (This Month) 💰</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-number">127</div>
        <div class="stat-label">New Reviews 💬</div>
      </div>
    </div>

    <!-- Pending Approvals Alert -->
    <div class="alert-card">
      <div class="alert-icon">⏰</div>
      <div class="alert-content">
        <h3>Pending Vlog Approvals</h3>
        <p>3 items require attention</p>
      </div>
      <button class="btn-view">View All</button>
    </div>
  </div>
</div>


</body>
</html>
