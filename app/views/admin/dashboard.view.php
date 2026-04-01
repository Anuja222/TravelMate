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
  <link rel="stylesheet" href="assets/css/Admin/dashboard.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Section Groupings */
    .dashboard-section {
      margin-bottom: 40px;
    }
    .section-title {
      font-size: 1.25rem;
      color: #2c5f5d;
      margin-bottom: 20px;
      font-weight: 600;
      border-bottom: 2px solid #eef2f5;
      padding-bottom: 10px;
    }

    /* Redesigned grid and cards */
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 25px;
    }

    .dash-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.04);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      position: relative;
      overflow: hidden;
      border: 1px solid #f0f0f0;
    }
    .dash-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    }
    .dash-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; width: 4px; height: 100%;
      border-radius: 12px 0 0 12px;
    }

    /* Color Themes */
    .theme-blue::before { background: #3498db; }
    .theme-blue .card-icon { background: rgba(52, 152, 219, 0.1); color: #3498db; }
    
    .theme-green::before { background: #27ae60; }
    .theme-green .card-icon { background: rgba(39, 174, 96, 0.1); color: #27ae60; }
    
    .theme-orange::before { background: #f39c12; }
    .theme-orange .card-icon { background: rgba(243, 156, 18, 0.1); color: #f39c12; }
    
    .theme-purple::before { background: #9b59b6; }
    .theme-purple .card-icon { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }
    
    .theme-red::before { background: #e74c3c; }
    .theme-red .card-icon { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }
    
    .theme-teal::before { background: #1abc9c; }
    .theme-teal .card-icon { background: rgba(26, 188, 156, 0.1); color: #1abc9c; }

    .card-icon {
      width: 55px;
      height: 55px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      flex-shrink: 0;
    }
    
    .card-info { flex-grow: 1; text-align: left; }
    .dash-card-title {
      font-size: 13px;
      color: #7f8fa6;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
    }
    .dash-card-value {
      font-size: 24px;
      font-weight: 700;
      color: #2c3e50;
      margin: 0;
      line-height: 1.1;
    }
  </style>
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<div class="page-container">
   <?php include 'sidebar.view.php'; ?>

  <div class="content">

    <div class="page-title">
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
      </div>
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
