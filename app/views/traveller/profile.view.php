<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')); ?> - TravelMate</title>
    <link rel="stylesheet" href="assets/css/Traveller/profile.css">
    <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- Profile Container -->
  <div class="profile-wrapper">
    <!-- Cover Photo -->
    <div class="cover">
      <img src="assets/images/cover.jpg" alt="Travel Cover" class="cover-img">
      <span class="cover-text">TRAVEL <span class="cover-sub">more</span></span>
    </div>
    
    <!-- Profile Section -->
    <div class="profile-section">
      <img src="assets/images/profile.jpg" alt="User" class="profile-pic">
      <div>
        <h2 class="profile-name"><?php echo htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')); ?></h2>
        <span class="profile-email"><?php echo htmlspecialchars($user->email ?? ''); ?></span>
      </div>
    </div>

    <!-- My Posts Section -->
    <div class="my-posts-section">
      <div class="posts-header">
        <h2>Shared Posts (<?php echo isset($posts) && is_array($posts) ? count($posts) : 0; ?> posts)</h2>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == ($user->id ?? null)): ?>
          <a href="blog" class="btn-create-post">Create New Post</a>
        <?php endif; ?>
      </div>

      <!-- Profile Content -->
      <div class="profile-content">
        <!-- Left Sidebar -->
        <aside class="profile-sidebar">
        <div class="sidebar-card">
          <h3>About</h3>
          <div class="about-section">
            <div class="about-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              <div>
                <p class="label">Member Since</p>
                <p class="value"><?php echo isset($user->created_at) ? date('F Y', strtotime($user->created_at)) : 'Recently'; ?></p>
              </div>
            </div>
            
            <?php if (!empty($user->email)): ?>
            <div class="about-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
              <div>
                <p class="label">Email</p>
                <p class="value"><?php echo htmlspecialchars($user->email); ?></p>
              </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($user->phone)): ?>
            <div class="about-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
              </svg>
              <div>
                <p class="label">Phone</p>
                <p class="value"><?php 
                  $phone = $user->phone;
                  // Format phone numbers for better readability
                  if (strlen($phone) == 10) {
                    // Format as: XXX XXX XXXX
                    echo htmlspecialchars(substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6, 4));
                  } elseif (strlen($phone) == 9) {
                    // Format as: XX XXX XXXX
                    echo htmlspecialchars(substr($phone, 0, 2) . ' ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 4));
                  } else {
                    echo htmlspecialchars($phone);
                  }
                ?></p>
              </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($user->date_of_birth)): ?>
            <div class="about-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
              <div>
                <p class="label">Birthday</p>
                <p class="value"><?php echo date('F d, Y', strtotime($user->date_of_birth)); ?></p>
              </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($user->country)): ?>
            <div class="about-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
              </svg>
              <div>
                <p class="label">Location</p>
                <p class="value"><?php echo htmlspecialchars($user->city && $user->country ? $user->city . ', ' . $user->country : ($user->country ?? $user->city)); ?></p>
              </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($user->gender)): ?>
            <div class="about-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 6v6l4 2"></path>
              </svg>
              <div>
                <p class="label">Gender</p>
                <p class="value"><?php echo htmlspecialchars(ucfirst($user->gender)); ?></p>
              </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($user->bio)): ?>
            <div class="about-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              <div>
                <p class="label">Bio</p>
                <p class="value"><?php echo htmlspecialchars($user->bio); ?></p>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </aside>

      <!-- Main Content Area -->
      <div class="profile-main">
        <!-- Navigation Tabs -->
        <div class="profile-tabs">
          <button class="tab-btn active" data-tab="posts">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7"></rect>
              <rect x="14" y="3" width="7" height="7"></rect>
              <rect x="14" y="14" width="7" height="7"></rect>
              <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            Posts
          </button>
          <button class="tab-btn" data-tab="photos">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <circle cx="8.5" cy="8.5" r="1.5"></circle>
              <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
            Photos
          </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content active" id="posts-tab">
          <?php if (isset($posts) && count($posts) > 0): ?>
            <div class="posts-grid">
              <?php foreach ($posts as $post): ?>
                <article class="profile-post-card">
                  <div class="post-header">
                    <div class="post-user-info">
                      <img src="assets/images/profile.jpg" alt="Profile" class="post-avatar">
                      <div>
                        <h4><?php echo htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')); ?></h4>
                        <p class="post-date"><?php echo isset($post->created_at) ? date('M d, Y', strtotime($post->created_at)) : ''; ?></p>
                      </div>
                    </div>
                  </div>

                  <?php if (!empty($post->image)): ?>
                  <div class="post-image">
                    <img src="<?php echo htmlspecialchars($post->image); ?>" alt="<?php echo htmlspecialchars($post->title ?? ''); ?>">
                  </div>
                  <?php endif; ?>

                  <div class="post-content">
                    <?php if (!empty($post->title)): ?>
                      <h3><?php echo htmlspecialchars($post->title); ?></h3>
                    <?php endif; ?>
                    <p><?php echo htmlspecialchars(substr($post->description ?? '', 0, 150)); ?><?php echo strlen($post->description ?? '') > 150 ? '...' : ''; ?></p>
                    
                    <?php if (!empty($post->location)): ?>
                    <div class="post-location">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                      </svg>
                      <span><?php echo htmlspecialchars($post->location); ?></span>
                    </div>
                    <?php endif; ?>
                  </div>

                  <div class="post-footer">
                    <button class="post-action-btn">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                      </svg>
                      <span>0</span>
                    </button>
                    <button class="post-action-btn">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                      </svg>
                      <span>0</span>
                    </button>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="empty-state">
              <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
              </svg>
              <h3>No posts yet</h3>
              <p>This traveler hasn't shared any stories yet.</p>
            </div>
          <?php endif; ?>
        </div>

        <div class="tab-content" id="photos-tab">
          <div class="photos-grid">
            <?php if (isset($posts) && count($posts) > 0): ?>
              <?php foreach ($posts as $post): ?>
                <?php if (!empty($post->image)): ?>
                  <div class="photo-item">
                    <img src="<?php echo htmlspecialchars($post->image); ?>" alt="<?php echo htmlspecialchars($post->title ?? ''); ?>">
                    <div class="photo-overlay">
                      <div class="photo-stats">
                        <span><svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> 0</span>
                        <span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> 0</span>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="empty-state">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <h3>No photos yet</h3>
                <p>No travel photos shared yet.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/profile.js"></script>
</body>
</html>
