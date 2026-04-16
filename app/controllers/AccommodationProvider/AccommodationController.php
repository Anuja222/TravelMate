<?php
namespace App\Controllers;

require_once __DIR__ . '/../../models/Accommodation.php';
require_once __DIR__ . '/../../../config/database.php';

use App\Models\Accommodation;

class AccommodationController {
    private $uploadDir;

    public function __construct() { //start session. setup enviroenment
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->uploadDir = __DIR__ . '/../../../public/uploads/accommodations'; //setup upload directory for images
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true); //create directory if it deosnt exist. 
        }
    }

    private function sendResponse($success, $errors = [], $data = null) { //JSON API response handler
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'errors' => $errors, 'data' => $data]); //JSON format - success, errors, data
        exit;
    }

    public function create() {
        global $pdo;                                            //used to connect to the database

        error_log("=== Accommodation Create Called ===");
        error_log("POST Data: " . print_r($_POST, true));
        error_log("FILES Data: " . print_r($_FILES, true));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {                //only POST data allowed
            $this->sendResponse(false, ['Invalid request method']);
        }

        if (!isset($_SESSION['user'])) {                            //login check - auth
            $this->sendResponse(false, ['User not authenticated']);
        }

        $userId = $_SESSION['user']['id'];
        
        // get accommodation details from POST data (form data)
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

        // validate required fields
        if (empty($propertyType) || empty($title) || empty($description)) {
            $this->sendResponse(false, ['Missing required fields']); 
        }

        $accommodation = new Accommodation([ //create model object
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
            $pdo->beginTransaction(); //saves to database 
            
            // create accommodation record
            $accommodationId = $accommodation->create($pdo); //saving to DB. call the model
            
            // handle image uploads
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $images = $_FILES['images'];
                $totalFiles = count($images['name']);
                error_log("API create: Found $totalFiles images to process");
                
                for ($i = 0; $i < $totalFiles; $i++) {
                    error_log("API create: Processing image $i, error code: " . $images['error'][$i]);
                    if ($images['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $images['tmp_name'][$i];
                        $originalName = $images['name'][$i];
                        $filePath = $this->saveFile($tmpName, $originalName);
                        
                        if ($filePath) {
                            $isMain = $i === 0 ? 1 : 0;
                            error_log("API create: Saving image $i with is_main=$isMain, path=$filePath");
                            // Set first image as main image
                            Accommodation::addImage($pdo, $accommodationId, $filePath, $isMain);
                        } else {
                            error_log("API create: Failed to save image $i");
                        }
                    }
                }
            } else {
                error_log("API create: No images in FILES or empty images['name'][0]");
            }
            
            $pdo->commit();
            $this->sendResponse(true, [], ['id' => $accommodationId]); //return accommodation ID when success.
            
        } catch (\Exception $e) {
            $pdo->rollBack(); //jump to preveious step
            error_log("Error creating accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to create accommodation']);//send JSON response while creating the error log file
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
                // handle multiple files in one field - accept multiple file uploads
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

            $this->sendResponse(true, [], ['files' => $saved]); //return paths for all uploaded files in JSON format
        } catch (\Exception $e) {
            error_log('uploadTemp error: ' . $e->getMessage());
            $this->sendResponse(false, ['Upload failed']);
        }
    }

    private function saveFile($tmpPath, $originalName) {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '_' . time() . '.' . $extension; //genarate unique filename using uniqid()
        $targetPath = $this->uploadDir . '/' . $newFileName;
        $logFile = __DIR__ . '/../../logs/php_error.log';
        
        file_put_contents($logFile, "[" . date('d-M-Y H:i:s e') . "] DEBUG saveFile: tmpPath=$tmpPath, targetPath=$targetPath, uploadDir=$this->uploadDir\n", FILE_APPEND);
        
        // Ensure directory exists
        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0777, true)) {
                file_put_contents($logFile, "[" . date('d-M-Y H:i:s e') . "] DEBUG saveFile: Failed to create directory: $this->uploadDir\n", FILE_APPEND);
                return false;
            }
            file_put_contents($logFile, "[" . date('d-M-Y H:i:s e') . "] DEBUG saveFile: Created directory: $this->uploadDir\n", FILE_APPEND);
        }
        
        if (move_uploaded_file($tmpPath, $targetPath)) {
            $relativePath = 'uploads/accommodations/' . $newFileName;
            file_put_contents($logFile, "[" . date('d-M-Y H:i:s e') . "] DEBUG saveFile: Successfully saved file to $relativePath\n", FILE_APPEND);
            return $relativePath;
        }
        
        file_put_contents($logFile, "[" . date('d-M-Y H:i:s e') . "] DEBUG saveFile: Failed to move file from $tmpPath to $targetPath\n", FILE_APPEND);
        return false;
    }

    public function listByUser() { 
        global $pdo;
        
        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['User not authenticated']);
        }
        
        try {
            $accommodations = Accommodation::findByUser($pdo, $_SESSION['user']['id']); //retrieves all accommodations for the logged-in user
            
            // get main image for each accommodation
            foreach ($accommodations as &$accommodation) {
                $accommodation['main_image'] = Accommodation::getMainImage($pdo, $accommodation['id']); //include main images for each accommodation
            }
            
            $this->sendResponse(true, [], $accommodations);
            
        } catch (\Exception $e) {
            error_log("Error listing accommodations: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to list accommodations']);
        }
    }

    public function listAll() { //retrieves all accommodations in the system
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

    public function get() { //retrieves a single accommodation by ID
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
            
            // get all images for the accommodation
            $accommodation['images'] = Accommodation::getImages($pdo, $id);
            
            $this->sendResponse(true, [], $accommodation);
            
        } catch (\Exception $e) {
            error_log("Error getting accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to get accommodation details']);
        }
    }

    public function update() { //update accommodation details
        global $pdo;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
            $this->sendResponse(false, ['Invalid request method']);
        }
        
        if (!isset($_SESSION['user'])) { //validate ownership
            $this->sendResponse(false, ['User not authenticated']);
        }
        
        $id = $_POST['id'] ?? null; //
        if (!$id) {
            $this->sendResponse(false, ['Accommodation ID not provided']);
        }
        
        try {
            $existing = Accommodation::findById($pdo, $id, $_SESSION['user']['id']);//retrieve existing accommodatin from DB
            if (!$existing) {
                $this->sendResponse(false, ['Accommodation not found or unauthorized']);
            }
            
            $accommodation = new Accommodation([ //create new object before update
                'id' => $id,
                'userId' => $_SESSION['user']['id'],
                'propertyType' => $_POST['property_type'] ?? $existing['property_type'],//if POST data available then update with that, else use existing data
                'title' => $_POST['title'] ?? $existing['title'],
                'description' => $_POST['description'] ?? $existing['description'],
                'location' => $_POST['location'] ?? ($existing['location'] ?? ''),
                'rooms' => $_POST['rooms'] ?? $existing['rooms'],
                'bathrooms' => $_POST['bathrooms'] ?? $existing['bathrooms'],
                'maxGuests' => $_POST['max_guests'] ?? $existing['max_guests'],
                'pricePerNight' => $_POST['price_per_night'] ?? ($existing['price_per_night'] ?? 0),
                // accept explicit 0/1 values for checkboxes (form should send 0 when unchecked)
                'smoking' => isset($_POST['smoking']) ? intval($_POST['smoking']) : $existing['smoking'],
                'parties' => isset($_POST['parties']) ? intval($_POST['parties']) : $existing['parties'],
                'pets' => $_POST['pets'] ?? $existing['pets'],
                'checkInStart' => $_POST['check_in_start'] ?? $existing['check_in_start'],
                'checkInEnd' => $_POST['check_in_end'] ?? $existing['check_in_end'],
                'checkOutTime' => $_POST['check_out_time'] ?? $existing['check_out_time'],
                'status' => $_POST['status'] ?? $existing['status']
            ]);
            
            $pdo->beginTransaction(); //to safe DB operation - if error then rollback() else commit()
            
            if ($accommodation->update($pdo)) { //update accommdoation data
                // allow deletion of existing images (delete_images[])
                if (!empty($_POST['delete_images'])) { //check whether have images that user choose to delete
                    $delIds = is_array($_POST['delete_images']) ? $_POST['delete_images'] : explode(',', $_POST['delete_images']);
                    foreach ($delIds as $imgId) { //delete images
                        $imgId = intval($imgId);
                        if ($imgId <= 0) continue;
                        // get path
                        $stmtImg = $pdo->prepare("SELECT image_path FROM accommodation_images WHERE id = ? AND accommodation_id = ?");
                        $stmtImg->execute([$imgId, $id]);
                        $row = $stmtImg->fetch(\PDO::FETCH_ASSOC);
                        if ($row) {
                            // remove file if exists
                            $fileOnDisk = __DIR__ . '/../../../public/' . $row['image_path'];
                            if (file_exists($fileOnDisk)) {
                                @unlink($fileOnDisk);
                            }
                            $stmtDel = $pdo->prepare("DELETE FROM accommodation_images WHERE id = ? AND accommodation_id = ?");
                            $stmtDel->execute([$imgId, $id]);
                        }
                    }
                }

                // handle new image uploads (can mark first new uploaded as main via make_new_main)
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

                // set existing image as main if requested (main_image_id)
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

    public function delete() { //delete and accommodation provider owns
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
            if (Accommodation::deleteById($pdo, $id, $_SESSION['user']['id'])) { //delete using accommodation id
                $this->sendResponse(true);
            } else {
                $this->sendResponse(false, ['Failed to delete accommodation']);
            }
        } catch (\Exception $e) {
            error_log("Error deleting accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to delete accommodation']);
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

            $_SESSION['property_details']['max_guests'] = (int)($_POST['max_guests'] ?? 2);
            $_SESSION['property_details']['bathrooms'] = (int)($_POST['bathrooms'] ?? 1);
            $_SESSION['property_details']['children'] = $_POST['children'] ?? null;
            
            // Bedroom data is already in session, but you can update if needed
            if (isset($_POST['bedrooms'])) {
                foreach ($_POST['bedrooms'] as $index => $bedroom) {
                    $_SESSION['property_details']['bedrooms'][$index] = $bedroom;
                }
            }
            header('Location: /TravelMate/public/photoUpload');
            exit;
        }
    }

    public function saveBedRoom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['property_details'])) {
                $_SESSION['property_details'] = [
                    'bedrooms' => [null],
                    'max_guests' => 2,
                    'bathrooms' => 1,
                    'children' => null
                ];
            }

            $bedIndex = (int)($_POST['bed_index'] ?? 1);
            if ($bedIndex < 1) {
                $bedIndex = 1;
            }
            $type = trim($_POST['bed_type'] ?? '');
            $count = (int)($_POST['bed_count'] ?? 0);

            // Ensure array size
            while (count($_SESSION['property_details']['bedrooms']) < $bedIndex) {
                $_SESSION['property_details']['bedrooms'][] = null;
            }

            $_SESSION['property_details']['bedrooms'][$bedIndex - 1] = [
                'type' => $type,
                'count' => $count
            ];

            header('Location: /TravelMate/public/propertyDetails');
            exit;
        }
    }

    public function savePhoto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Store photo data in session
            $_SESSION['accommodation_photos'] = $_POST;
            
            $uploadedImages = [];

            // Move uploaded images immediately so tmp files do not disappear between steps
            if (isset($_FILES['images']) && isset($_FILES['images']['name'])) {
                $files = $_FILES['images'];

                if (is_array($files['name'])) {
                    $totalFiles = count($files['name']);
                    for ($i = 0; $i < $totalFiles; $i++) {
                        if ($files['error'][$i] === UPLOAD_ERR_OK) {
                            $path = $this->saveFile($files['tmp_name'][$i], $files['name'][$i]);
                            if ($path) {
                                $uploadedImages[] = $path;
                            }
                        }
                    }
                } else {
                    if ($files['error'] === UPLOAD_ERR_OK) {
                        $path = $this->saveFile($files['tmp_name'], $files['name']);
                        if ($path) {
                            $uploadedImages[] = $path;
                        }
                    }
                }
            }

            // Persist paths for final save step
            $_SESSION['accommodation_uploaded_images'] = $uploadedImages;
            
            header('Location: /TravelMate/public/price');
            exit;
        }
    }

    public function savePrice(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_SESSION['accommodation_price'] = [
                'price_night' => $_POST['price_night'] ?? '',
                'price_guest' => $_POST['price_guest'] ?? ''
            ];
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
            $prices = $_SESSION['accommodation_price'] ?? []; //price from price.view.php page
            $rules = $_POST; //house rules from current page
            
            // DEBUG: Log session data to check what's being retrieved
            error_log("DEBUG accommodation_features: " . print_r($features, true));
            error_log("DEBUG accommodation_details: " . print_r($details, true));
            error_log("DEBUG accommodation_photos: " . print_r($photos, true));
            
            // Get all fields
            $title = $features['title'] ?? '';
            $property_type = $features['property_type'] ?? '';  // Fixed: get from features, not type
            $location = $features['location'] ?? '';
            $price_night = $prices['price_night'] ?? '';
            $price_guest = $prices['price_guest'] ?? '';
            $description = $photos['propertyDescription'] ?? $features['description'] ?? '';
            $rooms = $details['rooms'] ?? 0;
            $bathrooms = $details['bathrooms'] ?? 0;
            $maxGuests = $details['max_guests'] ?? 0;
            $smoking = isset($rules['smoking']) ? 1 : 0;
            $parties = isset($rules['parties']) ? 1 : 0;
            $pets = $rules['pets'] ?? 'no';
            $checkInStart = $rules['check_in_start'] ?? '';
            $checkInEnd = $rules['check_in_end'] ?? '';
            $checkOutStart = $rules['check_out_start'] ?? '';
            $checkOutEnd = $rules['check_out_end'] ?? '';
            $checkOutTime = '';
            if (!empty($checkOutStart) && !empty($checkOutEnd)) {
                $checkOutTime = $checkOutStart . '-' . $checkOutEnd;
            } elseif (!empty($checkOutStart)) {
                $checkOutTime = $checkOutStart;
            } elseif (!empty($checkOutEnd)) {
                $checkOutTime = $checkOutEnd;
            }
            $status = 'active';
            
            // Extract feature flags from session data
            $featureAirConditioning = isset($features['feature_air_conditioning']) ? 1 : 0;
            $featureHeating = isset($features['feature_heating']) ? 1 : 0;
            $featureWifi = isset($features['feature_wifi']) ? 1 : 0;
            $featureEvCharging = isset($features['feature_ev_charging']) ? 1 : 0;
            $featurePool = isset($features['feature_pool']) ? 1 : 0;
            $featureKitchen = isset($features['feature_kitchen']) ? 1 : 0;
            $featureKitchenette = isset($features['feature_kitchenette']) ? 1 : 0;
            $featureWashingMachine = isset($features['feature_washing_machine']) ? 1 : 0;
            $featureTv = isset($features['feature_tv']) ? 1 : 0;
            $featureEntertainmentPool = isset($features['feature_entertainment_pool']) ? 1 : 0;
            $featureHotTub = isset($features['feature_hot_tub']) ? 1 : 0;
            $featureMinibar = isset($features['feature_minibar']) ? 1 : 0;
            $featureSauna = isset($features['feature_sauna']) ? 1 : 0;
            $featureBalcony = isset($features['feature_balcony']) ? 1 : 0;
            $featureGardenView = isset($features['feature_garden_view']) ? 1 : 0;
            $featureTerrace = isset($features['feature_terrace']) ? 1 : 0;
            $featureView = isset($features['feature_view']) ? 1 : 0;
            $featureCctv = isset($features['feature_cctv']) ? 1 : 0;
            $featureSecurityGuards = isset($features['feature_security_guards']) ? 1 : 0;
            $featureFirstAidKit = isset($features['feature_first_aid_kit']) ? 1 : 0;
            $featureLivingRoom = isset($features['feature_living_room']) ? 1 : 0;
            
            // DEBUG: Log extracted values
            error_log("DEBUG title: $title, property_type: $property_type, location: $location");
            error_log("DEBUG accommodation_uploaded_images in session: " . print_r($_SESSION['accommodation_uploaded_images'] ?? 'NOT SET', true));
            
            try {
                $pdo->beginTransaction();
                
                // Insert accommodation
                $sql = "INSERT INTO accommodations (
                    user_id, property_type, title, description, location, 
                    rooms, bathrooms, max_guests, price_per_night, price_per_guest,
                    smoking, parties, pets, check_in_start, check_in_end,
                    check_out_time, status, 
                    feature_air_conditioning, feature_heating, feature_wifi, feature_ev_charging, feature_pool,
                    feature_kitchen, feature_kitchenette, feature_washing_machine, feature_tv, feature_entertainment_pool,
                    feature_hot_tub, feature_minibar, feature_sauna, feature_balcony, feature_garden_view,
                    feature_terrace, feature_view, feature_cctv, feature_security_guards, feature_first_aid_kit, feature_living_room,
                    created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
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
                    $price_night,
                    $price_guest,
                    $smoking,
                    $parties,
                    $pets,
                    $checkInStart,
                    $checkInEnd,
                    $checkOutTime,
                    $status,
                    $featureAirConditioning,
                    $featureHeating,
                    $featureWifi,
                    $featureEvCharging,
                    $featurePool,
                    $featureKitchen,
                    $featureKitchenette,
                    $featureWashingMachine,
                    $featureTv,
                    $featureEntertainmentPool,
                    $featureHotTub,
                    $featureMinibar,
                    $featureSauna,
                    $featureBalcony,
                    $featureGardenView,
                    $featureTerrace,
                    $featureView,
                    $featureCctv,
                    $featureSecurityGuards,
                    $featureFirstAidKit,
                    $featureLivingRoom
                ]);
                
                $accommodationId = $pdo->lastInsertId();
                $logFile = __DIR__ . '/../../logs/php_error.log';
                
                // Handle already-moved image paths from session
                if (!empty($_SESSION['accommodation_uploaded_images'])) {
                    $imagePaths = $_SESSION['accommodation_uploaded_images'];
                    file_put_contents($logFile, "[" . date('d-M-Y H:i:s e') . "] DEBUG saveAccommodation: Found " . count($imagePaths) . " uploaded image paths\n", FILE_APPEND);
                    foreach ($imagePaths as $index => $path) {
                        file_put_contents($logFile, "[" . date('d-M-Y H:i:s e') . "] DEBUG saveAccommodation: Adding image: accommodation_id=$accommodationId, path=$path, is_main=" . ($index === 0 ? '1' : '0') . "\n", FILE_APPEND);
                        Accommodation::addImage($pdo, $accommodationId, $path, $index === 0);
                    }
                }
                
                $pdo->commit();
                
                // Clear session data
                unset($_SESSION['accommodation_features']);
                unset($_SESSION['accommodation_details']);
                unset($_SESSION['accommodation_photos']);
                unset($_SESSION['accommodation_images']);
                unset($_SESSION['accommodation_uploaded_images']);
                
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

    public function index()
    {
        // Handle POST actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAction();
            return;
        }
        
        // Display the view
        $this->view('accommodation/propertyDetails');
    }
    
   private function handleAction()
    {
        $action = $_POST['action'] ?? '';
        $removeIndex = null;
        if (strpos($action, 'remove_bedroom:') === 0) {
            $parts = explode(':', $action, 2);
            if (isset($parts[1]) && is_numeric($parts[1])) {
                $removeIndex = (int)$parts[1];
            }
            $action = 'remove_bedroom';
        }
        
        if (!isset($_SESSION['property_details'])) {
            $_SESSION['property_details'] = [
                'bedrooms' => [null],
                'max_guests' => 2,
                'bathrooms' => 1,
                'children' => null
            ];
        }
        
        switch ($action) {
            case 'add_bedroom':
                $_SESSION['property_details']['bedrooms'][] = null;
                break;
                
            case 'remove_bedroom':
                $index = $removeIndex ?? (int)($_POST['bedroom_index'] ?? -1);
                if ($index >= 0 && isset($_SESSION['property_details']['bedrooms'][$index])) {
                    array_splice($_SESSION['property_details']['bedrooms'], $index, 1);
                    // Ensure at least one bedroom remains
                    if (empty($_SESSION['property_details']['bedrooms'])) {
                        $_SESSION['property_details']['bedrooms'] = [null];
                    }
                }
                break;
                
            case 'increment_guests':
                $_SESSION['property_details']['max_guests'] = min(100, $_SESSION['property_details']['max_guests'] + 1);
                break;
                
            case 'decrement_guests':
                $_SESSION['property_details']['max_guests'] = max(1, $_SESSION['property_details']['max_guests'] - 1);
                break;
                
            case 'increment_bathrooms':
                $_SESSION['property_details']['bathrooms'] = min(100, $_SESSION['property_details']['bathrooms'] + 1);
                break;
                
            case 'decrement_bathrooms':
                $_SESSION['property_details']['bathrooms'] = max(1, $_SESSION['property_details']['bathrooms'] - 1);
                break;
        }
        
        // Redirect back to avoid form resubmission
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }


    public function getRoomAvailability() {
        global $pdo;
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->sendResponse(false, ['Accommodation ID not provided']);
            return;
        }
        
        try {
            // get total rooms from accommodation
            $stmt = $pdo->prepare("SELECT rooms FROM accommodations WHERE id = ?"); //prepare sql query with excuting
            $stmt->execute([$id]); //execute with replacing $id -> ?
            $accommodation = $stmt->fetch(\PDO::FETCH_ASSOC); //gets result as associative array
            // $accommodation = [
            //     "rooms" => 3
            // ];

            if (!$accommodation) {
                $this->sendResponse(false, ['Accommodation not found']); 
                return;
            }
            
            $totalRooms = (int)$accommodation['rooms']; //get rooms count
            
            // get booked rooms (only active/confirmed bookings that haven't been completed or cancelled)
            $stmt = $pdo->prepare("
                SELECT COALESCE(SUM(number_of_rooms), 0) as booked_rooms 
                FROM bookings 
                WHERE accommodation_id = ? 
                AND booking_status IN ('confirmed', 'pending')
                AND checkout_date >= CURDATE() 
            ");
            //'booked_rooms' is an alias to represent the result of the query
            $stmt->execute([$id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            $bookedRooms = (int)$result['booked_rooms'];
            $availableRooms = max(0, $totalRooms - $bookedRooms);
            
            $this->sendResponse(true, [], [
                'total_rooms' => $totalRooms,
                'available_rooms' => $availableRooms,
                'unavailable_rooms' => $bookedRooms
            ]);
            
        } catch (\Exception $e) {
            error_log("Error getting room availability: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to get room availability']);
        }
    }

//admin methods -> approveByAdmin() , rejectByAdmin() , deleteByAdmin()
    public function approveByAdmin() { //set accommodatino status to active by admin
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }

        //check user is logged in or user role is admin
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') { //check admin authorization
            $this->sendResponse(false, ['Unauthorized']);
        }

        $id = $_POST['id'] ?? null; //get ID from request
        if (!$id) {
            $this->sendResponse(false, ['Accommodation ID not provided']);
        }

        try {
            $stmt = $pdo->prepare("UPDATE accommodations SET status = 'active', updated_at = NOW() WHERE id = ?");//updatet status and updated time
            $result = $stmt->execute([$id]);

            if ($result && $stmt->rowCount() > 0) {//check whether query excuted succesfuly /at least 1 row updated
                $this->sendResponse(true, [], ['status' => 'active']);
            }else{  
                $this->sendResponse(false, ['Failed to approve accommodation']);
            }

        } catch (\Exception $e) {
            error_log("Error approving accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to approve accommodation']);
        }
    }

    public function rejectByAdmin() {//set accommodation status to inactive by admin
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }

        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
            $this->sendResponse(false, ['Unauthorized']);
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->sendResponse(false, ['Accommodation ID not provided']);
        }

        try {
            $stmt = $pdo->prepare("UPDATE accommodations SET status = 'inactive', updated_at = NOW() WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result && $stmt->rowCount() > 0) {
                $this->sendResponse(true, [], ['status' => 'inactive']);
            }else{
                $this->sendResponse(false, ['Failed to reject accommodation']);
            }
        } catch (\Exception $e) {
            error_log("Error rejecting accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to reject accommodation']);
        }
    }

    public function deleteByAdmin() {//deletes accommodation and all related data (images, amenities)
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }

        if (!isset($_SESSION['user']) || (($_SESSION['user']['role'] ?? '') !== 'admin')) {
            $this->sendResponse(false, ['Unauthorized']);
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->sendResponse(false, ['Accommodation ID not provided']);
        }

        try {
            $pdo->beginTransaction();

            $imgStmt = $pdo->prepare("SELECT image_path FROM accommodation_images WHERE accommodation_id = ?");
            $imgStmt->execute([$id]);
            $images = $imgStmt->fetchAll(\PDO::FETCH_ASSOC);

            $delImgsStmt = $pdo->prepare("DELETE FROM accommodation_images WHERE accommodation_id = ?");
            $delImgsStmt->execute([$id]);

            $delAmenitiesStmt = $pdo->prepare("DELETE FROM accommodation_amenities WHERE accommodation_id = ?");
            $delAmenitiesStmt->execute([$id]);

            // Disable foreign key checks to prevent constraint violations
            $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

            $delAccommodationStmt = $pdo->prepare("DELETE FROM accommodations WHERE id = ?");
            $result = $delAccommodationStmt->execute([$id]);

            // Re-enable foreign key checks
            $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

            if (!$result || $delAccommodationStmt->rowCount() === 0) {
                $pdo->rollBack();
                $this->sendResponse(false, ['Failed to delete accommodation']);
            }

            $pdo->commit();

            foreach ($images as $image) {
                if (!empty($image['image_path'])) {
                    $filePath = __DIR__ . '/../../../public/' . ltrim($image['image_path'], '/');
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            $this->sendResponse(true, [], ['id' => (int)$id]);
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Error deleting accommodation by admin: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to delete accommodation']);
        }
    }

    // Get all bookings for provider's accommodations
    public function getProviderBookings() {
        global $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log("=== Get Provider Bookings Called ===");
        error_log("Session User: " . print_r($_SESSION['user'] ?? 'NOT SET', true));
        
        if (!isset($_SESSION['user'])) {
            error_log("Unauthorized access to provider bookings - no user session");
            $this->sendResponse(false, ['Unauthorized access - Please log in']);
            return;
        }
        
        try {
            $userId = $_SESSION['user']['id'];
            error_log("Fetching bookings for user_id: " . $userId);
            
            // Get all bookings for this provider's accommodations with accommodation and user details
            $sql = "SELECT 
                        b.*,
                        a.title as accommodation_name,
                        a.property_type,
                        ai.image_path as accommodation_image,
                        CONCAT(u.first_name, ' ', COALESCE(u.last_name, '')) as customer_name,
                        u.email as customer_email,
                        u.phone as customer_phone
                    FROM bookings b
                    INNER JOIN accommodations a ON b.accommodation_id = a.id
                    INNER JOIN users u ON b.user_id = u.id
                    LEFT JOIN accommodation_images ai ON a.id = ai.accommodation_id AND ai.is_main = 1
                    WHERE a.user_id = ?
                    ORDER BY b.created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);
            $bookings = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            error_log("Found " . count($bookings) . " bookings");
            
            $this->sendResponse(true, [], ['bookings' => $bookings]);
            
        } catch (\Exception $e) {
            error_log("Error getting provider bookings: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to get bookings']);
        }
    }
}