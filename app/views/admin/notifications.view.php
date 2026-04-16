<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Management</title>
     <link rel="stylesheet" href="assets/css/Admin/common.css">
      <link rel="stylesheet" href="assets/css/Admin/notifications.css">
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
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <div class="page-title-text">
                    <h1>Notifications</h1>
                    <p class="page-subtitle">Manage system notifications and alerts</p>
                </div>
            </div>
        </div>

        <div class="filter-bar">
            <input type="text" id="searchBox" placeholder="🔍 Search notifications...">
            
            <select id="typeFilter">
                <option value="all">All Types</option>
                <option value="booking">Booking</option>
                <option value="user">User</option>
                <option value="vlog">Vlog</option>
                <option value="payment">Payment</option>
                <option value="system">System</option>
                <option value="transport">Transport</option>
            </select>

            <select id="statusFilter">
                <option value="all">All Status</option>
                <option value="unread">Unread</option>
                <option value="read">Read</option>
            </select>

            <button id="applyFilter">Search</button>
            <button id="clearAll" class="clear-btn">Clear All</button>
        </div>

        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">📨</div>
                <div class="stat-info">
                    <div class="stat-title">Total Notifications</div>
                    <div class="stat-value">24</div>
                </div>
            </div>
            <div class="stat-card unread">
                <div class="stat-icon">🔔</div>
                <div class="stat-info">
                    <div class="stat-title">Unread</div>
                    <div class="stat-value">8</div>
                </div>
            </div>
            <div class="stat-card today">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <div class="stat-title">Today</div>
                    <div class="stat-value">5</div>
                </div>
            </div>
            <div class="stat-card urgent">
                <div class="stat-icon">⚠️</div>
                <div class="stat-info">
                    <div class="stat-title">Urgent</div>
                    <div class="stat-value">3</div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="notification-row unread">
                        <td>N001</td>
                        <td><span class="notification-type booking-type">Booking</span></td>
                        <td>New Booking Received</td>
                        <td>Sunrise Resort has received a new booking from John Doe for 3 nights</td>
                        <td>2 hours ago</td>
                        <td><span class="status unread">Unread</span></td>
                        <td class="actions">
                            <button class="view-btn">View</button>
                            <button class="dismiss-btn">Dismiss</button>
                        </td>
                    </tr>
                    <tr class="notification-row unread">
                        <td>N002</td>
                        <td><span class="notification-type user-type">User</span></td>
                        <td>New User Registration</td>
                        <td>Sarah Johnson has registered as a new user and is awaiting verification</td>
                        <td>4 hours ago</td>
                        <td><span class="status unread">Unread</span></td>
                        <td class="actions">
                            <button class="view-btn">View</button>
                            <button class="dismiss-btn">Dismiss</button>
                        </td>
                    </tr>
                    <tr class="notification-row unread">
                        <td>N003</td>
                        <td><span class="notification-type vlog-type">Vlog</span></td>
                        <td>Vlog Approval Request</td>
                        <td>"Amazing Sri Lankan Adventure" vlog by TravelExplorer needs approval</td>
                        <td>6 hours ago</td>
                        <td><span class="status unread">Unread</span></td>
                        <td class="actions">
                            <button class="view-btn">View</button>
                            <button class="dismiss-btn">Dismiss</button>
                        </td>
                    </tr>
                    <tr>
                        <td>N004</td>
                        <td><span class="notification-type payment-type">Payment</span></td>
                        <td>Payment Issue Resolved</td>
                        <td>Payment dispute for booking #12345 has been successfully resolved</td>
                        <td>1 day ago</td>
                        <td><span class="status read">Read</span></td>
                        <td class="actions">
                            <button class="view-btn">View</button>
                            <button class="dismiss-btn">Dismiss</button>
                        </td>
                    </tr>
                    <tr>
                        <td>N005</td>
                        <td><span class="notification-type system-type">System</span></td>
                        <td>System Maintenance</td>
                        <td>Scheduled maintenance on Dec 20, 2024 from 2:00 AM to 4:00 AM</td>
                        <td>2 days ago</td>
                        <td><span class="status read">Read</span></td>
                        <td class="actions">
                            <button class="view-btn">View</button>
                            <button class="dismiss-btn">Dismiss</button>
                        </td>
                    </tr>
                    <tr>
                        <td>N006</td>
                        <td><span class="notification-type transport-type">Transport</span></td>
                        <td>Transport Provider Request</td>
                        <td>Rajesh Motors has submitted an application to become a verified provider</td>
                        <td>3 days ago</td>
                        <td><span class="status read">Read</span></td>
                        <td class="actions">
                            <button class="view-btn">View</button>
                            <button class="dismiss-btn">Dismiss</button>
                        </td>
                    </tr>
                    <tr>
                        <td>N007</td>
                        <td><span class="notification-type booking-type">Booking</span></td>
                        <td>Booking Cancellation</td>
                        <td>Guest has cancelled booking #56789 for Ocean View Hotel</td>
                        <td>4 days ago</td>
                        <td><span class="status read">Read</span></td>
                        <td class="actions">
                            <button class="view-btn">View</button>
                            <button class="dismiss-btn">Dismiss</button>
                        </td>
                    </tr>
                    <tr>
                        <td>N008</td>
                        <td><span class="notification-type user-type">User</span></td>
                        <td>Profile Update Request</td>
                        <td>Travel agent "Lanka Tours" has requested profile verification</td>
                        <td>5 days ago</td>
                        <td><span class="status unread">Unread</span></td>
                        <td class="actions">
                            <button class="view-btn">View</button>
                            <button class="dismiss-btn">Dismiss</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <script>
        // Filter functionality
        function filterNotifications() {
            const searchTerm = document.getElementById('searchBox').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                // Skip if it's an empty state row
                if (row.cells.length < 7) return;
                
                const title = row.cells[2]?.textContent.toLowerCase() || '';
                const message = row.cells[3]?.textContent.toLowerCase() || '';
                const type = row.cells[1]?.textContent.toLowerCase().trim() || '';
                const status = row.cells[5]?.textContent.toLowerCase().trim() || '';
                
                let showRow = true;
                
                // Search filter (check title and message)
                if (searchTerm && !title.includes(searchTerm) && !message.includes(searchTerm)) {
                    showRow = false;
                }
                
                // Type filter
                if (typeFilter !== 'all' && !type.includes(typeFilter)) {
                    showRow = false;
                }
                
                // Status filter
                if (statusFilter !== 'all' && !status.includes(statusFilter)) {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }

        // Add event listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const searchBox = document.getElementById('searchBox');
            const typeFilter = document.getElementById('typeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const applyFilter = document.getElementById('applyFilter');
            
            // Real-time search as user types
            searchBox.addEventListener('input', filterNotifications);
            
            // Filter on dropdown change
            typeFilter.addEventListener('change', filterNotifications);
            statusFilter.addEventListener('change', filterNotifications);
            
            // Filter on button click
            applyFilter.addEventListener('click', filterNotifications);
            
            // Filter on Enter key in search box
            searchBox.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    filterNotifications();
                }
            });
        });

        // Clear all functionality
        document.getElementById('clearAll').addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all notifications?')) {
                document.querySelector('tbody').innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #666;">No notifications found</td></tr>';
                
                // Update stats
                document.querySelector('.stat-card:nth-child(1) .stat-value').textContent = '0';
                document.querySelector('.stat-card:nth-child(2) .stat-value').textContent = '0';
                document.querySelector('.stat-card:nth-child(3) .stat-value').textContent = '0';
                document.querySelector('.stat-card:nth-child(4) .stat-value').textContent = '0';
            }
        });

        // Dismiss individual notifications
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('dismiss-btn')) {
                if (confirm('Are you sure you want to dismiss this notification?')) {
                    const row = e.target.closest('tr');
                    row.remove();
                    
                    // Update total count
                    const totalElement = document.querySelector('.stat-card:nth-child(1) .stat-value');
                    const currentTotal = parseInt(totalElement.textContent);
                    totalElement.textContent = Math.max(0, currentTotal - 1);
                    
                    // Update unread count if needed
                    if (row.classList.contains('unread')) {
                        const unreadElement = document.querySelector('.stat-card:nth-child(2) .stat-value');
                        const currentUnread = parseInt(unreadElement.textContent);
                        unreadElement.textContent = Math.max(0, currentUnread - 1);
                    }
                }
            }
        });

        // Mark as read on view
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('view-btn')) {
                const row = e.target.closest('tr');
                if (row.classList.contains('unread')) {
                    row.classList.remove('unread');
                    const statusCell = row.cells[5];
                    statusCell.innerHTML = '<span class="status read">Read</span>';
                    
                    // Update unread count
                    const unreadElement = document.querySelector('.stat-card:nth-child(2) .stat-value');
                    const currentUnread = parseInt(unreadElement.textContent);
                    unreadElement.textContent = Math.max(0, currentUnread - 1);
                    
                    alert('Notification marked as read and will be moved to read section.');
                } else {
                    alert('Viewing notification details...');
                }
            }
        });
    </script>
</body>
</html>