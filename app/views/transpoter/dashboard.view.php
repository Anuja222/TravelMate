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
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <span>4.5 (12 Reviews)</span>
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

    function calculateAverageRating(bookings) {
      if (!Array.isArray(bookings) || bookings.length === 0) {
        return 0;
      }

      const ratingFields = ['rating', 'provider_rating', 'booking_rating'];
      const values = [];

      bookings.forEach((booking) => {
        ratingFields.forEach((field) => {
          const value = Number(booking?.[field]);
          if (Number.isFinite(value) && value > 0) {
            values.push(value);
          }
        });
      });

      if (values.length === 0) {
        return 0;
      }

      const total = values.reduce((sum, current) => sum + current, 0);
      return total / values.length;
    }

    async function loadDashboardSummary() {
      try {
        const [vehicles, bookings] = await Promise.all([
          loadUserVehicles(),
          fetchProviderBookings()
        ]);

        const bookedCount = bookings.filter((booking) => String(booking.booking_status || '').toLowerCase() === 'confirmed').length;
        const avgRating = calculateAverageRating(bookings);

        setSummaryValues({
          listings: vehicles.length,
          booked: bookedCount,
          received: bookings.length,
          avgRating
        });
      } catch (error) {
        console.error('Error loading dashboard summary:', error);
        setSummaryValues({ listings: 0, booked: 0, received: 0, avgRating: 0 });
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
          <a href="DeleteVehicle?id=${vehicle.id}" class="vehicle-card-btn-delete">
            <i class="fas fa-trash"></i> Delete
          </a>
          <button type="button" class="${toggleBtnClass}" onclick="toggleVehicleStatus(${vehicle.id}, '${toggleNextStatus}')">
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

        await loadUserVehicles();
      } catch (error) {
        console.error('Error toggling vehicle status:', error);
        alert('Failed to update vehicle status. Please try again.');
      }
    }

    window.toggleVehicleStatus = toggleVehicleStatus;
  </script>
</body>

</html>