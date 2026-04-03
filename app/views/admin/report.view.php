<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - System Reports</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/report.css">
  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .stat-section-title { color: #2e6262; font-size: 1.1rem; margin: 25px 0 15px; font-weight: 700; }
    .custom-report-stats .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .stat-card.minimal { display: flex; align-items: center; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border-left: 4px solid transparent; }
    .stat-icon-square { width: 50px; height: 50px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 15px; }
    .stat-label { font-size: 0.75rem; font-weight: 700; color: #7f8c8d; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 5px; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: #2c3e50; }
    .border-blue { border-left-color: #3498db; } .bg-blue { background: #ebf5fb; } .text-blue { color: #3498db; }
    .border-green { border-left-color: #2ecc71; } .bg-green { background: #eafaf1; } .text-green { color: #2ecc71; }
    .border-orange { border-left-color: #f39c12; } .bg-orange { background: #fef5e7; } .text-orange { color: #f39c12; }
    .border-yellow { border-left-color: #f1c40f; } .bg-yellow { background: #fdfefe; } .text-yellow { color: #f1c40f; }
    .border-purple { border-left-color: #9b59b6; } .bg-purple { background: #f5eef8; } .text-purple { color: #9b59b6; }
    .border-light-blue { border-left-color: #5dade2; } .bg-light-blue { background: #ebf5fb; } .text-light-blue { color: #5dade2; }
    .border-mint { border-left-color: #1abc9c; } .bg-mint { background: #e8f8f5; } .text-mint { color: #1abc9c; }
    .border-red-light { border-left-color: #e74c3c; } .bg-red-light { background: #fdedec; } .text-red-light { color: #e74c3c; }
    .border-red { border-left-color: #c0392b; } .bg-red { background: #f9ebea; } .text-red { color: #c0392b; }
    .stat-change-text { font-size: 0.75rem; margin-top: 5px; font-weight: 600; display:flex; align-items:center; gap:4px; }
  </style>
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
      <div class="custom-report-stats">
        <h3 class="stat-section-title"><i class="fas fa-users"></i> Users Summary</h3>
        <div class="stats-grid custom-grid">
          <div class="stat-card minimal border-blue">
            <div class="stat-icon-square bg-blue"><i class="fas fa-users text-blue"></i></div>
            <div class="stat-info">
              <div class="stat-label">TOTAL USERS</div>
              <div class="stat-value" id="stat-total-users">...</div>
              <div class="stat-change-text" id="change-total-users"></div>
            </div>
          </div>
          <div class="stat-card minimal border-green">
            <div class="stat-icon-square bg-green"><i class="fas fa-user text-green"></i></div>
            <div class="stat-info">
              <div class="stat-label">TRAVELLERS</div>
              <div class="stat-value" id="stat-travellers">...</div>
              <div class="stat-change-text" id="change-travellers"></div>
            </div>
          </div>
          <div class="stat-card minimal border-green">
            <div class="stat-icon-square bg-green"><i class="fas fa-hotel text-green"></i></div>
            <div class="stat-info">
              <div class="stat-label">ACCOM. PROVIDERS</div>
              <div class="stat-value" id="stat-accom-providers">...</div>
              <div class="stat-change-text" id="change-accom-providers"></div>
            </div>
          </div>
          <div class="stat-card minimal border-orange">
            <div class="stat-icon-square bg-orange"><i class="fas fa-car text-orange"></i></div>
            <div class="stat-info">
              <div class="stat-label">TRANSPORT PROVIDERS</div>
              <div class="stat-value" id="stat-transport-providers">...</div>
              <div class="stat-change-text" id="change-transport-providers"></div>
            </div>
          </div>
        </div>

        <h3 class="stat-section-title"><i class="fas fa-list-alt"></i> Listings & Bookings</h3>
        <div class="stats-grid custom-grid">
          <div class="stat-card minimal border-green">
            <div class="stat-icon-square bg-green"><i class="fas fa-bed text-green"></i></div>
            <div class="stat-info">
              <div class="stat-label">ACCOMMODATION ADS</div>
              <div class="stat-value" id="stat-accom-ads">...</div>
              <div class="stat-change-text" id="change-accom-ads"></div>
            </div>
          </div>
          <div class="stat-card minimal border-yellow">
            <div class="stat-icon-square bg-yellow"><i class="fas fa-car text-yellow"></i></div>
            <div class="stat-info">
              <div class="stat-label">VEHICLE ADS</div>
              <div class="stat-value" id="stat-vehicle-ads">...</div>
              <div class="stat-change-text" id="change-vehicle-ads"></div>
            </div>
          </div>
          <div class="stat-card minimal border-purple">
            <div class="stat-icon-square bg-purple"><i class="fas fa-calendar-check text-purple"></i></div>
            <div class="stat-info">
              <div class="stat-label">ACC. BOOKINGS</div>
              <div class="stat-value" id="stat-acc-bookings">...</div>
              <div class="stat-change-text" id="change-acc-bookings"></div>
            </div>
          </div>
          <div class="stat-card minimal border-purple">
            <div class="stat-icon-square bg-purple"><i class="fas fa-calendar-alt text-purple"></i></div>
            <div class="stat-info">
              <div class="stat-label">TRANSPORT BOOKINGS</div>
              <div class="stat-value" id="stat-transport-bookings">...</div>
              <div class="stat-change-text" id="change-transport-bookings"></div>
            </div>
          </div>
        </div>

        <h3 class="stat-section-title"><i class="fas fa-globe"></i> Content & Media</h3>
        <div class="stats-grid custom-grid">
          <div class="stat-card minimal border-light-blue">
            <div class="stat-icon-square bg-light-blue"><i class="fas fa-map-marker-alt text-light-blue"></i></div>
            <div class="stat-info">
              <div class="stat-label">TOTAL DESTINATIONS</div>
              <div class="stat-value" id="stat-total-destinations">...</div>
              <div class="stat-change-text" id="change-total-destinations"></div>
            </div>
          </div>
          <div class="stat-card minimal border-mint">
            <div class="stat-icon-square bg-mint"><i class="fas fa-hiking text-mint"></i></div>
            <div class="stat-info">
              <div class="stat-label">TOTAL ACTIVITIES</div>
              <div class="stat-value" id="stat-total-activities">...</div>
              <div class="stat-change-text" id="change-total-activities"></div>
            </div>
          </div>
          <div class="stat-card minimal border-red-light">
            <div class="stat-icon-square bg-red-light"><i class="fas fa-blog text-red-light"></i></div>
            <div class="stat-info">
              <div class="stat-label">BLOGS / VLOGS</div>
              <div class="stat-value" id="stat-blogs-vlogs">...</div>
              <div class="stat-change-text" id="change-blogs-vlogs"></div>
            </div>
          </div>
          <div class="stat-card minimal border-red">
            <div class="stat-icon-square bg-red"><i class="fas fa-clock text-red"></i></div>
            <div class="stat-info">
              <div class="stat-label">PENDING APPROVALS</div>
              <div class="stat-value" id="stat-pending">...</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="charts-section">
        <div class="chart-card">
          <div class="chart-header">
            <h3>Booking Trends <span id="bookingTrendRange" style="font-size:0.8rem; font-weight:normal; color:#7f8c8d;">(Last 7 Days)</span></h3>
            <div class="chart-actions">
              <button class="chart-btn active" data-period="weekly">Weekly</button>
              <button class="chart-btn" data-period="monthly">Monthly</button>
              <button class="chart-btn" data-period="yearly">Yearly</button>
            </div>
          </div>
          <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="bookingTrendsChart"></canvas>
          </div>
        </div>

        <div class="chart-card">
          <div class="chart-header">
            <h3>User Distribution</h3>
          </div>
          <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="userDistChart"></canvas>
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
    let bookingChartInstance = null;
    let userDistChartInstance = null;

    // Time period selector
    document.getElementById('timePeriod').addEventListener('change', function() {
      const activeChartBtn = document.querySelector('.chart-btn.active');
      const chartPeriod = activeChartBtn ? activeChartBtn.getAttribute('data-period') : 'weekly';
      updateDashboardData(this.value, chartPeriod);
    });

    function updateDashboardData(period, chartPeriod = 'weekly') {
      console.log('Fetching dashboard data for period:', period, 'chart:', chartPeriod);
      // Show loading state
      const stats = document.querySelectorAll('.stat-value');
      const changes = document.querySelectorAll('.stat-change span:first-child');
      stats.forEach(stat => stat.textContent = '...');
      changes.forEach(change => change.textContent = '...');
      
      fetch('<?= ROOT ?>/report_stats?period=' + period + '&chart_period=' + chartPeriod)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            
            // Update stats
            document.getElementById('stat-total-users').textContent = data.stats.users.value;
            document.getElementById('change-total-users').textContent = data.stats.users.change;
            
            document.getElementById('stat-travellers').textContent = data.stats.travellers.value;
            document.getElementById('change-travellers').textContent = data.stats.travellers.change;
            
            document.getElementById('stat-accom-providers').textContent = data.stats.accom_providers.value;
            document.getElementById('change-accom-providers').textContent = data.stats.accom_providers.change;
            
            document.getElementById('stat-transport-providers').textContent = data.stats.transport_providers.value;
            document.getElementById('change-transport-providers').textContent = data.stats.transport_providers.change;
            
            document.getElementById('stat-accom-ads').textContent = data.stats.accom_ads.value;
            document.getElementById('change-accom-ads').textContent = data.stats.accom_ads.change;
            
            document.getElementById('stat-vehicle-ads').textContent = data.stats.vehicle_ads.value;
            document.getElementById('change-vehicle-ads').textContent = data.stats.vehicle_ads.change;
            
            document.getElementById('stat-acc-bookings').textContent = data.stats.acc_bookings.value;
            document.getElementById('change-acc-bookings').textContent = data.stats.acc_bookings.change;
            
            document.getElementById('stat-transport-bookings').textContent = data.stats.transport_bookings.value;
            document.getElementById('change-transport-bookings').textContent = data.stats.transport_bookings.change;
            
            document.getElementById('stat-total-destinations').textContent = data.stats.total_destinations.value;
            document.getElementById('change-total-destinations').textContent = data.stats.total_destinations.change;
            
            document.getElementById('stat-total-activities').textContent = data.stats.total_activities.value;
            document.getElementById('change-total-activities').textContent = data.stats.total_activities.change;
            
            document.getElementById('stat-blogs-vlogs').textContent = data.stats.blogs_vlogs.value;
            document.getElementById('change-blogs-vlogs').textContent = data.stats.blogs_vlogs.change;
            
            document.getElementById('stat-pending').textContent = data.stats.pending_approvals.value;
            
            // Format stat changes
            const applyChange = (id, changeText) => {
                const el = document.getElementById(id);
                if (!el) return;
                let color = '#7f8c8d'; // neutral
                let icon = ''; // neutral
                
                if (changeText.includes('-')) {
                    color = '#e74c3c';
                    icon = '<i class="fas fa-arrow-down"></i>';
                } else if (changeText !== '0%') {
                    color = '#1abc5b';
                    icon = '<i class="fas fa-arrow-up"></i>';
                } else {
                    icon = '<i class="fas fa-minus"></i>';
                }
                
                const periodText = document.getElementById('timePeriod').options[document.getElementById('timePeriod').selectedIndex].text.toLowerCase();
                el.innerHTML = `<span style="color: ${color}">${icon} ${changeText}</span> <span style="color: #95a5a6; font-weight: normal; margin-left: 2px;">from last ${periodText.replace('this ', '')}</span>`;
            };

            applyChange('change-total-users', data.stats.users.change);
            applyChange('change-travellers', data.stats.travellers.change);
            applyChange('change-accom-providers', data.stats.accom_providers.change);
            applyChange('change-transport-providers', data.stats.transport_providers.change);
            applyChange('change-accom-ads', data.stats.accom_ads.change);
            applyChange('change-vehicle-ads', data.stats.vehicle_ads.change);
            applyChange('change-acc-bookings', data.stats.acc_bookings.change);
            applyChange('change-transport-bookings', data.stats.transport_bookings.change);
            applyChange('change-total-destinations', data.stats.total_destinations.change);
            applyChange('change-total-activities', data.stats.total_activities.change);
            applyChange('change-blogs-vlogs', data.stats.blogs_vlogs.change);
            
            // Update Charts
            if (data.charts) {
                // Booking Trends Chart
                const bookingCtx = document.getElementById('bookingTrendsChart').getContext('2d');
                if (bookingChartInstance) {
                    bookingChartInstance.destroy();
                }
                bookingChartInstance = new Chart(bookingCtx, {
                    type: 'line',
                    data: {
                        labels: data.charts.bookingTrends.labels,
                        datasets: [{
                            label: 'Bookings',
                            data: data.charts.bookingTrends.data,
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });

                // User Distribution Chart
                const userDistCtx = document.getElementById('userDistChart').getContext('2d');
                if (userDistChartInstance) {
                    userDistChartInstance.destroy();
                }
                userDistChartInstance = new Chart(userDistCtx, {
                    type: 'pie',
                    data: {
                        labels: data.charts.userDistribution.labels,
                        datasets: [{
                            data: data.charts.userDistribution.data,
                            backgroundColor: ['#1abc5b', '#f1c40f', '#e74c3c', '#9b59b6', '#34495e'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }

            // Format stat changes colors depending on +/- // (Removed since change metrics aren't in this UI, but you can add them back)

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
        
        const chartPeriod = this.getAttribute('data-period');
        let rangeText = "(Last 7 Days)";
        if (chartPeriod === 'monthly') rangeText = "(Last 30 Days)";
        if (chartPeriod === 'yearly') rangeText = "(Last 12 Months)";
        document.getElementById('bookingTrendRange').innerText = rangeText;

        const period = document.getElementById('timePeriod').value;
        updateDashboardData(period, chartPeriod);
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