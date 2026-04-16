<!DOCTYPE html>
<html>
<head>
<<<<<<< HEAD
  <title>Users Management - TravelMate</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/Users.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css">
=======
  <title>Delete Users</title>
  <link rel="stylesheet" href="assets/css/Admin/Users.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="assets/css/Admin/common.css?v=<?php echo time(); ?>">
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<div class="page-container">  
  <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <div class="page-title">
<<<<<<< HEAD
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
=======
        <h1>Users</h1>
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
      </div>

      <div class="filter-bar">
        <input type="text" id="searchBox" placeholder="🔍 Search users by name, email, phone...">
        
        <select id="userTypeFilter">
          <option value="all">All User Types</option>
          <option value="traveler">Travelers</option>
          <option value="provider">Service Providers</option>
          <option value="admin">Administrators</option>
        </select>

        <select id="statusFilter">
          <option value="all">All Status</option>
          <option value="active">Active</option>
          <option value="suspended">Suspended</option>
          <option value="inactive">Inactive</option>
        </select>

        <button id="applyFilter">Search</button>
      </div>

<<<<<<< HEAD
      <div class="users-grid">
        <?php if (isset($users) && count($users) > 0): ?>
          <?php foreach ($users as $user): ?>
            <div class="user-card"
                 data-name="<?php echo htmlspecialchars(strtolower(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')), ENT_QUOTES); ?>"
                 data-email="<?php echo htmlspecialchars(strtolower($user->email ?? ''), ENT_QUOTES); ?>"
                 data-phone="<?php echo htmlspecialchars(strtolower($user->phone ?? ''), ENT_QUOTES); ?>"
                 data-role="<?php echo htmlspecialchars(strtolower($user->role ?? ''), ENT_QUOTES); ?>"
                 data-status="<?php echo htmlspecialchars(strtolower($user->status ?? 'active'), ENT_QUOTES); ?>">
              <div class="user-card-header">
                <div class="profile-pic-large">
                  <?php if (!empty($user->profile_picture)): ?>
                    <img src="<?= ROOT ?>/<?php echo htmlspecialchars($user->profile_picture); ?>" alt="<?php echo htmlspecialchars($user->first_name ?? 'User'); ?>">
                  <?php else: ?>
                    <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="User">
                  <?php endif; ?>
=======
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
            <tr>
              <td>
                <div class="profile-pic">
                  <img src="assets/images/profile.jpg" alt="Lakmal">
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
                </div>
              </td>
              <td>
                <div class="user-name">
                  <strong>Lakmal Perera</strong>
                  <small>+94 77 123 4567</small>
                </div>
<<<<<<< HEAD
              </div>
              
              <div class="user-card-footer">
                <button class="btn-view" onclick='viewUserDetails(<?php echo json_encode($user); ?>)'>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  View
                </button>
                
                <?php if($user->status === 'suspended'): ?>
                <button class="btn-suspend" style="background:#52c41a" onclick="unsuspendUser(<?php echo $user->id; ?>, '<?php echo htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''), ENT_QUOTES); ?>')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 4v6h6M22 20v-6h-6M20.49 9A9 9 0 0 0 5.64 5.64L1 10M3.51 15A9 9 0 0 0 18.36 18.36L23 14" />
                  </svg>
                  Unsuspend
                </button>
                <?php else: ?>
                <button class="btn-suspend" onclick="suspendUser(<?php echo $user->id; ?>, '<?php echo htmlspecialchars(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''), ENT_QUOTES); ?>')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="10" y1="15" x2="10" y2="9"></line>
                    <line x1="14" y1="15" x2="14" y2="9"></line>
                  </svg>
                  Suspend
                </button>
                <?php endif; ?>
                
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
=======
              </td>
              <td>lakmal.perera@email.com</td>
              <td><span class="user-type provider">Provider</span></td>
              <td>2024-01-15</td>
              <td><span class="status active">Active</span></td>
              <td>3 Hotels</td>
              <td>
                <div class="action-buttons">
                  <button class="btn-view" onclick="window.location.href='viewprovider';">View</button>
                  <button class="btn-suspend" onclick="suspendUser(1, 'Lakmal Perera')">Suspend</button>
                  <button class="btn-delete" onclick="deleteUser(1, 'Lakmal Perera')">Delete</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="profile-pic">
                  <img src="assets/images/profile.jpg" alt="Anuja">
                </div>
              </td>
              <td>
                <div class="user-name">
                  <strong>Anuja Silva</strong>
                  <small>+94 71 987 6543</small>
                </div>
              </td>
              <td>anuja.silva@email.com</td>
              <td><span class="user-type provider">Provider</span></td>
              <td>2024-02-03</td>
              <td><span class="status active">Active</span></td>
              <td>2 Vehicles</td>
              <td>
                <div class="action-buttons">
                  <button class="btn-view" onclick="window.location.href='viewprovider';">View</button>
                  <button class="btn-suspend" onclick="suspendUser(2, 'Anuja Silva')">Suspend</button>
                  <button class="btn-delete" onclick="deleteUser(2, 'Anuja Silva')">Delete</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="profile-pic">
                  <img src="assets/images/profile.jpg" alt="Saman">
                </div>
              </td>
              <td>
                <div class="user-name">
                  <strong>Saman Wijeratne</strong>
                  <small>+94 76 456 7890</small>
                </div>
              </td>
              <td>saman.w@email.com</td>
              <td><span class="user-type traveler">Traveler</span></td>
              <td>2024-01-20</td>
              <td><span class="status active">Active</span></td>
              <td>0</td>
              <td>
                <div class="action-buttons">
                  <button class="btn-view" onclick="window.location.href='viewtraveller';">View</button>
                  <button class="btn-suspend" onclick="suspendUser(3, 'Saman Wijeratne')">Suspend</button>
                  <button class="btn-delete" onclick="deleteUser(3, 'Saman Wijeratne')">Delete</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="profile-pic">
                  <img src="assets/images/profile.jpg" alt="Minoli">
                </div>
              </td>
              <td>
                <div class="user-name">
                  <strong>Minoli Fernando</strong>
                  <small>+94 75 321 6547</small>
                </div>
              </td>
              <td>minoli.fernando@email.com</td>
              <td><span class="user-type provider">Provider</span></td>
              <td>2024-01-28</td>
              <td><span class="status suspended">Suspended</span></td>
              <td>1 Hotel</td>
              <td>
                <div class="action-buttons">
                  <button class="btn-view" onclick="window.location.href='viewprovider';">View</button>
                  <button class="btn-suspend" onclick="activateUser(4, 'Minoli Fernando')">Suspend</button>
                  <button class="btn-delete" onclick="deleteUser(4, 'Minoli Fernando')">Delete</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="profile-pic">
                  <img src="assets/images/profile.jpg" alt="Rasika">
                </div>
              </td>
              <td>
                <div class="user-name">
                  <strong>Rasika Jayasinghe</strong>
                  <small>+94 77 789 1234</small>
                </div>
              </td>
              <td>rasika.j@email.com</td>
              <td><span class="user-type traveler">Traveler</span></td>
              <td>2024-02-10</td>
              <td><span class="status active">Active</span></td>
              <td>0</td>
              <td>
                <div class="action-buttons">
                  <button class="btn-view" onclick="window.location.href='viewtraveller';">View</button>
                  <button class="btn-suspend" onclick="suspendUser(5, 'Rasika Jayasinghe')">Suspend</button>
                  <button class="btn-delete" onclick="deleteUser(5, 'Rasika Jayasinghe')">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
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

<<<<<<< HEAD
  <!-- Unsuspend Confirmation Modal -->
  <div id="unsuspendModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Unsuspend User Account</h3>
        <span class="close" onclick="closeUnsuspendModal()">&times;</span>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to unsuspend user "<span id="unsuspendUserName"></span>"?</p>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeUnsuspendModal()">Cancel</button>
        <button class="btn-suspend" style="background:#52c41a; margin: 0; display: inline-block; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; color: white" onclick="confirmUnsuspend()">Unsuspend User</button>
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
            <img id="viewUserImage" src="<?= ROOT ?>/assets/images/profile.jpg" alt="User" style="width: 100%; height: 100%; object-fit: cover;">
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
            <div id="viewUserSuspendReasonContainer" style="display: none;">
              <strong style="color: #666; font-size: 0.85em;">Suspend Reason</strong>
              <p id="viewUserSuspendReason" style="margin: 4px 0 0 0; color: #e74c3c; font-weight:bold;"></p>
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

  <!-- Success Modal -->
  <div id="successModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Success</h3>
        <span class="close" onclick="closeSuccessModal()">&times;</span>
      </div>
      <div class="modal-body">
        <p id="successMessage">Operation completed successfully.</p>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeSuccessModal()">OK</button>
      </div>
    </div>
  </div>

=======
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
  <script>
    let userToDelete = null;
    let userToSuspend = null;
    let userToUnsuspend = null;
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

<<<<<<< HEAD
    function viewUserDetails(user) {
      // Populate modal with user data
      document.getElementById('viewUserImage').src = user.profile_picture ? ('<?= ROOT ?>/' + user.profile_picture) : '<?= ROOT ?>/assets/images/profile.jpg';
      document.getElementById('viewUserFullName').textContent = (user.first_name || '') + ' ' + (user.last_name || '');
      document.getElementById('viewUserFirstName').textContent = user.first_name || 'N/A';
      document.getElementById('viewUserLastName').textContent = user.last_name || 'N/A';
      document.getElementById('viewUserEmail').textContent = user.email || 'N/A';
      document.getElementById('viewUserPhone').textContent = user.phone || 'N/A';
      document.getElementById('viewUserDOB').textContent = user.date_of_birth || 'N/A';
      document.getElementById('viewUserGender').textContent = user.gender ? (user.gender.charAt(0).toUpperCase() + user.gender.slice(1)) : 'N/A';
      document.getElementById('viewUserId').textContent = '#' + (user.id || 'N/A');
      document.getElementById('viewUserRole').textContent = user.role ? (user.role.charAt(0).toUpperCase() + user.role.slice(1)) : 'N/A';
      document.getElementById('viewUserStatus').textContent = user.status ? (user.status.charAt(0).toUpperCase() + user.status.slice(1)) : 'Active';
      
      if (user.status === 'suspended' && user.suspend_reason) {
          document.getElementById('viewUserSuspendReason').textContent = user.suspend_reason;
          document.getElementById('viewUserSuspendReasonContainer').style.display = 'block';
      } else {
          document.getElementById('viewUserSuspendReasonContainer').style.display = 'none';
      }

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
=======
    // Filter functionality
    function filterUsers() {
      const searchText = document.getElementById('searchBox').value.toLowerCase();
      const userTypeFilter = document.getElementById('userTypeFilter').value.toLowerCase();
      const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
      
      const rows = document.querySelectorAll('.users-table tbody tr');
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
      
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

    // Add event listeners to checkboxes and filters
    document.addEventListener('DOMContentLoaded', function() {
      const checkboxes = document.querySelectorAll('.user-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedUsers);
      });
<<<<<<< HEAD

      // Filter functionality
      const applyBtn = document.getElementById('applyFilter');
      const searchBox = document.getElementById('searchBox');
      const typeFilter = document.getElementById('userTypeFilter');
      const statusFilter = document.getElementById('statusFilter');

      function filterUsers() {
        const searchTerm = searchBox ? searchBox.value.toLowerCase().trim() : '';
        const selectedType = typeFilter ? typeFilter.value.toLowerCase() : 'all';
        const selectedStatus = statusFilter ? statusFilter.value.toLowerCase() : 'all';
        
        const userCards = document.querySelectorAll('.user-card');
        let visibleCount = 0;
        
        userCards.forEach(card => {
          const name = card.getAttribute('data-name') || '';
          const email = card.getAttribute('data-email') || '';
          const phone = card.getAttribute('data-phone') || '';
          const role = card.getAttribute('data-role') || '';
          let status = card.getAttribute('data-status') || 'active';
          
          const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm) || phone.includes(searchTerm);
          const matchesType = (selectedType === 'all') || (role === selectedType);
          const matchesStatus = (selectedStatus === 'all') || (status === selectedStatus);
          
          if (matchesSearch && matchesType && matchesStatus) {
            card.style.display = ''; // Revert to stylesheet default
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });
        
        // Handle "No users found" state dynamically when filtering zero results
        let noUsersMsg = document.querySelector('.no-users-dynamic');
        if (visibleCount === 0 && userCards.length > 0) {
          if (!noUsersMsg) {
             const grid = document.querySelector('.users-grid');
             grid.insertAdjacentHTML('beforeend', `
               <div class="no-users no-users-dynamic" style="width:100%; grid-column: 1 / -1; margin-top:20px; text-align:center;">
                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:48px;height:48px;color:#a0aec0;margin-bottom:15px;display:inline-block;">
                   <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                   <circle cx="12" cy="7" r="4"></circle>
                 </svg>
                 <h3>No matching users found</h3>
                 <p style="color:#718096; margin-top:5px;">Try adjusting your search or filters.</p>
               </div>
             `);
          } else {
             noUsersMsg.style.display = 'block';
          }
        } else if (noUsersMsg) {
          noUsersMsg.style.display = 'none';
        }
      }

      if (applyBtn) applyBtn.addEventListener('click', filterUsers);
      // Optional: Filter in real-time immediately when typing or selecting dropdowns
      if (searchBox) searchBox.addEventListener('input', filterUsers);
      if (typeFilter) typeFilter.addEventListener('change', filterUsers);
      if (statusFilter) statusFilter.addEventListener('change', filterUsers);
=======
      
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
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
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

    function closeSuccessModal() {
      document.getElementById('successModal').style.display = 'none';
      window.location.reload();
    }

    function confirmDelete() {
      if (userToDelete) {
        const formData = new FormData();
        formData.append('id', userToDelete);
        
        fetch('<?= ROOT ?>/admin_delete_user', {
          method: 'POST',
          body: formData
        }).then(response => response.json())
          .then(data => {
            if(data.success) {
              closeModal();
              document.getElementById('successMessage').textContent = 'This user has been permanently deleted.';
              document.getElementById('successModal').style.display = 'block';
            } else {
              alert('Failed to delete user: ' + (data.error || 'Unknown error'));
            }
          }).catch(e => {
            console.error('Error:', e);
            alert('A network error occurred.');
          });
      }
    }

    function unsuspendUser(id, name) {
      userToUnsuspend = id;
      document.getElementById('unsuspendUserName').textContent = name;
      document.getElementById('unsuspendModal').style.display = 'block';
    }

    function closeUnsuspendModal() {
      document.getElementById('unsuspendModal').style.display = 'none';
      userToUnsuspend = null;
    }

    function confirmUnsuspend() {
      if (userToUnsuspend) {
        const formData = new FormData();
        formData.append('id', userToUnsuspend);
        
        fetch('<?= ROOT ?>/admin_unsuspend_user', {
          method: 'POST',
          body: formData
        }).then(response => response.json())
          .then(data => {
            if(data.success) {
              closeUnsuspendModal();
              document.getElementById('successMessage').textContent = 'This user has been unsuspended.';
              document.getElementById('successModal').style.display = 'block';
            } else {
              alert('Failed to unsuspend user: ' + (data.error || 'Unknown error'));
            }
          }).catch(e => {
            console.error('Error:', e);
            alert('A network error occurred.');
          });
      }
    }

    function confirmSuspend() {
      if (userToSuspend) {
        const reason = document.getElementById('suspendReason').value;
        if (!reason.trim()) {
           alert("Please enter a reason for suspension.");
           return;
        }
        
        const formData = new FormData();
        formData.append('id', userToSuspend);
        formData.append('reason', reason);

        fetch('<?= ROOT ?>/admin_suspend_user', {
          method: 'POST',
          body: formData
        }).then(response => response.json())
          .then(data => {
            if(data.success) {
              closeSuspendModal();
              document.getElementById('successMessage').textContent = 'This user is suspended.';
              document.getElementById('successModal').style.display = 'block';
            } else {
              alert('Failed to suspend user: ' + (data.error || 'Unknown error'));
            }
          }).catch(e => {
            console.error('Error:', e);
            alert('A network error occurred.');
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
<<<<<<< HEAD
      const unsuspendModal = document.getElementById('unsuspendModal');
      const viewUserModal = document.getElementById('viewUserModal');
      const successModal = document.getElementById('successModal');
=======
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
      
      if (event.target === deleteModal) {
        closeModal();
      }
      if (event.target === suspendModal) {
        closeSuspendModal();
      }
<<<<<<< HEAD
      if (event.target === unsuspendModal) {
        closeUnsuspendModal();
      }
      if (event.target === viewUserModal) {
        closeViewUserModal();
      }
      if (event.target === successModal) {
        closeSuccessModal();
      }
=======
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874
    }
  </script>

</body>
</html>