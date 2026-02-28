<!DOCTYPE html>
<html>
<head>
  <title>Hotel Details - Sunrise Resort</title>
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="assets/css/Admin/viewHotel.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-container">

    <div class="content">
      <div class="detail-container">
        <div class="detail-header">
          <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Sunrise Resort">
          <span class="detail-badge">Hotel</span>
        </div>
        
        <div class="detail-content">
          <div class="detail-title">
            <h1>Sunrise Resort</h1>
            <div class="rating">
              <span class="rating-value">4.5</span>
              <span>⭐</span>
            </div>
          </div>
          
          <div class="detail-info">
            <div class="info-section">
              <h3>Basic Information</h3>
              <div class="info-grid">
                <div class="info-item">
                  <span class="info-label">Owner</span>
                  <span class="info-value">Lakmal Perera</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Location</span>
                  <span class="info-value">Colombo</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Contact</span>
                  <span class="info-value">+94 77 123 4567</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Email</span>
                  <span class="info-value">info@sunriseresort.com</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Added Date</span>
                  <span class="info-value">2024-01-15</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Status</span>
                  <span class="status active">Active</span>
                </div>
              </div>
            </div>
            
            <div class="info-section">
              <h3>Hotel Details</h3>
              <div class="info-grid">
                <div class="info-item">
                  <span class="info-label">Room Types</span>
                  <span class="info-value">Standard, Deluxe, Suite</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Total Rooms</span>
                  <span class="info-value">45</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Price Range</span>
                  <span class="info-value">$80 - $250/night</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Check-in/out</span>
                  <span class="info-value">2:00 PM / 11:00 AM</span>
                </div>
              </div>
              
              <h3 style="margin-top: 20px;">Amenities</h3>
              <div class="amenities">
                <span class="amenity">Swimming Pool</span>
                <span class="amenity">Spa</span>
                <span class="amenity">Restaurant</span>
                <span class="amenity">Free WiFi</span>
                <span class="amenity">Parking</span>
                <span class="amenity">Fitness Center</span>
                <span class="amenity">Air Conditioning</span>
              </div>
            </div>
          </div>
          
          <div class="info-section">
            <h3>Description</h3>
            <p>Sunrise Resort is a luxurious beachfront property located in the heart of Colombo. With stunning ocean views, modern amenities, and exceptional service, we provide an unforgettable experience for both business and leisure travelers. Our resort features 45 elegantly designed rooms, a swimming pool, spa, and multiple dining options.</p>
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
      alert('Edit functionality would open a form to update hotel details');
      // Implement edit functionality
    }
    
    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>