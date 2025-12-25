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

  <!-- Transportation Details Section -->
  <section class="transport-details-section">
    <div class="container">
      <div class="details-grid">
        <!-- Left Column - Transport Info -->
        <div class="transport-info">
          <div class="transport-header">
            <div class="transport-title">
              <h1 id="transportTitle">Premium SUV - Toyota Land Cruiser</h1>
              <div class="transport-badges" id="transportBadges">
                <!-- <span class="badge luxury">Luxury</span> -->
              </div>
            </div>
          </div>

          <div class="transport-location">
            <span class="location-icon">📍</span>
            <span id="pickupLocation">Colombo International Airport</span>
            <button class="map-btn" onclick="showMap()">View on Map</button>
          </div>

          <div class="transport-description">
            <h3>About This Vehicle</h3>
            <p id="transportDescription">
              Experience comfortable and reliable transportation with our premium SUV service. Perfect for families or groups, this spacious vehicle offers luxury, safety, and convenience throughout your journey in Sri Lanka. Equipped with modern amenities and driven by professional, experienced chauffeurs.
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
              <span class="price-amount" id="priceAmount">Rs.12,500</span>
              <span class="price-period">/ day</span>
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
              <input type="text" id="pickupLocation" placeholder="Enter pickup address" required>
            </div>

            <div class="form-group">
              <label>Drop-off Location</label>
              <input type="text" id="dropoffLocation" placeholder="Enter drop-off address" required>
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

  <script src="assets/js/transportdetails.js"></script>
</body>
</html>