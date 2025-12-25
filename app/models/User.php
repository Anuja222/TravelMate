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
            $sql = "INSERT INTO users (first_name, last_name, email, phone, date_of_birth, gender, password, role) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
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
}