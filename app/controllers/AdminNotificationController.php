<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';

/**
 * AdminNotificationController - Controller for admin notification management
 */
class AdminNotificationController
{
    use Database;

    /**
     * Display the notifications page
     */
    public function index()
    {
        // Get filter parameters
        $type = $_GET['type'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get notifications data
        $notifications = $this->getAllNotifications($type, $status);
        $stats = $this->getNotificationStats();

        // Pass data to view
        require_once __DIR__ . '/../views/admin/notifications.view.php';
    }

    /**
     * Get all notifications with optional filtering
     */
    private function getAllNotifications($type = '', $status = '')
    {
        $sql = "SELECT n.*, u.first_name, u.last_name, u.email 
                FROM notifications n
                LEFT JOIN users u ON n.user_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($type)) {
            $sql .= " AND n.type = ?";
            $params[] = $type;
        }

        if (!empty($status)) {
            $sql .= " AND n.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY n.created_at DESC LIMIT 100";

        return $this->query($sql, $params);
    }

    /**
     * Get notification statistics
     */
    private function getNotificationStats()
    {
        $stats = [];

        // Total notifications
        $result = $this->getRow("SELECT COUNT(*) as total FROM notifications");
        $stats['total'] = $result ? $result->total : 0;

        // Unread notifications
        $result = $this->getRow("SELECT COUNT(*) as total FROM notifications WHERE status = 'unread'");
        $stats['unread'] = $result ? $result->total : 0;

        // Today's notifications
        $result = $this->getRow("SELECT COUNT(*) as total FROM notifications WHERE DATE(created_at) = CURDATE()");
        $stats['today'] = $result ? $result->total : 0;

        // Urgent notifications
        $result = $this->getRow("SELECT COUNT(*) as total FROM notifications WHERE priority = 'urgent' AND status = 'unread'");
        $stats['urgent'] = $result ? $result->total : 0;

        return $stats;
    }

    /**
     * Mark notification as read (API endpoint)
     */
    public function markRead()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'Notification ID is required']);
            return;
        }

        $sql = "UPDATE notifications SET status = 'read' WHERE id = ?";
        $result = $this->query($sql, [$id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Notification marked as read']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to mark notification as read']);
        }
    }

    /**
     * Mark all notifications as read (API endpoint)
     */
    public function markAllRead()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $sql = "UPDATE notifications SET status = 'read' WHERE status = 'unread'";
        $result = $this->query($sql);

        if ($result !== false) {
            echo json_encode(['success' => true, 'message' => 'All notifications marked as read']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to mark notifications as read']);
        }
    }

    /**
     * Delete notification (API endpoint)
     */
    public function delete()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'Notification ID is required']);
            return;
        }

        $sql = "DELETE FROM notifications WHERE id = ?";
        $result = $this->query($sql, [$id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Notification deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete notification']);
        }
    }

    /**
     * Clear all notifications (API endpoint)
     */
    public function clearAll()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $sql = "DELETE FROM notifications";
        $result = $this->query($sql);

        if ($result !== false) {
            echo json_encode(['success' => true, 'message' => 'All notifications cleared']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to clear notifications']);
        }
    }

    /**
     * Create a notification (for internal use)
     */
    public function createNotification($type, $title, $message, $userId = null, $priority = 'normal')
    {
        $sql = "INSERT INTO notifications (type, title, message, user_id, priority) VALUES (?, ?, ?, ?, ?)";
        return $this->query($sql, [$type, $title, $message, $userId, $priority]);
    }
}
