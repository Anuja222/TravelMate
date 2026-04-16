<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Feed</title>
    <link rel="stylesheet" href="assets/css/Traveller/feed.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
    <style>
      .vote-btn.upvote.active { color: #1abc5b !important; }
      .vote-btn.upvote.active svg { stroke: #1abc5b !important; }
      .vote-btn.downvote.active { color: #e74c3c !important; }
      .vote-btn.downvote.active svg { stroke: #e74c3c !important; }
      
      /* Active category style */
      .category-list li.active-category a {
          color: #1abc5b;
          font-weight: 600;
          background-color: rgba(26, 188, 91, 0.1);
          border-radius: 6px;
          padding: 5px 10px;
          display: inline-block;
          width: 100%;
          box-sizing: border-box;
      }
    </style>
</head>
<body>
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- Output Base URL for JS safely -->
  <script>
     window.AppConfig = {
         baseUrl: '<?php echo defined("ROOT") ? ROOT : "http://localhost/TravelMate/public"; ?>'
     };
  </script>

  <!-- Main Feed Container -->
  <div class="feed-wrapper">
    <!-- Left Sidebar - Navigation -->
    <aside class="feed-sidebar left-sidebar">
      <div class="sidebar-section">
        <h3>Quick Access</h3>
        <ul class="sidebar-menu">
          <li><a href="dashboard">Dashboard</a></li>
          <li><a href="mybookings">Accomodation Booking</a></li>
          <li><a href="mytransportbookings">Transport Booking</a></li>
          <li><a href="profile_setting">Settings</a></li>
        </ul>
      </div>
      
      <div class="sidebar-section">
        <h3>Categories</h3>
        <ul class="category-list">
          <li class="<?php echo !isset($_GET['category']) ? 'active-category' : ''; ?>"><a href="feed">All Posts</a></li>
          <li class="<?php echo (isset($_GET['category']) && $_GET['category'] === 'Destination') ? 'active-category' : ''; ?>"><a href="feed?category=Destination">Destination</a></li>
          <li class="<?php echo (isset($_GET['category']) && $_GET['category'] === 'Adventure') ? 'active-category' : ''; ?>"><a href="feed?category=Adventure">Adventure</a></li>
          <li class="<?php echo (isset($_GET['category']) && $_GET['category'] === 'Food & Culture') ? 'active-category' : ''; ?>"><a href="feed?category=Food & Culture">Food & Culture</a></li>
          <li class="<?php echo (isset($_GET['category']) && $_GET['category'] === 'Travel Tips') ? 'active-category' : ''; ?>"><a href="feed?category=Travel Tips">Travel Tips</a></li>
          <li class="<?php echo (isset($_GET['category']) && $_GET['category'] === 'Accommodation') ? 'active-category' : ''; ?>"><a href="feed?category=Accommodation">Accommodation</a></li>
          <li class="<?php echo (isset($_GET['category']) && $_GET['category'] === 'Transportation') ? 'active-category' : ''; ?>"><a href="feed?category=Transportation">Transportation</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main Feed Content -->
    <main class="feed-main">
      <!-- Create Post Section -->
      <div class="create-post-card">
        <div class="create-post-header">
          <?php 
          $rootUrl = defined('ROOT') ? ROOT : '/TravelMate/public';
          $currentUserProfileImg = !empty($_SESSION['user']['profile_image']) ? $rootUrl . '/' . $_SESSION['user']['profile_image'] : 'assets/images/profile.jpg';
          ?>
          <img src="<?php echo htmlspecialchars($currentUserProfileImg); ?>" alt="Your Profile" class="user-avatar" onerror="this.src='assets/images/profile.jpg'">
          <a href="blog" class="create-post-input">
            <span class="placeholder-text">Share your travel story...</span>
          </a>
        </div>
        <div class="create-post-actions">
          <a href="blog" class="post-action-btn">
            <span class="action-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg></span>
            Photo
          </a>
          <a href="blog" class="post-action-btn">
            <span class="action-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></span>
            Video
          </a>
          <a href="blog" class="post-action-btn">
            <span class="action-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>
            Location
          </a>
        </div>
      </div>

      <!-- Feed Posts -->
      <div class="feed-posts">
        <?php if (isset($posts) && is_array($posts) && count($posts) > 0): ?>
          <?php foreach ($posts as $post): ?>
            <article class="post-card" data-category="<?php echo htmlspecialchars($post->category ?? ''); ?>">
              <div class="post-header">
                <a href="profile?username=<?php echo urlencode($post->email ?? ''); ?>" style="text-decoration: none;">
                  <?php 
                  $rootUrl = defined('ROOT') ? ROOT : '/TravelMate/public';
                  $authorProfileImg = !empty($post->profile_image) ? $rootUrl . '/' . $post->profile_image : 'assets/images/profile.jpg';
                  ?>
                  <img src="<?php echo htmlspecialchars($authorProfileImg); ?>" alt="<?php echo htmlspecialchars(($post->first_name ?? '') . ' ' . ($post->last_name ?? '')); ?>" class="user-avatar" onerror="this.src='assets/images/profile.jpg'">
                </a>
                <div class="user-info">
                  <a href="profile?username=<?php echo urlencode($post->email ?? ''); ?>" style="text-decoration: none; color: inherit;">
                    <h4><?php echo htmlspecialchars(($post->first_name ?? 'Anonymous') . ' ' . ($post->last_name ?? '')); ?></h4>
                  </a>
                  <p class="post-meta">
                    <span class="time"><?php echo isset($post->created_at) ? date('M d, Y', strtotime($post->created_at)) : ''; ?></span>
                    <span class="separator">•</span>
                    <span class="location">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                      </svg>
                      <?php echo htmlspecialchars($post->location ?? ''); ?>
                    </span>
                  </p>
                </div>
                <button class="post-menu-btn">⋯</button>
              </div>

              <div class="post-body">
                <?php if (!empty($post->title)): ?>
                  <h3 class="post-title"><?php echo htmlspecialchars($post->title); ?></h3>
                <?php endif; ?>
                <p class="post-text"><?php echo htmlspecialchars($post->description ?? ''); ?></p>
              </div>

              <?php if (!empty($post->image)): ?>
              <div class="post-image-container">
                <img src="<?php echo htmlspecialchars($post->image); ?>" alt="<?php echo htmlspecialchars($post->title ?? 'Post image'); ?>" class="post-image">
              </div>
              <?php endif; ?>

              <div class="post-actions">
                <button class="action-btn vote-btn upvote <?php echo (isset($post->user_vote) && $post->user_vote === 'upvote') ? 'active' : ''; ?>" data-id="<?php echo $post->id; ?>" data-type="upvote">
                  <span class="action-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                  </span>
                  <span class="action-text count"><?php echo $post->upvotes ?? 0; ?></span>
                </button>
                <button class="action-btn vote-btn downvote <?php echo (isset($post->user_vote) && $post->user_vote === 'downvote') ? 'active' : ''; ?>" data-id="<?php echo $post->id; ?>" data-type="downvote">
                  <span class="action-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                  </span>
                  <span class="action-text count"><?php echo $post->downvotes ?? 0; ?></span>
                </button>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-posts" style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; margin: 20px 0;">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 20px; opacity: 0.3;">
              <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
            <h3 style="margin-bottom: 10px; color: #333;">No posts yet</h3>
            <p style="color: #666; margin-bottom: 25px;">Be the first to share your travel story!</p>
            <a href="blog" style="display: inline-block; padding: 12px 30px; background: #FF6B6B; color: white; text-decoration: none; border-radius: 8px; font-weight: 500;">Create a Post</a>
          </div>
        <?php endif; ?>
      </div>
    </main>

    <!-- Right Sidebar - Suggestions -->
    <aside class="feed-sidebar right-sidebar">
      <div class="sidebar-section">
        <h3>Trending Destinations</h3>
        <div class="trending-list">
          <?php if (isset($destinations) && is_array($destinations) && count($destinations) > 0): ?>
            <?php foreach ($destinations as $destination): ?>
              <a href="destinationview?id=<?php echo $destination->id; ?>" class="trending-item">
                <?php 
                  $imagePath = $destination->image ?? 'assets/images/contact.jpg';
                  // Remove leading slash if present to make path relative
                  if (substr($imagePath, 0, 1) === '/') {
                    $imagePath = substr($imagePath, 1);
                  }
                ?>
                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($destination->title); ?>" class="trending-img">
                <div class="trending-info">
                  <h5><?php echo htmlspecialchars($destination->title); ?></h5>
                </div>
              </a>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="trending-item">
              <img src="assets/images/contact.jpg" alt="Destination" class="trending-img">
              <div class="trending-info">
                <h5>Explore Sri Lanka</h5>
                <p>Start your journey</p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="sidebar-section">
        <h3>Popular Hashtags</h3>
        <div class="hashtags">
          <a href="#" class="hashtag">#TravelSriLanka</a>
          <a href="#" class="hashtag">#BeachLife</a>
          <a href="#" class="hashtag">#MountainViews</a>
          <a href="#" class="hashtag">#CulturalHeritage</a>
          <a href="#" class="hashtag">#FoodTravel</a>
          <a href="#" class="hashtag">#AdventureTime</a>
        </div>
      </div>

      <div class="sidebar-footer">
        <p>&copy; 2026 TravelMate</p>
        <div class="footer-links">
          <a href="#">About</a>
          <a href="#">Help</a>
          <a href="#">Terms</a>
          <a href="#">Privacy</a>
        </div>
      </div>
    </aside>
  </div>

  <script src="assets/js/feed.js?v=<?php echo time(); ?>"></script>
  </body>
</html>