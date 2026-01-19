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
        <h3>Pending Blog Approvals</h3>
        <p>3 items require attention</p>
      </div>
      <button class="btn-view" onclick="window.location.href='content'">View All</button>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions" style="margin-top: 30px;">
      <h3 style="margin: 0 0 20px 0; color: #222;">Quick Actions</h3>
      <div class="actions-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div class="action-btn" onclick="window.location.href='Users'" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: all 0.3s ease;">
          <div class="action-icon" style="font-size: 2.5rem; margin-bottom: 10px;">👥</div>
          <div class="action-label" style="color: #333; font-weight: 500;">Manage Users</div>
        </div>
        <div class="action-btn" onclick="window.location.href='content'" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: all 0.3s ease;">
          <div class="action-icon" style="font-size: 2.5rem; margin-bottom: 10px;">📝</div>
          <div class="action-label" style="color: #333; font-weight: 500;">Content Moderation</div>
        </div>
        <div class="action-btn" onclick="window.location.href='ViewListing'" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: all 0.3s ease;">
          <div class="action-icon" style="font-size: 2.5rem; margin-bottom: 10px;">🏨</div>
          <div class="action-label" style="color: #333; font-weight: 500;">View Listings</div>
        </div>
        <div class="action-btn" onclick="generateReport()" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: all 0.3s ease;">
          <div class="action-icon" style="font-size: 2.5rem; margin-bottom: 10px;">📊</div>
          <div class="action-label" style="color: #333; font-weight: 500;">Generate Report</div>
        </div>
        <div class="action-btn" onclick="systemSettings()" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: all 0.3s ease;">
          <div class="action-icon" style="font-size: 2.5rem; margin-bottom: 10px;">⚙️</div>
          <div class="action-label" style="color: #333; font-weight: 500;">System Settings</div>
        </div>
        <div class="action-btn" onclick="backupSystem()" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: all 0.3s ease;">
          <div class="action-icon" style="font-size: 2.5rem; margin-bottom: 10px;">💾</div>
          <div class="action-label" style="color: #333; font-weight: 500;">Backup System</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Quick action functions
  function generateReport() {
    window.location.href='report';
  }

  function systemSettings() {
    window.location.href='setting';
  }

  function backupSystem() {
    if (confirm('Create a system backup? This may take a few minutes.')) {
      alert('Backup process started...');
    }
  }
</script>


</body>
</html>
