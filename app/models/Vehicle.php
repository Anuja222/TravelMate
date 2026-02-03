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
        $sql = "INSERT INTO vehicles (user_id, vehicle_type, working_district, passenger_count, ac_type, vehicle_model, vehicle_year, vehicle_color, vehicle_number, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
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
            $this->status ?? 'active'
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
            $this->status ?? 'active',
            $this->id,
            $this->userId
        ]);
        
        return $result;
    }

    public static function deleteById($conn, $id, $userId)
    {
        // First delete related documents
        $sql = "DELETE FROM vehicle_documents WHERE vehicle_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        
        // Then delete the vehicle
        $sql = "DELETE FROM vehicles WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }

    public static function findByUser($conn, $userId)
    {
        $sql = "SELECT * FROM vehicles WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findAll($conn)
    {
        $sql = "SELECT * FROM vehicles WHERE status = 'active' ORDER BY created_at DESC";
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

    // Document helpers
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
}