<?php
namespace App\Controllers;

require_once __DIR__ . '/../../models/Vehicle.php';
require_once __DIR__ . '/../../../config/database.php';

use App\Models\Vehicle;

class VehicleController
{
    private $uploadDir;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $this->uploadDir = __DIR__ . '/../../../public/uploads/vehicles';
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

    // Create vehicle
    public function create()
    {
        global $pdo;

        // Log for debugging
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
        error_log("User ID: " . $userId);

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
            $vehicles = Vehicle::findByUser($pdo, $userId);

            // Add main image to each vehicle
            foreach ($vehicles as &$vehicle) {
                $mainImage = self::getVehicleMainImage($pdo, $vehicle['id']);
                $vehicle['main_image'] = $mainImage;

                // Get all documents for this vehicle
                $docs = Vehicle::getDocuments($pdo, $vehicle['id']);
                $vehicle['documents'] = $docs;
            }

            $this->sendResponse(true, [], $vehicles);
        } catch (\Exception $e) {
            error_log("Error listing vehicles: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicles']);
        }
    }

    public function listAll()
    {
        global $pdo;

        try {
            $vehicles = Vehicle::findAll($pdo);

            // Add main image to each vehicle
            foreach ($vehicles as &$vehicle) {
                $mainImage = self::getVehicleMainImage($pdo, $vehicle['id']);
                $vehicle['main_image'] = $mainImage;

                // Get all documents for this vehicle
                $docs = Vehicle::getDocuments($pdo, $vehicle['id']);
                $vehicle['documents'] = $docs;
            }

            $this->sendResponse(true, [], $vehicles);
        } catch (\Exception $e) {
            error_log("Error listing all vehicles: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicles']);
        }
    }

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

            $this->sendResponse(true, [], $vehicle);
        } catch (\Exception $e) {
            error_log("Error getting vehicle: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicle']);
        }
    }

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
}