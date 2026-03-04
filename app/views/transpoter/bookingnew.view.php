<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Bookings</title>
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="assets/css/Transpoter/bookingnew.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

      <section class="booking-requests">
        <h2>Booking Requests</h2>
        <div class="filter-bar">
          <input type="text" id="searchBox" placeholder="🔍 Search accepted, rejected and pending requests">
          <select id="categoryFilter">
            <option value="all">All Categories</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
            <option value="pending">Pending</option>
          </select>
          <button id="applyFilter">Search</button>
        </div>
        
        <div class="booking-list">
          <div id="providerBookingList"></div>
          
          <div class="booking-actions-footer">
            <button class="history-btn" onclick="window.location.href='bookingHistory';">
              <i class="fas fa-history"></i> View Booking History
            </button>
          </div>
        </div>
      </section>
    </div>
  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <div class="booking-modal" id="bookingDetailsModal" aria-hidden="true">
    <div class="booking-modal-content">
      <div class="booking-modal-header">
        <h3>Booking Details</h3>
        <button type="button" class="booking-modal-close" onclick="closeBookingDetailsModal()">&times;</button>
      </div>
      <div class="booking-modal-body" id="bookingModalBody"></div>
      <div class="booking-modal-footer">
        <button type="button" class="history-btn" onclick="closeBookingDetailsModal()">Close</button>
      </div>
    </div>
  </div>

  <div class="booking-modal" id="bookingActionModal" aria-hidden="true">
    <div class="booking-modal-content" style="max-width:520px;">
      <div class="booking-modal-header">
        <h3 id="bookingActionTitle">Confirm Action</h3>
        <button type="button" class="booking-modal-close" onclick="closeBookingActionModal()">&times;</button>
      </div>
      <div class="booking-modal-body">
        <p id="bookingActionMessage" style="margin:0;color:#334155;">Are you sure?</p>
      </div>
      <div class="booking-modal-footer" style="justify-content:flex-end;gap:10px;">
        <button type="button" class="view-btn" onclick="closeBookingActionModal()">Cancel</button>
        <button type="button" class="accept-btn" id="confirmBookingActionBtn">Confirm</button>
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

    function renderFilteredBookings() {
      const searchTerm = document.getElementById('searchBox').value.toLowerCase();
      const filterValue = document.getElementById('categoryFilter').value;
      const container = document.getElementById('providerBookingList');

      const filtered = allProviderBookings.filter(booking => {
        const normalizedStatus = normalizeStatus(booking.booking_status);
        const customerName = `${booking.first_name || ''} ${booking.last_name || ''}`.toLowerCase();
        const textBlob = `${booking.booking_id} ${customerName} ${booking.pickup_location || ''} ${booking.dropoff_location || ''}`.toLowerCase();
        const matchesSearch = !searchTerm || textBlob.includes(searchTerm);
        const matchesFilter = filterValue === 'all' || filterValue === normalizedStatus;
        return matchesSearch && matchesFilter;
      });

      if (filtered.length === 0) {
        container.innerHTML = '<p style="padding:1rem;color:#666;">No bookings found.</p>';
        return;
      }

      container.innerHTML = filtered.map(renderBookingCard).join('');
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
      const cardClass = normalizeStatus(status);
      const customer = `${booking.first_name || ''} ${booking.last_name || ''}`.trim() || 'Traveller';
      const pickupDate = booking.pickup_date || '-';
      const pickupTime = booking.pickup_time || '-';
      const amount = Number(booking.total_price || 0).toLocaleString();

      return `
        <div class="booking-card ${cardClass}">
          <div class="booking-header">
            <h3>Booking #${booking.booking_id}</h3>
            <span class="status-badge">${displayStatus(status)}</span>
          </div>
          <div class="booking-details">
            <p><strong>Customer:</strong> ${customer}</p>
            <p><strong>Pickup:</strong> ${booking.pickup_location || '-'}</p>
            <p><strong>Destination:</strong> ${booking.dropoff_location || '-'}</p>
            <p><strong>Date & Time:</strong> ${pickupDate} at ${pickupTime}</p>
            <p><strong>Passengers:</strong> ${booking.passengers || 0}</p>
            <p><strong>Estimated Fare:</strong> LKR ${amount}</p>
            <p><strong>Payment:</strong> ${String(booking.payment_status || 'pending').toUpperCase()}</p>
          </div>
          <div class="booking-actions">
            <button class="view-btn" onclick="openBookingDetailsModal('${booking.booking_id}')"><i class="fas fa-eye"></i> View</button>
            ${status === 'pending' ? `<button class="accept-btn" onclick="openAcceptBookingModal('${booking.booking_id}')"><i class="fas fa-check"></i> Accept</button>
              <button class="reject-btn" onclick="openRejectBookingModal('${booking.booking_id}')"><i class="fas fa-times"></i> Reject</button>` : ''}
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
        confirmBtn.className = 'accept-btn';
      confirmBtn.onclick = confirmPendingModerationAction;

      modal.classList.add('show');
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
        confirmBtn.className = 'reject-btn';
        confirmBtn.onclick = confirmPendingModerationAction;

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
      }

    function closeBookingActionModal() {
      const modal = document.getElementById('bookingActionModal');
      if (!modal) return;

      modal.classList.remove('show');
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
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
    }

    function closeBookingDetailsModal() {
      const modal = document.getElementById('bookingDetailsModal');
      modal.classList.remove('show');
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