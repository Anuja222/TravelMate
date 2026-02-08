<?php
// Detect admin session
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Home</title>
  <?php require_once __DIR__ . '/../core/config.php'; ?>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/home.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/main.css">
  <?php if ($_isAdmin): ?>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <?php endif; ?>
</head>
<body>
  <!-- Header/Navbar -->
  <?php if ($_isAdmin): ?>
    <?php include __DIR__ . '/admin/admin_header.view.php'; ?>
  <?php else: ?>
    <?php include __DIR__ . '/traveller/header.view.php'; ?>
  <?php endif; ?>

  <?php if ($_isAdmin): ?>
  <div class="page-container">
    <?php include __DIR__ . '/admin/sidebar.view.php'; ?>
    <div class="content">
  <?php endif; ?>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <img src="<?= ROOT ?>/assets/images/home1.jpg" alt="Home Hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <h1>Explore The World,<br><span>Plan Your Adventure</span><br>Today!</h1>
      <p>
        Discover the wonders of Sri Lanka with our comprehensive travel planning platform. From ancient temples to pristine beaches, create your perfect journey.
      </p>
      <div class="search-box">
        <input type="text" placeholder="Where to?">
        <input type="date" placeholder="Check In">
        <input type="date" placeholder="Check Out">
        <input type="number" min="1" placeholder="Guests">
      </div>
      <div> <button class="btn-search">Search & Explore</button></div>
      <div class="hero-stats">
        <div><strong>1000+</strong><br>Destinations</div>
        <div><strong>50K+</strong><br>Happy Travelers</div>
        <div><strong>24/7</strong><br>Support</div>
      </div>
    </div>
  </section>

  <!-- Popular Destinations -->
  <section class="destinations">
    <h2>Popular Destinations</h2>
    <p>From ancient temples and royal cities going to beautiful, diverse experiences.<br>Let's explore our amazing country in a new way.</p>
    <div class="destination-cards">
        <div class="card">
          <span class="tag">Nature</span>
          <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80" alt="Sigiriya">
          <div class="card-info">
            <h3>Sigiriya</h3>
            <p>Ancient rock fortress</p>
          </div>
        </div>
        <div class="card">
          <span class="tag">City</span>
          <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=400&q=80" alt="Kandy">
          <div class="card-info">
            <h3>Kandy</h3>
            <p>Historic city</p>
          </div>
        </div>
        <div class="card">
          <span class="tag">Beach</span>
          <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=400&q=80" alt="Galle">
          <div class="card-info">
            <h3>Galle</h3>
            <p>Coastal city</p>
          </div>
        </div>
        </div>
        <div class="destination-cards">
        <div class="card">
          <span class="tag">Culture</span>
          <img src="https://images.unsplash.com/photo-1548786817-3d9d07e8e8a4?auto=format&fit=crop&w=400&q=80" alt="Anuradhapura">
          <div class="card-info">
            <h3>Anuradhapura</h3>
            <p>Ancient city</p>
          </div>
        </div>
        <div class="card">
          <span class="tag">Nature</span>
          <img src="https://images.unsplash.com/photo-1502086223501-7ea6ecd79368?auto=format&fit=crop&w=400&q=80" alt="Horton Plains">
          <div class="card-info">
            <h3>Horton Plains</h3>
            <p>National Park</p>
          </div>
        </div>
        <div class="card">
          <span class="tag">Culture</span>
          <img src="https://images.unsplash.com/photo-1526481280690-3bfa7568e8b7?auto=format&fit=crop&w=400&q=80" alt="Jaffna">
          <div class="card-info">
            <h3>Jaffna</h3>
            <p>Historical town</p>
          </div>
        </div> 
    </div>
    <div>
      <button class="view-all">View All Destinations</button>
    </div>
  </section>

  <!-- Empower Your Business -->
  <section class="empower-business">
    <h2>Empower Your <span>Business</span></h2>
    <p>
      Discover a world of possibilities and connect with travel businesses and vendors who make your adventures unforgettable. Join our platform and experience the difference.
    </p>
    <div class="empower-actions">
      <button class="btn">Start Planning</button>
      <button class="btn learn">Learn More</button>
    </div>
    <div class="business-features">
      <div class="feature">
        <h4>Smart Itinerary Generator</h4>
        <p>Create custom itineraries based on traveler interests, helping them explore Sri Lanka like never before.</p>
      </div>
      <div class="feature">
        <h4>Flexible Scheduling</h4>
        <p>Offer seamless scheduling options for travel plans and bookings.</p>
      </div>
      <div class="feature">
        <h4>Group Planning</h4>
        <p>Enable users to plan travels with family or friends by collaborating on shared itineraries.</p>
      </div>
      </div>
      <div class="business-features">
      <div class="feature">
        <h4>Local Experiences</h4>
        <p>Discover hidden gems and authentic experiences curated by local vendors.</p>
      </div>
      <div class="feature">
        <h4>Real-time Updates</h4>
        <p>Stay updated on the latest events, weather, and travel conditions for your destination.</p>
      </div>
      <div class="feature">
        <h4>Memory Keeper</h4>
        <p>Capture and share travel stories with photos, reviews, and more in an interactive journal.</p>
      </div>
    </div>
    <div class="empower-cta">
      <p>Ready to Plan Your Next Adventure?</p>
      <span>Join thousands of travelers who have discovered top tips & hassle-free trip planning with TravelMate's smart platform.</span>
      <button class="btn">Get Started for Free</button>
      <button class="btn demo">Watch Demo</button>
    </div>
  </section>

  <!-- Things To Do -->
  <section class="things-to-do">
    <h2>Things To Do</h2>
    <div class="todo-cards">
      <div class="todo-card">
        <h4>Hiking</h4>
        <ul>
          <li>Explore trails and climb mountain peaks</li>
          <li>Guided tours</li>
          <li>Adventure packages</li>
        </ul>
      </div>
      <div class="todo-card">
        <h4>Surfing</h4>
        <ul>
          <li>Ride the waves at pristine beaches</li>
          <li>Rent surfboards</li>
          <li>Learn to surf</li>
        </ul>
      </div>
      <div class="todo-card">
        <h4>Night Ride</h4>
        <ul>
          <li>Explore the nightlife of top Lankan spots</li>
          <li>Travel safely</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Transport Section -->
  <section class="transport">
    <h3>Explore hassle free transport options to get you where you need to go.</h3>
    <p>
      Experience unlimited comfort and convenience of our top-rated transportation services that help get you anywhere at any time with the utmost luxury.
    </p>
    <div class="transport-features">
      <div>
        <strong>24/7 Availability</strong>
      </div>
      <div>
        <strong>Preferred Drivers</strong>
      </div>
      <div>
        <strong>GPS Tracking</strong>
      </div>
    </div>
    <button class="btn">Book Transport</button>
    <button class="btn learn">Learn More</button>
  </section>

    <!-- Why Choose Us Section -->
  <section class="why-choose-section" id="aboutUs">
    <div class="why-choose-container">
      <div class="image-section">
        <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80" alt="Palm trees and tropical beach" class="feature-image">
      </div>
      <div class="content-section">
        <h2>Why Choose Us?</h2>
        <ul class="features-list">
          <li>
            <div class="feature-point">
              <span class="bullet"></span>
              <span>Easy-to-use platform with no hidden fees</span>
            </div>
          </li>
          <li>
            <div class="feature-point">
              <span class="bullet"></span>
              <span>Handpicked, verified partners for quality and safety</span>
            </div>
          </li>
          <li>
            <div class="feature-point">
              <span class="bullet"></span>
              <span>Smart itinerary recommendations based on your preferences</span>
            </div>
          </li>
          <li>
            <div class="feature-point">
              <span class="bullet"></span>
              <span>Local insights to help you travel like a Sri Lankan</span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <div class="footer-section company">
        <h4>TravelMate</h4>
        <p>Your trusted partner for exploring Sri Lanka. Create memories that last a lifetime.</p>
      </div>
      <div class="footer-section links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Help Center</a></li>
        </ul>
      </div>
      <div class="footer-section support">
        <h4>Support</h4>
        <ul>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Live Chat</a></li>
        </ul>
      </div>
      <div class="footer-section connect">
        <h4>Contact Info</h4>
        <p>+94 11 434 4340<br>info@travelmate.lk</p>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; 2024 TravelMate Sri Lanka. All rights reserved.</span>
    </div>
  </footer>

  <script src="script.js"></script>

  <?php if ($_isAdmin): ?>
    </div><!-- /.content -->
  </div><!-- /.page-container -->
  <?php endif; ?>
</body>
</html>