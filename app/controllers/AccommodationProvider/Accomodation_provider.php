<?php

require_once __DIR__ . '/../../models/Accommodation.php';
require_once __DIR__ . '/../../../config/database.php';

use App\Models\Accommodation;

class Accomodation_provider extends Controller {
    
    private $uploadDir;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { //initializes PHP session
            session_start();
        }
        
        // check if user is authenticated
        if (!isset($_SESSION['user'])) {
            header('Location: /TravelMate/public/index.php?url=Login');
            exit;
        }
        
        $this->uploadDir = __DIR__ . '/../../../public/uploads/accommodations'; //set up the accommodations folder for storing images
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    // dashboard
    public function newerDashboard() {
        $this->view('accommodation/newerDashboard');
    }

    // property type selection page
    public function propertyListingStart() {
        $this->view('accommodation/propertyListingStart');
    }
    
    // property Listing - Step 1 View
    public function propertyListingStep1() {
        // save property type if coming from selection page
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_type'])) {
            $_SESSION['listing_step1'] = [
                'property_type' => $_POST['property_type'] ?? ''
            ];
        }
        
        // if no session data exists and not POST, initialize empty session
        if (!isset($_SESSION['listing_step1']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['listing_step1'] = [
                'property_type' => '',
                'title' => '',
                'location' => '',
                'address' => '',
                'description' => ''
            ];
        }
        
        // show step 1 form
        $this->view('accommodation/propertyListingStep1');
    }

    // property Listing - Step 2 View
    public function propertyListingStep2() {
        // save step 1 data to session if POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['listing_step1'] = [
                'property_type' => $_POST['property_type'] ?? '',
                'title' => $_POST['title'] ?? '',
                'location' => $_POST['location'] ?? '',
                'address' => $_POST['address'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
        }
        
        // show step 2 form
        $this->view('accommodation/propertyListingStep2');
    }
    
    // save Property - Handle form submission from step 2
    public function saveProperty() {
        global $pdo;

        error_log("SaveProperty Called ");
        error_log("POST Data: " . print_r($_POST, true));
        error_log("FILES Data: " . print_r($_FILES, true));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }

        $userId = $_SESSION['user']['id'];
        
        // get data from both steps
        $step1Data = $_SESSION['listing_step1'] ?? [];
        
        // step 1 data
        $propertyType = $step1Data['property_type'] ?? '';
        $title = $step1Data['title'] ?? '';
        $location = $step1Data['location'] ?? '';
        $address = $step1Data['address'] ?? '';
        $description = $step1Data['description'] ?? '';
        
        // step 2 data - Room details
        $rooms = $_POST['rooms'] ?? 1;
        $bathrooms = $_POST['bathrooms'] ?? 1;
        $maxGuests = $_POST['max_guests'] ?? 2;
        $childrenAllowed = ($_POST['children_allowed'] ?? '1') === '1' ? 1 : 0;
        
        // check-in/out times
        $checkInStart = $_POST['check_in_start'] ?? '';
        $checkInEnd = $_POST['check_in_end'] ?? '';
        $checkOutTime = $_POST['check_out_time'] ?? '';
        
        // house rules
        $smoking = isset($_POST['smoking']) ? 1 : 0;
        $parties = isset($_POST['parties']) ? 1 : 0;
        $pets = $_POST['pets'] ?? 'no';
        
        // contact info
        $contactName = $_POST['contact_name'] ?? '';
        $contactPhone = $_POST['contact_phone'] ?? '';
        $contactEmail = $_POST['contact_email'] ?? '';
        
        // services
        $breakfast = $_POST['breakfast'] ?? 'no';
        $parking = $_POST['parking'] ?? 'no';
        
        // pricing
        $pricePerNight = $_POST['price_per_night'] ?? 0;
        $pricePerGuest = $_POST['price_per_guest'] ?? 0;
        
        // amenities - collect all selected amenities
        $amenities = isset($_POST['amenities']) && is_array($_POST['amenities']) 
            ? json_encode($_POST['amenities']) 
            : json_encode([]);

        // validate required fields
        if (empty($propertyType) || empty($title) || empty($location) || empty($description) || empty($pricePerNight)) {
            $this->sendResponse(false, ['Missing required fields']);
        }

        try {
            $pdo->beginTransaction();
            
            // insert accommodation record
            $sql = "INSERT INTO accommodations (
                user_id, property_type, title, description, location, address,
                rooms, bathrooms, max_guests, children_allowed,
                smoking, parties, pets,
                check_in_start, check_in_end, check_out_time,
                contact_name, contact_phone, contact_email,
                amenities, price_per_night, price_per_guest,
                breakfast, parking, status, created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                ?, ?, 'pending', NOW(), NOW()
            )";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $userId, $propertyType, $title, $description, $location, $address,
                $rooms, $bathrooms, $maxGuests, $childrenAllowed,
                $smoking, $parties, $pets,
                $checkInStart, $checkInEnd, $checkOutTime,
                $contactName, $contactPhone, $contactEmail,
                $amenities, $pricePerNight, $pricePerGuest,
                $breakfast, $parking
            ]);
            
            if (!$result) {
                throw new \Exception("Failed to insert accommodation");
            }
            
            $accommodationId = $pdo->lastInsertId();
            
            error_log("Accommodation ID created: " . $accommodationId);
            error_log("Checking for image uploads...");
            error_log("FILES array: " . print_r($_FILES, true));
            
            // handle image uploads
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                error_log("Images found in FILES array");
                $images = $_FILES['images'];
                $totalFiles = count($images['name']);
                error_log("Total files to upload: " . $totalFiles);
                
                for ($i = 0; $i < $totalFiles; $i++) {
                    error_log("Processing file $i: " . $images['name'][$i] . " (error code: " . $images['error'][$i] . ")");
                    
                    if ($images['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $images['tmp_name'][$i];
                        $originalName = $images['name'][$i];
                        $filePath = $this->saveFile($tmpName, $originalName);
                        
                        if ($filePath) {
                            // set first image as main image
                            $isMain = ($i === 0) ? 1 : 0;
                            error_log("Saving image to DB: $filePath (is_main: $isMain)");
                            $imgSql = "INSERT INTO accommodation_images (accommodation_id, image_path, is_main) VALUES (?, ?, ?)";
                            $imgStmt = $pdo->prepare($imgSql);
                            $imgStmt->execute([$accommodationId, $filePath, $isMain]);
                            error_log("Image saved successfully");
                        } else {
                            error_log("Failed to save file: " . $originalName);
                        }
                    } else {
                        error_log("File $i has upload error: " . $images['error'][$i]);
                    }
                }
            } else {
                error_log("No images found in request");
            }
            
            // save individual amenities to accommodation_amenities table
            if (isset($_POST['amenities']) && is_array($_POST['amenities'])) {
                foreach ($_POST['amenities'] as $amenity) {
                    $amenitySql = "INSERT INTO accommodation_amenities (accommodation_id, amenity_name) VALUES (?, ?)";
                    $amenityStmt = $pdo->prepare($amenitySql);
                    $amenityStmt->execute([$accommodationId, $amenity]);
                }
            }
            
            $pdo->commit();
            
            // clear session data
            unset($_SESSION['listing_step1']);
            
            $this->sendResponse(true, [], ['id' => $accommodationId, 'message' => 'Property listed successfully!']);
            
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log("Error creating accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to create accommodation: ' . $e->getMessage()]);
        }
    }

    // legacy property listing pages (keep for backward compatibility)
    public function accommodationFeatures() {
        $this->view('accommodation/accommodationFeatures');
    }

    public function propertyDetails() {
        $this->view('accommodation/propertyDetails');
    }

    public function bedRoom() {
        $this->view('accommodation/bedRoom');
    }

    public function services() {
        $this->view('accommodation/services');
    }

    public function photoUpload() {
        $this->view('accommodation/photoUpload');
    }

    public function houseRules() {
        $this->view('accommodation/houseRules');
    }

    public function price() {
        $this->view('accommodation/price');
    }

    public function success() {
        $this->view('accommodation/success');
    }

    // property management pages
    public function viewProperty() {
        $this->view('accommodation/viewProperty');
    }

    public function updateProperty() {
        $this->view('accommodation/updateProperty');
    }

    public function deleteProperty() {
        $this->view('accommodation/deleteProperty');
    }

    public function detailsProperty() {
        $this->view('accommodation/detailsProperty');
    }

    public function editPropertyDetails() {
        $this->view('accommodation/editPropertyDetails');
    }

    public function editAccommodationFeatures() {
        $this->view('accommodation/editAccommodationFeatures');
    }

    public function editBedrooms() {
        $this->view('accommodation/editBedrooms');
    }

    public function editServices() {
        $this->view('accommodation/editServices');
    }

    public function editHouseRules() {
        $this->view('accommodation/editHouseRules');
    }

    public function editPrice() {
        $this->view('accommodation/editPrice');
    }

    public function editAvailability() {
        $this->view('accommodation/editAvailability');
    }

    public function photoGallery() {
        $this->view('accommodation/photoGallery');
    }

    public function bookings() {
        $this->view('accommodation/bookings');
    }

    public function setting() {
        $this->view('accommodation/setting');
    }

    // API endpoint for creating property (called from step 2)
    public function createProperty() {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, ['Invalid request method']);
        }

        $userId = $_SESSION['user']['id'];
        
        // get data from both steps
        $step1Data = $_SESSION['listing_step1'] ?? [];
        
        // merge step 1 and step 2 data
        $propertyType = $step1Data['property_type'] ?? $_POST['property_type'] ?? '';
        $title = $step1Data['title'] ?? $_POST['title'] ?? '';
        $location = $step1Data['location'] ?? $_POST['location'] ?? '';
        $address = $step1Data['address'] ?? '';
        $description = $step1Data['description'] ?? $_POST['description'] ?? '';
        
        // step 2 data
        $rooms = $_POST['rooms'] ?? 1;
        $bathrooms = $_POST['bathrooms'] ?? 1;
        $maxGuests = $_POST['max_guests'] ?? 2;
        $childrenAllowed = ($_POST['children_allowed'] ?? 'yes') === 'yes' ? 1 : 0;
        
        // pricing
        $pricePerNight = $_POST['price_per_night'] ?? 0;
        $pricePerGuest = $_POST['price_per_guest'] ?? 0;
        
        // house rules
        $smoking = isset($_POST['smoking']) ? 1 : 0;
        $parties = isset($_POST['parties']) ? 1 : 0;
        $pets = $_POST['pets'] ?? 'no';
        
        // times
        $checkInStart = $_POST['check_in_start'] ?? '';
        $checkInEnd = $_POST['check_in_end'] ?? '';
        $checkOutTime = $_POST['check_out_time'] ?? '';
        
        // services
        $breakfast = $_POST['breakfast'] ?? 'no';
        $parking = $_POST['parking'] ?? 'no';

        // validate required fields
        if (empty($propertyType) || empty($title) || empty($location) || empty($description)) {
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
            'status' => 'active'
        ]);

        try {
            $pdo->beginTransaction(); //it starts a database transaction
            
            // create accommodation record
            $accommodationId = $accommodation->create($pdo);
            
            // handle image uploads
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $images = $_FILES['images'];
                $totalFiles = count($images['name']);
                
                for ($i = 0; $i < $totalFiles; $i++) {
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
            
            // save amenities/features as JSON
            $features = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'feature_') === 0 && $value == '1') {
                    $features[] = str_replace('feature_', '', $key);
                }
            }
            
            if (!empty($features)) {
                // store features in a separate table or as JSON field
                // for now, we'll assume there's a features column or you can store in metadata
            }
            
            $pdo->commit(); //save all changes
            
            // clear session data
            unset($_SESSION['listing_step1']);
            
            $this->sendResponse(true, [], ['id' => $accommodationId]);
            
        } catch (\Exception $e) {
            $pdo->rollBack(); //undo changes
            error_log("Error creating accommodation: " . $e->getMessage());
            $this->sendResponse(false, ['Failed to create accommodation: ' . $e->getMessage()]);
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

    private function sendResponse($success, $errors = [], $data = null) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'errors' => $errors, 'data' => $data]);
        exit;
    }
}
