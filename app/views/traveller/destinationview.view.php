<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - <?php echo htmlspecialchars($destination?->title ?? 'Destination'); ?></title>
  <link rel="stylesheet" href="assets/css/Traveller/beach.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
  
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-background" style="background-image: url('<?php 
      $imagePath = is_object($destination) ? ($destination->image ?? 'assets/images/default-dest.png') : 'assets/images/default-dest.png';
      if (substr($imagePath, 0, 1) === '/') {
        $imagePath = substr($imagePath, 1);
      }
      echo htmlspecialchars($imagePath); 
    ?>');">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Discover <?php echo htmlspecialchars(is_object($destination) ? ($destination->title ?? 'Paradise') : 'Paradise'); ?></h1>
          <p><?php echo htmlspecialchars(is_object($destination) ? ($destination->description ?? 'Explore the most beautiful destinations Sri Lanka has to offer') : 'Explore the most beautiful destinations Sri Lanka has to offer'); ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Places/Locations Section -->
  <section class="beaches-section">
    <div class="container">
      <div class="section-header">
        <h2>Popular Places in <?php echo htmlspecialchars(is_object($destination) ? ($destination->title ?? 'Sri Lanka') : 'Sri Lanka'); ?></h2>
        <p>Discover amazing locations and experiences</p>
      </div>

      <?php if (isset($places) && count($places) > 0): ?>
        <div class="beaches-grid">
          <?php foreach ($places as $place): ?>
            <div class="card" data-location="<?php echo htmlspecialchars($place->slug ?? ''); ?>">
              <div class="card-image">
                <?php 
                  $placeImage = $place->image ?? 'assets/images/default-place.png';
                  if (substr($placeImage, 0, 1) === '/') {
                    $placeImage = substr($placeImage, 1);
                  }
                ?>
                <img src="<?php echo htmlspecialchars($placeImage); ?>" alt="<?php echo htmlspecialchars($place->title ?? ''); ?>">
                <div class="card-overlay">
                  <button class="explore-btn" onclick="explorePlace(<?php echo $place->id; ?>)">Explore</button>
                </div>
              </div>
              <div class="card-content">
                <h3><?php echo htmlspecialchars($place->title ?? ''); ?></h3>
                <p><?php echo htmlspecialchars(substr($place->description ?? '', 0, 150)); ?><?php echo strlen($place->description ?? '') > 150 ? '...' : ''; ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-places" style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; margin: 40px 0;">
          <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 20px; opacity: 0.3;">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <h3 style="margin-bottom: 10px; color: #333;">Places Coming Soon</h3>
          <p style="color: #666;">We're adding amazing places to this destination. Check back soon!</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Related Posts Section -->
  <?php if (isset($relatedPosts) && count($relatedPosts) > 0): ?>
  <section class="beaches-section" style="background: #f8f9fa; padding: 60px 0;">
    <div class="container">
      <div class="section-header">
        <h2>Traveler Stories</h2>
        <p>See what other travelers have shared about <?php echo htmlspecialchars(is_object($destination) ? ($destination->title ?? 'this destination') : 'this destination'); ?></p>
      </div>

      <div class="beaches-grid">
        <?php foreach ($relatedPosts as $post): ?>
          <div class="card">
            <div class="card-image">
              <?php if (!empty($post->image)): ?>
                <img src="<?php echo htmlspecialchars($post->image); ?>" alt="<?php echo htmlspecialchars($post->title ?? ''); ?>">
              <?php else: ?>
                <img src="assets/images/default-post.png" alt="Post">
              <?php endif; ?>
              <div class="card-overlay">
                <a href="profile?username=<?php echo urlencode($post->email ?? ''); ?>" class="explore-btn">View Post</a>
              </div>
            </div>
            <div class="card-content">
              <h3><?php echo htmlspecialchars($post->title ?? 'Untitled'); ?></h3>
              <p><?php echo htmlspecialchars(substr($post->description ?? '', 0, 120)); ?><?php echo strlen($post->description ?? '') > 120 ? '...' : ''; ?></p>
              <div style="margin-top: 10px; display: flex; align-items: center; gap: 8px; color: #666; font-size: 0.9em;">
                <span>By <?php echo htmlspecialchars(($post->first_name ?? '') . ' ' . ($post->last_name ?? '')); ?></span>
                <span>•</span>
                <span><?php echo isset($post->created_at) ? date('M d, Y', strtotime($post->created_at)) : ''; ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    function explorePlace(placeId) {
      console.log('Exploring place:', placeId);
      // You can add functionality here to show place details or navigate to a place detail page
      alert('Place details coming soon!');
    }
  </script>
</body>
</html>
