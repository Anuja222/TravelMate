<!DOCTYPE html>
<html>
<head>
  <title>User Management</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/Users.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

<div class="page-container">  
  <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <div class="page-title">
        <h1>Users</h1>
        <div class="stats-summary">
          <span class="stat-badge">Total: <?php echo $stats['total'] ?? 0; ?></span>
          <span class="stat-badge active">Active: <?php echo $stats['active'] ?? 0; ?></span>
          <span class="stat-badge suspended">Suspended: <?php echo $stats['suspended'] ?? 0; ?></span>
        </div>
      </div>

      <div class="filter-bar">
        <input type="text" id="searchBox" placeholder="🔍 Search users by name, email, phone..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        
        <select id="userTypeFilter">
          <option value="all" <?php echo ($_GET['role'] ?? '') === '' ? 'selected' : ''; ?>>All User Types</option>
          <option value="traveller" <?php echo ($_GET['role'] ?? '') === 'traveller' ? 'selected' : ''; ?>>Travelers</option>
          <option value="provider" <?php echo ($_GET['role'] ?? '') === 'provider' ? 'selected' : ''; ?>>Service Providers</option>
          <option value="transporter" <?php echo ($_GET['role'] ?? '') === 'transporter' ? 'selected' : ''; ?>>Transporters</option>
          <option value="admin" <?php echo ($_GET['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrators</option>
        </select>

        <select id="statusFilter">
          <option value="all" <?php echo ($_GET['status'] ?? '') === '' ? 'selected' : ''; ?>>All Status</option>
          <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
          <option value="suspended" <?php echo ($_GET['status'] ?? '') === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
        </select>

        <button id="applyFilter">Search</button>
      </div>

      <div class="users-table-container">
        <table class="users-table">
          <thead>
            <tr>
              <th>Profile</th>
              <th>Name</th>
              <th>Email</th>
              <th>User Type</th>
              <th>Join Date</th>
              <th>Status</th>
              <th>Listings</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($users) && count($users) > 0): ?>
              <?php foreach ($users as $user): ?>
                <?php 
                  $fullName = htmlspecialchars($user->first_name . ' ' . $user->last_name);
                  $listingsCount = ($user->accommodation_count ?? 0) + ($user->vehicle_count ?? 0);
                  $listingsText = $listingsCount > 0 ? $listingsCount . ' Listing(s)' : '0';
                  $viewPage = ($user->role === 'traveller') ? 'viewtraveller' : 'viewprovider';
                  $roleClass = strtolower($user->role);
                  $statusClass = strtolower($user->status ?? 'active');
                ?>
                <tr data-user-id="<?php echo $user->id; ?>">
                  <td>
                    <div class="profile-pic">
                      <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="<?php echo htmlspecialchars($user->first_name); ?>">
                    </div>
                  </td>
                  <td>
                    <div class="user-name">
                      <strong><?php echo $fullName; ?></strong>
                      <small><?php echo htmlspecialchars($user->phone ?? 'N/A'); ?></small>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($user->email); ?></td>
                  <td><span class="user-type <?php echo $roleClass; ?>"><?php echo ucfirst($user->role); ?></span></td>
                  <td><?php echo date('Y-m-d', strtotime($user->created_at)); ?></td>
                  <td><span class="status <?php echo $statusClass; ?>"><?php echo ucfirst($user->status ?? 'Active'); ?></span></td>
                  <td><?php echo $listingsText; ?></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-view" onclick="window.location.href='<?php echo $viewPage; ?>?id=<?php echo $user->id; ?>';">View</button>
                      <?php if (($user->status ?? 'active') === 'active'): ?>
                        <button class="btn-suspend" onclick="suspendUser(<?php echo $user->id; ?>, '<?php echo addslashes($fullName); ?>')">Suspend</button>
                      <?php else: ?>
                        <button class="btn-activate" onclick="activateUser(<?php echo $user->id; ?>, '<?php echo addslashes($fullName); ?>')">Activate</button>
                      <?php endif; ?>
                      <button class="btn-delete" onclick="deleteUser(<?php echo $user->id; ?>, '<?php echo addslashes($fullName); ?>')">Delete</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                  <p>No users found.</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>⚠️ Confirm User Deletion</h3>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete user "<span id="userName"></span>"?</p>
        <div class="warning-box">
          <p class="warning">⚠️ This action will permanently:</p>
          <ul>
            <li>Delete the user account and profile</li>
            <li>Remove all their listings and content</li>
            <li>Cancel all pending bookings</li>
            <li>Delete all reviews and ratings</li>
          </ul>
          <p class="warning"><strong>This action cannot be undone!</strong></p>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeModal()">Cancel</button>
        <button class="btn-danger" onclick="confirmDelete()">Delete Permanently</button>
      </div>
    </div>
  </div>

  <!-- Suspend Confirmation Modal -->
  <div id="suspendModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>⏸️ Suspend User Account</h3>
        <span class="close" onclick="closeSuspendModal()">&times;</span>
      </div>
      <div class="modal-body">
        <p>Suspend user "<span id="suspendUserName"></span>"?</p>
        <div class="form-group">
          <label for="suspendReason">Reason for suspension:</label>
          <textarea id="suspendReason" rows="3" placeholder="Enter reason for suspension..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeSuspendModal()">Cancel</button>
        <button class="btn-warning" onclick="confirmSuspend()">Suspend User</button>
      </div>
    </div>
  </div>

  <script>
    let userToDelete = null;
    let userToSuspend = null;
    let selectedUsers = [];

    function toggleSelectAll() {
      const selectAll = document.getElementById('selectAll');
      const checkboxes = document.querySelectorAll('.user-checkbox');
      
      checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
      });
      
      updateSelectedUsers();
    }

    function updateSelectedUsers() {
      const checkboxes = document.querySelectorAll('.user-checkbox:checked');
      selectedUsers = Array.from(checkboxes).map(cb => cb.dataset.userId);
      document.querySelector('.selected-count').textContent = `${selectedUsers.length} users selected`;
    }

    // Filter functionality
    function filterUsers() {
      const searchText = document.getElementById('searchBox').value.toLowerCase();
      const userTypeFilter = document.getElementById('userTypeFilter').value.toLowerCase();
      const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
      
      const rows = document.querySelectorAll('.users-table tbody tr');
      
      rows.forEach(row => {
        const name = row.querySelector('.user-name strong')?.textContent.toLowerCase() || '';
        const email = row.querySelectorAll('td')[2]?.textContent.toLowerCase() || '';
        const phone = row.querySelector('.user-name small')?.textContent.toLowerCase() || '';
        const userType = row.querySelector('.user-type')?.textContent.toLowerCase() || '';
        const status = row.querySelector('.status')?.textContent.toLowerCase() || '';
        
        // Check search text match
        const matchesSearch = searchText === '' || 
                            name.includes(searchText) || 
                            email.includes(searchText) || 
                            phone.includes(searchText);
        
        // Check user type filter
        const matchesUserType = userTypeFilter === 'all' || userType.includes(userTypeFilter);
        
        // Check status filter
        const matchesStatus = statusFilter === 'all' || status.includes(statusFilter);
        
        // Show or hide row
        if (matchesSearch && matchesUserType && matchesStatus) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    // Add event listeners to checkboxes
    document.addEventListener('DOMContentLoaded', function() {
      const checkboxes = document.querySelectorAll('.user-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedUsers);
      });
      
      // Add filter event listeners
      const searchBox = document.getElementById('searchBox');
      const userTypeFilter = document.getElementById('userTypeFilter');
      const statusFilter = document.getElementById('statusFilter');
      const applyFilter = document.getElementById('applyFilter');
      
      // Real-time search as user types
      searchBox.addEventListener('input', filterUsers);
      
      // Filter on dropdown change
      userTypeFilter.addEventListener('change', filterUsers);
      statusFilter.addEventListener('change', filterUsers);
      
      // Filter on button click
      applyFilter.addEventListener('click', filterUsers);
      
      // Filter on Enter key in search box
      searchBox.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          filterUsers();
        }
      });
    });

    function viewUser(id) {
      alert('Viewing user ID: ' + id);
      // Implement view functionality
    }

    function deleteUser(id, name) {
      userToDelete = id;
      document.getElementById('userName').textContent = name;
      document.getElementById('deleteModal').style.display = 'block';
    }

    function suspendUser(id, name) {
      userToSuspend = id;
      document.getElementById('suspendUserName').textContent = name;
      document.getElementById('suspendModal').style.display = 'block';
    }

    function activateUser(id, name) {
      if (confirm(`Activate user "${name}"?`)) {
        fetch('/api/admin/user/activate', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({user_id: id})
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('User activated successfully');
            location.reload();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to activate user');
        });
      }
    }

    function closeModal() {
      document.getElementById('deleteModal').style.display = 'none';
      userToDelete = null;
    }

    function closeSuspendModal() {
      document.getElementById('suspendModal').style.display = 'none';
      userToSuspend = null;
      document.getElementById('suspendReason').value = '';
    }

    function confirmDelete() {
      if (userToDelete) {
        fetch('/api/admin/user/delete', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({user_id: userToDelete})
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('User deleted successfully');
            closeModal();
            location.reload();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to delete user');
        });
      }
    }

    function confirmSuspend() {
      if (userToSuspend) {
        const reason = document.getElementById('suspendReason').value;
        fetch('/api/admin/user/suspend', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({user_id: userToSuspend, reason: reason})
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('User suspended successfully');
            closeSuspendModal();
            location.reload();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to suspend user');
        });
      }
    }

    function bulkSuspend() {
      if (selectedUsers.length === 0) {
        alert('Please select users to suspend');
        return;
      }
      if (confirm(`Suspend ${selectedUsers.length} selected users?`)) {
        alert('Bulk suspended users: ' + selectedUsers.join(', '));
        // Implement bulk suspend functionality
      }
    }

    function bulkDelete() {
      if (selectedUsers.length === 0) {
        alert('Please select users to delete');
        return;
      }
      if (confirm(`Permanently delete ${selectedUsers.length} selected users? This action cannot be undone!`)) {
        alert('Bulk deleted users: ' + selectedUsers.join(', '));
        // Implement bulk delete functionality
      }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const deleteModal = document.getElementById('deleteModal');
      const suspendModal = document.getElementById('suspendModal');
      
      if (event.target === deleteModal) {
        closeModal();
      }
      if (event.target === suspendModal) {
        closeSuspendModal();
      }
    }
  </script>

</body>
</html>
