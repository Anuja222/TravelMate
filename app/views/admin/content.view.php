<!DOCTYPE html>
<html>
<head>
  <title>Blogs</title>
  <link rel="stylesheet" href="assets/css/Admin/content.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
</head>
<body>

    <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<div class="page-container">

      <?php include 'sidebar.view.php'; ?>

<div class="content">
  <div class="page-title">  
    <h1>Blog Management</h1>
    </div>
  <ul class="vlogs">
    <li>
      <div class="vlog-info">
        <img src="assets/images/bluebeach.png" alt="Vlog Preview">
        <div class="vlog-details">
          <h2>Vlog Title 1</h2>
          <a href="viewblog" class="view-link">View Full Vlog</a>
        </div>
      </div>
      <div class="action-buttons">
        <button class="approve">Approve</button>
        <button class="reject">Reject</button>
      </div>
    </li>

    <li>
      <div class="vlog-info">
        <img src="assets/images/birdwatching.png" alt="Vlog Preview">
        <div class="vlog-details">
          <h2>Vlog Title 2</h2>
          <a href="viewblog" class="view-link" target="_blank">View Full Vlog</a>
        </div>
      </div>
      <div class="action-buttons">
        <button class="approve">Approve</button>
        <button class="reject">Reject</button>
      </div>
    </li>
  </ul>
</div>

</div>
</body>
</html>
