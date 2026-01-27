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
        // Get all destination categories with statistics
        $categories = $this->destinationModel->getAllCategories();
        
        $data = [
            'categories' => $categories,
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

        // Get all places in this category
        $places = $this->destinationModel->getPlacesByCategory($categoryId);
        
        // Get all categories for dropdown (for moving places)
        $allCategories = $this->destinationModel->getCategoriesForDropdown();

        $data = [
            'category' => $category,
            'places' => $places,
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

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['image'], 'categories');
            if (!$imagePath) {
                echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
                exit;
            }
        }

        $categoryData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
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

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['image'], 'categories');
        }

        $categoryData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
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

        $result = $this->destinationModel->deleteCategory($categoryId, $this->adminId);

        if ($result) {
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
        $name = $data['name'] ?? '';

        if (!$destinationId || empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Category ID and place name are required']);
            exit;
        }

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['image'], 'destinations');
            if (!$imagePath) {
                echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
                exit;
            }
        }

        $placeData = [
            'destination_id' => $destinationId,
            'name' => $name,
            'description' => $data['description'] ?? '',
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
        $name = $data['name'] ?? '';

        if (!$placeId || empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Place ID and name are required']);
            exit;
        }

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['image'], 'destinations');
        }

        $placeData = [
            'name' => $name,
            'description' => $data['description'] ?? '',
            'image' => $imagePath
        ];

        $result = $this->destinationModel->updatePlace($placeId, $placeData);

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

        $result = $this->destinationModel->deletePlace($placeId);

        if ($result) {
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
     * Upload image helper
     */
    private function uploadImage($file, $directory) {
        $uploadDir = __DIR__ . '/../../public/uploads/' . $directory . '/';
        
        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $uploadPath = $uploadDir . $fileName;

        // Move file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return '/uploads/' . $directory . '/' . $fileName;
        }

        return null;
    }
}
