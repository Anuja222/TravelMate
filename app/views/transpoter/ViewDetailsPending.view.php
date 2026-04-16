<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trip Booking Request - TravelMate Provider</title>
  <link rel="stylesheet" href="assets/css/Transpoter/ViewDetailsPending.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <div id="alertBox" class="alert" role="alert"></div>
    
    <div class="detail-container">
      <div class="detail-form">
        <h1 class="page-title">Trip Booking Request</h1>
        <p class="page-subtitle">Review booking details and confirm or reject this request</p>
        
        <div class="request-header">
          <div class="request-id">Request #: <span>TR-2024-7892</span></div>
          <div class="request-status pending">Status: <span>Pending</span></div>
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
              <label for="vehicle-type"><strong>Requested Vehicle Type</strong></label>
              <div class="vehicle-options">
                <div class="vehicle-option selected" data-type="van" data-price="40">
                  <div class="vehicle-icon">
                    <i class="fas fa-shuttle-van"></i>
                  </div>
                  <div class="vehicle-name">Van</div>
                  <div class="vehicle-price">$40/hr</div>
                </div>
              </div>
            </div>
                
            <div class="form-group">
              <p><strong>Customer Estimated Fare:</strong> <span id="estimated-fare">LKR 2,500</span></p>
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
            
            <div class="form-group">
              <label for="provider-note">Your Response to Customer (Optional)</label>
              <textarea id="provider-note" placeholder="Add any message for the customer..."></textarea>
            </div>
            
            <div class="form-group">
              <label for="counter-offer">Counter Offer Price (LKR)</label>
              <input type="number" id="counter-offer" placeholder="Enter your price offer" value="2500">
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
          <span class="label">Taxes & Fees:</span>
          <span class="value">$5.00</span>
        </div>
        
        <div class="summary-divider"></div>
        
        <div class="summary-total">
          <span class="label">Your Total:</span>
          <span class="value" id="summary-total">$55.00</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Customer Offer:</span>
          <span class="value">LKR 2,500</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Your Counter:</span>
          <span class="value">LKR <span id="counter-display">2,500</span></span>
        </div>
        
        <div class="action-buttons">
          <button type="button" class="btn-confirm" id="confirm-booking" onclick="window.location.href='confirmBooking';">
            <i class="fas fa-check-circle"></i> Confirm Booking
          </button>
          
          <button type="button" class="btn-reject" id="reject-booking">
            <i class="fas fa-times-circle"></i> Reject Request
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // get DOM elements
      const alertBox = document.getElementById('alertBox');
      const confirmBtn = document.getElementById('confirm-booking');
      const rejectBtn = document.getElementById('reject-booking');
      const counterOffer = document.getElementById('counter-offer');
      const counterDisplay = document.getElementById('counter-display');
      
      // format counter offer value as user types
      counterOffer.addEventListener('input', function() {
        const value = this.value ? parseInt(this.value).toLocaleString() : '';
        counterDisplay.textContent = value;
        updateSummary();
      });
      
      // show alert function
      function showAlert(message, type) {
        alertBox.textContent = message;
        alertBox.className = `alert alert-${type}`;
        alertBox.style.display = 'block';
        
        // hide alert after 5 seconds
        setTimeout(() => {
          alertBox.style.display = 'none';
        }, 5000);
      }
      
      // update summary
      function updateSummary() {
        // in a real app, this would calculate based on distance, vehicle type, etc.
        const baseFare = 50; // this would normally be calculated
        document.getElementById('summary-basefare').textContent = `$${baseFare}.00`;
        
        // calculate total (base fare + $5 fees)
        const total = baseFare + 5;
        document.getElementById('summary-total').textContent = `$${total}.00`;
      }
      
      // confirm booking
      confirmBtn.addEventListener('click', function() {
        const providerNote = document.getElementById('provider-note').value;
        const counterPrice = document.getElementById('counter-offer').value;
        
        if (!counterPrice) {
          showAlert('Please enter a counter offer price', 'error');
          return;
        }
        
        // show loading state
        confirmBtn.disabled = true;
        rejectBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // simulate API call
        setTimeout(() => {
          showAlert('Booking confirmed successfully! The customer has been notified.', 'success');
          
          // update UI to show confirmed status
          document.querySelector('.request-status').innerHTML = 'Status: <span>Confirmed</span>';
          document.querySelector('.request-status').classList.remove('pending');
          document.querySelector('.request-status').classList.add('confirmed');
          
          // reset button after success
          setTimeout(() => {
            confirmBtn.disabled = false;
            rejectBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-check-circle"></i> Confirmed';
          }, 2000);
        }, 1500);
      });
      
      // reject booking
      rejectBtn.addEventListener('click', function() {
        const rejectReason = prompt('Please provide a reason for rejecting this booking:');
        
        if (rejectReason === null) return; // user cancelled
          
        if (!rejectReason.trim()) {
          showAlert('Please provide a reason for rejection', 'error');
          return;
        }
        
        // show loading state
        confirmBtn.disabled = true;
        rejectBtn.disabled = true;
        rejectBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // simulate API call
        setTimeout(() => {
          showAlert('Booking request rejected. The customer has been notified.', 'success');
          
          // update UI to show rejected status
          document.querySelector('.request-status').innerHTML = 'Status: <span>Rejected</span>';
          document.querySelector('.request-status').classList.remove('pending');
          document.querySelector('.request-status').classList.add('rejected');
          
          // reset button after success
          setTimeout(() => {
            confirmBtn.disabled = false;
            rejectBtn.disabled = false;
            rejectBtn.innerHTML = '<i class="fas fa-times-circle"></i> Rejected';
          }, 2000);
        }, 1500);
      });
      
      // initialize summary
      updateSummary();
      
      // responsive adjustments
      function handleResize() {
        if (window.innerWidth < 900) {
          document.querySelector('.detail-summary').style.position = 'relative';
        } else {
          document.querySelector('.detail-summary').style.position = 'sticky';
        }
      }
      
      // listen for window resize
      window.addEventListener('resize', handleResize);
      
      // initial call
      handleResize();
    });
  </script>
</body>
</html>