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

    public function accommodationActivitySummary(){
        global $pdo;

        if(!isset($_SESSION['user'])){
            header('Location: /TravelMate/public/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        //get activity summary counts
        //listingsCount
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM accommodations WHERE user_id = ?");
        $stmt->execute([$userId]);
        $listingsCount = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

        //bookedCount
        //bookingRecievedCount


        include __DIR__ . '/../views/accommodation/newerDashboard.view.php'; //load view
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

        if ($result) {
            // Instead of JSON response, redirect to dashboard
            header('Location: /TravelMate/public/ac_dashboard');
            exit;
        }
    }
    
    public function selectPropertyType() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propertyType = $_POST['property_type'] ?? '';
            if (!empty($propertyType)) {
                $_SESSION['accommodation_features'] = ['property_type' => $propertyType];
                header('Location: /TravelMate/public/accommodationFeatures');
                exit;
            } else {
                header('Location: /TravelMate/public/propertyListingStart');
                exit;
            }
        }
    }


    
    public function saveFeatures() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { //check whether  the form is submited using POST method
            // Merge new POST data with existing session data (preserve property_type)
            $_SESSION['accommodation_features'] = array_merge(
                $_SESSION['accommodation_features'] ?? [],
                $_POST
            );

            // Redirect to next page
            header('Location: /TravelMate/public/propertyDetails'); //header() - sends HTTP response
            exit;
        }
    }

    public function saveDetails() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['accommodation_details'] = $_POST;
            header('Location: /TravelMate/public/photoUpload');
            exit;
        }
    }

    public function savePhoto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Store photo data in session
            $_SESSION['accommodation_photos'] = $_POST;
            
            // Store uploaded images if any
            if (isset($_FILES['images'])) {
                $_SESSION['accommodation_images'] = $_FILES['images'];
            }
            
            header('Location: /TravelMate/public/houseRules');
            exit;
        }
    }

    public function saveAccommodation() {
        global $pdo;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user'])) {
                header('Location: /TravelMate/public/login');
                exit;
            }
            
            $userId = $_SESSION['user']['id'];
            
            // Combine all session data
            $features = $_SESSION['accommodation_features'] ?? []; //all property details from accommodationFeatures page
            $details = $_SESSION['accommodation_details'] ?? []; //details from propertyDetails page
            $photos = $_SESSION['accommodation_photos'] ?? []; //photos and description from photoUpload page
            $rules = $_POST; //house rules from current page
            
            // DEBUG: Log session data to check what's being retrieved
            error_log("DEBUG accommodation_features: " . print_r($features, true));
            error_log("DEBUG accommodation_details: " . print_r($details, true));
            error_log("DEBUG accommodation_photos: " . print_r($photos, true));
            
            // Get all fields
            $title = $features['title'] ?? '';
            $property_type = $features['property_type'] ?? '';  // Fixed: get from features, not type
            $location = $features['location'] ?? '';
            $description = $photos['propertyDescription'] ?? $features['description'] ?? '';
            $rooms = $details['rooms'] ?? 0;
            $bathrooms = $details['bathrooms'] ?? 0;
            $maxGuests = $details['max_guests'] ?? 0;
            $smoking = isset($rules['smoking']) ? 1 : 0;
            $parties = isset($rules['parties']) ? 1 : 0;
            $pets = $rules['pets'] ?? 'no';
            $checkInStart = $rules['check_in_start'] ?? '';
            $checkInEnd = $rules['check_in_end'] ?? '';
            $checkOutTime = $rules['check_out_time'] ?? '';
            $status = 'active';
            
            // DEBUG: Log extracted values
            error_log("DEBUG title: $title, property_type: $property_type, location: $location");
            
            try {
                $pdo->beginTransaction();
                
                // Insert accommodation
                $sql = "INSERT INTO accommodations (
                    user_id, property_type, title, description, location,
                    rooms, bathrooms, max_guests,
                    smoking, parties, pets, check_in_start, check_in_end,
                    check_out_time, status, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
                )";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $userId,
                    $property_type,
                    $title,
                    $description,
                    $location,
                    $rooms,
                    $bathrooms,
                    $maxGuests,
                    $smoking,
                    $parties,
                    $pets,
                    $checkInStart,
                    $checkInEnd,
                    $checkOutTime,
                    $status
                ]);
                
                $accommodationId = $pdo->lastInsertId();
                
                // Handle image uploads from session if any
                if (isset($_SESSION['accommodation_images'])) {
                    $images = $_SESSION['accommodation_images'];
                    // Handle both single file and array of files
                    if (isset($images['name'])) {
                        if (is_array($images['name'])) {
                            // Multiple files
                            $totalFiles = count($images['name']);
                            for ($i = 0; $i < $totalFiles; $i++) {
                                if ($images['error'][$i] === UPLOAD_ERR_OK) {
                                    $tmpName = $images['tmp_name'][$i];
                                    $originalName = $images['name'][$i];
                                    $filePath = $this->saveFile($tmpName, $originalName);
                                    
                                    if ($filePath) {
                                        Accommodation::addImage($pdo, $accommodationId, $filePath, $i === 0);
                                    }
                                }
                            }
                        } else {
                            // Single file
                            if ($images['error'] === UPLOAD_ERR_OK) {
                                $tmpName = $images['tmp_name'];
                                $originalName = $images['name'];
                                $filePath = $this->saveFile($tmpName, $originalName);
                                
                                if ($filePath) {
                                    Accommodation::addImage($pdo, $accommodationId, $filePath, true);
                                }
                            }
                        }
                    }
                }
                
                $pdo->commit();
                
                // Clear session data
                unset($_SESSION['accommodation_features']);
                unset($_SESSION['accommodation_details']);
                unset($_SESSION['accommodation_photos']);
                unset($_SESSION['accommodation_images']);
                
                header('Location: /TravelMate/public/success');
                exit;
                
            } catch (\Exception $e) {
                $pdo->rollBack(); //cancel database transaction
                error_log("Error saving accommodation: " . $e->getMessage()); //write the error to error server log
                echo "Error: " . $e->getMessage(); //display error msg on screen
                exit;
            }
        }
    }
}