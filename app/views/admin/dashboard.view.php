<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/Admin/dashboard.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>


<div class="page-container">
   <?php include 'sidebar.view.php'; ?>

  <div class="content">

    <div class="page-title">
      <h1><i class="fas fa-chart-line"></i> Dashboard Overview</h1>
      <p class="subtitle">Monitor your platform's performance at a glance</p>
    </div>

    <div class="cards-container">
      <div class="card card-users">
        <div class="card-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="card-content">
          <div class="card-title">Total Users</div>
          <div class="card-value">1,200</div>
          <div class="card-trend">
            <i class="fas fa-arrow-up"></i> 12% from last month
          </div>
        </div>
      </div>
      
      <div class="card card-hotels">
        <div class="card-icon">
          <i class="fas fa-hotel"></i>
        </div>
        <div class="card-content">
          <div class="card-title">Hotel Listings</div>
          <div class="card-value">350</div>
          <div class="card-trend">
            <i class="fas fa-arrow-up"></i> 8% from last month
          </div>
        </div>
      </div>
      
      <div class="card card-vehicles">
        <div class="card-icon">
          <i class="fas fa-car"></i>
        </div>
        <div class="card-content">
          <div class="card-title">Vehicle Listings</div>
          <div class="card-value">75</div>
          <div class="card-trend">
            <i class="fas fa-arrow-up"></i> 5% from last month
          </div>
        </div>
      </div>
      
      <div class="card card-bookings">
        <div class="card-icon">
          <i class="fas fa-calendar-check"></i>
        </div>
        <div class="card-content">
          <div class="card-title">Total Bookings</div>
          <div class="card-value">55</div>
          <div class="card-trend">
            <i class="fas fa-arrow-down"></i> 3% from last month
          </div>
        </div>
      </div>
      
      <div class="card card-pending">
        <div class="card-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="card-content">
          <div class="card-title">Pending Vlog Approvals</div>
          <div class="card-value">3</div>
          <div class="card-trend">
            <i class="fas fa-exclamation-circle"></i> Requires attention
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Stats Section -->
    <div class="stats-row">
      <div class="stat-item">
        <div class="stat-icon">
          <i class="fas fa-globe"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Active Destinations</span>
          <span class="stat-value">24</span>
        </div>
      </div>
      
      <div class="stat-item">
        <div class="stat-icon">
          <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Average Rating</span>
          <span class="stat-value">4.7</span>
        </div>
      </div>
      
      <div class="stat-item">
        <div class="stat-icon">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Revenue (This Month)</span>
          <span class="stat-value">$45,320</span>
        </div>
      </div>
      
      <div class="stat-item">
        <div class="stat-icon">
          <i class="fas fa-comments"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">New Reviews</span>
          <span class="stat-value">127</span>
        </div>
      </div>
    </div>
  </div>
</div>


</body>
</html>
