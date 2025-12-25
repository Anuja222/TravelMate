<!DOCTYPE html>
<html>
<head>
  <title>Vehicle Details - Audi A4</title>
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="assets/css/Admin/viewVehicle.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-container">

    <div class="content">
      <div class="detail-container">
        <div class="detail-header">
          <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Audi A4">
          <span class="detail-badge">Vehicle</span>
        </div>
        
        <div class="detail-content">
          <div class="detail-title">
            <h1>Audi A4 - 17AW</h1>
            <div class="rating">
              <span class="rating-value">4.8</span>
              <span>⭐</span>
            </div>
          </div>
          
          <div class="detail-info">
            <div class="info-section">
              <h3>Basic Information</h3>
              <div class="info-grid">
                <div class="info-item">
                  <span class="info-label">Owner</span>
                  <span class="info-value">Anuja Silva</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Location</span>
                  <span class="info-value">Kandy</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Contact</span>
                  <span class="info-value">+94 71 234 5678</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Email</span>
                  <span class="info-value">anuja.silva@example.com</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Added Date</span>
                  <span class="info-value">2024-02-03</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Status</span>
                  <span class="status active">Active</span>
                </div>
              </div>
            </div>
            
            <div class="info-section">
              <h3>Vehicle Details</h3>
              <div class="info-grid">
                <div class="info-item">
                  <span class="info-label">Vehicle Type</span>
                  <span class="info-value">Sedan</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Model</span>
                  <span class="info-value">Audi A4</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Year</span>
                  <span class="info-value">2022</span>
                </div>
                <div class="info-item">
                  <span class="info-label">License Plate</span>
                  <span class="info-value">17AW-1234</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Fuel Type</span>
                  <span class="info-value">Petrol</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Transmission</span>
                  <span class="info-value">Automatic</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Seating Capacity</span>
                  <span class="info-value">5</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Daily Rate</span>
                  <span class="info-value">$65/day</span>
                </div>
              </div>
              
              <h3 style="margin-top: 20px;">Features</h3>
              <div class="features">
                <span class="feature">Air Conditioning</span>
                <span class="feature">GPS Navigation</span>
                <span class="feature">Bluetooth</span>
                <span class="feature">Backup Camera</span>
                <span class="feature">Leather Seats</span>
                <span class="feature">Sunroof</span>
                <span class="feature">Keyless Entry</span>
              </div>
            </div>
          </div>
          
          <div class="info-section">
            <h3>Description</h3>
            <p>This well-maintained 2022 Audi A4 offers a premium driving experience with its luxurious interior, advanced technology features, and powerful yet efficient engine. Perfect for both city driving and longer trips, this vehicle provides comfort, style, and reliability. Available for daily rental with flexible pickup and drop-off options in Kandy.</p>
          </div>
          
          <div class="detail-actions">
            <button class="btn-back" onclick="window.location.href='ViewListing.php';">← Back to Listings</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function editContent() {
      alert('Edit functionality would open a form to update vehicle details');
      // Implement edit functionality
    }
    
    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>