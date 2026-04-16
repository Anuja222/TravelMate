<?php
// Initialize database connection for dynamic counts
require_once __DIR__ . '/../../core/config.php';
require_once __DIR__ . '/../../core/Database.php';

if (!class_exists('AdminDashboardDB')) {
    class AdminDashboardDB {
        use Database;
    }
}
$db = new AdminDashboardDB();

// Dynamic Data Fetching

// 1. Users
$usersData = $db->getRow("SELECT COUNT(*) as count FROM users");
$usersCount = $usersData ? $usersData->count : 0;

$travellersData = $db->getRow("SELECT COUNT(*) as count FROM users WHERE role = 'traveller'");
$travellersCount = $travellersData ? $travellersData->count : 0;

$accProvidersData = $db->getRow("SELECT COUNT(*) as count FROM users WHERE role = 'accommodation'");
$accProvidersCount = $accProvidersData ? $accProvidersData->count : 0;

$transProvidersData = $db->getRow("SELECT COUNT(*) as count FROM users WHERE role = 'transport'");
$transProvidersCount = $transProvidersData ? $transProvidersData->count : 0;

// 2. Listings
$hotelData = $db->getRow("SELECT COUNT(*) as count FROM accommodations");
$hotelCount = $hotelData ? $hotelData->count : 0;

$vehicleData = $db->getRow("SELECT COUNT(*) as count FROM vehicles");
$vehicleCount = $vehicleData ? $vehicleData->count : 0;

// 3. Bookings
$accBookingsData = $db->getRow("SELECT COUNT(*) as count FROM bookings");
$accBookingsCount = $accBookingsData ? $accBookingsData->count : 0;

$transBookingsData = $db->getRow("SELECT COUNT(*) as count FROM transport_bookings");
$transBookingsCount = $transBookingsData ? $transBookingsData->count : 0;
$totalBookings = $accBookingsCount + $transBookingsCount;

// 4. Content (Destinations & Activities & Posts)
$destinationsData = $db->getRow("SELECT COUNT(*) as count FROM destinations");
$destinationsCount = $destinationsData ? $destinationsData->count : 0;

$activitiesData = $db->getRow("SELECT COUNT(*) as count FROM activities");
$activitiesCount = $activitiesData ? $activitiesData->count : 0;

$postsData = $db->getRow("SELECT COUNT(*) as count FROM posts");
$postsCount = $postsData ? $postsData->count : 0;

$pendingVlogsData = $db->getRow("SELECT COUNT(*) as count FROM posts WHERE status = 'pending'");
$pendingVlogsCount = $pendingVlogsData ? $pendingVlogsData->count : 0;

?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
<<<<<<< HEAD
  <link rel="stylesheet" href="assets/css/Admin/dashboard.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
=======
  <link rel="stylesheet" href="assets/css/Admin/common.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="assets/css/Admin/dashboard.css?v=<?php echo time(); ?>">
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<div class="page-container">
  <?php include 'sidebar.view.php'; ?>

  <div class="content">
    <div class="page-title">
<<<<<<< HEAD
      <div class="page-title-content">
        <div class="page-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7"></rect>
            <rect x="14" y="3" width="7" height="7"></rect>
            <rect x="14" y="14" width="7" height="7"></rect>
            <rect x="3" y="14" width="7" height="7"></rect>
          </svg>
        </div>
        <div class="page-title-text">
          <h1>Admin Dashboard</h1>
          <p class="page-subtitle">Welcome back! Here is a summary of all website details.</p>
        </div>
      </div>
    </div>

    <!-- USERS SUMMARY -->
    <div class="dashboard-section">
      <h2 class="section-title"><i class="fas fa-users"></i> Users Summary</h2>
      <div class="dashboard-grid">
        <div class="dash-card theme-blue">
          <div class="card-icon"><i class="fas fa-user-friends"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Total Users</div>
            <div class="dash-card-value"><?php echo number_format($usersCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-teal">
          <div class="card-icon"><i class="fas fa-user-tie"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Travellers</div>
            <div class="dash-card-value"><?php echo number_format($travellersCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-green">
          <div class="card-icon"><i class="fas fa-hotel"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Accom. Providers</div>
            <div class="dash-card-value"><?php echo number_format($accProvidersCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-orange">
          <div class="card-icon"><i class="fas fa-car-side"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Transport Providers</div>
            <div class="dash-card-value"><?php echo number_format($transProvidersCount); ?></div>
          </div>
        </div>
=======
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
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
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

    <!-- LISTINGS & BOOKINGS SUMMARY -->
    <div class="dashboard-section">
      <h2 class="section-title"><i class="fas fa-list-alt"></i> Listings & Bookings</h2>
      <div class="dashboard-grid">
        <div class="dash-card theme-green">
          <div class="card-icon"><i class="fas fa-bed"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Accommodation Ads</div>
            <div class="dash-card-value"><?php echo number_format($hotelCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-orange">
          <div class="card-icon"><i class="fas fa-car"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Vehicle Ads</div>
            <div class="dash-card-value"><?php echo number_format($vehicleCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-purple">
          <div class="card-icon"><i class="fas fa-calendar-check"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Acc. Bookings</div>
            <div class="dash-card-value"><?php echo number_format($accBookingsCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-purple">
          <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Transport Bookings</div>
            <div class="dash-card-value"><?php echo number_format($transBookingsCount); ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- CONTENT SUMMARY -->
    <div class="dashboard-section">
      <h2 class="section-title"><i class="fas fa-globe-americas"></i> Content & Media</h2>
      <div class="dashboard-grid">
        <div class="dash-card theme-blue">
          <div class="card-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Total Destinations</div>
            <div class="dash-card-value"><?php echo number_format($destinationsCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-teal">
          <div class="card-icon"><i class="fas fa-hiking"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Total Activities</div>
            <div class="dash-card-value"><?php echo number_format($activitiesCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-red">
          <div class="card-icon"><i class="fas fa-blog"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Blogs / Vlogs</div>
            <div class="dash-card-value"><?php echo number_format($postsCount); ?></div>
          </div>
        </div>
        <div class="dash-card theme-red">
          <div class="card-icon"><i class="fas fa-clock"></i></div>
          <div class="card-info">
            <div class="dash-card-title">Pending Approvals</div>
            <div class="dash-card-value"><?php echo number_format($pendingVlogsCount); ?></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
