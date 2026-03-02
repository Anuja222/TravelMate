<!DOCTYPE html>
<html>
<head>
  <title>Users Management - TravelMate</title>
  <link rel="stylesheet" href="assets/css/Admin/Users.css">
  <link rel="stylesheet" href="assets/css/Admin/common.css">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<div class="page-container">  
  <?php include 'sidebar.view.php'; ?>

    <div class="content">

      <div class="page-title">
        <div class="page-title-content">
          <div class="page-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
          </div>
          <div class="page-title-text">
            <h1>Users Management</h1>
            <p class="page-subtitle">Manage and monitor all registered users</p>
          </div>
        </div>
        <?php if (isset($users) && count($users) > 0): ?>
          <p class="user-count">Total Users: <span><?php echo count($users); ?></span></p>
        <?php endif; ?>
      </div>

      <div class="filter-bar">
        <input type="text" id="searchBox" placeholder="Search users by name, email, phone...">
        
        <select id="userTypeFilter">
          <option value="all">All User Types</option>
          <option value="traveller">Travellers</option>
          <option value="accommodation">Accommodation Providers</option>
          <option value="transport">Transport Providers</option>
          <option value="admin">Administrators</option>
        </select>

        <select id="statusFilter">
          <option value="all">All Status</option>
          <option value="active">Active</option>
          <option value="suspended">Suspended</option>
          <option value="inactive">Inactive</option>
        </select>

        <button id="applyFilter">Apply Filters</button>
      </div>

      <div class="users-grid">
        <?php if (isset($users) && count($users) > 0): ?>
          <?php foreach ($users as $user): ?>
            <div class="user-card">
              <div class="user-card-header">
                <div class="profile-pic-large">
                  <?php if (!empty($user->profile_picture)): ?>
                    <img src="<?php echo htmlspecialchars($user->profile_picture); ?>" alt="<?php echo htmlspecialchars($user->first_name ?? 'User'); ?>">
                  <?php else: ?>
                    <img src="assets/images/profile.jpg" alt="User">
                  <?php endif; ?>
                </div>
              </div>
              
              <div class="user-card-body">
                <h3 class="user-card-name"><?php echo htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')); ?></h3>
                
                <div class="user-card-info">
                  <div class="info-item">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span><?php echo htmlspecialchars($user->email ?? 'No email'); ?></span>
                  </div>
                  
                  <div class="info-item">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span><?php echo htmlspecialchars($user->phone ?? 'No phone'); ?></span>
                  </div>
                  
                  <div class="info-item">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                      <line x1="16" y1="2" x2="16" y2="6"></line>
                      <line x1="8" y1="2" x2="8" y2="6"></line>
                      <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Joined: <?php echo isset($user->created_at) ? date('M d, Y', strtotime($user->created_at)) : 'N/A'; ?></span>
                  </div>
                </div>
              </div>
              
              <div class="user-card-footer">
                <button class="btn-view" onclick='viewUserDetails(<?php echo json_encode($user); ?>)'>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  View
                </button>
                
                <button class="btn-suspend" onclick="suspendUser(<?php echo $user->id; ?>, '<?php echo htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''), ENT_QUOTES); ?>')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="10" y1="15" x2="10" y2="9"></line>
                    <line x1="14" y1="15" x2="14" y2="9"></line>
                  </svg>
                  Suspend
                </button>
                
                <button class="btn-delete" onclick="deleteUser(<?php echo $user->id; ?>, '<?php echo htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''), ENT_QUOTES); ?>')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
                  Delete
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-users">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <h3>No users found</h3>
            <p>There are no users to display at the moment.</p>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Confirm User Deletion</h3>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete user "<span id="userName"></span>"?</p>
        <div class="warning-box">
          <p class="warning">This action will permanently:</p>
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
        <h3>Suspend User Account</h3>
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

  <!-- View User Details Modal -->
  <div id="viewUserModal" class="modal" style="overflow-y: auto;">
    <div class="modal-content" style="max-width: 700px; margin: 20px auto; max-height: 90vh; display: flex; flex-direction: column;">
      <div class="modal-header">
        <h3>User Details</h3>
        <span class="close" onclick="closeViewUserModal()">&times;</span>
      </div>
      <div class="modal-body" style="max-height: none; overflow-y: auto; flex: 1;">
        <div style="text-align: center; margin-bottom: 20px;">
          <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; margin: 0 auto 15px; border: 3px solid #1abc5b;">
            <img id="viewUserImage" src="assets/images/profile.jpg" alt="User" style="width: 100%; height: 100%; object-fit: cover;">
          </div>
          <h2 id="viewUserFullName" style="margin: 0 0 8px 0; color: #2c3e50; font-size: 1.5em;"></h2>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
          <h4 style="margin: 0 0 12px 0; color: #2c3e50; border-bottom: 2px solid #1abc5b; padding-bottom: 6px; font-size: 1.1em;">Personal Information</h4>
          
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
            <div>
              <strong style="color: #666; font-size: 0.85em;">First Name</strong>
              <p id="viewUserFirstName" style="margin: 4px 0 0 0; color: #2c3e50;"></p>
            </div>
            <div>
              <strong style="color: #666; font-size: 0.85em;">Last Name</strong>
              <p id="viewUserLastName" style="margin: 4px 0 0 0; color: #2c3e50;"></p>
            </div>
          </div>

          <div style="margin-bottom: 12px;">
            <strong style="color: #666; font-size: 0.85em; display: flex; align-items: center; gap: 6px;">
              <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              Email Address
            </strong>
            <p id="viewUserEmail" style="margin: 4px 0 0 0; color: #2c3e50; word-break: break-word;"></p>
          </div>

          <div style="margin-bottom: 12px;">
            <strong style="color: #666; font-size: 0.85em; display: flex; align-items: center; gap: 6px;">
              <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              Phone Number
            </strong>
            <p id="viewUserPhone" style="margin: 4px 0 0 0; color: #2c3e50;"></p>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
              <strong style="color: #666; font-size: 0.85em;">Date of Birth</strong>
              <p id="viewUserDOB" style="margin: 4px 0 0 0; color: #2c3e50;"></p>
            </div>
            <div>
              <strong style="color: #666; font-size: 0.85em;">Gender</strong>
              <p id="viewUserGender" style="margin: 4px 0 0 0; color: #2c3e50;"></p>
            </div>
          </div>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
          <h4 style="margin: 0 0 12px 0; color: #2c3e50; border-bottom: 2px solid #1abc5b; padding-bottom: 6px; font-size: 1.1em;">Account Information</h4>
          
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
              <strong style="color: #666; font-size: 0.85em;">User ID</strong>
              <p id="viewUserId" style="margin: 4px 0 0 0; color: #2c3e50; font-family: monospace;"></p>
            </div>
            <div>
              <strong style="color: #666; font-size: 0.85em;">Account Type</strong>
              <p id="viewUserRole" style="margin: 4px 0 0 0; color: #2c3e50;"></p>
            </div>
            <div>
              <strong style="color: #666; font-size: 0.85em;">Status</strong>
              <p id="viewUserStatus" style="margin: 4px 0 0 0; color: #2c3e50;"></p>
            </div>
            <div>
              <strong style="color: #666; font-size: 0.85em;">Member Since</strong>
              <p id="viewUserJoined" style="margin: 4px 0 0 0; color: #2c3e50;"></p>
            </div>
          </div>
        </div>

        <div id="viewUserBioSection" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px; display: none;">
          <h4 style="margin: 0 0 12px 0; color: #2c3e50; border-bottom: 2px solid #1abc5b; padding-bottom: 6px; font-size: 1.1em;">Biography</h4>
          <p id="viewUserBio" style="margin: 0; color: #2c3e50; line-height: 1.6;"></p>
        </div>

        <div id="viewUserAddressSection" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px; display: none;">
          <h4 style="margin: 0 0 12px 0; color: #2c3e50; border-bottom: 2px solid #1abc5b; padding-bottom: 6px; font-size: 1.1em;">Location</h4>
          <p id="viewUserAddress" style="margin: 0; color: #2c3e50; line-height: 1.6;"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeViewUserModal()">Close</button>
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
      const selectedCountEl = document.querySelector('.selected-count');
      if (selectedCountEl) {
        selectedCountEl.textContent = `${selectedUsers.length} users selected`;
      }
    }

    function viewUserDetails(user) {
      // Populate modal with user data
      document.getElementById('viewUserImage').src = user.profile_picture || 'assets/images/profile.jpg';
      document.getElementById('viewUserFullName').textContent = (user.first_name || '') + ' ' + (user.last_name || '');
      document.getElementById('viewUserFirstName').textContent = user.first_name || 'N/A';
      document.getElementById('viewUserLastName').textContent = user.last_name || 'N/A';
      document.getElementById('viewUserEmail').textContent = user.email || 'N/A';
      document.getElementById('viewUserPhone').textContent = user.phone || 'N/A';
      document.getElementById('viewUserDOB').textContent = user.date_of_birth || 'N/A';
      document.getElementById('viewUserGender').textContent = user.gender ? (user.gender.charAt(0).toUpperCase() + user.gender.slice(1)) : 'N/A';
      document.getElementById('viewUserId').textContent = '#' + (user.id || 'N/A');
      document.getElementById('viewUserRole').textContent = user.role ? (user.role.charAt(0).toUpperCase() + user.role.slice(1)) : 'N/A';
      document.getElementById('viewUserStatus').textContent = user.status ? (user.status.charAt(0).toUpperCase() + user.status.slice(1)) : 'N/A';
      
      // Format date
      if (user.created_at) {
        const date = new Date(user.created_at);
        document.getElementById('viewUserJoined').textContent = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
      } else {
        document.getElementById('viewUserJoined').textContent = 'N/A';
      }

      // Optional fields
      if (user.bio && user.bio.trim()) {
        document.getElementById('viewUserBio').textContent = user.bio;
        document.getElementById('viewUserBioSection').style.display = 'block';
      } else {
        document.getElementById('viewUserBioSection').style.display = 'none';
      }

      // Construct address from city and country
      let addressText = '';
      if (user.city && user.country) {
        addressText = user.city + ', ' + user.country;
      } else if (user.city) {
        addressText = user.city;
      } else if (user.country) {
        addressText = user.country;
      }
      
      if (addressText) {
        document.getElementById('viewUserAddress').textContent = addressText;
        document.getElementById('viewUserAddressSection').style.display = 'block';
      } else {
        document.getElementById('viewUserAddressSection').style.display = 'none';
      }

      // Show modal
      document.getElementById('viewUserModal').style.display = 'block';
    }

    function closeViewUserModal() {
      document.getElementById('viewUserModal').style.display = 'none';
    }

    // Add event listeners to checkboxes
    document.addEventListener('DOMContentLoaded', function() {
      const checkboxes = document.querySelectorAll('.user-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedUsers);
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
        alert('Activated user ID: ' + id);
        // Implement activation functionality
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
        alert('Deleted user ID: ' + userToDelete);
        // Implement actual delete functionality
        closeModal();
      }
    }

    function confirmSuspend() {
      if (userToSuspend) {
        const reason = document.getElementById('suspendReason').value;
        alert(`Suspended user ID: ${userToSuspend}\nReason: ${reason}`);
        // Implement actual suspend functionality
        closeSuspendModal();
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
      const viewUserModal = document.getElementById('viewUserModal');
      
      if (event.target === deleteModal) {
        closeModal();
      }
      if (event.target === suspendModal) {
        closeSuspendModal();
      }
      if (event.target === viewUserModal) {
        closeViewUserModal();
      }
    }
  </script>

</body>
</html>