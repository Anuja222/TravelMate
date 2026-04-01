<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - System Reports</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/report.css">
  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-container">

    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <!-- Dashboard Header -->
      <div class="page-title">
        <div class="page-title-content">
          <div class="page-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="20" x2="12" y2="10"></line>
              <line x1="18" y1="20" x2="18" y2="4"></line>
              <line x1="6" y1="20" x2="6" y2="16"></line>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>System Dashboard</h1>
            <p class="page-subtitle">Overview of platform performance and key metrics</p>
          </div>
        </div>
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

      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-header">
            <div>
              <div class="stat-value">2,847</div>
              <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-icon icon-users"><i class="fas fa-users"></i></div>
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
            <div class="stat-icon icon-listings"><i class="fas fa-hotel"></i></div>
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
            <div class="stat-icon icon-bookings"><i class="fas fa-calendar-check"></i></div>
          </div>
          <div class="stat-change change-positive">
            <span>↑ 8.7%</span>
            <span>from last week</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <div>
              <div class="stat-value">Rs. 0.00</div>
              <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-icon icon-revenue"><i class="fas fa-money-bill-wave"></i></div>
          </div>
          <div class="stat-change change-positive">
            <span>0%</span>
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
            <i class="fas fa-chart-line" style="font-size: 2rem; margin-right: 10px; color: #3498db;"></i> Booking Trends Chart Visualization
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
            <i class="fas fa-chart-pie" style="font-size: 2rem; margin-right: 10px; color: #1abc5b;"></i> User Distribution Pie Chart
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
                    <img src="assets/images/profile.jpg" alt="User" class="user-avatar">
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
                    <img src="assets/images/profile.jpg" alt="User" class="user-avatar">
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
                    <img src="assets/images/profile.jpg" alt="User" class="user-avatar">
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
                    <img src="assets/images/profile.jpg" alt="User" class="user-avatar">
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

      <!-- Quick Actions -->
      <div class="quick-actions">
        <h3 style="margin: 0 0 20px 0; color: #222;">Quick Actions</h3>
        <div class="actions-grid">
          <div class="action-btn" onclick="window.location.href='Users'">
            <div class="action-icon"><i class="fas fa-users-cog"></i></div>
            <div class="action-label">Manage Users</div>
          </div>
          <div class="action-btn" onclick="window.location.href='content'">
            <div class="action-icon"><i class="fas fa-clipboard-check"></i></div>
            <div class="action-label">Content Moderation</div>
          </div>
          <div class="action-btn" onclick="window.location.href='ViewListing'">
            <div class="action-icon"><i class="fas fa-hotel"></i></div>
            <div class="action-label">View Listings</div>
          </div>
          <div class="action-btn" onclick="generateReport()">
            <div class="action-icon"><i class="fas fa-file-invoice"></i></div>
            <div class="action-label">Generate Report</div>
          </div>
          <div class="action-btn" onclick="systemSettings()">
            <div class="action-icon"><i class="fas fa-cog"></i></div>
            <div class="action-label">System Settings</div>
          </div>
          <div class="action-btn" onclick="backupSystem()">
            <div class="action-icon"><i class="fas fa-database"></i></div>
            <div class="action-label">Backup System</div>
          </div>
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
      console.log('Fetching dashboard data for period:', period);
      // Show loading state
      const stats = document.querySelectorAll('.stat-value');
      const changes = document.querySelectorAll('.stat-change span:first-child');
      stats.forEach(stat => stat.textContent = '...');
      changes.forEach(change => change.textContent = '...');
      
      fetch('<?= ROOT ?>/report_stats?period=' + period)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            
            // Update stats
            const statCards = document.querySelectorAll('.stat-card');
            statCards[0].querySelector('.stat-value').textContent = data.stats.users.value;
            statCards[0].querySelector('.stat-change span:first-child').textContent = data.stats.users.change;
            
            statCards[1].querySelector('.stat-value').textContent = data.stats.listings.value;
            statCards[1].querySelector('.stat-change span:first-child').textContent = data.stats.listings.change;
            
            statCards[2].querySelector('.stat-value').textContent = data.stats.bookings.value;
            statCards[2].querySelector('.stat-change span:first-child').textContent = data.stats.bookings.change;
            
            statCards[3].querySelector('.stat-value').textContent = data.stats.revenue.value;
            statCards[3].querySelector('.stat-change span:first-child').textContent = data.stats.revenue.change;
            
            // Format stat changes colors depending on +/-
            document.querySelectorAll('.stat-change').forEach(el => {
                const changeText = el.querySelector('span:first-child').textContent;
                if (changeText.includes('-')) {
                    el.style.color = '#e74c3c';
                } else if (changeText !== '0%') {
                    el.style.color = '#1abc5b';
                } else {
                    el.style.color = '#7f8c8d';
                }
            });

            // Update recent users table
            const usersTbody = document.querySelector('.data-card:nth-child(1) .data-table tbody');
            usersTbody.innerHTML = '';
            if (data.lists.recentUsers.length > 0) {
                data.lists.recentUsers.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                      <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                          <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="User" class="user-avatar" onerror="this.src='<?= ROOT ?>/assets/images/default.jpg'">
                          <span>${user.first_name || ''} ${user.last_name || ''}</span>
                        </div>
                      </td>
                      <td>${user.role ? (user.role.charAt(0).toUpperCase() + user.role.slice(1)) : 'Unknown'}</td>
                      <td>${new Date(user.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</td>
                      <td><span class="status-badge status-active">Active</span></td>
                    `;
                    usersTbody.appendChild(tr);
                });
            } else {
                usersTbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No recent users</td></tr>';
            }

            // Update pending approvals table
            const pendingTbody = document.querySelector('.data-card:nth-child(2) .data-table tbody');
            pendingTbody.innerHTML = '';
            if (data.lists.pending.length > 0) {
                data.lists.pending.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                      <td>${item.content || 'N/A'}</td>
                      <td>${item.type || 'N/A'}</td>
                      <td>${new Date(item.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</td>
                      <td>
                        <button style="background: #1abc5b; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">Approve</button>
                        <button style="background: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">Reject</button>
                      </td>
                    `;
                    pendingTbody.appendChild(tr);
                });
            } else {
                pendingTbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No pending approvals</td></tr>';
            }
        })
        .catch(error => console.error('Error fetching dashboard data:', error));
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