<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Booking History</title>
  <link rel="stylesheet" href="assets/css/Transpoter/bookingHistory.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <!-- mAIN CONTENT -->
  <main>
    <!-- sIDEBAR -->

    <div class="content">
      <div class="page-title">  
        <h1>Completed Bookings</h1>
        <p>View your completed transportation services</p>
      </div>
      
      <div class="filter-container">
        <div class="search-box">
          <input type="text" id="bookingSearch" placeholder="Search bookings...">
          <i class="fas fa-search"></i>
        </div>
        <select id="bookingFilter">
          <option value="all">All Completed</option>
          <option value="week">This Week</option>
          <option value="month">This Month</option>
          <option value="high-rated">Highly Rated</option>
        </select>
      </div>
      
      <ul class="bookings">
        <li class="booking-item">
          <div class="booking-info">
            <div class="booking-icon">
              <i class="fas fa-car"></i>
            </div>
            <div class="booking-details">
              <h2>Booking #TR4512 - Colombo to Kandy</h2>
              <div class="booking-meta">
                <span class="booking-date"><i class="far fa-calendar"></i> Completed: Jun 15, 2024</span>
                <span class="booking-status completed">Completed</span>
              </div>
              <div class="customer-info">
                <i class="fas fa-user"></i> Customer: Samantha Perera
              </div>
              <div class="customer-info">
                <i class="fas fa-star"></i> Your rating: 4.8/5
              </div>
               <a href="tripDetails" class="view-link">View Trip Details</a>
            </div>
          </div>
          <div class="action-buttons">
            <button class="download-btn"><i class="fas fa-download"></i> Receipt</button>
            <button class="delete-btn"><i class="fas fa-delete"></i> Delete</button>
          </div>
        </li>

        <li class="booking-item">
          <div class="booking-info">
            <div class="booking-icon">
              <i class="fas fa-shuttle-van"></i>
            </div>
            <div class="booking-details">
              <h2>Booking #TR4789 - Airport Transfer</h2>
              <div class="booking-meta">
                <span class="booking-date"><i class="far fa-calendar"></i> Completed: Jun 12, 2024</span>
                <span class="booking-status completed">Completed</span>
              </div>
              <div class="customer-info">
                <i class="fas fa-user"></i> Customer: James Wilson
              </div>
              <div class="customer-info">
                <i class="fas fa-star"></i> Your rating: 5.0/5
              </div>
              <a href="tripDetails" class="view-link">View Trip Details</a>
                
            </div>
          </div>
          <div class="action-buttons">
            <button class="download-btn"><i class="fas fa-download"></i> Receipt</button>
            <button class="delete-btn"><i class="fas fa-delete"></i> Delete</button>
          </div>
        </li>
        
        <li class="booking-item">
          <div class="booking-info">
            <div class="booking-icon">
              <i class="fas fa-taxi"></i>
            </div>
            <div class="booking-details">
              <h2>Booking #TR4321 - Galle to Colombo</h2>
              <div class="booking-meta">
                <span class="booking-date"><i class="far fa-calendar"></i> Completed: Jun 8, 2024</span>
                <span class="booking-status completed">Completed</span>
              </div>
              <div class="customer-info">
                <i class="fas fa-user"></i> Customer: Maria Silva
              </div>
              <div class="customer-info">
                <i class="fas fa-star"></i> Your rating: 4.5/5
              </div>
               <a href="tripDetails" class="view-link">View Trip Details</a>
            </div>
          </div>
          <div class="action-buttons">
            <button class="download-btn"><i class="fas fa-download"></i> Receipt</button>
            <button class="delete-btn"><i class="fas fa-delete"></i> Delete</button>
          </div>
        </li>

        <li class="booking-item">
          <div class="booking-info">
            <div class="booking-icon">
              <i class="fas fa-bus"></i>
            </div>
            <div class="booking-details">
              <h2>Booking #TR4125 - City Tour</h2>
              <div class="booking-meta">
                <span class="booking-date"><i class="far fa-calendar"></i> Completed: Jun 5, 2024</span>
                <span class="booking-status completed">Completed</span>
              </div>
              <div class="customer-info">
                <i class="fas fa-user"></i> Customer: Robert Chang
              </div>
              <div class="customer-info">
                <i class="fas fa-star"></i> Your rating: 4.2/5
              </div>
               <a href="tripDetails" class="view-link">View Trip Details</a>
            </div>
          </div>
          <div class="action-buttons">
            <button class="download-btn"><i class="fas fa-download"></i> Receipt</button>
            <button class="delete-btn"><i class="fas fa-delete"></i> Contact</button>
          </div>
        </li>
      </ul>
      
      <div class="pagination">
        <button class="page-btn disabled"><i class="fas fa-chevron-left"></i></button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
      </div>
    </div>
  </main>

  <script>
    // search functionality
    document.getElementById('bookingSearch').addEventListener('keyup', function() {
      const searchTerm = this.value.toLowerCase();
      const bookings = document.querySelectorAll('.booking-item');
      
      bookings.forEach(booking => {
        const bookingText = booking.textContent.toLowerCase();
        if (bookingText.includes(searchTerm)) {
          booking.style.display = 'flex';
        } else {
          booking.style.display = 'none';
        }
      });
    });
    
    // filter functionality
    document.getElementById('bookingFilter').addEventListener('change', function() {
      const filterValue = this.value;
      const bookings = document.querySelectorAll('.booking-item');
      
      // in a real application, this would filter based on actual data
      // for demo purposes, we're just showing all items
      bookings.forEach(booking => {
        booking.style.display = 'flex';
      });
      
      // show message based on selection
      if (filterValue !== 'all') {
        alert('Filtering by: ' + this.options[this.selectedIndex].text);
      }
    });
    
    // modal functionality
    const modal = document.getElementById('ratingModal');
    const closeBtn = document.querySelector('.close-btn');
    const ratingStars = document.querySelectorAll('.rating-stars i');
    const ratingValue = document.getElementById('ratingValue');
    
    // function to open rating modal
    function openRatingModal(bookingId, customer) {
      document.getElementById('bookingId').value = bookingId;
      document.getElementById('customerName').textContent = customer;
      modal.style.display = 'flex';
    }
    
    // close modal
    closeBtn.addEventListener('click', function() {
      modal.style.display = 'none';
    });
    
    // close modal if clicked outside
    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
    
    // star rating functionality
    ratingStars.forEach(star => {
      star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        ratingValue.value = value;
        
        // update star display
        ratingStars.forEach(s => {
          if (s.getAttribute('data-value') <= value) {
            s.classList.add('active');
          } else {
            s.classList.remove('active');
          }
        });
      });
    });
    
    // form submission
    document.getElementById('ratingForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      // validate rating
      if (!ratingValue.value) {
        alert('Please select a rating');
        return;
      }
      
      // in a real application, this would submit to the server
      alert('Rating submitted for booking #' + document.getElementById('bookingId').value);
      modal.style.display = 'none';
      
      // reset form
      this.reset();
      ratingStars.forEach(star => star.classList.remove('active'));
    });
    
    // responsive adjustments
    function handleResize() {
      const bookings = document.querySelectorAll('.booking-item');
      
      if (window.innerWidth < 768) {
        bookings.forEach(booking => {
          booking.style.flexDirection = 'column';
          booking.style.alignItems = 'flex-start';
        });
      } else {
        bookings.forEach(booking => {
          booking.style.flexDirection = 'row';
          booking.style.alignItems = 'center';
        });
      }
    }
    
    // initial call and event listener
    handleResize();
    window.addEventListener('resize', handleResize);
  </script>
</body>
</html>