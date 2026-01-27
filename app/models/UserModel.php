<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';

/**
 * UserModel - Model for admin user management operations
 */
class UserModel
{
    use Database;

    /**
     * Get all users with pagination and optional filtering
     */
    public function getAllUsers($page = 1, $perPage = 10, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        $perPage = (int)$perPage;
        $offset = (int)$offset;
        
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM accommodations WHERE user_id = u.id) as accommodation_count,
                (SELECT COUNT(*) FROM vehicles WHERE user_id = u.id) as vehicle_count,
                (SELECT COUNT(*) FROM blogs WHERE traveler_id = u.id) as blog_count
                FROM users u WHERE 1=1";
        $params = [];

        // Apply filters
        if (!empty($filters['role'])) {
            $sql .= " AND u.role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND u.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Use direct values for LIMIT and OFFSET to avoid PDO type issues
        $sql .= " ORDER BY u.created_at DESC LIMIT {$perPage} OFFSET {$offset}";

        return $this->query($sql, $params);
    }

    /**
     * Get total count of users with filters
     */
    public function getTotalUsers($filters = [])
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE 1=1";
        $params = [];

        if (!empty($filters['role'])) {
            $sql .= " AND role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $result = $this->getRow($sql, $params);
        return $result ? $result->total : 0;
    }

    /**
     * Get a single user by ID
     */
    public function getUserById($id)
    {
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM accommodations WHERE user_id = u.id) as accommodation_count,
                (SELECT COUNT(*) FROM vehicles WHERE user_id = u.id) as vehicle_count,
                (SELECT COUNT(*) FROM blogs WHERE traveler_id = u.id) as blog_count
                FROM users u WHERE u.id = ?";
        return $this->getRow($sql, [$id]);
    }

    /**
     * Suspend a user
     */
    public function suspendUser($id)
    {
        $sql = "UPDATE users SET status = 'suspended' WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    /**
     * Activate a user
     */
    public function activateUser($id)
    {
        $sql = "UPDATE users SET status = 'active' WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    /**
     * Delete a user and all their associated data
     */
    public function deleteUser($id)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            // Delete user's blogs
            $this->query("DELETE FROM blogs WHERE user_id = ?", [$id]);

            // Delete user's vehicles
            $this->query("DELETE FROM vehicles WHERE user_id = ?", [$id]);

            // Delete user's accommodations
            $this->query("DELETE FROM accommodations WHERE user_id = ?", [$id]);

            // Delete user's bookings
            $this->query("DELETE FROM bookings WHERE user_id = ?", [$id]);

            // Delete user preferences
            $this->query("DELETE FROM user_preferences WHERE user_id = ?", [$id]);

            // Finally delete the user
            $this->query("DELETE FROM users WHERE id = ?", [$id]);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user statistics for admin dashboard
     */
    public function getUserStats()
    {
        $stats = [];

        // Total users
        $result = $this->getRow("SELECT COUNT(*) as total FROM users");
        $stats['total'] = $result ? $result->total : 0;

        // Active users
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE status = 'active'");
        $stats['active'] = $result ? $result->total : 0;

        // Suspended users
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE status = 'suspended'");
        $stats['suspended'] = $result ? $result->total : 0;

        // Users by role
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE role = 'traveller'");
        $stats['travellers'] = $result ? $result->total : 0;

        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE role = 'provider'");
        $stats['providers'] = $result ? $result->total : 0;

        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE role = 'transporter'");
        $stats['transporters'] = $result ? $result->total : 0;

        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
        $stats['admins'] = $result ? $result->total : 0;

        // New users this month
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stats['new_this_month'] = $result ? $result->total : 0;

        return $stats;
    }

    /**
     * Get user's listings count (accommodations + vehicles)
     */
    public function getUserListingsCount($userId)
    {
        $sql = "SELECT 
                (SELECT COUNT(*) FROM accommodations WHERE user_id = ?) +
                (SELECT COUNT(*) FROM vehicles WHERE user_id = ?) as total";
        $result = $this->getRow($sql, [$userId, $userId]);
        return $result ? $result->total : 0;
    }

    /**
     * Update user role
     */
    public function updateUserRole($id, $role)
    {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        return $this->query($sql, [$role, $id]);
    }

    /**
     * Search users
     */
    public function searchUsers($term)
    {
        $sql = "SELECT * FROM users 
                WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?
                ORDER BY created_at DESC LIMIT 20";
        $searchTerm = '%' . $term . '%';
        return $this->query($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
}
