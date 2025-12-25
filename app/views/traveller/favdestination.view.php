<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Explore Sri Lanka</title>
  <link rel="stylesheet" href="assets/css/Traveller/favdestination.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
  
</head>
<body>
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <!-- <img src="assets/images/cover.jpg" alt="About Hero"> -->
    <div class="hero-background">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Discover Sri Lanka's Hidden Treasures</h1>
          <p>Embark on an unforgettable journey through the pearl of the Indian Ocean</p>
          <!-- <div class="hero-actions">
            <button class="btn-primary" onclick="scrollToDestinations()">Explore Destinations</button>
            <button class="btn-secondary" onclick="openBookingModal()">Book Your Trip</button>
          </div> -->
        </div>
      </div>
    </div>
  </section>

  <!-- Destinations Section -->
  <section class="destinations-section">
    <div class="container">
      <div class="section-header">
        <h2>Explore Popular Destinations</h2>
        <p>From pristine beaches to ancient temples, discover what makes Sri Lanka magical</p>
      </div>

      <div class="destinations-grid">
        <!-- Beach Side -->
        <div class="card" data-category="beach">
          <div class="card-image">
            <img src="assets/images/beach.png" alt="Beach Side">
            <div class="card-overlay">
              <a href="beach" class="explore-btn">Explore</a>
            </div>
          </div>
          <div class="card-content">
            <h3>Beach Side</h3>
            <p>Golden sands, turquoise waves, and sunsets that feel like pure magic — the beachside is where serenity meets adventure.</p>
          </div>
        </div>
        <!-- Country Side -->
        <div class="card" data-category="country">
          <div class="card-image">
            <img src="assets/images/country.png" alt="Country Side">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreDestination('country')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Country Side</h3>
            <p>Rolling fields, fresh air, and timeless charm — the countryside is where nature’s simplicity meets peaceful living.</p>
          </div>
        </div>
        <!-- Historical -->
        <div class="card" data-category="historical">
          <div class="card-image">
            <img src="assets/images/historical.png" alt="Historical">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreDestination('historical')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Historical Side</h3>
            <p>Ancient streets, grand monuments, and stories etched in stone — the historical side lets you walk through the pages of time.</p>
          </div>
        </div>
        <!-- Hill Side -->
        <div class="card" data-category="hill">
          <div class="card-image">
            <img src="assets/images/hill.png" alt="Hill Side">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreDestination('hill')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Hill Side</h3>
            <p>Misty peaks, winding trails, and breathtaking views — the hillside is where adventure rises with the clouds.</p>
          </div>
        </div>
        <!-- Mountains -->
        <div class="card" data-category="mountain">
          <div class="card-image">
            <img src="assets/images/mountain.png" alt="Mountains">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreDestination('mountain')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Mountains</h3>
            <p>Sri Lanka’s mountains are known for their cool climate, lush greenery, and breathtaking scenery, forming the heart of the island’s natural beauty.</p>
</p>
          </div>
        </div>
        <!-- Cultural -->
        <div class="card" data-category="cultural">
          <div class="card-image">
            <img src="assets/images/cultural.png" alt="Cultural">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreDestination('cultural')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Cultural</h3>
            <p>Sri Lanka’s cultural side is rich with ancient traditions, vibrant festivals, and historic temples reflecting a blend of Buddhist, Hindu, and colonial influences.</p>
          </div>
        </div>
        <!-- City Tours -->
        <div class="card" data-category="city">
          <div class="card-image">
            <img src="assets/images/citytour.png" alt="City Tours">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreDestination('city')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>City Tours</h3>
            <p>City tours in Sri Lanka offer a mix of history, culture, and modern life, featuring bustling markets, colonial landmarks, temples, and vibrant local experiences.</p>
          </div>
        </div>
        <!-- Forests -->
        <div class="card" data-category="forest">
          <div class="card-image">
            <img src="assets/images/forest.png" alt="Forests">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreDestination('forest')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Forests</h3>
            <p>Sri Lanka’s forests are lush and diverse, home to rich wildlife, cascading waterfalls, and serene nature trails perfect for exploration and wildlife spotting.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Booking Modal -->
  <div class="modal" id="bookingModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Book Your Sri Lankan Adventure</h3>
        <button class="close-btn" onclick="closeBookingModal()">&times;</button>
      </div>
      <div class="modal-body">
        <form class="booking-form">
          <div class="form-group">
            <label>Destination Type</label>
            <select id="destinationType">
              <option value="">Select destination type</option>
              <option value="beach">Beach Side</option>
              <option value="country">Country Side</option>
              <option value="hill">Hill Side</option>
              <option value="mountain">Mountains</option>
              <option value="cultural">Cultural</option>
              <option value="historical">Historical</option>
              <option value="city">City Tours</option>
              <option value="forest">Forests</option>
              <option value="waterfall">Waterfalls</option>
              <option value="rural">Rural</option>
              <option value="island">Island</option>
              <option value="dryland">Drylands</option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Check-in Date</label>
              <input type="date" id="checkinDate">
            </div>
            <div class="form-group">
              <label>Check-out Date</label>
              <input type="date" id="checkoutDate">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Adults</label>
              <select id="adults">
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
          <button type="button" class="btn-primary full-width" onclick="submitBooking()">Search Available Tours</button>
        </form>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <!-- <script src="assets/js/favdestination.js"></script> -->
</body>
</html>