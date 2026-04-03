<?php
namespace App\Models;

class User
{
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $dateOfBirth;
    public $gender;
    public $password;
    public $role;

    public function __construct($firstName, $lastName, $email, $phone, $dateOfBirth, $gender, $password)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->password = $password;
    }

    public function createUser($conn)
    {
        try {
            $sql = "INSERT INTO users (first_name, last_name, email, phone, date_of_birth, gender, password, role, account_status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())";
            $stmt = $conn->prepare($sql);
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->execute([
                $this->firstName,
                $this->lastName,
                $this->email,
                $this->phone,
                $this->dateOfBirth,
                $this->gender,
                $hashedPassword,
                $this->role ?? 'traveller'
            ]);
            return $conn->lastInsertId(); // Return the new user's ID
        } catch (\PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }

    // FIXED: Always return account_status with COALESCE
    public static function findUserByEmail($conn, $email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Ensure account_status is set - NULL defaults to 'active'
        if ($user && (empty($user['account_status']) || $user['account_status'] === null)) {
            $user['account_status'] = 'active';
        }
        
        return $user;
    }

    public static function updateUser($userId, $data)
    {
        try {
            // Load database connection
            require_once '../config/database.php';
            
            // Create PDO connection directly
            $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
            $conn = new \PDO($dsn, DBUSER, DBPASS);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            // Build update query dynamically based on provided data
            $updates = [];
            $params = [];
            
            $allowedFields = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'bio', 'country', 'city', 'timezone', 'travel_style', 'budget', 'interests', 'account_status'];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $updates[] = "$key = ?";
                    $params[] = $value;
                }
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $params[] = $userId; // Add user ID at the end for WHERE clause
            
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute($params);
            
            return $result;
        } catch (\PDOException $e) {
            error_log("User update error: " . $e->getMessage());
            return false;
        }
    }

    public static function updateAccountStatus($conn, $userId, $status, $reason = null, $feedback = null)
    {
        try {
            $sql = "UPDATE users SET 
                    account_status = ?,
                    account_deactivated_at = CASE WHEN ? = 'deactivated' THEN NOW() ELSE NULL END,
                    account_reactivated_at = CASE WHEN ? = 'active' AND account_status = 'deactivated' THEN NOW() ELSE NULL END,
                    account_deactivation_reason = ?,
                    account_deactivation_feedback = ?
                    WHERE id = ?";
            
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$status, $status, $status, $reason, $feedback, $userId]);
        } catch (\PDOException $e) {
            error_log("Account status update error: " . $e->getMessage());
            return false;
        }
    }

    public static function getAccountStatus($conn, $userId)
    {
        $sql = "SELECT account_status, account_deactivated_at, account_deactivation_reason 
                FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}