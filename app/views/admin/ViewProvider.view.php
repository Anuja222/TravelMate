<!DOCTYPE html>
<html>
<head>
  <title>Provider Profile - Lakmal Perera</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/ViewProvider.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

  <div class="page-container">

    <div class="content">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="profile-banner"></div>
        <div class="profile-info">
          <div class="profile-avatar">
            <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="Lakmal Perera">
          </div>
          <div class="profile-details">
            <div class="profile-name">
              <h1>Lakmal Perera</h1>
              <span class="user-type">Service Provider</span>
            </div>
            <p style="color: #666; margin: 5px 0;">Hotel & Accommodation Provider</p>
            <div class="profile-stats">
              <div class="stat">
                <span class="stat-value">3</span>
                <span class="stat-label">Listings</span>
              </div>
              <div class="stat">
                <span class="stat-value">4.5</span>
                <span class="stat-label">Avg Rating</span>
              </div>
              <div class="stat">
                <span class="stat-value">47</span>
                <span class="stat-label">Bookings</span>
              </div>
              <div class="stat">
                <span class="stat-value">2</span>
                <span class="stat-label">Years</span>
              </div>
            </div>
            <div class="profile-actions">
              <button class="btn-suspend" onclick="suspendUser()">⏸️ Suspend Account</button>
              <button class="btn-back" onclick="window.location.href='Users';">← Back to Users</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="profile-tabs">
        <div class="tabs-header">
          <button class="tab active" onclick="switchTab('overview')">Overview</button>
          <button class="tab" onclick="switchTab('listings')">Listings (3)</button>
          <button class="tab" onclick="switchTab('activities')">Activities</button>
          <button class="tab" onclick="switchTab('reviews')">Reviews</button>
        </div>

        <!-- Overview Tab -->
        <div id="overview" class="tab-content active">
          <div class="info-grid">
            <div class="info-card">
              <h3>Contact Information</h3>
              <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">lakmal.perera@email.com</span>
              </div>
              <div class="info-item">
                <span class="info-label">Phone</span>
                <span class="info-value">+94 77 123 4567</span>
              </div>
              <div class="info-item">
                <span class="info-label">Location</span>
                <span class="info-value">Colombo, Sri Lanka</span>
              </div>
              <div class="info-item">
                <span class="info-label">Join Date</span>
                <span class="info-value">January 15, 2024</span>
              </div>
            </div>

            <div class="info-card">
              <h3>Account Status</h3>
              <div class="info-item">
                <span class="info-label">Status</span>
                <span class="info-value" style="color: #155724; background: #d4edda; padding: 4px 12px; border-radius: 12px; font-size: 0.8rem;">Active</span>
              </div>
              <div class="info-item">
                <span class="info-label">Verification</span>
                <span class="info-value" style="color: #155724; background: #d4edda; padding: 4px 12px; border-radius: 12px; font-size: 0.8rem;">Verified</span>
              </div>
              <div class="info-item">
                <span class="info-label">Last Login</span>
                <span class="info-value">2 hours ago</span>
              </div>
              <div class="info-item">
                <span class="info-label">Member Since</span>
                <span class="info-value">2 years</span>
              </div>
            </div>

            <div class="info-card">
              <h3>Business Information</h3>
              <div class="info-item">
                <span class="info-label">Business Name</span>
                <span class="info-value">Sunrise Hospitality Group</span>
              </div>
              <div class="info-item">
                <span class="info-label">Tax ID</span>
                <span class="info-value">123-456-789</span>
              </div>
              <div class="info-item">
                <span class="info-label">Business Type</span>
                <span class="info-value">Hotel & Accommodation</span>
              </div>
              <div class="info-item">
                <span class="info-label">Service Areas</span>
                <span class="info-value">Colombo, Galle, Kandy</span>
              </div>
            </div>

            <div class="info-card">
              <h3>Performance Metrics</h3>
              <div class="info-item">
                <span class="info-label">Response Rate</span>
                <span class="info-value">98%</span>
              </div>
              <div class="info-item">
                <span class="info-label">Booking Rate</span>
                <span class="info-value">85%</span>
              </div>
              <div class="info-item">
                <span class="info-label">Repeat Customers</span>
                <span class="info-value">42%</span>
              </div>
              <div class="info-item">
                <span class="info-label">Avg Response Time</span>
                <span class="info-value">15 minutes</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Listings Tab -->
        <div id="listings" class="tab-content">
          <div class="listings-grid">
            <!-- Listing 1 -->
            <div class="listing-card">
              <div class="listing-image">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Sunrise Resort">
              </div>
              <div class="listing-info">
                <div class="listing-header">
                  <h4>Sunrise Resort</h4>
                  <span class="listing-type">Hotel</span>
                </div>
                <div class="listing-details">
                  <strong>Location:</strong> Colombo<br>
                  <strong>Price:</strong> $120-250/night
                </div>
                <div class="listing-stats">
                  <span>⭐ 4.5 (47 reviews)</span>
                  <span>🔍 1.2k views</span>
                </div>
              </div>
            </div>

            <!-- Listing 2 -->
            <div class="listing-card">
              <div class="listing-image">
                <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Ocean View Villa">
              </div>
              <div class="listing-info">
                <div class="listing-header">
                  <h4>Ocean View Villa</h4>
                  <span class="listing-type">Villa</span>
                </div>
                <div class="listing-details">
                  <strong>Location:</strong> Galle<br>
                  <strong>Price:</strong> $200-400/night
                </div>
                <div class="listing-stats">
                  <span>⭐ 4.8 (23 reviews)</span>
                  <span>🔍 890 views</span>
                </div>
              </div>
            </div>

            <!-- Listing 3 -->
            <div class="listing-card">
              <div class="listing-image">
                <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="City Center Apartment">
              </div>
              <div class="listing-info">
                <div class="listing-header">
                  <h4>City Center Apartment</h4>
                  <span class="listing-type">Apartment</span>
                </div>
                <div class="listing-details">
                  <strong>Location:</strong> Colombo<br>
                  <strong>Price:</strong> $80-150/night
                </div>
                <div class="listing-stats">
                  <span>⭐ 4.3 (15 reviews)</span>
                  <span>🔍 560 views</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Activities Tab -->
        <div id="activities" class="tab-content">
          <div class="activities-list">
            <div class="activity-item">
              <div class="activity-icon">✓</div>
              <div class="activity-content">
                <div class="activity-title">New Booking Received</div>
                <div class="activity-desc">Booking #BKG-7842 for Sunrise Resort (3 nights)</div>
                <div class="activity-time">2 hours ago</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">⭐</div>
              <div class="activity-content">
                <div class="activity-title">New Review Added</div>
                <div class="activity-desc">5-star review for Ocean View Villa</div>
                <div class="activity-time">1 day ago</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">📸</div>
              <div class="activity-content">
                <div class="activity-title">Photos Updated</div>
                <div class="activity-desc">Added new photos to City Center Apartment</div>
                <div class="activity-time">2 days ago</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">💰</div>
              <div class="activity-content">
                <div class="activity-title">Payout Processed</div>
                <div class="activity-desc">Monthly earnings payout of $2,450 processed</div>
                <div class="activity-time">3 days ago</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">✏️</div>
              <div class="activity-content">
                <div class="activity-title">Profile Updated</div>
                <div class="activity-desc">Updated business information and contact details</div>
                <div class="activity-time">1 week ago</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Reviews Tab -->
        <div id="reviews" class="tab-content">
          <div style="text-align: center; padding: 40px; color: #666;">
            <h3>Reviews & Ratings</h3>
            <p>Review management interface would be displayed here</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function switchTab(tabName) {
      // Hide all tab contents
      document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
      });
      
      // Remove active class from all tabs
      document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
      });
      
      // Show selected tab content
      document.getElementById(tabName).classList.add('active');
      
      // Add active class to clicked tab
      event.target.classList.add('active');
    }

    function editProfile() {
      alert('Edit profile functionality would open here');
    }

    function suspendUser() {
      if (confirm('Suspend this provider account?')) {
        alert('Account suspension functionality would execute here');
      }
    }

    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>
