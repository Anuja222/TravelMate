<!DOCTYPE html>
<html>
<head>
  <title>Blog Title - TravelVista</title>
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="assets/css/Admin/ViewBlog.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-containerr">

    <div class="content">
      <!-- vlog Container -->
      <div class="vlog-container">
        <!-- vlog Header -->
        <div class="vlog-header">
          <div class="vlog-meta">
            <div class="author-info">
              <div class="author-avatar">
                <img src="assets/images/profile.jpg" alt="Travel Seeker">
              </div>
              <div class="author-details">
                <h3>Travel Seeker</h3>
                <p>Adventure Blogger & Photographer</p>
              </div>
            </div>
            <div class="vlog-stats">
              <div class="stat">📅 March 15, 2024</div>
              <div class="stat">⏱️ 8 min read</div>
              <div class="stat">👁️ 2.4k views</div>
            </div>
          </div>
          <h1 class="vlog-title">Exploring the Hidden Beaches of Southern Sri Lanka</h1>
          <p class="vlog-subtitle">A journey through the untouched coastal paradise beyond the popular tourist spots</p>
        </div>

        <!-- vlog Content -->
        <div class="vlog-content">
          <div class="vlog-hero">
            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Sri Lanka Beach">
          </div>

          <div class="vlog-body">
            <p>When most travelers think of Sri Lanka's southern coast, they imagine the popular beaches of Mirissa, Unawatuna, and Hikkaduwa. But venture just a little further, and you'll discover a world of pristine, untouched beaches that feel like they're from another time.</p>

            <h2>The Journey Begins</h2>
            <p>Our adventure started early in the morning from Galle Fort. The air was fresh, carrying the scent of salt and tropical flowers. We'd hired a local tuk-tuk driver named Saman who promised to show us beaches that even many locals haven't visited.</p>

            <div class="image-grid">
              <img src="assets/images/tuktuk.png" alt="Tuk-tuk ride">
              <img src="assets/images/coastal.png" alt="Coastal road">
            </div>

            <h2>Secret Beach #1: Dalawella's Hidden Cove</h2>
            <p>Just beyond the famous Dalawella Beach, there's a small path that leads through dense coconut groves. After a 10-minute walk, we emerged at a secluded cove that took our breath away. The water was crystal clear, with shades of turquoise I didn't think existed in real life.</p>

            <img src="https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Hidden cove">

            <h2>Meeting the Local Fishermen</h2>
            <p>At each beach we visited, we encountered local fishermen who were more than happy to share stories about their lives and the sea. At one particularly remote beach, an elderly fisherman named Rohan showed us how they still use traditional fishing methods passed down through generations.</p>

            <p>"The tourists go to the big beaches," he told us in broken English, "but here, the sea is still pure. The fish are plenty, and the water remembers the old ways."</p>

            <h2>Jungle Beach: Worth the Hike</h2>
            <p>No visit to the southern coast is complete without hiking to Jungle Beach near Unawatuna. The 30-minute trek through lush forest is challenging but absolutely worth it. When you finally break through the trees and see the crescent-shaped bay below, it feels like discovering a secret world.</p>

            <div class="image-grid">
              <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Jungle hike">
              <img src="https://images.unsplash.com/photo-1505118380757-91f5f5632de0?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Jungle Beach">
            </div>

            <h2>Tips for Your Own Adventure</h2>
            <p>If you're planning to explore these hidden beaches yourself, here are some tips from our experience:</p>
            <ul>
              <li>Start early to avoid the midday heat</li>
              <li>Hire a local guide - they know the best spots</li>
              <li>Bring plenty of water and snacks</li>
              <li>Wear sturdy shoes for hiking</li>
              <li>Respect the environment - take only photos, leave only footprints</li>
            </ul>

            <p>The southern coast of Sri Lanka has so much more to offer beyond the popular tourist spots. With a sense of adventure and respect for the local culture and environment, you can discover beaches that will stay in your memory long after you've returned home.</p>
          </div>
        </div>

        <!-- vlog Actions -->
        <div class="vlog-actions">
          <button class="btn-action btn-approve" onclick="approveVlog()">
            <span>✓</span> Approve Vlog
          </button>
          <button class="btn-action btn-reject" onclick="rejectVlog()">
            <span>✗</span> Reject Vlog
          </button>
          <button class="btn-action btn-back" onclick="window.location.href='content.php';">
            <span>←</span> Back to List
          </button>
        </div>
      </div>

      <!-- related Vlogs -->
      <div class="related-vlogs">
        <h3 class="section-title">Related Vlogs</h3>
        <div class="related-grid">
          <div class="related-card">
            <div class="related-image">
              <img src="assets/images/cultural.png" alt="Temple visit">
            </div>
            <div class="related-info">
              <h4>Ancient Temples of the Cultural Triangle</h4>
              <div class="related-meta">
                <span>⏱️ 6 min read</span>
                <span>👁️ 1.8k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="assets/images/tea.png" alt="Tea plantation">
            </div>
            <div class="related-info">
              <h4>Hiking Through Sri Lanka's Tea Country</h4>
              <div class="related-meta">
                <span>⏱️ 10 min read</span>
                <span>👁️ 3.2k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="assets/images/wild.png" alt="Wildlife">
            </div>
            <div class="related-info">
              <h4>Wildlife Encounters in Yala National Park</h4>
              <div class="related-meta">
                <span>⏱️ 7 min read</span>
                <span>👁️ 2.1k views</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function approveVlog() {
      if (confirm('Approve this vlog for publication?')) {
        alert('Vlog approved successfully!');
        // implement actual approval functionality
      }
    }

    function rejectVlog() {
      const reason = prompt('Please enter reason for rejection:');
      if (reason) {
        alert(`Vlog rejected. Reason: ${reason}`);
        // implement actual rejection functionality
      }
    }

    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>