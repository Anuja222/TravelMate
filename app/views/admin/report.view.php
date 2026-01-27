<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - System Reports</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/report.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

  <div class="page-container">

    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <!-- Dashboard Header -->
      <div class="dashboard-header">
        <div class="dashboard-title">
          <h1>System Dashboard</h1>
          <div class="date-range">
            <span>Period:</span>
            <select id="timePeriod">
              <option value="today">Today</option>
              <option value="week" selected>This Week</option>
              <option value="month">This Month</option>
              <option value="quarter">This Quarter</option>
              <option value="year">This Year</option>
            </select>
          </div>
        </div>
        <p style="color: #666; margin: 0;">Overview of platform performance and key metrics</p>
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-header">
            <div>
              <div class="stat-value">2,847</div>
              <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-icon icon-users">👥</div>
          </div>
          <div class="stat-change change-positive">
            <span>↑ 12.5%</span>
            <span>from last week</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <div>
              <div class="stat-value">156</div>
              <div class="stat-label">Active Listings</div>
            </div>
            <div class="stat-icon icon-listings">🏨</div>
          </div>
          <div class="stat-change change-positive">
            <span>↑ 5.2%</span>
            <span>from last week</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <div>
              <div class="stat-value">324</div>
              <div class="stat-label">Total Bookings</div>
            </div>
            <div class="stat-icon icon-bookings">📅</div>
          </div>
          <div class="stat-change change-positive">
            <span>↑ 8.7%</span>
            <span>from last week</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <div>
              <div class="stat-value">$24,580</div>
              <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-icon icon-revenue">💰</div>
          </div>
          <div class="stat-change change-positive">
            <span>↑ 15.3%</span>
            <span>from last week</span>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="charts-section">
        <div class="chart-card">
          <div class="chart-header">
            <h3>Booking Trends</h3>
            <div class="chart-actions">
              <button class="chart-btn active">Weekly</button>
              <button class="chart-btn">Monthly</button>
              <button class="chart-btn">Yearly</button>
            </div>
          </div>
          <div class="chart-placeholder">
            📈 Booking Trends Chart Visualization
          </div>
        </div>

        <div class="chart-card">
          <div class="chart-header">
            <h3>User Distribution</h3>
            <div class="chart-actions">
              <button class="chart-btn active">Types</button>
              <button class="chart-btn">Regions</button>
            </div>
          </div>
          <div class="chart-placeholder">
            🥧 User Distribution Pie Chart
          </div>
        </div>
      </div>

      <!-- Data Tables -->
      <div class="data-section">
        <div class="data-card">
          <div class="data-header">
            <h3>Recent Users</h3>
            <a href="Users" class="view-all">View All →</a>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Type</th>
                <th>Join Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="User" class="user-avatar">
                    <span>Amal Kumarasinghe</span>
                  </div>
                </td>
                <td>Traveler</td>
                <td>Mar 12, 2024</td>
                <td><span class="status-badge status-active">Active</span></td>
              </tr>
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="User" class="user-avatar">
                    <span>Sunil Perera</span>
                  </div>
                </td>
                <td>Provider</td>
                <td>Mar 10, 2024</td>
                <td><span class="status-badge status-pending">Pending</span></td>
              </tr>
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="User" class="user-avatar">
                    <span>Mala Fernando</span>
                  </div>
                </td>
                <td>Provider</td>
                <td>Mar 8, 2024</td>
                <td><span class="status-badge status-active">Active</span></td>
              </tr>
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="User" class="user-avatar">
                    <span>Ravi Jayasuriya</span>
                  </div>
                </td>
                <td>Traveler</td>
                <td>Mar 5, 2024</td>
                <td><span class="status-badge status-suspended">Suspended</span></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="data-card">
          <div class="data-header">
            <h3>Pending Approvals</h3>
            <a href="content" class="view-all">View All →</a>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <th>Content</th>
                <th>Type</th>
                <th>Submitted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Sunset Beach Resort Listing</td>
                <td>Hotel</td>
                <td>2 hours ago</td>
                <td>
                  <button style="background: #1abc5b; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">Approve</button>
                  <button style="background: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">Reject</button>
                </td>
              </tr>
              <tr>
                <td>Mountain Hiking Guide Blog</td>
                <td>Blog</td>
                <td>5 hours ago</td>
                <td>
                  <button style="background: #1abc5b; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">Approve</button>
                  <button style="background: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">Reject</button>
                </td>
              </tr>
              <tr>
                <td>Luxury Car Rental - Toyota Premio</td>
                <td>Vehicle</td>
                <td>1 day ago</td>
                <td>
                  <button style="background: #1abc5b; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">Approve</button>
                  <button style="background: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">Reject</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Time period selector
    document.getElementById('timePeriod').addEventListener('change', function() {
      updateDashboardData(this.value);
    });

    function updateDashboardData(period) {
      // In a real application, this would fetch new data from the server
      console.log('Updating dashboard data for period:', period);
      // Show loading state
      const stats = document.querySelectorAll('.stat-value');
      stats.forEach(stat => {
        stat.textContent = '...';
      });
      
      // Simulate API call delay
      setTimeout(() => {
        // Update with new data (mock)
        document.querySelector('.stat-card:nth-child(1) .stat-value').textContent = '2,847';
        document.querySelector('.stat-card:nth-child(2) .stat-value').textContent = '156';
        document.querySelector('.stat-card:nth-child(3) .stat-value').textContent = '324';
        document.querySelector('.stat-card:nth-child(4) .stat-value').textContent = '$24,580';
      }, 1000);
    }

    // Chart period buttons
    document.querySelectorAll('.chart-btn').forEach(button => {
      button.addEventListener('click', function() {
        document.querySelectorAll('.chart-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Quick action functions
    function generateReport() {
      alert('Report generation functionality would open here');
    }

    function systemSettings() {
      alert('System settings panel would open here');
    }

    function backupSystem() {
      if (confirm('Create a system backup? This may take a few minutes.')) {
        alert('Backup process started...');
      }
    }

    // Initialize dashboard with current data
    document.addEventListener('DOMContentLoaded', function() {
      updateDashboardData('week');
    });
  </script>

</body>
</html>
