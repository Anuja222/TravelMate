<?php
namespace App\Controllers;

// Manually include dependencies
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../Validation/Validator.php';
require_once __DIR__ . '/../../../config/database.php';

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
            $this->sendResponse(true, [], ['userId' => $userId, 'role' => $role]);
        } else {
            $this->sendResponse(false, ['general' => 'Registration failed']);
        }
    }

    // Login user
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

        // Set session with user data
        $_SESSION['user'] = [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'] ?? '',
            'phone' => $userData['phone'] ?? '',
            'gender' => $userData['gender'] ?? '',
            'dateOfBirth' => $userData['date_of_birth'] ?? '',
            'role' => $userData['role'],
            'logged_in' => true,
            'login_time' => time()
        ];

        $this->sendResponse(true, [], [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'first_name' => $userData['first_name'],
            'role' => $userData['role']
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
        include __DIR__ . '/../../views/traveller/signup.view.php';
    }

    public function showLogin()
    {
        // Redirect if already logged in
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            header('Location: homet');
            exit;
        }
        include __DIR__ . '/../../views/traveller/login.view.php';
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