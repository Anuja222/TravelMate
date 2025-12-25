<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trip Details - TravelMate</title>
  <link rel="stylesheet" href="assets/css/Transpoter/tripDetails.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <div class="detail-container">
      <div class="detail-form">
        <h1 class="page-title">
          <i class="fas fa-clipboard-check"></i>
          Trip Details
        </h1>
        <p class="page-subtitle">Review details of your completed trip</p>
        
        <div id="tripDetails">
          <div class="form-section">
            <h2 class="section-title">
              <i class="fas fa-route"></i>
              <strong>Journey Details</strong> 
            </h2>

            <div class="form-row">
              <div class="form-group">
                <label>Pickup Location</label>
                <p>Colombo International Airport</p>
              </div>
          
              <div class="form-group">
                <label>Destination</label>
                <p>Galle Face Hotel, Colombo</p>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Date & Time</label>
                <p>2024-06-15 at 14:30</p>
              </div>
              
              <div class="form-group">
                <label>Passengers</label>
                <p>4</p>
              </div>
            </div>
            
            <div class="trip-map">
              <i class="fas fa-map-marked-alt"></i>
              <span>View Trip Route</span>
            </div>
          </div>        

          <div class="form-section">
            <h2 class="section-title">
              <i class="fas fa-car"></i>
              <strong>Vehicle Information</strong>
            </h2>
            
            <div class="vehicle-options">
              <div class="vehicle-option selected" data-type="van" data-price="40">
                <div class="vehicle-icon">
                  <i class="fas fa-shuttle-van"></i>
                </div>
                <div class="vehicle-name">Van</div>
                <div class="vehicle-price">$40/hr</div>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Fare Paid</label>
                <p>LKR 2,500</p>
              </div>
              
              <div class="form-group">
                <label>Payment Method</label>
                <p>Credit Card (****1234)</p>
              </div>
            </div>
            
            <div class="form-group">
              <label>Status</label>
              <div class="status-badge status-completed">Completed</div>
            </div>
          </div>
          
          <div class="form-section">
            <h2 class="section-title">
              <i class="fas fa-user"></i>
              Traveller Details
            </h2>
            
            <div class="form-row">
              <div class="form-group">
                <label>Customer Name</label>
                <p>John Smith</p>
              </div>
              
              <div class="form-group">
                <label>Email Address</label>
                <p>johnsmith@gmail.com</p>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Phone Number</label>
                <p>098753322</p>
              </div>
              
              <div class="form-group">
                <label>Special Requests</label>
                <p>Plan to travel with a 5 years old kid</p>
              </div>
            </div>
          </div>
          
          <div class="driver-info">
            <h4><i class="fas fa-id-card"></i> Driver Information</h4>
            <p><i class="fas fa-user"></i> <strong>Name:</strong> Robert Perera</p>
            <p><i class="fas fa-phone"></i> <strong>Contact:</strong> 077-1234567</p>
            <p><i class="fas fa-car"></i> <strong>Vehicle:</strong> Toyota Hiace (CAB-1234)</p>
            <div class="rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
              <span>4.5/5</span>
            </div>
          </div>
          
        </div>
      </div>
      
      <div class="booking-summary">
        <div class="summary-card">
          <h3><i class="fas fa-receipt"></i> Trip Summary</h3>
          <div class="summary-details">
            <div class="summary-item">
              <span>Distance:</span>
              <span>35 km</span>
            </div>
            <div class="summary-item">
              <span>Duration:</span>
              <span>1 hr 15 min</span>
            </div>
            <div class="summary-item">
              <span>Base Fare:</span>
              <span>LKR 1,800</span>
            </div>
            <div class="summary-item">
              <span>Waiting Time:</span>
              <span>LKR 200</span>
            </div>
            <div class="summary-item">
              <span>Service Fee:</span>
              <span>LKR 200</span>
            </div>
            <div class="summary-item">
              <span>Tax:</span>
              <span>LKR 300</span>
            </div>
            <div class="summary-item total">
              <span>Total Fare:</span>
              <span>LKR 2,500</span>
            </div>
          </div>
          
          <div class="status-badge status-completed" style="margin-top: 20px;">
            <i class="fas fa-check-circle"></i> Completed on Jun 15, 2024
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add click effect to buttons
      const buttons = document.querySelectorAll('.btn');
      buttons.forEach(button => {
        button.addEventListener('click', function() {
          this.style.transform = 'scale(0.98)';
          setTimeout(() => {
            this.style.transform = '';
          }, 150);
        });
      });
      
      // Simulate receipt download
      const downloadBtn = document.querySelector('.btn-secondary');
      downloadBtn.addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing...';
        setTimeout(() => {
          alert('Receipt downloaded successfully!');
          this.innerHTML = '<i class="fas fa-download"></i> Download Receipt';
        }, 1500);
      });
      
      // Simulate rating process
      const rateBtn = document.querySelector('.btn-primary');
      rateBtn.addEventListener('click', function() {
        alert('Redirecting to rating page...');
      });
      
      // Map interaction
      const mapElement = document.querySelector('.trip-map');
      mapElement.addEventListener('click', function() {
        alert('Showing detailed trip route map...');
      });
    });
  </script>
</body>
</html>