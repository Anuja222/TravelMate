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
                FROM users u WHERE u.role != 'admin'";
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
            $searchTerm = '%' . substr(trim($filters['search']), 0, 100) . '%';
            $sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ? OR CONCAT(u.first_name, ' ', u.last_name) LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Use direct values for LIMIT and OFFSET to avoid PDO type issues
        $sql .= " ORDER BY u.created_at DESC LIMIT {$perPage} OFFSET {$offset}";

        $result = $this->query($sql, $params);
        return is_array($result) ? $result : [];
    }

    /**
     * Get total count of users with filters
     */
    public function getTotalUsers($filters = [])
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role != 'admin'";
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
            $searchTerm = '%' . substr(trim($filters['search']), 0, 100) . '%';
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ? OR CONCAT(first_name, ' ', last_name) LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
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
     * Suspend a user with reason
     */
    public function suspendUser($id, $reason = '')
    {
        try {
            // Prevent suspending admin accounts
            $user = $this->getRow("SELECT role FROM users WHERE id = ?", [$id]);
            if ($user && $user->role === 'admin') {
                return false;
            }

            // Update user status
            $sql = "UPDATE users SET status = 'suspended' WHERE id = ? AND role != 'admin'";
            $this->query($sql, [$id]);

            // Record suspension reason if provided
            if (!empty($reason)) {
                $logSql = "INSERT INTO user_suspension_logs (user_id, reason, suspended_at) VALUES (?, ?, NOW())";
                $this->query($logSql, [$id, $reason]);
            }

            return true;
        } catch (Exception $e) {
            error_log("Error suspending user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Activate a user
     */
    public function activateUser($id)
    {
        try {
            $sql = "UPDATE users SET status = 'active' WHERE id = ?";
            $this->query($sql, [$id]);

            // Update suspension log to mark as reactivated
            $logSql = "UPDATE user_suspension_logs SET reactivated_at = NOW() WHERE user_id = ? AND reactivated_at IS NULL ORDER BY suspended_at DESC LIMIT 1";
            $this->query($logSql, [$id]);

            return true;
        } catch (Exception $e) {
            error_log("Error activating user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a user and all their associated data
     */
    public function deleteUser($id)
    {
        try {
            // Prevent deleting admin accounts
            $user = $this->getRow("SELECT role FROM users WHERE id = ?", [$id]);
            if ($user && $user->role === 'admin') {
                error_log("Cannot delete admin user ID: " . $id);
                return false;
            }

            $conn = $this->connect();
            $conn->beginTransaction();

            // Helper to safely delete using the SAME connection (for transaction atomicity)
            $safeDelete = function($sql, $params) use ($conn) {
                try {
                    $stm = $conn->prepare($sql);
                    $stm->execute($params);
                } catch (\PDOException $e) {
                    // Ignore errors for tables that may not exist (e.g., 42S02)
                    if ($e->getCode() !== '42S02') {
                        error_log("Safe delete warning: " . $e->getMessage());
                    }
                }
            };

            // Helper to query using the SAME connection
            $safeQuery = function($sql, $params) use ($conn) {
                try {
                    $stm = $conn->prepare($sql);
                    $stm->execute($params);
                    return $stm->fetchAll(PDO::FETCH_OBJ);
                } catch (\PDOException $e) {
                    error_log("Safe query warning: " . $e->getMessage());
                    return [];
                }
            };

            // Delete blog-related data first (due to foreign key constraints)
            $safeDelete("DELETE FROM blog_likes WHERE user_id = ?", [$id]);
            $safeDelete("DELETE FROM blog_comments WHERE user_id = ?", [$id]);
            $safeDelete("DELETE FROM blog_notifications WHERE user_id = ?", [$id]);
            
            // Get blog IDs owned by this user to delete related data
            $blogs = $safeQuery("SELECT id FROM blogs WHERE traveler_id = ?", [$id]);
            if (is_array($blogs) && count($blogs) > 0) {
                foreach ($blogs as $blog) {
                    $safeDelete("DELETE FROM blog_images WHERE blog_id = ?", [$blog->id]);
                    $safeDelete("DELETE FROM blog_category_pivot WHERE blog_id = ?", [$blog->id]);
                    $safeDelete("DELETE FROM blog_likes WHERE blog_id = ?", [$blog->id]);
                    $safeDelete("DELETE FROM blog_comments WHERE blog_id = ?", [$blog->id]);
                }
            }
            
            // Delete user's blogs
            $safeDelete("DELETE FROM blogs WHERE traveler_id = ?", [$id]);
            
            // Update blogs approved_by to NULL instead of deleting
            $safeDelete("UPDATE blogs SET approved_by = NULL WHERE approved_by = ?", [$id]);

            // Get accommodation IDs to delete related images
            $accommodations = $safeQuery("SELECT id FROM accommodations WHERE user_id = ?", [$id]);
            if (is_array($accommodations) && count($accommodations) > 0) {
                foreach ($accommodations as $acc) {
                    $safeDelete("DELETE FROM accommodation_images WHERE accommodation_id = ?", [$acc->id]);
                }
            }
            
            // Delete user's accommodations
            $safeDelete("DELETE FROM accommodations WHERE user_id = ?", [$id]);

            // Get vehicle IDs to delete related data
            $vehicles = $safeQuery("SELECT id FROM vehicles WHERE user_id = ?", [$id]);
            if (is_array($vehicles) && count($vehicles) > 0) {
                foreach ($vehicles as $vehicle) {
                    $safeDelete("DELETE FROM vehicle_images WHERE vehicle_id = ?", [$vehicle->id]);
                    $safeDelete("DELETE FROM vehicle_documents WHERE vehicle_id = ?", [$vehicle->id]);
                }
            }
            
            // Delete user's vehicles
            $safeDelete("DELETE FROM vehicles WHERE user_id = ?", [$id]);

            // Delete user's bookings
            $safeDelete("DELETE FROM bookings WHERE user_id = ?", [$id]);
            
            // Delete transport bookings
            $safeDelete("DELETE FROM transport_bookings WHERE user_id = ?", [$id]);
            
            // Delete user's reviews
            $safeDelete("DELETE FROM reviews WHERE user_id = ?", [$id]);
            
            // Delete notifications
            $safeDelete("DELETE FROM notifications WHERE user_id = ?", [$id]);

            // Delete user preferences
            $safeDelete("DELETE FROM user_preferences WHERE user_id = ?", [$id]);
            
            // Delete user activities
            $safeDelete("DELETE FROM user_activities WHERE user_id = ?", [$id]);
            
            // Delete user environments
            $safeDelete("DELETE FROM user_environments WHERE user_id = ?", [$id]);
            
            // Delete suspension logs
            $safeDelete("DELETE FROM user_suspension_logs WHERE user_id = ?", [$id]);

            // Finally delete the user (using same connection)
            $stm = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
            $stm->execute([$id]);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
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

        // Total users (excluding admins)
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE role != 'admin'");
        $stats['total'] = $result ? $result->total : 0;

        // Active users (excluding admins)
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE status = 'active' AND role != 'admin'");
        $stats['active'] = $result ? $result->total : 0;

        // Suspended users (excluding admins)
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE status = 'suspended' AND role != 'admin'");
        $stats['suspended'] = $result ? $result->total : 0;

        // Users by role
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE role = 'traveller'");
        $stats['travellers'] = $result ? $result->total : 0;

        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE role = 'accommodation'");
        $stats['accommodation'] = $result ? $result->total : 0;

        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE role = 'transport'");
        $stats['transport'] = $result ? $result->total : 0;

        // New users this month (excluding admins)
        $result = $this->getRow("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND role != 'admin'");
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
                WHERE role != 'admin' AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)
                ORDER BY created_at DESC LIMIT 20";
        $searchTerm = '%' . substr(trim($term), 0, 100) . '%';
        $result = $this->query($sql, [$searchTerm, $searchTerm, $searchTerm]);
        return is_array($result) ? $result : [];
    }

    /**
     * Get suspension history for a user
     */
    public function getSuspensionHistory($userId)
    {
        $sql = "SELECT * FROM user_suspension_logs 
                WHERE user_id = ? 
                ORDER BY suspended_at DESC";
        $result = $this->query($sql, [$userId]);
        return is_array($result) ? $result : [];
    }
}
