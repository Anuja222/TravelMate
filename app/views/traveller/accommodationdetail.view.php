<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Hotel Details</title>
  <link rel="stylesheet" href="assets/css/Traveller/accommodationdetail.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- Hotel Hero Section -->
  <section class="hotel-hero">
    <div class="hero-gallery">
      <div class="main-image">
        <img id="mainHotelImage" src="assets/images/luxuryhotel.png" alt="Hotel Main Image">
        <div class="image-counter">
          <span id="imageCounter">1 / 4</span>
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

  <!-- Hotel Details Section -->
  <section class="hotel-details-section">
    <div class="container">
      <div class="details-grid">
        <!-- Left Column - Hotel Info -->
        <div class="hotel-info">
          <div class="hotel-header">
            <div class="hotel-title">
              <h1 id="hotelTitle">Luxury Beach Resort</h1>
              <div class="hotel-badges" id="hotelBadges">
                <!-- <span class="badge luxury">Luxury</span> -->
              </div>
            </div>
            <!-- <div class="hotel-rating">
              <div class="stars" id="hotelStars">★★★★★</div>
              <span class="rating-score" id="hotelRating">4.8</span>
              <span class="reviews-count" id="reviewsCount">(324 reviews)</span>
            </div> -->
          </div>

          <div class="hotel-location">
            <span class="location-icon">📍</span>
            <span id="hotelLocation">Bentota Beach, Sri Lanka</span>
            <button class="map-btn" onclick="showMap()">View on Map</button>
          </div>

          <div class="hotel-description">
            <h3>About This Property</h3>
            <p id="hotelDescription">
              Experience the ultimate in luxury at our beachfront resort in Bentota. With pristine beaches, world-class amenities, and exceptional service, this resort offers an unforgettable stay in paradise. Our spacious rooms and suites feature modern amenities and stunning ocean views.
            </p>
          </div>

          <div class="hotel-amenities">
            <h3>Room Availability</h3>
            <div class="room-availability-section" style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div style="background: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                  <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Rooms</div>
                  <div id="totalRooms" style="font-size: 32px; font-weight: 700; color: #2c3e50;">-</div>
                </div>
                <div style="background: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                  <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Available Rooms</div>
                  <div id="availableRooms" style="font-size: 32px; font-weight: 700; color: #1abc5b;">-</div>
                </div>
                <div style="background: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                  <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Booked Rooms</div>
                  <div id="unavailableRooms" style="font-size: 32px; font-weight: 700; color: #e74c3c;">-</div>
                </div>
              </div>
              <div id="availabilityMessage" style="margin-top: 15px; padding: 12px; background: white; border-radius: 8px; text-align: center; font-size: 14px; color: #666; display: none;">
                <!-- Availability message will be shown here -->
              </div>
            </div>
          </div>

          <div class="hotel-amenities">
            <h3>Amenities & Features</h3>
            <div class="amenities-grid" id="amenitiesGrid">
              <!-- Amenities will be populated by JavaScript -->
            </div>
          </div>

          <div class="hotel-rooms">
            <h3>Room Types</h3>
            <div class="rooms-grid" id="roomsGrid">
              <!-- Room types will be populated by JavaScript -->
            </div>
          </div>

          <div class="hotel-reviews">
            <h3>Guest Reviews</h3>
            <div class="reviews-summary">
              <div class="overall-rating">
                <div class="rating-number" id="overallRating">4.8</div>
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
              <span class="price-amount" id="priceAmount">Rs.45000</span>
              <span class="price-period">/ night</span>
            </div>
            <div class="price-note">Prices may vary by season</div>
          </div>

          <form class="booking-form" id="bookingForm">
            <div class="date-group">
              <div class="form-group">
                <label>Check-in</label>
                <input type="date" id="checkinDate" required>
              </div>
              <div class="form-group">
                <label>Check-out</label>
                <input type="date" id="checkoutDate" required>
              </div>
            </div>

            <div class="guests-group">
              <div class="form-group">
                <label>Adults</label>
                <select id="adults" required>
                  <option value="1">1 Adult</option>
                  <option value="2">2 Adults</option>
                  <option value="3">3 Adults</option>
                  <option value="4">4+ Adults</option>
                </select>
              </div>
              <div class="form-group">
                <label>Children</label>
                <select id="children">
                  <option value="0">0 Children</option>
                  <option value="1">1 Child</option>
                  <option value="2">2 Children</option>
                  <option value="3">3+ Children</option>
                </select>
              </div>
            </div>

            <div class="room-selection">
              <label>Room Type</label>
              <select id="roomType" required>
                <option value="0">Select Room Type</option>
                <option value="1">Family Suite</option>
                <!-- Room options will be populated by JavaScript -->
              </select>
            </div>

<<<<<<< HEAD
            <div class="room-selection">
              <label>Number of Rooms</label>
              <select id="numberOfRooms" required>
                <option value="">Loading...</option>
              </select>
            </div>

=======
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
            <div class="booking-summary" id="bookingSummary" style="display: none;">
              <div class="summary-row">
                <span>Nights:</span>
                <span id="nightsCount">0</span>
              </div>
              <div class="summary-row">
                <span>Base Price:</span>
                <span id="basePrice">$0</span>
              </div>
              <div class="summary-row">
                <span>Taxes & Fees:</span>
                <span id="taxesFees">$0</span>
              </div>
              <div class="summary-row total">
                <span>Total:</span>
                <span id="totalPrice">$0</span>
              </div>
            </div>

            <button type="button" class="btn-primary book-now-btn" onclick="calculatePrice()">Check Availability</button>
            <button type="button" class="btn-primary confirm-booking-btn" onclick="confirmBooking()" style="display: none;">Confirm Booking</button>

            <div class="booking-notes">
              <p>✓ Free cancellation up to 24 hours before check-in</p>
              <p>✓ Best price guarantee</p>
              <p>✓ Instant confirmation</p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Similar Hotels Section -->
  <!-- <section class="similar-hotels-section">
    <div class="container">
      <div class="section-header">
        <h2>Similar Accommodations</h2>
        <p>You might also like these properties</p>
      </div>
      <div class="similar-hotels-grid" id="similarHotelsGrid">
        
      </div>
    </div>
  </section> -->

  <!-- Image Gallery Modal -->
  <!-- <div class="modal gallery-modal" id="galleryModal">
    <div class="gallery-modal-content">
      <div class="gallery-header">
        <h3 id="galleryTitle">Hotel Gallery</h3>
        <button class="close-btn" onclick="closeGalleryModal()">&times;</button>
      </div>
      <div class="gallery-viewer">
        <img id="galleryImage" src="" alt="Gallery Image">
        <div class="gallery-controls">
          <button class="gallery-nav-btn prev" onclick="previousGalleryImage()">‹</button>
          <button class="gallery-nav-btn next" onclick="nextGalleryImage()">›</button>
        </div>
        <div class="gallery-counter">
          <span id="galleryCounter">1 / 5</span>
        </div>
      </div>
      <div class="gallery-thumbnails" id="galleryThumbnails">
         Gallery thumbnails will be populated by JavaScript
      </div>
    </div>
  </div>  -->

  <!-- Validation Error Modal -->
  <div id="mapModal" class="map-modal">
    <div class="map-modal-content">
      <div class="map-modal-header">
        <h3 id="mapModalTitle">Accommodation Location</h3>
        <button type="button" class="map-modal-close" onclick="closeMapModal()">&times;</button>
      </div>
      <div class="map-modal-body">
        <iframe id="mapFrame" title="Accommodation Location Map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <div class="map-modal-fallback">
          <a id="mapExternalLink" href="#" target="_blank" rel="noopener noreferrer">Open in Google Maps</a>
        </div>
      </div>
    </div>
  </div>

  <div id="validationModal" class="validation-modal">
    <div class="validation-modal-content">
      <div class="validation-icon">
        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="30" cy="30" r="28" stroke="#f59e0b" stroke-width="3" fill="#fffbeb"/>
          <path d="M30 20V32" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
          <circle cx="30" cy="40" r="2" fill="#f59e0b"/>
        </svg>
      </div>
      <h2>Incomplete Information</h2>
      <p id="validationMessage">Please fill in all required fields</p>
      <button class="btn-close-validation" onclick="document.getElementById('validationModal').classList.remove('show')">Got it</button>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script src="assets/js/accommodationdetail.js"></script>
</body>
</html>