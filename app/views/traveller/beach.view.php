<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Popular Beaches in Sri Lanka</title>
  <link rel="stylesheet" href="assets/css/Traveller/beach.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
  
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-background">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Discover Paradise</h1>
          <p>Explore the most beautiful beaches Sri Lanka has to offer</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Beaches Section -->
  <section class="beaches-section">
    <div class="container">
      <div class="section-header">
        <h2>Popular Beaches in SriLanka</h2>
      </div>

      <div class="beaches-grid">
        <!-- Blue -->
        <div class="card" data-location="blue">
          <div class="card-image">
            <img src="assets/images/bluebeach.png" alt="Blue Beach">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreBeach('blue')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Blue Beach</h3>
            <p>Blue Beach (also called Blue Beach Island) is a small scenic beach/island located near Nilwella, between Dickwella and Tangalle on Sri Lanka’s southern coast.</p>
          </div>
        </div>
        <!-- Thalpe -->
        <div class="card" data-location="thalpe">
          <div class="card-image">
            <img src="assets/images/thalpe.png" alt="Thalpe Beach">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreBeach('thalpe')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Thalpe</h3>
            <p>The beach has unique rock pools carved into coral reefs which fill with ocean water. These make for calm shallow bathing areas. It’s relatively less crowded than nearby tourist hotspots, offering peace, golden sand, palm trees, and scenic views.</p>
          </div>
        </div>
        <!-- Jungle -->
        <div class="card" data-location="jungle">
          <div class="card-image">
            <img src="assets/images/jungle.png" alt="Jungle Beach">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreBeach('jungle')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Jungle Beach</h3>
            <p>A peaceful, relatively secluded cove near Unawatuna, surrounded by lush jungle and forest on the Rumassala hill side. Golden sand, clear turquoise waters, calm waves — good for swimming, especially for those who aren’t strong swimmers.</p>
          </div>
        </div>
        <!-- Hikkaduwa -->
        <div class="card" data-location="hikkaduwa">
          <div class="card-image">
            <img src="assets/images/hikkaduwa.png" alt="Hikkaduwa Beach">
            <div class="card-overlay">
              <a href="beachdetail" class="explore-btn" onclick="exploreBeach('hikkaduwa')">Explore</a>
            </div>
          </div>
          <div class="card-content">
            <h3>Hikkaduwa</h3>
            <p>Hikkaduwa Beach is a famous coastal destination on Sri Lanka’s southwest coast, known for its golden sand, clear blue waters, and vibrant coral reefs. It’s a popular spot for swimming, snorkeling, surfing, and watching sea turtles.</p>
          </div>
        </div>
        <!-- Mirissa -->
        <div class="card" data-location="mirissa">
          <div class="card-image">
            <img src="assets/images/mirissa.png" alt="Mirissa Beach">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreBeach('mirissa')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Mirissa Beach</h3>
            <p>The beach features golden sand fringed by coconut palms, calm turquoise waters, and a relaxed tropical vibe. It’s great for swimming, sunbathing, enjoying seafood by the shore, or watching dramatic sunsets.</p>
          </div>
        </div>
        <!-- Marble Beach -->
        <div class="card" data-location="marble">
          <div class="card-image">
            <img src="assets/images/marble.png" alt="Marble Beach">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreBeach('marble')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Marble Beach</h3>
            <p>The water is crystal clear, and often calm, making it good for swimming and snorkeling. It’s more peaceful and less commercial than many tourist beaches — good for relaxing, enjoying nature, quiet walks, particularly those seeking calm and beauty.</p>
          </div>
        </div>
        <!-- Nilaweli Beach -->
        <div class="card" data-location="nilaweli">
          <div class="card-image">
            <img src="assets/images/nilaweli.png" alt="Nilaweli Beach">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreBeach('nilaweli')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Nilaweli Beach</h3>
            <p>The beach is known for its soft white-sand shores, calm turquoise waters, and gentle slope into the sea — making it ideal for swimming, relaxing, and quiet beach walks.</p>
          </div>
        </div>
        <!-- Unawatuna Beach -->
        <div class="card" data-location="unawatuna">
          <div class="card-image">
            <img src="assets/images/unawatuna.png" alt="Unawatuna Beach">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreBeach('unawatuna')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Unawatuna Beach</h3>
            <p>Unawatuna also offers a lively yet laid-back vibe: beachfront restaurants, cafés, bars, and amenities are easily accessible. There are cultural points nearby, such as the Japanese Peace Pagoda, and hidden coves like Jungle Beach for a more tranquil experience.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Beach Details Modal -->
  <!-- <div class="modal" id="beachModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="modalTitle">Beach Details</h3>
        <button class="close-btn" onclick="closeBeachModal()">&times;</button>
      </div>
      <div class="modal-body">
        <div class="beach-details">
          <div class="beach-image">
            <img id="modalImage" src="" alt="Beach Image">
          </div>
          <div class="beach-info">
            <h4 id="modalBeachName">Beach Name</h4>
            <p id="modalDescription">Beach description will appear here...</p>
            <div class="beach-features">
              <h5>Features:</h5>
              <ul id="modalFeatures">
              </ul>
            </div>
            <div class="modal-actions">
              <button class="btn-primary" onclick="bookBeachTrip()">Book Trip</button>
              <button class="btn-secondary" onclick="getDirections()">Get Directions</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  <!-- Booking Modal -->
  <!-- <div class="modal" id="bookingModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Book Your Beach Trip</h3>
        <button class="close-btn" onclick="closeBookingModal()">&times;</button>
      </div>
      <div class="modal-body">
        <form class="booking-form">
          <div class="form-group">
            <label>Beach Destination</label>
            <select id="beachDestination">
              <option value="">Select beach</option>
              <option value="hikkaduwa">Hikkaduwa</option>
              <option value="tharipe">Tharipe</option>
              <option value="galle">Galle</option>
              <option value="trincomalee">Trincomalee</option>
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
          <div class="form-group">
            <label>Accommodation Type</label>
            <select id="accommodationType">
              <option value="hotel">Beach Hotel</option>
              <option value="resort">Beach Resort</option>
              <option value="villa">Beach Villa</option>
              <option value="guesthouse">Beachside Guesthouse</option>
            </select>
          </div>
          <button type="button" class="btn-primary full-width" onclick="submitBeachBooking()">Book Beach Trip</button>
        </form>
      </div>
    </div>
  </div> -->

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script src="assets/js/beaches.js"></script>
</body>
</html>