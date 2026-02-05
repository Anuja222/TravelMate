<?php

class Profilesetting extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // Check if user is logged in
        if (!isset($_SESSION['user']['id'])) {
            header('Location: login');
            exit;
        }
        
        // Load database config and create connection
        require_once '../config/database.php';
        
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $conn = new PDO($dsn, DBUSER, DBPASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user']['id']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Pass user data to view
        $this->view('Traveller/profilesetting', ['user' => $userData]);
    }

    public function update(){
        // Start output buffering to prevent any output before JSON
        ob_start();
        
        // Load necessary files
        require_once '../app/core/config.php';
        require_once '../app/core/Model.php';
        require_once '../app/core/Database.php';
        require_once '../app/models/User.php';
        
        // Check if user is logged in
        if (!isset($_SESSION['user']['id'])) {
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        
        // Get POST data
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $dateOfBirth = $_POST['dateOfBirth'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $country = $_POST['country'] ?? '';
        $city = $_POST['city'] ?? '';
        $timezone = $_POST['timezone'] ?? '';
        $travelStyle = $_POST['travelStyle'] ?? '';
        $budget = $_POST['budget'] ?? '';
        $interests = $_POST['interests'] ?? '';
        
        // Validation
        $errors = [];
        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (!empty($errors)) {
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
            exit;
        }
        
        // Update user in database
        $result = \App\Models\User::updateUser($userId, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'date_of_birth' => $dateOfBirth,
            'gender' => $gender,
            'bio' => $bio,
            'country' => $country,
            'city' => $city,
            'timezone' => $timezone,
            'travel_style' => $travelStyle,
            'budget' => $budget,
            'interests' => $interests
        ]);
        
        if ($result) {
            // Update session data
            $_SESSION['user']['first_name'] = $firstName;
            $_SESSION['user']['last_name'] = $lastName;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['dateOfBirth'] = $dateOfBirth;
            $_SESSION['user']['gender'] = $gender;
            
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
        }
        exit;
    }
}