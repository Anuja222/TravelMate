<?php 
// Convert the PHP objects/arrays to a format JS can easily use
$placesJson = isset($places) ? json_encode($places) : '[]';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - <?php echo htmlspecialchars($destination?->title ?? 'Destination'); ?></title>
  <link rel="stylesheet" href="assets/css/Traveller/beach.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
  <style>
    /* Modal Styles */
    .place-modal {
      display: none; 
      position: fixed; 
      z-index: 9999; 
      left: 0;
      top: 0;
      width: 100%; 
      height: 100%; 
      overflow: auto; 
      background-color: rgba(0,0,0,0.6); 
      backdrop-filter: blur(5px);
      align-items: center;
      justify-content: center;
    }
    .place-modal.show {
      display: flex;
    }
    .modal-content {
      background-color: #fff;
      margin: auto;
      border-radius: 15px;
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 20px 40px rgba(0,0,0,0.2);
      position: relative;
      animation: modalSlideIn 0.3s ease-out;
    }
    @keyframes modalSlideIn {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .close-btn {
      position: absolute;
      top: 15px;
      right: 20px;
      color: #333;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      background: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      z-index: 10;
      transition: all 0.2s;
    }
    .close-btn:hover {
      background: #f1f1f1;
      transform: scale(1.05);
    }
    .modal-image {
      width: 100%;
      height: 350px;
      object-fit: cover;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }
    .modal-body {
      padding: 40px;
    }
    .modal-title {
      font-size: 2.2rem;
      margin-top: 0;
      margin-bottom: 15px;
      color: #2c3e50;
      font-family: 'Poppins', sans-serif;
    }
    .modal-description {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #555;
      margin-bottom: 25px;
      white-space: pre-wrap;
    }
    .modal-meta {
      display: flex;
      gap: 20px;
      border-top: 1px solid #eee;
      padding-top: 20px;
      color: #7f8fa6;
      font-size: 0.95rem;
    }
    .modal-meta i {
      margin-right: 8px;
      color: #1abc9c;
    }
    .modal-action-btn:hover {
      filter: brightness(0.9);
      transform: translateY(-2px);
    }
  </style>
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
                  <!-- The onclick calls our new openModal function passing the ID -->
                  <button class="explore-btn" onclick="openPlaceModal(<?php echo $place->id; ?>)">Explore</button>
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

  <!-- THE MODAL HTML -->
  <div id="placeModal" class="place-modal" onclick="closePlaceModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
      <div class="close-btn" onclick="closePlaceModal(event)">&times;</div>
      <img id="modalImage" class="modal-image" src="" alt="Place Image">
      <div class="modal-body">
        <h2 id="modalTitle" class="modal-title"></h2>
        
        <div class="place-meta-details" style="margin-bottom: 20px; font-size: 0.95rem; display: flex; flex-direction: column; gap: 8px;">
            <div id="modalLocationWrap" style="display: none;">
                <i class="fas fa-map-marker-alt" style="color: #FF5A5F; width: 20px;"></i>
                <span id="modalLocation"></span>
            </div>
            <div id="modalBestTimeWrap" style="display: none;">
                <i class="fas fa-sun" style="color: #FFB400; width: 20px;"></i>
                <strong>Best time:</strong> <span id="modalBestTime"></span>
            </div>
        </div>

        <div id="modalDescription" class="modal-description"></div>
        <div class="modal-meta" style="margin-top: 15px; margin-bottom: 20px;">
          <span><i class="fas fa-calendar-alt"></i> Added on <span id="modalDate"></span></span>
        </div>

        <div class="modal-actions" style="display: flex; gap: 10px; flex-wrap: wrap; border-top: 1px solid #eee; padding-top: 20px;">
            <a href="favactivity" class="modal-action-btn" style="background-color: #2ecc71; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 8px; flex: 1; justify-content: center; transition: background 0.3s;">
                <i class="fas fa-skating"></i> Find Activities
            </a>
            <a href="accommodation" class="modal-action-btn" style="background-color: #e67e22; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 8px; flex: 1; justify-content: center; transition: background 0.3s;">
                <i class="fas fa-hotel"></i> Find Accommodations
            </a>
            <a href="transport" class="modal-action-btn" style="background-color: #3498db; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 8px; flex: 1; justify-content: center; transition: background 0.3s;">
                <i class="fas fa-car"></i> Find Transport
            </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Load FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script>
    // Make PHP data available to JS
    const placesData = <?php echo $placesJson; ?>;
    const modal = document.getElementById('placeModal');

    function openPlaceModal(placeId) {
      // Find the specific place object
      const place = placesData.find(p => p.id == placeId);
      
      if (!place) {
          alert("Could not load place details.");
          return;
      }

      // Populate Image - Handle leading slash
      let imgPath = place.image || 'assets/images/default-place.png';
      if (imgPath.startsWith('/')) {
          imgPath = imgPath.substring(1);
      }
      document.getElementById('modalImage').src = imgPath;

      // Populate Title & Description
      document.getElementById('modalTitle').textContent = place.title || 'Unknown Place';
      document.getElementById('modalDescription').textContent = place.description || 'No description available for this place.';

      // Extra Details
      const locWrap = document.getElementById('modalLocationWrap');
      if (place.location && place.location.trim() !== '') {
          document.getElementById('modalLocation').textContent = place.location;
          locWrap.style.display = 'block';
      } else {
          locWrap.style.display = 'none';
      }

      const btWrap = document.getElementById('modalBestTimeWrap');
      if (place.best_time && place.best_time.trim() !== '') {
          document.getElementById('modalBestTime').textContent = place.best_time;
          btWrap.style.display = 'block';
      } else {
          btWrap.style.display = 'none';
      }

      // Format Date
      let dateText = '';
      if(place.created_at) {
          const d = new Date(place.created_at);
          dateText = d.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
      }
      document.getElementById('modalDate').textContent = dateText;

      // Show modal
      modal.classList.add('show');
      document.body.style.overflow = 'hidden'; // prevent background scrolling
    }

    function closePlaceModal(event) {
      if (event) {
          event.stopPropagation();
      }
      modal.classList.remove('show');
      document.body.style.overflow = 'auto'; // restore background scrolling
    }
  </script>
</body>
</html>
