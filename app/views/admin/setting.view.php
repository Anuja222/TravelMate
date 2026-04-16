<!DOCTYPE html>
<html>
<head>
  <title>Site Config</title>
  <link rel="stylesheet" href="assets/css/Admin/setting.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
      
  <!-- Content Area -->
 <div class="page-container">

 <?php include 'sidebar.view.php'; ?>

  <div class="content">
    <div class="page-title">
      <div class="page-title-content">
        <div class="page-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M12 1v6m0 6v6m10-7h-6m-6 0H4m15.364 6.364l-4.243-4.243m-6.364 0l-4.243 4.243M19.071 4.929l-4.243 4.243m-6.364 0L4.222 4.93"></path>
          </svg>
        </div>
        <div class="page-title-text">
          <h1>System Settings</h1>
          <p class="page-subtitle">Configure and manage platform settings</p>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
      <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">2,548</div>
        <div class="stat-trend">+12% this month</div>
      </div>

      <div class="stat-card">
        <div class="stat-label">System Health</div>
        <div class="stat-value">98%</div>
        <div class="stat-trend">All systems operational</div>
      </div>

      <div class="stat-card">
        <div class="stat-label">Storage Used</div>
        <div class="stat-value">64%</div>
        <div class="stat-trend">325GB of 512GB</div>
      </div>
     </div>

    <!-- Settings Grid -->
    <div class="settings-grid">

      <!-- About & Terms Section -->
      <section class="settings-section">
        <div class="section-header">
          <h2>About & Legal</h2>
        </div>
        <form>
          <label for="about">About Us:</label>
          <textarea id="about" rows="4" placeholder="Enter information about your company..."></textarea>

          <label for="terms">Terms & Conditions:</label>
          <textarea id="terms" rows="4" placeholder="Enter terms and conditions..."></textarea>

          <button type="submit" class="btn save-btn">Save Changes</button>
        </form>
      </section>

        <!-- User Management -->
        <section class="settings-section">
          <div class="section-header">
            <h2>User Management</h2>
          </div>
          <div class="action-buttons">
              <button class="btn manage-btn"onclick="window.location.href='Users.php';"> View All Users</button>
              <button class="btn manage-btn" >Ban User</button>
          </div>
                    
          <div style="margin-top: 20px;">
            <h3>Quick Stats</h3>
            <div style="display: flex; gap: 15px; margin-top: 10px;">
              <div style="background: #f0f5ff; padding: 10px; border-radius: 8px; text-align: center; flex: 1;">
                <div style="font-weight: bold; color: #4361ee;">1,842</div>
                <div style="font-size: 0.8rem;">Active Users</div>
              </div>
              <div style="background: #fff0f6; padding: 10px; border-radius: 8px; text-align: center; flex: 1;">
                <div style="font-weight: bold; color: #f72585;">24</div>
                <div style="font-size: 0.8rem;">Banned Users</div>
              </div>
            </div>
          </div>
        </section>

        <!-- Database Management -->
        <section class="settings-section">
          <div class="section-header">
            <h2>Database Management</h2>
          </div>
          <div class="action-buttons">
            <button class="btn manage-btn"> Backup Database</button>
            <button class="btn manage-btn">Restore Database</button>
            <button class="btn manage-btn">Clear Cache</button>
          </div>
          <p class="note">Ensure you download backups before restoring. Last backup: Today, 11:42 AM</p>
                    
          <div style="margin-top: 20px;">
            <h3>Database Status</h3>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 10px;">
                  <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Usage</span>
                    <span>64%</span>
                  </div>
                  <div style="height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                    <div style="height: 100%; width: 64%; background: #4361ee;"></div>
                  </div>
                  <div style="font-size: 0.8rem; color: #6c757d; margin-top: 8px;">
                    325GB of 512GB used
                  </div>
                </div>
          </div>
        </section>

        <!-- Security Settings -->
        <section class="settings-section">
          <div class="section-header">
            <h2>Security</h2>
          </div>
          <div class="action-buttons">
            <button class="btn security-btn"onclick="window.location.href='forgotPassword.php';">Change Admin Password</button>
            <button class="btn security-btn">Enable 2FA</button>
            <button class="btn security-btn">View Access Logs</button>
          </div>
                    
          <div style="margin-top: 20px;">
            <h3>Security Status</h3>
            <ul style="list-style: none; margin-top: 10px;">
              <li style="padding: 8px 0; display: flex; align-items: center;">
                SSL Certificate (Valid until: Dec 15, 2023)
              </li>
              <li style="padding: 8px 0; display: flex; align-items: center;">
                Firewall Active
              </li>
              <li style="padding: 8px 0; display: flex; align-items: center;">
                2FA Not Enabled
              </li>
            </ul>
          </div>
        </section>

    </div>
  </div>
  </div>

  </body>
   
  </html>  