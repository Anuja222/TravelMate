<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Confirmed - TravelMate Provider</title>
  <link rel="stylesheet" href="assets/css/Transpoter/confirmBooking.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <div id="alertBox" class="alert" role="alert"></div>
    
    <div class="detail-container">
      <div class="detail-form">
        <h1 class="page-title">Booking Confirmed</h1>
        <p class="page-subtitle">Your booking has been successfully confirmed</p>
        
        <div class="request-header">
          <div class="request-id">Request #: <span>TR-2024-7892</span></div>
          <div class="request-status confirmed">Status: <span>Confirmed</span></div>
        </div>
        
        <form id="detailForm">
          <div class="form-section">
            <h2 class="section-title">
              <i class="fas fa-route"></i>
              Journey Details
            </h2>

            <div class="detail-info">
              <div class="form-group">
                <p><strong>Pickup:</strong> <span id="pickup-location">Colombo International Airport</span></p>
              </div>
          
              <div class="form-group">
                <p><strong>Destination:</strong> <span id="destination">Galle Face Hotel, Colombo</span></p>
              </div>
          
              <div class="form-group">
                <p><strong>Date & Time:</strong> <span id="datetime">2024-06-15 at 14:30</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Passengers:</strong> <span id="passengers">4</span> (2 Adults, 2 Children)</p>
              </div>
              
              <div class="form-group">
                <p><strong>Distance:</strong> <span id="distance">35 km</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Estimated Duration:</strong> <span id="duration">1 hour 15 minutes</span></p>
              </div>
            </div>

            <div class="form-group">
              <label for="vehicle-type"><strong>Confirmed Vehicle Type</strong></label>
              <div class="vehicle-options">
                <div class="vehicle-option confirmed" data-type="van" data-price="40">
                  <div class="vehicle-icon">
                    <i class="fas fa-shuttle-van"></i>
                  </div>
                  <div class="vehicle-name">Van</div>
                  <div class="vehicle-price">$40/hr</div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="form-section">
            <h2 class="section-title">
              <i class="fas fa-credit-card"></i>
              Payment Details
            </h2>
            
            <div class="detail-info">
              <div class="form-group">
                <p><strong>Payment Method:</strong> <span id="payment-method">Credit Card (Visa ending in 4567)</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Payment Status:</strong> <span class="payment-status confirmed">Confirmed</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Transaction ID:</strong> <span id="transaction-id">TXN-789456123</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Payment Date:</strong> <span id="payment-date">2024-06-10 at 10:15 AM</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Amount Paid:</strong> <span id="amount-paid">LKR 2,800</span></p>
              </div>
            </div>
          </div>
          
          <div class="form-section">
            <h2 class="section-title">
              <i class="fas fa-user"></i>
              Traveller Details
            </h2>
            
            <div class="detail-info">
              <div class="form-group">
                <p><strong>Customer:</strong> <span id="customer-name">John Smith</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Email Address:</strong> <span id="customer-email">johnsmith@gmail.com</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Phone Number:</strong> <span id="customer-phone">098753322</span></p>
              </div>
            
              <div class="form-group">
                <p><strong>Special Note from Customer:</strong> <span id="special-note">Plan to travel with a 5 years old kid. Need child seat if available.</span></p>
              </div>
            </div>
          </div>
          
          <div class="confirmation-details">
            <div class="form-group">
              <p><strong>Confirmed On:</strong> <span id="confirmed-date">2024-06-10 at 09:45</span></p>
            </div>
            
            <div class="form-group">
              <p><strong>Your Note to Customer:</strong> <span id="provider-note-display">We'll provide a child seat as requested.</span></p>
            </div>
          </div>
        </form>
      </div>
      
      <div class="detail-summary">
        <h3 class="summary-title">Booking Summary</h3>
        
        <div class="summary-item">
          <span class="label">Request ID:</span>
          <span class="value">TR-2024-7892</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Vehicle Type:</span>
          <span class="value" id="summary-vehicle">Van</span>
        </div>
        
        <div class="summary-item">
          <span class="label">From:</span>
          <span class="value" id="summary-from">Colombo International Airport</span>
        </div>
        
        <div class="summary-item">
          <span class="label">To:</span>
          <span class="value" id="summary-to">Galle Face Hotel, Colombo</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Date & Time:</span>
          <span class="value" id="summary-datetime">2024-06-15 at 14:30</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Passengers:</span>
          <span class="value" id="summary-passengers">4</span>
        </div>
        
        <div class="summary-divider"></div>
        
        <div class="summary-item">
          <span class="label">Your Rate:</span>
          <span class="value">$40/hr</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Estimated Time:</span>
          <span class="value">1.25 hours</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Base Fare:</span>
          <span class="value" id="summary-basefare">$50.00</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Child Seat:</span>
          <span class="value">$5.00</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Taxes & Fees:</span>
          <span class="value">$5.00</span>
        </div>
        
        <div class="summary-divider"></div>
        
        <div class="summary-total">
          <span class="label">Total Amount:</span>
          <span class="value" id="summary-total">$60.00</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Agreed Price:</span>
          <span class="value">LKR 2,800</span>
        </div>
        
        <div class="confirmation-badge">
          <i class="fas fa-check-circle"></i>
          <span>Booking Confirmed</span>
        </div>
        
        <div class="action-buttons">
          <button type="button" class="btn-print" id="print-invoice">
            <i class="fas fa-print"></i> Print Invoice
          </button>
          <button type="button" class="btn-download" id="download-receipt">
            <i class="fas fa-download"></i> Download Receipt
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Get DOM elements
      const alertBox = document.getElementById('alertBox');
      const printBtn = document.getElementById('print-invoice');
      const downloadBtn = document.getElementById('download-receipt');
      
      // Show alert function
      function showAlert(message, type) {
        alertBox.textContent = message;
        alertBox.className = `alert alert-${type}`;
        alertBox.style.display = 'block';
        
        // Hide alert after 5 seconds
        setTimeout(() => {
          alertBox.style.display = 'none';
        }, 5000);
      }
      
      // Print invoice
      printBtn.addEventListener('click', function() {
        // Show loading state
        printBtn.disabled = true;
        printBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing...';
        
        // Simulate processing
        setTimeout(() => {
          window.print();
          
          // Reset button after success
          setTimeout(() => {
            printBtn.disabled = false;
            printBtn.innerHTML = '<i class="fas fa-print"></i> Print Invoice';
          }, 1000);
        }, 1500);
      });
      
      // Download receipt
      downloadBtn.addEventListener('click', function() {
        // Show loading state
        downloadBtn.disabled = true;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
        
        // Simulate download
        setTimeout(() => {
          showAlert('Receipt downloaded successfully!', 'success');
          
          // Reset button after success
          setTimeout(() => {
            downloadBtn.disabled = false;
            downloadBtn.innerHTML = '<i class="fas fa-download"></i> Download Receipt';
          }, 2000);
        }, 1500);
      });
      
      // Responsive adjustments
      function handleResize() {
        if (window.innerWidth < 900) {
          document.querySelector('.detail-summary').style.position = 'relative';
        } else {
          document.querySelector('.detail-summary').style.position = 'sticky';
        }
      }
      
      // Listen for window resize
      window.addEventListener('resize', handleResize);
      
      // Initial call
      handleResize();
    });
  </script>
</body>
</html>