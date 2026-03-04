<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Transportation Details</title>
  <link rel="stylesheet" href="assets/css/Traveller/transportdetails.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- Transportation Hero Section -->
  <section class="transport-hero">
    <div class="hero-gallery">
      <div class="main-image">
        <img id="mainTransportImage" src="assets/images/car.png" alt="Transport Main Image">
        <div class="image-counter">
          <span id="imageCounter">0 / 0</span>
        </div>
        <div class="gallery-nav">
          <button class="nav-btn prev" onclick="previousImage()">‹</button>
          <button class="nav-btn next" onclick="nextImage()">›</button>
        </div>
      </div>
      <div class="thumbnail-gallery" id="thumbnailGallery">
        <!-- Thumbnails will be populated by JavaScript -->
      </div>
    </div>
  </section>

  <!-- Transportation Details Section -->
  <section class="transport-details-section">
    <div class="container">
      <div class="details-grid">
        <!-- Left Column - Transport Info -->
        <div class="transport-info">
          <div class="transport-header">
            <div class="transport-title">
              <h1 id="transportTitle">Loading transport details...</h1>
              <div class="transport-badges" id="transportBadges">
                <!-- <span class="badge luxury">Luxury</span> -->
              </div>
            </div>
          </div>

          <div class="transport-location">
            <span class="location-icon">📍</span>
            <span id="defaultLocation">Loading location...</span>
            <button class="map-btn" onclick="showMap()">View on Map</button>
          </div>

          <div class="transport-description">
            <h3>About This Vehicle</h3>
            <p id="transportDescription">
              Transport details are loading. Please wait...
            </p>
          </div>

          <div class="transport-features">
            <h3>Vehicle Features & Amenities</h3>
            <div class="features-grid" id="featuresGrid">
              <!-- Features will be populated by JavaScript -->
            </div>
          </div>

          <div class="transport-specifications">
            <h3>Vehicle Specifications</h3>
            <div class="specs-grid" id="specsGrid">
              <!-- Specifications will be populated by JavaScript -->
            </div>
          </div>

          <div class="transport-pricing">
            <h3>Pricing Options</h3>
            <div class="pricing-grid" id="pricingGrid">
              <!-- Pricing options will be populated by JavaScript -->
            </div>
          </div>

          <div class="transport-reviews">
            <h3>Customer Reviews</h3>
            <div class="reviews-summary">
              <div class="overall-rating">
                <div class="rating-number" id="overallRating">4.9</div>
                <div class="rating-label">Excellent</div>
              </div>
              <div class="rating-breakdown" id="ratingBreakdown">
                <!-- Rating breakdown will be populated by JavaScript -->
              </div>
            </div>
            <div class="reviews-list" id="reviewsList">
              <!-- Reviews will be populated by JavaScript -->
            </div>
          </div>
        </div>

        <!-- Right Column - Booking Widget -->
        <div class="booking-widget">
          <div class="widget-header">
            <div class="price-display">
              <span class="price-amount" id="priceAmount">Rs.0</span>
              <span class="price-period">/ 1km</span>
            </div>
            <div class="price-note">Prices may vary by duration and destination</div>
          </div>

          <form class="booking-form" id="bookingForm">
            <div class="form-group">
              <label>Service Type</label>
              <select id="serviceType" required>
                <option value="0">Select Service Type</option>
                <option value="airport">Airport Transfer</option>
                <option value="daily">Daily Rental</option>
                <option value="tour">Tour Package</option>
                <option value="custom">Custom Route</option>
              </select>
            </div>

            <div class="date-group">
              <div class="form-group">
                <label>Pickup Date</label>
                <input type="date" id="pickupDate" required>
              </div>
              <div class="form-group">
                <label>Pickup Time</label>
                <input type="time" id="pickupTime" required>
              </div>
            </div>

            <div class="date-group">
              <div class="form-group">
                <label>Return Date</label>
                <input type="date" id="returnDate" required>
              </div>
              <div class="form-group">
                <label>Return Time</label>
                <input type="time" id="returnTime" required>
              </div>
            </div>

            <div class="form-group">
              <label>Pickup Location</label>
              <input type="text" id="pickupLocationInput" placeholder="Enter pickup address" required>
            </div>

            <div class="form-group">
              <label>Drop-off Location</label>
              <input type="text" id="dropoffLocationInput" placeholder="Enter drop-off address" required>
            </div>

            <div class="passengers-group">
              <div class="form-group">
                <label>Passengers</label>
                <select id="passengers" required>
                  <option value="1">1 Passenger</option>
                  <option value="2">2 Passengers</option>
                  <option value="3">3 Passengers</option>
                  <option value="4">4 Passengers</option>
                  <option value="5">5+ Passengers</option>
                </select>
              </div>
              <div class="form-group">
                <label>Luggage</label>
                <select id="luggage">
                  <option value="0">No Luggage</option>
                  <option value="1">1 Bag</option>
                  <option value="2">2 Bags</option>
                  <option value="3">3+ Bags</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Special Requirements</label>
              <textarea id="specialRequirements" rows="3" placeholder="Child seat, wheelchair accessible, etc."></textarea>
            </div>

            <div class="booking-summary" id="bookingSummary" style="display: none;">
              <div class="summary-row">
                <span>Duration:</span>
                <span id="durationCount">0 days</span>
              </div>
              <div class="summary-row">
                <span>Base Price:</span>
                <span id="basePrice">Rs.0</span>
              </div>
              <div class="summary-row">
                <span>Service Charges:</span>
                <span id="serviceCharge">Rs.0</span>
              </div>
              <div class="summary-row total">
                <span>Total:</span>
                <span id="totalPrice">Rs.0</span>
              </div>
            </div>

            <button type="button" class="btn-primary book-now-btn" onclick="calculatePrice()">Check Availability</button>
            <button type="button" class="btn-primary confirm-booking-btn" onclick="confirmBooking()" style="display: none;">Confirm Booking</button>

            <div class="booking-notes">
              <p>✓ Professional chauffeur included</p>
              <p>✓ Free cancellation up to 24 hours</p>
              <p>✓ Fuel & tolls included</p>
              <p>✓ 24/7 customer support</p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <!-- Transport Booking Success Modal -->
  <div id="bookingSuccessModal" class="booking-success-modal">
    <div class="booking-success-content">
      <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <h2>Request Submitted!</h2>
      <p>Your transport booking request is pending transporter approval.</p>
      <div class="booking-id-display">
        <span class="label">Booking ID:</span>
        <span class="booking-id" id="modalBookingId">TB12345678</span>
      </div>
      <p class="confirmation-note">You’ll be notified once the transporter approves your request.</p>
      <div class="modal-actions">
        <button onclick="goToTransportBookings()" class="btn-view-bookings">
          <i class="fas fa-list"></i> View My Bookings
        </button>
        <button onclick="closeBookingModal()" class="btn-close-modal">
          Close
        </button>
      </div>
    </div>
  </div>

  <div id="dateUnavailableModal" class="booking-success-modal">
    <div class="booking-success-content">
      <div class="success-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
      </div>
      <h2>Dates Not Available</h2>
      <p id="dateUnavailableMessage">Dates are not available. Please choose different pickup and return dates.</p>
      <div class="modal-actions">
        <button onclick="closeDateUnavailableModal()" class="btn-close-modal">OK</button>
      </div>
    </div>
  </div>

  <style>
    .booking-success-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 10000;
      animation: fadeIn 0.3s ease-in-out;
    }

    .booking-success-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      animation: slideUp 0.4s ease-out;
    }

    .booking-success-modal .success-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      background: linear-gradient(135deg, #10b981, #059669);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease-out 0.2s both;
    }

    .booking-success-modal .success-icon svg {
      width: 45px;
      height: 45px;
      color: white;
    }

    .booking-success-content h2 {
      font-size: 26px;
      color: #1f2937;
      margin-bottom: 12px;
      font-weight: 600;
    }

    .booking-success-content p {
      font-size: 15px;
      color: #6b7280;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .booking-id-display {
      background: #f3f4f6;
      padding: 15px 20px;
      border-radius: 8px;
      margin: 20px 0;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .booking-id-display .label {
      font-size: 13px;
      color: #6b7280;
      font-weight: 500;
    }

    .booking-id-display .booking-id {
      font-size: 20px;
      color: #1abc5b;
      font-weight: 700;
      font-family: 'Courier New', monospace;
      letter-spacing: 1px;
    }

    .confirmation-note {
      font-size: 13px !important;
      color: #9ca3af !important;
      margin-bottom: 25px !important;
    }

    .modal-actions {
      display: flex;
      gap: 12px;
      justify-content: center;
      margin-top: 25px;
    }

    .btn-view-bookings {
      background: linear-gradient(135deg, #1abc5b, #149647);
      color: white;
      border: none;
      padding: 12px 28px;
      font-size: 15px;
      font-weight: 500;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-view-bookings:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(26, 188, 91, 0.4);
    }

    .btn-close-modal {
      background: #f3f4f6;
      color: #6b7280;
      border: 2px solid #e5e7eb;
      padding: 12px 28px;
      font-size: 15px;
      font-weight: 500;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-close-modal:hover {
      background: #e5e7eb;
      border-color: #d1d5db;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translate(-50%, -40%);
      }
      to {
        opacity: 1;
        transform: translate(-50%, -50%);
      }
    }

    @keyframes scaleIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    @media (max-width: 600px) {
      .modal-actions {
        flex-direction: column;
      }
      
      .btn-view-bookings,
      .btn-close-modal {
        width: 100%;
        justify-content: center;
      }
    }
  </style>

  <script src="assets/js/transportdetails.js?v=<?php echo file_exists(__DIR__ . '/../../../public/assets/js/transportdetails.js') ? filemtime(__DIR__ . '/../../../public/assets/js/transportdetails.js') : time(); ?>"></script>
  <script>
    // Modal helper functions
    function showBookingSuccessModal(bookingId) {
      const modal = document.getElementById('bookingSuccessModal');
      const bookingIdEl = document.getElementById('modalBookingId');
      if (modal && bookingIdEl) {
        bookingIdEl.textContent = bookingId;
        modal.style.display = 'block';
      }
    }

    function closeBookingModal() {
      const modal = document.getElementById('bookingSuccessModal');
      if (modal) {
        modal.style.display = 'none';
        // Reset form
        document.getElementById('bookingForm').reset();
        document.getElementById('bookingSummary').style.display = 'none';
        document.querySelector('.book-now-btn').style.display = 'block';
        document.querySelector('.confirm-booking-btn').style.display = 'none';
      }
    }

    function goToTransportBookings() {
      window.location.href = 'mytransportbookings';
    }

    function showDateUnavailableModal(message) {
      const modal = document.getElementById('dateUnavailableModal');
      const messageEl = document.getElementById('dateUnavailableMessage');
      if (modal && messageEl) {
        messageEl.textContent = message || 'Dates are not available. Please choose different pickup and return dates.';
        modal.style.display = 'block';
      }
    }

    function closeDateUnavailableModal() {
      const modal = document.getElementById('dateUnavailableModal');
      if (modal) {
        modal.style.display = 'none';
      }
    }
  </script>
</body>
</html>