<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/Admin/dashboard.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>


<div class="page-container">
   <?php include 'sidebar.view.php'; ?>

  <div class="content">

    <div class="page-title">
      <div class="page-title-content">
        <div class="page-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7"></rect>
            <rect x="14" y="3" width="7" height="7"></rect>
            <rect x="14" y="14" width="7" height="7"></rect>
            <rect x="3" y="14" width="7" height="7"></rect>
          </svg>
        </div>
        <div class="page-title-text">
          <h1>Admin Dashboard</h1>
          <p class="page-subtitle">Welcome back! Here's what's happening today</p>
        </div>
      </div>
    </div>

    <div class="cards-container">
      <div class="card">
        <div class="card-title">Total Users</div>
        <div class="card-value">1,200</div>
      </div>
      <div class="card">
        <div class="card-title">Hotel Listings</div>
        <div class="card-value">350</div>
      </div>
      <div class="card">
        <div class="card-title">Vehicle Listings</div>
        <div class="card-value">75</div>
      </div>
      <div class="card">
        <div class="card-title">Total Bookings</div>
        <div class="card-value">55</div>
      </div>
      <div class="card">
        <div class="card-title">Pending Vlog Approvals</div>
        <div class="card-value">3</div>
      </div>
    </div>
  </div>
</div>


</body>
</html>
