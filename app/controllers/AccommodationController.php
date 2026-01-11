<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Accommodation.php';
require_once __DIR__ . '/../../config/database.php';

use App\Models\Accommodation;

class AccommodationController {
    private $uploadDir;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->uploadDir = __DIR__ . '/../../public/uploads/accommodations';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    private function sendResponse($success, $errors = [], $data = null) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'errors' => $errors, 'data' => $data]);
        exit;
    }

    public function create() {
        global $pdo;

        error_log("=== Accommodation Create Called ===");
        error_log("POST Data: " . print_r($_POST, true));
        error_log("FILES Data: " . print_r($_FILES, true));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }

        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['User not authenticated']);
        }

        $userId = $_SESSION['user']['id'];
        
        // Get accommodation details from POST data
        $propertyType = $_POST['property_type'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $location = $_POST['location'] ?? '';
        $rooms = $_POST['rooms'] ?? 0;
        $bathrooms = $_POST['bathrooms'] ?? 0;
        $maxGuests = $_POST['max_guests'] ?? 0;
        $pricePerNight = $_POST['price_per_night'] ?? 0;
        $smoking = isset($_POST['smoking']) ? 1 : 0;
        $parties = isset($_POST['parties']) ? 1 : 0;
        $pets = $_POST['pets'] ?? 'no';
        $checkInStart = $_POST['check_in_start'] ?? '';
        $checkInEnd = $_POST['check_in_end'] ?? '';
        $checkOutTime = $_POST['check_out_time'] ?? '';
        $status = $_POST['status'] ?? 'active';

        // Validate required fields
        if (empty($propertyType) || empty($title) || empty($description)) {
            $this->sendResponse(false, ['Missing required fields']);
        }

        $accommodation = new Accommodation([
            'userId' => $userId,
            'propertyType' => $propertyType,
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'rooms' => $rooms,
            'bathrooms' => $bathrooms,
            'maxGuests' => $maxGuests,
            'pricePerNight' => $pricePerNight,
            'smoking' => $smoking,
            'parties' => $parties,
            'pets' => $pets,
            'checkInStart' => $checkInStart,
            'checkInEnd' => $checkInEnd,
            'checkOutTime' => $checkOutTime,
            'status' => $status
        ]);

        try {
            $pdo->beginTransaction();
            
            // Create accommodation record
            $accommodationId = $accommodation->create($pdo);
            
            // Handle image uploads
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $images = $_FILES['images'];
                $totalFiles = count($images['name']);
                
                for ($i = 0; $i < $totalFiles; $i++) {
                    if ($images['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $images['tmp_name'][$i];
                        $originalName = $images['name'][$i];
                        $filePath = $this->saveFile($tmpName, $originalName);
                        
                        if ($filePath) {
                            // Set first image as main image
                            Accommodation::addImage($pdo, $accommodationId, $filePath, $i === 0);
                        }
                    }
                }
            }
            
            $pdo->commit();
            $this->sendResponse(true, [], ['id' => $accommodationId]);
            
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log("Error creating accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to create accommodation']);
        }
    }

    // Temporary upload endpoint used by client-side uploaders (returns JSON)
    public function uploadTemp() {
        // Accept POST file uploads and return JSON with saved paths
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }

        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['User not authenticated']);
        }

        $saved = [];
        try {
            if (empty($_FILES)) {
                $this->sendResponse(false, ['No files uploaded']);
            }

            foreach ($_FILES as $field => $fileInfo) {
                // handle multiple files in one field
                if (is_array($fileInfo['name'])) {
                    for ($i = 0; $i < count($fileInfo['name']); $i++) {
                        if ($fileInfo['error'][$i] === UPLOAD_ERR_OK) {
                            $tmpName = $fileInfo['tmp_name'][$i];
                            $originalName = $fileInfo['name'][$i];
                            $path = $this->saveFile($tmpName, $originalName);
                            if ($path) $saved[] = $path;
                        }
                    }
                } else {
                    if ($fileInfo['error'] === UPLOAD_ERR_OK) {
                        $tmpName = $fileInfo['tmp_name'];
                        $originalName = $fileInfo['name'];
                        $path = $this->saveFile($tmpName, $originalName);
                        if ($path) $saved[] = $path;
                    }
                }
            }

            $this->sendResponse(true, [], ['files' => $saved]);
        } catch (\Exception $e) {
            error_log('uploadTemp error: ' . $e->getMessage());
            $this->sendResponse(false, ['Upload failed']);
        }
    }

    private function saveFile($tmpPath, $originalName) {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '_' . time() . '.' . $extension;
        $targetPath = $this->uploadDir . '/' . $newFileName;
        
        if (move_uploaded_file($tmpPath, $targetPath)) {
            return 'uploads/accommodations/' . $newFileName;
        }
        
        return false;
    }

    public function listByUser() {
        global $pdo;
        
        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['User not authenticated']);
        }
        
        try {
            $accommodations = Accommodation::findByUser($pdo, $_SESSION['user']['id']);
            
            // Get main image for each accommodation
            foreach ($accommodations as &$accommodation) {
                $accommodation['main_image'] = Accommodation::getMainImage($pdo, $accommodation['id']);
            }
            
            $this->sendResponse(true, [], $accommodations);
            
        } catch (\Exception $e) {
            error_log("Error listing accommodations: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to list accommodations']);
        }
    }

    public function listAll() {
        global $pdo;
        
        try {
            $accommodations = Accommodation::findAll($pdo);
            
            // Get main image for each accommodation
            foreach ($accommodations as &$accommodation) {
                $accommodation['main_image'] = Accommodation::getMainImage($pdo, $accommodation['id']);
            }
            
            $this->sendResponse(true, [], $accommodations);
            
        } catch (\Exception $e) {
            error_log("Error listing all accommodations: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to list accommodations']);
        }
    }

    public function get() {
        global $pdo;
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->sendResponse(false, ['Accommodation ID not provided']);
        }
        
        try {
            $accommodation = Accommodation::findById($pdo, $id);
            if (!$accommodation) {
                $this->sendResponse(false, ['Accommodation not found']);
            }
            
            // Get all images for the accommodation
            $accommodation['images'] = Accommodation::getImages($pdo, $id);
            
            $this->sendResponse(true, [], $accommodation);
            
        } catch (\Exception $e) {
            error_log("Error getting accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to get accommodation details']);
        }
    }

    public function update() {
        global $pdo;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }
        
        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['User not authenticated']);
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->sendResponse(false, ['Accommodation ID not provided']);
        }
        
        try {
            $existing = Accommodation::findById($pdo, $id, $_SESSION['user']['id']);
            if (!$existing) {
                $this->sendResponse(false, ['Accommodation not found or unauthorized']);
            }
            
            $accommodation = new Accommodation([
                'id' => $id,
                'userId' => $_SESSION['user']['id'],
                'propertyType' => $_POST['property_type'] ?? $existing['property_type'],
                'title' => $_POST['title'] ?? $existing['title'],
                'description' => $_POST['description'] ?? $existing['description'],
                'rooms' => $_POST['rooms'] ?? $existing['rooms'],
                'bathrooms' => $_POST['bathrooms'] ?? $existing['bathrooms'],
                'maxGuests' => $_POST['max_guests'] ?? $existing['max_guests'],
                // Accept explicit 0/1 values for checkboxes (form should send 0 when unchecked)
                'smoking' => isset($_POST['smoking']) ? intval($_POST['smoking']) : $existing['smoking'],
                'parties' => isset($_POST['parties']) ? intval($_POST['parties']) : $existing['parties'],
                'pets' => $_POST['pets'] ?? $existing['pets'],
                'checkInStart' => $_POST['check_in_start'] ?? $existing['check_in_start'],
                'checkInEnd' => $_POST['check_in_end'] ?? $existing['check_in_end'],
                'checkOutTime' => $_POST['check_out_time'] ?? $existing['check_out_time'],
                'status' => $_POST['status'] ?? $existing['status']
            ]);
            
            $pdo->beginTransaction();
            
            if ($accommodation->update($pdo)) {
                // Allow deletion of existing images (delete_images[])
                if (!empty($_POST['delete_images'])) {
                    $delIds = is_array($_POST['delete_images']) ? $_POST['delete_images'] : explode(',', $_POST['delete_images']);
                    foreach ($delIds as $imgId) {
                        $imgId = intval($imgId);
                        if ($imgId <= 0) continue;
                        // get path
                        $stmtImg = $pdo->prepare("SELECT image_path FROM accommodation_images WHERE id = ? AND accommodation_id = ?");
                        $stmtImg->execute([$imgId, $id]);
                        $row = $stmtImg->fetch(\PDO::FETCH_ASSOC);
                        if ($row) {
                            // remove file if exists
                            $fileOnDisk = __DIR__ . '/../../public/' . $row['image_path'];
                            if (file_exists($fileOnDisk)) {
                                @unlink($fileOnDisk);
                            }
                            $stmtDel = $pdo->prepare("DELETE FROM accommodation_images WHERE id = ? AND accommodation_id = ?");
                            $stmtDel->execute([$imgId, $id]);
                        }
                    }
                }

                // Handle new image uploads (can mark first new uploaded as main via make_new_main)
                $makeNewMain = isset($_POST['make_new_main']) && ($_POST['make_new_main'] === '1' || $_POST['make_new_main'] === 'on');
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $images = $_FILES['images'];
                    $totalFiles = count($images['name']);

                    for ($i = 0; $i < $totalFiles; $i++) {
                        if ($images['error'][$i] === UPLOAD_ERR_OK) {
                            $tmpName = $images['tmp_name'][$i];
                            $originalName = $images['name'][$i];
                            $filePath = $this->saveFile($tmpName, $originalName);

                            if ($filePath) {
                                $isMain = ($makeNewMain && $i === 0) ? true : false;
                                Accommodation::addImage($pdo, $id, $filePath, $isMain);
                            }
                        }
                    }
                }

                // Set existing image as main if requested (main_image_id)
                if (!empty($_POST['main_image_id'])) {
                    $mainId = intval($_POST['main_image_id']);
                    if ($mainId > 0) {
                        // reset all to 0 then set chosen one to 1 (only for this accommodation)
                        $stmtReset = $pdo->prepare("UPDATE accommodation_images SET is_main = 0 WHERE accommodation_id = ?");
                        $stmtReset->execute([$id]);
                        $stmtSet = $pdo->prepare("UPDATE accommodation_images SET is_main = 1 WHERE id = ? AND accommodation_id = ?");
                        $stmtSet->execute([$mainId, $id]);
                    }
                }

                $pdo->commit();
                $this->sendResponse(true);
            } else {
                $pdo->rollBack();
                $this->sendResponse(false, ['Failed to update accommodation']);
            }
            
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Error updating accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to update accommodation']);
        }
    }

    public function delete() {
        global $pdo;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }
        
        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['User not authenticated']);
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->sendResponse(false, ['Accommodation ID not provided']);
        }
        
        try {
            if (Accommodation::deleteById($pdo, $id, $_SESSION['user']['id'])) {
                $this->sendResponse(true);
            } else {
                $this->sendResponse(false, ['Failed to delete accommodation']);
            }
        } catch (\Exception $e) {
            error_log("Error deleting accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to delete accommodation']);
        }
    }
}