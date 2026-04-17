<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
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
        <div class="sidebar-inner">
          <div class="sidebar-menu">
            <a href="/TravelMate/public/tr_dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="/TravelMate/public/bookingnew"><i class="fas fa-calendar-alt"></i> Bookings</a>
            <a href="/TravelMate/public/payment-history"><i class="fas fa-credit-card"></i> Payment History</a>
            <a href="/TravelMate/public/statistics"><i class="fas fa-chart-line"></i> Statistics</a>
            <a href="/TravelMate/public/setting"><i class="fas fa-cog"></i> Settings</a>
          </div>
        </div>
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
            <span>4.5 (20 Reviews)</span>
          </div>
        </div>
      </section>

      <!-- Vehicles -->
      <section class="favourites">
        <div class="section-header">
          <h3>My Vehicles</h3>
          <button class="btn-list-vehicle" onclick="window.location.href='/TravelMate/public/vehicleType';">
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

      <!-- PERFORMANCE SUMMARY -->
      <section class="activity-summary">
        <h3>Performance Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <div class="stat-icon">
              <i class="fas fa-car"></i>
            </div>
            <div class="stat-num">4</div>
            <div class="stat-label">Listings</div>
          </div>
          <div class="stat">
            <div class="stat-icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-num">3</div>
            <div class="stat-label">Booked</div>
          </div>
          <div class="stat">
            <div class="stat-icon">
              <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-num">6</div>
            <div class="stat-label">Bookings Received</div>
          </div>
          <div class="stat">
            <div class="stat-icon">
              <i class="fas fa-star"></i>
            </div>
            <div class="stat-num">4.5</div>
            <div class="stat-label">Average Rating</div>
          </div>
        </div>
      </section>

    </div>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

    <script src="../public/assets/js/vehicle.js"></script>

  <script>
    // Performance stats animation
    document.addEventListener('DOMContentLoaded', function () {
      const stats = document.querySelectorAll('.stat-num');
      stats.forEach(stat => {
        const target = parseInt(stat.textContent);
        let count = 0;
        const duration = 2000; // in milliseconds
        const frameDuration = 1000 / 60; // 60 fps
        const totalFrames = Math.round(duration / frameDuration);
        const easeOutQuad = t => t * (2 - t);

        const counter = setInterval(() => {
          const progress = easeOutQuad(++count / totalFrames);
          const current = Math.round(target * progress);

          if (parseInt(stat.textContent) !== target) {
            stat.textContent = current;
          } else {
            clearInterval(counter);
          }
        }, frameDuration);
      });
    });

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

    // Fetch and display user vehicles
    async function loadUserVehicles() {
      try {
        const response = await fetch('<?php echo '/TravelMate/public'; ?>/api/vehicle/list', {
          credentials: 'same-origin'
        });

        const result = await response.json();
        console.log('Vehicle list response:', result); // Debug log

        // FIX: Check the correct response structure
        if (result.success) {
          // FIX: Access vehicles from data.vehicles (not data)
          const vehicles = result.data?.vehicles || [];
          
          if (vehicles.length > 0) {
            displayVehicles(vehicles);
          } else {
            const container = document.querySelector('.vehicle-cards-grid');
            if (container) {
              container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;"><p>No vehicles found. <a href="vehicleType" style="color: #1abc5b;">Add your first vehicle</a></p></div>';
            }
          }
          
          // Show account status message if account is deactivated
          if (result.data?.account_deactivated) {
            showAccountStatusMessage(result.data.message);
          }
        } else {
          console.error('Failed to load vehicles:', result.errors);
        }
      } catch (error) {
        console.error('Error loading vehicles:', error);
      }
    }

    function showAccountStatusMessage(message) {
      // Create and show a toast or banner message
      const container = document.querySelector('.vehicle-cards-grid');
      if (container && message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'account-status-message';
        messageDiv.style.cssText = 'grid-column: 1/-1; background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeeba;';
        messageDiv.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;
        container.parentNode.insertBefore(messageDiv, container);
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
      const badgeText = vehicle.status === 'active' ? 'Active' :
        vehicle.status === 'booked' ? 'Booked' : 'Available';
      const badgeClass = `vehicle-status-badge status-${vehicle.status || 'available'}`;

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

      card.innerHTML = `
    <div class="${badgeClass}">${badgeText}</div>
    <img src="${vehicleImage}" class="vehicle-card-image" alt="${vehicle.vehicle_type}" 
         onerror="this.onerror=null; this.src='assets/trimages/car.png';">
    <div class="vehicle-card-content">
      <h4 class="vehicle-card-title">${vehicle.vehicle_model || 'Vehicle'}</h4>
      <div class="vehicle-card-location">
        <i class="fas fa-map-marker-alt"></i> ${vehicle.working_district || 'Sri Lanka'}
      </div>
      <div class="vehicle-card-rating">★★★★☆ <span>(0 Reviews)</span></div>
      <div class="vehicle-card-specs">
        <span><i class="fas fa-users"></i> ${vehicle.passenger_count || 'N/A'} seats</span>
        <span><i class="fas fa-snowflake"></i> ${vehicle.ac_type === 'ac' ? 'A/C' : 'Non-A/C'}</span>
      </div>
      <div class="vehicle-card-number">${vehicle.vehicle_number || 'N/A'}</div>
      <div class="vehicle-card-actions">
        <a href="editVehicle?id=${vehicle.id}" class="vehicle-card-btn-edit">
          <i class="fas fa-edit"></i> Edit
        </a>
        <a href="DeleteVehicle?id=${vehicle.id}" class="vehicle-card-btn-delete">
          <i class="fas fa-trash"></i> Delete
        </a>
      </div>
    </div>
  `;

      return card;
    }

    // Load vehicles when page loads
    document.addEventListener('DOMContentLoaded', function () {
      loadUserVehicles();
    });
  </script>
</body>

</html>