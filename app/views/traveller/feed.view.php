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

  <!-- Hero Section -->
  <section class="hero-section">
    <!-- <img src="assets/images/cover.jpg" alt="About Hero"> -->
    <div class="hero-background">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Travel Feed</h1>
          <p>Discover amazing travel experiences from our community</p>
          <!-- <div class="hero-actions">
            <button class="btn-primary" onclick="scrollToDestinations()">Explore Destinations</button>
            <button class="btn-secondary" onclick="openBookingModal()">Book Your Trip</button>
          </div> -->
        </div>
      </div>
    </div>
  </section>
    <!-- Feed Content -->
    <main class="feed-container">
        <div class="feed-header">
            <!-- <h1>Travel Feed</h1>
            <p>Discover amazing travel experiences from our community</p> -->
            <a href="blog" class="create-post-btn">Share Your Adventure</a>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="tab-btn active">All Posts</button>
            <button class="tab-btn">Destinations</button>
            <button class="tab-btn">Adventures</button>
            <button class="tab-btn">Tips</button>
        </div>

        <!-- Feed Posts -->
        <div class="feed-posts">
            <!-- Post 1 -->
            <article class="post-card">
                <div class="post-header">
                    <img src="assets/images/profile.jpg" alt="Anuja Wanigasekara" class="user-avatar">
                    <div class="user-info">
                        <h4>Anuja Wanigasekara</h4>
                        <p>2 hours ago • Badulla, Ella</p>
                    </div>
                </div>
                <img src="assets/images/contact.jpg" alt="Bali Sunset" class="post-image">
                <div class="post-content">
                    <h3>Magical Sunset at Tanah Lot Temple</h3>
                    <p>Just witnessed one of the most breathtaking sunsets at Tanah Lot Temple in Bali! The way the golden light reflects off the ancient temple perched on the rock formation is absolutely mesmerizing. This place should definitely be on everyone's Bali bucket list! 🌅</p>
                    <div class="post-actions">
                        <button class="action-btn">❤️ 127 Likes</button>
                    </div>
                </div>
            </article>

            <!-- Post 2 -->
            <article class="post-card">
                <div class="post-header">
                    <img src="../../images/user2.jpg" alt="Minoli Isakya" class="user-avatar">
                    <div class="user-info">
                        <h4>Minoli Isakya</h4>
                        <p>1 day ago • Anuradhapura</p>
                    </div>
                </div>
                <img src="../../images/kyoto-bamboo.jpg" alt="Bamboo Forest" class="post-image">
                <div class="post-content">
                    <h3>Lost in the Bamboo Forest</h3>
                    <p>Walking through the Arashiyama Bamboo Grove feels like stepping into another world. The towering bamboo creates this incredible natural cathedral with filtered green light. Pro tip: visit early morning to avoid crowds and capture that perfect shot! 🎋</p>
                    <div class="post-actions">
                        <button class="action-btn">❤️ 89 Likes</button>
                    </div>
                </div>
            </article>

            <!-- Post 3 -->
            <article class="post-card">
                <div class="post-header">
                    <img src="../../images/user3.jpg" alt="Amra Shafwan" class="user-avatar">
                    <div class="user-info">
                        <h4>Amra Shafwan</h4>
                        <p>3 days ago • Nuwaraeliya</p>
                    </div>
                </div>
                <img src="../../images/santorini-view.jpg" alt="Santorini View" class="post-image">
                <div class="post-content">
                    <h3>Blue Domes and Endless Views</h3>
                    <p>Santorini never fails to take my breath away! These iconic blue-domed churches against the backdrop of the Aegean Sea create the most picture-perfect scenery. Every corner of Oia offers a new perspective that's more beautiful than the last. Already planning my next visit! 💙</p>
                    <div class="post-actions">
                        <button class="action-btn">❤️ 203 Likes</button>
                    </div>
                </div>
            </article>
        </div>
    </main>

    
    <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>
  </body>
</html>