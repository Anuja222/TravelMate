<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

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
        SessionHelper::requireAdmin();

        // Get filter parameters
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        
        // Validate filter values
        $allowedRoles = ['', 'traveller', 'accommodation', 'transport'];
        $allowedStatuses = ['', 'active', 'suspended'];
        $role = in_array($_GET['role'] ?? '', $allowedRoles) ? ($_GET['role'] ?? '') : '';
        $status = in_array($_GET['status'] ?? '', $allowedStatuses) ? ($_GET['status'] ?? '') : '';
        
        $filters = [
            'role' => $role,
            'status' => $status,
            'search' => substr(trim($_GET['search'] ?? ''), 0, 100)
        ];

        // Get users data
        $users = $this->userModel->getAllUsers($page, 10, $filters);
        $totalUsers = $this->userModel->getTotalUsers($filters);
        $stats = $this->userModel->getUserStats();

        // Calculate pagination
        $totalPages = ceil($totalUsers / 10);

        // Generate CSRF token for forms
        $csrfToken = SessionHelper::getCsrfToken();

        // Pass data to view
        require_once __DIR__ . '/../views/admin/Users.view.php';
    }

    /**
     * View a single user's details - Traveller
     */
    public function viewTraveller()
    {
        SessionHelper::requireAdmin();
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

        // Get suspension history for this user
        $suspensionHistory = $this->userModel->getSuspensionHistory($userId);

        // Generate CSRF token
        $csrfToken = SessionHelper::getCsrfToken();

        require_once __DIR__ . '/../views/admin/viewtraveller.view.php';
    }

    /**
     * View a single user's details - Provider
     */
    public function viewProvider()
    {
        SessionHelper::requireAdmin();
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

        // Get suspension history for this user
        $suspensionHistory = $this->userModel->getSuspensionHistory($userId);

        // Generate CSRF token
        $csrfToken = SessionHelper::getCsrfToken();

        require_once __DIR__ . '/../views/admin/viewprovider.view.php';
    }

    /**
     * Suspend a user (API endpoint)
     */
    public function suspend()
    {
        header('Content-Type: application/json');

        if (!SessionHelper::requireAdminApi()) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!SessionHelper::requireCsrfApi($input)) return;

        $userId = (int)($input['user_id'] ?? 0);
        $reason = trim($input['reason'] ?? '');

        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'User ID is required']);
            return;
        }

        // Prevent suspending admin accounts
        $user = $this->userModel->getUserById($userId);
        if ($user && $user->role === 'admin') {
            echo json_encode(['success' => false, 'error' => 'Cannot suspend administrator accounts']);
            return;
        }

        $result = $this->userModel->suspendUser($userId, $reason);
        
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

        if (!SessionHelper::requireAdminApi()) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        // Validate CSRF token
        if (!SessionHelper::requireCsrfApi($input)) return;

        $userId = (int)($input['user_id'] ?? 0);

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

        if (!SessionHelper::requireAdminApi()) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        // Validate CSRF token
        if (!SessionHelper::requireCsrfApi($input)) return;

        $userId = (int)($input['user_id'] ?? 0);

        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'User ID is required']);
            return;
        }

        // Prevent admin from deleting themselves
        if (SessionHelper::getUserId() == $userId) {
            echo json_encode(['success' => false, 'error' => 'You cannot delete your own account']);
            return;
        }

        // Get user info before deletion
        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            echo json_encode(['success' => false, 'error' => 'User not found']);
            return;
        }

        // Prevent deleting admin accounts
        if ($user->role === 'admin') {
            echo json_encode(['success' => false, 'error' => 'Cannot delete administrator accounts']);
            return;
        }

        $result = $this->userModel->deleteUser($userId);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete user. Please check server logs.']);
        }
    }

    /**
     * Get user stats (API endpoint)
     */
    public function getStats()
    {
        header('Content-Type: application/json');
        
        if (!SessionHelper::requireAdminApi()) return;

        $stats = $this->userModel->getUserStats();
        echo json_encode(['success' => true, 'data' => $stats]);
    }

    /**
     * Search users (API endpoint)
     */
    public function search()
    {
        header('Content-Type: application/json');

        if (!SessionHelper::requireAdminApi()) return;

        $term = $_GET['term'] ?? '';
        
        if (strlen($term) < 2) {
            echo json_encode(['success' => false, 'error' => 'Search term must be at least 2 characters']);
            return;
        }

        $users = $this->userModel->searchUsers($term);
        echo json_encode(['success' => true, 'data' => $users]);
    }
}
