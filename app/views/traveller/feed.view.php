<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate - Feed</title>
    <link rel="stylesheet" href="assets/css/Traveller/feed.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

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
          <li><a href="#">Beaches</a></li>
          <li><a href="#">Mountains</a></li>
          <li><a href="#">Historical</a></li>
          <li><a href="#">Food & Culture</a></li>
          <li><a href="#">Adventure</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main Feed Content -->
    <main class="feed-main">
      <!-- Create Post Section -->
      <div class="create-post-card">
        <div class="create-post-header">
          <img src="assets/images/profile.jpg" alt="Your Profile" class="user-avatar">
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

      <!-- Filter Tabs -->
      <div class="filter-tabs-container">
        <div class="filter-tabs">
          <button class="tab-btn active" data-filter="all">
            <span class="tab-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg></span>
            All Posts
          </button>
          <button class="tab-btn" data-filter="destinations">
            <span class="tab-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg></span>
            Destinations
          </button>
          <button class="tab-btn" data-filter="adventures">
            <span class="tab-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="7" width="14" height="12" rx="2" ry="2"></rect><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path></svg></span>
            Adventures
          </button>
          <button class="tab-btn" data-filter="tips">
            <span class="tab-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18h6"></path><path d="M10 22h4"></path><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8c0-3.31-2.69-6-6-6S6 4.69 6 8c0 1.45.5 2.5 1.5 3.5.76.76 1.23 1.52 1.41 2.5"></path></svg></span>
            Tips & Guides
          </button>
        </div>
      </div>

      <!-- Feed Posts -->
      <div class="feed-posts">
        <?php if (isset($posts) && is_array($posts) && count($posts) > 0): ?>
          <?php foreach ($posts as $post): ?>
            <article class="post-card" data-category="<?php echo htmlspecialchars($post->category ?? ''); ?>">
              <div class="post-header">
                <a href="profile?username=<?php echo urlencode($post->email ?? ''); ?>" style="text-decoration: none;">
                  <img src="assets/images/profile.jpg" alt="<?php echo htmlspecialchars(($post->first_name ?? '') . ' ' . ($post->last_name ?? '')); ?>" class="user-avatar">
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

              <div class="post-stats">
                <div class="stats-left">
                  <span class="reaction-count">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="red" stroke="red" stroke-width="2" style="display: inline; vertical-align: middle;">
                      <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg> 0
                  </span>
                  <span class="comment-count">0 comments</span>
                </div>
              </div>

              <div class="post-actions">
                <button class="action-btn like-btn">
                  <span class="action-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                  </span>
                  <span class="action-text">Like</span>
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
              <?php 
                $postCount = isset($destinationPostCounts[$destination->id]) ? $destinationPostCounts[$destination->id] : 0;
              ?>
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
                  <p><?php echo $postCount; ?> post<?php echo $postCount != 1 ? 's' : ''; ?></p>
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
        <h3>Suggested Travelers</h3>
        <div class="suggestions-list">
          <?php if (isset($suggestedTravelers) && is_array($suggestedTravelers) && count($suggestedTravelers) > 0): ?>
            <?php foreach ($suggestedTravelers as $traveler): ?>
              <div class="suggestion-item">
                <a href="profile?username=<?php echo urlencode($traveler->email); ?>" style="text-decoration: none;">
                  <img src="assets/images/profile.jpg" alt="<?php echo htmlspecialchars($traveler->first_name . ' ' . $traveler->last_name); ?>" class="suggestion-avatar">
                </a>
                <div class="suggestion-info">
                  <a href="profile?username=<?php echo urlencode($traveler->email); ?>" style="text-decoration: none; color: inherit;">
                    <h5><?php echo htmlspecialchars($traveler->first_name . ' ' . $traveler->last_name); ?></h5>
                  </a>
                  <p><?php echo isset($travelerFollowerCounts[$traveler->id]) ? $travelerFollowerCounts[$traveler->id] : 0; ?> followers</p>
                </div>
                <button class="follow-btn">Follow</button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="suggestion-item">
              <img src="assets/images/profile.jpg" alt="User" class="suggestion-avatar">
              <div class="suggestion-info">
                <h5>No travelers yet</h5>
                <p>Be the first!</p>
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

  <script src="assets/js/feed.js"></script>
  </body>
</html>