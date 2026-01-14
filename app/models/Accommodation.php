<?php
namespace App\Models;

class Accommodation {
    public $id;
    public $userId;
    public $propertyType;
    public $title;
    public $description;
    public $location;
    public $rooms;
    public $bathrooms;
    public $maxGuests;
    public $smoking;
    public $parties;
    public $pets;
    public $checkInStart;
    public $checkInEnd;
    public $checkOutTime;
    public $status;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function create($conn) {
        $sql = "INSERT INTO accommodations (
            user_id, property_type, title, description, location,
            rooms, bathrooms, max_guests,
            smoking, parties, pets, check_in_start, check_in_end,
            check_out_time, status, created_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            $this->userId,
            $this->propertyType,
            $this->title,
            $this->description,
            $this->location,
            $this->rooms,
            $this->bathrooms,
            $this->maxGuests,
            $this->smoking,
            $this->parties,
            $this->pets,
            $this->checkInStart,
            $this->checkInEnd,
            $this->checkOutTime,
            $this->status ?? 'active'
        ]);

        if (!$result) {
            throw new \Exception("Failed to create accommodation");
        }
        
        return $conn->lastInsertId();
    }

    public static function findByUser($conn, $userId) {
        $sql = "SELECT * FROM accommodations WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findById($conn, $id, $userId = null) {
        if ($userId) {
            $sql = "SELECT * FROM accommodations WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id, $userId]);
        } else {
            $sql = "SELECT * FROM accommodations WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
        }
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($conn) {
        $sql = "UPDATE accommodations SET 
                property_type = ?,
                title = ?,
                description = ?,
                rooms = ?,
                bathrooms = ?,
                max_guests = ?,
                smoking = ?,
                parties = ?,
                pets = ?,
                check_in_start = ?,
                check_in_end = ?,
                check_out_time = ?,
                status = ?,
                updated_at = NOW()
                WHERE id = ? AND user_id = ?";
                
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $this->propertyType,
            $this->title,
            $this->description,
            $this->rooms,
            $this->bathrooms,
            $this->maxGuests,
            $this->smoking,
            $this->parties,
            $this->pets,
            $this->checkInStart,
            $this->checkInEnd,
            $this->checkOutTime,
            $this->status,
            $this->id,
            $this->userId
        ]);
    }

    public static function deleteById($conn, $id, $userId) {
        // First delete related images
        $sql = "DELETE FROM accommodation_images WHERE accommodation_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        
        // Then delete the accommodation
        $sql = "DELETE FROM accommodations WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }

    public static function addImage($conn, $accommodationId, $imagePath, $isMain = false) {
        $sql = "INSERT INTO accommodation_images (accommodation_id, image_path, is_main) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$accommodationId, $imagePath, $isMain ? 1 : 0]);
    }

    public static function getImages($conn, $accommodationId) {
        $sql = "SELECT * FROM accommodation_images WHERE accommodation_id = ? ORDER BY is_main DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$accommodationId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getMainImage($conn, $accommodationId) {
        $sql = "SELECT image_path FROM accommodation_images WHERE accommodation_id = ? AND is_main = 1 LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$accommodationId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['image_path'] : null;
    }
}