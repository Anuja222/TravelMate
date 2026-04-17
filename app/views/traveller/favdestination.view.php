<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Explore Sri Lanka</title>
  <link rel="stylesheet" href="assets/css/Traveller/favdestination.css?v=2">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
  
</head>
<body>
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- hero Section -->
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

  <!-- destinations Section -->
  <section class="destinations-section">
    <div class="container">
      <div class="section-header">
        <h2>Explore Popular Destinations</h2>
        <p>From pristine beaches to ancient temples, discover what makes Sri Lanka magical</p>
      </div>

      <div class="destinations-grid">
        <?php if (!empty($destinations)): ?>
          <?php foreach ($destinations as $destination): ?>
            <!-- <?= htmlspecialchars($destination->title) ?> -->
            <div class="card" data-category="<?= htmlspecialchars(strtolower($destination->slug)) ?>">
              <div class="card-image">
                <img src="<?= htmlspecialchars(ltrim($destination->image, '/')) ?>" alt="<?= htmlspecialchars($destination->title) ?>">
                <div class="card-overlay">
                  <a href="destinationview?id=<?= $destination->id ?>" class="explore-btn">Explore</a>
                </div>
              </div>
              <div class="card-content">
                <h3><?= htmlspecialchars($destination->title) ?></h3>
                <p><?= htmlspecialchars($destination->description) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
            <p style="font-size: 18px; color: #666;">No destinations available at the moment.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- booking Modal -->
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