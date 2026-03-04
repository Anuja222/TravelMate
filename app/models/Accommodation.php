<?php
namespace App\Models;

class Accommodation {
    public $id;
    public $userId;
    public $propertyType;
    public $title;
    public $description;
    public $location;
    public $address;
    public $rooms;
    public $bathrooms;
    public $maxGuests;
    public $childrenAllowed;
    public $smoking;
    public $parties;
    public $pets;
    public $checkInStart;
    public $checkInEnd;
    public $checkOutTime;
    public $contactName;
    public $contactPhone;
    public $contactEmail;
    public $amenities;
    public $pricePerNight;
    public $pricePerGuest;
    public $breakfast;
    public $parking;
    public $status;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    private static function hasBookingRatingsTable($conn) {
        try {
            $stmt = $conn->query("SHOW TABLES LIKE 'booking_ratings'");
            return $stmt && $stmt->fetch(\PDO::FETCH_NUM);
        } catch (\Exception $e) {
            return false;
        }
    }

    private static function ratingSelectClause($alias = 'a') {
        return "
            COALESCE((
                SELECT ROUND(AVG(br.rating), 1)
                FROM booking_ratings br
                WHERE br.accommodation_id = {$alias}.id
            ), 0) AS avg_rating,
            COALESCE((
                SELECT COUNT(*)
                FROM booking_ratings br
                WHERE br.accommodation_id = {$alias}.id
            ), 0) AS rating_count
        ";
    }

    public function create($conn) {
        $sql = "INSERT INTO accommodations (
            user_id, property_type, title, description, location, address,
            rooms, bathrooms, max_guests, children_allowed,
            smoking, parties, pets, check_in_start, check_in_end,
            check_out_time, contact_name, contact_phone, contact_email,
            amenities, price_per_night, price_per_guest,
            breakfast, parking, status, created_at, updated_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, NOW(), NOW()
        )";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            $this->userId,
            $this->propertyType,
            $this->title,
            $this->description,
            $this->location,
            $this->address,
            $this->rooms,
            $this->bathrooms,
            $this->maxGuests,
            $this->childrenAllowed,
            $this->smoking,
            $this->parties,
            $this->pets,
            $this->checkInStart,
            $this->checkInEnd,
            $this->checkOutTime,
            $this->contactName,
            $this->contactPhone,
            $this->contactEmail,
            $this->amenities,
            $this->pricePerNight,
            $this->pricePerGuest,
            $this->breakfast,
            $this->parking,
            $this->status ?? 'pending'
        ]);

        if (!$result) {
            throw new \Exception("Failed to create accommodation");
        }
        
        return $conn->lastInsertId();
    }

    public static function findByUser($conn, $userId) {
        $ratingSelect = self::hasBookingRatingsTable($conn)
            ? self::ratingSelectClause('a')
            : "0 AS avg_rating, 0 AS rating_count";

        $sql = "SELECT 
                    a.*,
                    (
                        SELECT COUNT(*)
                        FROM bookings b
                        WHERE b.accommodation_id = a.id
                    ) AS bookings_received,
                    (
                        SELECT COALESCE(SUM(COALESCE(b.number_of_rooms, 1)), 0)
                        FROM bookings b
                        WHERE b.accommodation_id = a.id
                          AND b.booking_status IN ('confirmed', 'pending')
                          AND b.checkout_date >= CURDATE()
                    ) AS booked_rooms,
                    {$ratingSelect}
                FROM accommodations a
                WHERE a.user_id = ?
                ORDER BY a.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findAll($conn) {
        $ratingSelect = self::hasBookingRatingsTable($conn)
            ? self::ratingSelectClause('a')
            : "0 AS avg_rating, 0 AS rating_count";

        $sql = "SELECT a.*, {$ratingSelect} FROM accommodations a WHERE a.status = 'active' ORDER BY a.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findAllForAdmin($conn) {
        $ratingSelect = self::hasBookingRatingsTable($conn)
            ? self::ratingSelectClause('a')
            : "0 AS avg_rating, 0 AS rating_count";

        $sql = "SELECT a.*, {$ratingSelect} FROM accommodations a ORDER BY a.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
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
                location = ?,
                address = ?,
                rooms = ?,
                bathrooms = ?,
                max_guests = ?,
                children_allowed = ?,
                smoking = ?,
                parties = ?,
                pets = ?,
                check_in_start = ?,
                check_in_end = ?,
                check_out_time = ?,
                contact_name = ?,
                contact_phone = ?,
                contact_email = ?,
                amenities = ?,
                price_per_night = ?,
                price_per_guest = ?,
                breakfast = ?,
                parking = ?,
                status = ?,
                updated_at = NOW()
                WHERE id = ? AND user_id = ?";
                
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $this->propertyType,
            $this->title,
            $this->description,
            $this->location,
            $this->address,
            $this->rooms,
            $this->bathrooms,
            $this->maxGuests,
            $this->childrenAllowed,
            $this->smoking,
            $this->parties,
            $this->pets,
            $this->checkInStart,
            $this->checkInEnd,
            $this->checkOutTime,
            $this->contactName,
            $this->contactPhone,
            $this->contactEmail,
            $this->amenities,
            $this->pricePerNight,
            $this->pricePerGuest,
            $this->breakfast,
            $this->parking,
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