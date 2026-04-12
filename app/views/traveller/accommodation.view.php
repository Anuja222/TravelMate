<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Accommodations</title>
  <link rel="stylesheet" href="assets/css/Traveller/accommodation.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- Hero Section -->
  <section class="hero-section" style="position: relative; min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="hero-background" style="position: absolute; inset: 0; background-image: url('assets/images/accomodationmain.png'); background-size: cover; background-position: center; background-attachment: fixed; z-index: 1;"></div>
    <div class="hero-overlay" style="position: relative; z-index: 2; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); padding: 50px 20px;">
      <div class="hero-content" style="text-align: center; color: white;">
          <h1>Find Your Perfect Stay</h1>
          <p>From luxury resorts to cozy guesthouses, discover the best accommodations in Sri Lanka</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Filter Section -->
  <section class="filter-section">
    <div class="container">
      <div class="filter-bar">
        <div class="filter-group">
          <label for="location">Location</label>
          <select id="location">
            <option value="">All Locations</option>
            <option value="colombo">Colombo</option>
            <option value="kandy">Kandy</option>
            <option value="galle">Galle</option>
            <option value="nuwara-eliya">Nuwara Eliya</option>
            <option value="sigiriya">Sigiriya</option>
            <option value="bentota">Bentota</option>
            <option value="ella">Ella</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="type">Accommodation Type</label>
          <select id="type">
            <option value="">All Types</option>
            <option value="hotel">Hotels</option>
            <option value="resort">Resorts</option>
            <option value="villa">Villas</option>
            <option value="guesthouse">Guest Houses</option>
            <option value="boutique">Boutique Hotels</option>
            <option value="homestay">Homestays</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="price">Price Range</label>
          <select id="price">
            <option value="">All Prices</option>
            <option value="budget">Budget (Under Rs.15000)</option>
            <option value="mid">Mid-range (Rs.15000-45000)</option>
            <option value="luxury">Luxury (Rs.45000+)</option>
          </select>
        </div>
        <!-- <div class="filter-group">
          <label for="rating">Rating</label>
          <select id="rating">
            <option value="">All Ratings</option>
            <option value="5">5 Stars</option>
            <option value="4">4+ Stars</option>
            <option value="3">3+ Stars</option>
          </select>
        </div> -->
        <button class="filter-btn" onclick="applyFilters()">Filter Results</button>
      </div>
    </div>
  </section>

  <!-- Accommodations Section -->
  <section class="accommodations-section">
    <div class="container">
      <!-- <div class="section-header">
        <h2>Discover Amazing Accommodations</h2>
        <p>Choose from our carefully selected collection of hotels, resorts, and unique stays</p>
      </div> -->

      <div class="accommodations-grid" id="accommodationsGrid">
        <!-- Luxury Resort -->
        <div class="accommodation-card" data-type="resort" data-location="bentota" data-price="luxury" data-rating="5">
          <div class="card-image">
            <img src="assets/images/luxuryhotel.png" alt="Luxury Beach Resort">
            <div class="card-overlay">
              <a href="accommodationdetail" class="book-btn">Book Now</a>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Luxury Beach Resort</h3>
              <div class="location" style="font-weight:600; margin-top: 6px;">
                <span style="color:#f59e0b; display:inline-flex; gap:2px;">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </span>
                <span>5.0 (1)</span>
              </div>
            </div>
            <p class="location">📍 Bentota Beach</p>
            <p class="description">Beachfront luxury resort with world-class amenities and stunning ocean views</p>
            <div class="card-features">
              <span class="feature">🏊 Pool</span>
              <span class="feature">🍽️ Restaurant</span>
              <span class="feature">🏖️ Beach Access</span>
              <span class="feature">📶 WiFi</span>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.45000</span>
                <span class="price-period">/ night</span>
              </div>
              <a href="accommodationdetail" class="btn-primary view-btn">View Details</a>
            </div>
          </div>
        </div>

        <!-- Boutique Hotel -->
        <div class="accommodation-card" data-type="boutique" data-location="kandy" data-price="mid" data-rating="4">
          <div class="card-image">
            <img src="assets/images/boutiquehotel.png" alt="Kandy Boutique Hotel">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookAccommodation('boutique-hotel')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Kandy Boutique Hotel</h3>
              <div class="location" style="font-weight:600; margin-top: 6px;">
                <span style="color:#f59e0b; display:inline-flex; gap:2px;">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                </span>
                <span>4.0 (1)</span>
              </div>
            </div>
            <p class="location">📍 Kandy City Center</p>
            <p class="description">Charming boutique hotel in the heart of the cultural capital</p>
            <div class="card-features">
              <span class="feature">🏛️ Cultural</span>
              <span class="feature">🍽️ Restaurant</span>
              <span class="feature">🚗 Parking</span>
              <span class="feature">📶 WiFi</span>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.8500</span>
                <span class="price-period">/ night</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('boutique-hotel')">View Details</button>
            </div>
          </div>
        </div>

        <!-- Mountain Lodge -->
        <div class="accommodation-card" data-type="mountain" data-location="kandy" data-price="mid" data-rating="4">
          <div class="card-image">
            <img src="assets/images/mountainlodge.png" alt="Mountain Lodge">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookAccommodation('mountain-lodge')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Mountain Lodge</h3>
              <div class="location" style="font-weight:600; margin-top: 6px;">
                <span style="color:#f59e0b; display:inline-flex; gap:2px;">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                </span>
                <span>4.0 (1)</span>
              </div>
            </div>
            <p class="location">📍 Kandy City Center</p>
            <p class="description">Charming boutique hotel in the heart of the cultural capital</p>
            <div class="card-features">
              <span class="feature">🏛️ Cultural</span>
              <span class="feature">🍽️ Restaurant</span>
              <span class="feature">🚗 Parking</span>
              <span class="feature">📶 WiFi</span>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.18000</span>
                <span class="price-period">/ night</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('mountain-lodge')">View Details</button>
            </div>
          </div>
        </div>

        <!-- Hill Country Villa -->
        <div class="accommodation-card" data-type="villa" data-location="ella" data-price="mid" data-rating="4">
          <div class="card-image">
            <img src="assets/images/countryvilla.png" alt="Hill Country Villa">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookAccommodation('hill-villa')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Hill Country Villa</h3>
              <div class="location" style="font-weight:600; margin-top: 6px;">
                <span style="color:#f59e0b; display:inline-flex; gap:2px;">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                </span>
                <span>4.0 (1)</span>
              </div>
            </div>
            <p class="location">📍 Ella Hills</p>
            <p class="description">Private villa with panoramic mountain views and tea plantation walks</p>
            <div class="card-features">
              <span class="feature">🏔️ Mountain View</span>
              <span class="feature">🍃 Garden</span>
              <span class="feature">🔥 Fireplace</span>
              <span class="feature">📶 WiFi</span>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.15000</span>
                <span class="price-period">/ night</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('hill-villa')">View Details</button>
            </div>
          </div>
        </div>

        <!-- Heritage Hotel -->
        <div class="accommodation-card" data-type="hotel" data-location="galle" data-price="mid" data-rating="4">
          <div class="card-image">
            <img src="assets/images/heritagevilla.png" alt="Heritage Hotel">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookAccommodation('heritage-hotel')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Galle Heritage Hotel</h3>
              <div class="location" style="font-weight:600; margin-top: 6px;">
                <span style="color:#f59e0b; display:inline-flex; gap:2px;">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                </span>
                <span>4.0 (1)</span>
              </div>
            </div>
            <p class="location">📍 Galle Fort</p>
            <p class="description">Historic colonial building within the UNESCO World Heritage Galle Fort</p>
            <div class="card-features">
              <span class="feature">🏛️ Historic</span>
              <span class="feature">🍽️ Restaurant</span>
              <span class="feature">🌊 Ocean View</span>
              <span class="feature">📶 WiFi</span>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.19000</span>
                <span class="price-period">/ night</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('heritage-hotel')">View Details</button>
            </div>
          </div>
        </div>

        <!-- Budget Guesthouse -->
        <div class="accommodation-card" data-type="guesthouse" data-location="sigiriya" data-price="budget" data-rating="3">
          <div class="card-image">
            <img src="assets/images/gardenhouse.png" alt="Sigiriya Guesthouse">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookAccommodation('sigiriya-guesthouse')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Sigiriya Garden House</h3>
              <div class="location" style="font-weight:600; margin-top: 6px;">
                <span style="color:#f59e0b; display:inline-flex; gap:2px;">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                </span>
                <span>3.0 (1)</span>
              </div>
            </div>
            <p class="location">📍 Near Sigiriya Rock</p>
            <p class="description">Comfortable guesthouse with garden views, perfect for exploring ancient sites</p>
            <div class="card-features">
              <span class="feature">🌿 Garden</span>
              <span class="feature">🚲 Bike Rental</span>
              <span class="feature">🍳 Breakfast</span>
              <span class="feature">📶 WiFi</span>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.8000</span>
                <span class="price-period">/ night</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('sigiriya-guesthouse')">View Details</button>
            </div>
          </div>
        </div>

        <!-- City Hotel -->
        <div class="accommodation-card" data-type="hotel" data-location="colombo" data-price="mid" data-rating="4">
          <div class="card-image">
            <img src="assets/images/cityhotel.png" alt="Colombo City Hotel">
            <div class="card-overlay">
              <button class="book-btn" onclick="bookAccommodation('city-hotel')">Book Now</button>
            </div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3>Colombo City Hotel</h3>
              <div class="location" style="font-weight:600; margin-top: 6px;">
                <span style="color:#f59e0b; display:inline-flex; gap:2px;">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                </span>
                <span>4.0 (1)</span>
              </div>
            </div>
            <p class="location">📍 Colombo City Center</p>
            <p class="description">Modern business hotel in the heart of Sri Lanka's commercial capital</p>
            <div class="card-features">
              <span class="feature">🏢 Business Center</span>
              <span class="feature">🏋️ Gym</span>
              <span class="feature">🍽️ Restaurant</span>
              <span class="feature">📶 WiFi</span>
            </div>
            <div class="card-footer">
              <div class="price">
                <span class="price-amount">Rs.15000</span>
                <span class="price-period">/ night</span>
              </div>
              <button class="btn-primary view-btn" onclick="viewDetails('city-hotel')">View Details</button>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="load-more-section">
        <button class="btn-secondary load-more-btn" onclick="loadMoreAccommodations()">Load More Accommodations</button>
      </div> -->
    </div>
  </section>
  <!-- Booking Modal -->
  <!-- <div class="modal" id="bookingModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Book Your Stay</h3>
        <button class="close-btn" onclick="closeBookingModal()">&times;</button>
      </div>
      <div class="modal-body">
        <form class="booking-form">
          <div class="form-group">
            <label>Accommodation</label>
            <input type="text" id="selectedAccommodation" readonly>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Check-in Date</label>
              <input type="date" id="checkinDate" required>
            </div>
            <div class="form-group">
              <label>Check-out Date</label>
              <input type="date" id="checkoutDate" required>
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
            <label>Special Requests</label>
            <textarea id="specialRequests" rows="3" placeholder="Any special requests or preferences..."></textarea>
          </div>
          <button type="button" class="btn-primary full-width" onclick="submitBooking()">Book Now</button>
        </form>
      </div>
    </div>
  </div> -->

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
  
  <script>
    // Load accommodations dynamically
    document.addEventListener('DOMContentLoaded', function() {
      loadAccommodations();
    });

    function getBaseUrl() {
      const path = window.location.pathname;
      if (path.includes('/TravelMate')) {
        return '/TravelMate/public';
      }
      return '';
    }

    async function loadAccommodations() {
      try {
        const baseUrl = getBaseUrl();
        const response = await fetch(baseUrl + '/api/accommodation/listAll');
        
        if (!response.ok) {
          console.error('Failed to fetch accommodations:', response.status);
          return;
        }

        const result = await response.json();
        
        if (result.success && result.data && result.data.length > 0) {
          displayAccommodations(result.data);
        } else {
          console.log('No accommodations found');
        }
      } catch (error) {
        console.error('Error loading accommodations:', error);
      }
    }

    function displayAccommodations(accommodations) {
      const grid = document.getElementById('accommodationsGrid');
      if (!grid) return;

      // Clear existing static content
      grid.innerHTML = '';

      if (accommodations.length === 0) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #666;">No accommodations available at the moment.</p>';
        return;
      }

      accommodations.forEach(accommodation => {
        const card = createAccommodationCard(accommodation);
        grid.appendChild(card);
      });
    }

    function createAccommodationCard(accommodation) {
      const card = document.createElement('div');
      card.className = 'accommodation-card';
      card.setAttribute('data-type', accommodation.property_type || '');
      card.setAttribute('data-location', accommodation.location || '');
      
      const imageUrl = accommodation.main_image 
        ? getBaseUrl() + '/' + accommodation.main_image 
        : 'assets/images/default-accommodation.png';
      
      const price = accommodation.price_per_night || 0;
      const priceRange = price > 45000 ? 'luxury' : price > 15000 ? 'mid' : 'budget';
      card.setAttribute('data-price', priceRange);

      const ratingCount = parseInt(accommodation.rating_count || 0, 10) || 0;
      const avgRatingValue = parseFloat(accommodation.avg_rating || 0);
      const ratingStarsHtml = (() => {
        let stars = '';
        for (let index = 1; index <= 5; index++) {
          if (avgRatingValue >= index) {
            stars += '<i class="fas fa-star"></i>';
          } else if (avgRatingValue >= index - 0.5) {
            stars += '<i class="fas fa-star-half-alt"></i>';
          } else {
            stars += '<i class="far fa-star"></i>';
          }
        }
        return stars;
      })();
      const ratingText = ratingCount > 0 ? `${avgRatingValue.toFixed(1)} (${ratingCount})` : 'Not yet rated';
      
      // Format property type for badge
      const propertyType = accommodation.property_type ? 
        accommodation.property_type.charAt(0).toUpperCase() + accommodation.property_type.slice(1).replace(/_/g, ' ') : 
        'Property';
      
      card.innerHTML = `
        <div class="card-image">
          <img src="${imageUrl}" alt="${escapeHtml(accommodation.title)}" onerror="this.src='assets/images/default-accommodation.png'">
          <div class="card-badge">${escapeHtml(propertyType)}</div>
          <div class="card-overlay">
            <a href="accommodationdetail?id=${accommodation.id}" class="book-btn">Book Now</a>
          </div>
        </div>
        <div class="card-content">
          <div class="card-header">
            <h3>${escapeHtml(accommodation.title)}</h3>
          </div>
          <p class="location">
            <i class="fas fa-map-marker-alt"></i>
            ${escapeHtml(accommodation.location || 'Sri Lanka')}
          </p>
          <p class="location" style="font-weight:600;">
            <span style="color:#f59e0b; display:inline-flex; gap:2px;">${ratingStarsHtml}</span>
            <span>${escapeHtml(ratingText)}</span>
          </p>
          <p class="description">${escapeHtml(accommodation.description || 'No description available')}</p>
          <div class="card-features">
            <span class="feature">🛏️ ${accommodation.rooms || 0} Rooms</span>
            <span class="feature">🚿 ${accommodation.bathrooms || 0} Bathrooms</span>
            <span class="feature">👥 ${accommodation.max_guests || 0} Guests</span>
          </div>
          <div class="card-footer">
            <div class="price">
              <span class="price-amount">LKR ${formatPrice(price)}</span>
              <span class="price-period">per night</span>
            </div>
            <button class="btn-primary view-btn" onclick="viewDetails(${accommodation.id})">View Details</button>
          </div>
        </div>
      `;
      
      return card;
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text || '';
      return div.innerHTML;
    }

    function formatPrice(price) {
      return parseFloat(price || 0).toLocaleString('en-US');
    }

    function viewDetails(accommodationId) {
      window.location.href = 'accommodationdetail?id=' + accommodationId;
    }

    function applyFilters() {
      const location = document.getElementById('location').value.toLowerCase();
      const type = document.getElementById('type').value.toLowerCase();
      const price = document.getElementById('price').value;
      
      const cards = document.querySelectorAll('.accommodation-card');
      
      cards.forEach(card => {
        const cardLocation = card.getAttribute('data-location').toLowerCase();
        const cardType = card.getAttribute('data-type').toLowerCase();
        const cardPrice = card.getAttribute('data-price');
        
        let show = true;
        
        if (location && !cardLocation.includes(location)) {
          show = false;
        }
        
        if (type && !cardType.includes(type)) {
          show = false;
        }
        
        if (price && cardPrice !== price) {
          show = false;
        }
        
        card.style.display = show ? 'block' : 'none';
      });
    }
  </script>
</body>
</html>