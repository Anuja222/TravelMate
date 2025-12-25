<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accepted Trip Booking - TravelMate Provider</title>
  <link rel="stylesheet" href="assets/css/Transpoter/ViewDetailsAccepted.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <div id="alertBox" class="alert" role="alert"></div>
    
    <div class="detail-container">
      <div class="detail-form">
        <h1 class="page-title">Accepted Trip Booking</h1>
        <p class="page-subtitle">Manage your confirmed booking</p>
        
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
                
            <div class="form-group">
              <p><strong>Agreed Fare:</strong> <span id="agreed-fare">LKR 2,800</span></p>
            </div>
            
            <div class="confirmation-details">
              <div class="form-group">
                <p><strong>Confirmed On:</strong> <span id="confirmed-date">2024-06-10 at 09:45</span></p>
              </div>
              
              <div class="form-group">
                <p><strong>Your Note to Customer:</strong> <span id="provider-note-display">We'll provide a child seat as requested.</span></p>
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
          
          <div class="tracking-section">
            <h2 class="section-title">
              <i class="fas fa-map-marker-alt"></i>
              Booking Status
            </h2>
            
            <div class="tracking-status">
              <div class="tracking-step completed">
                <div class="step-icon">
                  <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="step-label">Confirmed</div>
              </div>
              
              <div class="tracking-step active">
                <div class="step-icon">
                  <i class="fas fa-user-check"></i>
                </div>
                <div class="step-label">Driver Assigned</div>
              </div>
              
              <div class="tracking-step">
                <div class="step-icon">
                  <i class="fas fa-car"></i>
                </div>
                <div class="step-label">On the Way</div>
              </div>
              
              <div class="tracking-step">
                <div class="step-icon">
                  <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="step-label">Completed</div>
              </div>
              
              <div class="tracking-progress">
                <div class="progress-bar" style="width: 50%"></div>
              </div>
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
          <span class="label">Your Total:</span>
          <span class="value" id="summary-total">$60.00</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Agreed Price:</span>
          <span class="value">LKR 2,800</span>
        </div>
        
        <div class="action-buttons">
          <button type="button" class="btn-complete" id="complete-booking">
            <i class="fas fa-check-circle"></i> Mark as Completed
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
      const completeBtn = document.getElementById('complete-booking');
      const modifyBtn = document.getElementById('modify-booking');
      
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
      
      // Complete booking
      completeBtn.addEventListener('click', function() {
        // Show loading state
        completeBtn.disabled = true;
        modifyBtn.disabled = true;
        completeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Simulate API call
        setTimeout(() => {
          showAlert('Booking marked as completed successfully! Payment processed.', 'success');
          
          // Update UI to show completed status
          document.querySelector('.tracking-step:nth-child(3)').classList.remove('active');
          document.querySelector('.tracking-step:nth-child(3)').classList.add('completed');
          document.querySelector('.tracking-step:nth-child(4)').classList.add('active');
          document.querySelector('.progress-bar').style.width = '100%';
          
          // Reset button after success
          setTimeout(() => {
            completeBtn.disabled = false;
            modifyBtn.disabled = false;
            completeBtn.innerHTML = '<i class="fas fa-check-circle"></i> Completed';
            completeBtn.disabled = true;
          }, 2000);
        }, 1500);
      });
      
      // Modify booking
      modifyBtn.addEventListener('click', function() {
        const newPrice = prompt('Enter new price (LKR):', '2800');
        
        if (newPrice === null) return; // User cancelled
          
        if (!newPrice.trim() || isNaN(newPrice)) {
          showAlert('Please enter a valid price', 'error');
          return;
        }
        
        // Show loading state
        completeBtn.disabled = true;
        modifyBtn.disabled = true;
        modifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        
        // Simulate API call
        setTimeout(() => {
          showAlert('Booking details updated successfully! Customer notified.', 'success');
          
          // Update UI with new price
          document.getElementById('agreed-fare').textContent = 'LKR ' + parseInt(newPrice).toLocaleString();
          document.querySelector('.summary-item:nth-last-child(2) .value').textContent = 'LKR ' + parseInt(newPrice).toLocaleString();
          
          // Reset button after success
          setTimeout(() => {
            completeBtn.disabled = false;
            modifyBtn.disabled = false;
            modifyBtn.innerHTML = '<i class="fas fa-edit"></i> Modify Details';
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