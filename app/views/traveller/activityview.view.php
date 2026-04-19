<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// convert the PHP objects/arrays to a format JS can easily use
$placesJson = isset($places) ? json_encode($places) : '[]';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - <?php echo htmlspecialchars($activity?->title ?? 'Activity'); ?></title>
  <link rel="stylesheet" href="assets/css/Traveller/beach.css">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
  <link rel="stylesheet" href="assets/css/Traveller/activityview.css">
</head>
<body>
  
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>
  
  <!-- hero Section -->
  <section class="hero-section">
    <div class="hero-background" style="background-image: url('<?php 
      $imagePath = is_object($activity) ? ($activity->image ?? 'assets/images/default-activity.png') : 'assets/images/default-activity.png';
      if (substr($imagePath, 0, 1) === '/') {
        $imagePath = substr($imagePath, 1);
      }
      echo htmlspecialchars($imagePath); 
    ?>');">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Experience <?php echo htmlspecialchars(is_object($activity) ? ($activity->title ?? 'Adventure') : 'Adventure'); ?></h1>
          <p><?php echo htmlspecialchars(is_object($activity) ? ($activity->description ?? 'Discover amazing activities and create unforgettable memories') : 'Discover amazing activities and create unforgettable memories'); ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- locations Section -->
  <section class="beaches-section">
    <div class="container">
      <div class="section-header">
        <h2>Locations for <?php echo htmlspecialchars(is_object($activity) ? ($activity->title ?? 'this Activity') : 'this Activity'); ?></h2>
        <p>Discover the best places to experience this activity</p>
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
                  <button class="explore-btn" onclick="openPlaceModal(<?php echo $place->id; ?>)">Learn More</button>
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
        <div class="no-places">
          <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="sa">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <h3 class="sa1">Locations Coming Soon</h3>
          <p class="sa2">We're adding amazing locations for this activity. Check back soon!</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- related Posts Section -->
  <?php if (isset($relatedPosts) && count($relatedPosts) > 0): ?>
  <section class="beaches-section related-posts-section">
    <div class="container">
      <div class="section-header">
        <h2>Traveler Stories</h2>
        <p>See what other travelers have shared about <?php echo htmlspecialchars(is_object($activity) ? ($activity->title ?? 'this activity') : 'this activity'); ?></p>
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
              <div class="post-meta-footer">
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

  <!-- tHE MODAL HTML -->
  <div id="placeModal" class="place-modal" onclick="closePlaceModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
      <div class="close-btn" onclick="closePlaceModal(event)">&times;</div>
      <img id="modalImage" class="modal-image" src="" alt="Place Image">
      <div class="modal-body">
        <h2 id="modalTitle" class="modal-title"></h2>
        
        <div class="place-meta-details">
            <div id="modalLocationWrap" class="hidden-wrap">
                <i class="fas fa-map-marker-alt icon-location"></i>
                <span id="modalLocation"></span>
            </div>
            <div id="modalBestTimeWrap" class="hidden-wrap">
                <i class="fas fa-sun icon-time"></i>
                <strong>Best time:</strong> <span id="modalBestTime"></span>
            </div>
        </div>

        <div id="modalDescription" class="modal-description"></div>
        <div class="modal-meta modal-meta-spaced">
          <span><i class="fas fa-calendar-alt"></i> Added on <span id="modalDate"></span></span>
        </div>

        <div class="modal-actions">
            <a href="favdestination" class="modal-action-btn btn-activity">
                <i class="fas fa-skating"></i> Find Destinations
            </a>
            <a href="accommodation" class="modal-action-btn btn-accommodation">
                <i class="fas fa-hotel"></i> Find Accommodations
            </a>
            <a href="transport" class="modal-action-btn btn-transport">
                <i class="fas fa-car"></i> Find Transport
            </a>
        </div>
      </div>
    </div>
  </div>

  <!-- load FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script>
    // make PHP data available to JS
    const placesData = <?php echo $placesJson; ?>;
    const modal = document.getElementById('placeModal');

    function openPlaceModal(placeId) {
      // find the specific place object
      const place = placesData.find(p => p.id == placeId);
      
      if (!place) {
          alert("Could not load place details.");
          return;
      }

      // populate Image - Handle leading slash
      let imgPath = place.image || 'assets/images/default-place.png';
      if (imgPath.startsWith('/')) {
          imgPath = imgPath.substring(1);
      }
      document.getElementById('modalImage').src = imgPath;

      // populate Title & Description
      document.getElementById('modalTitle').textContent = place.title || 'Unknown Place';
      document.getElementById('modalDescription').textContent = place.description || 'No description available for this place.';

      // extra Details
      const locWrap = document.getElementById('modalLocationWrap');
      if (place.location && place.location.trim() !== '') {
          document.getElementById('modalLocation').textContent = place.location;
          locWrap.classList.remove('hidden-wrap');
          locWrap.classList.add('show-wrap');
      } else {
          locWrap.classList.remove('show-wrap');
          locWrap.classList.add('hidden-wrap');
      }

      const btWrap = document.getElementById('modalBestTimeWrap');
      if (place.best_time && place.best_time.trim() !== '') {
          document.getElementById('modalBestTime').textContent = place.best_time;
          btWrap.classList.remove('hidden-wrap');
          btWrap.classList.add('show-wrap');
      } else {
          btWrap.classList.remove('show-wrap');
          btWrap.classList.add('hidden-wrap');
      }

      // format Date
      let dateText = '';
      if(place.created_at) {
          const d = new Date(place.created_at);
          dateText = d.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
      }
      document.getElementById('modalDate').textContent = dateText;

      // show modal
      modal.classList.add('show');
      document.body.classList.add('no-scroll'); // prevent background scrolling
    }

    function closePlaceModal(event) {
      if (event) {
          event.stopPropagation();
      }
      modal.classList.remove('show');
      document.body.classList.remove('no-scroll'); // restore background scrolling
    }
  </script>
</body>
</html>