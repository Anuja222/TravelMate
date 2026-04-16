<?php

class Tr_setting extends Controller {
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

        $this->view('transpoter/setting', ['user' => $userData]);
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
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, gender = ?, profile_image = ? WHERE id = ?");
                    $stmt->execute([$firstName, $lastName, $email, $phone, $dateOfBirth, $gender, $profileImagePath, $userId]);
                    $_SESSION['user']['profile_image'] = $profileImagePath;
                } else {
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, gender = ? WHERE id = ?");
                    $stmt->execute([$firstName, $lastName, $email, $phone, $dateOfBirth, $gender, $userId]);
                }

                $_SESSION['user']['first_name'] = $firstName;
                $_SESSION['user']['last_name'] = $lastName;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['dateOfBirth'] = $dateOfBirth;
                $_SESSION['user']['gender'] = $gender;

                echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
                exit;
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
                exit;
            }
        }
    }

    public function updatePassword() {
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
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            if (empty($currentPassword) || empty($newPassword)) {
                echo json_encode(['success' => false, 'message' => 'Passwords cannot be empty']);
                exit;
            }

            try {
                // Get current password hash
                $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($currentPassword, $user['password'])) {
                    // Update to new password
                    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updateStmt->execute([$newHash, $userId]);

                    echo json_encode(['success' => true, 'message' => 'Password updated successfully!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Incorrect current password']);
                }
                exit;
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
                exit;
            }
        }
    }
}
