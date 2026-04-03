<?php
// Ensure session is active and check transporter role safely
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
$isTransporter = isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'transport';
if (!$isTransporter) {
  header('Location: /TravelMate/public/login');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TravelMate - Transport Bookings</title>
  <link rel="stylesheet" href="assets/css/Transpoter/bookingnew.css">
  <link rel="stylesheet" href="assets/css/Transpoter/common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <div class="content">
      <div class="page-title">  
        <h1>Booking Management</h1>
        <p>Manage your transport booking requests</p>
        <button id="refreshBookings" class="refresh-btn">
          <i class="fas fa-sync-alt"></i> Refresh
        </button>
      </div>

      <!-- PERFORMANCE SUMMARY -->
      <section class="activity-summary">
        <h3>Booking Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <div class="stat-num">0</div>
            <div class="stat-label">PENDING</div>
          </div>
          <div class="stat">
            <div class="stat-num">0</div>
            <div class="stat-label">COMPLETED</div>
          </div>
          <div class="stat">
            <div class="stat-num">0</div>
            <div class="stat-label">TOTAL BOOKINGS</div>
          </div>
        </div>
      </section>

      <section class="booking-requests">
        <h2>Transport Bookings</h2>
        <div class="filter-bar">
          <input type="text" id="searchBox" placeholder="🔍 Search bookings by customer, vehicle, or location">
          <select id="categoryFilter">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
          </select>
          <button id="applyFilter">Search</button>
        </div>
        
        <div class="booking-list">
          <!-- Bookings will be loaded dynamically by JavaScript -->
          <div class="loading">Loading bookings...</div>
        </div>
      </section>
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingDetailsModal" class="modal">
        <div class="modal-content" id="modalContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>

  </main>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script src="assets/js/bookingnew.js"></script>
</body>
</html>