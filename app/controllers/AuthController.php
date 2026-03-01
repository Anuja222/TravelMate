<?php
namespace App\Controllers;

// Manually include dependencies
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../Validation/Validator.php';
require_once __DIR__ . '/../../config/database.php';

use App\Models\User;
use App\Validation\Validator;

class AuthController
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Register user
    public function registerUser()
    {
        global $pdo;
        $data = $_POST;

        // Validate required fields
        $errors = $this->validator->validateRequiredFields($data, [
            'firstName',
            'lastName',
            'email',
            'phone',
            'dateOfBirth',
            'gender',
            'password',
            'confirmPassword',
        ]);
        if (!empty($errors)) {
            $this->sendResponse(false, $errors);
            return;
        }

        // Email format
        if (!$this->validator->validateEmail($data['email'])) {
            $this->sendResponse(false, ['email' => 'Invalid email format']);
            return;
        }

        // Password match
        if ($data['password'] !== $data['confirmPassword']) {
            $this->sendResponse(false, ['confirmPassword' => 'Passwords do not match']);
            return;
        }

        // Password strength
        if (!$this->validator->validatePassword($data['password'])) {
            $this->sendResponse(false, ['password' => 'Password must be at least 6 characters']);
            return;
        }

        // Email exists
        if (User::findUserByEmail($pdo, $data['email'])) {
            $this->sendResponse(false, ['email' => 'Email already registered']);
            return;
        }

        // Create user
        $user = new User(
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['phone'],
            $data['dateOfBirth'],
            $data['gender'],
            $data['password']
        );
        $role = $data['role'] ?? 'traveller';
        $user->role = $role;
        
        $userId = $user->createUser($pdo);

        if ($userId) {
            // Set default account_status to active for new users
            $updateSql = "UPDATE users SET account_status = 'active' WHERE id = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$userId]);
            
            $this->sendResponse(true, [], ['userId' => $userId, 'role' => $role]);
        } else {
            $this->sendResponse(false, ['general' => 'Registration failed']);
        }
    }

    // Login user - FIXED VERSION with account_status
    public function loginUser()
    {
        global $pdo;
        $data = $_POST;

        $errors = $this->validator->validateRequiredFields($data, ['email', 'password']);
        if (!empty($errors)) {
            $this->sendResponse(false, $errors);
            return;
        }

        $userData = User::findUserByEmail($pdo, $data['email']);
        if (!$userData || !password_verify($data['password'], $userData['password'])) {
            $this->sendResponse(false, ['general' => 'Invalid email or password']);
            return;
        }

        // CRITICAL FIX: Get the account_status from database
        // If it's NULL or empty, set to 'active' as default
        $accountStatus = $userData['account_status'] ?? 'active';
        if (empty($accountStatus)) {
            $accountStatus = 'active';
        }

        // Set session with ALL user data including account_status
        $_SESSION['user'] = [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'] ?? '',
            'phone' => $userData['phone'] ?? '',
            'gender' => $userData['gender'] ?? '',
            'dateOfBirth' => $userData['date_of_birth'] ?? '',
            'role' => $userData['role'],
            'account_status' => $accountStatus, // CRITICAL: Add this line - should be 'active' or 'deactivated'
            'logged_in' => true,
            'login_time' => time()
        ];

        $this->sendResponse(true, [], [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'first_name' => $userData['first_name'],
            'role' => $userData['role'],
            'account_status' => $accountStatus // Also return in response
        ]);
    }

    // Logout user
    public function logoutUser()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header('Location: home');
        exit;
    }

    public function showSignup()
    {
        include __DIR__ . '/../views/traveller/signup.view.php';
    }

    public function showLogin()
    {
        // Redirect if already logged in
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            // Check account status and redirect accordingly
            if ($_SESSION['user']['role'] === 'transport') {
                header('Location: tr_dashboard');
            } else {
                header('Location: homet');
            }
            exit;
        }
        include __DIR__ . '/../views/traveller/login.view.php';
    }

    private function sendResponse($success, $errors = [], $user = null)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'errors' => $errors,
            'user' => $user
        ]);
        exit;
    }
}