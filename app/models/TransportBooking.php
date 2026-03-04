<?php
namespace App\Models;

use PDO;
use Exception;

class TransportBooking {
    private $id;
    private $userId;
    private $bookingId;
    private $vehicleId;
    private $serviceType;
    private $pickupDate;
    private $pickupTime;
    private $returnDate;
    private $returnTime;
    private $pickupLocation;
    private $dropoffLocation;
    private $passengers;
    private $luggage;
    private $specialRequirements;
    private $duration;
    private $basePrice;
    private $serviceCharge;
    private $totalPrice;
    private $bookingStatus;
    private $paymentStatus;
    private $bookingDate;

    public function __construct($data = null) {
        if ($data) {
            $this->id                  = $data['id'] ?? null;
            $this->userId              = $data['user_id'] ?? null;
            $this->bookingId           = $data['booking_id'] ?? null;
            $this->vehicleId           = $data['vehicle_id'] ?? null;
            $this->serviceType         = $data['service_type'] ?? null;
            $this->pickupDate          = $data['pickup_date'] ?? null;
            $this->pickupTime          = $data['pickup_time'] ?? null;
            $this->returnDate          = $data['return_date'] ?? null;
            $this->returnTime          = $data['return_time'] ?? null;
            $this->pickupLocation      = $data['pickup_location'] ?? null;
            $this->dropoffLocation     = $data['dropoff_location'] ?? null;
            $this->passengers          = $data['passengers'] ?? 1;
            $this->luggage             = $data['luggage'] ?? 0;
            $this->specialRequirements = $data['special_requirements'] ?? null;
            $this->duration            = $data['duration'] ?? 1;
            $this->basePrice           = $data['base_price'] ?? 0;
            $this->serviceCharge       = $data['service_charge'] ?? 0;
            $this->totalPrice          = $data['total_price'] ?? 0;
            $this->bookingStatus       = $data['booking_status'] ?? 'pending';
            $this->paymentStatus       = $data['payment_status'] ?? 'pending';
            $this->bookingDate         = $data['booking_date'] ?? date('Y-m-d H:i:s');
        }
    }

    // Create transport booking
    public function createBooking($conn, $data) {
        try {
            error_log('TransportBooking Model: Starting createBooking');
            error_log('Connection type: ' . get_class($conn));
            
            $sql = "INSERT INTO transport_bookings 
                    (user_id, booking_id, vehicle_id, service_type, pickup_date, pickup_time, 
                     return_date, return_time, pickup_location, dropoff_location, passengers, 
                     luggage, special_requirements, duration, base_price, service_charge, 
                     total_price, booking_status, payment_status, booking_date, created_at) 
                    VALUES 
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            error_log('SQL: ' . $sql);
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                error_log('ERROR: Failed to prepare statement');
                error_log('PDO Error: ' . print_r($conn->errorInfo(), true));
                return false;
            }
            
            $result = $stmt->execute([
                $data['user_id'],
                $data['booking_id'],
                $data['vehicle_id'],
                $data['service_type'],
                $data['pickup_date'],
                $data['pickup_time'],
                $data['return_date'],
                $data['return_time'],
                $data['pickup_location'],
                $data['dropoff_location'],
                $data['passengers'],
                $data['luggage'],
                $data['special_requirements'] ?? null,
                $data['duration'],
                $data['base_price'],
                $data['service_charge'],
                $data['total_price'],
                $data['booking_status'] ?? 'pending',
                $data['payment_status'] ?? 'pending',
                $data['booking_date']
            ]);
            
            if (!$result) {
                error_log('ERROR: Execute failed');
                error_log('Statement Error: ' . print_r($stmt->errorInfo(), true));
            } else {
                error_log('SUCCESS: Booking created successfully');
            }
            
            return $result;
        } catch (Exception $e) {
            error_log('Create transport booking error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    // Get all transport bookings by user
    public function getBookingsByUserId($conn, $userId) {
        try {
                    $sql = "SELECT tb.*, v.vehicle_model, v.vehicle_type, v.vehicle_number, v.ac_type, v.passenger_count,
                           (SELECT vd.file_path
                            FROM vehicle_documents vd
                            WHERE vd.vehicle_id = v.id AND vd.doc_type = 'vehicle_photos'
                            ORDER BY vd.id DESC
                            LIMIT 1) AS vehicle_photo
                    FROM transport_bookings tb
                    LEFT JOIN vehicles v ON tb.vehicle_id = v.id
                    WHERE tb.user_id = ? 
                    ORDER BY tb.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Get transport bookings error: ' . $e->getMessage());
            return [];
        }
    }

    public function getBookingsByProviderId($conn, $providerId) {
        try {
            $sql = "SELECT tb.*, v.vehicle_model, v.vehicle_type, v.vehicle_number,
                           u.first_name, u.last_name, u.email
                    FROM transport_bookings tb
                    INNER JOIN vehicles v ON tb.vehicle_id = v.id
                    LEFT JOIN users u ON tb.user_id = u.id
                    WHERE v.user_id = ?
                    ORDER BY tb.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Get provider transport bookings error: ' . $e->getMessage());
            return [];
        }
    }

    // Get single transport booking
    public function getBookingById($conn, $bookingId, $userId) {
        try {
                    $sql = "SELECT tb.*, v.vehicle_model, v.vehicle_type, v.vehicle_number, v.ac_type, v.passenger_count, v.working_district,
                           (SELECT vd.file_path
                            FROM vehicle_documents vd
                            WHERE vd.vehicle_id = v.id AND vd.doc_type = 'vehicle_photos'
                            ORDER BY vd.id DESC
                            LIMIT 1) AS vehicle_photo
                    FROM transport_bookings tb
                    LEFT JOIN vehicles v ON tb.vehicle_id = v.id
                    WHERE tb.booking_id = ? AND tb.user_id = ? 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$bookingId, $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Get transport booking error: ' . $e->getMessage());
            return null;
        }
    }

    // Update booking status
    public function updateBookingStatus($conn, $bookingId, $status, $userId) {
        try {
            $sql = "UPDATE transport_bookings 
                    SET booking_status = ?, updated_at = NOW() 
                    WHERE booking_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$status, $bookingId, $userId]);
        } catch (Exception $e) {
            error_log('Update transport booking status error: ' . $e->getMessage());
            return false;
        }
    }

    public function updateBookingStatusByProvider($conn, $bookingId, $providerId, $status) {
        try {
            $sql = "UPDATE transport_bookings tb
                    INNER JOIN vehicles v ON tb.vehicle_id = v.id
                    SET tb.booking_status = ?, tb.updated_at = NOW()
                    WHERE tb.booking_id = ? AND v.user_id = ? AND tb.booking_status = 'pending'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$status, $bookingId, $providerId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log('Update provider transport booking status error: ' . $e->getMessage());
            return false;
        }
    }

    public function payApprovedBooking($conn, $bookingId, $userId) {
        try {
            $sql = "UPDATE transport_bookings
                    SET payment_status = 'paid', updated_at = NOW()
                    WHERE booking_id = ?
                    AND user_id = ?
                    AND booking_status = 'confirmed'
                    AND payment_status = 'pending'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$bookingId, $userId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log('Pay approved transport booking error: ' . $e->getMessage());
            return false;
        }
    }

    // Update booking details
    public function updateBooking($conn, $bookingId, $userId, $data) {
        try {
            $sql = "UPDATE transport_bookings 
                    SET pickup_date = ?, pickup_time = ?, return_date = ?, return_time = ?, 
                        pickup_location = ?, dropoff_location = ?, passengers = ?, luggage = ?, 
                        special_requirements = ?, duration = ?, base_price = ?, service_charge = ?, 
                        total_price = ?, updated_at = NOW() 
                    WHERE booking_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([
                $data['pickup_date'],
                $data['pickup_time'],
                $data['return_date'],
                $data['return_time'],
                $data['pickup_location'],
                $data['dropoff_location'],
                $data['passengers'],
                $data['luggage'],
                $data['special_requirements'] ?? null,
                $data['duration'],
                $data['base_price'],
                $data['service_charge'],
                $data['total_price'],
                $bookingId,
                $userId
            ]);
        } catch (Exception $e) {
            error_log('Update transport booking error: ' . $e->getMessage());
            return false;
        }
    }

    // Cancel booking
    public function cancelBooking($conn, $bookingId, $userId) {
        try {
            $sql = "UPDATE transport_bookings 
                    SET booking_status = 'cancelled', updated_at = NOW() 
                    WHERE booking_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$bookingId, $userId]);
        } catch (Exception $e) {
            error_log('Cancel transport booking error: ' . $e->getMessage());
            return false;
        }
    }

    // Delete booking
    public function deleteBooking($conn, $bookingId, $userId) {
        try {
            $sql = "DELETE FROM transport_bookings WHERE booking_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$bookingId, $userId]);
        } catch (Exception $e) {
            error_log('Delete transport booking error: ' . $e->getMessage());
            return false;
        }
    }

    // Get bookings by status
    public function getBookingsByStatus($conn, $userId, $status) {
        try {
            $sql = "SELECT tb.*, v.vehicle_model, v.vehicle_type, v.vehicle_number
                    FROM transport_bookings tb
                    LEFT JOIN vehicles v ON tb.vehicle_id = v.id
                    WHERE tb.user_id = ? AND tb.booking_status = ? 
                    ORDER BY tb.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId, $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Get transport bookings by status error: ' . $e->getMessage());
            return [];
        }
    }

    // Get upcoming bookings
    public function getUpcomingBookings($conn, $userId) {
        try {
            $sql = "SELECT tb.*, v.vehicle_model, v.vehicle_type, v.vehicle_number
                    FROM transport_bookings tb
                    LEFT JOIN vehicles v ON tb.vehicle_id = v.id
                    WHERE tb.user_id = ? 
                    AND tb.pickup_date >= CURDATE() 
                    AND tb.booking_status != 'cancelled' 
                    ORDER BY tb.pickup_date ASC, tb.pickup_time ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Get upcoming transport bookings error: ' . $e->getMessage());
            return [];
        }
    }

    // Get past bookings
    public function getPastBookings($conn, $userId) {
        try {
            $sql = "SELECT tb.*, v.vehicle_model, v.vehicle_type, v.vehicle_number
                    FROM transport_bookings tb
                    LEFT JOIN vehicles v ON tb.vehicle_id = v.id
                    WHERE tb.user_id = ? 
                    AND tb.return_date < CURDATE() 
                    ORDER BY tb.return_date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Get past transport bookings error: ' . $e->getMessage());
            return [];
        }
    }

    // Get current bookings (active)
    public function getCurrentBookings($conn, $userId) {
        try {
            $sql = "SELECT tb.*, v.vehicle_model, v.vehicle_type, v.vehicle_number
                    FROM transport_bookings tb
                    LEFT JOIN vehicles v ON tb.vehicle_id = v.id
                    WHERE tb.user_id = ? 
                    AND tb.pickup_date <= CURDATE() 
                    AND tb.return_date >= CURDATE() 
                    AND tb.booking_status = 'confirmed'
                    ORDER BY tb.pickup_date ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Get current transport bookings error: ' . $e->getMessage());
            return [];
        }
    }

    // Get booking statistics
    public function getBookingStats($conn, $userId) {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_bookings,
                        SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                        SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                        SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed,
                        SUM(total_price) as total_spent
                    FROM transport_bookings 
                    WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Get transport booking stats error: ' . $e->getMessage());
            return null;
        }
    }

    // Check vehicle availability
    public static function checkAvailability($conn, $vehicleId, $pickupDate, $returnDate, $excludeBookingId = null) {
        try {
            error_log('=== Checking Vehicle Availability ===');
            error_log('Vehicle ID: ' . $vehicleId);
            error_log('Pickup Date: ' . $pickupDate);
            error_log('Return Date: ' . $returnDate);
            error_log('Exclude Booking ID: ' . ($excludeBookingId ?? 'none'));
            
            $sql = "SELECT COUNT(*) as conflicts 
                    FROM transport_bookings 
                    WHERE vehicle_id = ? 
                    AND booking_status NOT IN ('cancelled', 'completed', 'rejected')
                    AND (
                        (pickup_date BETWEEN ? AND ?) OR
                        (return_date BETWEEN ? AND ?) OR
                        (? BETWEEN pickup_date AND return_date) OR
                        (? BETWEEN pickup_date AND return_date)
                    )";
            
            $params = [$vehicleId, $pickupDate, $returnDate, $pickupDate, $returnDate, $pickupDate, $returnDate];
            
            if ($excludeBookingId) {
                $sql .= " AND booking_id != ?";
                $params[] = $excludeBookingId;
            }
            
            error_log('SQL: ' . $sql);
            error_log('Params: ' . print_r($params, true));
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log('ERROR: Failed to prepare availability check statement');
                error_log('PDO Error: ' . print_r($conn->errorInfo(), true));
                return false;
            }
            
            $result = $stmt->execute($params);
            if (!$result) {
                error_log('ERROR: Failed to execute availability check');
                error_log('Statement Error: ' . print_r($stmt->errorInfo(), true));
                return false;
            }
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $conflicts = $row['conflicts'] ?? 0;
            error_log('Conflicts found: ' . $conflicts);
            error_log('Available: ' . ($conflicts == 0 ? 'YES' : 'NO'));
            
            return $conflicts == 0;
        } catch (Exception $e) {
            error_log('Check vehicle availability error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }
}
