<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Transportation</title>
  <link rel="stylesheet" href="assets/css/Traveller/transport.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
  <!-- Header/Navbar -->
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-background">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Explore Transportation Options</h1>
          <p>From scenic train rides to comfortable bus journeys, discover the best ways to travel around Sri Lanka</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Filter Section -->
  <section class="filter-section">
    <div class="container">
      <div class="filter-bar">
        <div class="filter-group">
          <label for="route">Route</label>
          <select id="route">
            <option value="">All Routes</option>
            <option value="colombo-kandy">Colombo - Kandy</option>
            <option value="colombo-galle">Colombo - Galle</option>
            <option value="kandy-ella">Kandy - Ella</option>
            <option value="colombo-nuwara">Colombo - Nuwara Eliya</option>
            <option value="galle-mirissa">Galle - Mirissa</option>
            <option value="anuradhapura-polonnaruwa">Anuradhapura - Polonnaruwa</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="transport-type">Transport Type</label>
          <select id="transport-type">
            <option value="">All Types</option>
            <option value="train">Train</option>
            <option value="bus">Bus</option>
            <option value="taxi">Taxi/Car</option>
            <option value="tuk-tuk">Tuk-Tuk</option>
            <option value="boat">Boat</option>
            <option value="plane">Domestic Flight</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="price-range">Price Range</label>
          <select id="price-range">
            <option value="">All Prices</option>
            <option value="budget">Budget (Under Rs.500)</option>
            <option value="mid">Mid-range (Rs.500-2000)</option>
            <option value="premium">Premium (Rs.2000+)</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="duration">Duration</label>
          <select id="duration">
            <option value="">All Durations</option>
            <option value="short">Under 2 hours</option>
            <option value="medium">2-5 hours</option>
            <option value="long">5+ hours</option>
          </select>
        </div>
        <button class="btn filter-btn" onclick="applyFilters()">Filter Results</button>
      </div>
    </div>
  </section>

  <!-- Transportation Section -->
  <section class="transportation-section">
    <div class="container">
      <div class="transportation-grid" id="transportationGrid">
        
        <!-- Scenic Train -->
        <!-- <div class="transport-card" data-type="train" data-route="kandy-ella" data-price="budget" data-duration="long">
          <div class="card-image">
            <img src="assets/images/scenic-train.jpg" alt="Scenic Train Journey">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('scenic-train')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Kandy to Ella Train</h3>
              <div class="transport-type">
                <span class="type-icon">🚂</span>
                <span class="type-text">Train</span>
              </div>
            </div>
            <p class="route">📍 Kandy → Ella</p>
            <p class="description">One of the world's most beautiful train journeys through misty mountains and tea plantations</p>
            <div class="card-features">
              <span class="feature">🏔️ Mountain Views</span>
              <span class="feature">🍃 Tea Plantations</span>
              <span class="feature">📸 Photo Stops</span>
              <span class="feature">❄️ Cool Climate</span>
            </div>
            <div class="transport-info">
              <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">7 hours</span>
              </div>
              <div class="info-item">
                <span class="info-label">Frequency:</span>
                <span class="info-value">3 times daily</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.180</span>
                <span class="price-period">/ person</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('scenic-train')">View Details</button>
            </div>
          </div>
        </div> -->

        <!-- Express Bus -->
        <div class="transport-card" data-type="bus" data-route="colombo-kandy" data-price="budget" data-duration="medium">
          <div class="card-image">
            <img src="assets/images/bus.png" alt="Express Bus">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('express-bus')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <!-- <h3>Colombo to Kandy Express</h3> -->
              <div class="transport-type">
                <span class="type-icon">🚌</span>
                <span class="type-text">Bus</span>
              </div>
            </div>
            <!-- <p class="route">📍 Colombo → Kandy</p> -->
            <p class="description">Comfortable air-conditioned bus service connecting the commercial and cultural capitals</p>
            <div class="card-features">
              <span class="feature">❄️ Air Conditioned</span>
              <span class="feature">💺 Comfortable Seats</span>
              <span class="feature">📱 WiFi</span>
              <span class="feature">🎬 Entertainment</span>
            </div>
            <div class="transport-info">
              <!-- <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">3.5 hours</span>
              </div> -->
              <div class="info-item">
                <span class="info-label">Frequency:</span>
                <span class="info-value">Every 30 mins</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.320</span>
                <span class="price-period">/ 1km</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('express-bus')">View Details</button>
            </div>
          </div>
        </div>

        <!-- Private Taxi -->
        <div class="transport-card" data-type="taxi" data-route="colombo-galle" data-price="premium" data-duration="medium">
          <div class="card-image">
            <img src="assets/images/car.png" alt="Private Taxi">
            <div class="card-overlay">
              <button class="book-btn" onclick="window.location.href='transportdetails';">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <!-- <h3>Colombo to Galle Private Car</h3> -->
              <div class="transport-type">
                <span class="type-icon">🚗</span>
                <span class="type-text">Private Car</span>
              </div>
            </div>
            <!-- <p class="route">📍 Colombo → Galle</p> -->
            <p class="description">Door-to-door luxury transport with professional driver and flexible stops</p>
            <div class="card-features">
              <span class="feature">🚗 Private Vehicle</span>
              <span class="feature">👨‍✈️ Professional Driver</span>
              <span class="feature">🛑 Flexible Stops</span>
              <span class="feature">❄️ Air Conditioned</span>
            </div>
            <div class="transport-info">
              <!-- <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">2 hours</span>
              </div> -->
              <div class="info-item">
                <span class="info-label">Availability:</span>
                <span class="info-value">24/7</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.220</span>
                <span class="price-period">/1km</span>
              </div>
              <button class="btn-primary view-btn" onclick="window.location.href='transportdetails';">View Details</button>
            </div>
          </div>
        </div>

        <!-- Tuk-Tuk Experience -->
        <div class="transport-card" data-type="tuk-tuk" data-route="galle-mirissa" data-price="mid" data-duration="short">
          <div class="card-image">
            <img src="assets/images/wheel.png" alt="Tuk-Tuk">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('tuk-tuk-tour')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <!-- <h3>Galle to Mirissa Tuk-Tuk</h3> -->
              <div class="transport-type">
                <span class="type-icon">🛺</span>
                <span class="type-text">Tuk-Tuk</span>
              </div>
            </div>
            <!-- <p class="route">📍 Galle → Mirissa</p> -->
            <p class="description">Authentic Sri Lankan experience with coastal views and local interactions</p>
            <div class="card-features">
              <span class="feature">🌊 Coastal Route</span>
              <span class="feature">🏛️ Cultural Sites</span>
              <span class="feature">📸 Photo Stops</span>
              <span class="feature">🗣️ Local Guide</span>
            </div>
            <div class="transport-info">
              <!-- <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">45 mins</span>
              </div> -->
              <div class="info-item">
                <span class="info-label">Capacity:</span>
                <span class="info-value">3 passengers</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.150</span>
                <span class="price-period">/ 1km</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('tuk-tuk-tour')">View Details</button>
            </div>
          </div>
        </div>

        <!-- Boat Transfer -->
        <!-- <div class="transport-card" data-type="boat" data-route="colombo-galle" data-price="premium" data-duration="medium">
          <div class="card-image">
            <img src="assets/images/boat-transfer.jpg" alt="Boat Transfer">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('boat-transfer')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Coastal Boat Transfer</h3>
              <div class="transport-type">
                <span class="type-icon">🛥️</span>
                <span class="type-text">Boat</span>
              </div>
            </div>
            <p class="route">📍 Colombo Port → Galle Harbor</p>
            <p class="description">Scenic coastal journey with dolphin spotting opportunities and ocean breeze</p>
            <div class="card-features">
              <span class="feature">🐬 Dolphin Watching</span>
              <span class="feature">🌊 Ocean Views</span>
              <span class="feature">☀️ Sunset Tours</span>
              <span class="feature">🍹 Refreshments</span>
            </div>
            <div class="transport-info">
              <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">4 hours</span>
              </div>
              <div class="info-item">
                <span class="info-label">Schedule:</span>
                <span class="info-value">Twice daily</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.4500</span>
                <span class="price-period">/ person</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('boat-transfer')">View Details</button>
            </div>
          </div>
        </div> -->

        <!-- Domestic Flight -->
        <!-- <div class="transport-card" data-type="plane" data-route="colombo-jaffna" data-price="premium" data-duration="short">
          <div class="card-image">
            <img src="assets/images/domestic-flight.jpg" alt="Domestic Flight">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('domestic-flight')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Colombo to Jaffna Flight</h3>
              <div class="transport-type">
                <span class="type-icon">✈️</span>
                <span class="type-text">Flight</span>
              </div>
            </div>
            <p class="route">📍 Colombo → Jaffna</p>
            <p class="description">Quick and comfortable air travel to reach the northern peninsula in record time</p>
            <div class="card-features">
              <span class="feature">⚡ Fastest Option</span>
              <span class="feature">🛫 Modern Aircraft</span>
              <span class="feature">🧳 Baggage Included</span>
              <span class="feature">☁️ Aerial Views</span>
            </div>
            <div class="transport-info">
              <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">1.5 hours</span>
              </div>
              <div class="info-item">
                <span class="info-label">Frequency:</span>
                <span class="info-value">Daily flights</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.12000</span>
                <span class="price-period">/ person</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('domestic-flight')">View Details</button>
            </div>
          </div>
        </div> -->

      </div>

      <!-- <div class="load-more-section">
        <button class="btn-secondary load-more-btn" onclick="loadMoreTransportation()">Load More Options</button>
      </div> -->
    </div>
  </section>

  <!-- Booking Modal -->
  <div class="modal" id="bookingModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Book Transportation</h3>
        <button class="close-btn" onclick="closeBookingModal()">&times;</button>
      </div>
      <div class="modal-body">
        <form class="booking-form">
          <div class="form-group">
            <label>Transportation Option</label>
            <input type="text" id="selectedTransport" readonly>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Travel Date</label>
              <input type="date" id="travelDate" required>
            </div>
            <div class="form-group">
              <label>Return Date (Optional)</label>
              <input type="date" id="returnDate">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Passengers</label>
              <select id="passengers">
                <option value="1">1 Passenger</option>
                <option value="2">2 Passengers</option>
                <option value="3">3 Passengers</option>
                <option value="4">4+ Passengers</option>
              </select>
            </div>
            <div class="form-group">
              <label>Departure Time</label>
              <select id="departureTime">
                <option value="">Select Time</option>
                <option value="morning">Morning (6AM - 12PM)</option>
                <option value="afternoon">Afternoon (12PM - 6PM)</option>
                <option value="evening">Evening (6PM - 10PM)</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Additional Requirements</label>
            <textarea id="additionalRequests" rows="3" placeholder="Any special requirements or preferences..."></textarea>
          </div>
          <button type="button" class="btn-primary full-width" onclick="submitTransportBooking()">Book Transportation</button>
        </form>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script src="assets/js/transportation.js"></script>
</body>
</html>