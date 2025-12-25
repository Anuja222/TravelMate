<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Details - TravelMate Provider</title>
  <link rel="stylesheet" href="assets/css/Transpoter/paymentDetails.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="container">
    <h1 class="page-title">Payment Details</h1>
    <p class="page-subtitle">View complete payment information for this booking</p>
    
    <div class="booking-container">
      <div class="payment-form-container">
        <div class="form-section">
          <h3 class="section-title">
            <i class="fas fa-money-bill-wave"></i>
            Payment Information
          </h3>
          
          <div class="payment-status-badge">
            <span class="status status-completed">Completed</span>
            <span class="payment-date">Received on: Oct 15, 2023 at 14:30</span>
          </div>
          
          <div class="payment-info-grid">
            <div class="info-item">
              <span class="info-label">Payment Method:</span>
              <span class="info-value">Credit Card (Visa)</span>
            </div>
            <div class="info-item">
              <span class="info-label">Transaction ID:</span>
              <span class="info-value">TXN-789456123</span>
            </div>
            <div class="info-item">
              <span class="info-label">Card Number:</span>
              <span class="info-value">**** **** **** 3456</span>
            </div>
            <div class="info-item">
              <span class="info-label">Cardholder Name:</span>
              <span class="info-value">John Smith</span>
            </div>
            <div class="info-item">
              <span class="info-label">Authorization Code:</span>
              <span class="info-value">AUTH-789123</span>
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <h3 class="section-title">
            <i class="fas fa-receipt"></i>
            Payment Breakdown
          </h3>
          
          <div class="payment-breakdown">
            <div class="breakdown-item">
              <span class="breakdown-label">Base Fare:</span>
              <span class="breakdown-value">$60.00</span>
            </div>
            <div class="breakdown-item">
              <span class="breakdown-label">Distance Charge:</span>
              <span class="breakdown-value">$45.00</span>
            </div>
            <div class="breakdown-item">
              <span class="breakdown-label">Waiting Time:</span>
              <span class="breakdown-value">$10.00</span>
            </div>
            <div class="breakdown-item">
              <span class="breakdown-label">Taxes & Fees:</span>
              <span class="breakdown-value">$10.00</span>
            </div>
            <div class="breakdown-item breakdown-total">
              <span class="breakdown-label">Total Amount:</span>
              <span class="breakdown-value">$125.00</span>
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <h3 class="section-title">
            <i class="fas fa-file-invoice-dollar"></i>
            Invoice Details
          </h3>
          
          <div class="invoice-info">
            <div class="info-item">
              <span class="info-label">Invoice Number:</span>
              <span class="info-value">INV-20231015-789</span>
            </div>
            <div class="info-item">
              <span class="info-label">Issue Date:</span>
              <span class="info-value">October 15, 2023</span>
            </div>
            <div class="info-item">
              <span class="info-label">Due Date:</span>
              <span class="info-value">October 15, 2023</span>
            </div>
            <div class="info-item">
              <span class="info-label">Status:</span>
              <span class="info-value status-paid">Paid</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="booking-summary">
        <h3 class="summary-title">Booking Summary</h3>
        
        <div class="summary-item">
          <span class="label">Booking ID:</span>
          <span class="value" id="summary-bookingid">BK-789456</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Traveller Name:</span>
          <span class="value" id="summary-name">John Smith</span>
        </div>

        <div class="summary-item">
          <span class="label">Phone number:</span>
          <span class="value" id="summary-phone">098876337</span>
        </div>

        <div class="summary-item">
          <span class="label">Email Address:</span>
          <span class="value" id="summary-email">johnsmith@gmail.com</span>
        </div>

        <div class="summary-item">
          <span class="label">Vehicle Type:</span>
          <span class="value" id="summary-vehicle">Van</span>
        </div>
        
        <div class="summary-item">
          <span class="label">From:</span>
          <span class="value" id="summary-from">Colombo</span>
        </div>
        
        <div class="summary-item">
          <span class="label">To:</span>
          <span class="value" id="summary-to">Kandy</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Date & Time:</span>
          <span class="value" id="summary-datetime">2023-10-15 at 14:30</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Passengers:</span>
          <span class="value" id="summary-passengers">4</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Driver:</span>
          <span class="value" id="summary-driver">Robert Johnson</span>
        </div>
        
        <div class="summary-divider"></div>
        
        <div class="summary-item">
          <span class="label">Distance:</span>
          <span class="value">122 km</span>
        </div>
        
        <div class="summary-item">
          <span class="label">Travel Time:</span>
          <span class="value">2 hours 15 mins</span>
        </div>
        
        <div class="summary-divider"></div>
        
        <div class="summary-total">
          <span class="label">Total Amount:</span>
          <span class="value" id="summary-total">$125.00</span>
        </div>
        
        <div class="action-buttons">
          <button class="btn-primary" id="print-btn">
            <i class="fas fa-download"></i>
            Download Receipt
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Print receipt functionality
      const printBtn = document.getElementById('print-btn');
      
      printBtn.addEventListener('click', function() {
        window.print();
      });
      
      // Back button functionality
      const backBtn = document.getElementById('back-btn');
      
      backBtn.addEventListener('click', function() {
        window.history.back();
      });
      
      // Download invoice functionality
      const downloadInvoiceBtn = document.getElementById('download-invoice');
      
      downloadInvoiceBtn.addEventListener('click', function() {
        // Simulate download process
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
        
        setTimeout(() => {
          alert('Invoice downloaded successfully!');
          this.innerHTML = '<i class="fas fa-download"></i> Download Invoice';
        }, 1500);
      });
      
      // Send invoice functionality
      const sendInvoiceBtn = document.getElementById('send-invoice');
      
      sendInvoiceBtn.addEventListener('click', function() {
        // Simulate sending process
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        setTimeout(() => {
          alert('Invoice sent to johnsmith@gmail.com successfully!');
          this.innerHTML = '<i class="fas fa-paper-plane"></i> Send to Email';
        }, 1500);
      });
      
      // Responsive adjustments
      function handleResponsive() {
        const summaryElement = document.querySelector('.booking-summary');
        
        if (window.innerWidth < 768) {
          summaryElement.style.position = 'relative';
          summaryElement.style.top = '0';
        } else {
          summaryElement.style.position = 'sticky';
          summaryElement.style.top = '20px';
        }
      }
      
      // Initial call
      handleResponsive();
      
      // Listen for window resize
      window.addEventListener('resize', handleResponsive);
    });
  </script>
</body>
</html>