<!DOCTYPE html>
<html>
<head>
  <title>User Management - TravelMate Admin</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/Users.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

<div class="page-container">  
  <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <!-- Page Header -->
      <div class="page-header">
        <div class="page-title">
          <div class="title-row">
            <i class="fas fa-users-cog"></i>
            <h1>User Management</h1>
          </div>
          <p class="subtitle">Manage all registered users in the TravelMate platform</p>
        </div>
        <div class="header-stats">
          <div class="stat-pill total">
            <span class="stat-number"><?php echo $stats['total'] ?? 0; ?></span>
            <span class="stat-label">Total Users</span>
          </div>
          <div class="stat-pill active">
            <span class="stat-number"><?php echo $stats['active'] ?? 0; ?></span>
            <span class="stat-label">Active</span>
          </div>
          <div class="stat-pill suspended">
            <span class="stat-number"><?php echo $stats['suspended'] ?? 0; ?></span>
            <span class="stat-label">Suspended</span>
          </div>
        </div>
      </div>

      <!-- User Type Cards -->
      <div class="user-type-cards">
        <div class="type-card traveller" onclick="filterByRole('traveller')">
          <div class="card-icon">
            <i class="fas fa-hiking"></i>
          </div>
          <div class="card-info">
            <span class="card-count"><?php echo $stats['travellers'] ?? 0; ?></span>
            <span class="card-label">Travellers</span>
          </div>
        </div>
        <div class="type-card provider" onclick="filterByRole('accommodation')">
          <div class="card-icon">
            <i class="fas fa-hotel"></i>
          </div>
          <div class="card-info">
            <span class="card-count"><?php echo $stats['accommodation'] ?? 0; ?></span>
            <span class="card-label">Accommodation</span>
          </div>
        </div>
        <div class="type-card transporter" onclick="filterByRole('transport')">
          <div class="card-icon">
            <i class="fas fa-car-side"></i>
          </div>
          <div class="card-info">
            <span class="card-count"><?php echo $stats['transport'] ?? 0; ?></span>
            <span class="card-label">Transport</span>
          </div>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" id="searchBox" placeholder="Search by name, email, or phone..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" maxlength="100">
        </div>
        
        <select id="userTypeFilter">
          <option value="" <?php echo empty($_GET['role']) ? 'selected' : ''; ?>>All User Types</option>
          <option value="traveller" <?php echo ($_GET['role'] ?? '') === 'traveller' ? 'selected' : ''; ?>>Travellers</option>
          <option value="accommodation" <?php echo ($_GET['role'] ?? '') === 'accommodation' ? 'selected' : ''; ?>>Accommodation Providers</option>
          <option value="transport" <?php echo ($_GET['role'] ?? '') === 'transport' ? 'selected' : ''; ?>>Transporters</option>
        </select>

        <select id="statusFilter">
          <option value="" <?php echo empty($_GET['status']) ? 'selected' : ''; ?>>All Status</option>
          <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
          <option value="suspended" <?php echo ($_GET['status'] ?? '') === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
        </select>

        <button id="applyFilter" onclick="applyFilters()">
          <i class="fas fa-filter"></i> Filter
        </button>
        <button class="btn-clear" onclick="clearFilters()">
          <i class="fas fa-times"></i> Clear
        </button>
      </div>

      <!-- Users Table -->
      <div class="users-table-container">
        <div class="table-header">
          <h3><i class="fas fa-list"></i> Registered Users</h3>
          <span class="result-count"><?php echo isset($totalUsers) ? $totalUsers : 0; ?> users found</span>
        </div>
        <table class="users-table">
          <thead>
            <tr>
              <th>User</th>
              <th>Contact</th>
              <th>User Type</th>
              <th>Join Date</th>
              <th>Status</th>
              <th>Listings</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($users) && is_array($users) && count($users) > 0): ?>
              <?php foreach ($users as $user): ?>
                <?php 
                  $fullName = htmlspecialchars($user->first_name . ' ' . $user->last_name);
                  $fullNameJs = htmlspecialchars($user->first_name . ' ' . $user->last_name, ENT_QUOTES, 'UTF-8');
                  $listingsCount = ($user->accommodation_count ?? 0) + ($user->vehicle_count ?? 0);
                  $viewPage = ($user->role === 'traveller') ? 'viewtraveller' : 'viewprovider';
                  $roleClass = strtolower($user->role ?? 'traveller');
                  $statusClass = strtolower($user->status ?? 'active');
                  $profileImage = ROOT . '/assets/images/default-avatar.png';
                  $roleIcon = match($user->role ?? 'traveller') {
                    'traveller' => 'fa-hiking',
                    'accommodation' => 'fa-hotel',
                    'transport' => 'fa-car-side',
                    default => 'fa-user'
                  };
                  $roleDisplay = match($user->role ?? 'traveller') {
                    'traveller' => 'Traveller',
                    'accommodation' => 'Accommodation',
                    'transport' => 'Transport',
                    default => ucfirst($user->role ?? 'User')
                  };
                ?>
                <tr data-user-id="<?php echo $user->id; ?>" data-role="<?php echo $roleClass; ?>" data-status="<?php echo $statusClass; ?>">
                  <td>
                    <div class="user-info">
                      <div class="profile-pic">
                        <img src="<?php echo $profileImage; ?>" alt="<?php echo htmlspecialchars($user->first_name); ?>" onerror="this.src='<?= ROOT ?>/assets/images/default-avatar.png'">
                        <span class="status-dot <?php echo $statusClass; ?>"></span>
                      </div>
                      <div class="user-details">
                        <strong><?php echo $fullName; ?></strong>
                        <small>ID: #<?php echo str_pad($user->id, 5, '0', STR_PAD_LEFT); ?></small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="contact-info">
                      <span class="email"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user->email); ?></span>
                      <span class="phone"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($user->phone ?? 'N/A'); ?></span>
                    </div>
                  </td>
                  <td>
                    <span class="user-type-badge <?php echo $roleClass; ?>">
                      <i class="fas <?php echo $roleIcon; ?>"></i>
                      <?php echo $roleDisplay; ?>
                    </span>
                  </td>
                  <td>
                    <div class="date-info">
                      <span class="date"><?php echo date('M d, Y', strtotime($user->created_at)); ?></span>
                      <small><?php echo date('h:i A', strtotime($user->created_at)); ?></small>
                    </div>
                  </td>
                  <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst($user->status ?? 'Active'); ?></span></td>
                  <td>
                    <?php if ($listingsCount > 0): ?>
                      <span class="listings-badge"><?php echo $listingsCount; ?> Listing<?php echo $listingsCount > 1 ? 's' : ''; ?></span>
                    <?php else: ?>
                      <span class="no-listings">No Listings</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-action btn-view" onclick="window.location.href='<?= ROOT ?>/<?php echo $viewPage; ?>?id=<?php echo (int)$user->id; ?>';" title="View Profile">
                        <i class="fas fa-eye"></i>
                      </button>
                      <?php if (($user->status ?? 'active') === 'active'): ?>
                        <button class="btn-action btn-suspend" data-user-id="<?php echo (int)$user->id; ?>" data-user-name="<?php echo $fullNameJs; ?>" onclick="suspendUser(this.dataset.userId, this.dataset.userName)" title="Suspend User">
                          <i class="fas fa-ban"></i>
                        </button>
                      <?php else: ?>
                        <button class="btn-action btn-activate" data-user-id="<?php echo (int)$user->id; ?>" data-user-name="<?php echo $fullNameJs; ?>" onclick="activateUser(this.dataset.userId, this.dataset.userName)" title="Activate User">
                          <i class="fas fa-check"></i>
                        </button>
                      <?php endif; ?>
                      <button class="btn-action btn-delete" data-user-id="<?php echo (int)$user->id; ?>" data-user-name="<?php echo $fullNameJs; ?>" onclick="deleteUser(this.dataset.userId, this.dataset.userName)" title="Delete User">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="empty-state">
                  <div class="empty-icon">
                    <i class="fas fa-users-slash"></i>
                  </div>
                  <h4>No Users Found</h4>
                  <p>No users match your current filter criteria.</p>
                  <button class="btn-clear-filters" onclick="clearFilters()">
                    <i class="fas fa-redo"></i> Reset Filters
                  </button>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if (isset($totalPages) && $totalPages > 1): ?>
      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?php echo $i; ?>&role=<?php echo urlencode($_GET['role'] ?? ''); ?>&status=<?php echo urlencode($_GET['status'] ?? ''); ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>" 
             class="page-link <?php echo ($page ?? 1) == $i ? 'active' : ''; ?>">
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content delete-modal">
      <div class="modal-header">
        <div class="modal-icon delete">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Delete User Account</h3>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to permanently delete the account of:</p>
        <div class="user-highlight">
          <i class="fas fa-user-circle"></i>
          <span id="userName"></span>
        </div>
        <div class="warning-box">
          <p class="warning"><i class="fas fa-exclamation-circle"></i> This action will permanently:</p>
          <ul>
            <li><i class="fas fa-user-times"></i> Delete the user account and profile</li>
            <li><i class="fas fa-home"></i> Remove all their listings and content</li>
            <li><i class="fas fa-calendar-times"></i> Cancel all pending bookings</li>
            <li><i class="fas fa-star"></i> Delete all reviews and ratings</li>
          </ul>
          <p class="danger-text"><i class="fas fa-ban"></i> <strong>This action cannot be undone!</strong></p>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button class="btn-danger" onclick="confirmDelete()">
          <i class="fas fa-trash-alt"></i> Delete Permanently
        </button>
      </div>
    </div>
  </div>

  <!-- Suspend Confirmation Modal -->
  <div id="suspendModal" class="modal">
    <div class="modal-content suspend-modal">
      <div class="modal-header">
        <div class="modal-icon suspend">
          <i class="fas fa-ban"></i>
        </div>
        <h3>Suspend User Account</h3>
        <span class="close" onclick="closeSuspendModal()">&times;</span>
      </div>
      <div class="modal-body">
        <p>You are about to suspend the account of:</p>
        <div class="user-highlight">
          <i class="fas fa-user-circle"></i>
          <span id="suspendUserName"></span>
        </div>
        <div class="info-box">
          <i class="fas fa-info-circle"></i>
          <p>Suspended users will not be able to login or access their account until reactivated.</p>
        </div>
        <div class="form-group">
          <label for="suspendReason"><i class="fas fa-comment-alt"></i> Reason for suspension: <span class="required">*</span></label>
          <textarea id="suspendReason" rows="3" placeholder="Please provide a reason for suspending this user..." required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeSuspendModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button class="btn-warning" onclick="confirmSuspend()">
          <i class="fas fa-ban"></i> Suspend User
        </button>
      </div>
    </div>
  </div>

  <!-- Success Toast Notification -->
  <div id="toastNotification" class="toast">
    <div class="toast-icon"><i class="fas fa-check-circle"></i></div>
    <div class="toast-message"></div>
  </div>

  <script>
    const CSRF_TOKEN = '<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8'); ?>';
    let userToDelete = null;
    let userToSuspend = null;

    // Filter by role from type cards
    function filterByRole(role) {
      document.getElementById('userTypeFilter').value = role;
      applyFilters();
    }

    // Apply filters and redirect
    function applyFilters() {
      const search = document.getElementById('searchBox').value.trim().substring(0, 100);
      const role = document.getElementById('userTypeFilter').value;
      const status = document.getElementById('statusFilter').value;

      let url = '<?= ROOT ?>/Users?';
      const params = [];

      if (search) params.push('search=' + encodeURIComponent(search));
      if (role) params.push('role=' + encodeURIComponent(role));
      if (status) params.push('status=' + encodeURIComponent(status));

      window.location.href = url + params.join('&');
    }

    // Clear all filters
    function clearFilters() {
      window.location.href = '<?= ROOT ?>/Users';
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('searchBox').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          applyFilters();
        }
      });

      document.getElementById('userTypeFilter').addEventListener('change', applyFilters);
      document.getElementById('statusFilter').addEventListener('change', applyFilters);
    });

    // Show toast notification
    function showToast(message, type = 'success') {
      const toast = document.getElementById('toastNotification');
      const icon = toast.querySelector('.toast-icon i');
      const msgEl = toast.querySelector('.toast-message');
      
      msgEl.textContent = message;
      toast.className = 'toast show ' + type;
      icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-times-circle';
      
      setTimeout(() => { toast.className = 'toast'; }, 3000);
    }

    // Safe fetch wrapper with proper error handling
    function safeFetch(url, options) {
      return fetch(url, options)
        .then(response => {
          if (!response.ok) {
            throw new Error('Server error (' + response.status + ')');
          }
          const contentType = response.headers.get('content-type');
          if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Invalid response from server');
          }
          return response.json();
        });
    }

    // Delete user functions
    function deleteUser(id, name) {
      userToDelete = id;
      document.getElementById('userName').textContent = name;
      document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('deleteModal').style.display = 'none';
      userToDelete = null;
      // Reset button state
      const btn = document.querySelector('#deleteModal .btn-danger');
      if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash-alt"></i> Delete Permanently'; }
    }

    function confirmDelete() {
      if (!userToDelete) return;
      
      const btn = document.querySelector('#deleteModal .btn-danger');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

      safeFetch('<?= ROOT ?>/api/admin/user/delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({user_id: userToDelete, csrf_token: CSRF_TOKEN})
      })
      .then(data => {
        closeModal();
        if (data.success) {
          showToast('User deleted successfully', 'success');
          setTimeout(() => location.reload(), 1500);
        } else {
          showToast('Error: ' + (data.error || 'Unknown error'), 'error');
        }
      })
      .catch(error => {
        closeModal();
        showToast('Failed to delete user: ' + error.message, 'error');
      });
    }

    // Suspend user functions
    function suspendUser(id, name) {
      userToSuspend = id;
      document.getElementById('suspendUserName').textContent = name;
      document.getElementById('suspendModal').style.display = 'flex';
    }

    function closeSuspendModal() {
      document.getElementById('suspendModal').style.display = 'none';
      userToSuspend = null;
      document.getElementById('suspendReason').value = '';
      // Reset button state
      const btn = document.querySelector('#suspendModal .btn-warning');
      if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-ban"></i> Suspend User'; }
    }

    function confirmSuspend() {
      const reason = document.getElementById('suspendReason').value.trim();
      
      if (!reason) {
        showToast('Please provide a reason for suspension', 'error');
        document.getElementById('suspendReason').focus();
        return;
      }

      if (!userToSuspend) return;
      
      const btn = document.querySelector('#suspendModal .btn-warning');
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

    // Activate user function
    function activateUser(id, name) {
      if (!confirm('Activate user "' + name + '"?')) return;
      
      // Find and disable the clicked button
      const btns = document.querySelectorAll('.btn-activate[data-user-id="' + id + '"]');
      btns.forEach(b => { b.disabled = true; b.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; });

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
          btns.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fas fa-check"></i>'; });
        }
      })
      .catch(error => {
        showToast('Failed to activate user: ' + error.message, 'error');
        btns.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fas fa-check"></i>'; });
      });
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      if (event.target === document.getElementById('deleteModal')) closeModal();
      if (event.target === document.getElementById('suspendModal')) closeSuspendModal();
    };

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') { closeModal(); closeSuspendModal(); }
    });
  </script>

</body>
</html>
