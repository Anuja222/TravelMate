<?php
// start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$role = $isLoggedIn ? ($_SESSION['user']['role'] ?? $_SESSION['role'] ?? '') : '';

// role-based redirect - this is an accommodation provider page
if (!$isLoggedIn || $role !== 'accommodation') {
    if ($role === 'admin') {
        header('Location: ad_dashboard');
        exit;
    } elseif ($role === 'transport') {
        header('Location: tr_dashboard');
        exit;
    } else {
        header('Location: homet');
        exit;
    }
}

$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
$rootUrl = defined('ROOT') ? ROOT : '/TravelMate/public';
$profileImage = ($isLoggedIn && !empty($_SESSION['user']['profile_image'])) ? $rootUrl . '/' . $_SESSION['user']['profile_image'] : $rootUrl . '/assets/images/profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TravelMate - Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/dashboard.css">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/property-cards.css">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Traveller/usermain.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .property-cards-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      margin-top: 30px;
      padding: 10px;
    }
    
    .property-card {
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      position: relative;
    }
    
    .property-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    
    .property-card-image {
      width: 100%;
      height: 260px;
      object-fit: cover;
      position: relative;
    }
    
    .property-card-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    
    .property-card:hover .property-card-image img {
      transform: scale(1.05);
    }
    
    .property-card-badge {
      position: absolute;
      top: 16px;
      left: 16px;
      background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
      color: #fff;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: capitalize;
      box-shadow: 0 3px 10px rgba(26, 188, 91, 0.3);
    }
    
    .property-card-content {
      padding: 24px;
    }
    
    .property-card-title {
      font-size: 22px;
      font-weight: 700;
      color: #2c3e50;
      margin: 0 0 12px 0;
      line-height: 1.3;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .property-card-location {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #666;
      font-size: 14px;
      margin-bottom: 12px;
    }
    
    .property-card-location i {
      color: #1abc5b;
      font-size: 16px;
    }

    .property-card-location .fa-star {
      color: #f59e0b;
    }

    .property-card-rating {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #666;
      font-size: 13px;
      margin-bottom: 12px;
      font-weight: 600;
    }

    .property-card-rating .rating-stars {
      display: inline-flex;
      gap: 2px;
      color: #f59e0b;
      font-size: 14px;
      line-height: 1;
    }
    
    .property-card-description {
      font-size: 14px;
      color: #666;
      line-height: 1.6;
      margin-bottom: 16px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      min-height: 63px;
    }
    
    .property-card-footer {
      display: flex;
      flex-direction: column;
      gap: 16px;
      padding-top: 16px;
      border-top: 1px solid #e8e8e8;
    }
    
    .property-card-price-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .property-card-price {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    
    .property-card-price-amount {
      font-size: 24px;
      font-weight: 700;
      color: #1abc5b;
      display: flex;
      align-items: baseline;
      gap: 4px;
    }
    
    .property-card-price-amount .currency {
      font-size: 16px;
      font-weight: 600;
    }
    
    .property-card-price-label {
      font-size: 12px;
      color: #999;
    }
    
    .property-card-actions {
      display: grid;
      grid-template-columns: 1fr 1fr auto;
      gap: 10px;
      width: 100%;
    }
    
    .property-card-btn {
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .property-card-btn-edit {
      background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
      color: #fff;
      box-shadow: 0 4px 12px rgba(26, 188, 91, 0.2);
    }
    
    .property-card-btn-edit:hover {
      background: linear-gradient(135deg, #16a085 0%, #1abc5b 100%);
      box-shadow: 0 6px 16px rgba(26, 188, 91, 0.3);
      transform: translateY(-2px);
    }
    
    .property-card-btn-delete {
      background: #fff;
      color: #e74c3c;
      border: 2px solid #e74c3c;
      padding: 10px 16px;
    }
    
    .property-card-btn-delete:hover {
      background: #e74c3c;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
    }

    .stat-note {
      font-size: 12px;
      color: #666;
      margin-top: -2px;
      min-height: 16px;
    }
    
    .property-card-btn-toggle {
      background: #fff;
      color: #95a5a6;
      border: 2px solid #95a5a6;
      padding: 10px 16px;
      white-space: nowrap;
    }
    
    .property-card-btn-toggle.active {
      background: #fff;
      color: #1abc5b;
      border: 2px solid #1abc5b;
    }
    
    .property-card-btn-toggle:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .property-card-btn-toggle.active:hover {
      background: rgba(26, 188, 91, 0.1);
    }
    
    .property-card-status {
      position: absolute;
      top: 16px;
      right: 16px;
      background: rgba(255, 255, 255, 0.95);
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      color: #1abc5b;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .property-card-status.pending {
      color: #f39c12;
    }
    
    .property-card-status.inactive {
      color: #95a5a6;
    }
    
    .loading-message {
      grid-column: 1/-1;
      text-align: center;
      padding: 60px 20px;
      color: #666;
    }
    
    .loading-message i {
      font-size: 32px;
      margin-bottom: 16px;
      color: #1abc5b;
    }
    
    .loading-message p {
      font-size: 16px;
      margin: 0;
    }
    
    .no-properties-message {
      grid-column: 1/-1;
      text-align: center;
      padding: 60px 20px;
    }
    
    .no-properties-message i {
      font-size: 64px;
      color: #e8e8e8;
      margin-bottom: 20px;
    }
    
    .no-properties-message h3 {
      font-size: 24px;
      color: #2c3e50;
      margin: 0 0 10px 0;
    }
    
    .no-properties-message p {
      font-size: 16px;
      color: #666;
      margin: 0 0 24px 0;
    }
    
    .no-properties-message button {
      background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
      color: #fff;
      border: none;
      padding: 14px 32px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(26, 188, 91, 0.3);
    }
    
    .no-properties-message button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(26, 188, 91, 0.4);
    }
    
    @media (max-width: 1200px) {
      .property-cards-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    
    @media (max-width: 768px) {
      .property-cards-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }
      
      .property-card-title {
        font-size: 20px;
      }
      
      .property-card-actions {
        grid-template-columns: 1fr;
        width: 100%;
      }
      
      .property-card-btn {
        width: 100%;
        justify-content: center;
      }
    }

    /* status Toggle Modal Styles */
    .status-modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(5px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 10000;
      animation: fadeIn 0.3s ease;
    }

    .status-modal-overlay.active {
      display: flex;
    }

    .status-modal {
      background: white;
      border-radius: 20px;
      padding: 40px;
      max-width: 500px;
      width: 90%;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUpScale 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .status-icon-circle {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin: 0 auto 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      animation: scaleIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.2s both;
    }

    .status-icon-circle.active-status {
      background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
    }

    .status-icon-circle.inactive-status {
      background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    }

    .status-icon-circle::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      height: 100%;
      border-radius: 50%;
      animation: ripple 1.5s ease-out infinite;
    }

    .status-icon-circle.active-status::before {
      background: rgba(26, 188, 91, 0.3);
    }

    .status-icon-circle.inactive-status::before {
      background: rgba(149, 165, 166, 0.3);
    }

    .status-icon-circle i {
      font-size: 50px;
      color: white;
      position: relative;
      z-index: 1;
      animation: checkmark 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.4s both;
    }

    .status-modal h2 {
      font-size: 28px;
      font-weight: 700;
      color: #2c3e50;
      margin: 0 0 15px 0;
      animation: fadeInUp 0.5s ease 0.5s both;
    }

    .status-modal p {
      font-size: 16px;
      color: #666;
      margin: 0 0 30px 0;
      line-height: 1.6;
      animation: fadeInUp 0.5s ease 0.6s both;
    }

    .status-modal-btn {
      background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
      color: white;
      border: none;
      padding: 14px 40px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(26, 188, 91, 0.3);
      animation: fadeInUp 0.5s ease 0.7s both;
    }

    .status-modal-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(26, 188, 91, 0.4);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes slideUpScale {
      from {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes scaleIn {
      from {
        transform: scale(0) rotate(-180deg);
      }
      to {
        transform: scale(1) rotate(0deg);
      }
    }

    @keyframes checkmark {
      from {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
      }
      to {
        transform: scale(1) rotate(0deg);
        opacity: 1;
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes ripple {
      0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.6;
      }
      100% {
        transform: translate(-50%, -50%) scale(1.5);
        opacity: 0;
      }
    }

    /* confirmation Modal Styles */
    .confirm-modal {
      background: white;
      border-radius: 20px;
      padding: 40px;
      max-width: 450px;
      width: 90%;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUpScale 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .confirm-icon-circle {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin: 0 auto 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
      position: relative;
      animation: scaleIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.2s both;
    }

    .confirm-icon-circle::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background: rgba(243, 156, 18, 0.3);
      animation: ripple 1.5s ease-out infinite;
    }

    .confirm-icon-circle i {
      font-size: 50px;
      color: white;
      position: relative;
      z-index: 1;
      animation: checkmark 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.4s both;
    }

    .confirm-modal h2 {
      font-size: 26px;
      font-weight: 700;
      color: #2c3e50;
      margin: 0 0 15px 0;
      animation: fadeInUp 0.5s ease 0.5s both;
    }

    .confirm-modal p {
      font-size: 16px;
      color: #666;
      margin: 0 0 30px 0;
      line-height: 1.6;
      animation: fadeInUp 0.5s ease 0.6s both;
    }

    .confirm-modal-buttons {
      display: flex;
      gap: 12px;
      justify-content: center;
      animation: fadeInUp 0.5s ease 0.7s both;
    }

    .confirm-modal-btn {
      flex: 1;
      max-width: 150px;
      padding: 14px 30px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
    }

    .confirm-modal-btn-cancel {
      background: #95a5a6;
      color: white;
      box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
    }

    .confirm-modal-btn-cancel:hover {
      background: #7f8c8d;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(149, 165, 166, 0.4);
    }

    .confirm-modal-btn-delete {
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    .confirm-modal-btn-delete:hover {
      background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(231, 76, 60, 0.4);
    }
  </style>
</head>
<body>
  <!-- navbar -->
  <!-- <header>
    <nav class="navbar">
      <div class="logo-container">
        <img src="assets/images/logo.jpg" class="logo" alt="TravelMate Logo">
        <h2>TravelMate</h2>
      </div>
      <ul class="nav-links">
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact Us</a></li>
        <li><a href="#">Blog</a></li>
      </ul>
      <div class="nav-actions"> -->
        <!-- <i class="uil uil-bell" style="width:28px;height:28px;margin-right:12px;vertical-align:middle;cursor:pointer;"></i> -->
        <!-- <img src="assets/images/notification.png" class="notification-icon" alt="Notifications" style="width:28px;height:28px;margin-right:12px;vertical-align:middle;cursor:pointer;"> -->
        <!-- <img src="assets/images/profile.jpg" class="user-icon" alt="User Icon">
      </div>
    </nav>
  </header> -->

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <main>
    <?php 
    $active_page = 'dashboard';
    include __DIR__ . '/sidebar.view.php'; 
    ?>
    <section class="dashboard-content">
      <!-- cover -->
      <div class="cover">
  <img src="/TravelMate/public/assets/images/cover.jpg" alt="Travel Cover" class="cover-img">
        <span class="cover-text">TRAVEL <span class="cover-sub">more</span></span>
      </div>
      <!-- profile -->
      <div class="profile-section">
  <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User" class="profile-pic" onerror="this.src='/TravelMate/public/assets/images/profile.jpg'">
        <div>
          <h2><?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?></h2>
          <span class="profile-email"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
        </div>
      </div>
      <!-- activity Summary -->
      <div class="activity-summary">
        <h3>Activity Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <span class="stat-num" id="summaryListings">0</span>
            <span class="stat-label">Listings</span>
          </div>
          <div class="stat">
            <span class="stat-num" id="summaryBooked">0</span>
            <span class="stat-label">Booked</span>
          </div>
          <div class="stat">
            <span class="stat-num" id="summaryBookingsReceived">0</span>
            <span class="stat-label">Bookings Received</span>
          </div>
          <div class="stat">
            <span class="stat-num" id="summaryOverallRating">-</span>
            <span class="stat-label">Overall Rating</span>
            <span class="stat-note" id="summaryOverallRatingNote">Not yet rated</span>
          </div>
        </div>
      </div>

      <!-- favourites -->
     <section class="favourite">
        <div class="section-header">
          <h3>My Properties</h3>
          <button class="btn-list-property" onclick="window.location.href='/TravelMate/public/index.php?url=Accomodation_provider/propertyListingStep1';">
            <i class="fas fa-plus"></i> List Your Property
          </button>
        </div>

        <div class="property-cards-grid">
          <!-- property cards will be loaded here dynamically -->
          <div class="loading-message" style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 10px;"></i>
            <p>Loading your properties...</p>
          </div>
        </div>
      </section>
    </section>
  </main>
  <!-- status Toggle Modal -->
  <div class="status-modal-overlay" id="statusModal">
    <div class="status-modal">
      <div class="status-icon-circle" id="statusIconCircle">
        <i class="fas fa-check" id="statusIcon"></i>
      </div>
      <h2 id="statusModalTitle">Property Activated Successfully!</h2>
      <p id="statusModalMessage">Your property is now visible to travelers and can receive bookings.</p>
      <button class="status-modal-btn" onclick="closeStatusModal()">Got it</button>
    </div>
  </div>

  <!-- delete Success Modal -->
  <div class="status-modal-overlay" id="deleteModal">
    <div class="status-modal">
      <div class="status-icon-circle" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
        <i class="fas fa-trash-alt" style="font-size: 50px; color: white; position: relative; z-index: 1;"></i>
      </div>
      <h2>Property Deleted Successfully!</h2>
      <p>The property has been permanently removed from your listings.</p>
      <button class="status-modal-btn" onclick="closeDeleteModal()">Got it</button>
    </div>
  </div>

  <!-- delete Confirmation Modal -->
  <div class="status-modal-overlay" id="confirmDeleteModal">
    <div class="confirm-modal">
      <div class="confirm-icon-circle">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h2>Delete Property?</h2>
      <p>Are you sure you want to delete this property? This action cannot be undone.</p>
      <div class="confirm-modal-buttons">
        <button class="confirm-modal-btn confirm-modal-btn-cancel" onclick="closeConfirmModal()">Cancel</button>
        <button class="confirm-modal-btn confirm-modal-btn-delete" onclick="confirmDelete()">Delete</button>
      </div>
    </div>
  </div>

  <!-- footer -->
   <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
  <!-- <footer>
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
  </footer> -->
  <!-- dashboard scripts -->
  <script src="/TravelMate/public/assets/js/accommodation.js"></script>
  <script>
    // status Modal Functions
    function showStatusModal(isActive) {
      const modal = document.getElementById('statusModal');
      const iconCircle = document.getElementById('statusIconCircle');
      const icon = document.getElementById('statusIcon');
      const title = document.getElementById('statusModalTitle');
      const message = document.getElementById('statusModalMessage');
      
      if (isActive) {
        iconCircle.className = 'status-icon-circle active-status';
        icon.className = 'fas fa-check';
        title.textContent = 'Property Activated Successfully!';
        message.textContent = 'Your property is now visible to travelers and can receive bookings.';
      } else {
        iconCircle.className = 'status-icon-circle inactive-status';
        icon.className = 'fas fa-power-off';
        title.textContent = 'Property Deactivated Successfully!';
        message.textContent = 'Your property is now hidden from travelers and will not receive new bookings.';
      }
      
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    
    function closeStatusModal() {
      const modal = document.getElementById('statusModal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
    
    // delete Modal Functions
    function showDeleteModal() {
      const modal = document.getElementById('deleteModal');
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteModal() {
      const modal = document.getElementById('deleteModal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
    
    // confirmation Modal Functions
    let pendingDeleteId = null;
    
    function showConfirmDeleteModal(propertyId) {
      pendingDeleteId = propertyId;
      const modal = document.getElementById('confirmDeleteModal');
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    
    function closeConfirmModal() {
      pendingDeleteId = null;
      const modal = document.getElementById('confirmDeleteModal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
    
    function confirmDelete() {
      if (pendingDeleteId) {
        // trigger the actual delete by dispatching a custom event
        const event = new CustomEvent('confirmDeleteProperty', { detail: { id: pendingDeleteId } });
        document.dispatchEvent(event);
        closeConfirmModal();
      }
    }
    
    // close modal when clicking overlay
    document.getElementById('statusModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeStatusModal();
      }
    });
    
    document.getElementById('deleteModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeDeleteModal();
      }
    });
    
    document.getElementById('confirmDeleteModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeConfirmModal();
      }
    });
    
    // make functions globally available
    window.showStatusModal = showStatusModal;
    window.showDeleteModal = showDeleteModal;
    window.showConfirmDeleteModal = showConfirmDeleteModal;
    
    // ensure loader runs once script is available
    (function(){
      function tryRun(){
        if (window.loadUserProperties) {
          try { window.loadUserProperties(); } catch(e){ console.error('loadUserProperties error', e); }
        } else {
          setTimeout(tryRun, 200);
        }
      }
      tryRun();
    })();
  </script>
</body>
</html>