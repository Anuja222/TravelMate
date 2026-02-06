<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Provider Profile - TravelMate Admin</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/ViewProfile.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

  <div class="page-container">
    <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <?php include __DIR__ . '/flash_messages.php'; ?>

      <?php
        // Determine role-specific display
        $userRole = $user->role ?? 'accommodation';
        $isTransport = ($userRole === 'transport');
        $roleLabel = $isTransport ? 'Transport Provider' : 'Accommodation Provider';
        $roleIcon = $isTransport ? 'fa-car-side' : 'fa-hotel';
        $roleClass = $isTransport ? 'transport' : 'accommodation';
        $listingLabel = $isTransport ? 'Vehicles' : 'Properties';
        $listingCount = $isTransport ? ($user->vehicle_count ?? 0) : ($user->accommodation_count ?? 0);
      ?>

      <!-- Page Header -->
      <div class="profile-page-header">
        <div class="page-title">
          <h1><i class="fas <?= $roleIcon; ?> <?= $roleClass; ?>-icon"></i> <?= $roleLabel; ?> Profile</h1>
          <p class="subtitle">View and manage provider information</p>
        </div>
        <div class="breadcrumb">
          <a href="<?= ROOT ?>/Users"><i class="fas fa-users"></i> Users</a>
          <i class="fas fa-chevron-right"></i>
          <span><?= isset($user) ? htmlspecialchars($user->first_name . ' ' . $user->last_name) : 'Profile'; ?></span>
        </div>
      </div>

      <?php if (isset($user)): ?>

        <!-- Profile Layout -->
        <div class="profile-layout">
          
          <!-- Left Column: Profile Sidebar Card -->
          <div class="profile-sidebar-card">
            <div class="profile-cover <?= $roleClass; ?>"></div>
            
            <div class="profile-avatar-wrapper">
              <div class="profile-avatar">
                <img src="<?= ROOT ?>/assets/images/default-avatar.png" 
                     alt="<?= htmlspecialchars($user->first_name); ?>">
              </div>
            </div>
            
            <div class="profile-identity">
              <h2><?= htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></h2>
              <span class="role-badge <?= $roleClass; ?>"><i class="fas <?= $roleIcon; ?>"></i> <?= $roleLabel; ?></span>
              <div class="status-indicator">
                <div class="status-dot-lg <?= ($user->status ?? 'active') !== 'active' ? 'suspended' : ''; ?>"></div>
                <span><?= ucfirst($user->status ?? 'Active'); ?></span>
              </div>
            </div>

            <div class="profile-contact">
              <div class="contact-row">
                <div class="contact-icon email"><i class="fas fa-envelope"></i></div>
                <div class="contact-text">
                  <span class="contact-label">Email</span>
                  <span class="contact-value"><?= htmlspecialchars($user->email); ?></span>
                </div>
              </div>
              <div class="contact-row">
                <div class="contact-icon phone"><i class="fas fa-phone"></i></div>
                <div class="contact-text">
                  <span class="contact-label">Phone</span>
                  <span class="contact-value"><?= htmlspecialchars($user->phone ?? 'Not provided'); ?></span>
                </div>
              </div>
              <div class="contact-row">
                <div class="contact-icon calendar"><i class="fas fa-calendar-alt"></i></div>
                <div class="contact-text">
                  <span class="contact-label">Member Since</span>
                  <span class="contact-value"><?= date('M d, Y', strtotime($user->created_at)); ?></span>
                </div>
              </div>
            </div>

            <div class="profile-actions">
              <button class="profile-action-btn back" onclick="window.location.href='<?= ROOT ?>/Users'">
                <i class="fas fa-arrow-left"></i> Back to Users
              </button>
              <?php if (($user->status ?? 'active') === 'active'): ?>
                <button class="profile-action-btn suspend" data-user-id="<?= (int)$user->id; ?>" data-user-name="<?= htmlspecialchars($user->first_name . ' ' . $user->last_name, ENT_QUOTES, 'UTF-8'); ?>" onclick="openSuspendModal(this.dataset.userId, this.dataset.userName)">
                  <i class="fas fa-ban"></i> Suspend Account
                </button>
              <?php else: ?>
                <button class="profile-action-btn activate" data-user-id="<?= (int)$user->id; ?>" data-user-name="<?= htmlspecialchars($user->first_name . ' ' . $user->last_name, ENT_QUOTES, 'UTF-8'); ?>" onclick="activateUser(this.dataset.userId, this.dataset.userName)">
                  <i class="fas fa-check-circle"></i> Activate Account
                </button>
              <?php endif; ?>
              <button class="profile-action-btn delete" data-user-id="<?= (int)$user->id; ?>" data-user-name="<?= htmlspecialchars($user->first_name . ' ' . $user->last_name, ENT_QUOTES, 'UTF-8'); ?>" onclick="openDeleteModal(this.dataset.userId, this.dataset.userName)">
                <i class="fas fa-trash-alt"></i> Delete Account
              </button>
            </div>
          </div>

          <!-- Right Column: Detail Cards -->
          <div class="profile-detail-cards">

            <!-- Account Information -->
            <div class="detail-card">
              <h3 class="detail-card-title">
                <i class="fas fa-info-circle green"></i> Account Information
              </h3>
              <div class="info-grid">
                <div class="info-cell accent-green">
                  <label>User ID</label>
                  <p>#<?= str_pad($user->id, 5, '0', STR_PAD_LEFT); ?></p>
                </div>
                <div class="info-cell accent-blue">
                  <label>Status</label>
                  <p>
                    <span class="status-pill <?= ($user->status ?? 'active') === 'active' ? 'active' : 'suspended'; ?>">
                      <i class="fas fa-<?= ($user->status ?? 'active') === 'active' ? 'check-circle' : 'pause-circle'; ?>"></i>
                      <?= ucfirst($user->status ?? 'Active'); ?>
                    </span>
                  </p>
                </div>
                <div class="info-cell">
                  <label>Account Created</label>
                  <p><?= date('M d, Y h:i A', strtotime($user->created_at)); ?></p>
                </div>
                <div class="info-cell">
                  <label>Last Login</label>
                  <p><?= (isset($user->last_login) && !empty($user->last_login)) ? date('M d, Y h:i A', strtotime($user->last_login)) : 'Not available'; ?></p>
                </div>
                <div class="info-cell accent-orange">
                  <label>User Role</label>
                  <p><?= $roleLabel; ?></p>
                </div>
                <div class="info-cell">
                  <label>Email Verified</label>
                  <p><?= (isset($user->email_verified_at) && !empty($user->email_verified_at)) ? 'Yes' : 'Pending'; ?></p>
                </div>
              </div>
            </div>

            <!-- Activity Statistics -->
            <div class="detail-card">
              <h3 class="detail-card-title">
                <i class="fas fa-chart-bar blue"></i> Activity Statistics
              </h3>
              <div class="stats-grid">
                <div class="stat-block <?= $isTransport ? 'orange' : 'red'; ?>">
                  <span class="stat-number"><?= $listingCount; ?></span>
                  <span class="stat-label"><?= $listingLabel; ?></span>
                </div>
                <div class="stat-block blue">
                  <span class="stat-number">0</span>
                  <span class="stat-label">Total Bookings</span>
                </div>
                <div class="stat-block green">
                  <span class="stat-number"><?= $user->blog_count ?? 0; ?></span>
                  <span class="stat-label">Blog Posts</span>
                </div>
                <div class="stat-block orange">
                  <span class="stat-number">0</span>
                  <span class="stat-label">Reviews</span>
                </div>
              </div>
            </div>

            <!-- Location Information -->
            <div class="detail-card">
              <h3 class="detail-card-title">
                <i class="fas fa-map-marker-alt orange"></i> Location Information
              </h3>
              <?php if ((isset($user->address) && !empty($user->address)) || (isset($user->city) && !empty($user->city))): ?>
                <div class="info-grid">
                  <?php if (isset($user->address) && !empty($user->address)): ?>
                  <div class="info-cell">
                    <label>Address</label>
                    <p><?= htmlspecialchars($user->address); ?></p>
                  </div>
                  <?php endif; ?>
                  <?php if (isset($user->city) && !empty($user->city)): ?>
                  <div class="info-cell">
                    <label>City</label>
                    <p><?= htmlspecialchars($user->city); ?></p>
                  </div>
                  <?php endif; ?>
                </div>
              <?php else: ?>
                <div class="location-empty">
                  <i class="fas fa-map-marked-alt"></i>
                  <p>Location details not available</p>
                </div>
              <?php endif; ?>
            </div>

            <!-- Suspension History -->
            <?php if (isset($suspensionHistory) && !empty($suspensionHistory)): ?>
            <div class="detail-card">
              <h3 class="detail-card-title">
                <i class="fas fa-history red"></i> Suspension History
              </h3>
              <div class="suspension-timeline">
                <?php foreach ($suspensionHistory as $record): ?>
                <div class="suspension-entry">
                  <div class="suspension-entry-header">
                    <div class="suspension-label">
                      <i class="fas fa-ban"></i> Suspended
                    </div>
                    <?php if (!empty($record->reactivated_at)): ?>
                      <span class="reactivated-badge yes"><i class="fas fa-check"></i> Reactivated</span>
                    <?php else: ?>
                      <span class="reactivated-badge no"><i class="fas fa-clock"></i> Still Active</span>
                    <?php endif; ?>
                  </div>
                  <p class="suspension-reason"><?= htmlspecialchars($record->reason ?? 'No reason provided'); ?></p>
                  <div class="suspension-date">
                    <i class="fas fa-calendar-alt"></i>
                    <?= date('M d, Y h:i A', strtotime($record->suspended_at)); ?>
                    <?php if (!empty($record->reactivated_at)): ?>
                      &nbsp;→&nbsp; <?= date('M d, Y h:i A', strtotime($record->reactivated_at)); ?>
                    <?php endif; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

          </div>
        </div>

      <?php else: ?>
        <div class="profile-empty-state">
          <i class="fas fa-user-slash"></i>
          <h3>User Not Found</h3>
          <p>The requested user could not be found in the system.</p>
          <button class="profile-action-btn back" onclick="window.location.href='<?= ROOT ?>/Users'" style="margin-top: 16px;">
            <i class="fas fa-arrow-left"></i> Back to Users
          </button>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" class="profile-modal-overlay">
    <div class="profile-modal">
      <div class="profile-modal-header">
        <div class="profile-modal-icon delete">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Delete User Account</h3>
      </div>
      <div class="profile-modal-body">
        <p>Are you sure you want to permanently delete the account of <strong id="deleteUserName"></strong>?</p>
        <div class="profile-modal-warning">
          <i class="fas fa-exclamation-circle"></i>
          <span>This action cannot be undone. All user data, listings and bookings will be permanently removed.</span>
        </div>
      </div>
      <div class="profile-modal-footer">
        <button class="modal-btn cancel" onclick="closeDeleteModal()">Cancel</button>
        <button class="modal-btn danger" id="deleteConfirmBtn" onclick="confirmDelete()">
          <i class="fas fa-trash-alt"></i> Delete
        </button>
      </div>
    </div>
  </div>

  <!-- Suspend Confirmation Modal -->
  <div id="suspendModal" class="profile-modal-overlay">
    <div class="profile-modal">
      <div class="profile-modal-header">
        <div class="profile-modal-icon suspend">
          <i class="fas fa-ban"></i>
        </div>
        <h3>Suspend User Account</h3>
      </div>
      <div class="profile-modal-body">
        <p>Suspend the account of <strong id="suspendUserName"></strong></p>
        <label class="suspend-textarea-label"><i class="fas fa-comment-alt"></i> Reason for suspension <span style="color: #dc2626;">*</span></label>
        <textarea id="suspendReason" class="suspend-textarea" placeholder="Please provide a reason for suspending this user..." required></textarea>
      </div>
      <div class="profile-modal-footer">
        <button class="modal-btn cancel" onclick="closeSuspendModal()">Cancel</button>
        <button class="modal-btn warning" id="suspendConfirmBtn" onclick="confirmSuspend()">
          <i class="fas fa-ban"></i> Suspend
        </button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div id="profileToast" class="profile-toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage"></span>
  </div>

  <script>
    const CSRF_TOKEN = '<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8'); ?>';
    let userToDelete = null;
    let userToSuspend = null;

    function showToast(message, type) {
      const toast = document.getElementById('profileToast');
      const icon = toast.querySelector('i');
      const msg = document.getElementById('toastMessage');
      msg.textContent = message;
      toast.className = 'profile-toast show ' + type;
      icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-times-circle';
      setTimeout(() => { toast.className = 'profile-toast'; }, 3000);
    }

    function safeFetch(url, options) {
      return fetch(url, options)
        .then(response => {
          if (!response.ok) throw new Error('Server error (' + response.status + ')');
          const ct = response.headers.get('content-type');
          if (!ct || !ct.includes('application/json')) throw new Error('Invalid response from server');
          return response.json();
        });
    }

    function openDeleteModal(id, name) {
      userToDelete = id;
      document.getElementById('deleteUserName').textContent = name;
      document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
      document.getElementById('deleteModal').style.display = 'none';
      userToDelete = null;
      const btn = document.getElementById('deleteConfirmBtn');
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-trash-alt"></i> Delete';
    }

    function confirmDelete() {
      if (!userToDelete) return;
      const btn = document.getElementById('deleteConfirmBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

      safeFetch('<?= ROOT ?>/api/admin/user/delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({user_id: userToDelete, csrf_token: CSRF_TOKEN})
      })
      .then(data => {
        closeDeleteModal();
        if (data.success) {
          showToast('User deleted successfully', 'success');
          setTimeout(() => window.location.href = '<?= ROOT ?>/Users', 1500);
        } else {
          showToast('Error: ' + (data.error || 'Unknown error'), 'error');
        }
      })
      .catch(error => {
        closeDeleteModal();
        showToast('Failed to delete user: ' + error.message, 'error');
      });
    }

    function openSuspendModal(id, name) {
      userToSuspend = id;
      document.getElementById('suspendUserName').textContent = name;
      document.getElementById('suspendModal').style.display = 'flex';
    }

    function closeSuspendModal() {
      document.getElementById('suspendModal').style.display = 'none';
      userToSuspend = null;
      document.getElementById('suspendReason').value = '';
      document.getElementById('suspendReason').style.borderColor = '';
      const btn = document.getElementById('suspendConfirmBtn');
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-ban"></i> Suspend';
    }

    function confirmSuspend() {
      const reason = document.getElementById('suspendReason').value.trim();
      if (!reason) {
        document.getElementById('suspendReason').style.borderColor = '#ef4444';
        document.getElementById('suspendReason').focus();
        showToast('Please provide a reason for suspension', 'error');
        return;
      }
      if (!userToSuspend) return;

      const btn = document.getElementById('suspendConfirmBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suspending...';

      safeFetch('<?= ROOT ?>/api/admin/user/suspend', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({user_id: userToSuspend, reason: reason, csrf_token: CSRF_TOKEN})
      })
      .then(data => {
        closeSuspendModal();
        if (data.success) {
          showToast('User suspended successfully', 'success');
          setTimeout(() => location.reload(), 1500);
        } else {
          showToast('Error: ' + (data.error || 'Unknown error'), 'error');
        }
      })
      .catch(error => {
        closeSuspendModal();
        showToast('Failed to suspend user: ' + error.message, 'error');
      });
    }

    function activateUser(id, name) {
      if (!confirm('Activate user "' + name + '"?')) return;

      safeFetch('<?= ROOT ?>/api/admin/user/activate', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({user_id: id, csrf_token: CSRF_TOKEN})
      })
      .then(data => {
        if (data.success) {
          showToast('User activated successfully', 'success');
          setTimeout(() => location.reload(), 1500);
        } else {
          showToast('Error: ' + (data.error || 'Unknown error'), 'error');
        }
      })
      .catch(error => showToast('Failed to activate user: ' + error.message, 'error'));
    }

    window.addEventListener('click', function(e) {
      if (e.target.id === 'deleteModal') closeDeleteModal();
      if (e.target.id === 'suspendModal') closeSuspendModal();
    });
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') { closeDeleteModal(); closeSuspendModal(); }
    });
  </script>

</body>
</html>
