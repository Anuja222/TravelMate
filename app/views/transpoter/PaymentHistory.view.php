<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Provider Payments</title>
  <link rel="stylesheet" href="assets/css/Transpoter/PaymentHistory.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <!-- MAIN CONTENT -->
  <main>
    <div class="content">
      <div class="page-title">  
        <h1>Received Payments</h1>
        <div class="search-filter-container">
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="payment-search" placeholder="Search payments...">
          </div>
          <select id="filter-period" class="filter-dropdown">
            <option value="all">All Time</option>
            <option value="month">This Month</option>
            <option value="week">This Week</option>
            <option value="today">Today</option>
          </select>
        </div>
      </div>
      
      <div class="payment-summary">
        <div class="summary-card">
          <div class="summary-icon">
            <i class="fas fa-money-bill-wave"></i>
          </div>
          <div class="summary-details">
            <span class="summary-label">Total Received</span>
            <span class="summary-value">$2,845.75</span>
          </div>
        </div>
        <div class="summary-card">
          <div class="summary-icon completed">
            <i class="fas fa-calendar"></i>
          </div>
          <div class="summary-details">
            <span class="summary-label">This Month</span>
            <span class="summary-value">$875.50</span>
          </div>
        </div>
        <div class="summary-card">
          <div class="summary-icon pending">
            <i class="fas fa-user-friends"></i>
          </div>
          <div class="summary-details">
            <span class="summary-label">Total Customers</span>
            <span class="summary-value">18</span>
          </div>
        </div>
        <div class="summary-card">
          <div class="summary-icon failed">
            <i class="fas fa-route"></i>
          </div>
          <div class="summary-details">
            <span class="summary-label">Completed Rides</span>
            <span class="summary-value">23</span>
          </div>
        </div>
      </div>
      
      <div class="sort-options">
        <span>Sort by:</span>
        <select id="sort-payments" class="filter-dropdown">
          <option value="newest">Newest First</option>
          <option value="oldest">Oldest First</option>
          <option value="amount-high">Amount (High to Low)</option>
          <option value="amount-low">Amount (Low to High)</option>
        </select>
      </div>
      
      <ul class="payments">
        <li class="payment-item completed">
          <div class="payment-info">
            <div class="payment-icon">
              <i class="fas fa-car"></i>
            </div>
            <div class="payment-details">
              <h2>City to Airport Transfer</h2>
              <div class="payment-meta">
                <span class="payment-date"><i class="far fa-calendar"></i> Nov 12, 2023</span>
                <span class="payment-amount"><i class="fas fa-money-bill"></i> $125.00</span>
                <span class="payment-customer"><i class="fas fa-user"></i> Robert Johnson</span>
              </div>
              <a href="paymentDetails.php" class="view-link" target="_blank">View payment details</a>
            </div>
          </div>
          <div class="action-buttons">
            <button class="download-btn"><i class="fas fa-download"></i> Receipt</button>
            <button class="delete-btn"><i class="fas fa-envelope"></i> Delete</button>
          </div>
        </li>

        <li class="payment-item completed">
          <div class="payment-info">
            <div class="payment-icon">
              <i class="fas fa-bus"></i>
            </div>
            <div class="payment-details">
              <h2>Group Tour Transport</h2>
              <div class="payment-meta">
                <span class="payment-date"><i class="far fa-calendar"></i> Nov 10, 2023</span>
                <span class="payment-amount"><i class="fas fa-money-bill"></i> $320.00</span>
                <span class="payment-customer"><i class="fas fa-users"></i> Adventure Travel Group</span>
              </div>
              <a href="paymentDetails.php" class="view-link" target="_blank">View payment details</a>
            </div>
          </div>
          <div class="action-buttons">
            <button class="download-btn"><i class="fas fa-download"></i> Receipt</button>
            <button class="delete-btn"><i class="fas fa-envelope"></i> Delete</button>
          </div>
        </li>
        
        <li class="payment-item completed">
          <div class="payment-info">
            <div class="payment-icon">
              <i class="fas fa-taxi"></i>
            </div>
            <div class="payment-details">
              <h2>Downtown Pickup Service</h2>
              <div class="payment-meta">
                <span class="payment-date"><i class="far fa-calendar"></i> Nov 5, 2023</span>
                <span class="payment-amount"><i class="fas fa-money-bill"></i> $48.50</span>
                <span class="payment-customer"><i class="fas fa-user"></i> Sarah Williams</span>
              </div>
              <a href="paymentDetails.php" class="view-link" target="_blank">View payment details</a>
            </div>
          </div>
          <div class="action-buttons">
            <button class="download-btn"><i class="fas fa-download"></i> Receipt</button>
            <button class="delete-btn"><i class="fas fa-envelope"></i> Delete</button>
          </div>
        </li>
      </ul>
      
      <div class="pagination">
        <button class="page-btn" disabled><i class="fas fa-chevron-left"></i></button>
        <span class="current-page">1</span>
        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
      </div>
    </div>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Search functionality
      const searchInput = document.getElementById('payment-search');
      const paymentItems = document.querySelectorAll('.payment-item');
      
      searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        paymentItems.forEach(item => {
          const text = item.textContent.toLowerCase();
          if (text.includes(searchTerm)) {
            item.style.display = 'flex';
          } else {
            item.style.display = 'none';
          }
        });
      });
      
      // Filter by period functionality
      const periodSelect = document.getElementById('filter-period');
      
      periodSelect.addEventListener('change', function() {
        const filterValue = this.value;
        alert(`Filtering by ${filterValue} period. This would filter payments in a real application.`);
        // In a real application, this would filter payments by date
      });
      
      // Sort functionality
      const sortSelect = document.getElementById('sort-payments');
      
      sortSelect.addEventListener('change', function() {
        const sortValue = this.value;
        alert(`Sorting by ${sortValue}. This would sort payments in a real application.`);
        // In a real application, this would sort the payment items
      });
      
      // Download receipt functionality
      const downloadButtons = document.querySelectorAll('.download-btn');
      
      downloadButtons.forEach(button => {
        button.addEventListener('click', function() {
          const paymentItem = this.closest('.payment-item');
          const serviceType = paymentItem.querySelector('h2').textContent;
          alert(`Downloading receipt for: ${serviceType}`);
          // In a real application, this would download a PDF receipt
        });
      });
      
      // Contact customer functionality
      const contactButtons = document.querySelectorAll('.contact-btn');
      
      contactButtons.forEach(button => {
        button.addEventListener('click', function() {
          const paymentItem = this.closest('.payment-item');
          const customerElement = paymentItem.querySelector('.payment-customer');
          const customer = customerElement ? customerElement.textContent.replace(' ', '') : 'customer';
          alert(`Opening contact form for ${customer}`);
          // In a real application, this would open a contact modal or email client
        });
      });
      
      // Form validation for search
      searchInput.addEventListener('keypress', function(e) {
        // Prevent special characters that might be used for injection attacks
        const invalidChars = /[<>$#{}]/;
        if (invalidChars.test(e.key)) {
          e.preventDefault();
          alert("Special characters are not allowed in search.");
        }
      });
      
      // Responsive adjustments
      function handleResponsive() {
        if (window.innerWidth < 768) {
          document.querySelector('.payment-summary').classList.add('mobile-view');
          document.querySelectorAll('.payment-item').forEach(item => {
            item.classList.add('mobile-view');
          });
        } else {
          document.querySelector('.payment-summary').classList.remove('mobile-view');
          document.querySelectorAll('.payment-item').forEach(item => {
            item.classList.remove('mobile-view');
          });
        }
      }
      
      // Initial call and event listener for responsiveness
      handleResponsive();
      window.addEventListener('resize', handleResponsive);
    });
  </script>
</body>
</html>