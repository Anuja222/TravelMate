<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Contact Us</title>
  <link rel="stylesheet" href="assets/css/contact.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- Header/Navbar -->
  <?php include __DIR__ . '../Traveller/header.view.php'; ?>

  <!-- Hero Section -->
  <section class="hero">
  <img src="assets/images/contact.jpg" alt="Contact Hero">
    <div class="hero-overlay"></div>
  <div class="hero-content">
      <h1>Contact Us</h1>
    </div>
  </section>

  <!-- Contact Info Section -->
  <section class="contact-info">
    <div class="contact-cards">
      <div class="contact-card">
        <div class="icon-wrapper">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
        </div>
        <h3>Phone</h3>
        <p>Call us 24/7 at</p>
        <p class="contact-detail">+94 11 434 4340</p>
      </div>

      <div class="contact-card">
        <div class="icon-wrapper">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
        </div>
        <h3>Address</h3>
        <p>Visit us at</p>
        <p class="contact-detail">123 Travel Street,<br>Colombo 03, Sri Lanka</p>
      </div>

      <div class="contact-card">
        <div class="icon-wrapper">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
        </div>
        <h3>Email</h3>
        <p>Send us an email at</p>
        <p class="contact-detail">info@travelmate.lk</p>
      </div>
    </div>
  </section>

  <!-- Contact Form Section -->
  <section class="contact-section">
    <div class="contact-container">
      <div class="form-section">
        <h2>Let Us Help You With Your Questions</h2>
        <form class="contact-form" id="contactForm">
          <div class="form-group">
            <input type="text" id="fullName" name="fullName" placeholder="Full Name" required>
          </div>
          <div class="form-group">
            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required>
          </div>
          <div class="form-group">
            <input type="email" id="email" name="email" placeholder="Email" required>
          </div>
          <div class="form-group">
            <input type="text" id="subject" name="subject" placeholder="Subject" required>
          </div>
          <div class="form-group">
            <textarea id="message" name="message" placeholder="Your message here..." rows="5" required></textarea>
          </div>
          <button type="submit" class="btn-submit">Send Message</button>
        </form>
      </div>

      <div class="image-section">
        <div class="contact-image">
          <div class="image-overlay">
            <h3>Don't Know Which Destination To Choose?</h3>
            <p>We Can Help you</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="map-section">
    <div class="map-container">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.798467598627!2d79.84759737570315!3d6.914712818606842!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259684e8d1c9b%3A0xa8c0b24b75e88e9a!2sColombo%2003%2C%20Colombo!5e0!3m2!1sen!2slk!4v1703123456789!5m2!1sen!2slk"
        width="100%" 
        height="400" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
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

  <script src="../public/assets/js/contact.js"></script>
</body>
</html>