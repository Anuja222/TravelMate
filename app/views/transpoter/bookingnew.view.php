<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Bookings</title>
  <link rel="stylesheet" href="assets/css/Transpoter/bookingnew.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <!-- MAIN CONTENT -->
  <main>
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <ul>
        <li><a href="tr_dashboard"><i ></i> Dashboard</a></li>
        <li><a href="bookingnew" class="active"><i ></i> Bookings</a></li>
        <li><a href="setting"><i ></i> Setting</a></li>
      </ul>
    </aside>

    <div class="content">
      <div class="page-title">  
        <h1>Booking Management</h1>
        <p>Manage your booking requests and history</p>
      </div>

      <!-- PERFORMANCE SUMMARY -->
      <section class="activity-summary">
        <h3>Booking Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <div class="stat-num">4</div>
            <div class="stat-label">Pending</div>
          </div>
          <div class="stat">
            <div class="stat-num">3</div>
            <div class="stat-label">Booked</div>
          </div>
          <div class="stat">
            <div class="stat-num">45</div>
            <div class="stat-label">Total Bookings</div>
          </div>
        </div>
      </section>

      <section class="booking-requests">
        <h2>Booking Requests</h2>
        <div class="filter-bar">
          <input type="text" id="searchBox" placeholder="🔍 Search accepted, rejected and pending requests">
          <select id="categoryFilter">
            <option value="all">All Categories</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
            <option value="pending">Pending</option>
          </select>
          <button id="applyFilter">Search</button>
        </div>
        
        <div class="booking-list">
          <!-- Sample booking request -->
          <div class="booking-card pending">
            <div class="booking-header">
              <h3>Booking #TR2024001</h3>
              <span class="status-badge">Pending</span>
            </div>
            <div class="booking-details">
              <p><strong>Customer:</strong> John Smith</p>
              <p><strong>Pickup:</strong> Colombo International Airport</p>
              <p><strong>Destination:</strong> Galle Face Hotel, Colombo</p>
              <p><strong>Date & Time:</strong> 2024-06-15 at 14:30</p>
              <p><strong>Passengers:</strong> 4</p>
              <p><strong>Estimated Fare:</strong> LKR 2,500</p>
            </div>
            <div class="booking-actions">
              <button class="accept-btn" onclick="updateBookingStatus('TR2024001', 'accepted')"><i class="fas fa-check"></i> Accept</button>
              <button class="reject-btn" onclick="updateBookingStatus('TR2024001', 'rejected')"><i class="fas fa-times"></i> Reject</button>
              <button class="details-btn" onclick="window.location.href='ViewDetailspending';"><i class="fas fa-eye"></i> View Details</button>
            </div>
          </div>

          <!-- Additional booking requests would be dynamically generated from database -->
          <div class="booking-card pending">
            <div class="booking-header">
              <h3>Booking #TR2024002</h3>
              <span class="status-badge">Pending</span>
            </div>
            <div class="booking-details">
              <p><strong>Customer:</strong> Sarah Johnson</p>
              <p><strong>Pickup:</strong> Cinnamon Lakeside, Colombo</p>
              <p><strong>Destination:</strong> Bentota Beach Resort</p>
              <p><strong>Date & Time:</strong> 2024-06-17 at 09:00</p>
              <p><strong>Passengers:</strong> 2</p>
              <p><strong>Estimated Fare:</strong> LKR 8,000</p>
            </div>
            <div class="booking-actions">
              <button class="accept-btn" onclick="updateBookingStatus('TR2024002', 'accepted')"><i class="fas fa-check"></i> Accept</button>
              <button class="reject-btn" onclick="updateBookingStatus('TR2024002', 'rejected')"><i class="fas fa-times"></i> Reject</button>
              <button class="details-btn" onclick="window.location.href='ViewDetailspending';"><i class="fas fa-eye"></i> View Details</button>
            </div>
          </div>

          <div class="booking-card accepted">
            <div class="booking-header">
              <h3>Booking #TR2024003</h3>
              <span class="status-badge">Accepted</span>
            </div>
            <div class="booking-details">
              <p><strong>Customer:</strong> Robert Williams</p>
              <p><strong>Pickup:</strong> Negombo Beach</p>
              <p><strong>Destination:</strong> Kandy City Center</p>
              <p><strong>Date & Time:</strong> 2024-06-20 at 11:00</p>
              <p><strong>Passengers:</strong> 3</p>
              <p><strong>Agreed Fare:</strong> LKR 12,500</p>
            </div>
            <div class="booking-actions">
              <button class="details-btn" onclick="window.location.href='ViewDetailsAccepted';"><i class="fas fa-eye"></i> View Details</button>
              <button class="cancel-btn" onclick="updateBookingStatus('TR2024003', 'cancelled')"><i class="fas fa-times"></i> Cancel</button>
            </div>
          </div>
          
          <div class="booking-actions-footer">
            <button class="history-btn" onclick="window.location.href='bookingHistory';">
              <i class="fas fa-history"></i> View Booking History
            </button>
          </div>
        </div>
      </section>
    </div>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    // Filter functionality
    document.getElementById('applyFilter').addEventListener('click', function() {
      const searchTerm = document.getElementById('searchBox').value.toLowerCase();
      const filterValue = document.getElementById('categoryFilter').value;
      const bookingCards = document.querySelectorAll('.booking-card');
      
      bookingCards.forEach(card => {
        const cardText = card.textContent.toLowerCase();
        const status = card.classList[1]; // pending, accepted, rejected
        const matchesSearch = searchTerm === '' || cardText.includes(searchTerm);
        const matchesFilter = filterValue === 'all' || filterValue === status;
        
        if (matchesSearch && matchesFilter) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
    
    // Booking status update function
    function updateBookingStatus(bookingId, status) {
      const statusText = status.charAt(0).toUpperCase() + status.slice(1);
      
      if (confirm(`Are you sure you want to ${status} booking ${bookingId}?`)) {
        // In a real application, this would call an API to update the status
        alert(`Booking ${bookingId} has been ${status}.`);
        
        // Update UI accordingly
        const bookingCard = document.querySelector(`.booking-card:has(h3:contains('${bookingId}'))`);
        if (bookingCard) {
          bookingCard.classList.remove('pending', 'accepted', 'rejected');
          bookingCard.classList.add(status);
          
          const statusBadge = bookingCard.querySelector('.status-badge');
          statusBadge.textContent = statusText;
          
          // Update actions based on new status
          const actionsDiv = bookingCard.querySelector('.booking-actions');
          if (status === 'accepted') {
            actionsDiv.innerHTML = `
              <button class="details-btn" onclick="window.location.href='ViewDetailsAccepted';"><i class="fas fa-eye"></i> View Details</button>
              <button class="cancel-btn" onclick="updateBookingStatus('${bookingId}', 'cancelled')"><i class="fas fa-times"></i> Cancel</button>
            `;
          } else if (status === 'rejected') {
            actionsDiv.innerHTML = `
              <button class="details-btn" onclick="window.location.href='ViewDetails';"><i class="fas fa-eye"></i> View Details</button>
            `;
          }
        }
      }
    }
    
    // Responsive adjustments
    function handleResize() {
      const filterBar = document.querySelector('.filter-bar');
      
      if (window.innerWidth < 768) {
        filterBar.style.flexDirection = 'column';
      } else {
        filterBar.style.flexDirection = 'row';
      }
    }
    
    // Initial call and event listener
    handleResize();
    window.addEventListener('resize', handleResize);
  </script>
</body>
</html>