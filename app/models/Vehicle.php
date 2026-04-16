<?php
namespace App\Models;

class Vehicle
{
    public $id;
    public $userId;
    public $vehicleType;
    public $workingDistrict;
    public $passengerCount;
    public $acType;
    public $model;
    public $year;
    public $color;
    public $number;
    public $costPerKm;
    public $status;

    public function __construct($data = [])
    {
        foreach ($data as $k => $v) {
            $prop = lcfirst($k);
            if (property_exists($this, $prop)) $this->$prop = $v;
        }
    }

    public function create($conn)
    {
        $sql = "INSERT INTO vehicles (user_id, vehicle_type, working_district, passenger_count, ac_type, vehicle_model, vehicle_year, vehicle_color, vehicle_number, cost_per_km, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            $this->userId,
            $this->vehicleType,
            $this->workingDistrict,
            $this->passengerCount,
            $this->acType,
            $this->model,
            $this->year,
            $this->color,
            $this->number,
            $this->costPerKm,
            $this->status ?? 'pending'
        ]);
        
        if (!$result) {
            throw new \Exception('Failed to insert vehicle');
        }
        
        return $conn->lastInsertId();
    }

    public function update($conn)
    {
        $sql = "UPDATE vehicles SET 
                vehicle_type = ?, 
                working_district = ?, 
                passenger_count = ?, 
                ac_type = ?, 
                vehicle_model = ?, 
                vehicle_year = ?, 
                vehicle_color = ?, 
                vehicle_number = ?, 
                cost_per_km = ?,
                status = ?,
                updated_at = NOW()
                WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            $this->vehicleType,
            $this->workingDistrict,
            $this->passengerCount,
            $this->acType,
            $this->model,
            $this->year,
            $this->color,
            $this->number,
            $this->costPerKm,
            $this->status ?? 'pending',
            $this->id,
            $this->userId
        ]);
        
        return $result;
    }

    public static function deleteById($conn, $id, $userId)
    {
        // first delete related documents
        $sql = "DELETE FROM vehicle_documents WHERE vehicle_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        
        // then delete the vehicle
        $sql = "DELETE FROM vehicles WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }

    public static function findByUser($conn, $userId)
    {
        if (self::hasTransportRatingsTable($conn)) {
            $sql = "SELECT v.*, u.first_name, u.last_name, u.email, u.phone, u.profile_image, 
                    COALESCE((SELECT ROUND(AVG(tbr.rating), 1) FROM transport_booking_ratings tbr WHERE tbr.vehicle_id = v.id), 0) AS avg_rating,
                    COALESCE((SELECT COUNT(*) FROM transport_booking_ratings tbr WHERE tbr.vehicle_id = v.id), 0) AS rating_count
                    FROM vehicles v LEFT JOIN users u ON v.user_id = u.id
                    WHERE v.user_id = ?
                    ORDER BY v.created_at DESC";
        } else {
            $sql = "SELECT v.*, u.first_name, u.last_name, u.email, u.phone, u.profile_image, 0 AS avg_rating, 0 AS rating_count
                    FROM vehicles v LEFT JOIN users u ON v.user_id = u.id
                    WHERE v.user_id = ?
                    ORDER BY v.created_at DESC";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findAll($conn)
    {
        if (self::hasTransportRatingsTable($conn)) {
            $sql = "SELECT v.*, u.first_name, u.last_name, u.email, u.phone, u.profile_image, 
                    COALESCE((SELECT ROUND(AVG(tbr.rating), 1) FROM transport_booking_ratings tbr WHERE tbr.vehicle_id = v.id), 0) AS avg_rating,
                    COALESCE((SELECT COUNT(*) FROM transport_booking_ratings tbr WHERE tbr.vehicle_id = v.id), 0) AS rating_count
                    FROM vehicles v LEFT JOIN users u ON v.user_id = u.id
                    WHERE v.status = 'active'
                    ORDER BY v.created_at DESC";
        } else {
            $sql = "SELECT v.*, u.first_name, u.last_name, u.email, u.phone, u.profile_image, 0 AS avg_rating, 0 AS rating_count
                    FROM vehicles v LEFT JOIN users u ON v.user_id = u.id
                    WHERE v.status = 'active'
                    ORDER BY v.created_at DESC";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findAllForAdmin($conn)
    {
        if (self::hasTransportRatingsTable($conn)) {
            $sql = "SELECT v.*, u.first_name, u.last_name, u.email, u.phone, u.profile_image, 
                    COALESCE((SELECT ROUND(AVG(tbr.rating), 1) FROM transport_booking_ratings tbr WHERE tbr.vehicle_id = v.id), 0) AS avg_rating,
                    COALESCE((SELECT COUNT(*) FROM transport_booking_ratings tbr WHERE tbr.vehicle_id = v.id), 0) AS rating_count
                    FROM vehicles v LEFT JOIN users u ON v.user_id = u.id
                    ORDER BY v.created_at DESC";
        } else {
            $sql = "SELECT v.*, u.first_name, u.last_name, u.email, u.phone, u.profile_image, 0 AS avg_rating, 0 AS rating_count
                    FROM vehicles v LEFT JOIN users u ON v.user_id = u.id
                    ORDER BY v.created_at DESC";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findById($conn, $id, $userId = null)
    {
        if ($userId) {
            $sql = "SELECT * FROM vehicles WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id, $userId]);
        } else {
            $sql = "SELECT * FROM vehicles WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
        }
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // document helpers
    public static function addDocument($conn, $vehicleId, $docType, $filePath)
    {
        $sql = "INSERT INTO vehicle_documents (vehicle_id, doc_type, file_path, created_at) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$vehicleId, $docType, $filePath]);
    }

    public static function getDocuments($conn, $vehicleId)
    {
        $sql = "SELECT * FROM vehicle_documents WHERE vehicle_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$vehicleId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function deleteDocument($conn, $id)
    {
        $sql = "DELETE FROM vehicle_documents WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    private static function hasTransportRatingsTable($conn)
    {
        try {
            $tableCheck = $conn->query("SHOW TABLES LIKE 'transport_booking_ratings'");
            return $tableCheck && $tableCheck->fetch(\PDO::FETCH_NUM);
        } catch (\Exception $e) {
            return false;
        }
    }
}