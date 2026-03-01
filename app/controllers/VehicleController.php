<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/TransportBooking.php';
require_once __DIR__ . '/../../config/database.php';

use App\Models\Vehicle;
use App\Models\TransportBooking;

class VehicleController
{
    private $uploadDir;
    private $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
        
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $this->uploadDir = __DIR__ . '/../../public/uploads/vehicles';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    private function sendResponse($success, $errors = [], $data = null)
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'errors' => $errors, 'data' => $data]);
        exit;
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'transport') {
            $this->sendResponse(false, ['auth' => 'Unauthorized access']);
        }
        return $_SESSION['user']['id'];
    }

    private function checkTravellerAuth()
    {
        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['auth' => 'Please login to continue']);
        }
        return $_SESSION['user']['id'];
    }

    /**
     * Get upcoming confirmed bookings for a vehicle
     */
    private function getUpcomingConfirmedBookings($vehicleId)
    {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM transport_bookings 
                    WHERE vehicle_id = ? 
                    AND booking_status = 'confirmed'
                    AND pickup_date >= CURDATE()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$vehicleId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return intval($result['count'] ?? 0);
        } catch (\Exception $e) {
            error_log('Error getting upcoming bookings: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get upcoming bookings details for a vehicle
     */
    private function getUpcomingBookingsDetails($vehicleId)
    {
        try {
            $sql = "SELECT b.*, u.first_name, u.last_name, u.email, u.phone
                    FROM transport_bookings b
                    INNER JOIN users u ON b.user_id = u.id
                    WHERE b.vehicle_id = ? 
                    AND b.booking_status = 'confirmed'
                    AND b.pickup_date >= CURDATE()
                    ORDER BY b.pickup_date ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$vehicleId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log('Error getting upcoming bookings details: ' . $e->getMessage());
            return [];
        }
    }

    // ============================================
    // VEHICLE DEACTIVATION METHODS
    // ============================================

    /**
     * Deactivate vehicle
     */
    public function deactivateVehicle()
    {
        try {
            $userId = $this->checkAuth();
            
            $vehicleId = $_POST['vehicle_id'] ?? null;
            $reason = isset($_POST['deactivation_reason']) ? trim($_POST['deactivation_reason']) : '';
            $feedback = isset($_POST['deactivation_feedback']) ? trim($_POST['deactivation_feedback']) : '';
            
            if (!$vehicleId) {
                $this->sendResponse(false, ['error' => 'Vehicle ID required']);
            }

            // Verify vehicle ownership and get current status
            $vehicle = Vehicle::findById($this->db, $vehicleId, $userId);
            if (!$vehicle) {
                $this->sendResponse(false, ['error' => 'Vehicle not found or unauthorized']);
            }

            if ($vehicle['status'] === 'deactivated') {
                $this->sendResponse(false, ['error' => 'Vehicle is already deactivated']);
            }

            // Get upcoming confirmed bookings count (for informational purposes)
            $upcomingCount = $this->getUpcomingConfirmedBookings($vehicleId);
            $upcomingBookings = $this->getUpcomingBookingsDetails($vehicleId);

            // Start transaction
            $this->db->beginTransaction();

            // Update vehicle status to deactivated
            $updateSql = "UPDATE vehicles SET 
                          status = 'deactivated',
                          deactivated_at = NOW(),
                          deactivation_reason = ?,
                          deactivation_feedback = ?
                          WHERE id = ? AND user_id = ?";
            
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->execute([$reason, $feedback, $vehicleId, $userId]);

            // Log deactivation in history
            $logSql = "INSERT INTO vehicle_deactivation_history 
                       (vehicle_id, previous_status, new_status, reason, feedback, 
                        has_upcoming_bookings, changed_by, changed_at, ip_address, user_agent) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
            
            $logStmt = $this->db->prepare($logSql);
            $logStmt->execute([
                $vehicleId,
                $vehicle['status'],
                'deactivated',
                $reason,
                $feedback,
                $upcomingCount > 0 ? 1 : 0,
                $userId,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);

            $this->db->commit();

            // Build response message
            $message = 'Vehicle deactivated successfully. It will no longer appear in traveller searches.';
            if ($upcomingCount > 0) {
                $message .= ' Note: This vehicle has ' . $upcomingCount . ' upcoming booking(s) that must still be fulfilled.';
            }

            $this->sendResponse(true, [
                'message' => $message,
                'upcoming_bookings' => $upcomingCount,
                'upcoming_bookings_details' => $upcomingBookings
            ]);

        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Vehicle deactivation error: ' . $e->getMessage());
            $this->sendResponse(false, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Reactivate vehicle
     */
    public function reactivateVehicle()
    {
        try {
            $userId = $this->checkAuth();
            
            $vehicleId = $_POST['vehicle_id'] ?? null;
            
            if (!$vehicleId) {
                $this->sendResponse(false, ['error' => 'Vehicle ID required']);
            }

            // Verify vehicle ownership
            $vehicle = Vehicle::findById($this->db, $vehicleId, $userId);
            if (!$vehicle) {
                $this->sendResponse(false, ['error' => 'Vehicle not found or unauthorized']);
            }

            if ($vehicle['status'] !== 'deactivated') {
                $this->sendResponse(false, ['error' => 'Vehicle is not deactivated']);
            }

            // Update vehicle status
            $updateSql = "UPDATE vehicles SET 
                          status = 'active',
                          deactivated_at = NULL,
                          deactivation_reason = NULL,
                          deactivation_feedback = NULL
                          WHERE id = ? AND user_id = ?";
            
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->execute([$vehicleId, $userId]);

            $this->sendResponse(true, [
                'message' => 'Vehicle reactivated successfully. It will now appear in traveller searches.'
            ]);

        } catch (\Exception $e) {
            error_log('Vehicle reactivation error: ' . $e->getMessage());
            $this->sendResponse(false, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get vehicle deactivation status
     */
    public function getVehicleDeactivationStatus()
    {
        try {
            $userId = $this->checkAuth();
            
            $vehicleId = $_GET['vehicle_id'] ?? null;
            
            if (!$vehicleId) {
                $this->sendResponse(false, ['error' => 'Vehicle ID required']);
            }

            $vehicle = Vehicle::findById($this->db, $vehicleId, $userId);
            if (!$vehicle) {
                $this->sendResponse(false, ['error' => 'Vehicle not found']);
            }

            $upcomingCount = $this->getUpcomingConfirmedBookings($vehicleId);
            $upcomingBookings = $this->getUpcomingBookingsDetails($vehicleId);

            $this->sendResponse(true, [
                'vehicle' => $vehicle,
                'upcoming_bookings' => $upcomingCount,
                'upcoming_bookings_details' => $upcomingBookings,
                'is_deactivated' => $vehicle['status'] === 'deactivated'
            ]);

        } catch (\Exception $e) {
            error_log('Error getting deactivation status: ' . $e->getMessage());
            $this->sendResponse(false, ['error' => $e->getMessage()]);
        }
    }

    // ============================================
    // VEHICLE LISTING METHODS
    // ============================================

    /**
     * Create vehicle - FIXED with fresh account status check
     */
    public function create()
    {
        global $pdo;

        error_log("=== Vehicle Create Called ===");
        error_log("POST Data: " . print_r($_POST, true));
        error_log("FILES Data: " . print_r($_FILES, true));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['error' => 'Unauthorized - Please login']);
        }

        $userId = $_SESSION['user']['id'];
        
        // CRITICAL FIX: Always check fresh account status from database
        $userCheck = $this->db->prepare("SELECT id, account_status FROM users WHERE id = ?");
        $userCheck->execute([$userId]);
        $user = $userCheck->fetch(\PDO::FETCH_ASSOC);
        
        if (!$user) {
            $this->sendResponse(false, ['error' => 'User not found']);
        }
        
        // Get account status (deactivated users CAN still register vehicles, just won't be visible)
        $accountStatus = $user['account_status'] ?? 'active';
        if (empty($accountStatus)) {
            $accountStatus = 'active';
        }

        // Also update session with latest status
        $_SESSION['user']['account_status'] = $accountStatus;

        error_log("User ID: " . $userId);
        error_log("Account Status: " . $accountStatus);

        $vehicleType = $_POST['vehicle_type'] ?? '';
        $workingDistrict = $_POST['working_district'] ?? '';
        $passengerCount = $_POST['passenger_count'] ?? 2;
        $acType = $_POST['ac_type'] ?? $_POST['ac-type'] ?? 'non-ac';
        $model = $_POST['vehicle_model'] ?? '';
        $year = $_POST['vehicle_year'] ?? '';
        $color = $_POST['vehicle_color'] ?? '';
        $number = $_POST['vehicle_number'] ?? '';
        $status = $_POST['status'] ?? 'active';

        error_log("Parsed Data - Type: $vehicleType, District: $workingDistrict, Model: $model");

        // Validate required fields
        if (empty($vehicleType)) {
            $this->sendResponse(false, ['error' => 'Vehicle type is required']);
        }

        $vehicle = new Vehicle([
            'userId' => $userId,
            'vehicleType' => $vehicleType,
            'workingDistrict' => $workingDistrict,
            'passengerCount' => $passengerCount,
            'acType' => $acType,
            'model' => $model,
            'year' => $year,
            'color' => $color,
            'number' => $number,
            'status' => $status
        ]);

        try {
            if (!$pdo) {
                throw new \Exception('Database connection failed');
            }

            $pdo->beginTransaction();
            error_log("Transaction started");

            $vehicleId = $vehicle->create($pdo);
            error_log("Vehicle created with ID: " . $vehicleId);

            if (!$vehicleId) { 
                throw new \Exception('Failed to create vehicle - no ID returned');
            }

            // Handle file uploads
            $fileFields = [
                'revenue_license',
                'insurance',
                'registration',
                'vehicle_photos',
                'profile_photo',
                'license_front',
                'license_rear',
                'nic_front',
                'nic_rear',
                'owner_nic_front',
                'owner_nic_rear',
                'vehicle_photo'
            ];

            $uploadedFiles = 0;
            foreach ($fileFields as $field) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                    if (is_array($_FILES[$field]['name'])) {
                        // Multiple files
                        foreach ($_FILES[$field]['tmp_name'] as $idx => $tmp) {
                            if ($_FILES[$field]['error'][$idx] === UPLOAD_ERR_OK) {
                                $orig = $_FILES[$field]['name'][$idx];
                                $path = $this->saveFile($tmp, $orig);
                                if ($path) {
                                    Vehicle::addDocument($pdo, $vehicleId, $field, $path);
                                    $uploadedFiles++;
                                    error_log("Uploaded file: $field - $path");
                                }
                            }
                        }
                    } else {
                        // Single file
                        if ($_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                            $path = $this->saveFile($_FILES[$field]['tmp_name'], $_FILES[$field]['name']);
                            if ($path) {
                                Vehicle::addDocument($pdo, $vehicleId, $field, $path);
                                $uploadedFiles++;
                                error_log("Uploaded file: $field - $path");
                            }
                        }
                    }
                }
            }

            error_log("Total files uploaded: $uploadedFiles");

            $pdo->commit();
            error_log("Transaction committed successfully");

            $this->sendResponse(true, [], [
                'vehicleId' => $vehicleId,
                'message' => 'Vehicle created successfully',
                'filesUploaded' => $uploadedFiles
            ]);

        } catch (\PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("PDO Error: " . $e->getMessage());
            error_log("SQL Error Info: " . print_r($pdo->errorInfo(), true));
            $this->sendResponse(false, ['error' => 'Database error: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("General Error: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to save vehicle: ' . $e->getMessage()]);
        }
    }

    /**
     * List vehicles for the logged-in transporter - FIXED with fresh account status
     */
    public function listByUser()
    {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['error' => 'Unauthorized']);
        }

        try {
            $userId = $_SESSION['user']['id'];
            
            // CRITICAL FIX: Get fresh account status from database
            $userCheck = $pdo->prepare("SELECT account_status FROM users WHERE id = ?");
            $userCheck->execute([$userId]);
            $user = $userCheck->fetch(\PDO::FETCH_ASSOC);
            
            // Ensure account_status is never NULL or empty - default to 'active'
            $accountStatus = $user['account_status'] ?? 'active';
            if (empty($accountStatus)) {
                $accountStatus = 'active';
            }
            $isDeactivated = ($accountStatus === 'deactivated');
            
            // Update session with latest status
            $_SESSION['user']['account_status'] = $accountStatus;
            
            $vehicles = Vehicle::findByUser($pdo, $userId);

            // Add main image to each vehicle
            foreach ($vehicles as &$vehicle) {
                $mainImage = self::getVehicleMainImage($pdo, $vehicle['id']);
                $vehicle['main_image'] = $mainImage;

                // Get all documents for this vehicle
                $docs = Vehicle::getDocuments($pdo, $vehicle['id']);
                $vehicle['documents'] = $docs;
                
                // Get upcoming bookings count
                $vehicle['upcoming_bookings'] = $this->getUpcomingConfirmedBookings($vehicle['id']);
            }

            $this->sendResponse(true, [], [
                'vehicles' => $vehicles,
                'account_deactivated' => $isDeactivated,
                'account_status' => $accountStatus,
                'message' => $isDeactivated ? 'Your account is deactivated. Vehicles are hidden from travellers.' : null
            ]);

        } catch (\Exception $e) {
            error_log("Error listing vehicles: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicles']);
        }
    }

    /**
     * List all active vehicles for travellers (filtered by vehicle status AND user account status)
     */
    public function listAllForTravellers()
    {
        global $pdo;

        error_log("=== listAllForTravellers() called ===");

        try {
            // Modified query to only show vehicles from active accounts
            $sql = "SELECT v.*, 
                           u.first_name, 
                           u.last_name, 
                           u.email,
                           u.phone,
                           u.profile_image,
                           u.account_status,
                           u.created_at as transporter_since
                    FROM vehicles v
                    INNER JOIN users u ON v.user_id = u.id
                    WHERE v.status = 'active' 
                    AND u.account_status = 'active'
                    ORDER BY v.created_at DESC";
            
            error_log("SQL Query: " . $sql);
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $vehicles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            error_log("Found " . count($vehicles) . " vehicles");

            // Add main image to each vehicle
            foreach ($vehicles as &$vehicle) {
                $mainImage = self::getVehicleMainImage($pdo, $vehicle['id']);
                $vehicle['main_image'] = $mainImage;
                error_log("Vehicle ID: " . $vehicle['id'] . ", Main Image: " . ($mainImage ?? 'none'));

                // Get all documents for this vehicle (but don't send sensitive docs)
                $docs = Vehicle::getDocuments($pdo, $vehicle['id']);
                // Filter to only show photos, not license/nic docs
                $vehicle['photos'] = array_filter($docs, function($doc) {
                    return strpos($doc['doc_type'], 'photo') !== false || $doc['doc_type'] === 'vehicle_photos';
                });
                $vehicle['photos'] = array_values($vehicle['photos']); // Re-index
            }

            $this->sendResponse(true, [], $vehicles);
        } catch (\Exception $e) {
            error_log("Error listing all vehicles: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicles']);
        }
    }

    /**
     * Search vehicles for travellers with filters
     */
    public function searchVehicles()
    {
        global $pdo;
        
        try {
            $searchTerm = $_GET['search'] ?? '';
            $vehicleType = $_GET['type'] ?? '';
            $district = $_GET['district'] ?? '';
            $minPassengers = $_GET['min_passengers'] ?? 0;
            $acType = $_GET['ac_type'] ?? '';
            
            $sql = "SELECT v.*, 
                           u.first_name, 
                           u.last_name, 
                           u.email,
                           u.phone,
                           u.profile_image,
                           u.account_status
                    FROM vehicles v
                    INNER JOIN users u ON v.user_id = u.id
                    WHERE v.status = 'active' 
                    AND u.account_status = 'active'";
            
            $params = [];
            
            if (!empty($searchTerm)) {
                $sql .= " AND (v.vehicle_model LIKE ? OR v.vehicle_number LIKE ? OR v.working_district LIKE ?)";
                $params[] = "%$searchTerm%";
                $params[] = "%$searchTerm%";
                $params[] = "%$searchTerm%";
            }
            
            if (!empty($vehicleType)) {
                $sql .= " AND v.vehicle_type = ?";
                $params[] = $vehicleType;
            }
            
            if (!empty($district)) {
                $sql .= " AND v.working_district = ?";
                $params[] = $district;
            }
            
            if (!empty($minPassengers) && $minPassengers > 0) {
                $sql .= " AND v.passenger_count >= ?";
                $params[] = $minPassengers;
            }
            
            if (!empty($acType)) {
                $sql .= " AND v.ac_type = ?";
                $params[] = $acType;
            }
            
            $sql .= " ORDER BY v.created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $vehicles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Add images
            foreach ($vehicles as &$vehicle) {
                $mainImage = self::getVehicleMainImage($pdo, $vehicle['id']);
                $vehicle['main_image'] = $mainImage;
            }
            
            $this->sendResponse(true, [], $vehicles);
            
        } catch (\Exception $e) {
            error_log("Error searching vehicles: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to search vehicles']);
        }
    }

    /**
     * Get single vehicle for traveller view
     */
    public function getVehicleForTraveller($vehicleId)
    {
        global $pdo;
        
        try {
            $sql = "SELECT v.*, 
                           u.first_name, 
                           u.last_name, 
                           u.email,
                           u.phone,
                           u.profile_image,
                           u.account_status,
                           u.created_at as member_since
                    FROM vehicles v
                    INNER JOIN users u ON v.user_id = u.id
                    WHERE v.id = ? 
                    AND v.status = 'active'
                    AND u.account_status = 'active'";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$vehicleId]);
            $vehicle = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$vehicle) {
                $this->sendResponse(false, ['error' => 'Vehicle not found or unavailable']);
            }
            
            // Get vehicle images (only photos)
            $docs = Vehicle::getDocuments($pdo, $vehicleId);
            $vehicle['photos'] = array_filter($docs, function($doc) {
                return strpos($doc['doc_type'], 'photo') !== false || $doc['doc_type'] === 'vehicle_photos';
            });
            $vehicle['photos'] = array_values($vehicle['photos']); // Re-index
            
            // Get transporter's other active vehicles
            $otherSql = "SELECT id, vehicle_model, vehicle_type, vehicle_number, working_district, 
                                passenger_count, ac_type
                         FROM vehicles 
                         WHERE user_id = ? 
                         AND status = 'active'
                         AND id != ?
                         LIMIT 3";
            $otherStmt = $pdo->prepare($otherSql);
            $otherStmt->execute([$vehicle['user_id'], $vehicleId]);
            $vehicle['other_vehicles'] = $otherStmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Get transporter stats
            $statsSql = "SELECT 
                            COUNT(*) as total_vehicles,
                            AVG(rating) as avg_rating
                         FROM vehicles v
                         LEFT JOIN transport_bookings b ON v.id = b.vehicle_id
                         WHERE v.user_id = ? AND v.status = 'active'";
            $statsStmt = $pdo->prepare($statsSql);
            $statsStmt->execute([$vehicle['user_id']]);
            $stats = $statsStmt->fetch(\PDO::FETCH_ASSOC);
            
            $vehicle['transporter_stats'] = [
                'total_vehicles' => intval($stats['total_vehicles'] ?? 0),
                'avg_rating' => round(floatval($stats['avg_rating'] ?? 0), 1)
            ];
            
            $this->sendResponse(true, [], $vehicle);
            
        } catch (\Exception $e) {
            error_log("Error getting vehicle: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicle details']);
        }
    }

    /**
     * Get single vehicle for transporter (includes all data)
     */
    public function get()
    {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['error' => 'Unauthorized']);
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->sendResponse(false, ['error' => 'Missing vehicle ID']);
        }

        try {
            $vehicle = Vehicle::findById($pdo, $id, $_SESSION['user']['id']);
            if (!$vehicle) {
                $this->sendResponse(false, ['error' => 'Vehicle not found']);
            }

            $docs = Vehicle::getDocuments($pdo, $id);
            $vehicle['documents'] = $docs;
            
            $vehicle['upcoming_bookings'] = $this->getUpcomingConfirmedBookings($id);

            $this->sendResponse(true, [], $vehicle);
        } catch (\Exception $e) {
            error_log("Error getting vehicle: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicle']);
        }
    }

    /**
     * Update vehicle
     */
    public function update()
    {
        global $pdo;

        error_log("=== Vehicle Update Called ===");
        error_log("POST Data: " . print_r($_POST, true));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['error' => 'Unauthorized']);
        }

        $userId = $_SESSION['user']['id'];
        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->sendResponse(false, ['error' => 'Missing vehicle ID']);
        }

        // Get existing vehicle to verify ownership
        $existingVehicle = Vehicle::findById($pdo, $id, $userId);
        if (!$existingVehicle) {
            $this->sendResponse(false, ['error' => 'Vehicle not found or unauthorized']);
        }

        // Get form data with fallback to existing data
        $vehicleType = $_POST['vehicle_type'] ?? $existingVehicle['vehicle_type'];
        $workingDistrict = $_POST['working_district'] ?? $existingVehicle['working_district'];
        $passengerCount = $_POST['passenger_count'] ?? $existingVehicle['passenger_count'];
        $acType = $_POST['ac_type'] ?? $_POST['ac-type'] ?? $existingVehicle['ac_type'];
        $model = $_POST['vehicle_model'] ?? $existingVehicle['vehicle_model'];
        $year = $_POST['vehicle_year'] ?? $existingVehicle['vehicle_year'];
        $color = $_POST['vehicle_color'] ?? $existingVehicle['vehicle_color'];
        $number = $_POST['vehicle_number'] ?? $existingVehicle['vehicle_number'];
        $status = $_POST['status'] ?? $existingVehicle['status'];

        error_log("Update Data - Type: $vehicleType, Model: $model, Year: $year, Color: $color, Number: $number");

        $vehicle = new Vehicle([
            'id' => $id,
            'userId' => $userId,
            'vehicleType' => $vehicleType,
            'workingDistrict' => $workingDistrict,
            'passengerCount' => $passengerCount,
            'acType' => $acType,
            'model' => $model,
            'year' => $year,
            'color' => $color,
            'number' => $number,
            'status' => $status
        ]);

        try {
            $pdo->beginTransaction();

            $ok = $vehicle->update($pdo);
            error_log("Update result: " . ($ok ? 'success' : 'failed'));

            // Handle new file uploads if any
            $fileFields = [
                'revenue_license',
                'insurance',
                'registration',
                'vehicle_photos',
                'profile_photo',
                'vehicle_photo'
            ];

            $uploadedFiles = 0;
            foreach ($fileFields as $field) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                    if (is_array($_FILES[$field]['name'])) {
                        foreach ($_FILES[$field]['tmp_name'] as $idx => $tmp) {
                            if ($_FILES[$field]['error'][$idx] === UPLOAD_ERR_OK) {
                                $orig = $_FILES[$field]['name'][$idx];
                                $path = $this->saveFile($tmp, $orig);
                                if ($path) {
                                    Vehicle::addDocument($pdo, $id, $field, $path);
                                    $uploadedFiles++;
                                }
                            }
                        }
                    } else {
                        if ($_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                            $path = $this->saveFile($_FILES[$field]['tmp_name'], $_FILES[$field]['name']);
                            if ($path) {
                                Vehicle::addDocument($pdo, $id, $field, $path);
                                $uploadedFiles++;
                            }
                        }
                    }
                }
            }

            error_log("Files uploaded: $uploadedFiles");

            $pdo->commit();
            $this->sendResponse(true, [], [
                'updated' => (bool) $ok,
                'message' => 'Vehicle updated successfully',
                'filesUploaded' => $uploadedFiles
            ]);
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Update error: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete vehicle
     */
    public function delete()
    {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['error' => 'Unauthorized']);
        }

        $userId = $_SESSION['user']['id'];
        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->sendResponse(false, ['error' => 'Missing vehicle ID']);
        }

        try {
            $ok = Vehicle::deleteById($pdo, $id, $userId);
            $this->sendResponse((bool) $ok, $ok ? [] : ['error' => 'Delete failed']);
        } catch (\Exception $e) {
            error_log("Delete error: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Delete failed']);
        }
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    private function saveFile($tmpPath, $originalName)
    {
        try {
            if (!file_exists($tmpPath)) {
                error_log("File does not exist at temp path: $tmpPath");
                return null;
            }

            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'pdf'];

            if (!in_array($ext, $allowedExts)) {
                error_log("Invalid file extension: $ext");
                return null;
            }

            $fileName = uniqid('veh_', true) . '.' . $ext;
            $dest = $this->uploadDir . '/' . $fileName;

            if (move_uploaded_file($tmpPath, $dest)) {
                error_log("File saved successfully: $dest");
                return '/uploads/vehicles/' . $fileName;
            } else {
                error_log("Failed to move uploaded file from $tmpPath to $dest");
                return null;
            }
        } catch (\Exception $e) {
            error_log("Error saving file: " . $e->getMessage());
            return null;
        }
    }

    public static function getVehicleMainImage($conn, $vehicleId)
    {
        $sql = "SELECT file_path FROM vehicle_documents 
                WHERE vehicle_id = ? AND doc_type = 'vehicle_photos' 
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$vehicleId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['file_path'] : null;
    }

    /**
     * Get active vehicles for dropdown (for emergency plans)
     */
    public function getActiveVehicles()
    {
        try {
            $userId = $this->checkAuth();
            
            $sql = "SELECT id, vehicle_model, vehicle_number, vehicle_type, passenger_count 
                    FROM vehicles 
                    WHERE user_id = ? AND status = 'active'
                    ORDER BY vehicle_model ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $vehicles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            $this->sendResponse(true, [], $vehicles);
            
        } catch (\Exception $e) {
            error_log("Error getting active vehicles: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicles']);
        }
    }

    /**
     * Get vehicle deactivation history
     */
    public function getDeactivationHistory()
    {
        try {
            $userId = $this->checkAuth();
            
            $vehicleId = $_GET['vehicle_id'] ?? null;
            
            $sql = "SELECT h.*, v.vehicle_model, v.vehicle_number
                    FROM vehicle_deactivation_history h
                    INNER JOIN vehicles v ON h.vehicle_id = v.id
                    WHERE v.user_id = ?";
            
            $params = [$userId];
            
            if ($vehicleId) {
                $sql .= " AND h.vehicle_id = ?";
                $params[] = $vehicleId;
            }
            
            $sql .= " ORDER BY h.changed_at DESC LIMIT 50";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $history = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            $this->sendResponse(true, [], $history);
            
        } catch (\Exception $e) {
            error_log("Error getting deactivation history: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load history']);
        }
    }
}