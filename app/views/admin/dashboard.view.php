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
      <h1>Welcome, Admin</h1>
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
