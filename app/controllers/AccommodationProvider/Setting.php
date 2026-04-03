<?php

class Setting extends Controller {
    public function index() {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: login');
            exit;
        }
        
        require_once __DIR__ . '/../../core/config.php';
        
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $conn = new PDO($dsn, DBUSER, DBPASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user']['id']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->view('accommodation/setting', ['user' => $userData]);
    }

    public function update() {
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        require_once __DIR__ . '/../../core/config.php';
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $conn = new PDO($dsn, DBUSER, DBPASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $userId = $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['firstName'] ?? '';
            $lastName = $_POST['lastName'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $dateOfBirth = $_POST['dateOfBirth'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $bio = $_POST['bio'] ?? '';
            $country = $_POST['country'] ?? '';
            $city = $_POST['city'] ?? '';

            $profileImagePath = null;
            if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
                // Handle file upload
                $uploadDir = __DIR__ . '/../../../public/uploads/profile_images/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['profilePhoto']['name']);
                $targetFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $targetFilePath)) {
                    $profileImagePath = 'uploads/profile_images/' . $fileName;
                }
            }

            try {
                if ($profileImagePath) {
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, gender = ?, bio = ?, country = ?, city = ?, profile_image = ? WHERE id = ?");
                    $stmt->execute([$firstName, $lastName, $email, $phone, $dateOfBirth, $gender, $bio, $country, $city, $profileImagePath, $userId]);
                    $_SESSION['user']['profile_image'] = $profileImagePath;
                } else {
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, gender = ?, bio = ?, country = ?, city = ? WHERE id = ?");
                    $stmt->execute([$firstName, $lastName, $email, $phone, $dateOfBirth, $gender, $bio, $country, $city, $userId]);
                }
                
                $_SESSION['user']['first_name'] = $firstName;
                $_SESSION['user']['last_name'] = $lastName;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['dateOfBirth'] = $dateOfBirth;
                $_SESSION['user']['gender'] = $gender;
                $_SESSION['user']['bio'] = $bio;
                $_SESSION['user']['country'] = $country;
                $_SESSION['user']['city'] = $city;

                echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
                exit;
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
                exit;
            }
        }
    }
}
