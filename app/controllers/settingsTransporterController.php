<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

use App\Models\User;

class SettingsController
{
    private $db;
    
    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    private function sendResponse($success, $data = [], $errors = [])
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'data' => $data,
            'errors' => $errors
        ]);
        exit;
    }
    
    private function checkAuth()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'transport') {
            $this->sendResponse(false, [], ['auth' => 'Unauthorized access']);
        }
        return $_SESSION['user']['id'];
    }

    /**
     * Check if transporter has any upcoming confirmed bookings
     */
    private function hasUpcomingConfirmedBookings($transporterId)
    {
        try {
            $sql = "SELECT COUNT(*) as booking_count 
                    FROM transport_bookings b
                    INNER JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE v.user_id = ? 
                    AND b.booking_status = 'confirmed'
                    AND b.pickup_date >= CURDATE()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$transporterId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result['booking_count'] > 0;
        } catch (\Exception $e) {
            error_log('Error checking upcoming bookings: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get count of upcoming confirmed bookings
     */
    private function getUpcomingBookingsCount($transporterId)
    {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM transport_bookings b
                    INNER JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE v.user_id = ? 
                    AND b.booking_status = 'confirmed'
                    AND b.pickup_date >= CURDATE()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$transporterId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return intval($result['count'] ?? 0);
        } catch (\Exception $e) {
            error_log('Error getting upcoming bookings count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update account status (active/deactivated)
     */
    public function updateAccountStatus()
    {
        try {
            $userId = $this->checkAuth();
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['status']) || !in_array($input['status'], ['active', 'deactivated'])) {
                $this->sendResponse(false, [], ['status' => 'Invalid status value']);
            }
            
            $newStatus = $input['status'];
            $reason = isset($input['reason']) ? trim($input['reason']) : '';
            $feedback = isset($input['feedback']) ? trim($input['feedback']) : '';
            
            // Get current status
            $sql = "SELECT account_status FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $currentStatus = $stmt->fetchColumn();
            
            if ($currentStatus === $newStatus) {
                $this->sendResponse(false, [], ['status' => 'Account is already ' . $newStatus]);
            }
            
            // If deactivating, check for upcoming bookings (for informational purposes only)
            $upcomingCount = 0;
            if ($newStatus === 'deactivated') {
                $upcomingCount = $this->getUpcomingBookingsCount($userId);
            }
            
            // Start transaction
            $this->db->beginTransaction();
            
            // Update user account status
            if ($newStatus === 'deactivated') {
                $updateSql = "UPDATE users SET 
                              account_status = ?,
                              account_deactivated_at = NOW(),
                              account_deactivation_reason = ?,
                              account_deactivation_feedback = ?
                              WHERE id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->execute([$newStatus, $reason, $feedback, $userId]);
            } else {
                $updateSql = "UPDATE users SET 
                              account_status = ?,
                              account_reactivated_at = NOW()
                              WHERE id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->execute([$newStatus, $userId]);
            }
            
            // Log status change
            $logSql = "INSERT INTO account_status_history 
                       (user_id, previous_status, new_status, reason, feedback, changed_at, ip_address, user_agent) 
                       VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
            $logStmt = $this->db->prepare($logSql);
            $logStmt->execute([
                $userId,
                $currentStatus,
                $newStatus,
                $reason,
                $feedback,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
            
            $this->db->commit();
            
            // CRITICAL: Update session with new status
            $_SESSION['user']['account_status'] = $newStatus;
            
            $message = $newStatus === 'active' 
                ? 'Account activated successfully. Your vehicles are now visible to travellers.'
                : 'Account deactivated successfully. Your vehicles are now hidden from travellers.';
            
            if ($newStatus === 'deactivated' && $upcomingCount > 0) {
                $message .= ' You have ' . $upcomingCount . ' upcoming booking(s) that must still be fulfilled.';
            }
            
            $this->sendResponse(true, [
                'status' => $newStatus,
                'upcoming_bookings' => $upcomingCount,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Error in updateAccountStatus: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Server error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get current account status with details
     */
    public function getAccountStatus()
    {
        try {
            $userId = $this->checkAuth();
            
            // CRITICAL: Get account status directly - be very explicit
            $sql = "SELECT 
                        u.id,
                        u.account_status,
                        u.account_deactivated_at, 
                        u.account_deactivation_reason,
                        u.account_deactivation_feedback, 
                        u.account_reactivated_at,
                        (SELECT COUNT(*) FROM vehicles WHERE user_id = u.id AND status = 'active') as active_vehicles,
                        (SELECT COUNT(*) FROM vehicles WHERE user_id = u.id) as total_vehicles
                    FROM users u
                    WHERE u.id = ?";
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute([$userId])) {
                error_log("Query execution failed for user $userId");
                $this->sendResponse(false, [], ['error' => 'Database query failed']);
                return;
            }
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                error_log("User $userId not found in database");
                $this->sendResponse(false, [], ['error' => 'User not found']);
                return;
            }
            
            // Get upcoming bookings count
            $upcomingCount = $this->getUpcomingBookingsCount($userId);
            
            // CRITICAL: Extract account_status with explicit handling
            $dbStatus = $result['account_status'] ?? null;
            error_log("[getAccountStatus] Raw DB value for user $userId: " . var_export($dbStatus, true) . " (type: " . gettype($dbStatus) . ")");
            
            // Process status value
            $status = 'active'; // Default
            
            if ($dbStatus === 'deactivated') {
                // Explicitly deactivated
                $status = 'deactivated';
                error_log("[getAccountStatus] User $userId is DEACTIVATED");
            } elseif ($dbStatus === 'active') {
                // Explicitly active
                $status = 'active';
                error_log("[getAccountStatus] User $userId is ACTIVE");
            } elseif (empty($dbStatus) || $dbStatus === null) {
                // NULL or empty - default to active
                $status = 'active';
                error_log("[getAccountStatus] User $userId has NULL/empty status, defaulting to ACTIVE");
            } else {
                // Unknown value - still default to active but log it
                error_log("[getAccountStatus] User $userId has UNKNOWN status value: [" . $dbStatus . "], defaulting to ACTIVE");
                $status = 'active';
            }
            
            error_log("[getAccountStatus] Final status for user $userId: $status");
            
            $data = [
                'status' => $status,
                'deactivated_at' => $result['account_deactivated_at'],
                'deactivation_reason' => $result['account_deactivation_reason'],
                'deactivation_feedback' => $result['account_deactivation_feedback'],
                'reactivated_at' => $result['account_reactivated_at'],
                'active_vehicles' => intval($result['active_vehicles'] ?? 0),
                'total_vehicles' => intval($result['total_vehicles'] ?? 0),
                'upcoming_bookings' => $upcomingCount
            ];
            
            // Update session with latest status to keep it in sync
            $_SESSION['user']['account_status'] = $status;
            
            $this->sendResponse(true, $data);
            
        } catch (\Exception $e) {
            error_log('Error in getAccountStatus: ' . $e->getMessage());
            $this->sendResponse(false, [], ['general' => 'Failed to get account status']);
        }
    }
}