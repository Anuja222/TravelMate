<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TravelMate - Explore Activities</title>
  <link rel="stylesheet" href="assets/css/Traveller/favactivity.css?v=3">
  <link rel="stylesheet" href="assets/css/Traveller/usermain.css">
</head>
<body>
  <!-- header/Navbar -->
  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <!-- hero Section -->
  <section class="hero-section">
    <div class="hero-background">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Adventure Awaits You</h1>
          <p>Discover thrilling activities and create unforgettable memories in Sri Lanka</p>
        </div>
      </div>
    </div>
  </section>

  <!-- activities Section -->
  <section class="activities-section">
    <div class="container">
      <div class="section-header">
        <h2>Explore Popular Activities</h2>
        <p>From adrenaline-pumping adventures to peaceful nature experiences</p>
      </div>

      <div class="activities-grid" id="activitiesContainer">
        <!-- dynamic loaded activities -->
        <p style="grid-column: 1/-1; text-align: center; padding: 40px 20px; color: #666;">Loading activities...</p>
      </div>
    </div>
  </section>

  <!-- booking Modal -->
  <!-- <div class="modal" id="activityModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Book Your Activity</h3>
        <button class="close-btn" onclick="closeActivityModal()">&times;</button>
      </div>
      <div class="modal-body">
        <form class="booking-form">
          <div class="form-group">
            <label>Activity Type</label>
            <select id="activityType">
              <option value="">Select activity type</option>
              <option value="water-rafting">Water Rafting</option>
              <option value="surfing">Surfing</option>
              <option value="bird-watching">Bird Watching</option>
              <option value="safari">Safari</option>
              <option value="photography">Photography</option>
              <option value="shopping">Shopping</option>
              <option value="hiking">Hiking</option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Date</label>
              <input type="date" id="activityDate">
            </div>
            <div class="form-group">
              <label>Time Slot</label>
              <select id="timeSlot">
                <option value="morning">Morning (6:00 AM - 12:00 PM)</option>
                <option value="afternoon">Afternoon (12:00 PM - 6:00 PM)</option>
                <option value="evening">Evening (6:00 PM - 10:00 PM)</option>
                <option value="full-day">Full Day</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Participants</label>
              <select id="participants">
                <option value="1">1 Person</option>
                <option value="2">2 People</option>
                <option value="3">3 People</option>
                <option value="4">4 People</option>
                <option value="5+">5+ People</option>
              </select>
            </div>
            <div class="form-group">
              <label>Experience Level</label>
              <select id="experienceLevel">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
                <option value="any">Any Level</option>
              </select>
            </div>
          </div>
          <button type="button" class="btn-primary full-width" onclick="submitActivityBooking()">Book Activity</button>
        </form>
      </div>
    </div>
  </div> -->

  <?php include __DIR__ . '/../Traveller/footer.view.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const container = document.getElementById('activitiesContainer');

      const baseApi = (function() {
        const origin = window.location.origin;
        const path = window.location.pathname;
        const publicIndex = path.indexOf('/public');
        if (publicIndex !== -1) return origin + path.substring(0, publicIndex + 7);
        const match = path.match(/(\/[^\/]+\/public)/);
        if (match) return origin + match[1];
        return origin + '/TravelMate/public';
      })();

      // fetch activities from API
      fetch(baseApi + '/api/activity/list', { credentials: 'same-origin' })
        .then(r => r.json())
        .then(resp => {
          if (!resp.success) {
            container.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px 20px; color: #e74c3c;">Failed to load activities</p>';
            return;
          }
          
          const activities = resp.data || [];
          if (activities.length === 0) {
            container.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px 20px; color: #666;">No activities available at the moment</p>';
            return;
          }

          // render all activities
          container.innerHTML = activities.map(activity => {
            const baseUrl = window.location.origin + '/TravelMate/public';
            const img = activity.image ? baseUrl + activity.image : 'assets/images/default-activity.png';
            const description = activity.description ? (activity.description.length > 150 ? activity.description.substring(0, 150) + '...' : activity.description) : 'No description available';
            
            return `
              <div class="card" data-category="${escapeHtml(activity.slug)}">
                <div class="card-image">
                  <img src="${img}" alt="${escapeHtml(activity.title)}">
                  <div class="card-overlay">
                    <a href="activityview?id=${activity.id}" class="explore-btn">Explore</a>
                  </div>
                </div>
                <div class="card-content">
                  <h3>${escapeHtml(activity.title)}</h3>
                  <p>${escapeHtml(description)}</p>
                </div>
              </div>
            `;
          }).join('');
        })
        .catch(err => {
          console.error('Error loading activities:', err);
          container.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px 20px; color: #e74c3c;">Error loading activities. Please try again later.</p>';
        });

      function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/[&<>"']/g, m => ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;'
        }[m]));
      }
    });
  </script>
</body>
</html>