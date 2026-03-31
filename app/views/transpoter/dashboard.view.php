<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$role = $isLoggedIn ? ($_SESSION['user']['role'] ?? $_SESSION['role'] ?? '') : '';

// Role-based redirect - this is a transport provider page
if (!$isLoggedIn || $role !== 'transport') {
    if ($role === 'admin') {
        header('Location: ad_dashboard');
        exit;
    } elseif ($role === 'accommodation') {
        header('Location: ac_dashboard');
        exit;
    } else {
        header('Location: homet');
        exit;
    }
}

$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate Dashboard</title>
  <link rel="stylesheet" href="assets/css/Transpoter/dashboard.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    /* Vehicle Card Delete Button Override */
    .vehicle-card-actions .vehicle-card-btn-delete {
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
      color: white !important;
      border: none !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 6px !important;
      min-height: 44px !important;
      padding: 10px 12px !important;
      border-radius: 8px !important;
      font-size: 14px !important;
      font-weight: 600 !important;
      cursor: pointer !important;
      text-decoration: none !important;
      transition: all 0.3s ease !important;
      box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2) !important;
      font-family: inherit !important;
      box-sizing: border-box !important;
    }
    
    .vehicle-card-actions .vehicle-card-btn-delete:hover {
      background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%) !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 16px rgba(231, 76, 60, 0.4) !important;
    }

    /* Status Toggle Modal Styles */
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

    /* Confirmation Modal Styles */
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

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideUpScale {
      from { transform: translateY(30px) scale(0.9); opacity: 0; }
      to { transform: translateY(0) scale(1); opacity: 1; }
    }
    @keyframes scaleIn {
      from { transform: scale(0) rotate(-180deg); }
      to { transform: scale(1) rotate(0deg); }
    }
    @keyframes checkmark {
      from { transform: scale(0) rotate(-180deg); opacity: 0; }
      to { transform: scale(1) rotate(0deg); opacity: 1; }
    }
    @keyframes fadeInUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    @keyframes ripple {
      0% { transform: translate(-50%, -50%) scale(1); opacity: 0.6; }
      100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
    }
  </style>
</head>

<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- MAIN CONTENT -->
  <main>
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <ul>
        <li><a href="tr_dashboard" class="active"><i ></i> Dashboard</a></li>
        <li><a href="bookingnew"><i></i> Bookings</a></li>
        <li><a href="setting"><i></i> Setting</a></li>
      </ul>
    </aside>

    <div class="dashboard-content">

      <!-- COVER -->
      <div class="cover">
        <img src="assets/trimages/travel1.jpg" class="cover-img" alt="cover">
      </div>

      <!-- PROFILE -->
      <section class="profile-section">
        <div class="profile-image-container">
          <img src="assets/trimages/profile.jpg" alt="profile" class="profile-pic">
          <span class="online-status"></span>
        </div>
        <div>
          <h2><?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?></h2>
          <span class="profile-email"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
          <div class="profile-rating">
            <span id="profileRatingStars" style="color:#f59e0b; display:inline-flex; gap:2px;">
              <i class="far fa-star"></i>
              <i class="far fa-star"></i>
              <i class="far fa-star"></i>
              <i class="far fa-star"></i>
              <i class="far fa-star"></i>
            </span>
            <span id="profileRatingText">Not yet rated</span>
          </div>
        </div>
      </section>

      <!-- PERFORMANCE SUMMARY -->
      <section class="activity-summary">
        <h3>Performance Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <div class="stat-icon">
              <i class="fas fa-car"></i>
            </div>
            <div class="stat-num" id="listingCount">0</div>
            <div class="stat-label">Listings</div>
          </div>
          <div class="stat">
            <div class="stat-icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-num" id="bookedCount">0</div>
            <div class="stat-label">Booked</div>
          </div>
          <div class="stat">
            <div class="stat-icon">
              <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-num" id="bookingsReceivedCount">0</div>
            <div class="stat-label">Bookings Received</div>
          </div>
          <div class="stat">
            <div class="stat-icon">
              <i class="fas fa-star"></i>
            </div>
            <div class="stat-num" id="averageRatingCount">0.0</div>
            <div class="stat-label">Average Rating</div>
          </div>
        </div>
      </section>

      <!-- VEHICLES -->
      <section class="favourites">
        <div class="section-header">
          <h3>My Vehicles</h3>
          <button class="btn-list-vehicle" onclick="window.location.href='vehicleType';">
            <i class="fas fa-plus"></i> List Your Vehicle
          </button>
        </div>

        <!-- <div class="my-vehicle-list"></div> -->

        <div class="vehicle-cards-grid">
          <!-- Cards will be loaded here dynamically -->
          <div class="loading-message" style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 10px;"></i>
            <p>Loading your vehicles...</p>
          </div>
        </div>
      </section>

    </div>
  </main>

  <!-- Status Toggle Modal -->
  <div class="status-modal-overlay" id="statusModal">
    <div class="status-modal">
      <div class="status-icon-circle" id="statusIconCircle">
        <i class="fas fa-check" id="statusIcon"></i>
      </div>
      <h2 id="statusModalTitle">Vehicle Activated Successfully!</h2>
      <p id="statusModalMessage">Your vehicle is now visible to travelers and can receive bookings.</p>
      <button class="status-modal-btn" onclick="closeStatusModal()">Got it</button>
    </div>
  </div>

  <!-- Delete Success Modal -->
  <div class="status-modal-overlay" id="deleteModal">
    <div class="status-modal">
      <div class="status-icon-circle" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
        <i class="fas fa-trash-alt" style="font-size: 50px; color: white; position: relative; z-index: 1;"></i>
      </div>
      <h2>Vehicle Deleted Successfully!</h2>
      <p>The vehicle has been permanently removed from your listings.</p>
      <button class="status-modal-btn" onclick="closeDeleteModal()">Got it</button>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="status-modal-overlay" id="confirmDeleteModal">
    <div class="confirm-modal">
      <div class="confirm-icon-circle">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h2>Delete Vehicle?</h2>
      <p>Are you sure you want to delete this vehicle? This action cannot be undone.</p>
      <div class="confirm-modal-buttons">
        <button class="confirm-modal-btn confirm-modal-btn-cancel" onclick="closeConfirmModal()">Cancel</button>
        <button class="confirm-modal-btn confirm-modal-btn-delete" onclick="confirmDelete()">Delete</button>
      </div>
    </div>
  </div>

  <!-- Deactivate Confirmation Modal -->
  <div class="status-modal-overlay" id="confirmDeactivateModal">
    <div class="confirm-modal">
      <div class="confirm-icon-circle">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h2>Deactivate Vehicle?</h2>
      <p id="confirmDeactivateMessage">Are you sure you want to deactivate this vehicle? It will be hidden from travellers.</p>
      <div class="confirm-modal-buttons">
        <button class="confirm-modal-btn confirm-modal-btn-cancel" onclick="closeConfirmDeactivateModal()">Cancel</button>
        <button class="confirm-modal-btn confirm-modal-btn-delete" onclick="confirmDeactivateVehicle()">Deactivate</button>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script src="../public/assets/js/vehicle.js"></script>

  <script>
    // Card hover effects
    const cards = document.querySelectorAll('.fav-card');
    cards.forEach(card => {
      card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-10px)';
        card.style.boxShadow = '0 15px 30px rgba(0,0,0,0.1)';
      });

      card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
        card.style.boxShadow = '0 2px 12px rgba(0,0,0,0.08)';
      });
    });

    function renderStars(ratingValue = 0) {
      let stars = '';
      for (let index = 1; index <= 5; index++) {
        if (ratingValue >= index) {
          stars += '<i class="fas fa-star"></i>';
        } else if (ratingValue >= index - 0.5) {
          stars += '<i class="fas fa-star-half-alt"></i>';
        } else {
          stars += '<i class="far fa-star"></i>';
        }
      }
      return stars;
    }

    function calculateOverallVehicleRating(vehicles) {
      if (!Array.isArray(vehicles) || vehicles.length === 0) {
        return { average: 0, totalRatings: 0 };
      }

      let weightedSum = 0;
      let totalRatings = 0;

      vehicles.forEach((vehicle) => {
        const avg = Number(vehicle?.avg_rating || 0);
        const count = parseInt(vehicle?.rating_count || 0, 10) || 0;

        if (Number.isFinite(avg) && count > 0) {
          weightedSum += avg * count;
          totalRatings += count;
        }
      });

      if (totalRatings === 0) {
        return { average: 0, totalRatings: 0 };
      }

      return {
        average: weightedSum / totalRatings,
        totalRatings
      };
    }

    function updateProfileRating(average = 0, totalRatings = 0) {
      const starsEl = document.getElementById('profileRatingStars');
      const textEl = document.getElementById('profileRatingText');

      if (starsEl) {
        starsEl.innerHTML = renderStars(average);
      }

      if (textEl) {
        textEl.textContent = totalRatings > 0
          ? `${average.toFixed(1)} (${totalRatings} Reviews)`
          : 'Not yet rated';
      }
    }

    function setSummaryValues({ listings = 0, booked = 0, received = 0, avgRating = 0 }) {
      const listingEl = document.getElementById('listingCount');
      const bookedEl = document.getElementById('bookedCount');
      const receivedEl = document.getElementById('bookingsReceivedCount');
      const ratingEl = document.getElementById('averageRatingCount');

      if (listingEl) listingEl.textContent = String(listings);
      if (bookedEl) bookedEl.textContent = String(booked);
      if (receivedEl) receivedEl.textContent = String(received);
      if (ratingEl) ratingEl.textContent = Number(avgRating || 0).toFixed(1);
    }

    async function fetchProviderBookings() {
      const response = await fetch('<?php echo '/TravelMate/public'; ?>/api/transport-booking/provider/all', {
        credentials: 'same-origin'
      });

      const result = await response.json();
      if (!result.success) {
        throw new Error(result.errors?.general || 'Failed to load provider bookings');
      }

      return result.data?.bookings || [];
    }

    async function loadDashboardSummary() {
      try {
        const [vehicles, bookings] = await Promise.all([
          loadUserVehicles(),
          fetchProviderBookings()
        ]);

        const bookedCount = bookings.filter((booking) => String(booking.booking_status || '').toLowerCase() === 'confirmed').length;
        const overallRating = calculateOverallVehicleRating(vehicles);
        updateProfileRating(overallRating.average, overallRating.totalRatings);

        setSummaryValues({
          listings: vehicles.length,
          booked: bookedCount,
          received: bookings.length,
          avgRating: overallRating.average
        });
      } catch (error) {
        console.error('Error loading dashboard summary:', error);
        setSummaryValues({ listings: 0, booked: 0, received: 0, avgRating: 0 });
        updateProfileRating(0, 0);
      }
    }

    // Fetch and display user vehicles
    async function loadUserVehicles() {
      try {
        const response = await fetch('<?php echo '/TravelMate/public'; ?>/api/vehicle/list', {
          credentials: 'same-origin'
        });

        const result = await response.json();

        if (result.success && result.data && result.data.length > 0) {
          displayVehicles(result.data);
          return result.data;
        } else {
          const container = document.querySelector('.vehicle-cards-grid');
          if (container) {
            container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;"><p>No vehicles found. <a href="vehicleType" style="color: #1abc5b;">Add your first vehicle</a></p></div>';
          }
          return [];
        }
      } catch (error) {
        console.error('Error loading vehicles:', error);
        return [];
      }
    }

    function displayVehicles(vehicles) {
      const container = document.querySelector('.vehicle-cards-grid'); 
      if (!container) return;

      // Add the new class to container
      container.className = 'vehicle-cards-grid';
      container.innerHTML = '';

      vehicles.forEach(vehicle => {
        const card = createVehicleCard(vehicle);
        container.appendChild(card);
      });
    }

    function createVehicleCard(vehicle) {
      const card = document.createElement('div');
      card.className = 'vehicle-card-item';

      // Determine badge text and class based on status
      const status = (vehicle.status || 'active').toLowerCase();
      const badgeText = status === 'inactive' ? 'Inactive' : status === 'pending' ? 'Pending' : 'Active';
      const badgeClass = `vehicle-status-badge status-${status}`;
      const toggleNextStatus = status === 'active' ? 'inactive' : 'active';
      const toggleLabel = status === 'active' ? 'Deactivate' : 'Activate';
      const toggleBtnClass = `vehicle-card-btn-toggle ${status === 'active' ? 'active' : ''}`;

      // Get base URL for images
      const baseUrl = window.location.origin + '/TravelMate/public';

      // Get vehicle image
      let vehicleImage = 'assets/trimages/car.png';

      if (vehicle.main_image) {
        if (vehicle.main_image.startsWith('/')) {
          vehicleImage = baseUrl + vehicle.main_image;
        } else {
          vehicleImage = vehicle.main_image;
        }
      } else if (vehicle.documents && vehicle.documents.length > 0) {
        const photoDoc = vehicle.documents.find(doc => doc.doc_type === 'vehicle_photos');
        if (photoDoc && photoDoc.file_path) {
          if (photoDoc.file_path.startsWith('/')) {
            vehicleImage = baseUrl + photoDoc.file_path;
          } else {
            vehicleImage = photoDoc.file_path;
          }
        }
      }

      const vehicleTypeIcons = {
        'car': 'assets/trimages/car.png',
        'van': 'assets/trimages/van.jpg',
        'bus': 'assets/trimages/Bus.jpeg',
        'jeep': 'assets/trimages/jeepicon.webp',
        'tuk': 'assets/trimages/tukicon.jpg'
      };

      if (vehicle.main_image === null && vehicleTypeIcons[vehicle.vehicle_type]) {
        vehicleImage = vehicleTypeIcons[vehicle.vehicle_type];
      }

      const costPerKm = Number(vehicle.cost_per_km || 0);
      const formattedCost = costPerKm > 0 ? costPerKm.toFixed(2) : '0.00';
      const ratingCount = parseInt(vehicle.rating_count || 0, 10) || 0;
      const avgRatingValue = parseFloat(vehicle.avg_rating || 0);
      const ratingStarsHtml = renderStars(avgRatingValue);
      const ratingText = ratingCount > 0 ? `${avgRatingValue.toFixed(1)} (${ratingCount})` : 'Not yet rated';

      card.innerHTML = `
    <div class="vehicle-card-image-wrap">
      <div class="${badgeClass}">${badgeText}</div>
      <img src="${vehicleImage}" class="vehicle-card-image" alt="${vehicle.vehicle_type}" 
           onerror="this.onerror=null; this.src='assets/trimages/car.png';">
    </div>
    <div class="vehicle-card-content">
      <h4 class="vehicle-card-title">${vehicle.vehicle_model || 'Vehicle'}</h4>
      <div class="vehicle-card-location">
        <i class="fas fa-map-marker-alt"></i>
        <span>${vehicle.working_district || 'Sri Lanka'}</span>
      </div>
      <div class="vehicle-card-specs">
        <span><i class="fas fa-users"></i> ${vehicle.passenger_count || 'N/A'} seats</span>
        <span><i class="fas fa-snowflake"></i> ${vehicle.ac_type === 'ac' ? 'A/C' : 'Non-A/C'}</span>
      </div>
      <div class="vehicle-card-rating">
        <span style="color:#f59e0b; display:inline-flex; gap:2px;">${ratingStarsHtml}</span>
        <span>${ratingText}</span>
      </div>

      <div class="vehicle-card-footer">
        <div class="vehicle-card-price-row">
          <div class="vehicle-card-price">
            <div class="vehicle-card-price-amount">
              <span class="currency">LKR</span> ${formattedCost}
            </div>
            <div class="vehicle-card-price-label">per 1km</div>
          </div>
          <div class="vehicle-card-number">${vehicle.vehicle_number || 'N/A'}</div>
        </div>
        <div class="vehicle-card-actions">
          <a href="editVehicle?id=${vehicle.id}" class="vehicle-card-btn-edit">
            <i class="fas fa-edit"></i> Edit
          </a>
          <button type="button" class="vehicle-card-btn-delete" onclick="showConfirmDeleteModal('${vehicle.id}')">
            <i class="fas fa-trash"></i> Delete
          </button>
          <button type="button" class="${toggleBtnClass}" onclick="handleVehicleStatusToggle(${vehicle.id}, '${toggleNextStatus}', '${(vehicle.vehicle_model || 'this vehicle').replace(/'/g, "\\'")}')">
            <i class="fas fa-power-off"></i> ${toggleLabel}
          </button>
        </div>
      </div>
    </div>
  `;

      return card;
    }

    // Load summary + vehicles when page loads
    document.addEventListener('DOMContentLoaded', function () {
      loadDashboardSummary();
    });

    async function toggleVehicleStatus(vehicleId, nextStatus) {
      try {
        const formData = new FormData();
        formData.append('id', vehicleId);
        formData.append('status', nextStatus);

        const response = await fetch('<?php echo '/TravelMate/public'; ?>/api/vehicle/update', {
          method: 'POST',
          body: formData,
          credentials: 'same-origin'
        });

        const result = await response.json();
        if (!result.success) {
          const msg = result.errors && result.errors.error ? result.errors.error : 'Failed to update vehicle status';
          alert(msg);
          return;
        }

        await loadDashboardSummary();
        showStatusModal(nextStatus === 'active');
      } catch (error) {
        console.error('Error toggling vehicle status:', error);
        alert('Failed to update vehicle status. Please try again.');
      }
    }

    window.toggleVehicleStatus = toggleVehicleStatus;

    let pendingStatusChange = null;

    function handleVehicleStatusToggle(vehicleId, nextStatus, vehicleName) {
      if (nextStatus === 'inactive') {
        showConfirmDeactivateModal(vehicleId, nextStatus, vehicleName || 'this vehicle');
        return;
      }
      
      toggleVehicleStatus(vehicleId, nextStatus);
    }

    function showConfirmDeactivateModal(vehicleId, nextStatus, vehicleName) {
      pendingStatusChange = { vehicleId, nextStatus };
      const modal = document.getElementById('confirmDeactivateModal');
      const message = document.getElementById('confirmDeactivateMessage');

      if (message) {
        message.textContent = `Are you sure you want to deactivate ${vehicleName}? It will be hidden from travellers.`;
      }

      if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    }

    function closeConfirmDeactivateModal() {
      pendingStatusChange = null;
      const modal = document.getElementById('confirmDeactivateModal');
      if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
      }
    }

    async function confirmDeactivateVehicle() {
      if (!pendingStatusChange) {
        closeConfirmDeactivateModal();
        return;
      }

      const { vehicleId, nextStatus } = pendingStatusChange;
      const btn = document.querySelector('#confirmDeactivateModal .confirm-modal-btn-delete');
      if (btn) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deactivating...';
        btn.disabled = true;
      }

      closeConfirmDeactivateModal();
      await toggleVehicleStatus(vehicleId, nextStatus);
      
      if (btn) {
        btn.innerHTML = 'Deactivate';
        btn.disabled = false;
      }
    }

    // Status Modal Functions
    function showStatusModal(isActive) {
      const modal = document.getElementById('statusModal');
      const iconCircle = document.getElementById('statusIconCircle');
      const icon = document.getElementById('statusIcon');
      const title = document.getElementById('statusModalTitle');
      const message = document.getElementById('statusModalMessage');
      
      if (isActive) {
        iconCircle.className = 'status-icon-circle active-status';
        icon.className = 'fas fa-check';
        title.textContent = 'Vehicle Activated Successfully!';
        message.textContent = 'Your vehicle is now visible to travelers and can receive bookings.';
      } else {
        iconCircle.className = 'status-icon-circle inactive-status';
        icon.className = 'fas fa-power-off';
        title.textContent = 'Vehicle Deactivated Successfully!';
        message.textContent = 'Your vehicle is now hidden from travelers and will not receive new bookings.';
      }
      
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    
    function closeStatusModal() {
      const modal = document.getElementById('statusModal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
    
    // Delete Modal Functions
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
    
    // Confirmation Modal Functions
    let pendingDeleteId = null;
    
    function showConfirmDeleteModal(vehicleId) {
      pendingDeleteId = vehicleId;
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
        const btnDelete = document.querySelector('.confirm-modal-btn-delete');
        btnDelete.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        btnDelete.disabled = true;

        const fd = new FormData();
        fd.append('id', pendingDeleteId);
        
        fetch('<?php echo '/TravelMate/public'; ?>/api/vehicle/delete', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(data => {
            btnDelete.innerHTML = 'Delete';
            btnDelete.disabled = false;
            
            if (data.success) {
                closeConfirmModal();
                showDeleteModal();
                loadDashboardSummary();
            } else {
                alert('Failed to delete vehicle');
                closeConfirmModal();
            }
        })
        .catch(err => {
            btnDelete.innerHTML = 'Delete';
            btnDelete.disabled = false;
            alert('Failed to delete vehicle');
            closeConfirmModal();
        });
      }
    }
    
    // Close modal when clicking overlay
    const statusModalEl = document.getElementById('statusModal');
    if (statusModalEl) {
      statusModalEl.addEventListener('click', function(e) {
        if (e.target === this) {
          closeStatusModal();
        }
      });
    }

    const deleteModalEl = document.getElementById('deleteModal');
    if (deleteModalEl) {
      deleteModalEl.addEventListener('click', function(e) {
        if (e.target === this) {
          closeDeleteModal();
        }
      });
    }

    const confirmDeleteModalEl = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModalEl) {
      confirmDeleteModalEl.addEventListener('click', function(e) {
        if (e.target === this) {
          closeConfirmModal();
        }
      });
    }

    const confirmDeactivateModalEl = document.getElementById('confirmDeactivateModal');
    if (confirmDeactivateModalEl) {
      confirmDeactivateModalEl.addEventListener('click', function(e) {
        if (e.target === this) {
          closeConfirmDeactivateModal();
        }
      });
    }

    window.handleVehicleStatusToggle = handleVehicleStatusToggle;
    window.showConfirmDeactivateModal = showConfirmDeactivateModal;
    window.closeConfirmDeactivateModal = closeConfirmDeactivateModal;
    window.confirmDeactivateVehicle = confirmDeactivateVehicle;
  </script>
</body>

</html>