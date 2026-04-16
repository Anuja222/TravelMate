<?php
namespace App\Models;

require_once __DIR__ . '/../../config/database.php';

class bookingTrans
{
    public $id;
    public $bookingCode;
    public $userId;
    public $vehicleId;
    public $vehicleType;
    public $serviceType;
    public $pickupDatetime;
    public $returnDatetime;
    public $pickupLocation;
    public $dropoffLocation;
    public $passengers;
    public $luggage;
    public $specialRequirements;
    public $totalPrice;
    public $dailyRate;
    public $status;
    public $paymentStatus;
    public $paymentMethod;
    public $transactionId;
    public $rejectionReason;
    public $cancellationReason;
    public $cancelledBy;
    public $acceptedAt;
    public $rejectedAt;
    public $cancelledAt;
    public $completedAt;
    public $driverId;
    public $driverNotes;
    public $actualPickup;
    public $actualDropoff;
    public $actualDistance;
    public $actualDuration;
    public $customerRating;
    public $customerFeedback;

    public function __construct($data = [])
    {
        foreach ($data as $k => $v) {
            $prop = lcfirst($k);
            if (property_exists($this, $prop)) $this->$prop = $v;
        }
    }

    // CREATE - Create new booking
    public function create($conn)
    {
        // Generate booking code
        $this->bookingCode = 'BK' . date('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        $sql = "INSERT INTO transport_bookings (
            booking_code, user_id, vehicle_id, vehicle_type, service_type,
            pickup_datetime, return_datetime, pickup_location, dropoff_location,
            passengers, luggage, special_requirements, total_price, daily_rate,
            status, payment_status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            $this->bookingCode,
            $this->userId,
            $this->vehicleId,
            $this->vehicleType,
            $this->serviceType,
            $this->pickupDatetime,
            $this->returnDatetime,
            $this->pickupLocation,
            $this->dropoffLocation,
            $this->passengers,
            $this->luggage,
            $this->specialRequirements,
            $this->totalPrice,
            $this->dailyRate,
            $this->status ?? 'pending',
            $this->paymentStatus ?? 'pending'
        ]);
        
        if (!$result) {
            throw new \Exception('Failed to create booking: ' . implode(', ', $stmt->errorInfo()));
        }
        
        $this->id = $conn->lastInsertId();
        return $this->id;
    }

    // READ - Get booking by ID
    public static function findById($conn, $id)
    {
        $sql = "SELECT b.*, 
                u.first_name, u.last_name, u.email, u.phone,
                v.vehicle_model, v.vehicle_number, v.user_id as provider_id,
                d.name as driver_name, d.phone as driver_phone
                FROM transport_bookings b
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN vehicles v ON b.vehicle_id = v.id
                LEFT JOIN drivers d ON b.driver_id = d.id
                WHERE b.id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // READ - Get booking by code
    public static function findByCode($conn, $code)
    {
        $sql = "SELECT b.*, 
                u.first_name, u.last_name, u.email, u.phone,
                v.vehicle_model, v.vehicle_number, v.user_id as provider_id
                FROM transport_bookings b
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN vehicles v ON b.vehicle_id = v.id
                WHERE b.booking_code = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // READ - Get bookings by user
    public static function findByUser($conn, $userId, $limit = null)
    {
        $sql = "SELECT b.*, v.vehicle_model, v.vehicle_number 
                FROM transport_bookings b
                LEFT JOIN vehicles v ON b.vehicle_id = v.id
                WHERE b.user_id = ?
                ORDER BY b.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId, $limit]);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
        }
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // READ - Get bookings by transporter (vehicle owner)
    public static function findByTransporter($conn, $transporterId, $status = null)
    {
        $sql = "SELECT b.*, 
                u.first_name, u.last_name, u.email, u.phone,
                v.vehicle_model, v.vehicle_number
                FROM transport_bookings b
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN vehicles v ON b.vehicle_id = v.id
                WHERE v.user_id = ?";
        
        $params = [$transporterId];
        
        if ($status) {
            $sql .= " AND b.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // UPDATE - Update booking
    public function update($conn)
    {
        $sql = "UPDATE transport_bookings SET 
                service_type = ?,
                pickup_datetime = ?,
                return_datetime = ?,
                pickup_location = ?,
                dropoff_location = ?,
                passengers = ?,
                luggage = ?,
                special_requirements = ?,
                total_price = ?,
                daily_rate = ?,
                updated_at = NOW()
                WHERE id = ? AND user_id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $this->serviceType,
            $this->pickupDatetime,
            $this->returnDatetime,
            $this->pickupLocation,
            $this->dropoffLocation,
            $this->passengers,
            $this->luggage,
            $this->specialRequirements,
            $this->totalPrice,
            $this->dailyRate,
            $this->id,
            $this->userId
        ]);
    }

    // UPDATE - Update booking status
    public static function updateStatus($conn, $id, $status, $reason = null, $cancelledBy = null)
    {
        $sql = "UPDATE transport_bookings SET 
                status = ?,
                updated_at = NOW()";
        
        $params = [$status];
        
        if ($status === 'accepted') {
            $sql .= ", accepted_at = NOW()";
        } elseif ($status === 'rejected') {
            $sql .= ", rejection_reason = ?, rejected_at = NOW()";
            $params[] = $reason;
        } elseif ($status === 'cancelled') {
            $sql .= ", cancellation_reason = ?, cancelled_by = ?, cancelled_at = NOW()";
            $params[] = $reason;
            $params[] = $cancelledBy;
        } elseif ($status === 'completed') {
            $sql .= ", completed_at = NOW()";
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute($params);
    }

    // UPDATE - Assign driver to booking
    public static function assignDriver($conn, $bookingId, $driverId, $notes = null)
    {
        $sql = "UPDATE transport_bookings SET 
                driver_id = ?,
                driver_notes = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$driverId, $notes, $bookingId]);
    }

    // UPDATE - Mark as picked up
    public static function markAsPickedUp($conn, $bookingId, $actualTime = null)
    {
        $sql = "UPDATE transport_bookings SET 
                actual_pickup = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $actualTime = $actualTime ?? date('Y-m-d H:i:s');
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$actualTime, $bookingId]);
    }

    // UPDATE - Mark as completed
    public static function markAsCompleted($conn, $bookingId, $actualDropoff = null, $distance = null, $duration = null)
    {
        $sql = "UPDATE transport_bookings SET 
                actual_dropoff = ?,
                actual_distance = ?,
                actual_duration = ?,
                status = 'completed',
                completed_at = NOW(),
                updated_at = NOW()
                WHERE id = ?";
        
        $actualDropoff = $actualDropoff ?? date('Y-m-d H:i:s');
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$actualDropoff, $distance, $duration, $bookingId]);
    }

    // UPDATE - Add customer rating
    public static function addRating($conn, $bookingId, $rating, $feedback = null)
    {
        $sql = "UPDATE transport_bookings SET 
                customer_rating = ?,
                customer_feedback = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$rating, $feedback, $bookingId]);
    }

    // UPDATE - Update payment status
    public static function updatePaymentStatus($conn, $bookingId, $status, $method = null, $transactionId = null)
    {
        $sql = "UPDATE transport_bookings SET 
                payment_status = ?,
                payment_method = ?,
                transaction_id = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$status, $method, $transactionId, $bookingId]);
    }

    // DELETE - Delete booking
    public static function delete($conn, $id, $userId)
    {
        // Only allow deletion if booking is pending and belongs to user
        $sql = "DELETE FROM transport_bookings 
                WHERE id = ? AND user_id = ? AND status = 'pending'";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }

    // Count bookings by status
    public static function countByStatus($conn, $transporterId = null, $status = null)
    {
        if ($transporterId) {
            $sql = "SELECT COUNT(*) as count 
                    FROM transport_bookings b
                    JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE v.user_id = ?";
            
            $params = [$transporterId];
            
            if ($status) {
                $sql .= " AND b.status = ?";
                $params[] = $status;
            }
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
        } else {
            if ($status) {
                $sql = "SELECT COUNT(*) as count FROM transport_bookings WHERE status = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$status]);
            } else {
                $sql = "SELECT COUNT(*) as count FROM transport_bookings";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }
        }
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Search bookings
    public static function search($conn, $searchTerm, $userId = null, $transporterId = null)
    {
        $sql = "SELECT b.*, 
                u.first_name, u.last_name, u.email,
                v.vehicle_model, v.vehicle_number
                FROM transport_bookings b
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN vehicles v ON b.vehicle_id = v.id
                WHERE (b.booking_code LIKE ? OR 
                      b.pickup_location LIKE ? OR 
                      b.dropoff_location LIKE ? OR
                      u.first_name LIKE ? OR 
                      u.last_name LIKE ?)";
        
        $params = [
            "%$searchTerm%",
            "%$searchTerm%", 
            "%$searchTerm%",
            "%$searchTerm%",
            "%$searchTerm%"
        ];
        
        if ($userId) {
            $sql .= " AND b.user_id = ?";
            $params[] = $userId;
        }
        
        if ($transporterId) {
            $sql .= " AND v.user_id = ?";
            $params[] = $transporterId;
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Filter bookings
    public static function filter($conn, $filters = [])
    {
        $sql = "SELECT b.*, 
                u.first_name, u.last_name, u.email,
                v.vehicle_model, v.vehicle_number
                FROM transport_bookings b
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN vehicles v ON b.vehicle_id = v.id
                WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['status'])) {
            $sql .= " AND b.status = ?";
            $params[] = $filters['status'];
        }
        
        if (isset($filters['start_date'])) {
            $sql .= " AND DATE(b.pickup_datetime) >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (isset($filters['end_date'])) {
            $sql .= " AND DATE(b.pickup_datetime) <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (isset($filters['vehicle_type'])) {
            $sql .= " AND b.vehicle_type = ?";
            $params[] = $filters['vehicle_type'];
        }
        
        if (isset($filters['user_id'])) {
            $sql .= " AND b.user_id = ?";
            $params[] = $filters['user_id'];
        }
        
        if (isset($filters['transporter_id'])) {
            $sql .= " AND v.user_id = ?";
            $params[] = $filters['transporter_id'];
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 