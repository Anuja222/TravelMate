<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements - Admin Panel</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/announcement.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/admin_header.view.php'; ?>

  <div class="page-container">

    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <?php include __DIR__ . '/flash_messages.php'; ?>
      <!-- Page Header -->
      <div class="page-header">
        <h1>Announcements</h1>
        <button class="btn-new-announcement" onclick="openNewAnnouncementModal()">
          <span>📢</span> New Announcement
        </button>
      </div>

      <!-- Statistics -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
          <div class="stat-label">Total Announcements</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $stats['active'] ?? 0; ?></div>
          <div class="stat-label">Active Announcements</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo number_format($stats['total_views'] ?? 0); ?></div>
          <div class="stat-label">Total Views</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">-</div>
          <div class="stat-label">Read Rate</div>
        </div>
      </div>

      <!-- Announcements List -->
      <div class="announcements-grid">
        <?php if (isset($announcements) && is_array($announcements) && count($announcements) > 0): ?>
          <?php foreach ($announcements as $announcement): ?>
            <?php 
              $audienceClass = 'audience-' . ($announcement->audience ?? 'all');
              $audienceLabel = ucfirst($announcement->audience ?? 'All');
            ?>
            <div class="announcement-card" data-id="<?php echo $announcement->id; ?>">
              <div class="announcement-header">
                <h3 class="announcement-title"><?php echo htmlspecialchars($announcement->title); ?></h3>
                <div class="announcement-meta">
                  <span>📅 <?php echo date('M d, Y', strtotime($announcement->created_at)); ?></span>
                  <span>👁️ <?php echo number_format($announcement->views ?? 0); ?> views</span>
                </div>
              </div>
              <div class="announcement-audience">
                <span class="audience-badge <?php echo $audienceClass; ?>"><?php echo $audienceLabel; ?> Users</span>
                <span class="status-badge <?php echo $announcement->status ?? 'active'; ?>"><?php echo ucfirst($announcement->status ?? 'Active'); ?></span>
              </div>
              <div class="announcement-content">
                <p><?php echo htmlspecialchars(substr($announcement->content, 0, 200)); ?>...</p>
              </div>
              <div class="announcement-actions">
                <button class="btn-action btn-edit" onclick="editAnnouncement(<?php echo $announcement->id; ?>)">Edit</button>
                <button class="btn-action btn-delete" onclick="deleteAnnouncement(<?php echo $announcement->id; ?>)">Delete</button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Empty State -->
          <div class="empty-state">
            <div class="empty-state-icon">📢</div>
            <h3>No Announcements Yet</h3>
            <p>Create your first announcement to keep users informed about important updates.</p>
            <button class="btn-new-announcement" onclick="openNewAnnouncementModal()" style="margin-top: 20px;">
              <span>📢</span> Create Announcement
            </button>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <!-- New Announcement Modal -->
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

  <!-- Delete Confirmation Modal -->
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

    // Modal Functions
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
      
      // In a real application, you would fetch the announcement data
      // For demo purposes, we'll set some sample values
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

    // Announcement Functions
    function sendAnnouncement() {
      const title = document.getElementById('announcementTitle').value;
      const content = document.getElementById('announcementContent').value;
      const priority = document.getElementById('announcementPriority').value;
      const sendNotification = document.getElementById('sendNotification').checked;
      
      // Get selected audiences
      let audience = 'all';
      if (document.getElementById('audienceTravelers').checked) audience = 'travelers';
      if (document.getElementById('audienceProviders').checked) audience = 'providers';
      
      if (!title || !content) {
        alert('Please fill in all required fields.');
        return;
      }
      
      const url = isEditing ? '/api/admin/announcement/update' : '/api/admin/announcement/create';
      const data = {
        title: title,
        content: content,
        audience: audience
      };
      
      if (isEditing) {
        data.id = currentAnnouncementId;
      }
      
      fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          alert(isEditing ? 'Announcement updated successfully!' : 'Announcement created successfully!');
          closeModal();
          location.reload();
        } else {
          alert('Error: ' + result.error);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to save announcement');
      });
    }

    function deleteAnnouncement(id) {
      currentAnnouncementId = id;
      document.getElementById('deleteModal').style.display = 'block';
    }

    function confirmDelete() {
      if (currentAnnouncementId) {
        fetch('/api/admin/announcement/delete', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({id: currentAnnouncementId})
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            alert('Announcement deleted successfully!');
            closeDeleteModal();
            location.reload();
          } else {
            alert('Error: ' + result.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to delete announcement');
        });
      }
    }

    // Close modals when clicking outside
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

    // Handle audience selection (if "All" is selected, deselect others)
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
