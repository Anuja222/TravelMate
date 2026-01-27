<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * AdminUserController - Controller for admin user management
 */
class AdminUserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display the users list page
     */
    public function index()
    {
        // Check if admin is logged in (temporarily disabled for development)
        // $this->checkAdminAuth();

        // Get filter parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $filters = [
            'role' => $_GET['role'] ?? '',
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        // Get users data
        $users = $this->userModel->getAllUsers($page, 10, $filters);
        $totalUsers = $this->userModel->getTotalUsers($filters);
        $stats = $this->userModel->getUserStats();

        // Calculate pagination
        $totalPages = ceil($totalUsers / 10);

        // Pass data to view
        require_once __DIR__ . '/../views/admin/Users.view.php';
    }

    /**
     * View a single user's details - Traveller
     */
    public function viewTraveller()
    {
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$userId) {
            header('Location: Users');
            exit;
        }

        $user = $this->userModel->getUserById($userId);
        
        if (!$user) {
            header('Location: Users');
            exit;
        }

        require_once __DIR__ . '/../views/admin/viewtraveller.view.php';
    }

    /**
     * View a single user's details - Provider
     */
    public function viewProvider()
    {
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$userId) {
            header('Location: Users');
            exit;
        }

        $user = $this->userModel->getUserById($userId);
        
        if (!$user) {
            header('Location: Users');
            exit;
        }

        require_once __DIR__ . '/../views/admin/viewprovider.view.php';
    }

    /**
     * Suspend a user (API endpoint)
     */
    public function suspend()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $input['user_id'] ?? 0;

        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'User ID is required']);
            return;
        }

        $result = $this->userModel->suspendUser($userId);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'User suspended successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to suspend user']);
        }
    }

    /**
     * Activate a user (API endpoint)
     */
    public function activate()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $input['user_id'] ?? 0;

        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'User ID is required']);
            return;
        }

        $result = $this->userModel->activateUser($userId);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'User activated successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to activate user']);
        }
    }

    /**
     * Delete a user (API endpoint)
     */
    public function delete()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $input['user_id'] ?? 0;

        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'User ID is required']);
            return;
        }

        $result = $this->userModel->deleteUser($userId);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete user']);
        }
    }

    /**
     * Get user stats (API endpoint)
     */
    public function getStats()
    {
        header('Content-Type: application/json');
        
        $stats = $this->userModel->getUserStats();
        echo json_encode(['success' => true, 'data' => $stats]);
    }

    /**
     * Search users (API endpoint)
     */
    public function search()
    {
        header('Content-Type: application/json');

        $term = $_GET['term'] ?? '';
        
        if (strlen($term) < 2) {
            echo json_encode(['success' => false, 'error' => 'Search term must be at least 2 characters']);
            return;
        }

        $users = $this->userModel->searchUsers($term);
        echo json_encode(['success' => true, 'data' => $users]);
    }

    /**
     * Check if admin is logged in
     */
    private function checkAdminAuth()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: login');
            exit;
        }
    }
}
