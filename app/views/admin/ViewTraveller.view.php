<!DOCTYPE html>
<html>
<head>
  <title>Traveler Profile - Saman Wijeratne</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/ViewTraveller.css?v=<?= time() ?>">
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
            <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="Saman Wijeratne">
          </div>
          <div class="profile-details">
            <div class="profile-name">
              <h1>Saman Wijeratne</h1>
              <span class="user-type">Traveler</span>
            </div>
            <p style="color: #666; margin: 5px 0;">Frequent traveler exploring Sri Lanka</p>
            <div class="profile-stats">
              <div class="stat">
                <span class="stat-value">12</span>
                <span class="stat-label">Trips</span>
              </div>
              <div class="stat">
                <span class="stat-value">8</span>
                <span class="stat-label">Reviews</span>
              </div>
              <div class="stat">
                <span class="stat-value">2</span>
                <span class="stat-label">Years</span>
              </div>
              <div class="stat">
                <span class="stat-value">4.7</span>
                <span class="stat-label">Avg Rating</span>
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
          <button class="tab" onclick="switchTab('bookings')">Bookings (5)</button>
          <button class="tab" onclick="switchTab('activities')">Activities</button>
          <button class="tab" onclick="switchTab('preferences')">Preferences</button>
        </div>

        <!-- Overview Tab -->
        <div id="overview" class="tab-content active">
          <div class="info-grid">
            <div class="info-card">
              <h3>Personal Information</h3>
              <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">saman.w@email.com</span>
              </div>
              <div class="info-item">
                <span class="info-label">Phone</span>
                <span class="info-value">+94 76 456 7890</span>
              </div>
              <div class="info-item">
                <span class="info-label">Location</span>
                <span class="info-value">Kandy, Sri Lanka</span>
              </div>
              <div class="info-item">
                <span class="info-label">Join Date</span>
                <span class="info-value">January 20, 2024</span>
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
                <span class="info-value">5 hours ago</span>
              </div>
              <div class="info-item">
                <span class="info-label">Member Since</span>
                <span class="info-value">2 years</span>
              </div>
            </div>

            <div class="info-card">
              <h3>Travel Preferences</h3>
              <div class="info-item">
                <span class="info-label">Travel Style</span>
                <span class="info-value">Adventure & Cultural</span>
              </div>
              <div class="info-item">
                <span class="info-label">Group Size</span>
                <span class="info-value">Solo & Small Groups</span>
              </div>
              <div class="info-item">
                <span class="info-label">Budget Range</span>
                <span class="info-value">$$ Medium</span>
              </div>
              <div class="info-item">
                <span class="info-label">Favorite Regions</span>
                <span class="info-value">Hill Country, Beaches</span>
              </div>
            </div>

            <div class="info-card">
              <h3>Travel Statistics</h3>
              <div class="info-item">
                <span class="info-label">Total Trips</span>
                <span class="info-value">12</span>
              </div>
              <div class="info-item">
                <span class="info-label">Countries Visited</span>
                <span class="info-value">3</span>
              </div>
              <div class="info-item">
                <span class="info-label">Cities Explored</span>
                <span class="info-value">8</span>
              </div>
              <div class="info-item">
                <span class="info-label">Reviews Written</span>
                <span class="info-value">8</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Bookings Tab -->
        <div id="bookings" class="tab-content">
          <div class="bookings-grid">
            <!-- Booking 1 -->
            <div class="booking-card">
              <div class="booking-header">
                <h4>Sunrise Resort - Colombo</h4>
              </div>
              <div class="booking-details">
                <div class="booking-info">
                  <div class="info-item">
                    <span class="info-label">Check-in</span>
                    <span class="info-value">Mar 15, 2024</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Check-out</span>
                    <span class="info-value">Mar 18, 2024</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Guests</span>
                    <span class="info-value">2 Adults</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Total</span>
                    <span class="info-value">$360</span>
                  </div>
                </div>
                <div class="booking-status status-completed">Completed</div>
              </div>
            </div>

            <!-- Booking 2 -->
            <div class="booking-card">
              <div class="booking-header">
                <h4>Audi A4 - 17AW</h4>
              </div>
              <div class="booking-details">
                <div class="booking-info">
                  <div class="info-item">
                    <span class="info-label">Pick-up</span>
                    <span class="info-value">Apr 2, 2024</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Drop-off</span>
                    <span class="info-value">Apr 5, 2024</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Days</span>
                    <span class="info-value">3 Days</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Total</span>
                    <span class="info-value">$195</span>
                  </div>
                </div>
                <div class="booking-status status-confirmed">Confirmed</div>
              </div>
            </div>

            <!-- Booking 3 -->
            <div class="booking-card">
              <div class="booking-header">
                <h4>Ella Rock Guided Tour</h4>
              </div>
              <div class="booking-details">
                <div class="booking-info">
                  <div class="info-item">
                    <span class="info-label">Date</span>
                    <span class="info-value">Apr 10, 2024</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Duration</span>
                    <span class="info-value">6 Hours</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Participants</span>
                    <span class="info-value">1 Person</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">Total</span>
                    <span class="info-value">$45</span>
                  </div>
                </div>
                <div class="booking-status status-pending">Pending</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Activities Tab -->
        <div id="activities" class="tab-content">
          <div class="activities-list">
            <div class="activity-item">
              <div class="activity-icon">📖</div>
              <div class="activity-content">
                <div class="activity-title">Review Submitted</div>
                <div class="activity-desc">Posted a 5-star review for Sunrise Resort</div>
                <div class="activity-time">1 day ago</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">💰</div>
              <div class="activity-content">
                <div class="activity-title">Payment Processed</div>
                <div class="activity-desc">Payment of $195 for Audi A4 rental</div>
                <div class="activity-time">2 days ago</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">📅</div>
              <div class="activity-content">
                <div class="activity-title">New Booking Created</div>
                <div class="activity-desc">Booked Ella Rock Guided Tour for April 10</div>
                <div class="activity-time">3 days ago</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">⭐</div>
              <div class="activity-content">
                <div class="activity-title">Profile Updated</div>
                <div class="activity-desc">Updated travel preferences and interests</div>
                <div class="activity-time">1 week ago</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">🔍</div>
              <div class="activity-content">
                <div class="activity-title">Search Activity</div>
                <div class="activity-desc">Searched for beach resorts in Galle</div>
                <div class="activity-time">1 week ago</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Preferences Tab -->
        <div id="preferences" class="tab-content">
          <div class="info-grid">
            <div class="info-card">
              <h3>Accommodation Preferences</h3>
              <div class="info-item">
                <span class="info-label">Preferred Type</span>
                <span class="info-value">Hotels & Guest Houses</span>
              </div>
              <div class="info-item">
                <span class="info-label">Amenities</span>
                <span class="info-value">WiFi, Parking, Breakfast</span>
              </div>
              <div class="info-item">
                <span class="info-label">Budget Range</span>
                <span class="info-value">$50-150 per night</span>
              </div>
            </div>

            <div class="info-card">
              <h3>Transportation Preferences</h3>
              <div class="info-item">
                <span class="info-label">Vehicle Type</span>
                <span class="info-value">Sedan, SUV</span>
              </div>
              <div class="info-item">
                <span class="info-label">Transmission</span>
                <span class="info-value">Automatic Preferred</span>
              </div>
              <div class="info-item">
                <span class="info-label">Features</span>
                <span class="info-value">AC, GPS, Bluetooth</span>
              </div>
            </div>

            <div class="info-card">
              <h3>Travel Interests</h3>
              <div class="info-item">
                <span class="info-label">Activities</span>
                <span class="info-value">Hiking, Cultural Sites, Beaches</span>
              </div>
              <div class="info-item">
                <span class="info-label">Dining</span>
                <span class="info-value">Local Cuisine, Vegetarian Options</span>
              </div>
              <div class="info-item">
                <span class="info-label">Pace</span>
                <span class="info-value">Moderate</span>
              </div>
            </div>
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
      if (confirm('Suspend this traveler account?')) {
        alert('Account suspension functionality would execute here');
      }
    }

    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>
