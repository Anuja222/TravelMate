<!DOCTYPE html>
<html>
<head>
  <title>Delete Users</title>
  <link rel="stylesheet" href="assets/css/Admin/Users.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="assets/css/Admin/common.css?v=<?php echo time(); ?>">
</head>
<body>

  <?php include __DIR__ . '/../Traveller/header.view.php'; ?>

<div class="page-container">  
  <?php include 'sidebar.view.php'; ?>

    <div class="content">
      <div class="page-title">
        <h1>Users</h1>
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
                </div>
              </td>
              <td>
                <div class="user-name">
                  <strong>Lakmal Perera</strong>
                  <small>+94 77 123 4567</small>
                </div>
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