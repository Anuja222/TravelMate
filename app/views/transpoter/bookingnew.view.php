<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Bookings</title>
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="assets/css/Transpoter/bookingnew.css">
  <link rel="stylesheet" href="/TravelMate/public/assets/css/Accommodation/bookings.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Specific overrides for transporter buttons inside accommodation grid */
    .booking-btn-accept {
        background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%);
        color: white;
    }
    .booking-btn-accept:hover {
        background: linear-gradient(135deg, #16a085 0%, #1abc5b 100%);
        transform: translateY(-2px);
    }
    .booking-btn-reject {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }
    .booking-btn-reject:hover {
        background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
        transform: translateY(-2px);
    }
    .booking-card {
        min-height: auto;
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
        <li><a href="tr_dashboard"><i ></i> Dashboard</a></li>
        <li><a href="bookingnew" class="active"><i ></i> Bookings</a></li>
        <li><a href="setting"><i ></i> Setting</a></li>
      </ul>
    </aside>

    <div class="content">
      <div class="page-title">  
        <h1>Booking Management</h1>
        <p>Manage your booking requests and history</p>
      </div>

      <section class="bookings-container" style="margin-top: 20px;">
        <div class="filter-bar" style="margin-bottom: 2em; display:flex; gap:10px; align-items:center;">
          <input type="text" id="searchBox" placeholder="🔍 Search accepted, rejected and pending requests" style="flex:1;">
          <select id="categoryFilter" style="padding:10px; border-radius:8px; border:1px solid #ccc;">
            <option value="all">All Categories</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
            <option value="pending">Pending</option>
          </select>
          <button id="applyFilter" class="btn-primary" style="padding:10px 20px; border-radius:8px; border:none; background:#1abc5b; color:white; cursor:pointer;">Search</button>
          <button class="history-btn" onclick="window.location.href='bookingHistory';" style="padding:10px 20px; border-radius:8px; border:none; background:#3498db; color:white; cursor:pointer; margin-left:10px;">
            <i class="fas fa-history"></i> View History
          </button>
        </div>
        
        <div class="bookings-section">
            <div class="bookings-section-header">
                <h2><i class="fas fa-calendar-day"></i> Current Bookings</h2>
            </div>
            <div class="bookings-grid" id="currentBookingsGrid">
                <!-- Current bookings will load here -->
            </div>
        </div>

        <div class="bookings-section bookings-section-expired">
            <div class="bookings-section-header">
                <h2><i class="fas fa-history"></i> Expired Bookings</h2>
            </div>
            <div class="bookings-grid" id="expiredBookingsGrid">
                <!-- Expired bookings will load here -->
            </div>
        </div>
      </section>
    </div>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <div class="modal" id="bookingDetailsModal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeBookingDetailsModal()">&times;</span>
      <h2 class="modal-title">Booking Details</h2>
      <div class="modal-body" id="bookingModalBody">
          <!-- Details will be loaded here -->
      </div>
    </div>
  </div>

  <div class="modal" id="bookingActionModal">
    <div class="modal-content contact-modal-content">
      <span class="modal-close" onclick="closeBookingActionModal()">&times;</span>
      <h2 class="modal-title" id="bookingActionTitle">Confirm Action</h2>
      <div class="modal-body">
          <p id="bookingActionMessage" style="margin:0;color:#334155;font-size:16px;">Are you sure?</p>
          <div class="modal-actions" style="margin-top: 20px;">
              <button class="modal-btn modal-btn-close" onclick="closeBookingActionModal()">Cancel</button>
              <button class="modal-btn" style="background: linear-gradient(135deg, #1abc5b 0%, #16a085 100%); color: white; border: none;" id="confirmBookingActionBtn">Confirm</button>
          </div>
      </div>
    </div>
  </div>

  <script>
    const BASE_PATH = window.location.pathname.includes('/TravelMate/') ? '/TravelMate/public' : '';
    let allProviderBookings = [];
    let pendingModerationAction = null;

    document.addEventListener('DOMContentLoaded', function() {
      loadProviderBookings();
      document.getElementById('applyFilter').addEventListener('click', renderFilteredBookings);
    });

    async function loadProviderBookings() {
      try {
        const response = await fetch(`${BASE_PATH}/api/transport-booking/provider/all`, {
          method: 'GET',
          credentials: 'same-origin'
        });
        const result = await response.json();

        if (!result.success) {
          alert(result.errors?.general || 'Failed to load bookings');
          return;
        }

        allProviderBookings = result.data.bookings || [];
        renderFilteredBookings();
      } catch (error) {
        console.error(error);
        alert('Failed to load booking requests');
      }
    }

    function isExpiredBooking(booking) {
      if (!booking || !booking.pickup_date) return false;
      const today = new Date();
      today.setHours(0,0,0,0);
      let pDate = new Date(booking.pickup_date);
      pDate.setHours(0,0,0,0);
      return pDate < today;
    }

    function renderFilteredBookings() {
      const searchTerm = document.getElementById('searchBox').value.toLowerCase();
      const filterValue = document.getElementById('categoryFilter').value;
      const currentContainer = document.getElementById('currentBookingsGrid');
      const expiredContainer = document.getElementById('expiredBookingsGrid');

      const filtered = allProviderBookings.filter(booking => {
        const normalizedStatus = normalizeStatus(booking.booking_status);
        const customerName = `${booking.first_name || ''} ${booking.last_name || ''}`.toLowerCase();
        const textBlob = `${booking.booking_id} ${customerName} ${booking.pickup_location || ''} ${booking.dropoff_location || ''}`.toLowerCase();
        const matchesSearch = !searchTerm || textBlob.includes(searchTerm);
        const matchesFilter = filterValue === 'all' || filterValue === normalizedStatus;
        return matchesSearch && matchesFilter;
      });

      const currentBookings = [];
      const expiredBookings = [];

      filtered.forEach(booking => {
          if (isExpiredBooking(booking)) {
              expiredBookings.push(booking);
          } else {
              currentBookings.push(booking);
          }
      });

      if (currentContainer) {
          currentContainer.innerHTML = currentBookings.length ? currentBookings.map(renderBookingCard).join('') : '<div class="empty-state" style="grid-column: 1/-1; text-align:center; padding: 30px;"><i class="fas fa-calendar-times" style="font-size: 30px; color:#ccc;"></i><p>No current bookings found.</p></div>';
      }
      if (expiredContainer) {
          expiredContainer.innerHTML = expiredBookings.length ? expiredBookings.map(renderBookingCard).join('') : '<div class="empty-state" style="grid-column: 1/-1; text-align:center; padding: 30px;"><i class="fas fa-history" style="font-size: 30px; color:#ccc;"></i><p>No expired bookings found.</p></div>';
      }
    }

    function normalizeStatus(status) {
      const s = String(status || '').toLowerCase();
      if (s === 'confirmed') return 'accepted';
      return s;
    }

    function displayStatus(status) {
      const s = String(status || '').toLowerCase();
      if (s === 'confirmed') return 'Accepted';
      return s.charAt(0).toUpperCase() + s.slice(1);
    }

    function renderBookingCard(booking) {
      const status = String(booking.booking_status || '').toLowerCase();
      const statusClass = normalizeStatus(status) || 'pending';
      const customer = `${booking.first_name || ''} ${booking.last_name || ''}`.trim() || 'Traveller';
      
      const pickupDateStr = booking.pickup_date || '-';
      const pickupTime = booking.pickup_time || '';
      let formattedDate = pickupDateStr;
      const pDate = new Date(pickupDateStr);
      if (!isNaN(pDate.getTime())) {
          formattedDate = pDate.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
      }

      const amount = Number(booking.total_price || 0).toLocaleString();
      const vehicleModel = booking.vehicle_model || 'Vehicle';
      
      const vehicleImage = (booking.vehicle_image || booking.main_image) 
            ? `${BASE_PATH}${booking.vehicle_image || booking.main_image}` 
            : `${BASE_PATH}/assets/trimages/car.png`;

      return `
        <div class="booking-card">
            <div class="booking-image">
                <img src="${vehicleImage}" alt="${vehicleModel}"
                     onerror="this.src='${BASE_PATH}/assets/trimages/car.png'">
                <div class="booking-badge"><i class="fas fa-car"></i> ${vehicleModel}</div>
                <div class="booking-status-badge ${statusClass}" style="text-transform: capitalize;">${displayStatus(status)}</div>
            </div>
            <div class="booking-content">
                <div class="booking-info-row">
                    <h3 class="booking-title">Booking #${booking.booking_id}</h3>
                    <div class="booking-location">
                        <i class="fas fa-user"></i>
                        <span>${customer}</span>
                    </div>
                    <div class="booking-description" style="margin-top: 10px; line-height: 1.5; font-size: 14px; color: #555;">
                        <i class="fas fa-calendar-alt"></i> ${formattedDate} at ${pickupTime} <br>
                        <i class="fas fa-map-marker-alt"></i> <strong>From:</strong> ${booking.pickup_location || '-'}<br>
                        <i class="fas fa-flag-checkered"></i> <strong>To:</strong> ${booking.dropoff_location || '-'}<br>
                        <i class="fas fa-users"></i> ${booking.passengers || 0} Passengers
                    </div>
                </div>

                <div class="booking-footer" style="padding-top: 15px; margin-top: 15px; border-top: 1px solid #edf2f7; display:flex; justify-content: space-between; align-items:center;">
                    <div class="booking-price">
                        <div class="price-amount" style="font-size: 1.1em; font-weight: bold; color: #2c3e50;">
                            <span class="currency">LKR</span>
                            <span>${amount}</span>
                        </div>
                        <span class="price-label" style="font-size: 0.8em; color: #7f8c8d;">Estimated Fare</span>
                    </div>
                    <div class="booking-actions" style="display:flex; gap:8px;">
                        <button class="booking-btn booking-btn-details" style="padding: 8px 12px; border-radius: 6px; font-weight:600; cursor:pointer;" onclick="openBookingDetailsModal('${booking.booking_id}')">
                            <i class="fas fa-eye"></i> View
                        </button>
                        ${statusClass === 'pending' ? `
                            <button class="booking-btn booking-btn-accept" style="padding: 8px; border-radius: 6px; cursor:pointer; border:none;" onclick="openAcceptBookingModal('${booking.booking_id}')" title="Accept">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="booking-btn booking-btn-reject" style="padding: 8px; border-radius: 6px; cursor:pointer; border:none;" onclick="openRejectBookingModal('${booking.booking_id}')" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
      `;
    }

    function openAcceptBookingModal(bookingId) {
      pendingModerationAction = { bookingId, action: 'approve' };

      const modal = document.getElementById('bookingActionModal');
      const titleEl = document.getElementById('bookingActionTitle');
      const msgEl = document.getElementById('bookingActionMessage');
      const confirmBtn = document.getElementById('confirmBookingActionBtn');

      if (!modal || !titleEl || !msgEl || !confirmBtn) {
        return;
      }

      titleEl.textContent = 'Accept Booking';
      msgEl.textContent = `Accept this booking request (${bookingId})?`;
      confirmBtn.textContent = 'Accept Booking';
      confirmBtn.style.background = 'linear-gradient(135deg, #1abc5b 0%, #16a085 100%)';
      confirmBtn.onclick = confirmPendingModerationAction;

      modal.style.display = 'block';
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
    }

      function openRejectBookingModal(bookingId) {
        pendingModerationAction = { bookingId, action: 'reject' };

        const modal = document.getElementById('bookingActionModal');
        const titleEl = document.getElementById('bookingActionTitle');
        const msgEl = document.getElementById('bookingActionMessage');
        const confirmBtn = document.getElementById('confirmBookingActionBtn');

        if (!modal || !titleEl || !msgEl || !confirmBtn) {
          return;
        }

        titleEl.textContent = 'Reject Booking';
        msgEl.textContent = `Reject this booking request (${bookingId})?`;
        confirmBtn.textContent = 'Reject Booking';
        confirmBtn.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
        confirmBtn.onclick = confirmPendingModerationAction;

        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
      }

    function closeBookingActionModal() {
      const modal = document.getElementById('bookingActionModal');
      if (!modal) return;

      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
      pendingModerationAction = null;
    }

    async function confirmPendingModerationAction() {
      if (!pendingModerationAction) {
        closeBookingActionModal();
        return;
      }

      const { bookingId, action } = pendingModerationAction;
      closeBookingActionModal();
      await moderateBooking(bookingId, action, false);
    }

    function openBookingDetailsModal(bookingId) {
      const booking = allProviderBookings.find(item => String(item.booking_id) === String(bookingId));
      if (!booking) {
        alert('Booking details not found');
        return;
      }

      const customer = `${booking.first_name || ''} ${booking.last_name || ''}`.trim() || 'Traveller';
      const statusText = displayStatus(booking.booking_status || 'pending');
      const paymentStatus = String(booking.payment_status || 'pending').toUpperCase();
      const amount = Number(booking.total_price || 0).toLocaleString();
      const luggage = booking.luggage ?? 0;
      const serviceType = String(booking.service_type || '-').charAt(0).toUpperCase() + String(booking.service_type || '-').slice(1);
      const special = booking.special_requirements && String(booking.special_requirements).trim()
        ? booking.special_requirements
        : 'No special requirements';

      const body = document.getElementById('bookingModalBody');
      body.innerHTML = `
        <div class="modal-grid">
          <p><strong>Booking ID:</strong> ${booking.booking_id || '-'}</p>
          <p><strong>Status:</strong> ${statusText}</p>
          <p><strong>Customer:</strong> ${customer}</p>
          <p><strong>Email:</strong> ${booking.email || '-'}</p>
          <p><strong>Service Type:</strong> ${serviceType}</p>
          <p><strong>Payment:</strong> ${paymentStatus}</p>
          <p><strong>Pickup Date:</strong> ${booking.pickup_date || '-'}</p>
          <p><strong>Pickup Time:</strong> ${booking.pickup_time || '-'}</p>
          <p><strong>Return Date:</strong> ${booking.return_date || '-'}</p>
          <p><strong>Return Time:</strong> ${booking.return_time || '-'}</p>
          <p><strong>Pickup:</strong> ${booking.pickup_location || '-'}</p>
          <p><strong>Destination:</strong> ${booking.dropoff_location || '-'}</p>
          <p><strong>Passengers:</strong> ${booking.passengers || 0}</p>
          <p><strong>Luggage:</strong> ${luggage}</p>
          <p><strong>Estimated Fare:</strong> LKR ${amount}</p>
          <p><strong>Special Requirements:</strong> ${special}</p>
        </div>
      `;

      const modal = document.getElementById('bookingDetailsModal');
      modal.style.display = 'block';
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
    }

    function closeBookingDetailsModal() {
      const modal = document.getElementById('bookingDetailsModal');
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
    }

    async function moderateBooking(bookingId, action, askConfirmation = true) {
      if (askConfirmation) {
        const label = action === 'approve' ? 'accept' : 'reject';
        if (!confirm(`Are you sure you want to ${label} booking ${bookingId}?`)) {
          return;
        }
      }

      const endpoint = action === 'approve' ? 'approve' : 'reject';
      try {
        const response = await fetch(`${BASE_PATH}/api/transport-booking/provider/${endpoint}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ booking_id: bookingId }),
          credentials: 'same-origin'
        });
        const result = await response.json();

        if (!result.success) {
          alert(result.errors?.general || 'Failed to update booking');
          return;
        }

        await loadProviderBookings();
      } catch (error) {
        console.error(error);
        alert('Failed to update booking status');
      }
    }

    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeBookingDetailsModal();
        closeBookingActionModal();
      }
    });
  </script>
</body>
</html>