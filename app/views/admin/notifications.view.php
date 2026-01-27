<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Management</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/notifications.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../traveller/header.view.php'; ?>

    <div class="page-container">
    <?php include 'sidebar.view.php'; ?>

    <div class="content">
        <div class="page-title">
            <h1>Notifications</h1>
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
                    <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
                </div>
            </div>
            <div class="stat-card unread">
                <div class="stat-icon">🔔</div>
                <div class="stat-info">
                    <div class="stat-title">Unread</div>
                    <div class="stat-value"><?php echo $stats['unread'] ?? 0; ?></div>
                </div>
            </div>
            <div class="stat-card today">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <div class="stat-title">Today</div>
                    <div class="stat-value"><?php echo $stats['today'] ?? 0; ?></div>
                </div>
            </div>
            <div class="stat-card urgent">
                <div class="stat-icon">⚠️</div>
                <div class="stat-info">
                    <div class="stat-title">Urgent</div>
                    <div class="stat-value"><?php echo $stats['urgent'] ?? 0; ?></div>
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
                    <?php if (isset($notifications) && is_array($notifications) && count($notifications) > 0): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <?php 
                                $rowClass = $notification->status === 'unread' ? 'notification-row unread' : 'notification-row';
                                $typeClass = strtolower($notification->type) . '-type';
                            ?>
                            <tr class="<?php echo $rowClass; ?>" data-id="<?php echo $notification->id; ?>">
                                <td>N<?php echo str_pad($notification->id, 3, '0', STR_PAD_LEFT); ?></td>
                                <td><span class="notification-type <?php echo $typeClass; ?>"><?php echo ucfirst($notification->type); ?></span></td>
                                <td><?php echo htmlspecialchars($notification->title); ?></td>
                                <td><?php echo htmlspecialchars(substr($notification->message ?? '', 0, 80)); ?>...</td>
                                <td><?php echo date('M d, Y H:i', strtotime($notification->created_at)); ?></td>
                                <td><span class="status <?php echo $notification->status; ?>"><?php echo ucfirst($notification->status); ?></span></td>
                                <td class="actions">
                                    <?php if ($notification->status === 'unread'): ?>
                                        <button class="view-btn" onclick="markAsRead(<?php echo $notification->id; ?>)">Mark Read</button>
                                    <?php endif; ?>
                                    <button class="dismiss-btn" onclick="deleteNotification(<?php echo $notification->id; ?>)">Dismiss</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <p>No notifications found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
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

        // Mark notification as read
        function markAsRead(id) {
            fetch('/api/admin/notification/mark-read', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id: id})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Delete notification
        function deleteNotification(id) {
            if (confirm('Are you sure you want to dismiss this notification?')) {
                fetch('/api/admin/notification/delete', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
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
                fetch('/api/admin/notification/clear-all', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'}
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    </script>
</body>
</html>
