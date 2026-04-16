<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Explore Activities</title>
  <link rel="stylesheet" href="assets/css/Traveller/favactivity.css">
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
          <h1>Adventure Awaits You</h1>
          <p>Discover thrilling activities and create unforgettable memories in Sri Lanka</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Activities Section -->
  <section class="activities-section">
    <div class="container">
      <div class="section-header">
        <h2>Explore Popular Activities</h2>
        <p>From adrenaline-pumping adventures to peaceful nature experiences</p>
      </div>

      <div class="activities-grid">

        <!-- Surfing -->
        <div class="card" data-category="surfing">
          <div class="card-image">
            <img src="assets/images/surfing.png" alt="Surfing">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreActivity('surfing')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Surfing</h3>
            <p>Catch the waves, feel the rhythm of the ocean — surfing is where balance meets pure freedom.</p>
          </div>
        </div>

        <!-- Water Rafting -->
        <div class="card" data-category="water-rafting">
          <div class="card-image">
            <img src="assets/images/waterafting.png" alt="Water Rafting">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreActivity('water-rafting')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Water Rafting</h3>
            <p>Thrilling rapids, splashing waves, and pure adrenaline — water rafting is where adventure flows wild and free.</p>
          </div>
        </div>

        <!-- Bird Watching -->
        <div class="card" data-category="bird-watching">
          <div class="card-image">
            <img src="assets/images/birdwatching.png" alt="Bird Watching">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreActivity('bird-watching')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Bird watching</h3>
            <p>Gentle trails, quiet moments, and wings in flight — bird watching is nature’s calmest spectacle.</p>
          </div>
        </div>

        <!-- Safari -->
        <div class="card" data-category="safari">
          <div class="card-image">
            <img src="assets/images/safari.png" alt="Safari">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreActivity('safari')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Safari</h3>
            <p>Golden plains, roaming wildlife, and untamed beauty — a safari is the closest you’ll get to nature’s wild heart.</p>
          </div>
        </div>

        <!-- Photography -->
        <div class="card" data-category="photography">
          <div class="card-image">
            <img src="assets/images/photography.png" alt="Photography">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreActivity('photography')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Photography</h3>
            <p>Photography is the art of capturing moments, light, and emotions through images, allowing creativity and storytelling through visual expression.</p>
          </div>
        </div>

        <!-- Shopping -->
        <div class="card" data-category="shopping">
          <div class="card-image">
            <img src="assets/images/shopping.png" alt="Shopping">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreActivity('shopping')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Shopping</h3>
            <p>Shopping is the activity of exploring and purchasing goods, from everyday essentials to unique items, often reflecting local culture and trends.</p>
          </div>
        </div>

        <!-- Hiking -->
        <div class="card" data-category="hiking">
          <div class="card-image">
            <img src="assets/images/hiking.png" alt="Hiking">
            <div class="card-overlay">
              <button class="explore-btn" onclick="exploreActivity('hiking')">Explore</button>
            </div>
          </div>
          <div class="card-content">
            <h3>Hiking</h3>
            <p>Hiking is an outdoor activity that involves walking through natural trails, mountains, or forests, combining exercise with exploration and adventure.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Booking Modal -->
  <!-- <div class="modal" id="activityModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Book Your Activity</h3>
        <button class="close-btn" onclick="closeActivityModal()">&times;</button>
      </div>
      <div class="modal-body">
        <form class="booking-form">
          <div class="form-group">
            <label>Activity Type</label>
            <select id="activityType">
              <option value="">Select activity type</option>
              <option value="water-rafting">Water Rafting</option>
              <option value="surfing">Surfing</option>
              <option value="bird-watching">Bird Watching</option>
              <option value="safari">Safari</option>
              <option value="photography">Photography</option>
              <option value="shopping">Shopping</option>
              <option value="hiking">Hiking</option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Date</label>
              <input type="date" id="activityDate">
            </div>
            <div class="form-group">
              <label>Time Slot</label>
              <select id="timeSlot">
                <option value="morning">Morning (6:00 AM - 12:00 PM)</option>
                <option value="afternoon">Afternoon (12:00 PM - 6:00 PM)</option>
                <option value="evening">Evening (6:00 PM - 10:00 PM)</option>
                <option value="full-day">Full Day</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Participants</label>
              <select id="participants">
                <option value="1">1 Person</option>
                <option value="2">2 People</option>
                <option value="3">3 People</option>
                <option value="4">4 People</option>
                <option value="5+">5+ People</option>
              </select>
            </div>
            <div class="form-group">
              <label>Experience Level</label>
              <select id="experienceLevel">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
                <option value="any">Any Level</option>
              </select>
            </div>
          </div>
          <button type="button" class="btn-primary full-width" onclick="submitActivityBooking()">Book Activity</button>
        </form>
      </div>
    </div>
  </div> -->

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script src="assets/js/activities.js"></script>
</body>
</html>