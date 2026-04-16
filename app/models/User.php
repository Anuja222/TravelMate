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
    public $profile_image;

    public function __construct($firstName, $lastName, $email, $phone, $dateOfBirth, $gender, $password, $profile_image = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->password = $password;
        $this->profile_image = $profile_image;
    }

    public function createUser($conn)
    {
        try {
            $sql = "INSERT INTO users (first_name, last_name, email, phone, date_of_birth, gender, password, role, profile_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
                $this->role ?? 'traveller',
                $this->profile_image
            ]);
            return $conn->lastInsertId(); // return the new user's ID
        } catch (\PDOException $e) {
            return false;
        }
    }

    public static function findUserByEmail($conn, $email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function updateUser($userId, $data)
    {
        try {
            // load database connection
            require_once '../config/database.php';
            
            // create PDO connection directly
            $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
            $conn = new \PDO($dsn, DBUSER, DBPASS);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            // build update query dynamically based on provided data
            $updates = [];
            $params = [];
            
            $allowedFields = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'bio', 'country', 'city', 'timezone', 'travel_style', 'budget', 'interests', 'profile_image'];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $updates[] = "$key = ?";
                    $params[] = $value;
                }
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $params[] = $userId; // add user ID at the end for WHERE clause
            
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute($params);
            
            return $result;
        } catch (\PDOException $e) {
            error_log("User update error: " . $e->getMessage());
            return false;
        }
    }
}