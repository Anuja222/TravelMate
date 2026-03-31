<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Transportation</title>
  <link rel="stylesheet" href="assets/css/Traveller/transport.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Fix grid layout for single items */
    .transportation-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }
    
    .transport-card {
      width: 100%;
      max-width: 100%;
      border-radius: 16px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      flex-direction: column;
      min-height: 500px;
      overflow: hidden;
      background: #fff;
    }

    .transport-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .transport-card .card-image {
      height: 240px;
      position: relative;
      overflow: hidden;
    }

    .transport-card .card-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .transport-card:hover .card-image img {
      transform: scale(1.05);
    }

    .transport-card .card-status-badge {
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
      z-index: 2;
    }

    .transport-card .card-status-badge.pending {
      background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
      box-shadow: 0 3px 10px rgba(243, 156, 18, 0.3);
    }

    .transport-card .card-status-badge.inactive {
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
      box-shadow: 0 3px 10px rgba(231, 76, 60, 0.3);
    }

    .transport-card .card-content {
      padding: 22px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      flex: 1;
    }

    .transport-card .card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 0;
      gap: 10px;
    }

    .transport-card .card-content h3 {
      margin: 0;
      font-size: 22px;
      font-weight: 700;
      color: #2c3e50;
      line-height: 1.2;
      min-height: 52px;
    }

    .transport-card .route {
      margin: 0;
      font-size: 14px;
      color: #666;
      min-height: 22px;
    }

    .transport-card .description {
      margin: 0;
      min-height: 20px;
      color: #666;
    }

    .transport-card .card-rating {
      margin: 0;
      min-height: 22px;
      font-size: 13px;
      color: #6b7280;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .transport-card .card-rating .rating-stars {
      color: #f59e0b;
      display: inline-flex;
      gap: 2px;
      line-height: 1;
    }

    .transport-card .card-features {
      margin: 0;
      min-height: 24px;
    }

    .transport-card .feature {
      background: #f1f5f9;
      color: #475569;
      border-radius: 999px;
      padding: 0.3em 0.7em;
    }

    .transport-card .card-footer {
      margin-top: auto;
      border-top: 1px solid #e8e8e8;
      padding-top: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .transport-card .price {
      display: flex;
      flex-direction: column;
      gap: 4px;
      margin: 0;
    }

    .transport-card .price-amount {
      font-size: 24px;
      font-weight: 700;
      color: #1abc5b;
      line-height: 1;
    }

    .transport-card .price-period {
      font-size: 12px;
      color: #999;
      margin: 0;
    }

    .transport-card .view-btn {
      background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      padding: 11px 20px;
      min-width: 140px;
      box-shadow: 0 4px 12px rgba(26, 188, 91, 0.2);
    }

    .transport-card .view-btn:hover {
      background: linear-gradient(135deg, #16a085 0%, #1abc5b 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(26, 188, 91, 0.3);
    }
  </style>
</head>
<body>
  <!-- Header/Navbar -->
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-background">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Explore Transportation Options</h1>
          <p>From scenic train rides to comfortable bus journeys, discover the best ways to travel around Sri Lanka</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Filter Section -->
  <section class="filter-section">
    <div class="container">
      <div class="filter-bar">
        <div class="filter-group">
          <label for="route">Route</label>
          <select id="route">
            <option value="">All Routes</option>
            <option value="colombo-kandy">Colombo - Kandy</option>
            <option value="colombo-galle">Colombo - Galle</option>
            <option value="kandy-ella">Kandy - Ella</option>
            <option value="colombo-nuwara">Colombo - Nuwara Eliya</option>
            <option value="galle-mirissa">Galle - Mirissa</option>
            <option value="anuradhapura-polonnaruwa">Anuradhapura - Polonnaruwa</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="transport-type">Transport Type</label>
          <select id="transport-type">
            <option value="">All Types</option>
            <option value="train">Train</option>
            <option value="bus">Bus</option>
            <option value="taxi">Taxi/Car</option>
            <option value="tuk-tuk">Tuk-Tuk</option>
            <option value="boat">Boat</option>
            <option value="plane">Domestic Flight</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="price-range">Price Range</label>
          <select id="price-range">
            <option value="">All Prices</option>
            <option value="budget">Budget (Under Rs.500)</option>
            <option value="mid">Mid-range (Rs.500-2000)</option>
            <option value="premium">Premium (Rs.2000+)</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="duration">Duration</label>
          <select id="duration">
            <option value="">All Durations</option>
            <option value="short">Under 2 hours</option>
            <option value="medium">2-5 hours</option>
            <option value="long">5+ hours</option>
          </select>
        </div>
        <button class="btn filter-btn" onclick="applyFilters()">Filter Results</button>
      </div>
    </div>
  </section>

  <!-- Transportation Section -->
  <section class="transportation-section">
    <div class="container">
      <div class="transportation-grid" id="transportationGrid">
        
        <!-- Scenic Train -->
        <!-- <div class="transport-card" data-type="train" data-route="kandy-ella" data-price="budget" data-duration="long">
          <div class="card-image">
            <img src="assets/images/scenic-train.jpg" alt="Scenic Train Journey">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('scenic-train')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Kandy to Ella Train</h3>
              <div class="transport-type">
                <span class="type-icon">🚂</span>
                <span class="type-text">Train</span>
              </div>
            </div>
            <p class="route">📍 Kandy → Ella</p>
            <p class="description">One of the world's most beautiful train journeys through misty mountains and tea plantations</p>
            <div class="card-features">
              <span class="feature">🏔️ Mountain Views</span>
              <span class="feature">🍃 Tea Plantations</span>
              <span class="feature">📸 Photo Stops</span>
              <span class="feature">❄️ Cool Climate</span>
            </div>
            <div class="transport-info">
              <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">7 hours</span>
              </div>
              <div class="info-item">
                <span class="info-label">Frequency:</span>
                <span class="info-value">3 times daily</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.180</span>
                <span class="price-period">/ person</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('scenic-train')">View Details</button>
            </div>
          </div>
        </div> -->

        <!-- Express Bus -->
        <div class="transport-card" data-type="bus" data-route="colombo-kandy" data-price="budget" data-duration="medium">
          <div class="card-image">
            <img src="assets/images/bus.png" alt="Express Bus">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('express-bus')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <!-- <h3>Colombo to Kandy Express</h3> -->
              <div class="transport-type">
                <span class="type-icon">🚌</span>
                <span class="type-text">Bus</span>
              </div>
            </div>
            <!-- <p class="route">📍 Colombo → Kandy</p> -->
            <p class="description">Comfortable air-conditioned bus service connecting the commercial and cultural capitals</p>
            <div class="card-features">
              <span class="feature">❄️ Air Conditioned</span>
              <span class="feature">💺 Comfortable Seats</span>
              <span class="feature">📱 WiFi</span>
              <span class="feature">🎬 Entertainment</span>
            </div>
            <div class="transport-info">
              <!-- <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">3.5 hours</span>
              </div> -->
              <div class="info-item">
                <span class="info-label">Frequency:</span>
                <span class="info-value">Every 30 mins</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.320</span>
                <span class="price-period">/ 1km</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('express-bus')">View Details</button>
            </div>
          </div>
        </div>

        <!-- Private Taxi -->
        <div class="transport-card" data-type="taxi" data-route="colombo-galle" data-price="premium" data-duration="medium">
          <div class="card-image">
            <img src="assets/images/car.png" alt="Private Taxi">
            <div class="card-overlay">
              <button class="book-btn" onclick="window.location.href='transportdetails';">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <!-- <h3>Colombo to Galle Private Car</h3> -->
              <div class="transport-type">
                <span class="type-icon">🚗</span>
                <span class="type-text">Private Car</span>
              </div>
            </div>
            <!-- <p class="route">📍 Colombo → Galle</p> -->
            <p class="description">Door-to-door luxury transport with professional driver and flexible stops</p>
            <div class="card-features">
              <span class="feature">🚗 Private Vehicle</span>
              <span class="feature">👨‍✈️ Professional Driver</span>
              <span class="feature">🛑 Flexible Stops</span>
              <span class="feature">❄️ Air Conditioned</span>
            </div>
            <div class="transport-info">
              <!-- <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">2 hours</span>
              </div> -->
              <div class="info-item">
                <span class="info-label">Availability:</span>
                <span class="info-value">24/7</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.220</span>
                <span class="price-period">/1km</span>
              </div>
              <button class="btn-primary view-btn" onclick="window.location.href='transportdetails';">View Details</button>
            </div>
          </div>
        </div>

        <!-- Tuk-Tuk Experience -->
        <div class="transport-card" data-type="tuk-tuk" data-route="galle-mirissa" data-price="mid" data-duration="short">
          <div class="card-image">
            <img src="assets/images/wheel.png" alt="Tuk-Tuk">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('tuk-tuk-tour')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <!-- <h3>Galle to Mirissa Tuk-Tuk</h3> -->
              <div class="transport-type">
                <span class="type-icon">🛺</span>
                <span class="type-text">Tuk-Tuk</span>
              </div>
            </div>
            <!-- <p class="route">📍 Galle → Mirissa</p> -->
            <p class="description">Authentic Sri Lankan experience with coastal views and local interactions</p>
            <div class="card-features">
              <span class="feature">🌊 Coastal Route</span>
              <span class="feature">🏛️ Cultural Sites</span>
              <span class="feature">📸 Photo Stops</span>
              <span class="feature">🗣️ Local Guide</span>
            </div>
            <div class="transport-info">
              <!-- <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">45 mins</span>
              </div> -->
              <div class="info-item">
                <span class="info-label">Capacity:</span>
                <span class="info-value">3 passengers</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.150</span>
                <span class="price-period">/ 1km</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('tuk-tuk-tour')">View Details</button>
            </div>
          </div>
        </div>

        <!-- Boat Transfer -->
        <!-- <div class="transport-card" data-type="boat" data-route="colombo-galle" data-price="premium" data-duration="medium">
          <div class="card-image">
            <img src="assets/images/boat-transfer.jpg" alt="Boat Transfer">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('boat-transfer')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Coastal Boat Transfer</h3>
              <div class="transport-type">
                <span class="type-icon">🛥️</span>
                <span class="type-text">Boat</span>
              </div>
            </div>
            <p class="route">📍 Colombo Port → Galle Harbor</p>
            <p class="description">Scenic coastal journey with dolphin spotting opportunities and ocean breeze</p>
            <div class="card-features">
              <span class="feature">🐬 Dolphin Watching</span>
              <span class="feature">🌊 Ocean Views</span>
              <span class="feature">☀️ Sunset Tours</span>
              <span class="feature">🍹 Refreshments</span>
            </div>
            <div class="transport-info">
              <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">4 hours</span>
              </div>
              <div class="info-item">
                <span class="info-label">Schedule:</span>
                <span class="info-value">Twice daily</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.4500</span>
                <span class="price-period">/ person</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('boat-transfer')">View Details</button>
            </div>
          </div>
        </div> -->

        <!-- Domestic Flight -->
        <!-- <div class="transport-card" data-type="plane" data-route="colombo-jaffna" data-price="premium" data-duration="short">
          <div class="card-image">
            <img src="assets/images/domestic-flight.jpg" alt="Domestic Flight">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookTransport('domestic-flight')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Colombo to Jaffna Flight</h3>
              <div class="transport-type">
                <span class="type-icon">✈️</span>
                <span class="type-text">Flight</span>
              </div>
            </div>
            <p class="route">📍 Colombo → Jaffna</p>
            <p class="description">Quick and comfortable air travel to reach the northern peninsula in record time</p>
            <div class="card-features">
              <span class="feature">⚡ Fastest Option</span>
              <span class="feature">🛫 Modern Aircraft</span>
              <span class="feature">🧳 Baggage Included</span>
              <span class="feature">☁️ Aerial Views</span>
            </div>
            <div class="transport-info">
              <div class="info-item">
                <span class="info-label">Duration:</span>
                <span class="info-value">1.5 hours</span>
              </div>
              <div class="info-item">
                <span class="info-label">Frequency:</span>
                <span class="info-value">Daily flights</span>
              </div>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.12000</span>
                <span class="price-period">/ person</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('domestic-flight')">View Details</button>
            </div>
          </div>
        </div> -->

      </div>

      <!-- <div class="load-more-section">
        <button class="btn-secondary load-more-btn" onclick="loadMoreTransportation()">Load More Options</button>
      </div> -->
    </div>
  </section>

  <!-- Booking Modal -->
  <div class="modal" id="bookingModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Book Transportation</h3>
        <button class="close-btn" onclick="closeBookingModal()">&times;</button>
      </div>
      <div class="modal-body">
        <form class="booking-form">
          <div class="form-group">
            <label>Transportation Option</label>
            <input type="text" id="selectedTransport" readonly>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Travel Date</label>
              <input type="date" id="travelDate" required>
            </div>
            <div class="form-group">
              <label>Return Date (Optional)</label>
              <input type="date" id="returnDate">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Passengers</label>
              <select id="passengers">
                <option value="1">1 Passenger</option>
                <option value="2">2 Passengers</option>
                <option value="3">3 Passengers</option>
                <option value="4">4+ Passengers</option>
              </select>
            </div>
            <div class="form-group">
              <label>Departure Time</label>
              <select id="departureTime">
                <option value="">Select Time</option>
                <option value="morning">Morning (6AM - 12PM)</option>
                <option value="afternoon">Afternoon (12PM - 6PM)</option>
                <option value="evening">Evening (6PM - 10PM)</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Additional Requirements</label>
            <textarea id="additionalRequests" rows="3" placeholder="Any special requirements or preferences..."></textarea>
          </div>
          <button type="button" class="btn-primary full-width" onclick="submitTransportBooking()">Book Transportation</button>
        </form>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    // Load vehicles dynamically
    document.addEventListener('DOMContentLoaded', function() {
      loadVehicles();
    });

    function getBaseUrl() {
      const path = window.location.pathname;
      if (path.includes('/TravelMate')) {
        return '/TravelMate/public';
      }
      return '';
    }

    async function loadVehicles() {
      try {
        const baseUrl = getBaseUrl();
        const response = await fetch(baseUrl + '/api/vehicle/listAll');
        
        if (!response.ok) {
          console.error('Failed to fetch vehicles:', response.status);
          return;
        }

        const result = await response.json();
        
        if (result.success && result.data && result.data.length > 0) {
          displayVehicles(result.data);
        } else {
          console.log('No vehicles found');
        }
      } catch (error) {
        console.error('Error loading vehicles:', error);
      }
    }

    function displayVehicles(vehicles) {
      const grid = document.getElementById('transportationGrid');
      if (!grid) return;

      // Clear existing static content
      grid.innerHTML = '';

      vehicles.forEach(vehicle => {
        const card = createVehicleCard(vehicle);
        grid.appendChild(card);
      });
    }

    function createVehicleCard(vehicle) {
      const card = document.createElement('div');
      card.className = 'transport-card';
      card.setAttribute('data-type', vehicle.vehicle_type || '');
      card.setAttribute('data-route', vehicle.working_district || '');
      
      const imageUrl = vehicle.main_image 
        ? getBaseUrl() + vehicle.main_image 
        : 'assets/images/default-vehicle.png';
      
      const acBadge = vehicle.ac_type === 'ac' ? '❄️ AC' : '';
      const vehicleTypeIcon = getVehicleIcon(vehicle.vehicle_type);
      const status = (vehicle.status || 'active').toLowerCase();
      const statusLabel = status === 'inactive' ? 'Inactive' : status === 'pending' ? 'Pending' : 'Active';
      const costPerKm = Number(vehicle.cost_per_km || 0);
      const formattedCost = costPerKm > 0 ? costPerKm.toFixed(2) : '0.00';
      const ratingCount = parseInt(vehicle.rating_count || 0, 10) || 0;
      const avgRatingValue = parseFloat(vehicle.avg_rating || 0);
      const ratingStarsHtml = (() => {
        let stars = '';
        for (let index = 1; index <= 5; index++) {
          if (avgRatingValue >= index) {
            stars += '<i class="fa-solid fa-star"></i>';
          } else if (avgRatingValue >= index - 0.5) {
            stars += '<i class="fa-solid fa-star-half-stroke"></i>';
          } else {
            stars += '<i class="fa-regular fa-star"></i>';
          }
        }
        return stars;
      })();
      const ratingText = ratingCount > 0 ? `${avgRatingValue.toFixed(1)} (${ratingCount})` : 'Not yet rated';
      
      card.innerHTML = `
        <div class="card-image">
          <div class="card-status-badge ${status}">${statusLabel}</div>
          <img src="${imageUrl}" alt="${escapeHtml(vehicle.vehicle_model || vehicle.vehicle_type)}" onerror="this.src='assets/images/default-vehicle.png'">
        </div>
        <div class="card-content">
          <div class="card-header">
            <h3>${escapeHtml(vehicle.vehicle_model || vehicle.vehicle_type)}</h3>
            <div class="transport-type">
              <span class="type-icon">${vehicleTypeIcon}</span>
              <span class="type-text">${escapeHtml(vehicle.vehicle_type || 'Vehicle')}</span>
            </div>
          </div>
          <p class="route">📍 ${escapeHtml(vehicle.working_district || 'Sri Lanka')}</p>
          <p class="description">${vehicle.vehicle_year ? vehicle.vehicle_year + ' Model' : ''} ${vehicle.vehicle_color ? '• ' + vehicle.vehicle_color : ''}</p>
          <p class="card-rating"><span class="rating-stars">${ratingStarsHtml}</span><span>${escapeHtml(ratingText)}</span></p>
          <div class="card-features">
            <span class="feature">👥 ${vehicle.passenger_count || 0} Passengers</span>
            ${acBadge ? '<span class="feature">' + acBadge + '</span>' : ''}
            ${vehicle.vehicle_number ? '<span class="feature">🚗 ' + escapeHtml(vehicle.vehicle_number) + '</span>' : ''}
          </div>
          <div class="card-footer">
            <div class="price">
              <span class="price-amount">LKR ${formattedCost}</span>
              <span class="price-period">per 1km</span>
            </div>
            <button class="btn-primary view-btn" onclick="viewVehicleDetails(${vehicle.id})">View Details</button>
          </div>
        </div>
      `;
      
      return card;
    }

    function getVehicleIcon(type) {
      const icons = {
        'car': '🚗',
        'van': '🚐',
        'bus': '🚌',
        'tuk-tuk': '🛺',
        'bike': '🏍️',
        'suv': '🚙',
        'luxury': '🚘',
        'taxi': '🚕'
      };
      return icons[type?.toLowerCase()] || '🚗';
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text || '';
      return div.innerHTML;
    }

    function bookTransport(vehicleId) {
      window.location.href = 'transportdetails?id=' + vehicleId;
    }

    function viewVehicleDetails(vehicleId) {
      window.location.href = 'transportdetails?id=' + vehicleId;
    }

    function applyFilters() {
      const route = document.getElementById('route').value.toLowerCase();
      const type = document.getElementById('transport-type').value.toLowerCase();
      const priceRange = document.getElementById('price-range').value;
      const duration = document.getElementById('duration').value;
      
      const cards = document.querySelectorAll('.transport-card');
      
      cards.forEach(card => {
        const cardRoute = card.getAttribute('data-route').toLowerCase();
        const cardType = card.getAttribute('data-type').toLowerCase();
        
        let show = true;
        
        if (route && !cardRoute.includes(route)) {
          show = false;
        }
        
        if (type && !cardType.includes(type)) {
          show = false;
        }
        
        card.style.display = show ? 'block' : 'none';
      });
    }
  </script>
</body>
</html>