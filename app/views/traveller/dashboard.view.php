<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
$firstName = $isLoggedIn ? $_SESSION['user']['first_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['user']['last_name'] : '';
$profileImage = ($isLoggedIn && !empty($_SESSION['user']['profile_image'])) 
    ? 'assets/' . $_SESSION['user']['profile_image'] 
    : 'assets/images/profile.jpg';

// In case the path already starts with assets or uploads
if ($isLoggedIn && !empty($_SESSION['user']['profile_image'])) {
    $img = $_SESSION['user']['profile_image'];
    $profileImage = (strpos($img, 'http') === 0 || strpos($img, '/') === 0) ? $img : $img;
    // ensure relative path works from public
    if (strpos($profileImage, 'uploads/') === 0) {
        $profileImage = $profileImage; // relative from public
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TravelMate - Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/Traveller/dashboard.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
  <!-- Navbar -->
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <main>
    <aside class="sidebar">
      <ul class="sidebar-menu">
        <li class="sidebar-item">
          <a href="mybookings" class="sidebar-link">
            <!-- <span class="sidebar-icon">🏨</span> -->
            <span class="sidebar-text">Accommodation Bookings</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="mytransportbookings" class="sidebar-link">
            <!-- <span class="sidebar-icon">🚗</span> -->
            <span class="sidebar-text">Transport Bookings</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="feed" class="sidebar-link">
            <!-- <span class="sidebar-icon">📹</span> -->
            <span class="sidebar-text">Vlogs</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="profile_setting" class="sidebar-link">
            <!-- <span class="sidebar-icon">⚙️</span> -->
            <span class="sidebar-text">Settings</span>
          </a>
        </li>
        <li class="sidebar-item sidebar-logout">
          <a href="logout.php" class="sidebar-link logout-link">
            <!-- <span class="sidebar-icon">🚪</span> -->
            <span class="sidebar-text">Log Out</span>
          </a>
        </li>
      </ul>
    </aside>
    <section class="dashboard-content">
      <!-- Cover -->
      <div class="cover">
        <img src="assets/images/cover.jpg" alt="Travel Cover" class="cover-img">
        <span class="cover-text">TRAVEL <span class="cover-sub">more</span></span>
      </div>
      <!-- Profile -->
      <div class="profile-section">
        <?php 
        $rootUrl = defined('ROOT') ? ROOT : '/TravelMate/public';
        $profileImg = !empty($_SESSION['user']['profile_image']) ? $rootUrl . '/' . $_SESSION['user']['profile_image'] : 'assets/images/profile.jpg';
        ?>
        <img src="<?php echo htmlspecialchars($profileImg); ?>" alt="User" class="profile-pic">
        <div>
          <h2 class="profile-name"><?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?></h2>
          <span class="profile-email"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
        </div>
      </div>

      <!-- Activity Summary -->
      <div class="activity-summary" style="margin-top: 30px;">
        <h3>Activity Summary</h3>
        <div class="summary-stats">
          <div class="stat">
            <span class="stat-num"><?php echo isset($accBookingsCount) ? $accBookingsCount : 0; ?></span>
            <span class="stat-label">Accommodation Bookings</span>
          </div>
          <div class="stat">
            <span class="stat-num"><?php echo isset($transBookingsCount) ? $transBookingsCount : 0; ?></span>
            <span class="stat-label">Transport Bookings</span>
          </div>
          <div class="stat">
            <span class="stat-num"><?php echo isset($posts) ? count($posts) : 0; ?></span>
            <span class="stat-label">Posts Shared</span>
          </div>
        </div>
      </div>

      <!-- My Posts Section -->
      <div class="my-posts-section" style="margin-top: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <h2>Shared Posts (<?php echo isset($posts) && is_array($posts) ? count($posts) : 0; ?> posts)</h2>
          <a href="blog" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 8px; font-weight: 500;">Create New Post</a>
        </div>
        
        <?php 
        // Debug posts
        if (isset($posts) && is_array($posts) && count($posts) > 0) {
            error_log("Dashboard - Number of posts: " . count($posts));
            foreach ($posts as $idx => $post) {
                error_log("Post $idx - ID: " . (isset($post->id) ? $post->id : 'NOT SET') . ", Title: " . (isset($post->title) ? $post->title : 'NOT SET'));
            }
        }
        ?>
        
        <?php if (isset($posts) && is_array($posts) && count($posts) > 0): ?>
          <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php foreach ($posts as $post): ?>
              <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.2s;">
                <?php if (!empty($post->image)): ?>
                  <img src="<?php echo htmlspecialchars($post->image); ?>" alt="<?php echo htmlspecialchars($post->title ?? 'Post image'); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div style="padding: 15px;">
                  <h4 style="margin: 0 0 10px 0; color: #333; font-size: 18px;"><?php echo htmlspecialchars($post->title); ?></h4>
                  <p style="color: #666; margin: 0 0 10px 0; font-size: 14px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars($post->description ?? ''); ?></p>
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                    <div style="display: flex; align-items: center; gap: 8px; color: #666; font-size: 13px;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                      </svg>
                      <span><?php echo htmlspecialchars($post->location ?? ''); ?></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                      <span style="color: #999; font-size: 12px;"><?php echo isset($post->created_at) ? date('M d, Y', strtotime($post->created_at)) : ''; ?></span>
                      <button onclick="deletePost(<?php echo intval($post->id); ?>)" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; display: flex; align-items: center; gap: 5px;" title="Delete post" data-post-id="<?php echo intval($post->id); ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Delete
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" style="margin: 0 auto 20px;">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="12" y1="18" x2="12" y2="12"></line>
              <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
            <h3 style="margin-bottom: 10px; color: #333;">No posts yet</h3>
            <p style="color: #666; margin-bottom: 25px;">Start sharing your travel experiences!</p>
            <a href="blog" style="display: inline-block; padding: 12px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 8px; font-weight: 500;">Create Your First Post</a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
  
  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <!-- Delete Confirmation Modal -->
  <div id="deleteConfirmModal" class="delete-modal">
    <div class="delete-modal-content">
      <div class="delete-icon">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="10" stroke="#ef4444" stroke-width="2" fill="#fee2e2"/>
          <path d="M12 8v4M12 16h.01" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </div>
      <h2>Delete Post?</h2>
      <p>Are you sure you want to delete this post? This action cannot be undone.</p>
      <div class="modal-actions">
        <button class="btn-cancel" onclick="closeDeleteConfirmModal()">Cancel</button>
        <button class="btn-delete" onclick="confirmDeletePost()">Delete</button>
      </div>
    </div>
  </div>

  <!-- Delete Success Modal -->
  <div id="deleteSuccessModal" class="delete-modal">
    <div class="delete-modal-content">
      <div class="success-icon">
        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="30" cy="30" r="28" stroke="#10b981" stroke-width="3" fill="#ecfdf5"/>
          <path d="M18 30L26 38L42 22" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <h2>Post Deleted Successfully!</h2>
      <p>Your post has been removed from your profile.</p>
      <div class="modal-actions">
        <button class="btn-done" onclick="closeDeleteSuccessModal()">Done</button>
      </div>
    </div>
  </div>

  <style>
    /* Modal Styles */
    .delete-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 9999;
      animation: fadeIn 0.3s ease;
      align-items: center;
      justify-content: center;
    }

    .delete-modal.show {
      display: flex;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes scaleIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    .delete-modal-content {
      background: white;
      border-radius: 20px;
      padding: 2.5em;
      text-align: center;
      max-width: 420px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.4s ease;
    }

    .delete-icon,
    .success-icon {
      margin-bottom: 1.2em;
      animation: scaleIn 0.5s ease 0.2s both;
    }

    .delete-modal-content h2 {
      color: #1f2937;
      font-size: 24px;
      margin: 0 0 0.5em 0;
      font-weight: 700;
    }

    .delete-modal-content p {
      color: #6b7280;
      font-size: 15px;
      margin: 0 0 2em 0;
      line-height: 1.6;
    }

    .modal-actions {
      display: flex;
      gap: 1em;
      justify-content: center;
    }

    .btn-cancel,
    .btn-delete,
    .btn-done {
      border: none;
      padding: 0.85em 2em;
      border-radius: 30px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-cancel {
      background: #f3f4f6;
      color: #374151;
    }

    .btn-cancel:hover {
      background: #e5e7eb;
      transform: translateY(-2px);
    }

    .btn-delete {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .btn-delete:hover {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    .btn-done {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-done:hover {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }
  </style>

  <script src="dashboard.js"></script>
  <script>
    let postIdToDelete = null;

    function deletePost(postId) {
      console.log('deletePost called with:', postId, 'type:', typeof postId);
      if (!postId || postId === null || postId === 'null') {
        console.error('Invalid post ID:', postId);
        alert('Invalid post ID');
        return;
      }
      postIdToDelete = postId;
      showDeleteConfirmModal();
    }

    function showDeleteConfirmModal() {
      const modal = document.getElementById('deleteConfirmModal');
      modal.classList.add('show');
    }

    function closeDeleteConfirmModal() {
      const modal = document.getElementById('deleteConfirmModal');
      modal.classList.remove('show');
      postIdToDelete = null;
    }

    function confirmDeletePost() {
      if (!postIdToDelete) {
        console.error('No post ID to delete');
        return;
      }

      // Store the ID before closing modal (which resets it)
      const postId = postIdToDelete;
      closeDeleteConfirmModal();
      
      console.log('Deleting post ID:', postId);
      
      // Create form data
      const formData = new FormData();
      formData.append('post_id', postId);
      
      // Send delete request
      fetch('blog/delete', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text();
      })
      .then(text => {
        console.log('Raw response:', text);
        try {
          const data = JSON.parse(text);
          console.log('Parsed data:', data);
          if (data.success) {
            showDeleteSuccessModal();
          } else {
            alert('Error: ' + (data.message || 'Failed to delete post'));
          }
        } catch (e) {
          console.error('JSON parse error:', e);
          console.error('Response was:', text);
          alert('Server returned invalid response');
        }
      })
      .catch(error => {
        console.error('Fetch error:', error);
        alert('An error occurred while deleting the post');
      });
    }

    function showDeleteSuccessModal() {
      const modal = document.getElementById('deleteSuccessModal');
      modal.classList.add('show');
    }

    function closeDeleteSuccessModal() {
      const modal = document.getElementById('deleteSuccessModal');
      modal.classList.remove('show');
      window.location.reload();
    }
  </script>
</body>
</html>