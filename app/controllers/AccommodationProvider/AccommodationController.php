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
                
                for ($i = 0; $i < $totalFiles; $i++) { //save images in a loop
                    if ($images['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $images['tmp_name'][$i];
                        $originalName = $images['name'][$i];
                        $filePath = $this->saveFile($tmpName, $originalName);
                        
                        if ($filePath) {
                            // set first image as main image
                            Accommodation::addImage($pdo, $accommodationId, $filePath, $i === 0);
                        }
                    }
                }
            }
            
            $pdo->commit();
            $this->sendResponse(true, [], ['id' => $accommodationId]); //return accommodation ID when success.
            
        } catch (\Exception $e) {
            $pdo->rollBack(); //jump to preveious step
            error_log("Error creating accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to create accommodation']);//send JSON response while creating the error log file
        }
    } 
    //upto now,
    //get data from the form
    //validate data
    //pass to the model
    //save in the DB
    //upload images
    //return JSON response

    // temporary upload endpoint used by client-side uploaders (returns JSON)
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
        
        if (move_uploaded_file($tmpPath, $targetPath)) { //moves uploaded files from temp to permanent location
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
            
            // get main image and all images for each accommodation
            foreach ($accommodations as &$accommodation) {
                $accommodation['main_image'] = Accommodation::getMainImage($pdo, $accommodation['id']);
                $accommodation['images'] = Accommodation::getImages($pdo, $accommodation['id']);
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

    public function toggleStatus() { //changes accommodation status between active and inactive
        global $pdo;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }
        
        if (!isset($_SESSION['user'])) {
            $this->sendResponse(false, ['User not authenticated']);
        }
        
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if (!$id || !$status) {
            $this->sendResponse(false, ['Missing required parameters']);
        }
        
        if (!in_array($status, ['active', 'inactive'])) {
            $this->sendResponse(false, ['Invalid status value']);
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE accommodations SET status = ? WHERE id = ? AND user_id = ?");
            $result = $stmt->execute([$status, $id, $_SESSION['user']['id']]);
            
            if ($result && $stmt->rowCount() > 0) {
                $this->sendResponse(true, [], ['status' => $status]); //vip point
            } else {
                $this->sendResponse(false, ['Failed to update property status']);
            }
        } catch (\Exception $e) {
            error_log("Error toggling accommodation status: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to update property status']);
        }
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

    public function deleteByAdmin() {
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