<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';

/**
 * BlogNotificationModel - Handles blog-related notifications for travelers
 */
class BlogNotificationModel {
    use Database;
    
    protected $table = 'blog_notifications';

    /**
     * Create a notification for a traveler
     */
    public function createNotification($blogId, $travelerId, $type, $message) {
        $query = "INSERT INTO {$this->table} 
                 (blog_id, user_id, type, message, is_read, created_at) 
                 VALUES (:blog_id, :user_id, :type, :message, 0, NOW())";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'blog_id' => $blogId,
            'user_id' => $travelerId,
            'type' => $type,
            'message' => substr($message, 0, 255)
        ]);
    }

    /**
     * Get all notifications for a traveler
     */
    public function getTravelerNotifications($travelerId, $unreadOnly = false) {
        $query = "SELECT n.*, b.title as blog_title, b.slug as blog_slug
                 FROM {$this->table} n
                 LEFT JOIN blogs b ON n.blog_id = b.id
                 WHERE n.user_id = :user_id";
        
        $params = ['user_id' => $travelerId];
        
        if ($unreadOnly) {
            $query .= " AND n.is_read = 0";
        }
        
        $query .= " ORDER BY n.created_at DESC";
        
        return $this->query($query, $params);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id) {
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Mark all notifications as read for a traveler
     */
    public function markAllAsRead($travelerId) {
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = :user_id";
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['user_id' => $travelerId]);
    }

    /**
     * Get unread count for a traveler
     */
    public function getUnreadCount($travelerId) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} 
                 WHERE user_id = :user_id AND is_read = 0";
        $result = $this->getRow($query, ['user_id' => $travelerId]);
        return $result ? $result->count : 0;
    }

    /**
     * Delete a notification
     */
    public function deleteNotification($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Delete all notifications for a traveler
     */
    public function clearAllNotifications($travelerId) {
        $query = "DELETE FROM {$this->table} WHERE user_id = :user_id";
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['user_id' => $travelerId]);
    }
}
