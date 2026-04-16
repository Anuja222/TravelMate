<!DOCTYPE html>
<html>
<head>
  <title>Announcements - Admin Panel</title>
  <link rel="stylesheet" href="assets/css/Admin/common.css">
  <link rel="stylesheet" href="assets/css/Admin/announcement.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

  <div class="page-container">

    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <!-- page Header -->
      <div class="page-title">
        <div class="page-title-content">
          <div class="page-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"></path>
              <path d="M12 6v6l4 2"></path>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>Announcements</h1>
            <p class="page-subtitle">Broadcast important updates to all users</p>
          </div>
        </div>
        <button class="btn-new-announcement" onclick="openNewAnnouncementModal()">
          <span>📢</span> New Announcement
        </button>
      </div>

      <!-- statistics -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-number">24</div>
          <div class="stat-label">Total Announcements</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">18</div>
          <div class="stat-label">Active Announcements</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">2,847</div>
          <div class="stat-label">Total Recipients</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">92%</div>
          <div class="stat-label">Read Rate</div>
        </div>
      </div>

      <!-- announcements List -->
      <div class="announcements-grid">
        <!-- announcement 1 -->
        <div class="announcement-card">
          <div class="announcement-header">
            <h3 class="announcement-title">System Maintenance - March 20</h3>
            <div class="announcement-meta">
              <span>📅 Mar 15, 2024</span>
              <span>👁️ 2.1k views</span>
            </div>
          </div>
          <div class="announcement-audience">
            <span class="audience-badge audience-all">All Users</span>
          </div>
          <div class="announcement-content">
            <p>We will be performing scheduled system maintenance on March 20, 2024 from 2:00 AM to 6:00 AM (UTC). During this time, the platform will be temporarily unavailable. We apologize for any inconvenience.</p>
          </div>
          <div class="announcement-actions">
            <button class="btn-action btn-edit" onclick="editAnnouncement(1)">Edit</button>
            <button class="btn-action btn-delete" onclick="deleteAnnouncement(1)">Delete</button>
          </div>
        </div>

        <!-- announcement 2 -->
        <div class="announcement-card">
          <div class="announcement-header">
            <h3 class="announcement-title">New Feature: Advanced Booking Analytics</h3>
            <div class="announcement-meta">
              <span>📅 Mar 10, 2024</span>
              <span>👁️ 1.8k views</span>
            </div>
          </div>
          <div class="announcement-audience">
            <span class="audience-badge audience-providers">Service Providers</span>
          </div>
          <div class="announcement-content">
            <p>We're excited to introduce our new Advanced Booking Analytics dashboard! Service providers can now access detailed insights about booking patterns, customer preferences, and revenue trends.</p>
          </div>
          <div class="announcement-actions">
            <button class="btn-action btn-edit" onclick="editAnnouncement(2)">Edit</button>
            <button class="btn-action btn-delete" onclick="deleteAnnouncement(2)">Delete</button>
          </div>
        </div>

        <!-- announcement 3 -->
        <div class="announcement-card">
          <div class="announcement-header">
            <h3 class="announcement-title">Summer Travel Deals & Promotions</h3>
            <div class="announcement-meta">
              <span>📅 Mar 5, 2024</span>
              <span>👁️ 2.5k views</span>
            </div>
          </div>
          <div class="announcement-audience">
            <span class="audience-badge audience-travelers">Travelers</span>
          </div>
          <div class="announcement-content">
            <p>Get ready for summer! We've partnered with top hotels and service providers to bring you exclusive summer deals. Book before April 15th to get up to 30% off on selected destinations.</p>
          </div>
          <div class="announcement-actions">
            <button class="btn-action btn-edit" onclick="editAnnouncement(3)">Edit</button>
            <button class="btn-action btn-delete" onclick="deleteAnnouncement(3)">Delete</button>
          </div>
        </div>
      </div>

      <!-- empty State (hidden by default) -->
      <div class="empty-state" style="display: none;">
        <div class="empty-state-icon">📢</div>
        <h3>No Announcements Yet</h3>
        <p>Create your first announcement to keep users informed about important updates.</p>
        <button class="btn-new-announcement" onclick="openNewAnnouncementModal()" style="margin-top: 20px;">
          <span>📢</span> Create Announcement
        </button>
      </div>
    </div>
  </div>

  <!-- new Announcement Modal -->
  <div id="announcementModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="modalTitle">New Announcement</h3>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      <div class="modal-body">
        <form id="announcementForm">
          <div class="form-group">
            <label for="announcementTitle">Title</label>
            <input type="text" id="announcementTitle" placeholder="Enter announcement title" required>
          </div>
          
          <div class="form-group">
            <label for="announcementContent">Content</label>
            <textarea id="announcementContent" placeholder="Enter announcement content..." required></textarea>
          </div>
          
          <div class="form-group">
            <label>Target Audience</label>
            <div class="checkbox-group">
              <div class="checkbox-item">
                <input type="checkbox" id="audienceAll" name="audience" value="all" checked>
                <label for="audienceAll">All Users</label>
              </div>
              <div class="checkbox-item">
                <input type="checkbox" id="audienceTravelers" name="audience" value="travelers">
                <label for="audienceTravelers">Travelers</label>
              </div>
              <div class="checkbox-item">
                <input type="checkbox" id="audienceProviders" name="audience" value="providers">
                <label for="audienceProviders">Accommodation Providers</label>
              </div>
               <div class="checkbox-item">
                <input type="checkbox" id="audienceProviders" name="audience" value="providers">
                <label for="audienceProviders">Transport Providers</label>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="announcementPriority">Priority</label>
            <select id="announcementPriority">
              <option value="low">Low</option>
              <option value="normal" selected>Normal</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
          
          <div class="form-group">
            <div class="checkbox-item">
              <input type="checkbox" id="sendNotification" checked>
              <label for="sendNotification">Send push notification to users</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-cancel" onclick="closeModal()">Cancel</button>
        <button class="btn-send" onclick="sendAnnouncement()">Send Announcement</button>
      </div>
    </div>
  </div>

  <!-- delete Confirmation Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Confirm Deletion</h3>
        <span class="close" onclick="closeDeleteModal()">&times;</span>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this announcement?</p>
        <p style="color: #e74c3c; font-weight: 500;">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
        <button class="btn-send" style="background: #e74c3c;" onclick="confirmDelete()">Delete Announcement</button>
      </div>
    </div>
  </div>

  <script>
    let currentAnnouncementId = null;
    let isEditing = false;

    // modal Functions
    function openNewAnnouncementModal() {
      isEditing = false;
      document.getElementById('modalTitle').textContent = 'New Announcement';
      document.getElementById('announcementForm').reset();
      document.getElementById('announcementModal').style.display = 'block';
    }

    function editAnnouncement(id) {
      isEditing = true;
      currentAnnouncementId = id;
      document.getElementById('modalTitle').textContent = 'Edit Announcement';
      
      // in a real application, you would fetch the announcement data
      // for demo purposes, we'll set some sample values
      document.getElementById('announcementTitle').value = 'System Maintenance - March 20';
      document.getElementById('announcementContent').value = 'We will be performing scheduled system maintenance on March 20, 2024 from 2:00 AM to 6:00 AM (UTC). During this time, the platform will be temporarily unavailable. We apologize for any inconvenience.';
      
      document.getElementById('announcementModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('announcementModal').style.display = 'none';
      currentAnnouncementId = null;
      isEditing = false;
    }

    function closeDeleteModal() {
      document.getElementById('deleteModal').style.display = 'none';
      currentAnnouncementId = null;
    }

    // announcement Functions
    function sendAnnouncement() {
      const title = document.getElementById('announcementTitle').value;
      const content = document.getElementById('announcementContent').value;
      const priority = document.getElementById('announcementPriority').value;
      const sendNotification = document.getElementById('sendNotification').checked;
      
      // get selected audiences
      const audiences = [];
      if (document.getElementById('audienceAll').checked) audiences.push('all');
      if (document.getElementById('audienceTravelers').checked) audiences.push('travelers');
      if (document.getElementById('audienceProviders').checked) audiences.push('providers');
      
      if (!title || !content) {
        alert('Please fill in all required fields.');
        return;
      }
      
      if (audiences.length === 0) {
        alert('Please select at least one target audience.');
        return;
      }
      
      // in a real application, you would send this data to the server
      console.log('Sending announcement:', {
        title,
        content,
        audiences,
        priority,
        sendNotification,
        isEditing,
        id: currentAnnouncementId
      });
      
      // show success message
      alert(isEditing ? 'Announcement updated successfully!' : 'Announcement sent successfully!');
      
      closeModal();
      
      // in a real application, you would refresh the announcements list
      // for demo, we'll just log to console
      console.log('Announcements list should be refreshed');
    }

    function deleteAnnouncement(id) {
      currentAnnouncementId = id;
      document.getElementById('deleteModal').style.display = 'block';
    }

    function confirmDelete() {
      if (currentAnnouncementId) {
        // in a real application, you would send delete request to server
        console.log('Deleting announcement:', currentAnnouncementId);
        alert('Announcement deleted successfully!');
        closeDeleteModal();
        
        // in a real application, you would remove the announcement from the list
        // for demo, we'll just log to console
        console.log('Announcement should be removed from the list');
      }
    }

    // close modals when clicking outside
    window.onclick = function(event) {
      const announcementModal = document.getElementById('announcementModal');
      const deleteModal = document.getElementById('deleteModal');
      
      if (event.target === announcementModal) {
        closeModal();
      }
      if (event.target === deleteModal) {
        closeDeleteModal();
      }
    }

    // handle audience selection (if "All" is selected, deselect others)
    document.getElementById('audienceAll').addEventListener('change', function() {
      if (this.checked) {
        document.getElementById('audienceTravelers').checked = false;
        document.getElementById('audienceProviders').checked = false;
      }
    });

    document.getElementById('audienceTravelers').addEventListener('change', function() {
      if (this.checked) {
        document.getElementById('audienceAll').checked = false;
      }
    });

    document.getElementById('audienceProviders').addEventListener('change', function() {
      if (this.checked) {
        document.getElementById('audienceAll').checked = false;
      }
    });
  </script>

</body>
</html>