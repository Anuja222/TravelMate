<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../models/Destination.php';

class AdminDestinationController {
    
    private $destinationModel;
    private $adminId;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->destinationModel = new Destination();
        $this->adminId = $_SESSION['user_id'] ?? 1;
    }

    /**
     * Display main destinations page with all category cards
     */
    public function index() {
        // Get pagination and search parameters
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $perPage = 8; // 2 rows x 4 columns
        
        // Get categories with pagination and search
        $result = $this->destinationModel->getAllCategories($page, $perPage, $search);
        
        $data = [
            'categories' => $result['data'],
            'pagination' => [
                'total' => $result['total'],
                'pages' => $result['pages'],
                'current_page' => $result['current_page'],
                'per_page' => $result['per_page']
            ],
            'search' => $search,
            'pageTitle' => 'Destination Categories'
        ];
        
        require_once __DIR__ . '/../views/admin/destinations/index.view.php';
    }

    /**
     * View places for a specific category
     */
    public function viewCategory() {
        $categoryId = $_GET['id'] ?? null;
        
        if (!$categoryId) {
            $_SESSION['error'] = 'Category ID required';
            header('Location: ' . ROOT . '/admin/destinations');
            exit;
        }

        // Get category details
        $category = $this->destinationModel->getCategoryById($categoryId);
        
        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            header('Location: ' . ROOT . '/admin/destinations');
            exit;
        }

        // Get pagination and search parameters
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $perPage = 12;
        
        // Get places with pagination and search
        $result = $this->destinationModel->getPlacesByCategoryPaginated($categoryId, $page, $perPage, $search);
        
        // Get all categories for dropdown (for moving places)
        $allCategories = $this->destinationModel->getCategoriesForDropdown();

        $data = [
            'category' => $category,
            'places' => $result['data'],
            'pagination' => [
                'total' => $result['total'],
                'pages' => $result['pages'],
                'current_page' => $result['current_page'],
                'per_page' => $result['per_page']
            ],
            'search' => $search,
            'allCategories' => $allCategories,
            'pageTitle' => $category->name . ' - Places'
        ];
        
        require_once __DIR__ . '/../views/admin/destinations/view_category.view.php';
    }

    /**
     * Add new destination category (API)
     */
    public function addCategory() {
        header('Content-Type: application/json');

        // Handle FormData
        if (!empty($_POST)) {
            $data = $_POST;
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if (empty($data['name'])) {
            echo json_encode(['success' => false, 'message' => 'Category name is required']);
            exit;
        }
        
        // Sanitize and validate name
        $name = trim($data['name']);
        if (strlen($name) < 2 || strlen($name) > 100) {
            echo json_encode(['success' => false, 'message' => 'Category name must be between 2 and 100 characters']);
            exit;
        }
        
        // Check for duplicate category name
        if ($this->categoryNameExists($name)) {
            echo json_encode(['success' => false, 'message' => 'A category with this name already exists']);
            exit;
        }

        // Handle image upload with validation
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadImage($_FILES['image'], 'categories');
            if (!$uploadResult['success']) {
                echo json_encode(['success' => false, 'message' => $uploadResult['error']]);
                exit;
            }
            $imagePath = $uploadResult['path'];
        }

        $categoryData = [
            'name' => $name,
            'description' => trim($data['description'] ?? ''),
            'image' => $imagePath,
            'created_by' => $this->adminId
        ];

        $categoryId = $this->destinationModel->createCategory($categoryData);

        if ($categoryId) {
            echo json_encode([
                'success' => true, 
                'message' => 'Category created successfully',
                'category_id' => $categoryId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create category']);
        }
    }

    /**
     * Update category (API)
     */
    public function updateCategory() {
        header('Content-Type: application/json');

        if (!empty($_POST)) {
            $data = $_POST;
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
        }

        $categoryId = $data['id'] ?? null;

        if (!$categoryId || empty($data['name'])) {
            echo json_encode(['success' => false, 'message' => 'Category ID and name are required']);
            exit;
        }
        
        // Sanitize and validate name
        $name = trim($data['name']);
        if (strlen($name) < 2 || strlen($name) > 100) {
            echo json_encode(['success' => false, 'message' => 'Category name must be between 2 and 100 characters']);
            exit;
        }
        
        // Check for duplicate category name (excluding current category)
        if ($this->categoryNameExists($name, $categoryId)) {
            echo json_encode(['success' => false, 'message' => 'A category with this name already exists']);
            exit;
        }

        // Handle image upload with validation
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadImage($_FILES['image'], 'categories');
            if (!$uploadResult['success']) {
                echo json_encode(['success' => false, 'message' => $uploadResult['error']]);
                exit;
            }
            $imagePath = $uploadResult['path'];
            
            // Delete old image if new one is uploaded
            $oldCategory = $this->destinationModel->getCategoryById($categoryId);
            if ($oldCategory && !empty($oldCategory->image)) {
                $this->deleteImageFile($oldCategory->image);
            }
        }

        $categoryData = [
            'name' => $name,
            'description' => trim($data['description'] ?? ''),
            'image' => $imagePath
        ];

        $result = $this->destinationModel->updateCategory($categoryId, $categoryData);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update category']);
        }
    }

    /**
     * Delete category (API)
     */
    public function deleteCategory() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $categoryId = $data['id'] ?? null;

        if (!$categoryId) {
            echo json_encode(['success' => false, 'message' => 'Category ID required']);
            exit;
        }

        // Check if category has places
        if ($this->destinationModel->categoryHasPlaces($categoryId)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Cannot delete category with existing places. Delete all places first.'
            ]);
            exit;
        }
        
        // Get category details to delete image
        $category = $this->destinationModel->getCategoryById($categoryId);

        $result = $this->destinationModel->deleteCategory($categoryId, $this->adminId);

        if ($result) {
            // Delete the image file from server
            if ($category && !empty($category->image)) {
                $this->deleteImageFile($category->image);
            }
            echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete category']);
        }
    }

    /**
     * Get category details (API)
     */
    public function getCategory() {
        header('Content-Type: application/json');

        $categoryId = $_GET['id'] ?? null;

        if (!$categoryId) {
            echo json_encode(['success' => false, 'message' => 'Category ID required']);
            exit;
        }

        $category = $this->destinationModel->getCategoryById($categoryId);

        if ($category) {
            echo json_encode(['success' => true, 'category' => $category]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
        }
    }

    /**
     * Add place to category (API)
     */
    public function addPlace() {
        header('Content-Type: application/json');

        // Handle FormData
        if (!empty($_POST)) {
            $data = $_POST;
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
        }

        $destinationId = $data['destination_id'] ?? null;
        $name = trim($data['name'] ?? '');

        if (!$destinationId || empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Category ID and place name are required']);
            exit;
        }
        
        // Validate name length
        if (strlen($name) < 2 || strlen($name) > 120) {
            echo json_encode(['success' => false, 'message' => 'Place name must be between 2 and 120 characters']);
            exit;
        }
        
        // Check for duplicate place name in this category
        if ($this->placeNameExists($name, $destinationId)) {
            echo json_encode(['success' => false, 'message' => 'A place with this name already exists in this category']);
            exit;
        }

        // Handle image upload with validation
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadImage($_FILES['image'], 'destinations');
            if (!$uploadResult['success']) {
                echo json_encode(['success' => false, 'message' => $uploadResult['error']]);
                exit;
            }
            $imagePath = $uploadResult['path'];
        }

        $placeData = [
            'destination_id' => $destinationId,
            'name' => $name,
            'description' => trim($data['description'] ?? ''),
            'image' => $imagePath
        ];

        $placeId = $this->destinationModel->addPlace($placeData);

        if ($placeId) {
            echo json_encode([
                'success' => true, 
                'message' => 'Place added successfully',
                'place_id' => $placeId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add place']);
        }
    }

    /**
     * Update place (API)
     */
    public function updatePlace() {
        header('Content-Type: application/json');

        if (!empty($_POST)) {
            $data = $_POST;
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
        }

        $placeId = $data['id'] ?? null;
        $name = trim($data['name'] ?? '');

        if (!$placeId || empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Place ID and name are required']);
            exit;
        }
        
        // Validate name length
        if (strlen($name) < 2 || strlen($name) > 120) {
            echo json_encode(['success' => false, 'message' => 'Place name must be between 2 and 120 characters']);
            exit;
        }
        
        // Get current place to check category and for image deletion
        $currentPlace = $this->destinationModel->getPlaceById($placeId);
        if (!$currentPlace) {
            echo json_encode(['success' => false, 'message' => 'Place not found']);
            exit;
        }
        
        // Check for duplicate place name in this category (excluding current place)
        if ($this->placeNameExists($name, $currentPlace->destination_id, $placeId)) {
            echo json_encode(['success' => false, 'message' => 'A place with this name already exists in this category']);
            exit;
        }

        // Handle image upload with validation
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadImage($_FILES['image'], 'destinations');
            if (!$uploadResult['success']) {
                echo json_encode(['success' => false, 'message' => $uploadResult['error']]);
                exit;
            }
            $imagePath = $uploadResult['path'];
            
            // Delete old image if new one is uploaded
            if (!empty($currentPlace->image)) {
                $this->deleteImageFile($currentPlace->image);
            }
        }

        $placeData = [
            'name' => $name,
            'description' => trim($data['description'] ?? ''),
            'image' => $imagePath
        ];

        $result = $this->destinationModel->updatePlaceById($placeId, $placeData);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Place updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update place']);
        }
    }

    /**
     * Delete place (API)
     */
    public function deletePlace() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $placeId = $data['id'] ?? null;

        if (!$placeId) {
            echo json_encode(['success' => false, 'message' => 'Place ID required']);
            exit;
        }
        
        // Get place details to delete image
        $place = $this->destinationModel->getPlaceById($placeId);

        $result = $this->destinationModel->deletePlace($placeId);

        if ($result) {
            // Delete the image file from server
            if ($place && !empty($place->image)) {
                $this->deleteImageFile($place->image);
            }
            echo json_encode(['success' => true, 'message' => 'Place deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete place']);
        }
    }

    /**
     * Get place details (API)
     */
    public function getPlace() {
        header('Content-Type: application/json');

        $placeId = $_GET['id'] ?? null;

        if (!$placeId) {
            echo json_encode(['success' => false, 'message' => 'Place ID required']);
            exit;
        }

        $place = $this->destinationModel->getPlaceById($placeId);

        if ($place) {
            echo json_encode(['success' => true, 'data' => $place]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Place not found']);
        }
    }

    /**
     * Upload image helper with validation
     * @param array $file - The uploaded file from $_FILES
     * @param string $directory - The subdirectory to store in
     * @return array - ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    private function uploadImage($file, $directory) {
        $result = ['success' => false, 'path' => null, 'error' => null];
        
        $uploadDir = __DIR__ . '/../../public/uploads/' . $directory . '/';
        
        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validate file type using both MIME type and extension
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            $result['error'] = 'Invalid file type. Only JPG, PNG, WebP, and GIF images are allowed.';
            return $result;
        }
        
        if (!in_array($extension, $allowedExtensions)) {
            $result['error'] = 'Invalid file extension. Allowed: jpg, jpeg, png, webp, gif';
            return $result;
        }

        // Validate file size (max 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            $result['error'] = 'File size too large. Maximum size is 5MB. Your file is ' . round($file['size'] / 1024 / 1024, 2) . 'MB';
            return $result;
        }
        
        // Validate minimum file size (must be at least 1KB to be a real image)
        if ($file['size'] < 1024) {
            $result['error'] = 'File is too small. It may be corrupted.';
            return $result;
        }

        // Verify it's a real image
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $result['error'] = 'The file is not a valid image.';
            return $result;
        }

        // Generate unique filename
        $fileName = 'dest_' . uniqid() . '.' . $extension;
        $uploadPath = $uploadDir . $fileName;

        // Move file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $result['success'] = true;
            $result['path'] = '/uploads/' . $directory . '/' . $fileName;
            return $result;
        }

        $result['error'] = 'Failed to save the uploaded file.';
        return $result;
    }
    
    /**
     * Delete image from server
     * @param string $imagePath - The relative path to the image
     * @return bool
     */
    private function deleteImageFile($imagePath) {
        if (empty($imagePath)) {
            return true;
        }
        
        $fullPath = __DIR__ . '/../../public' . $imagePath;
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        
        return true;
    }
    
    /**
     * Check if category name already exists
     * @param string $name - Category name to check
     * @param int|null $excludeId - ID to exclude (for updates)
     * @return bool
     */
    private function categoryNameExists($name, $excludeId = null) {
        return $this->destinationModel->checkCategoryNameExists($name, $excludeId);
    }
    
    /**
     * Check if place name already exists in a category
     * @param string $name - Place name to check
     * @param int $categoryId - Category ID
     * @param int|null $excludeId - ID to exclude (for updates)
     * @return bool
     */
    private function placeNameExists($name, $categoryId, $excludeId = null) {
        return $this->destinationModel->checkPlaceNameExists($name, $categoryId, $excludeId);
    }
}
