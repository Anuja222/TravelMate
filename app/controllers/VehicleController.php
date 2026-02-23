<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

use App\Models\Vehicle;

class VehicleController
{
    private $uploadDir;
    private $pdo;

    // Upload constraints
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'pdf'];
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg', 'image/png', 'application/pdf'
    ];

    public function __construct()
    {
        \SessionHelper::start();

        // Store PDO reference from config instead of using global everywhere
        global $pdo;
        $this->pdo = $pdo;

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

    /**
     * Sanitize a string input - trim, limit length, strip tags
     */
    private function sanitizeInput(string $value, int $maxLength = 255): string
    {
        return substr(trim(strip_tags($value)), 0, $maxLength);
    }

    /**
     * Validate an integer input within range
     */
    private function sanitizeInt($value, int $min = 0, int $max = PHP_INT_MAX): int
    {
        $val = (int)$value;
        return max($min, min($max, $val));
    }

    // ─── Create Vehicle ──────────────────────────────────────────────

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!\SessionHelper::requireAuthApi()) return;

        $userId = \SessionHelper::getUserId();

        // Sanitize all inputs
        $vehicleType = $this->sanitizeInput($_POST['vehicle_type'] ?? '', 50);
        $workingDistrict = $this->sanitizeInput($_POST['working_district'] ?? '', 100);
        $passengerCount = $this->sanitizeInt($_POST['passenger_count'] ?? 2, 1, 60);
        $acType = $this->sanitizeInput($_POST['ac_type'] ?? $_POST['ac-type'] ?? 'non-ac', 20);
        $model = $this->sanitizeInput($_POST['vehicle_model'] ?? '', 100);
        $year = $this->sanitizeInt($_POST['vehicle_year'] ?? date('Y'), 1900, (int)date('Y') + 2);
        $color = $this->sanitizeInput($_POST['vehicle_color'] ?? '', 50);
        $number = $this->sanitizeInput($_POST['vehicle_number'] ?? '', 20);
        $status = 'active'; // Force active on creation

        // Validate required fields
        if (empty($vehicleType)) {
            $this->sendResponse(false, ['error' => 'Vehicle type is required']);
        }

        // Validate AC type
        if (!in_array($acType, ['ac', 'non-ac'])) {
            $acType = 'non-ac';
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
            if (!$this->pdo) {
                throw new \Exception('Database connection failed');
            }

            $this->pdo->beginTransaction();

            $vehicleId = $vehicle->create($this->pdo);

            if (!$vehicleId) { 
                throw new \Exception('Failed to create vehicle');
            }

            // Handle file uploads
            $uploadedFiles = $this->processFileUploads($vehicleId);

            $this->pdo->commit();

            $this->sendResponse(true, [], [
                'vehicleId' => $vehicleId,
                'message' => 'Vehicle created successfully',
                'filesUploaded' => $uploadedFiles
            ]);

        } catch (\PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Vehicle create PDO Error: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'A database error occurred. Please try again.']);
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Vehicle create Error: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to save vehicle. Please try again.']);
        }
    }

    // ─── File Handling ───────────────────────────────────────────────

    /**
     * Process all file uploads for a vehicle
     */
    private function processFileUploads(int $vehicleId): int
    {
        $fileFields = [
            'revenue_license', 'insurance', 'registration',
            'vehicle_photos', 'profile_photo', 'license_front',
            'license_rear', 'nic_front', 'nic_rear',
            'owner_nic_front', 'owner_nic_rear', 'vehicle_photo'
        ];

        $uploadedFiles = 0;
        foreach ($fileFields as $field) {
            if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if (is_array($_FILES[$field]['name'])) {
                foreach ($_FILES[$field]['tmp_name'] as $idx => $tmp) {
                    if ($_FILES[$field]['error'][$idx] === UPLOAD_ERR_OK) {
                        $size = $_FILES[$field]['size'][$idx] ?? 0;
                        $path = $this->saveFile($tmp, $_FILES[$field]['name'][$idx], $size);
                        if ($path) {
                            Vehicle::addDocument($this->pdo, $vehicleId, $field, $path);
                            $uploadedFiles++;
                        }
                    }
                }
            } else {
                if ($_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $size = $_FILES[$field]['size'] ?? 0;
                    $path = $this->saveFile($_FILES[$field]['tmp_name'], $_FILES[$field]['name'], $size);
                    if ($path) {
                        Vehicle::addDocument($this->pdo, $vehicleId, $field, $path);
                        $uploadedFiles++;
                    }
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Save a single uploaded file with full validation
     */
    private function saveFile($tmpPath, $originalName, $fileSize = 0)
    {
        try {
            if (!file_exists($tmpPath)) {
                error_log("File does not exist at temp path: $tmpPath");
                return null;
            }

            // Validate file size
            if ($fileSize > self::MAX_FILE_SIZE) {
                error_log("File too large: " . round($fileSize / 1024 / 1024, 2) . "MB");
                return null;
            }

            // Validate extension
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($ext, self::ALLOWED_EXTENSIONS)) {
                error_log("Invalid file extension: $ext");
                return null;
            }

            // Validate MIME type using finfo (not trusting client headers)
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($tmpPath);
            if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
                error_log("Invalid MIME type: $mimeType for file: $originalName");
                return null;
            }

            // For images, verify it's actually a valid image
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $imageInfo = @getimagesize($tmpPath);
                if ($imageInfo === false) {
                    error_log("File is not a valid image: $originalName");
                    return null;
                }
            }

            $fileName = uniqid('veh_', true) . '.' . $ext;
            $dest = $this->uploadDir . '/' . $fileName;

            if (move_uploaded_file($tmpPath, $dest)) {
                return '/uploads/vehicles/' . $fileName;
            } else {
                error_log("Failed to move uploaded file");
                return null;
            }
        } catch (\Exception $e) {
            error_log("Error saving file: " . $e->getMessage());
            return null;
        }
    }

    // ─── Static Helper ───────────────────────────────────────────────

    public static function getVehicleMainImage($conn, $vehicleId)
    {
        $sql = "SELECT file_path FROM vehicle_documents 
            WHERE vehicle_id = ? AND doc_type = 'vehicle_photos' 
            LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$vehicleId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['file_path'] : null;
    }

    // ─── List Vehicles by User ───────────────────────────────────────

    public function listByUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!\SessionHelper::requireAuthApi()) return;

        try {
            $userId = \SessionHelper::getUserId();
            $vehicles = Vehicle::findByUser($this->pdo, $userId);

            foreach ($vehicles as &$vehicle) {
                $vehicle['main_image'] = self::getVehicleMainImage($this->pdo, $vehicle['id']);
                $vehicle['documents'] = Vehicle::getDocuments($this->pdo, $vehicle['id']);
            }

            $this->sendResponse(true, [], $vehicles);
        } catch (\Exception $e) {
            error_log("Error listing vehicles: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicles']);
        }
    }

    // ─── List All Active Vehicles ────────────────────────────────────

    public function listAll()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        try {
            $vehicles = Vehicle::findAll($this->pdo);

            foreach ($vehicles as &$vehicle) {
                $vehicle['main_image'] = self::getVehicleMainImage($this->pdo, $vehicle['id']);
                $vehicle['documents'] = Vehicle::getDocuments($this->pdo, $vehicle['id']);
            }

            $this->sendResponse(true, [], $vehicles);
        } catch (\Exception $e) {
            error_log("Error listing all vehicles: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicles']);
        }
    }

    // ─── Get Single Vehicle ──────────────────────────────────────────

    public function get()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!\SessionHelper::requireAuthApi()) return;

        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $this->sendResponse(false, ['error' => 'Missing vehicle ID']);
        }

        try {
            $userId = \SessionHelper::getUserId();
            $vehicle = Vehicle::findById($this->pdo, $id, $userId);
            if (!$vehicle) {
                $this->sendResponse(false, ['error' => 'Vehicle not found']);
            }

            $vehicle['documents'] = Vehicle::getDocuments($this->pdo, $id);

            $this->sendResponse(true, [], $vehicle);
        } catch (\Exception $e) {
            error_log("Error getting vehicle: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Failed to load vehicle']);
        }
    }

    // ─── Update Vehicle ──────────────────────────────────────────────

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!\SessionHelper::requireAuthApi()) return;

        $userId = \SessionHelper::getUserId();
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            $this->sendResponse(false, ['error' => 'Missing vehicle ID']);
        }

        // Verify ownership
        $existingVehicle = Vehicle::findById($this->pdo, $id, $userId);
        if (!$existingVehicle) {
            $this->sendResponse(false, ['error' => 'Vehicle not found or unauthorized']);
        }

        // Sanitize inputs with fallback to existing data
        $vehicleType = $this->sanitizeInput($_POST['vehicle_type'] ?? $existingVehicle['vehicle_type'], 50);
        $workingDistrict = $this->sanitizeInput($_POST['working_district'] ?? $existingVehicle['working_district'], 100);
        $passengerCount = $this->sanitizeInt($_POST['passenger_count'] ?? $existingVehicle['passenger_count'], 1, 60);
        $acType = $this->sanitizeInput($_POST['ac_type'] ?? $_POST['ac-type'] ?? $existingVehicle['ac_type'], 20);
        $model = $this->sanitizeInput($_POST['vehicle_model'] ?? $existingVehicle['vehicle_model'], 100);
        $year = $this->sanitizeInt($_POST['vehicle_year'] ?? $existingVehicle['vehicle_year'], 1900, (int)date('Y') + 2);
        $color = $this->sanitizeInput($_POST['vehicle_color'] ?? $existingVehicle['vehicle_color'], 50);
        $number = $this->sanitizeInput($_POST['vehicle_number'] ?? $existingVehicle['vehicle_number'], 20);
        $status = $this->sanitizeInput($_POST['status'] ?? $existingVehicle['status'], 20);

        // Validate status
        if (!in_array($status, ['active', 'inactive', 'maintenance'])) {
            $status = $existingVehicle['status'];
        }

        // Validate AC type
        if (!in_array($acType, ['ac', 'non-ac'])) {
            $acType = 'non-ac';
        }

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
            $this->pdo->beginTransaction();

            $ok = $vehicle->update($this->pdo);

            // Handle new file uploads
            $uploadedFiles = $this->processFileUploads($id);

            $this->pdo->commit();
            $this->sendResponse(true, [], [
                'updated' => (bool) $ok,
                'message' => 'Vehicle updated successfully',
                'filesUploaded' => $uploadedFiles
            ]);
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Vehicle update error: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Update failed. Please try again.']);
        }
    }

    // ─── Delete Vehicle ──────────────────────────────────────────────

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['error' => 'Invalid method']);
        }

        if (!\SessionHelper::requireAuthApi()) return;

        $userId = \SessionHelper::getUserId();
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            $this->sendResponse(false, ['error' => 'Missing vehicle ID']);
        }

        try {
            $ok = Vehicle::deleteById($this->pdo, $id, $userId);
            $this->sendResponse((bool) $ok, $ok ? [] : ['error' => 'Delete failed']);
        } catch (\Exception $e) {
            error_log("Vehicle delete error: " . $e->getMessage());
            $this->sendResponse(false, ['error' => 'Delete failed. Please try again.']);
        }
    }
}