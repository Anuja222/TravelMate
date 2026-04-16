<?php

class Profilesetting extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // check if user is logged in
        if (!isset($_SESSION['user']['id'])) {
            header('Location: login');
            exit;
        }
        
        // load database config and create connection
        require_once __DIR__ . '/../../core/config.php';
        
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $conn = new PDO($dsn, DBUSER, DBPASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user']['id']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // pass user data to view
        $this->view('Traveller/profilesetting', ['user' => $userData]);
    }

    public function update(){
        // start output buffering to prevent any output before JSON
        ob_start();
        
        // load necessary files
        require_once __DIR__ . '/../../core/config.php';
        require_once __DIR__ . '/../../core/Model.php';
        require_once __DIR__ . '/../../core/Database.php';
        require_once __DIR__ . '/../../models/User.php';
        
        // check if user is logged in
        if (!isset($_SESSION['user']['id'])) {
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        
        // get POST data
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
        
        // validation
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
        
        $updateData = [
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
        ];

        // handle profile photo upload or removal
        if (isset($_POST['removePhoto']) && $_POST['removePhoto'] === 'true') {
            $updateData['profile_image'] = '';
        } elseif (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/profile_images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['profilePhoto']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = 'profile_' . $userId . '_' . time() . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;
                
                if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $destination)) {
                    $updateData['profile_image'] = 'uploads/profile_images/' . $newFileName;
                }
            }
        }
        
        // update user in database
        $result = \App\Models\User::updateUser($userId, $updateData);
        
        if ($result) {
            // update session data
            $_SESSION['user']['first_name'] = $firstName;
            $_SESSION['user']['last_name'] = $lastName;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['dateOfBirth'] = $dateOfBirth;
            $_SESSION['user']['gender'] = $gender;
            
            if (isset($updateData['profile_image'])) {
                $_SESSION['user']['profile_image'] = $updateData['profile_image'];
            }
            
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